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
		foreach($fields as $field) {
			if(isset($_POST[$field])) {
				$init["person.$field"] = $_POST[$field];
			}
		}
		$person = new person_model($init);
		$person -> insert();
		return $person -> to_array_filtered($role);
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

		$update = false;
		if(isset($_POST['code']) && in_array('code', core::$permission[$role]['person']['update'])) {
			$person -> set_code($_POST['code']);
		}
		if(isset($_POST['is_staff']) && in_array('is_staff', core::$permission[$role]['person']['update'])) {
			$person -> set_is_staff($_POST['is_staff']);
		}
		if(isset($_POST['is_active']) && in_array('is_active', core::$permission[$role]['person']['update'])) {
			$person -> set_is_active($_POST['is_active']);
		}
		if(isset($_POST['firstname']) && in_array('firstname', core::$permission[$role]['person']['update'])) {
			$person -> set_firstname($_POST['firstname']);
		}
		if(isset($_POST['surname']) && in_array('surname', core::$permission[$role]['person']['update'])) {
			$person -> set_surname($_POST['surname']);
		}
		$person -> update();
	}

	public static function delete() {
	}
}
?>