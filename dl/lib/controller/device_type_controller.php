<?php
class device_type_controller {
	public static function init() {
		core::loadClass("session");
		core::loadClass("device_type_model");
	}

	public static function create() {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['device_type']['create']) || core::$permission[$role]['device_type']['create'] != true) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Find fields to insert */
		$fields = array('id', 'name', 'model_no');
		$init = array();
		foreach($fields as $field) {
			if(isset($_POST[$field])) {
				$init["device_type.$field"] = $_POST[$field];
			}
		}
		$device_type = new device_type_model($init);
		$device_type -> insert();
		return $device_type -> to_array_filtered($role);
	}

	public static function read($id) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['device_type']['read']) || count(core::$permission[$role]['device_type']['read']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load device_type */
		$device_type = device_type_model::get($id);
		if(!$device_type) {
			return array('error' => 'device_type not found');
		}
		// $device_type -> populate_list_device();
		return $device_type -> to_array_filtered($role);
	}

	public static function update($id) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['device_type']['update']) || count(core::$permission[$role]['device_type']['update']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load device_type */
		$device_type = device_type_model::get($id);
		if(!$device_type) {
			return array('error' => 'device_type not found');
		}

		$update = false;
		if(isset($_POST['name']) && in_array('name', core::$permission[$role]['device_type']['update'])) {
			$device_type -> set_name($_POST['name']);
		}
		if(isset($_POST['model_no']) && in_array('model_no', core::$permission[$role]['device_type']['update'])) {
			$device_type -> set_model_no($_POST['model_no']);
		}
		$device_type -> update();
	}

	public static function delete() {
	}
}
?>