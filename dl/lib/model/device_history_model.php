<?php
class device_history_model {
	private $id;
	private $date;
	private $comment;
	private $is_spare;
	private $is_damaged;
	private $has_photos;
	private $is_bought;
	private $change;
	private $technician_id;
	private $device_id;
	private $device_status_id;
	private $person_id;
	private $model_variables_changed; // Only variables which have been changed
	private $model_variables_set; // All variables which have been set (initially or with a setter)
	private static $change_values = array('comment', 'photo', 'owner', 'status', 'damaged', 'spare', 'bought');

	/* Parent tables */
	public $technician;
	public $device;
	public $device_status;
	public $person;

	/* Child tables */
	public $list_device_photo;

	/**
	 * Construct new device_history from field list
	 * 
	 * @return array
	 */
	public function __construct(array $fields = array()) {
		if(isset($fields['device_history.id'])) {
			$this -> set_id($fields['device_history.id']);
		}
		if(isset($fields['device_history.date'])) {
			$this -> set_date($fields['device_history.date']);
		}
		if(isset($fields['device_history.comment'])) {
			$this -> set_comment($fields['device_history.comment']);
		}
		if(isset($fields['device_history.is_spare'])) {
			$this -> set_is_spare($fields['device_history.is_spare']);
		}
		if(isset($fields['device_history.is_damaged'])) {
			$this -> set_is_damaged($fields['device_history.is_damaged']);
		}
		if(isset($fields['device_history.has_photos'])) {
			$this -> set_has_photos($fields['device_history.has_photos']);
		}
		if(isset($fields['device_history.is_bought'])) {
			$this -> set_is_bought($fields['device_history.is_bought']);
		}
		if(isset($fields['device_history.change'])) {
			$this -> set_change($fields['device_history.change']);
		}
		if(isset($fields['device_history.technician_id'])) {
			$this -> set_technician_id($fields['device_history.technician_id']);
		}
		if(isset($fields['device_history.device_id'])) {
			$this -> set_device_id($fields['device_history.device_id']);
		}
		if(isset($fields['device_history.device_status_id'])) {
			$this -> set_device_status_id($fields['device_history.device_status_id']);
		}
		if(isset($fields['device_history.person_id'])) {
			$this -> set_person_id($fields['device_history.person_id']);
		}

		$this -> model_variables_changed = array();
		$this -> technician = new technician_model($fields);
		$this -> device = new device_model($fields);
		$this -> device_status = new device_status_model($fields);
		$this -> person = new person_model($fields);
	}

	/**
	 * Convert device_history to shallow associative array
	 * 
	 * @return array
	 */
	private function to_array() {
		$values = array(
			'id' => $this -> id,
			'date' => $this -> date,
			'comment' => $this -> comment,
			'is_spare' => $this -> is_spare,
			'is_damaged' => $this -> is_damaged,
			'has_photos' => $this -> has_photos,
			'is_bought' => $this -> is_bought,
			'change' => $this -> change,
			'technician_id' => $this -> technician_id,
			'device_id' => $this -> device_id,
			'device_status_id' => $this -> device_status_id,
			'person_id' => $this -> person_id);
		return $values;
	}

	/**
	 * Convert device_history to associative array, including only visible fields,
	 * parent tables, and loaded child tables
	 * 
	 * @param string $role The user role to use
	 */
	public function to_array_filtered($role = "anon") {
		// TODO: Insert code for device_history permission-check
	}

