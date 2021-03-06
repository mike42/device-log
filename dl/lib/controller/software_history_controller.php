<?php
class software_history_controller {
	public static function init() {
		core::loadClass("session");
		core::loadClass("software_history_model");
	}

	public static function create() {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['software_history']['create']) || core::$permission[$role]['software_history']['create'] != true) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Find fields to insert */
		$fields = array('id', 'date', 'person_id', 'software_id', 'technician_id', 'software_status_id', 'comment', 'change', 'is_bought');
		$init = array();
		$received = json_decode(file_get_contents('php://input'), true, 2);
		foreach($fields as $field) {
			if(isset($received[$field])) {
				$init["software_history.$field"] = $received[$field];
			}
		}
		$software_history = new software_history_model($init);
		if(!$software = software_model::get($software_history -> get_software_id())) {
			return array('error' => 'software_history is invalid because related software does not exist', 'code' => '400');
		}
		if(!$technician = technician_model::get_by_technician_login(session::getUsername())) {
			return array('error' => 'Failed to find out the technician submitting this.', 'code' => '400');
		}
		if($software_history -> get_comment() == "") {
			return array('error' => 'Comment is required.', 'code' => '400');
		}
		
		/* Fill everything else with defaults */
		$software_history -> set_date(date('Y-m-d H:i:s'));

		$software_history -> set_technician_id($technician -> get_id());
		if($software_history -> get_change() != 'owner') {
			$software_history -> set_person_id($software -> get_person_id());
		}
		if($software_history -> get_change() != 'status') {
			$software_history -> set_software_status_id($software -> get_software_status_id());
		}
		if($software_history -> get_change() != 'bought') {
			$software_history -> set_is_bought($software -> get_is_bought());
		}
		
		/* Check parent tables */
		if(!$person = person_model::get($software_history -> get_person_id())) {
			return array('error' => 'software_history is invalid because related person does not exist', 'code' => '400');
		}
		if(!$technician = technician_model::get($software_history -> get_technician_id())) {
			return array('error' => 'software_history is invalid because related technician does not exist', 'code' => '400');
		}
		if(!$software_status = software_status_model::get($software_history -> get_software_status_id())) {
			return array('error' => 'software_history is invalid because related software_status does not exist', 'code' => '400');
		}

		/* Insert new row */
		try {
			$software_history -> insert();

			// Update related device
			switch($software_history -> get_change()) {
				case 'owner':
					$software -> set_person_id($software_history -> get_person_id());
					$software -> update();
					break;
				case 'status':
					$software -> set_software_status_id($software_history -> get_software_status_id());
					$software -> update();
					break;
				case 'bought':
					$software -> set_is_bought($software_history -> get_is_bought());
					$software -> update();
					break;
				default:
					// Nothing to do.
			}
			$software_history -> software = $software;
			$software_history -> person = $person;
			$software_history -> software_status = $software_status;
			$software_history -> technician = $technician;

			if(isset($received['receipt']) && $received['receipt'] == 'true') {
				/* Print receipt */
				core::loadClass("ReceiptPrinter");
			
				try {
					ReceiptPrinter::shReceipt($software_history);
				} catch(Exception $e) {
					// Ignore receipt printing issues
				}
			}
			
			return $software_history -> to_array_filtered($role);
		} catch(Exception $e) {
			return array('error' => 'Failed to add to database', 'code' => '500');
		}
	}

	public static function read($id = null) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['software_history']['read']) || count(core::$permission[$role]['software_history']['read']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load software_history */
		$software_history = software_history_model::get($id);
		if(!$software_history) {
			return array('error' => 'software_history not found', 'code' => '404');
		}
		return $software_history -> to_array_filtered($role);
	}

	public static function update($id = null) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['software_history']['update']) || count(core::$permission[$role]['software_history']['update']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load software_history */
		$software_history = software_history_model::get($id);
		if(!$software_history) {
			return array('error' => 'software_history not found', 'code' => '404');
		}

		/* Find fields to update */
		$update = false;
		$received = json_decode(file_get_contents('php://input'), true);
		if(isset($received['date']) && in_array('date', core::$permission[$role]['software_history']['update'])) {
			$software_history -> set_date($received['date']);
		}
		if(isset($received['person_id']) && in_array('person_id', core::$permission[$role]['software_history']['update'])) {
			$software_history -> set_person_id($received['person_id']);
		}
		if(isset($received['software_id']) && in_array('software_id', core::$permission[$role]['software_history']['update'])) {
			$software_history -> set_software_id($received['software_id']);
		}
		if(isset($received['technician_id']) && in_array('technician_id', core::$permission[$role]['software_history']['update'])) {
			$software_history -> set_technician_id($received['technician_id']);
		}
		if(isset($received['software_status_id']) && in_array('software_status_id', core::$permission[$role]['software_history']['update'])) {
			$software_history -> set_software_status_id($received['software_status_id']);
		}
		if(isset($received['comment']) && in_array('comment', core::$permission[$role]['software_history']['update'])) {
			$software_history -> set_comment($received['comment']);
		}
		if(isset($received['change']) && in_array('change', core::$permission[$role]['software_history']['update'])) {
			$software_history -> set_change($received['change']);
		}
		if(isset($received['is_bought']) && in_array('is_bought', core::$permission[$role]['software_history']['update'])) {
			$software_history -> set_is_bought($received['is_bought']);
		}

		/* Check parent tables */
		if(!person_model::get($software_history -> get_person_id())) {
			return array('error' => 'software_history is invalid because related person does not exist', 'code' => '400');
		}
		if(!software_model::get($software_history -> get_software_id())) {
			return array('error' => 'software_history is invalid because related software does not exist', 'code' => '400');
		}
		if(!technician_model::get($software_history -> get_technician_id())) {
			return array('error' => 'software_history is invalid because related technician does not exist', 'code' => '400');
		}
		if(!software_status_model::get($software_history -> get_software_status_id())) {
			return array('error' => 'software_history is invalid because related software_status does not exist', 'code' => '400');
		}

		/* Update the row */
		try {
			$software_history -> update();
			return $software_history -> to_array_filtered($role);
		} catch(Exception $e) {
			return array('error' => 'Failed to update row', 'code' => '500');
		}
	}

	public static function delete($id = null) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['software_history']['delete']) || core::$permission[$role]['software_history']['delete'] != true) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load software_history */
		$software_history = software_history_model::get($id);
		if(!$software_history) {
			return array('error' => 'software_history not found', 'code' => '404');
		}


		/* Delete it */
		try {
			$software_history -> delete();
			return array('success' => 'yes');
		} catch(Exception $e) {
			return array('error' => 'Failed to delete', 'code' => '500');
		}
	}

	public static function list_all($page = 1, $itemspp = 20) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['software_history']['read']) || count(core::$permission[$role]['software_history']['read']) == 0) {
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
			$software_history_list = software_history_model::list_all($start, $limit);
			$ret = array();
			foreach($software_history_list as $software_history) {
				$ret[] = $software_history -> to_array_filtered($role);
			}
			return $ret;
		} catch(Exception $e) {
			return array('error' => 'Failed to list', 'code' => '500');
		}
	}
}
?>