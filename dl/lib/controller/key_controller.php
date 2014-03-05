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
		$received = json_decode(file_get_contents('php://input'), true, 2);
		foreach($fields as $field) {
			if(isset($received[$field])) {
				$init["key.$field"] = $received[$field];
			}
		}
			$key = new key_model($init);

		/* Check parent tables */
		if(!person_model::get($key -> get_person_id())) {
			return array('error' => 'key is invalid because related person does not exist', 'code' => '400');
		}
		if(!key_type_model::get($key -> get_key_type_id())) {
			return array('error' => 'key is invalid because related key_type does not exist', 'code' => '400');
		}
		if(!key_status_model::get($key -> get_key_status_id())) {
			return array('error' => 'key is invalid because related key_status does not exist', 'code' => '400');
		}

		/* Insert new row */
		try {
			$key -> insert();
			return $key -> to_array_filtered($role);
		} catch(Exception $e) {
			return array('error' => 'Failed to add to database', 'code' => '500');
		}
	}

	public static function read($id = null) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['key']['read']) || count(core::$permission[$role]['key']['read']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load key */
		$key = key_model::get($id);
		if(!$key) {
			return array('error' => 'key not found', 'code' => '404');
		}
		// $key -> populate_list_key_history();
		return $key -> to_array_filtered($role);
	}

	public static function update($id = null) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['key']['update']) || count(core::$permission[$role]['key']['update']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load key */
		$key = key_model::get($id);
		if(!$key) {
			return array('error' => 'key not found', 'code' => '404');
		}

		/* Find fields to update */
		$update = false;
		$received = json_decode(file_get_contents('php://input'), true);
		if(isset($received['serial']) && in_array('serial', core::$permission[$role]['key']['update'])) {
			$key -> set_serial($received['serial']);
		}
		if(isset($received['person_id']) && in_array('person_id', core::$permission[$role]['key']['update'])) {
			$key -> set_person_id($received['person_id']);
		}
		if(isset($received['is_spare']) && in_array('is_spare', core::$permission[$role]['key']['update'])) {
			$key -> set_is_spare($received['is_spare']);
		}
		if(isset($received['key_type_id']) && in_array('key_type_id', core::$permission[$role]['key']['update'])) {
			$key -> set_key_type_id($received['key_type_id']);
		}
		if(isset($received['key_status_id']) && in_array('key_status_id', core::$permission[$role]['key']['update'])) {
			$key -> set_key_status_id($received['key_status_id']);
		}

		/* Check parent tables */
		if(!person_model::get($key -> get_person_id())) {
			return array('error' => 'key is invalid because related person does not exist', 'code' => '400');
		}
		if(!key_type_model::get($key -> get_key_type_id())) {
			return array('error' => 'key is invalid because related key_type does not exist', 'code' => '400');
		}
		if(!key_status_model::get($key -> get_key_status_id())) {
			return array('error' => 'key is invalid because related key_status does not exist', 'code' => '400');
		}

		/* Update the row */
		try {
			$key -> update();
			return $key -> to_array_filtered($role);
		} catch(Exception $e) {
			return array('error' => 'Failed to update row', 'code' => '500');
		}
	}

	public static function delete($id = null) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['key']['delete']) || core::$permission[$role]['key']['delete'] != true) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load key */
		$key = key_model::get($id);
		if(!$key) {
			return array('error' => 'key not found', 'code' => '404');
		}

		/* Check for child rows */
		$key -> populate_list_key_history(0, 1);
		if(count($key -> list_key_history) > 0) {
			return array('error' => 'Cannot delete key because of a related key_history entry', 'code' => '400');
		}

		/* Delete it */
		try {
			$key -> delete();
			return array('success' => 'yes');
		} catch(Exception $e) {
			return array('error' => 'Failed to delete', 'code' => '500');
		}
	}

	public static function list_all($page = 1, $itemspp = 20) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['key']['read']) || count(core::$permission[$role]['key']['read']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}
		if($page < 1 || $itemspp < 1) {
			return array('error' => 'Invalid page number or item count', 'code' => '400');
		}

		/* Retrieve and filter rows */
		try {
			$key_list = key_model::list_all(($page - 1) * $itemspp, $itemspp);
			$ret = array();
			foreach($key_list as $key) {
				$ret[] = $key -> to_array_filtered($role);
			}
			return $ret;
		} catch(Exception $e) {
			return array('error' => 'Failed to list', 'code' => '500');
		}
	}
}
?>