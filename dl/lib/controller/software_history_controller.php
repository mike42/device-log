<?php
class software_history_controller {
	public static function init() {
		core::loadClass("session");
	}

	public static function create() {
	
	}

	public static function read($id) {
		$software_history = software_history_model::get($id);
		if($software_history) {
			return array('error' => 'software_history not found');
		}
		return $software_history -> to_array_filtered(session::getRole());
	}

	public static function update($id) {
	}

	public static function delete() {
	}
}
?>