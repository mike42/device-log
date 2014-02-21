<?php
class person_controller {
	public static function init() {
		core::loadClass("session");
	}

	public static function create() {
	
	}

	public static function read($id) {
		$person = person_model::get($id);
		if($person) {
			return array('error' => 'person not found');
		}
		// $person -> populate_list_device();
		// $person -> populate_list_software();
		// $person -> populate_list_software_history();
		// $person -> populate_list_key();
		// $person -> populate_list_key_history();
		// $person -> populate_list_device_history();
		return $person -> to_array_filtered(session::getRole());
	}

	public static function update($id) {
	}

	public static function delete() {
	}
}
?>