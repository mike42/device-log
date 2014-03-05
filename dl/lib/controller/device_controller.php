<?php
class device_controller {
	public static function init() {
		core::loadClass("session");
		core::loadClass("device_model");
	}

	public static function create() {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['device']['create']) || core::$permission[$role]['device']['create'] != true) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Find fields to insert */
		$fields = array('id', 'is_spare', 'is_damaged', 'sn', 'mac_eth0', 'mac_wlan0', 'is_bought', 'person_id', 'device_status_id', 'device_type_id');
		$init = array();
		$received = json_decode(file_get_contents('php://input'), true, 2);
		foreach($fields as $field) {
			if(isset($received[$field])) {
				$init["device.$field"] = $received[$field];
			}
		}
			$device = new device_model($init);

		/* Check parent tables */
		if(!person_model::get($device -> get_person_id())) {
			return array('error' => 'device is invalid because related person does not exist', 'code' => '400');
		}
		if(!device_status_model::get($device -> get_device_status_id())) {
			return array('error' => 'device is invalid because related device_status does not exist', 'code' => '400');
		}
		if(!device_type_model::get($device -> get_device_type_id())) {
			return array('error' => 'device is invalid because related device_type does not exist', 'code' => '400');
		}

		/* Insert new row */
		try {
			$device -> insert();
			return $device -> to_array_filtered($role);
		} catch(Exception $e) {
			return array('error' => 'Failed to add to database', 'code' => '500');
		}
	}

	public static function read($id = null) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['device']['read']) || count(core::$permission[$role]['device']['read']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load device */
		$device = device_model::get($id);
		if(!$device) {
			return array('error' => 'device not found', 'code' => '404');
		}
		// $device -> populate_list_device_history();
		return $device -> to_array_filtered($role);
	}

	public static function update($id = null) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['device']['update']) || count(core::$permission[$role]['device']['update']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load device */
		$device = device_model::get($id);
		if(!$device) {
			return array('error' => 'device not found', 'code' => '404');
		}

		/* Find fields to update */
		$update = false;
		$received = json_decode(file_get_contents('php://input'), true);
		if(isset($received['is_spare']) && in_array('is_spare', core::$permission[$role]['device']['update'])) {
			$device -> set_is_spare($received['is_spare']);
		}
		if(isset($received['is_damaged']) && in_array('is_damaged', core::$permission[$role]['device']['update'])) {
			$device -> set_is_damaged($received['is_damaged']);
		}
		if(isset($received['sn']) && in_array('sn', core::$permission[$role]['device']['update'])) {
			$device -> set_sn($received['sn']);
		}
		if(isset($received['mac_eth0']) && in_array('mac_eth0', core::$permission[$role]['device']['update'])) {
			$device -> set_mac_eth0($received['mac_eth0']);
		}
		if(isset($received['mac_wlan0']) && in_array('mac_wlan0', core::$permission[$role]['device']['update'])) {
			$device -> set_mac_wlan0($received['mac_wlan0']);
		}
		if(isset($received['is_bought']) && in_array('is_bought', core::$permission[$role]['device']['update'])) {
			$device -> set_is_bought($received['is_bought']);
		}
		if(isset($received['person_id']) && in_array('person_id', core::$permission[$role]['device']['update'])) {
			$device -> set_person_id($received['person_id']);
		}
		if(isset($received['device_status_id']) && in_array('device_status_id', core::$permission[$role]['device']['update'])) {
			$device -> set_device_status_id($received['device_status_id']);
		}
		if(isset($received['device_type_id']) && in_array('device_type_id', core::$permission[$role]['device']['update'])) {
			$device -> set_device_type_id($received['device_type_id']);
		}

		/* Check parent tables */
		if(!person_model::get($device -> get_person_id())) {
			return array('error' => 'device is invalid because related person does not exist', 'code' => '400');
		}
		if(!device_status_model::get($device -> get_device_status_id())) {
			return array('error' => 'device is invalid because related device_status does not exist', 'code' => '400');
		}
		if(!device_type_model::get($device -> get_device_type_id())) {
			return array('error' => 'device is invalid because related device_type does not exist', 'code' => '400');
		}

		/* Update the row */
		try {
			$device -> update();
			return $device -> to_array_filtered($role);
		} catch(Exception $e) {
			return array('error' => 'Failed to update row', 'code' => '500');
		}
	}

	public static function delete($id = null) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['device']['delete']) || core::$permission[$role]['device']['delete'] != true) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load device */
		$device = device_model::get($id);
		if(!$device) {
			return array('error' => 'device not found', 'code' => '404');
		}

		/* Check for child rows */
		$device -> populate_list_device_history(0, 1);
		if(count($device -> list_device_history) > 0) {
			return array('error' => 'Cannot delete device because of a related device_history entry', 'code' => '400');
		}

		/* Delete it */
		try {
			$device -> delete();
			return array('success' => 'yes');
		} catch(Exception $e) {
			return array('error' => 'Failed to delete', 'code' => '500');
		}
	}

	public static function list_all($page = 1, $itemspp = 20) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['device']['read']) || count(core::$permission[$role]['device']['read']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}
		if((int)$page < 1 || (int)$itemspp < 1) {
			return array('error' => 'Invalid page number or item count', 'code' => '400');
		}

		/* Retrieve and filter rows */
		try {
			$device_list = device_model::list_all(($page - 1) * $itemspp, $itemspp);
			$ret = array();
			foreach($device_list as $device) {
				$ret[] = $device -> to_array_filtered($role);
			}
			return $ret;
		} catch(Exception $e) {
			return array('error' => 'Failed to list', 'code' => '500');
		}
	}
}
?>