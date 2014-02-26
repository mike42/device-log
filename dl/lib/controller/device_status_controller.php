<?php
class device_status_controller {
	public static function init() {
		core::loadClass("session");
		core::loadClass("device_status_model");
	}

	public static function create() {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['device_status']['create']) || core::$permission[$role]['device_status']['create'] != true) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Find fields to insert */
		$fields = array('id', 'tag');
		$init = array();
		foreach($fields as $field) {
			if(isset($_POST[$field])) {
				$init["device_status.$field"] = $_POST[$field];
			}
		}
		$device_status = new device_status_model($init);
		$device_status -> insert();
		return $device_status -> to_array_filtered($role);
	}

	public static function read($id) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['device_status']['read']) || count(core::$permission[$role]['device_status']['read']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load device_status */
		$device_status = device_status_model::get($id);
		if(!$device_status) {
			return array('error' => 'device_status not found');
		}
		// $device_status -> populate_list_device();
		// $device_status -> populate_list_device_history();
		return $device_status -> to_array_filtered($role);
	}

	public static function update($id) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['device_status']['update']) || count(core::$permission[$role]['device_status']['update']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load device_status */
		$device_status = device_status_model::get($id);
		if(!$device_status) {
			return array('error' => 'device_status not found');
		}

		$update = false;
		if(isset($_POST['tag']) && in_array('tag', core::$permission[$role]['device_status']['update'])) {
			$device_status -> set_tag($_POST['tag']);
		}
		$device_status -> update();
	}

	public static function delete() {
	}
}
?>