	/**
	 * Convert retrieved database row from numbered to named keys, including table name
	 * 
	 * @param array $row ror retrieved from database
	 * @return array row with indices
	 */
	private static function row_to_assoc(array $row) {
		$values = array(
			"device_history.id" => $row[0],
			"device_history.date" => $row[1],
			"device_history.comment" => $row[2],
			"device_history.is_spare" => $row[3],
			"device_history.is_damaged" => $row[4],
			"device_history.has_photos" => $row[5],
			"device_history.is_bought" => $row[6],
			"device_history.change" => $row[7],
			"device_history.technician_id" => $row[8],
			"device_history.device_id" => $row[9],
			"device_history.device_status_id" => $row[10],
			"device_history.person_id" => $row[11],
			"technician.id" => $row[12],
			"technician.login" => $row[13],
			"technician.name" => $row[14],
			"device.id" => $row[15],
			"device.is_spare" => $row[16],
			"device.is_damaged" => $row[17],
			"device.sn" => $row[18],
			"device.mac_eth0" => $row[19],
			"device.mac_wlan0" => $row[20],
			"device.is_bought" => $row[21],
			"device.person_id" => $row[22],
			"device.device_status_id" => $row[23],
			"device.device_type_id" => $row[24],
			"device_status.id" => $row[25],
			"device_status.tag" => $row[26],
			"person.id" => $row[27],
			"person.code" => $row[28],
			"person.is_staff" => $row[29],
			"person.is_active" => $row[30],
			"person.firstname" => $row[31],
			"person.surname" => $row[32],
			"device_type.id" => $row[33],
			"device_type.name" => $row[34],
			"device_type.model_no" => $row[35]);
		return $values;
	}

	/**
	 * Get id
	 * 
	 * @return int
	 */
	public function get_id() {
		if(!isset($this -> model_variables_set['id'])) {
			throw new Exception("device_history.id has not been initialised.");
		}
		return $this -> id;
	}

	/**
	 * Set id
	 * 
	 * @param int $id
	 */
	private function set_id($id) {
		if(!is_numeric($id)) {
			throw new Exception("device_history.id must be numeric");
		}
		$this -> id = $id;
		$this -> model_variables_changed['id'] = true;
		$this -> model_variables_set['id'] = true;
	}

	/**
	 * Get date
	 * 
	 * @return string
	 */
	public function get_date() {
		if(!isset($this -> model_variables_set['date'])) {
			throw new Exception("device_history.date has not been initialised.");
		}
		return $this -> date;
	}

	/**
	 * Set date
	 * 
	 * @param string $date
	 */
	public function set_date($date) {
		// TODO: Add validation to device_history.date
		$this -> date = $date;
		$this -> model_variables_changed['date'] = true;
		$this -> model_variables_set['date'] = true;
	}

	/**
	 * Get comment
	 * 
	 * @return string
	 */
	public function get_comment() {
		if(!isset($this -> model_variables_set['comment'])) {
			throw new Exception("device_history.comment has not been initialised.");
		}
		return $this -> comment;
	}

	/**
	 * Set comment
	 * 
	 * @param string $comment
	 */
	public function set_comment($comment) {
		// TODO: Add TEXT validation to device_history.comment
		$this -> comment = $comment;
		$this -> model_variables_changed['comment'] = true;
		$this -> model_variables_set['comment'] = true;
	}

	/**
	 * Get is_spare
	 * 
	 * @return int
	 */
	public function get_is_spare() {
		if(!isset($this -> model_variables_set['is_spare'])) {
			throw new Exception("device_history.is_spare has not been initialised.");
		}
		return $this -> is_spare;
	}

	/**
	 * Set is_spare
	 * 
	 * @param int $is_spare
	 */
	public function set_is_spare($is_spare) {
		if(!is_numeric($is_spare)) {
			throw new Exception("device_history.is_spare must be numeric");
		}
		$this -> is_spare = $is_spare;
		$this -> model_variables_changed['is_spare'] = true;
		$this -> model_variables_set['is_spare'] = true;
	}

	/**
	 * Get is_damaged
	 * 
	 * @return int
	 */
	public function get_is_damaged() {
		if(!isset($this -> model_variables_set['is_damaged'])) {
			throw new Exception("device_history.is_damaged has not been initialised.");
		}
		return $this -> is_damaged;
	}

