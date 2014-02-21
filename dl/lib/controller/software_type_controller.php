<?php
class software_type_controller {
	public static function init() {
		core::loadClass("session");
	}

	public static function create() {
	
	}

	public static function read($id) {
		$software_type = software_type_model::get($id);
		if($software_type) {
			return array('error' => 'software_type not found');
		}
		// $software_type -> populate_list_software();
		return $software_type -> to_array_filtered(session::getRole());
	}

	public static function update($id) {
	}

	public static function delete() {
	}
}
?>