<?php
class device_photo_controller {
	public static function init() {
		core::loadClass("session");
	}

	public static function create() {
	
	}

	public static function read($id) {
		$device_photo = device_photo_model::get($id);
		if($device_photo) {
			return array('error' => 'device_photo not found');
		}
		return $device_photo -> to_array_filtered(session::getRole());
	}

	public static function update($id) {
	}

	public static function delete() {
	}
}
?>