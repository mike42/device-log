<?php
class key_history_controller {
	public static function init() {
		core::loadClass("session");
	}

	public static function create() {
	
	}

	public static function read($id) {
		$key_history = key_history_model::get($id);
		if($key_history) {
			return array('error' => 'key_history not found');
		}
		return $key_history -> to_array_filtered(session::getRole());
	}

	public static function update($id) {
	}

	public static function delete() {
	}
}
?>