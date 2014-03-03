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
		$set = json_decode(file_get_contents('php://input'), true, 2);
		$init = array();
		foreach($fields as $field) {
			if(isset($set[$field])) {
				$init["device.$field"] = $set[$field];
			}
		}
		$device = new device_model($init);
		$device -> insert();
		return $device -> to_array_filtered($role);
	}

	public static function read($id) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['device']['read']) || count(core::$permission[$role]['device']['read']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load device */
		$device = device_model::get($id);
		if(!$device) {
			return array('error' => 'device not found');
		}
		// $device -> populate_list_device_history();
		return $device -> to_array_filtered($role);
	}

	public static function update($id) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['device']['update']) || count(core::$permission[$role]['device']['update']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load device */
		$device = device_model::get($id);
		if(!$device) {
			return array('error' => 'device not found');
		}

		$update = false;
		if(isset($_POST['is_spare']) && in_array('is_spare', core::$permission[$role]['device']['update'])) {
			$device -> set_is_spare($_POST['is_spare']);
		}
		if(isset($_POST['is_damaged']) && in_array('is_damaged', core::$permission[$role]['device']['update'])) {
			$device -> set_is_damaged($_POST['is_damaged']);
		}
		if(isset($_POST['sn']) && in_array('sn', core::$permission[$role]['device']['update'])) {
			$device -> set_sn($_POST['sn']);
		}
		if(isset($_POST['mac_eth0']) && in_array('mac_eth0', core::$permission[$role]['device']['update'])) {
			$device -> set_mac_eth0($_POST['mac_eth0']);
		}
		if(isset($_POST['mac_wlan0']) && in_array('mac_wlan0', core::$permission[$role]['device']['update'])) {
			$device -> set_mac_wlan0($_POST['mac_wlan0']);
		}
		if(isset($_POST['is_bought']) && in_array('is_bought', core::$permission[$role]['device']['update'])) {
			$device -> set_is_bought($_POST['is_bought']);
		}
		if(isset($_POST['person_id']) && in_array('person_id', core::$permission[$role]['device']['update'])) {
			$device -> set_person_id($_POST['person_id']);
		}
		if(isset($_POST['device_status_id']) && in_array('device_status_id', core::$permission[$role]['device']['update'])) {
			$device -> set_device_status_id($_POST['device_status_id']);
		}
		if(isset($_POST['device_type_id']) && in_array('device_type_id', core::$permission[$role]['device']['update'])) {
			$device -> set_device_type_id($_POST['device_type_id']);
		}
		$device -> update();
	}

	public static function delete() {
	}
}
?>