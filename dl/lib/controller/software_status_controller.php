<?php
class software_status_controller {
	public static function init() {
		core::loadClass("session");
		core::loadClass("software_status_model");
	}

	public static function create() {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['software_status']['create']) || core::$permission[$role]['software_status']['create'] != true) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Find fields to insert */
		$fields = array('id', 'tag');
		$init = array();
		$received = json_decode(file_get_contents('php://input'), true, 2);
		foreach($fields as $field) {
			if(isset($received[$field])) {
				$init["software_status.$field"] = $received[$field];
			}
		}
			$software_status = new software_status_model($init);


		
		/* Insert new row */
		try {
			$software_status -> insert();
			return $software_status -> to_array_filtered($role);
		} catch(Exception $e) {
			return array('error' => 'Failed to add to database', 'code' => '500');
		}
	}

	public static function read($id) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['software_status']['read']) || count(core::$permission[$role]['software_status']['read']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load software_status */
		$software_status = software_status_model::get($id);
		if(!$software_status) {
			return array('error' => 'software_status not found');
		}
		// $software_status -> populate_list_software();
		// $software_status -> populate_list_software_history();
		return $software_status -> to_array_filtered($role);
	}

	public static function update($id) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['software_status']['update']) || count(core::$permission[$role]['software_status']['update']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load software_status */
		$software_status = software_status_model::get($id);
		if(!$software_status) {
			return array('error' => 'software_status not found');
		}

		/* Find fields to update */
		$update = false;
		$received = json_decode(file_get_contents('php://input'), true, 2);
		if(isset($received['tag']) && in_array('tag', core::$permission[$role]['software_status']['update'])) {
			$software_status -> set_tag($received['tag']);
		}
		$software_status -> update();
	}

	public static function delete() {
		/* Check permission */
		if(!isset(core::$permission[$role]['software_status']['delete']) || core::$permission[$role]['software_status']['delete'] != true) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Find fields for lookup */
		$received = json_decode(file_get_contents('php://input'), true, 2);
		if(!isset($received['id'])) {
			return array('error' => 'id was not set', 'code' => '404');
		}
		$id = $received['id'];

		/* Load software_status */
		$software_status = software_status_model::get($id);
		if(!$software_status) {
			return array('error' => 'software_status not found');
		}

		/* Check for child rows */
		$software_status -> populate_list_software(0, 1);
		if(count($software_status -> list_software) > 0) {
			return array('error' => 'Cannot delete software_status because of a related software entry', 'code' => '400');
		}
		$software_status -> populate_list_software_history(0, 1);
		if(count($software_status -> list_software_history) > 0) {
			return array('error' => 'Cannot delete software_status because of a related software_history entry', 'code' => '400');
		}

		/* Delete it */
		try {
			$software_status -> delete();
		} catch(Exception $e) {
			return array('error' => 'Failed to delete', 'code' => '500');
		}
	}
}
?>