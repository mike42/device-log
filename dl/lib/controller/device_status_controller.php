<?php
class device_status_controller {
	public static function init() {
		core::loadClass("session");
		core::loadClass("device_status_model");
	}

	public static function create() {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['device_status']['create']) || core::$permission[$role]['device_status']['create'] != true) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Find fields to insert */
		$fields = array('id', 'tag');
		$init = array();
		$received = json_decode(file_get_contents('php://input'), true, 2);
		foreach($fields as $field) {
			if(isset($received[$field])) {
				$init["device_status.$field"] = $received[$field];
			}
		}
			$device_status = new device_status_model($init);

		/* Insert new row */
		try {
			$device_status -> insert();
			return $device_status -> to_array_filtered($role);
		} catch(Exception $e) {
			return array('error' => 'Failed to add to database', 'code' => '500');
		}
	}

	public static function read($id) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['device_status']['read']) || count(core::$permission[$role]['device_status']['read']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load device_status */
		$device_status = device_status_model::get($id);
		if(!$device_status) {
			return array('error' => 'device_status not found', 'code' => '404');
		}
		// $device_status -> populate_list_device();
		// $device_status -> populate_list_device_history();
		return $device_status -> to_array_filtered($role);
	}

	public static function update($id) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['device_status']['update']) || count(core::$permission[$role]['device_status']['update']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load device_status */
		$device_status = device_status_model::get($id);
		if(!$device_status) {
			return array('error' => 'device_status not found', 'code' => '404');
		}

		/* Find fields to update */
		$update = false;
		$received = json_decode(file_get_contents('php://input'), true);
		if(isset($received['tag']) && in_array('tag', core::$permission[$role]['device_status']['update'])) {
			$device_status -> set_tag($received['tag']);
		}

		/* Update the row */
		try {
			$device_status -> update();
			return $device_status -> to_array_filtered($role);
		} catch(Exception $e) {
			return array('error' => 'Failed to update row', 'code' => '500');
		}
	}

	public static function delete($id) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['device_status']['delete']) || core::$permission[$role]['device_status']['delete'] != true) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load device_status */
		$device_status = device_status_model::get($id);
		if(!$device_status) {
			return array('error' => 'device_status not found', 'code' => '404');
		}

		/* Check for child rows */
		$device_status -> populate_list_device(0, 1);
		if(count($device_status -> list_device) > 0) {
			return array('error' => 'Cannot delete device_status because of a related device entry', 'code' => '400');
		}
		$device_status -> populate_list_device_history(0, 1);
		if(count($device_status -> list_device_history) > 0) {
			return array('error' => 'Cannot delete device_status because of a related device_history entry', 'code' => '400');
		}

		/* Delete it */
		try {
			$device_status -> delete();
			return array('success' => 'yes');
		} catch(Exception $e) {
			return array('error' => 'Failed to delete', 'code' => '500');
		}
	}
}
?>