<?php
class key_status_controller {
	public static function init() {
		core::loadClass("session");
	}

	public static function create() {
	
	}

	public static function read($id) {
		$key_status = key_status_model::get($id);
		if($key_status) {
			return array('error' => 'key_status not found');
		}
		// $key_status -> populate_list_key();
		// $key_status -> populate_list_key_history();
		return $key_status -> to_array_filtered(session::getRole());
	}

	public static function update($id) {
	}

	public static function delete() {
	}
}
?>