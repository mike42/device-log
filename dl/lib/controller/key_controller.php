<?php
class key_controller {
	public static function init() {
		core::loadClass("session");
		core::loadClass("key_model");
	}

	public static function create() {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['key']['create']) || core::$permission[$role]['key']['create'] != true) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Find fields to insert */
		$fields = array('id', 'serial', 'person_id', 'is_spare', 'key_type_id', 'key_status_id');
		$init = array();
		foreach($fields as $field) {
			if(isset($_POST[$field])) {
				$init["key.$field"] = $_POST[$field];
			}
		}
		$key = new key_model($init);
		$key -> insert();
		return $key -> to_array_filtered($role);
	}

	public static function read($id) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['key']['read']) || count(core::$permission[$role]['key']['read']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load key */
		$key = key_model::get($id);
		if(!$key) {
			return array('error' => 'key not found');
		}
		// $key -> populate_list_key_history();
		return $key -> to_array_filtered($role);
	}

	public static function update($id) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['key']['update']) || count(core::$permission[$role]['key']['update']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load key */
		$key = key_model::get($id);
		if(!$key) {
			return array('error' => 'key not found');
		}

		$update = false;
		if(isset($_POST['serial']) && in_array('serial', core::$permission[$role]['key']['update'])) {
			$key -> set_serial($_POST['serial']);
		}
		if(isset($_POST['person_id']) && in_array('person_id', core::$permission[$role]['key']['update'])) {
			$key -> set_person_id($_POST['person_id']);
		}
		if(isset($_POST['is_spare']) && in_array('is_spare', core::$permission[$role]['key']['update'])) {
			$key -> set_is_spare($_POST['is_spare']);
		}
		if(isset($_POST['key_type_id']) && in_array('key_type_id', core::$permission[$role]['key']['update'])) {
			$key -> set_key_type_id($_POST['key_type_id']);
		}
		if(isset($_POST['key_status_id']) && in_array('key_status_id', core::$permission[$role]['key']['update'])) {
			$key -> set_key_status_id($_POST['key_status_id']);
		}
		$key -> update();
	}

	public static function delete() {
	}
}
?>