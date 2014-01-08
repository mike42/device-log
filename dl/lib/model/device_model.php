<?php
class device_model {
	private $id;
	private $is_spare;
	private $is_damaged;
	private $sn;
	private $mac_eth0;
	private $mac_wlan0;
	private $is_bought;
	private $person_id;
	private $device_status_id;
	private $device_type_id;
	private $model_variables_changed; // Only variables which have been changed
	private $model_variables_set; // All variables which have been set (initially or with a setter)

	/* Parent tables */
	public $person;
	public $device_status;
	public $device_type;

	/* Child tables */
	public $list_device_history;

	/**
	 * Construct new device from field list
	 * 
	 * @return array
	 */
	public function __construct(array $fields = array()) {
		if(isset($fields['device.id'])) {
			$this -> set_id($fields['device.id']);
		}
		if(isset($fields['device.is_spare'])) {
			$this -> set_is_spare($fields['device.is_spare']);
		}
		if(isset($fields['device.is_damaged'])) {
			$this -> set_is_damaged($fields['device.is_damaged']);
		}
		if(isset($fields['device.sn'])) {
			$this -> set_sn($fields['device.sn']);
		}
		if(isset($fields['device.mac_eth0'])) {
			$this -> set_mac_eth0($fields['device.mac_eth0']);
		}
		if(isset($fields['device.mac_wlan0'])) {
			$this -> set_mac_wlan0($fields['device.mac_wlan0']);
		}
		if(isset($fields['device.is_bought'])) {
			$this -> set_is_bought($fields['device.is_bought']);
		}
		if(isset($fields['device.person_id'])) {
			$this -> set_person_id($fields['device.person_id']);
		}
		if(isset($fields['device.device_status_id'])) {
			$this -> set_device_status_id($fields['device.device_status_id']);
		}
		if(isset($fields['device.device_type_id'])) {
			$this -> set_device_type_id($fields['device.device_type_id']);
		}

		$this -> model_variables_changed = array();
		$this -> person = new person_model($fields);
		$this -> device_status = new device_status_model($fields);
		$this -> device_type = new device_type_model($fields);
	}

	/**
	 * Convert device to shallow associative array
	 * 
	 * @return array
	 */
	private function to_array() {
		$values = array(
			'id' => $this -> id,
			'is_spare' => $this -> is_spare,
			'is_damaged' => $this -> is_damaged,
			'sn' => $this -> sn,
			'mac_eth0' => $this -> mac_eth0,
			'mac_wlan0' => $this -> mac_wlan0,
			'is_bought' => $this -> is_bought,
			'person_id' => $this -> person_id,
			'device_status_id' => $this -> device_status_id,
			'device_type_id' => $this -> device_type_id);
		return $values;
	}

	/**
	 * Convert device to associative array, including only visible fields,
	 * parent tables, and loaded child tables
	 * 
	 * @param string $role The user role to use
	 */
	public function to_array_filtered($role = "anon") {
		// TODO: Insert code for device permission-check
	}

	/**
	 * Convert retrieved database row from numbered to named keys, including table name
	 * 
	 * @param array $row ror retrieved from database
	 * @return array row with indices
	 */
	private static function row_to_assoc(array $row) {
		$values = array(
			"device.id" => $row[0],
			"device.is_spare" => $row[1],
			"device.is_damaged" => $row[2],
			"device.sn" => $row[3],
			"device.mac_eth0" => $row[4],
			"device.mac_wlan0" => $row[5],
			"device.is_bought" => $row[6],
			"device.person_id" => $row[7],
			"device.device_status_id" => $row[8],
			"device.device_type_id" => $row[9],
			"person.id" => $row[10],
			"person.code" => $row[11],
			"person.is_staff" => $row[12],
			"person.is_active" => $row[13],
			"person.firstname" => $row[14],
			"person.surname" => $row[15],
			"device_status.id" => $row[16],
			"device_status.tag" => $row[17],
			"device_type.id" => $row[18],
			"device_type.name" => $row[19],
			"device_type.model_no" => $row[20]);
		return $values;
	}

	/**
	 * Get id
	 * 
	 * @return int
	 */
	public function get_id() {
		if(!isset($this -> model_variables_set['id'])) {
			throw new Exception("device.id has not been initialised.");
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
			throw new Exception("device.id must be numeric");
		}
		$this -> id = $id;
		$this -> model_variables_changed['id'] = true;
		$this -> model_variables_set['id'] = true;
	}

	/**
	 * Get is_spare
	 * 
	 * @return int
	 */
	public function get_is_spare() {
		if(!isset($this -> model_variables_set['is_spare'])) {
			throw new Exception("device.is_spare has not been initialised.");
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
			throw new Exception("device.is_spare must be numeric");
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
			throw new Exception("device.is_damaged has not been initialised.");
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
			throw new Exception("device.is_damaged must be numeric");
		}
		$this -> is_damaged = $is_damaged;
		$this -> model_variables_changed['is_damaged'] = true;
		$this -> model_variables_set['is_damaged'] = true;
	}

