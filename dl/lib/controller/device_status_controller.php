<?php
class device_status_controller {
	public static function init() {
		core::loadClass("session");
	}

	public static function create() {
	
	}

	public static function read($id) {
		$device_status = device_status_model::get($id);
		if($device_status) {
			return array('error' => 'device_status not found');
		}
		// $device_status -> populate_list_device();
		// $device_status -> populate_list_device_history();
		return $device_status -> to_array_filtered(session::getRole());
	}

	public static function update($id) {
	}

	public static function delete() {
	}
}
?>