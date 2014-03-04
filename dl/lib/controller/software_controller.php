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
		$received = json_decode(file_get_contents('php://input'), true, 2);
		foreach($fields as $field) {
			if(isset($received[$field])) {
				$init["software.$field"] = $received[$field];
			}
		}
			$software = new software_model($init);


				/* Check parent tables */
		if(!software_type_model::get($software -> get_software_type_id())) {
			return array('error' => 'Cannot add because related software_type does not exist', 'code' => '400');
		}
		if(!software_status_model::get($software -> get_software_status_id())) {
			return array('error' => 'Cannot add because related software_status does not exist', 'code' => '400');
		}
		if(!person_model::get($software -> get_person_id())) {
			return array('error' => 'Cannot add because related person does not exist', 'code' => '400');
		}

		/* Insert new row */
		try {
			$software -> insert();
			return $software -> to_array_filtered($role);
		} catch(Exception $e) {
			return array('error' => 'Failed to add to database', 'code' => '500');
		}
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

		/* Find fields to update */
		$update = false;
		$received = json_decode(file_get_contents('php://input'), true, 2);
		if(isset($received['code']) && in_array('code', core::$permission[$role]['software']['update'])) {
			$software -> set_code($received['code']);
		}
		if(isset($received['software_type_id']) && in_array('software_type_id', core::$permission[$role]['software']['update'])) {
			$software -> set_software_type_id($received['software_type_id']);
		}
		if(isset($received['software_status_id']) && in_array('software_status_id', core::$permission[$role]['software']['update'])) {
			$software -> set_software_status_id($received['software_status_id']);
		}
		if(isset($received['person_id']) && in_array('person_id', core::$permission[$role]['software']['update'])) {
			$software -> set_person_id($received['person_id']);
		}
		if(isset($received['is_bought']) && in_array('is_bought', core::$permission[$role]['software']['update'])) {
			$software -> set_is_bought($received['is_bought']);
		}
		$software -> update();
	}

	public static function delete() {
		/* Check permission */
		if(!isset(core::$permission[$role]['software']['delete']) || core::$permission[$role]['software']['delete'] != true) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Find fields for lookup */
		$received = json_decode(file_get_contents('php://input'), true, 2);
		if(!isset($received['id'])) {
			return array('error' => 'id was not set', 'code' => '404');
		}
		$id = $received['id'];

		/* Load software */
		$software = software_model::get($id);
		if(!$software) {
			return array('error' => 'software not found');
		}

		/* Check for child rows */
		$software -> populate_list_software_history(0, 1);
		if(count($software -> list_software_history) > 0) {
			return array('error' => 'Cannot delete software because of a related software_history entry', 'code' => '400');
		}

		/* Delete it */
		try {
			$software -> delete();
		} catch(Exception $e) {
			return array('error' => 'Failed to delete', 'code' => '500');
		}
	}
}
?>