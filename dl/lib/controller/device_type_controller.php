<?php
class device_type_controller {
	public static function init() {
		core::loadClass("session");
	}

	public static function create() {
	
	}

	public static function read($id) {
		$device_type = device_type_model::get($id);
		if($device_type) {
			return array('error' => 'device_type not found');
		}
		// $device_type -> populate_list_device();
		return $device_type -> to_array_filtered(session::getRole());
	}

	public static function update($id) {
	}

	public static function delete() {
	}
}
?>