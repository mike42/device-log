<?php
class technician_controller {
	public static function init() {
		core::loadClass("session");
	}

	public static function create() {
	
	}

	public static function read($id) {
		$technician = technician_model::get($id);
		if($technician) {
			return array('error' => 'technician not found');
		}
		// $technician -> populate_list_software_history();
		// $technician -> populate_list_key_history();
		// $technician -> populate_list_device_history();
		return $technician -> to_array_filtered(session::getRole());
	}

	public static function update($id) {
	}

	public static function delete() {
	}
}
?>