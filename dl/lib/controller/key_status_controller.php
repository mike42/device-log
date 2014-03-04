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
		$received = json_decode(file_get_contents('php://input'), true, 2);
		foreach($fields as $field) {
			if(isset($received[$field])) {
				$init["key_status.$field"] = $received[$field];
			}
		}
			$key_status = new key_status_model($init);


		
		/* Insert new row */
		try {
			$key_status -> insert();
			return $key_status -> to_array_filtered($role);
		} catch(Exception $e) {
			return array('error' => 'Failed to add to database', 'code' => '500');
		}
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

		/* Find fields to update */
		$update = false;
		$received = json_decode(file_get_contents('php://input'), true, 2);
		if(isset($received['name']) && in_array('name', core::$permission[$role]['key_status']['update'])) {
			$key_status -> set_name($received['name']);
		}
		$key_status -> update();
	}

	public static function delete() {
		/* Check permission */
		if(!isset(core::$permission[$role]['key_status']['delete']) || core::$permission[$role]['key_status']['delete'] != true) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Find fields for lookup */
		$received = json_decode(file_get_contents('php://input'), true, 2);
		if(!isset($received['id'])) {
			return array('error' => 'id was not set', 'code' => '404');
		}
		$id = $received['id'];

		/* Load key_status */
		$key_status = key_status_model::get($id);
		if(!$key_status) {
			return array('error' => 'key_status not found');
		}

		/* Check for child rows */
		$key_status -> populate_list_key(0, 1);
		if(count($key_status -> list_key) > 0) {
			return array('error' => 'Cannot delete key_status because of a related key entry', 'code' => '400');
		}
		$key_status -> populate_list_key_history(0, 1);
		if(count($key_status -> list_key_history) > 0) {
			return array('error' => 'Cannot delete key_status because of a related key_history entry', 'code' => '400');
		}

		/* Delete it */
		try {
			$key_status -> delete();
		} catch(Exception $e) {
			return array('error' => 'Failed to delete', 'code' => '500');
		}
	}
}
?>