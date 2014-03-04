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

	public static function read($id) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['person']['read']) || count(core::$permission[$role]['person']['read']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load person */
		$person = person_model::get($id);
		if(!$person) {
			return array('error' => 'person not found');
		}
		// $person -> populate_list_device();
		// $person -> populate_list_software();
		// $person -> populate_list_software_history();
		// $person -> populate_list_key();
		// $person -> populate_list_key_history();
		// $person -> populate_list_device_history();
		return $person -> to_array_filtered($role);
	}

	public static function update($id) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['person']['update']) || count(core::$permission[$role]['person']['update']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load person */
		$person = person_model::get($id);
		if(!$person) {
			return array('error' => 'person not found');
		}

		/* Find fields to update */
		$update = false;
		$received = json_decode(file_get_contents('php://input'), true, 2);
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
		$person -> update();
	}

	public static function delete() {
		/* Check permission */
		if(!isset(core::$permission[$role]['person']['delete']) || core::$permission[$role]['person']['delete'] != true) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Find fields for lookup */
		$received = json_decode(file_get_contents('php://input'), true, 2);
		if(!isset($received['id'])) {
			return array('error' => 'id was not set', 'code' => '404');
		}
		$id = $received['id'];

		/* Load person */
		$person = person_model::get($id);
		if(!$person) {
			return array('error' => 'person not found');
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
		$person -> populate_list_key(0, 1);
		if(count($person -> list_key) > 0) {
			return array('error' => 'Cannot delete person because of a related key entry', 'code' => '400');
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
		} catch(Exception $e) {
			return array('error' => 'Failed to delete', 'code' => '500');
		}
	}
}
?>