<?php
class device_controller {
	public static function init() {
		core::loadClass("session");
	}

	public static function create() {
	
	}

	public static function read($id) {
		$device = device_model::get($id);
		if($device) {
			return array('error' => 'device not found');
		}
		// $device -> populate_list_device_history();
		return $device -> to_array_filtered(session::getRole());
	}

	public static function update($id) {
	}

	public static function delete() {
	}
}
?>