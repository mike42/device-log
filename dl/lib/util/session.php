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
	 * Authenticate a user.
	 * 
	 * @param string $username
	 * @param string $password
	 */
	public static function authenticate($login, $password) {
		core::loadClass('technician_model');
		$technician = technician_model::get_by_technician_login($login);
		if(!$technician) {
			return false;
		}
		
		$_SESSION['username'] = $username;
		$_SESSION['role'] = 'user';		
		return true;
	}

	/**
	 * End the session
	 */
	public static function logout() {
		session_destroy();
	}
}
