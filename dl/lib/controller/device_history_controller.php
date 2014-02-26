<?php
class device_history_controller {
	public static function init() {
		core::loadClass("session");
		core::loadClass("device_history_model");
	}

	public static function create() {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['device_history']['create']) || core::$permission[$role]['device_history']['create'] != true) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Find fields to insert */
		$fields = array('id', 'date', 'comment', 'is_spare', 'is_damaged', 'has_photos', 'is_bought', 'change', 'technician_id', 'device_id', 'device_status_id', 'person_id');
		$init = array();
		foreach($fields as $field) {
			if(isset($_POST[$field])) {
				$init["device_history.$field"] = $_POST[$field];
			}
		}
		$device_history = new device_history_model($init);
		$device_history -> insert();
		return $device_history -> to_array_filtered($role);
	}

	public static function read($id) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['device_history']['read']) || count(core::$permission[$role]['device_history']['read']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load device_history */
		$device_history = device_history_model::get($id);
		if(!$device_history) {
			return array('error' => 'device_history not found');
		}
		// $device_history -> populate_list_device_photo();
		return $device_history -> to_array_filtered($role);
	}

	public static function update($id) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['device_history']['update']) || count(core::$permission[$role]['device_history']['update']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load device_history */
		$device_history = device_history_model::get($id);
		if(!$device_history) {
			return array('error' => 'device_history not found');
		}

		$update = false;
		if(isset($_POST['date']) && in_array('date', core::$permission[$role]['device_history']['update'])) {
			$device_history -> set_date($_POST['date']);
		}
		if(isset($_POST['comment']) && in_array('comment', core::$permission[$role]['device_history']['update'])) {
			$device_history -> set_comment($_POST['comment']);
		}
		if(isset($_POST['is_spare']) && in_array('is_spare', core::$permission[$role]['device_history']['update'])) {
			$device_history -> set_is_spare($_POST['is_spare']);
		}
		if(isset($_POST['is_damaged']) && in_array('is_damaged', core::$permission[$role]['device_history']['update'])) {
			$device_history -> set_is_damaged($_POST['is_damaged']);
		}
		if(isset($_POST['has_photos']) && in_array('has_photos', core::$permission[$role]['device_history']['update'])) {
			$device_history -> set_has_photos($_POST['has_photos']);
		}
		if(isset($_POST['is_bought']) && in_array('is_bought', core::$permission[$role]['device_history']['update'])) {
			$device_history -> set_is_bought($_POST['is_bought']);
		}
		if(isset($_POST['change']) && in_array('change', core::$permission[$role]['device_history']['update'])) {
			$device_history -> set_change($_POST['change']);
		}
		if(isset($_POST['technician_id']) && in_array('technician_id', core::$permission[$role]['device_history']['update'])) {
			$device_history -> set_technician_id($_POST['technician_id']);
		}
		if(isset($_POST['device_id']) && in_array('device_id', core::$permission[$role]['device_history']['update'])) {
			$device_history -> set_device_id($_POST['device_id']);
		}
		if(isset($_POST['device_status_id']) && in_array('device_status_id', core::$permission[$role]['device_history']['update'])) {
			$device_history -> set_device_status_id($_POST['device_status_id']);
		}
		if(isset($_POST['person_id']) && in_array('person_id', core::$permission[$role]['device_history']['update'])) {
			$device_history -> set_person_id($_POST['person_id']);
		}
		$device_history -> update();
	}

	public static function delete() {
	}
}
?>