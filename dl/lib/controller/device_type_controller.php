<?php
class device_type_controller {
	public static function init() {
		core::loadClass("session");
		core::loadClass("device_type_model");
	}

	public static function create() {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['device_type']['create']) || core::$permission[$role]['device_type']['create'] != true) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Find fields to insert */
		$fields = array('id', 'name', 'model_no');
		$init = array();
		$received = json_decode(file_get_contents('php://input'), true, 2);
		foreach($fields as $field) {
			if(isset($received[$field])) {
				$init["device_type.$field"] = $received[$field];
			}
		}
			$device_type = new device_type_model($init);


		
		/* Insert new row */
		try {
			$device_type -> insert();
			return $device_type -> to_array_filtered($role);
		} catch(Exception $e) {
			return array('error' => 'Failed to add to database', 'code' => '500');
		}
	}

	public static function read($id) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['device_type']['read']) || count(core::$permission[$role]['device_type']['read']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load device_type */
		$device_type = device_type_model::get($id);
		if(!$device_type) {
			return array('error' => 'device_type not found');
		}
		// $device_type -> populate_list_device();
		return $device_type -> to_array_filtered($role);
	}

	public static function update($id) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['device_type']['update']) || count(core::$permission[$role]['device_type']['update']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load device_type */
		$device_type = device_type_model::get($id);
		if(!$device_type) {
			return array('error' => 'device_type not found');
		}

		/* Find fields to update */
		$update = false;
		$received = json_decode(file_get_contents('php://input'), true, 2);
		if(isset($received['name']) && in_array('name', core::$permission[$role]['device_type']['update'])) {
			$device_type -> set_name($received['name']);
		}
		if(isset($received['model_no']) && in_array('model_no', core::$permission[$role]['device_type']['update'])) {
			$device_type -> set_model_no($received['model_no']);
		}
		$device_type -> update();
	}

	public static function delete() {
		/* Check permission */
		if(!isset(core::$permission[$role]['device_type']['delete']) || core::$permission[$role]['device_type']['delete'] != true) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Find fields for lookup */
		$received = json_decode(file_get_contents('php://input'), true, 2);
		if(!isset($received['id'])) {
			return array('error' => 'id was not set', 'code' => '404');
		}
		$id = $received['id'];

		/* Load device_type */
		$device_type = device_type_model::get($id);
		if(!$device_type) {
			return array('error' => 'device_type not found');
		}

		/* Check for child rows */
		$device_type -> populate_list_device(0, 1);
		if(count($device_type -> list_device) > 0) {
			return array('error' => 'Cannot delete device_type because of a related device entry', 'code' => '400');
		}

		/* Delete it */
		try {
			$device_type -> delete();
		} catch(Exception $e) {
			return array('error' => 'Failed to delete', 'code' => '500');
		}
	}
}
?>