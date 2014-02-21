<?php
class key_type_controller {
	public static function init() {
		core::loadClass("session");
	}

	public static function create() {
	
	}

	public static function read($id) {
		$key_type = key_type_model::get($id);
		if($key_type) {
			return array('error' => 'key_type not found');
		}
		// $key_type -> populate_list_key();
		return $key_type -> to_array_filtered(session::getRole());
	}

	public static function update($id) {
	}

	public static function delete() {
	}
}
?>