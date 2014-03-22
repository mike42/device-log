<?php
class key_history_controller {
	public static function init() {
		core::loadClass("session");
		core::loadClass("key_history_model");
	}

	public static function create() {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['key_history']['create']) || core::$permission[$role]['key_history']['create'] != true) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Find fields to insert */
		$fields = array('id', 'date', 'person_id', 'key_id', 'technician_id', 'key_status_id', 'comment', 'change', 'is_spare');
		$init = array();
		$received = json_decode(file_get_contents('php://input'), true, 2);
		foreach($fields as $field) {
			if(isset($received[$field])) {
				$init["key_history.$field"] = $received[$field];
			}
		}
			$key_history = new key_history_model($init);

		/* Check parent tables */
		if(!person_model::get($key_history -> get_person_id())) {
			return array('error' => 'key_history is invalid because related person does not exist', 'code' => '400');
		}
		if(!doorkey_model::get($key_history -> get_key_id())) {
			return array('error' => 'key_history is invalid because related doorkey does not exist', 'code' => '400');
		}
		if(!technician_model::get($key_history -> get_technician_id())) {
			return array('error' => 'key_history is invalid because related technician does not exist', 'code' => '400');
		}
		if(!key_status_model::get($key_history -> get_key_status_id())) {
			return array('error' => 'key_history is invalid because related key_status does not exist', 'code' => '400');
		}

		/* Insert new row */
		try {
			$key_history -> insert();
			return $key_history -> to_array_filtered($role);
		} catch(Exception $e) {
			return array('error' => 'Failed to add to database', 'code' => '500');
		}
	}

	public static function read($id = null) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['key_history']['read']) || count(core::$permission[$role]['key_history']['read']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load key_history */
		$key_history = key_history_model::get($id);
		if(!$key_history) {
			return array('error' => 'key_history not found', 'code' => '404');
		}
		return $key_history -> to_array_filtered($role);
	}

	public static function update($id = null) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['key_history']['update']) || count(core::$permission[$role]['key_history']['update']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load key_history */
		$key_history = key_history_model::get($id);
		if(!$key_history) {
			return array('error' => 'key_history not found', 'code' => '404');
		}

		/* Find fields to update */
		$update = false;
		$received = json_decode(file_get_contents('php://input'), true);
		if(isset($received['date']) && in_array('date', core::$permission[$role]['key_history']['update'])) {
			$key_history -> set_date($received['date']);
		}
		if(isset($received['person_id']) && in_array('person_id', core::$permission[$role]['key_history']['update'])) {
			$key_history -> set_person_id($received['person_id']);
		}
		if(isset($received['key_id']) && in_array('key_id', core::$permission[$role]['key_history']['update'])) {
			$key_history -> set_key_id($received['key_id']);
		}
		if(isset($received['technician_id']) && in_array('technician_id', core::$permission[$role]['key_history']['update'])) {
			$key_history -> set_technician_id($received['technician_id']);
		}
		if(isset($received['key_status_id']) && in_array('key_status_id', core::$permission[$role]['key_history']['update'])) {
			$key_history -> set_key_status_id($received['key_status_id']);
		}
		if(isset($received['comment']) && in_array('comment', core::$permission[$role]['key_history']['update'])) {
			$key_history -> set_comment($received['comment']);
		}
		if(isset($received['change']) && in_array('change', core::$permission[$role]['key_history']['update'])) {
			$key_history -> set_change($received['change']);
		}
		if(isset($received['is_spare']) && in_array('is_spare', core::$permission[$role]['key_history']['update'])) {
			$key_history -> set_is_spare($received['is_spare']);
		}

		/* Check parent tables */
		if(!person_model::get($key_history -> get_person_id())) {
			return array('error' => 'key_history is invalid because related person does not exist', 'code' => '400');
		}
		if(!doorkey_model::get($key_history -> get_key_id())) {
			return array('error' => 'key_history is invalid because related doorkey does not exist', 'code' => '400');
		}
		if(!technician_model::get($key_history -> get_technician_id())) {
			return array('error' => 'key_history is invalid because related technician does not exist', 'code' => '400');
		}
		if(!key_status_model::get($key_history -> get_key_status_id())) {
			return array('error' => 'key_history is invalid because related key_status does not exist', 'code' => '400');
		}

		/* Update the row */
		try {
			$key_history -> update();
			return $key_history -> to_array_filtered($role);
		} catch(Exception $e) {
			return array('error' => 'Failed to update row', 'code' => '500');
		}
	}

	public static function delete($id = null) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['key_history']['delete']) || core::$permission[$role]['key_history']['delete'] != true) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load key_history */
		$key_history = key_history_model::get($id);
		if(!$key_history) {
			return array('error' => 'key_history not found', 'code' => '404');
		}


		/* Delete it */
		try {
			$key_history -> delete();
			return array('success' => 'yes');
		} catch(Exception $e) {
			return array('error' => 'Failed to delete', 'code' => '500');
		}
	}

	public static function list_all($page = 1, $itemspp = 20) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['key_history']['read']) || count(core::$permission[$role]['key_history']['read']) == 0) {
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
			$key_history_list = key_history_model::list_all($start, $limit);
			$ret = array();
			foreach($key_history_list as $key_history) {
				$ret[] = $key_history -> to_array_filtered($role);
			}
			return $ret;
		} catch(Exception $e) {
			return array('error' => 'Failed to list', 'code' => '500');
		}
	}
}
?>