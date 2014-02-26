<?php
class software_controller {
	public static function init() {
		core::loadClass("session");
		core::loadClass("software_model");
	}

	public static function create() {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['software']['create']) || core::$permission[$role]['software']['create'] != true) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Find fields to insert */
		$fields = array('id', 'code', 'software_type_id', 'software_status_id', 'person_id', 'is_bought');
		$init = array();
		foreach($fields as $field) {
			if(isset($_POST[$field])) {
				$init["software.$field"] = $_POST[$field];
			}
		}
		$software = new software_model($init);
		$software -> insert();
		return $software -> to_array_filtered($role);
	}

	public static function read($id) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['software']['read']) || count(core::$permission[$role]['software']['read']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load software */
		$software = software_model::get($id);
		if(!$software) {
			return array('error' => 'software not found');
		}
		// $software -> populate_list_software_history();
		return $software -> to_array_filtered($role);
	}

	public static function update($id) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['software']['update']) || count(core::$permission[$role]['software']['update']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load software */
		$software = software_model::get($id);
		if(!$software) {
			return array('error' => 'software not found');
		}

		$update = false;
		if(isset($_POST['code']) && in_array('code', core::$permission[$role]['software']['update'])) {
			$software -> set_code($_POST['code']);
		}
		if(isset($_POST['software_type_id']) && in_array('software_type_id', core::$permission[$role]['software']['update'])) {
			$software -> set_software_type_id($_POST['software_type_id']);
		}
		if(isset($_POST['software_status_id']) && in_array('software_status_id', core::$permission[$role]['software']['update'])) {
			$software -> set_software_status_id($_POST['software_status_id']);
		}
		if(isset($_POST['person_id']) && in_array('person_id', core::$permission[$role]['software']['update'])) {
			$software -> set_person_id($_POST['person_id']);
		}
		if(isset($_POST['is_bought']) && in_array('is_bought', core::$permission[$role]['software']['update'])) {
			$software -> set_is_bought($_POST['is_bought']);
		}
		$software -> update();
	}

	public static function delete() {
	}
}
?>