	/**
	 * Get sn
	 * 
	 * @return string
	 */
	public function get_sn() {
		if(!isset($this -> model_variables_set['sn'])) {
			throw new Exception("device.sn has not been initialised.");
		}
		return $this -> sn;
	}

	/**
	 * Set sn
	 * 
	 * @param string $sn
	 */
	public function set_sn($sn) {
		if(strlen($sn) > 45) {
			throw new Exception("device.sn cannot be longer than 45 characters");
		}
		$this -> sn = $sn;
		$this -> model_variables_changed['sn'] = true;
		$this -> model_variables_set['sn'] = true;
	}

	/**
	 * Get mac_eth0
	 * 
	 * @return string
	 */
	public function get_mac_eth0() {
		if(!isset($this -> model_variables_set['mac_eth0'])) {
			throw new Exception("device.mac_eth0 has not been initialised.");
		}
		return $this -> mac_eth0;
	}

	/**
	 * Set mac_eth0
	 * 
	 * @param string $mac_eth0
	 */
	public function set_mac_eth0($mac_eth0) {
		if(strlen($mac_eth0) > 17) {
			throw new Exception("device.mac_eth0 cannot be longer than 17 characters");
		}
		$this -> mac_eth0 = $mac_eth0;
		$this -> model_variables_changed['mac_eth0'] = true;
		$this -> model_variables_set['mac_eth0'] = true;
	}

	/**
	 * Get mac_wlan0
	 * 
	 * @return string
	 */
	public function get_mac_wlan0() {
		if(!isset($this -> model_variables_set['mac_wlan0'])) {
			throw new Exception("device.mac_wlan0 has not been initialised.");
		}
		return $this -> mac_wlan0;
	}

	/**
	 * Set mac_wlan0
	 * 
	 * @param string $mac_wlan0
	 */
	public function set_mac_wlan0($mac_wlan0) {
		if(strlen($mac_wlan0) > 17) {
			throw new Exception("device.mac_wlan0 cannot be longer than 17 characters");
		}
		$this -> mac_wlan0 = $mac_wlan0;
		$this -> model_variables_changed['mac_wlan0'] = true;
		$this -> model_variables_set['mac_wlan0'] = true;
	}

	/**
	 * Get is_bought
	 * 
	 * @return int
	 */
	public function get_is_bought() {
		if(!isset($this -> model_variables_set['is_bought'])) {
			throw new Exception("device.is_bought has not been initialised.");
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
			throw new Exception("device.is_bought must be numeric");
		}
		$this -> is_bought = $is_bought;
		$this -> model_variables_changed['is_bought'] = true;
		$this -> model_variables_set['is_bought'] = true;
	}

	/**
	 * Get person_id
	 * 
	 * @return int
	 */
	public function get_person_id() {
		if(!isset($this -> model_variables_set['person_id'])) {
			throw new Exception("device.person_id has not been initialised.");
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
			throw new Exception("device.person_id must be numeric");
		}
		$this -> person_id = $person_id;
		$this -> model_variables_changed['person_id'] = true;
		$this -> model_variables_set['person_id'] = true;
	}

	/**
	 * Get device_status_id
	 * 
	 * @return int
	 */
	public function get_device_status_id() {
		if(!isset($this -> model_variables_set['device_status_id'])) {
			throw new Exception("device.device_status_id has not been initialised.");
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
			throw new Exception("device.device_status_id must be numeric");
		}
		$this -> device_status_id = $device_status_id;
		$this -> model_variables_changed['device_status_id'] = true;
		$this -> model_variables_set['device_status_id'] = true;
	}

	/**
	 * Get device_type_id
	 * 
	 * @return int
	 */
	public function get_device_type_id() {
		if(!isset($this -> model_variables_set['device_type_id'])) {
			throw new Exception("device.device_type_id has not been initialised.");
		}
		return $this -> device_type_id;
	}

	/**
	 * Set device_type_id
	 * 
	 * @param int $device_type_id
	 */
	public function set_device_type_id($device_type_id) {
		if(!is_numeric($device_type_id)) {
			throw new Exception("device.device_type_id must be numeric");
		}
		$this -> device_type_id = $device_type_id;
		$this -> model_variables_changed['device_type_id'] = true;
		$this -> model_variables_set['device_type_id'] = true;
	}

