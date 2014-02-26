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
		foreach($fields as $field) {
			if(isset($_POST[$field])) {
				$init["software_history.$field"] = $_POST[$field];
			}
		}
		$software_history = new software_history_model($init);
		$software_history -> insert();
		return $software_history -> to_array_filtered($role);
	}

	public static function read($id) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['software_history']['read']) || count(core::$permission[$role]['software_history']['read']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load software_history */
		$software_history = software_history_model::get($id);
		if(!$software_history) {
			return array('error' => 'software_history not found');
		}
		return $software_history -> to_array_filtered($role);
	}

	public static function update($id) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['software_history']['update']) || count(core::$permission[$role]['software_history']['update']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load software_history */
		$software_history = software_history_model::get($id);
		if(!$software_history) {
			return array('error' => 'software_history not found');
		}

		$update = false;
		if(isset($_POST['date']) && in_array('date', core::$permission[$role]['software_history']['update'])) {
			$software_history -> set_date($_POST['date']);
		}
		if(isset($_POST['person_id']) && in_array('person_id', core::$permission[$role]['software_history']['update'])) {
			$software_history -> set_person_id($_POST['person_id']);
		}
		if(isset($_POST['software_id']) && in_array('software_id', core::$permission[$role]['software_history']['update'])) {
			$software_history -> set_software_id($_POST['software_id']);
		}
		if(isset($_POST['technician_id']) && in_array('technician_id', core::$permission[$role]['software_history']['update'])) {
			$software_history -> set_technician_id($_POST['technician_id']);
		}
		if(isset($_POST['software_status_id']) && in_array('software_status_id', core::$permission[$role]['software_history']['update'])) {
			$software_history -> set_software_status_id($_POST['software_status_id']);
		}
		if(isset($_POST['comment']) && in_array('comment', core::$permission[$role]['software_history']['update'])) {
			$software_history -> set_comment($_POST['comment']);
		}
		if(isset($_POST['change']) && in_array('change', core::$permission[$role]['software_history']['update'])) {
			$software_history -> set_change($_POST['change']);
		}
		if(isset($_POST['is_bought']) && in_array('is_bought', core::$permission[$role]['software_history']['update'])) {
			$software_history -> set_is_bought($_POST['is_bought']);
		}
		$software_history -> update();
	}

	public static function delete() {
	}
}
?>