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
		foreach($fields as $field) {
			if(isset($_POST[$field])) {
				$init["key_history.$field"] = $_POST[$field];
			}
		}
		$key_history = new key_history_model($init);
		$key_history -> insert();
		return $key_history -> to_array_filtered($role);
	}

	public static function read($id) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['key_history']['read']) || count(core::$permission[$role]['key_history']['read']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load key_history */
		$key_history = key_history_model::get($id);
		if(!$key_history) {
			return array('error' => 'key_history not found');
		}
		return $key_history -> to_array_filtered($role);
	}

	public static function update($id) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['key_history']['update']) || count(core::$permission[$role]['key_history']['update']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load key_history */
		$key_history = key_history_model::get($id);
		if(!$key_history) {
			return array('error' => 'key_history not found');
		}

		$update = false;
		if(isset($_POST['date']) && in_array('date', core::$permission[$role]['key_history']['update'])) {
			$key_history -> set_date($_POST['date']);
		}
		if(isset($_POST['person_id']) && in_array('person_id', core::$permission[$role]['key_history']['update'])) {
			$key_history -> set_person_id($_POST['person_id']);
		}
		if(isset($_POST['key_id']) && in_array('key_id', core::$permission[$role]['key_history']['update'])) {
			$key_history -> set_key_id($_POST['key_id']);
		}
		if(isset($_POST['technician_id']) && in_array('technician_id', core::$permission[$role]['key_history']['update'])) {
			$key_history -> set_technician_id($_POST['technician_id']);
		}
		if(isset($_POST['key_status_id']) && in_array('key_status_id', core::$permission[$role]['key_history']['update'])) {
			$key_history -> set_key_status_id($_POST['key_status_id']);
		}
		if(isset($_POST['comment']) && in_array('comment', core::$permission[$role]['key_history']['update'])) {
			$key_history -> set_comment($_POST['comment']);
		}
		if(isset($_POST['change']) && in_array('change', core::$permission[$role]['key_history']['update'])) {
			$key_history -> set_change($_POST['change']);
		}
		if(isset($_POST['is_spare']) && in_array('is_spare', core::$permission[$role]['key_history']['update'])) {
			$key_history -> set_is_spare($_POST['is_spare']);
		}
		$key_history -> update();
	}

	public static function delete() {
	}
}
?>