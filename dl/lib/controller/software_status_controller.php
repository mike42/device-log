<?php
class software_status_controller {
	public static function init() {
		core::loadClass("session");
		core::loadClass("software_status_model");
	}

	public static function create() {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['software_status']['create']) || core::$permission[$role]['software_status']['create'] != true) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Find fields to insert */
		$fields = array('id', 'tag');
		$init = array();
		foreach($fields as $field) {
			if(isset($_POST[$field])) {
				$init["software_status.$field"] = $_POST[$field];
			}
		}
		$software_status = new software_status_model($init);
		$software_status -> insert();
		return $software_status -> to_array_filtered($role);
	}

	public static function read($id) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['software_status']['read']) || count(core::$permission[$role]['software_status']['read']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load software_status */
		$software_status = software_status_model::get($id);
		if(!$software_status) {
			return array('error' => 'software_status not found');
		}
		// $software_status -> populate_list_software();
		// $software_status -> populate_list_software_history();
		return $software_status -> to_array_filtered($role);
	}

	public static function update($id) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['software_status']['update']) || count(core::$permission[$role]['software_status']['update']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load software_status */
		$software_status = software_status_model::get($id);
		if(!$software_status) {
			return array('error' => 'software_status not found');
		}

		$update = false;
		if(isset($_POST['tag']) && in_array('tag', core::$permission[$role]['software_status']['update'])) {
			$software_status -> set_tag($_POST['tag']);
		}
		$software_status -> update();
	}

	public static function delete() {
	}
}
?>