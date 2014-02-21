<?php
class key_controller {
	public static function init() {
		core::loadClass("session");
	}

	public static function create() {
	
	}

	public static function read($id) {
		$key = key_model::get($id);
		if($key) {
			return array('error' => 'key not found');
		}
		// $key -> populate_list_key_history();
		return $key -> to_array_filtered(session::getRole());
	}

	public static function update($id) {
	}

	public static function delete() {
	}
}
?>