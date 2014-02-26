<?php
class key_status_controller {
	public static function init() {
		core::loadClass("session");
		core::loadClass("key_status_model");
	}

	public static function create() {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['key_status']['create']) || core::$permission[$role]['key_status']['create'] != true) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Find fields to insert */
		$fields = array('id', 'name');
		$init = array();
		foreach($fields as $field) {
			if(isset($_POST[$field])) {
				$init["key_status.$field"] = $_POST[$field];
			}
		}
		$key_status = new key_status_model($init);
		$key_status -> insert();
		return $key_status -> to_array_filtered($role);
	}

	public static function read($id) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['key_status']['read']) || count(core::$permission[$role]['key_status']['read']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load key_status */
		$key_status = key_status_model::get($id);
		if(!$key_status) {
			return array('error' => 'key_status not found');
		}
		// $key_status -> populate_list_key();
		// $key_status -> populate_list_key_history();
		return $key_status -> to_array_filtered($role);
	}

	public static function update($id) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['key_status']['update']) || count(core::$permission[$role]['key_status']['update']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load key_status */
		$key_status = key_status_model::get($id);
		if(!$key_status) {
			return array('error' => 'key_status not found');
		}

		$update = false;
		if(isset($_POST['name']) && in_array('name', core::$permission[$role]['key_status']['update'])) {
			$key_status -> set_name($_POST['name']);
		}
		$key_status -> update();
	}

	public static function delete() {
	}
}
?>