	/**
	 * Set is_damaged
	 * 
	 * @param int $is_damaged
	 */
	public function set_is_damaged($is_damaged) {
		if(!is_numeric($is_damaged)) {
			throw new Exception("device_history.is_damaged must be numeric");
		}
		$this -> is_damaged = $is_damaged;
		$this -> model_variables_changed['is_damaged'] = true;
		$this -> model_variables_set['is_damaged'] = true;
	}

	/**
	 * Get has_photos
	 * 
	 * @return int
	 */
	public function get_has_photos() {
		if(!isset($this -> model_variables_set['has_photos'])) {
			throw new Exception("device_history.has_photos has not been initialised.");
		}
		return $this -> has_photos;
	}

	/**
	 * Set has_photos
	 * 
	 * @param int $has_photos
	 */
	public function set_has_photos($has_photos) {
		if(!is_numeric($has_photos)) {
			throw new Exception("device_history.has_photos must be numeric");
		}
		$this -> has_photos = $has_photos;
		$this -> model_variables_changed['has_photos'] = true;
		$this -> model_variables_set['has_photos'] = true;
	}

	/**
	 * Get is_bought
	 * 
	 * @return int
	 */
	public function get_is_bought() {
		if(!isset($this -> model_variables_set['is_bought'])) {
			throw new Exception("device_history.is_bought has not been initialised.");
		}
		return $this -> is_bought;
	}

	/**
	 * Set is_bought
	 * 
	 * @param int $is_bought
	 */
	public function set_is_bought($is_bought) {
		if(!is_numeric($is_bought)) {
			throw new Exception("device_history.is_bought must be numeric");
		}
		$this -> is_bought = $is_bought;
		$this -> model_variables_changed['is_bought'] = true;
		$this -> model_variables_set['is_bought'] = true;
	}

	/**
	 * Get change
	 * 
	 * @return string
	 */
	public function get_change() {
		if(!isset($this -> model_variables_set['change'])) {
			throw new Exception("device_history.change has not been initialised.");
		}
		return $this -> change;
	}

	/**
	 * Set change
	 * 
	 * @param string $change
	 */
	public function set_change($change) {
		if(!in_array($change, self::$change_values)) {
			throw new Exception("device_history.change must be one of the defined values.");
		}
		$this -> change = $change;
		$this -> model_variables_changed['change'] = true;
		$this -> model_variables_set['change'] = true;
	}

	/**
	 * Get technician_id
	 * 
	 * @return int
	 */
	public function get_technician_id() {
		if(!isset($this -> model_variables_set['technician_id'])) {
			throw new Exception("device_history.technician_id has not been initialised.");
		}
		return $this -> technician_id;
	}

	/**
	 * Set technician_id
	 * 
	 * @param int $technician_id
	 */
	public function set_technician_id($technician_id) {
		if(!is_numeric($technician_id)) {
			throw new Exception("device_history.technician_id must be numeric");
		}
		$this -> technician_id = $technician_id;
		$this -> model_variables_changed['technician_id'] = true;
		$this -> model_variables_set['technician_id'] = true;
	}

	/**
	 * Get device_id
	 * 
	 * @return int
	 */
	public function get_device_id() {
		if(!isset($this -> model_variables_set['device_id'])) {
			throw new Exception("device_history.device_id has not been initialised.");
		}
		return $this -> device_id;
	}

	/**
	 * Set device_id
	 * 
	 * @param int $device_id
	 */
	public function set_device_id($device_id) {
		if(!is_numeric($device_id)) {
			throw new Exception("device_history.device_id must be numeric");
		}
		$this -> device_id = $device_id;
		$this -> model_variables_changed['device_id'] = true;
		$this -> model_variables_set['device_id'] = true;
	}

