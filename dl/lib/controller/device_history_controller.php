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
		$received = json_decode(file_get_contents('php://input'), true, 2);
		foreach($fields as $field) {
			if(isset($received[$field])) {
				$init["device_history.$field"] = $received[$field];
			}
		}
			$device_history = new device_history_model($init);

		/* Check parent tables */
		if(!technician_model::get($device_history -> get_technician_id())) {
			return array('error' => 'device_history is invalid because related technician does not exist', 'code' => '400');
		}
		if(!device_model::get($device_history -> get_device_id())) {
			return array('error' => 'device_history is invalid because related device does not exist', 'code' => '400');
		}
		if(!device_status_model::get($device_history -> get_device_status_id())) {
			return array('error' => 'device_history is invalid because related device_status does not exist', 'code' => '400');
		}
		if(!person_model::get($device_history -> get_person_id())) {
			return array('error' => 'device_history is invalid because related person does not exist', 'code' => '400');
		}

		/* Insert new row */
		try {
			$device_history -> insert();
			return $device_history -> to_array_filtered($role);
		} catch(Exception $e) {
			return array('error' => 'Failed to add to database', 'code' => '500');
		}
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
			return array('error' => 'device_history not found', 'code' => '404');
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
			return array('error' => 'device_history not found', 'code' => '404');
		}

		/* Find fields to update */
		$update = false;
		$received = json_decode(file_get_contents('php://input'), true);
		if(isset($received['date']) && in_array('date', core::$permission[$role]['device_history']['update'])) {
			$device_history -> set_date($received['date']);
		}
		if(isset($received['comment']) && in_array('comment', core::$permission[$role]['device_history']['update'])) {
			$device_history -> set_comment($received['comment']);
		}
		if(isset($received['is_spare']) && in_array('is_spare', core::$permission[$role]['device_history']['update'])) {
			$device_history -> set_is_spare($received['is_spare']);
		}
		if(isset($received['is_damaged']) && in_array('is_damaged', core::$permission[$role]['device_history']['update'])) {
			$device_history -> set_is_damaged($received['is_damaged']);
		}
		if(isset($received['has_photos']) && in_array('has_photos', core::$permission[$role]['device_history']['update'])) {
			$device_history -> set_has_photos($received['has_photos']);
		}
		if(isset($received['is_bought']) && in_array('is_bought', core::$permission[$role]['device_history']['update'])) {
			$device_history -> set_is_bought($received['is_bought']);
		}
		if(isset($received['change']) && in_array('change', core::$permission[$role]['device_history']['update'])) {
			$device_history -> set_change($received['change']);
		}
		if(isset($received['technician_id']) && in_array('technician_id', core::$permission[$role]['device_history']['update'])) {
			$device_history -> set_technician_id($received['technician_id']);
		}
		if(isset($received['device_id']) && in_array('device_id', core::$permission[$role]['device_history']['update'])) {
			$device_history -> set_device_id($received['device_id']);
		}
		if(isset($received['device_status_id']) && in_array('device_status_id', core::$permission[$role]['device_history']['update'])) {
			$device_history -> set_device_status_id($received['device_status_id']);
		}
		if(isset($received['person_id']) && in_array('person_id', core::$permission[$role]['device_history']['update'])) {
			$device_history -> set_person_id($received['person_id']);
		}

		/* Check parent tables */
		if(!technician_model::get($device_history -> get_technician_id())) {
			return array('error' => 'device_history is invalid because related technician does not exist', 'code' => '400');
		}
		if(!device_model::get($device_history -> get_device_id())) {
			return array('error' => 'device_history is invalid because related device does not exist', 'code' => '400');
		}
		if(!device_status_model::get($device_history -> get_device_status_id())) {
			return array('error' => 'device_history is invalid because related device_status does not exist', 'code' => '400');
		}
		if(!person_model::get($device_history -> get_person_id())) {
			return array('error' => 'device_history is invalid because related person does not exist', 'code' => '400');
		}

		/* Update the row */
		try {
			$device_history -> update();
			return $device_history -> to_array_filtered($role);
		} catch(Exception $e) {
			return array('error' => 'Failed to update row', 'code' => '500');
		}
	}

	public static function delete($id) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['device_history']['delete']) || core::$permission[$role]['device_history']['delete'] != true) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load device_history */
		$device_history = device_history_model::get($id);
		if(!$device_history) {
			return array('error' => 'device_history not found', 'code' => '404');
		}

		/* Check for child rows */
		$device_history -> populate_list_device_photo(0, 1);
		if(count($device_history -> list_device_photo) > 0) {
			return array('error' => 'Cannot delete device_history because of a related device_photo entry', 'code' => '400');
		}

		/* Delete it */
		try {
			$device_history -> delete();
			return array('success' => 'yes');
		} catch(Exception $e) {
			return array('error' => 'Failed to delete', 'code' => '500');
		}
	}
}
?>