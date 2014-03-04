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
		$received = json_decode(file_get_contents('php://input'), true, 2);
		foreach($fields as $field) {
			if(isset($received[$field])) {
				$init["software_type.$field"] = $received[$field];
			}
		}
			$software_type = new software_type_model($init);


		
		/* Insert new row */
		try {
			$software_type -> insert();
			return $software_type -> to_array_filtered($role);
		} catch(Exception $e) {
			return array('error' => 'Failed to add to database', 'code' => '500');
		}
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

		/* Find fields to update */
		$update = false;
		$received = json_decode(file_get_contents('php://input'), true, 2);
		if(isset($received['name']) && in_array('name', core::$permission[$role]['software_type']['update'])) {
			$software_type -> set_name($received['name']);
		}
		$software_type -> update();
	}

	public static function delete() {
		/* Check permission */
		if(!isset(core::$permission[$role]['software_type']['delete']) || core::$permission[$role]['software_type']['delete'] != true) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Find fields for lookup */
		$received = json_decode(file_get_contents('php://input'), true, 2);
		if(!isset($received['id'])) {
			return array('error' => 'id was not set', 'code' => '404');
		}
		$id = $received['id'];

		/* Load software_type */
		$software_type = software_type_model::get($id);
		if(!$software_type) {
			return array('error' => 'software_type not found');
		}

		/* Check for child rows */
		$software_type -> populate_list_software(0, 1);
		if(count($software_type -> list_software) > 0) {
			return array('error' => 'Cannot delete software_type because of a related software entry', 'code' => '400');
		}

		/* Delete it */
		try {
			$software_type -> delete();
		} catch(Exception $e) {
			return array('error' => 'Failed to delete', 'code' => '500');
		}
	}
}
?>