	/**
	 * Get device_status_id
	 * 
	 * @return int
	 */
	public function get_device_status_id() {
		if(!isset($this -> model_variables_set['device_status_id'])) {
			throw new Exception("device_history.device_status_id has not been initialised.");
		}
		return $this -> device_status_id;
	}

	/**
	 * Set device_status_id
	 * 
	 * @param int $device_status_id
	 */
	public function set_device_status_id($device_status_id) {
		if(!is_numeric($device_status_id)) {
			throw new Exception("device_history.device_status_id must be numeric");
		}
		$this -> device_status_id = $device_status_id;
		$this -> model_variables_changed['device_status_id'] = true;
		$this -> model_variables_set['device_status_id'] = true;
	}

	/**
	 * Get person_id
	 * 
	 * @return int
	 */
	public function get_person_id() {
		if(!isset($this -> model_variables_set['person_id'])) {
			throw new Exception("device_history.person_id has not been initialised.");
		}
		return $this -> person_id;
	}

	/**
	 * Set person_id
	 * 
	 * @param int $person_id
	 */
	public function set_person_id($person_id) {
		if(!is_numeric($person_id)) {
			throw new Exception("device_history.person_id must be numeric");
		}
		$this -> person_id = $person_id;
		$this -> model_variables_changed['person_id'] = true;
		$this -> model_variables_set['person_id'] = true;
	}

	/**
	 * Update device_history
	 */
	public function update() {
		if(count($this -> model_variables_changed) == 0) {
			throw new Exception("Nothing to update");
		}

		/* Compose list of changed fields */
		$fieldset = array();
		foreach($this -> model_variables_changed as $col => $changed) {
			$fieldset[] = "$col = :$col";
		}
		$fields = implode(", ", $fieldset);

		/* Execute query */
		$sth = database::$dbh -> prepare("UPDATE device_history SET $fields WHERE id = :id");
		$sth -> execute($this -> to_array());
	}

	/**
	 * Add new device_history
	 */
	public function insert() {
		if(count($this -> model_variables_changed) == 0) {
			throw new Exception("No fields have been set!");
		}

		/* Compose list of set fields */
		$fieldset = array();
		foreach($this -> model_variables_set as $col => $changed) {
			$fieldset[] = $col;
			$fieldset_colon[] = ":$col";
		}
		$fields = implode(", ", $fieldset);
		$vals = implode(", ", $fieldset_colon);

		/* Execute query */
		$sth = database::$dbh -> prepare("INSERT INTO device_history ($fields) VALUES ($vals);");
		$sth -> execute($this -> to_array());
	}

	/**
	 * Delete device_history
	 */
	public function delete() {
		$sth = database::$dbh -> prepare("DELETE FROM device_history WHERE id = :id");
		$sth -> execute($this -> to_array());
	}

	/**
	 * Get associated rows from device_photo table
	 * 
	 * @param int $start Row to begin from. Default 0 (begin from start)
	 * @param int $limit Maximum number of rows to retrieve. Default -1 (no limit)
	 */
	public function populate_list_device_photo($start = 0, $limit = -1) {
		$this -> list_device_photo = device_photo_model::list_by_device_history_id($device_history_id, $start, $limit);
	}

	public static function get($id) {
		$sth = database::$dbh -> prepare("SELECT device_history.id, device_history.date, device_history.comment, device_history.is_spare, device_history.is_damaged, device_history.has_photos, device_history.is_bought, device_history.change, device_history.technician_id, device_history.device_id, device_history.device_status_id, device_history.person_id, technician.id, technician.login, technician.name, device.id, device.is_spare, device.is_damaged, device.sn, device.mac_eth0, device.mac_wlan0, device.is_bought, device.person_id, device.device_status_id, device.device_type_id, device_status.id, device_status.tag, person.id, person.code, person.is_staff, person.is_active, person.firstname, person.surname, device_type.id, device_type.name, device_type.model_no FROM device_history JOIN technician ON device_history.technician_id = technician.id JOIN device ON device_history.device_id = device.id JOIN device_status ON device_history.device_status_id = device_status.id JOIN person ON device_history.person_id = person.id JOIN device_type ON device.device_type_id = device_type.id WHERE device_history.id = :id;");
		$sth -> execute(array('id' => $id));
		$row = $sth -> fetch(PDO::FETCH_NUM);
		$assoc = self::row_to_assoc($row);
		return new device_history_model($assoc);
	}