	/**
	 * Update device
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
		$sth = database::$dbh -> prepare("UPDATE device SET $fields WHERE id = :id");
		$sth -> execute($this -> to_array());
	}

	/**
	 * Add new device
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
		$sth = database::$dbh -> prepare("INSERT INTO device ($fields) VALUES ($vals);");
		$sth -> execute($this -> to_array());
	}

	/**
	 * Delete device
	 */
	public function delete() {
		$sth = database::$dbh -> prepare("DELETE FROM device WHERE id = :id");
		$sth -> execute($this -> to_array());
	}

	/**
	 * Get associated rows from device_history table
	 * 
	 * @param int $start Row to begin from. Default 0 (begin from start)
	 * @param int $limit Maximum number of rows to retrieve. Default -1 (no limit)
	 */
	public function populate_list_device_history($start = 0, $limit = -1) {
		$this -> list_device_history = device_history_model::list_by_device_id($device_id, $start, $limit);
	}

	public static function get($id) {
		$sth = database::$dbh -> prepare("SELECT device.id, device.is_spare, device.is_damaged, device.sn, device.mac_eth0, device.mac_wlan0, device.is_bought, device.person_id, device.device_status_id, device.device_type_id, person.id, person.code, person.is_staff, person.is_active, person.firstname, person.surname, device_status.id, device_status.tag, device_type.id, device_type.name, device_type.model_no FROM device JOIN person ON device.person_id = person.id JOIN device_status ON device.device_status_id = device_status.id JOIN device_type ON device.device_type_id = device_type.id WHERE device.id = :id;");
		$sth -> execute(array('id' => $id));
		$row = $sth -> fetch(PDO::FETCH_NUM);
		$assoc = self::row_to_assoc($row);
		return new device_model($assoc);
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
		$sth = database::$dbh -> prepare("SELECT device.id, device.is_spare, device.is_damaged, device.sn, device.mac_eth0, device.mac_wlan0, device.is_bought, device.person_id, device.device_status_id, device.device_type_id, person.id, person.code, person.is_staff, person.is_active, person.firstname, person.surname, device_status.id, device_status.tag, device_type.id, device_type.name, device_type.model_no FROM device JOIN person ON device.person_id = person.id JOIN device_status ON device.device_status_id = device_status.id JOIN device_type ON device.device_type_id = device_type.id WHERE device.person_id = :person_id" . $ls . ";");
		$sth -> execute(array('person_id' => $person_id));
		$rows = $sth -> fetchAll(PDO::FETCH_NUM);
		$ret = array();
		foreach($rows as $row) {
			$assoc = self::row_to_assoc($row);
			$ret[] = new device_model($assoc);
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
		$sth = database::$dbh -> prepare("SELECT device.id, device.is_spare, device.is_damaged, device.sn, device.mac_eth0, device.mac_wlan0, device.is_bought, device.person_id, device.device_status_id, device.device_type_id, person.id, person.code, person.is_staff, person.is_active, person.firstname, person.surname, device_status.id, device_status.tag, device_type.id, device_type.name, device_type.model_no FROM device JOIN person ON device.person_id = person.id JOIN device_status ON device.device_status_id = device_status.id JOIN device_type ON device.device_type_id = device_type.id WHERE device.device_status_id = :device_status_id" . $ls . ";");
		$sth -> execute(array('device_status_id' => $device_status_id));
		$rows = $sth -> fetchAll(PDO::FETCH_NUM);
		$ret = array();
		foreach($rows as $row) {
			$assoc = self::row_to_assoc($row);
			$ret[] = new device_model($assoc);
		}
		return $ret;
	}

	/**
	 * List rows by device_type_id index
	 * 
	 * @param int $start Row to begin from. Default 0 (begin from start)
	 * @param int $limit Maximum number of rows to retrieve. Default -1 (no limit)
	 */
	public static function list_by_device_type_id($device_type_id, $start = 0, $limit = -1) {
		$ls = "";
		$start = (int)$start;
		$limit = (int)$limit;
		if($start > 0 && $limit > 0) {
			$ls = " LIMIT $start, " . ($start + $limit);
		}
		$sth = database::$dbh -> prepare("SELECT device.id, device.is_spare, device.is_damaged, device.sn, device.mac_eth0, device.mac_wlan0, device.is_bought, device.person_id, device.device_status_id, device.device_type_id, person.id, person.code, person.is_staff, person.is_active, person.firstname, person.surname, device_status.id, device_status.tag, device_type.id, device_type.name, device_type.model_no FROM device JOIN person ON device.person_id = person.id JOIN device_status ON device.device_status_id = device_status.id JOIN device_type ON device.device_type_id = device_type.id WHERE device.device_type_id = :device_type_id" . $ls . ";");
		$sth -> execute(array('device_type_id' => $device_type_id));
		$rows = $sth -> fetchAll(PDO::FETCH_NUM);
		$ret = array();
		foreach($rows as $row) {
			$assoc = self::row_to_assoc($row);
			$ret[] = new device_model($assoc);
		}
		return $ret;
	}
}
?>