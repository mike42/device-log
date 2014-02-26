<?php
class technician_controller {
	public static function init() {
		core::loadClass("session");
		core::loadClass("technician_model");
	}

	public static function create() {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['technician']['create']) || core::$permission[$role]['technician']['create'] != true) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Find fields to insert */
		$fields = array('id', 'login', 'name');
		$init = array();
		foreach($fields as $field) {
			if(isset($_POST[$field])) {
				$init["technician.$field"] = $_POST[$field];
			}
		}
		$technician = new technician_model($init);
		$technician -> insert();
		return $technician -> to_array_filtered($role);
	}

	public static function read($id) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['technician']['read']) || count(core::$permission[$role]['technician']['read']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load technician */
		$technician = technician_model::get($id);
		if(!$technician) {
			return array('error' => 'technician not found');
		}
		// $technician -> populate_list_software_history();
		// $technician -> populate_list_key_history();
		// $technician -> populate_list_device_history();
		return $technician -> to_array_filtered($role);
	}

	public static function update($id) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['technician']['update']) || count(core::$permission[$role]['technician']['update']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load technician */
		$technician = technician_model::get($id);
		if(!$technician) {
			return array('error' => 'technician not found');
		}

		$update = false;
		if(isset($_POST['login']) && in_array('login', core::$permission[$role]['technician']['update'])) {
			$technician -> set_login($_POST['login']);
		}
		if(isset($_POST['name']) && in_array('name', core::$permission[$role]['technician']['update'])) {
			$technician -> set_name($_POST['name']);
		}
		$technician -> update();
	}

	public static function delete() {
	}
}
?>