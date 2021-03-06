<?php
class device_history_controller {
	public static function init() {
		core::loadClass("session");
		core::loadClass("device_history_model");
	}

	public static function create() {
		// TODO
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['device_history']['create']) || core::$permission[$role]['device_history']['create'] != true) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Find fields to insert */
		$fields = array('id', 'date', 'comment', 'is_spare', 'is_damaged', 'has_photos', 'is_bought', 'change', 'technician_id', 'device_id', 'device_status_id', 'person_id');
		$init = array();
		$received = json_decode(file_get_contents('php://input'), true, 2);
		if(!isset($received['change'])) {
			return array('error' => 'Not enough information', 'code' => '400');
		}
		foreach($fields as $field) {
			if(isset($received[$field])) {
				$init["device_history.$field"] = $received[$field];
			}
		}
		
		/* Fill in some basics */
		$device_history = new device_history_model($init);
		if(!$technician = technician_model::get_by_technician_login(session::getUsername())) {
			return array('error' => 'Failed to find out the technician submitting this.', 'code' => '400');
		}
		if(!$device = device_model::get($device_history -> get_device_id())) {
			return array('error' => 'device_history is invalid because related device does not exist', 'code' => '400');
		}
		if($device_history -> get_comment() == "") {
			return array('error' => 'Comment is required.', 'code' => '400');
		}
		
		/* Fill everything else with defaults */
		$device_history -> set_date(date('Y-m-d H:i:s'));
		
		$device_history -> set_technician_id($technician -> get_id());
		if($device_history -> get_change() != 'owner') {
			$device_history -> set_person_id($device -> get_person_id());
		}
		if($device_history -> get_change() != 'status') {
			$device_history -> set_device_status_id($device -> get_device_status_id());
		}
		if($device_history -> get_change() != 'damaged') {
			$device_history -> set_is_damaged($device -> get_is_damaged());
		}
		if($device_history -> get_change() != 'spare') {
			$device_history -> set_is_spare($device -> get_is_spare());
		}
		if($device_history -> get_change() != 'bought') {
			$device_history -> set_is_bought($device -> get_is_bought());
		}
		
		/* Check parent tables */
		if(!$technician = technician_model::get($device_history -> get_technician_id())) {
			return array('error' => 'device_history is invalid because related technician does not exist', 'code' => '400');
		}
		if(!$device_status = device_status_model::get($device_history -> get_device_status_id())) {
			return array('error' => 'device_history is invalid because related device_status does not exist', 'code' => '400');
		}
		if(!$person = person_model::get($device_history -> get_person_id())) {
			return array('error' => 'device_history is invalid because related person does not exist', 'code' => '400');
		}

		/* Insert new row */
		try {
			if($device_history -> get_change() == 'photo') {
				$ok = false;
				$device -> populate_list_device_history(0, 1);
				foreach($device -> list_device_history as $dh) {
					/* Find the earliest matching device_history entry */
					if($dh -> get_change() == 'photo' && $dh -> get_comment() == '') {
						$ok = true;
						break;
					}
				}
				if(!$ok) {
					return array('error' => 'No images found to include', 'code' => '400');
				}
				$dh -> set_comment($device_history -> get_comment());
				$dh -> update();
				$device_history = $dh;
			} else {
				$device_history -> insert();
				// Update related device
				switch($device_history -> get_change()) {
					case 'owner':
						$device -> set_person_id($device_history -> get_person_id());
						$device -> update();
						break;
					case 'status':
						$device -> set_device_status_id($device_history -> get_device_status_id());
						$device -> update();
						break;
					case 'damaged':
						$device -> set_is_damaged($device_history -> get_is_damaged());
						$device -> update();
						break;
					case 'spare':
						$device -> set_is_spare($device_history -> get_is_spare());
						$device -> update();
						break;
					case 'bought':
						$device -> set_is_bought($device_history -> get_is_bought());
						$device -> update();
						break;
					default:
						// Nothing to do.
				}
				$device_history -> device = $device;
				$device_history -> person = $person;
				$device_history -> device_status = $device_status;
				$device_history -> technician = $technician;
			}
			
			if(isset($received['receipt']) && $received['receipt'] == 'true') {
				/* Print receipt */
				core::loadClass("ReceiptPrinter");
				
				try {
					ReceiptPrinter::dhReceipt($device_history);
				} catch(Exception $e) {
					// Ignore receipt printing issues
				}
			}
			
			return $device_history -> to_array_filtered($role);
		} catch(Exception $e) {
			return array('error' => 'Failed to add to database', 'code' => '500');
		}
	}

	public static function read($id = null) {
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

	public static function update($id = null) {
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

	public static function delete($id = null) {
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

	public static function list_all($page = 1, $itemspp = 20) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['device_history']['read']) || count(core::$permission[$role]['device_history']['read']) == 0) {
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
			$device_history_list = device_history_model::list_all($start, $limit);
			$ret = array();
			foreach($device_history_list as $device_history) {
				$ret[] = $device_history -> to_array_filtered($role);
			}
			return $ret;
		} catch(Exception $e) {
			return array('error' => 'Failed to list', 'code' => '500');
		}
	}
}
?>