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
		$fields = array('id', 'login', 'name', 'is_active');
		$init = array();
		$received = json_decode(file_get_contents('php://input'), true, 2);
		foreach($fields as $field) {
			if(isset($received[$field])) {
				$init["technician.$field"] = $received[$field];
			}
		}
			$technician = new technician_model($init);

		/* Insert new row */
		try {
			$technician -> insert();
			return $technician -> to_array_filtered($role);
		} catch(Exception $e) {
			return array('error' => 'Failed to add to database', 'code' => '500');
		}
	}

	public static function read($id = null) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['technician']['read']) || count(core::$permission[$role]['technician']['read']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load technician */
		$technician = technician_model::get($id);
		if(!$technician) {
			return array('error' => 'technician not found', 'code' => '404');
		}
		// $technician -> populate_list_software_history();
		// $technician -> populate_list_key_history();
		// $technician -> populate_list_device_history();
		return $technician -> to_array_filtered($role);
	}

	public static function update($id = null) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['technician']['update']) || count(core::$permission[$role]['technician']['update']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load technician */
		$technician = technician_model::get($id);
		if(!$technician) {
			return array('error' => 'technician not found', 'code' => '404');
		}

		/* Find fields to update */
		$update = false;
		$received = json_decode(file_get_contents('php://input'), true);
		if(isset($received['login']) && in_array('login', core::$permission[$role]['technician']['update'])) {
			$technician -> set_login($received['login']);
		}
		if(isset($received['name']) && in_array('name', core::$permission[$role]['technician']['update'])) {
			$technician -> set_name($received['name']);
		}
		if(isset($received['is_active']) && in_array('is_active', core::$permission[$role]['technician']['update'])) {
			$technician -> set_is_active($received['is_active']);
		}

		/* Update the row */
		try {
			$technician -> update();
			return $technician -> to_array_filtered($role);
		} catch(Exception $e) {
			return array('error' => 'Failed to update row', 'code' => '500');
		}
	}

	public static function delete($id = null) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['technician']['delete']) || core::$permission[$role]['technician']['delete'] != true) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load technician */
		$technician = technician_model::get($id);
		if(!$technician) {
			return array('error' => 'technician not found', 'code' => '404');
		}

		/* Check for child rows */
		$technician -> populate_list_software_history(0, 1);
		if(count($technician -> list_software_history) > 0) {
			return array('error' => 'Cannot delete technician because of a related software_history entry', 'code' => '400');
		}
		$technician -> populate_list_key_history(0, 1);
		if(count($technician -> list_key_history) > 0) {
			return array('error' => 'Cannot delete technician because of a related key_history entry', 'code' => '400');
		}
		$technician -> populate_list_device_history(0, 1);
		if(count($technician -> list_device_history) > 0) {
			return array('error' => 'Cannot delete technician because of a related device_history entry', 'code' => '400');
		}

		/* Delete it */
		try {
			$technician -> delete();
			return array('success' => 'yes');
		} catch(Exception $e) {
			return array('error' => 'Failed to delete', 'code' => '500');
		}
	}

	public static function list_all($page = 1, $itemspp = 20) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['technician']['read']) || count(core::$permission[$role]['technician']['read']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}
		if((int)$page < 1 || (int)$itemspp < 1) {
			$start = 0;
			$limit = -1;
		} else {
			$start = ($page - 1) * $itemspp;
			$limit = $itemspp;
		}

		/* Retrieve and filter rows */
		try {
			$technician_list = technician_model::list_all($start, $limit);
			$ret = array();
			foreach($technician_list as $technician) {
				$ret[] = $technician -> to_array_filtered($role);
			}
			return $ret;
		} catch(Exception $e) {
			return array('error' => 'Failed to list', 'code' => '500');
		}
	}
}
?>