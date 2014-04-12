<?php
class device_photo_controller {
	public static function init() {
		core::loadClass("session");
		core::loadClass("device_photo_model");
	}

	public static function create() {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['device_photo']['create']) || core::$permission[$role]['device_photo']['create'] != true) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Find fields to insert */
		$fields = array('id', 'checksum', 'filename', 'device_history_id');
		$init = array();
		$received = json_decode(file_get_contents('php://input'), true, 2);
		foreach($fields as $field) {
			if(isset($received[$field])) {
				$init["device_photo.$field"] = $received[$field];
			}
		}
			$device_photo = new device_photo_model($init);

		/* Check parent tables */
		if(!device_history_model::get($device_photo -> get_device_history_id())) {
			return array('error' => 'device_photo is invalid because related device_history does not exist', 'code' => '400');
		}

		/* Insert new row */
		try {
			$device_photo -> insert();
			return $device_photo -> to_array_filtered($role);
		} catch(Exception $e) {
			return array('error' => 'Failed to add to database', 'code' => '500');
		}
	}

	public static function read($id = null) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['device_photo']['read']) || count(core::$permission[$role]['device_photo']['read']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load device_photo */
		$device_photo = device_photo_model::get($id);
		if(!$device_photo) {
			return array('error' => 'device_photo not found', 'code' => '404');
		}
		return $device_photo -> to_array_filtered($role);
	}

	public static function update($id = null) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['device_photo']['update']) || count(core::$permission[$role]['device_photo']['update']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load device_photo */
		$device_photo = device_photo_model::get($id);
		if(!$device_photo) {
			return array('error' => 'device_photo not found', 'code' => '404');
		}

		/* Find fields to update */
		$update = false;
		$received = json_decode(file_get_contents('php://input'), true);
		if(isset($received['checksum']) && in_array('checksum', core::$permission[$role]['device_photo']['update'])) {
			$device_photo -> set_checksum($received['checksum']);
		}
		if(isset($received['filename']) && in_array('filename', core::$permission[$role]['device_photo']['update'])) {
			$device_photo -> set_filename($received['filename']);
		}
		if(isset($received['device_history_id']) && in_array('device_history_id', core::$permission[$role]['device_photo']['update'])) {
			$device_photo -> set_device_history_id($received['device_history_id']);
		}

		/* Check parent tables */
		if(!device_history_model::get($device_photo -> get_device_history_id())) {
			return array('error' => 'device_photo is invalid because related device_history does not exist', 'code' => '400');
		}

		/* Update the row */
		try {
			$device_photo -> update();
			return $device_photo -> to_array_filtered($role);
		} catch(Exception $e) {
			return array('error' => 'Failed to update row', 'code' => '500');
		}
	}

	public static function delete($id = null) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['device_photo']['delete']) || core::$permission[$role]['device_photo']['delete'] != true) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load device_photo */
		$device_photo = device_photo_model::get($id);
		if(!$device_photo) {
			return array('error' => 'device_photo not found', 'code' => '404');
		}


		/* Delete it */
		try {
			$device_photo -> delete();
			return array('success' => 'yes');
		} catch(Exception $e) {
			return array('error' => 'Failed to delete', 'code' => '500');
		}
	}

	public static function list_all($page = 1, $itemspp = 20) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['device_photo']['read']) || count(core::$permission[$role]['device_photo']['read']) == 0) {
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
			$device_photo_list = device_photo_model::list_all($start, $limit);
			$ret = array();
			foreach($device_photo_list as $device_photo) {
				$ret[] = $device_photo -> to_array_filtered($role);
			}
			return $ret;
		} catch(Exception $e) {
			return array('error' => 'Failed to list', 'code' => '500');
		}
	}
	
	public static function upload($device_id) {
		/* Need permission to upload and to read device info, and to edit device hitory */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['device_photo']['create']) || core::$permission[$role]['device_photo']['create'] != true) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}
		if(!isset(core::$permission[$role]['device']['read']) || count(core::$permission[$role]['device']['read']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}
		if(!isset(core::$permission[$role]['device_history']['create']) || core::$permission[$role]['device_history']['create'] != true) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}
		
		/* Load up related device */
		$device = device_model::get($device_id);
		if(!$device) {
			return array('error' => 'device not found', 'code' => '404');
		}
		if(!$technician = technician_model::get_by_technician_login(session::getUsername())) {
			return array('error' => 'Failed to find out the technician submitting this.', 'code' => '400');
		}
		
		/* Get most recent history entry */
		$device -> populate_list_device_history(0, 1);
		if(count($device -> list_device_history) == 0 || $device -> list_device_history[0] -> get_change() != 'photo' || $device -> list_device_history[0] -> get_comment() != '') {
			/* Make a new dummy history entry */
			$device_history = new device_history_model();
			$device_history -> set_change('photo');
			$device_history -> set_date(date('Y-m-d H:i:s'));
			$device_history -> set_technician_id($technician -> get_id());
			$device_history -> set_device_id($device -> get_id());
			$device_history -> set_person_id($device -> get_person_id());
			$device_history -> set_device_status_id($device -> get_device_status_id());
			$device_history -> set_is_damaged($device -> get_is_damaged());
			$device_history -> set_is_spare($device -> get_is_spare());
			$device_history -> set_is_bought($device -> get_is_bought());
			$device_history -> set_comment('');
			$device_history -> set_has_photos(1);
			$device_history -> insert();
		} else {
			$device_history = $device -> list_device_history[0];
		}
		
		$device_history -> populate_list_device_photo();
		print_r($_FILES);
	}
}
?>