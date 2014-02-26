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
		foreach($fields as $field) {
			if(isset($_POST[$field])) {
				$init["device_photo.$field"] = $_POST[$field];
			}
		}
		$device_photo = new device_photo_model($init);
		$device_photo -> insert();
		return $device_photo -> to_array_filtered($role);
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

		$update = false;
		if(isset($_POST['checksum']) && in_array('checksum', core::$permission[$role]['device_photo']['update'])) {
			$device_photo -> set_checksum($_POST['checksum']);
		}
		if(isset($_POST['filename']) && in_array('filename', core::$permission[$role]['device_photo']['update'])) {
			$device_photo -> set_filename($_POST['filename']);
		}
		if(isset($_POST['device_history_id']) && in_array('device_history_id', core::$permission[$role]['device_photo']['update'])) {
			$device_photo -> set_device_history_id($_POST['device_history_id']);
		}
		$device_photo -> update();
	}

	public static function delete() {
	}
}
?>