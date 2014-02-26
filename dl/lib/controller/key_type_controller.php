<?php
class key_type_controller {
	public static function init() {
		core::loadClass("session");
		core::loadClass("key_type_model");
	}

	public static function create() {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['key_type']['create']) || core::$permission[$role]['key_type']['create'] != true) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Find fields to insert */
		$fields = array('id', 'name');
		$init = array();
		foreach($fields as $field) {
			if(isset($_POST[$field])) {
				$init["key_type.$field"] = $_POST[$field];
			}
		}
		$key_type = new key_type_model($init);
		$key_type -> insert();
		return $key_type -> to_array_filtered($role);
	}

	public static function read($id) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['key_type']['read']) || count(core::$permission[$role]['key_type']['read']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load key_type */
		$key_type = key_type_model::get($id);
		if(!$key_type) {
			return array('error' => 'key_type not found');
		}
		// $key_type -> populate_list_key();
		return $key_type -> to_array_filtered($role);
	}

	public static function update($id) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['key_type']['update']) || count(core::$permission[$role]['key_type']['update']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load key_type */
		$key_type = key_type_model::get($id);
		if(!$key_type) {
			return array('error' => 'key_type not found');
		}

		$update = false;
		if(isset($_POST['name']) && in_array('name', core::$permission[$role]['key_type']['update'])) {
			$key_type -> set_name($_POST['name']);
		}
		$key_type -> update();
	}

	public static function delete() {
	}
}
?>