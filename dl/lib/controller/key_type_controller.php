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
		$received = json_decode(file_get_contents('php://input'), true, 2);
		foreach($fields as $field) {
			if(isset($received[$field])) {
				$init["key_type.$field"] = $received[$field];
			}
		}
			$key_type = new key_type_model($init);

		/* Insert new row */
		try {
			$key_type -> insert();
			return $key_type -> to_array_filtered($role);
		} catch(Exception $e) {
			return array('error' => 'Failed to add to database', 'code' => '500');
		}
	}

	public static function read($id = null) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['key_type']['read']) || count(core::$permission[$role]['key_type']['read']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load key_type */
		$key_type = key_type_model::get($id);
		if(!$key_type) {
			return array('error' => 'key_type not found', 'code' => '404');
		}
		// $key_type -> populate_list_key();
		return $key_type -> to_array_filtered($role);
	}

	public static function update($id = null) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['key_type']['update']) || count(core::$permission[$role]['key_type']['update']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load key_type */
		$key_type = key_type_model::get($id);
		if(!$key_type) {
			return array('error' => 'key_type not found', 'code' => '404');
		}

		/* Find fields to update */
		$update = false;
		$received = json_decode(file_get_contents('php://input'), true);
		if(isset($received['name']) && in_array('name', core::$permission[$role]['key_type']['update'])) {
			$key_type -> set_name($received['name']);
		}

		/* Update the row */
		try {
			$key_type -> update();
			return $key_type -> to_array_filtered($role);
		} catch(Exception $e) {
			return array('error' => 'Failed to update row', 'code' => '500');
		}
	}

	public static function delete($id = null) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['key_type']['delete']) || core::$permission[$role]['key_type']['delete'] != true) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load key_type */
		$key_type = key_type_model::get($id);
		if(!$key_type) {
			return array('error' => 'key_type not found', 'code' => '404');
		}

		/* Check for child rows */
		$key_type -> populate_list_key(0, 1);
		if(count($key_type -> list_key) > 0) {
			return array('error' => 'Cannot delete key_type because of a related key entry', 'code' => '400');
		}

		/* Delete it */
		try {
			$key_type -> delete();
			return array('success' => 'yes');
		} catch(Exception $e) {
			return array('error' => 'Failed to delete', 'code' => '500');
		}
	}

	public static function list_all($page = 1, $itemspp = 20) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['key_type']['read']) || count(core::$permission[$role]['key_type']['read']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}
		if((int)$page < 1 || (int)$itemspp < 1) {
			return array('error' => 'Invalid page number or item count', 'code' => '400');
		}

		/* Retrieve and filter rows */
		try {
			$key_type_list = key_type_model::list_all(($page - 1) * $itemspp, $itemspp);
			$ret = array();
			foreach($key_type_list as $key_type) {
				$ret[] = $key_type -> to_array_filtered($role);
			}
			return $ret;
		} catch(Exception $e) {
			return array('error' => 'Failed to list', 'code' => '500');
		}
	}
}
?>