	/**
	 * List rows by technician_id index
	 * 
	 * @param int $start Row to begin from. Default 0 (begin from start)
	 * @param int $limit Maximum number of rows to retrieve. Default -1 (no limit)
	 */
	public static function list_by_technician_id($technician_id, $start = 0, $limit = -1) {
		$ls = "";
		$start = (int)$start;
		$limit = (int)$limit;
		if($start > 0 && $limit > 0) {
			$ls = " LIMIT $start, " . ($start + $limit);
		}
		$sth = database::$dbh -> prepare("SELECT device_history.id, device_history.date, device_history.comment, device_history.is_spare, device_history.is_damaged, device_history.has_photos, device_history.is_bought, device_history.change, device_history.technician_id, device_history.device_id, device_history.device_status_id, device_history.person_id, technician.id, technician.login, technician.name, device.id, device.is_spare, device.is_damaged, device.sn, device.mac_eth0, device.mac_wlan0, device.is_bought, device.person_id, device.device_status_id, device.device_type_id, device_status.id, device_status.tag, person.id, person.code, person.is_staff, person.is_active, person.firstname, person.surname, device_type.id, device_type.name, device_type.model_no FROM device_history JOIN technician ON device_history.technician_id = technician.id JOIN device ON device_history.device_id = device.id JOIN device_status ON device_history.device_status_id = device_status.id JOIN person ON device_history.person_id = person.id JOIN device_type ON device.device_type_id = device_type.id WHERE device_history.technician_id = :technician_id" . $ls . ";");
		$sth -> execute(array('technician_id' => $technician_id));
		$rows = $sth -> fetchAll(PDO::FETCH_NUM);
		$ret = array();
		foreach($rows as $row) {
			$assoc = self::row_to_assoc($row);
			$ret[] = new device_history_model($assoc);
		}
		return $ret;
	}

	/**
	 * List rows by device_id index
	 * 
	 * @param int $start Row to begin from. Default 0 (begin from start)
	 * @param int $limit Maximum number of rows to retrieve. Default -1 (no limit)
	 */
	public static function list_by_device_id($device_id, $start = 0, $limit = -1) {
		$ls = "";
		$start = (int)$start;
		$limit = (int)$limit;
		if($start > 0 && $limit > 0) {
			$ls = " LIMIT $start, " . ($start + $limit);
		}
		$sth = database::$dbh -> prepare("SELECT device_history.id, device_history.date, device_history.comment, device_history.is_spare, device_history.is_damaged, device_history.has_photos, device_history.is_bought, device_history.change, device_history.technician_id, device_history.device_id, device_history.device_status_id, device_history.person_id, technician.id, technician.login, technician.name, device.id, device.is_spare, device.is_damaged, device.sn, device.mac_eth0, device.mac_wlan0, device.is_bought, device.person_id, device.device_status_id, device.device_type_id, device_status.id, device_status.tag, person.id, person.code, person.is_staff, person.is_active, person.firstname, person.surname, device_type.id, device_type.name, device_type.model_no FROM device_history JOIN technician ON device_history.technician_id = technician.id JOIN device ON device_history.device_id = device.id JOIN device_status ON device_history.device_status_id = device_status.id JOIN person ON device_history.person_id = person.id JOIN device_type ON device.device_type_id = device_type.id WHERE device_history.device_id = :device_id" . $ls . ";");
		$sth -> execute(array('device_id' => $device_id));
		$rows = $sth -> fetchAll(PDO::FETCH_NUM);
		$ret = array();
		foreach($rows as $row) {
			$assoc = self::row_to_assoc($row);
			$ret[] = new device_history_model($assoc);
		}
		return $ret;
	}

