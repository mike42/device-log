<?php
class software_status_controller {
	public static function init() {
		core::loadClass("session");
	}

	public static function create() {
	
	}

	public static function read($id) {
		$software_status = software_status_model::get($id);
		if($software_status) {
			return array('error' => 'software_status not found');
		}
		// $software_status -> populate_list_software();
		// $software_status -> populate_list_software_history();
		return $software_status -> to_array_filtered(session::getRole());
	}

	public static function update($id) {
	}

	public static function delete() {
	}
}
?>