<?php
class session {
	/**
	 * Open the session
	 */
	public static function init() {
		session_start();
	}

	/**
	 * Get the role of the current user, or 'anon' if they aren't logged in.
	 *
	 * @return string Name of the user's current role
	 */
	public static function getRole() {
		if(isset($_SESSION['role'])) {
			return $_SESSION['role'];
		}
		return "anon";
	}
	
	/**
	 * @return string Current username. Should really only be used after getRole() has been verified.
	 */
	public static function getUsername() {
		if(isset($_SESSION['username'])) {
			return $_SESSION['username'];
		}
		return "";
	}

	/**
	 * Authenticate a user.
	 *
	 * @param string $username
	 * @param string $password
	 */
	public static function authenticate($login, $password) {
		core::loadClass('technician_model');
		$technician = technician_model::get_by_technician_login($login);
		if(!$technician) {
			throw new Exception("User is not technician");
			return false;
		}

		$config = core::getConfig('login');
		$v = self::ldap_verify_credentials($config['url'], $config['domain'], $login, $password);
		if($v['success']) {
			$_SESSION['username'] = $login;
			$_SESSION['role'] = 'user';
			return true;
		}
		throw new Exception($v['message']);
		return false;
	}

	/**
	 * End the session
	 */
	public static function logout() {
		session_destroy();
	}

	/**
	 * Match username and password against ldap
	 **/
	private static function ldap_verify_credentials($url, $domain, $username, $password) {
		/* Check server setup */
		if(!function_exists('ldap_connect')) {
			return array('success' => false, 'message' => 'LDAP functions are not available');
		}
		
		/* Check login names */
		if(!self::ldap_verify_uid($username)) {
			return array('success' => false, 'message' => 'Invalid login name');
		}

		/* Password is required */
		if(trim($password) == '') {
			return array('success' => false, 'message' => 'No password given');
		}

		/* Bind ldap */
		if(!$ldap_conn = ldap_connect($url)) {
			return array('success' => false, 'message' => 'Connecting to the LDAP server failed');
		}

		/* Set to protocol v3 (seems to be required to avoid "protocol error" */
		ldap_set_option($ldap_conn, LDAP_OPT_PROTOCOL_VERSION, 3);

		/* Anonymous bind and search the user */
		if(!ldap_bind($ldap_conn)) {
			return array('success' => false, 'message' => 'Anonymous bind to LDAP failed');
		}

		$filter="(cn=$username)";

		if(!$search_res = ldap_search($ldap_conn, $domain, $filter)) {
			return array('success' => false, 'message' => 'Searching for user failed');
		}

		$info = ldap_get_entries($ldap_conn, $search_res);

		if($info["count"] < 1) {
			return array('success' => false, 'message' => 'Incorrect username or password!');
		} else if ($info['count'] > 1) {
			return array('success' => false, 'message' => 'Multiple users found with this username. You will need to delete one.');
		}

		/* One username exists. Try to bind as it */
		$dn = $info[0]['dn'];
		if(!@ldap_bind($ldap_conn, $dn, $password)) {
			return array('success' => false, 'message' => 'Incorrect username or password!');
		}

		/* Finish up */
		ldap_unbind($ldap_conn);
		return array('success' => true, 'message' => 'Login OK');
	}

	/**
	 * Return false if a username looks dodgy
	 */
	private static function ldap_verify_uid($input) {
		if($input != PREG_REPLACE("/[^0-9a-zA-Z]/i", '', $input)) {
			return false;
		}
		return true;
	}
}
