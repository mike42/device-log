<?php
class device_history_controller {
	public static function init() {
		core::loadClass("session");
	}

	public static function create() {
	
	}

	public static function read($id) {
		$device_history = device_history_model::get($id);
		if($device_history) {
			return array('error' => 'device_history not found');
		}
		// $device_history -> populate_list_device_photo();
		return $device_history -> to_array_filtered(session::getRole());
	}

	public static function update($id) {
	}

	public static function delete() {
	}
}
?>