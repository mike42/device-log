<?php
class software_controller {
	public static function init() {
		core::loadClass("session");
	}

	public static function create() {
	
	}

	public static function read($id) {
		$software = software_model::get($id);
		if($software) {
			return array('error' => 'software not found');
		}
		// $software -> populate_list_software_history();
		return $software -> to_array_filtered(session::getRole());
	}

	public static function update($id) {
	}

	public static function delete() {
	}
}
?>