	/**
	 * List rows by device_status_id index
	 * 
	 * @param int $start Row to begin from. Default 0 (begin from start)
	 * @param int $limit Maximum number of rows to retrieve. Default -1 (no limit)
	 */
	public static function list_by_device_status_id($device_status_id, $start = 0, $limit = -1) {
		$ls = "";
		$start = (int)$start;
		$limit = (int)$limit;
		if($start > 0 && $limit > 0) {
			$ls = " LIMIT $start, " . ($start + $limit);
		}
		$sth = database::$dbh -> prepare("SELECT device_history.id, device_history.date, device_history.comment, device_history.is_spare, device_history.is_damaged, device_history.has_photos, device_history.is_bought, device_history.change, device_history.technician_id, device_history.device_id, device_history.device_status_id, device_history.person_id, technician.id, technician.login, technician.name, device.id, device.is_spare, device.is_damaged, device.sn, device.mac_eth0, device.mac_wlan0, device.is_bought, device.person_id, device.device_status_id, device.device_type_id, device_status.id, device_status.tag, person.id, person.code, person.is_staff, person.is_active, person.firstname, person.surname, device_type.id, device_type.name, device_type.model_no FROM device_history JOIN technician ON device_history.technician_id = technician.id JOIN device ON device_history.device_id = device.id JOIN device_status ON device_history.device_status_id = device_status.id JOIN person ON device_history.person_id = person.id JOIN device_type ON device.device_type_id = device_type.id WHERE device_history.device_status_id = :device_status_id" . $ls . ";");
		$sth -> execute(array('device_status_id' => $device_status_id));
		$rows = $sth -> fetchAll(PDO::FETCH_NUM);
		$ret = array();
		foreach($rows as $row) {
			$assoc = self::row_to_assoc($row);
			$ret[] = new device_history_model($assoc);
		}
		return $ret;
	}

	/**
	 * List rows by person_id index
	 * 
	 * @param int $start Row to begin from. Default 0 (begin from start)
	 * @param int $limit Maximum number of rows to retrieve. Default -1 (no limit)
	 */
	public static function list_by_person_id($person_id, $start = 0, $limit = -1) {
		$ls = "";
		$start = (int)$start;
		$limit = (int)$limit;
		if($start > 0 && $limit > 0) {
			$ls = " LIMIT $start, " . ($start + $limit);
		}
		$sth = database::$dbh -> prepare("SELECT device_history.id, device_history.date, device_history.comment, device_history.is_spare, device_history.is_damaged, device_history.has_photos, device_history.is_bought, device_history.change, device_history.technician_id, device_history.device_id, device_history.device_status_id, device_history.person_id, technician.id, technician.login, technician.name, device.id, device.is_spare, device.is_damaged, device.sn, device.mac_eth0, device.mac_wlan0, device.is_bought, device.person_id, device.device_status_id, device.device_type_id, device_status.id, device_status.tag, person.id, person.code, person.is_staff, person.is_active, person.firstname, person.surname, device_type.id, device_type.name, device_type.model_no FROM device_history JOIN technician ON device_history.technician_id = technician.id JOIN device ON device_history.device_id = device.id JOIN device_status ON device_history.device_status_id = device_status.id JOIN person ON device_history.person_id = person.id JOIN device_type ON device.device_type_id = device_type.id WHERE device_history.person_id = :person_id" . $ls . ";");
		$sth -> execute(array('person_id' => $person_id));
		$rows = $sth -> fetchAll(PDO::FETCH_NUM);
		$ret = array();
		foreach($rows as $row) {
			$assoc = self::row_to_assoc($row);
			$ret[] = new device_history_model($assoc);
		}
		return $ret;
	}
}
?>