<?php
class software_type_controller {
	public static function init() {
		core::loadClass("session");
		core::loadClass("software_type_model");
	}

	public static function create() {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['software_type']['create']) || core::$permission[$role]['software_type']['create'] != true) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Find fields to insert */
		$fields = array('id', 'name');
		$init = array();
		foreach($fields as $field) {
			if(isset($_POST[$field])) {
				$init["software_type.$field"] = $_POST[$field];
			}
		}
		$software_type = new software_type_model($init);
		$software_type -> insert();
		return $software_type -> to_array_filtered($role);
	}

	public static function read($id) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['software_type']['read']) || count(core::$permission[$role]['software_type']['read']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load software_type */
		$software_type = software_type_model::get($id);
		if(!$software_type) {
			return array('error' => 'software_type not found');
		}
		// $software_type -> populate_list_software();
		return $software_type -> to_array_filtered($role);
	}

	public static function update($id) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['software_type']['update']) || count(core::$permission[$role]['software_type']['update']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load software_type */
		$software_type = software_type_model::get($id);
		if(!$software_type) {
			return array('error' => 'software_type not found');
		}

		$update = false;
		if(isset($_POST['name']) && in_array('name', core::$permission[$role]['software_type']['update'])) {
			$software_type -> set_name($_POST['name']);
		}
		$software_type -> update();
	}

	public static function delete() {
	}
}
?>