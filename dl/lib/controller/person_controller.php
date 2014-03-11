<?php
class person_controller {
	public static function init() {
		core::loadClass("session");
		core::loadClass("person_model");
	}

	public static function create() {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['person']['create']) || core::$permission[$role]['person']['create'] != true) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Find fields to insert */
		$fields = array('id', 'code', 'is_staff', 'is_active', 'firstname', 'surname');
		$init = array();
		$received = json_decode(file_get_contents('php://input'), true, 2);
		foreach($fields as $field) {
			if(isset($received[$field])) {
				$init["person.$field"] = $received[$field];
			}
		}
			$person = new person_model($init);

		/* Insert new row */
		try {
			$person -> insert();
			return $person -> to_array_filtered($role);
		} catch(Exception $e) {
			return array('error' => 'Failed to add to database', 'code' => '500');
		}
	}

	public static function read($id = null) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['person']['read']) || count(core::$permission[$role]['person']['read']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load person */
		$person = person_model::get($id);
		if(!$person) {
			return array('error' => 'person not found', 'code' => '404');
		}
		$person -> populate_list_device();
		$person -> populate_list_software();
		$person -> populate_list_software_history();
		$person -> populate_list_doorkey();
		$person -> populate_list_key_history();
		$person -> populate_list_device_history();
		return $person -> to_array_filtered($role);
	}

	public static function update($id = null) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['person']['update']) || count(core::$permission[$role]['person']['update']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load person */
		$person = person_model::get($id);
		if(!$person) {
			return array('error' => 'person not found', 'code' => '404');
		}

		/* Find fields to update */
		$update = false;
		$received = json_decode(file_get_contents('php://input'), true);
		if(isset($received['code']) && in_array('code', core::$permission[$role]['person']['update'])) {
			$person -> set_code($received['code']);
		}
		if(isset($received['is_staff']) && in_array('is_staff', core::$permission[$role]['person']['update'])) {
			$person -> set_is_staff($received['is_staff']);
		}
		if(isset($received['is_active']) && in_array('is_active', core::$permission[$role]['person']['update'])) {
			$person -> set_is_active($received['is_active']);
		}
		if(isset($received['firstname']) && in_array('firstname', core::$permission[$role]['person']['update'])) {
			$person -> set_firstname($received['firstname']);
		}
		if(isset($received['surname']) && in_array('surname', core::$permission[$role]['person']['update'])) {
			$person -> set_surname($received['surname']);
		}

		/* Update the row */
		try {
			$person -> update();
			return $person -> to_array_filtered($role);
		} catch(Exception $e) {
			return array('error' => 'Failed to update row', 'code' => '500');
		}
	}

	public static function delete($id = null) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['person']['delete']) || core::$permission[$role]['person']['delete'] != true) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load person */
		$person = person_model::get($id);
		if(!$person) {
			return array('error' => 'person not found', 'code' => '404');
		}

		/* Check for child rows */
		$person -> populate_list_device(0, 1);
		if(count($person -> list_device) > 0) {
			return array('error' => 'Cannot delete person because of a related device entry', 'code' => '400');
		}
		$person -> populate_list_software(0, 1);
		if(count($person -> list_software) > 0) {
			return array('error' => 'Cannot delete person because of a related software entry', 'code' => '400');
		}
		$person -> populate_list_software_history(0, 1);
		if(count($person -> list_software_history) > 0) {
			return array('error' => 'Cannot delete person because of a related software_history entry', 'code' => '400');
		}
		$person -> populate_list_doorkey(0, 1);
		if(count($person -> list_doorkey) > 0) {
			return array('error' => 'Cannot delete person because of a related doorkey entry', 'code' => '400');
		}
		$person -> populate_list_key_history(0, 1);
		if(count($person -> list_key_history) > 0) {
			return array('error' => 'Cannot delete person because of a related key_history entry', 'code' => '400');
		}
		$person -> populate_list_device_history(0, 1);
		if(count($person -> list_device_history) > 0) {
			return array('error' => 'Cannot delete person because of a related device_history entry', 'code' => '400');
		}

		/* Delete it */
		try {
			$person -> delete();
			return array('success' => 'yes');
		} catch(Exception $e) {
			return array('error' => 'Failed to delete', 'code' => '500');
		}
	}

	public static function list_all($page = 1, $itemspp = 20) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['person']['read']) || count(core::$permission[$role]['person']['read']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}
		if((int)$page < 1 || (int)$itemspp < 1) {
			return array('error' => 'Invalid page number or item count', 'code' => '400');
		}

		/* Retrieve and filter rows */
		try {
			$person_list = person_model::list_all(($page - 1) * $itemspp, $itemspp);
			$ret = array();
			foreach($person_list as $person) {
				$ret[] = $person -> to_array_filtered($role);
			}
			return $ret;
		} catch(Exception $e) {
			return array('error' => 'Failed to list', 'code' => '500');
		}
	}

	public static function photo($id) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['person']['read']) || count(core::$permission[$role]['person']['read']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load person */
		$person = person_model::get($id);
		if(!$person) {
			return array('error' => 'person not found', 'code' => '404');
		}

		try {
			$code = preg_replace("/[^a-zA-Z0-9]+/", "", $person -> get_code());
			if(strlen($code) < 1 || strlen($code) > 5 || $code != $person -> get_code()) {
				throw new Exception("Code contains invalid characters");
			}
	
			$fn = dirname(__FILE__) . "/../../site/photos/$code.jpg";
			if(!file_exists($fn)) {
				throw new Exception("Photo does not exist");
			}

		} catch(Exception $e) {
			$fn = dirname(__FILE__) . "/../../public/profile-default.jpg";
		}

		header("content-type: image/jpeg");
		$a = fopen($fn, "r");
		fpassthru($a);
		exit(0);
	}
}
?>
