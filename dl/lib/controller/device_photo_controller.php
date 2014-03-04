<?php
class device_photo_controller {
	public static function init() {
		core::loadClass("session");
		core::loadClass("device_photo_model");
	}

	public static function create() {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['device_photo']['create']) || core::$permission[$role]['device_photo']['create'] != true) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Find fields to insert */
		$fields = array('id', 'checksum', 'filename', 'device_history_id');
		$init = array();
		$received = json_decode(file_get_contents('php://input'), true, 2);
		foreach($fields as $field) {
			if(isset($received[$field])) {
				$init["device_photo.$field"] = $received[$field];
			}
		}
			$device_photo = new device_photo_model($init);


				/* Check parent tables */
		if(!device_history_model::get($device_photo -> get_device_history_id())) {
			return array('error' => 'Cannot add because related device_history does not exist', 'code' => '400');
		}

		/* Insert new row */
		try {
			$device_photo -> insert();
			return $device_photo -> to_array_filtered($role);
		} catch(Exception $e) {
			return array('error' => 'Failed to add to database', 'code' => '500');
		}
	}

	public static function read($id) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['device_photo']['read']) || count(core::$permission[$role]['device_photo']['read']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load device_photo */
		$device_photo = device_photo_model::get($id);
		if(!$device_photo) {
			return array('error' => 'device_photo not found');
		}
		return $device_photo -> to_array_filtered($role);
	}

	public static function update($id) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['device_photo']['update']) || count(core::$permission[$role]['device_photo']['update']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load device_photo */
		$device_photo = device_photo_model::get($id);
		if(!$device_photo) {
			return array('error' => 'device_photo not found');
		}

		/* Find fields to update */
		$update = false;
		$received = json_decode(file_get_contents('php://input'), true, 2);
		if(isset($received['checksum']) && in_array('checksum', core::$permission[$role]['device_photo']['update'])) {
			$device_photo -> set_checksum($received['checksum']);
		}
		if(isset($received['filename']) && in_array('filename', core::$permission[$role]['device_photo']['update'])) {
			$device_photo -> set_filename($received['filename']);
		}
		if(isset($received['device_history_id']) && in_array('device_history_id', core::$permission[$role]['device_photo']['update'])) {
			$device_photo -> set_device_history_id($received['device_history_id']);
		}
		$device_photo -> update();
	}

	public static function delete() {
		/* Check permission */
		if(!isset(core::$permission[$role]['device_photo']['delete']) || core::$permission[$role]['device_photo']['delete'] != true) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Find fields for lookup */
		$received = json_decode(file_get_contents('php://input'), true, 2);
		if(!isset($received['id'])) {
			return array('error' => 'id was not set', 'code' => '404');
		}
		$id = $received['id'];

		/* Load device_photo */
		$device_photo = device_photo_model::get($id);
		if(!$device_photo) {
			return array('error' => 'device_photo not found');
		}


		/* Delete it */
		try {
			$device_photo -> delete();
		} catch(Exception $e) {
			return array('error' => 'Failed to delete', 'code' => '500');
		}
	}
}
?>