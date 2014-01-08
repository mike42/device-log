<?php
class key_model {
	private $id;
	private $serial;
	private $person_id;
	private $is_spare;
	private $key_type_id;
	private $key_status_id;
	private $model_variables_changed; // Only variables which have been changed
	private $model_variables_set; // All variables which have been set (initially or with a setter)

	/* Parent tables */
	public $person;
	public $key_type;
	public $key_status;

	/* Child tables */
	public $list_key_history;

	/**
	 * Construct new key from field list
	 * 
	 * @return array
	 */
	public function __construct(array $fields = array()) {
		if(isset($fields['key.id'])) {
			$this -> set_id($fields['key.id']);
		}
		if(isset($fields['key.serial'])) {
			$this -> set_serial($fields['key.serial']);
		}
		if(isset($fields['key.person_id'])) {
			$this -> set_person_id($fields['key.person_id']);
		}
		if(isset($fields['key.is_spare'])) {
			$this -> set_is_spare($fields['key.is_spare']);
		}
		if(isset($fields['key.key_type_id'])) {
			$this -> set_key_type_id($fields['key.key_type_id']);
		}
		if(isset($fields['key.key_status_id'])) {
			$this -> set_key_status_id($fields['key.key_status_id']);
		}

		$this -> model_variables_changed = array();
		$this -> person = new person_model($fields);
		$this -> key_type = new key_type_model($fields);
		$this -> key_status = new key_status_model($fields);
	}

	/**
	 * Convert key to shallow associative array
	 * 
	 * @return array
	 */
	private function to_array() {
		$values = array(
			'id' => $this -> id,
			'serial' => $this -> serial,
			'person_id' => $this -> person_id,
			'is_spare' => $this -> is_spare,
			'key_type_id' => $this -> key_type_id,
			'key_status_id' => $this -> key_status_id);
		return $values;
	}

	/**
	 * Convert key to associative array, including only visible fields,
	 * parent tables, and loaded child tables
	 * 
	 * @param string $role The user role to use
	 */
	public function to_array_filtered($role = "anon") {
		// TODO: Insert code for key permission-check
	}

	/**
	 * Convert retrieved database row from numbered to named keys, including table name
	 * 
	 * @param array $row ror retrieved from database
	 * @return array row with indices
	 */
	private static function row_to_assoc(array $row) {
		$values = array(
			"key.id" => $row[0],
			"key.serial" => $row[1],
			"key.person_id" => $row[2],
			"key.is_spare" => $row[3],
			"key.key_type_id" => $row[4],
			"key.key_status_id" => $row[5],
			"person.id" => $row[6],
			"person.code" => $row[7],
			"person.is_staff" => $row[8],
			"person.is_active" => $row[9],
			"person.firstname" => $row[10],
			"person.surname" => $row[11],
			"key_type.id" => $row[12],
			"key_type.name" => $row[13],
			"key_status.id" => $row[14],
			"key_status.name" => $row[15]);
		return $values;
	}

	/**
	 * Get id
	 * 
	 * @return int
	 */
	public function get_id() {
		if(!isset($this -> model_variables_set['id'])) {
			throw new Exception("key.id has not been initialised.");
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
			throw new Exception("key.id must be numeric");
		}
		$this -> id = $id;
		$this -> model_variables_changed['id'] = true;
		$this -> model_variables_set['id'] = true;
	}

	/**
	 * Get serial
	 * 
	 * @return string
	 */
	public function get_serial() {
		if(!isset($this -> model_variables_set['serial'])) {
			throw new Exception("key.serial has not been initialised.");
		}
		return $this -> serial;
	}

	/**
	 * Set serial
	 * 
	 * @param string $serial
	 */
	public function set_serial($serial) {
		if(strlen($serial) > 128) {
			throw new Exception("key.serial cannot be longer than 128 characters");
		}
		$this -> serial = $serial;
		$this -> model_variables_changed['serial'] = true;
		$this -> model_variables_set['serial'] = true;
	}

	/**
	 * Get person_id
	 * 
	 * @return int
	 */
	public function get_person_id() {
		if(!isset($this -> model_variables_set['person_id'])) {
			throw new Exception("key.person_id has not been initialised.");
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
			throw new Exception("key.person_id must be numeric");
		}
		$this -> person_id = $person_id;
		$this -> model_variables_changed['person_id'] = true;
		$this -> model_variables_set['person_id'] = true;
	}

	/**
	 * Get is_spare
	 * 
	 * @return int
	 */
	public function get_is_spare() {
		if(!isset($this -> model_variables_set['is_spare'])) {
			throw new Exception("key.is_spare has not been initialised.");
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
			throw new Exception("key.is_spare must be numeric");
		}
		$this -> is_spare = $is_spare;
		$this -> model_variables_changed['is_spare'] = true;
		$this -> model_variables_set['is_spare'] = true;
	}

	/**
	 * Get key_type_id
	 * 
	 * @return int
	 */
	public function get_key_type_id() {
		if(!isset($this -> model_variables_set['key_type_id'])) {
			throw new Exception("key.key_type_id has not been initialised.");
		}
		return $this -> key_type_id;
	}

	/**
	 * Set key_type_id
	 * 
	 * @param int $key_type_id
	 */
	public function set_key_type_id($key_type_id) {
		if(!is_numeric($key_type_id)) {
			throw new Exception("key.key_type_id must be numeric");
		}
		$this -> key_type_id = $key_type_id;
		$this -> model_variables_changed['key_type_id'] = true;
		$this -> model_variables_set['key_type_id'] = true;
	}

	/**
	 * Get key_status_id
	 * 
	 * @return int
	 */
	public function get_key_status_id() {
		if(!isset($this -> model_variables_set['key_status_id'])) {
			throw new Exception("key.key_status_id has not been initialised.");
		}
		return $this -> key_status_id;
	}

	/**
	 * Set key_status_id
	 * 
	 * @param int $key_status_id
	 */
	public function set_key_status_id($key_status_id) {
		if(!is_numeric($key_status_id)) {
			throw new Exception("key.key_status_id must be numeric");
		}
		$this -> key_status_id = $key_status_id;
		$this -> model_variables_changed['key_status_id'] = true;
		$this -> model_variables_set['key_status_id'] = true;
	}

	/**
	 * Update key
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
		$sth = database::$dbh -> prepare("UPDATE key SET $fields WHERE id = :id");
		$sth -> execute($this -> to_array());
	}

	/**
	 * Add new key
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
		$sth = database::$dbh -> prepare("INSERT INTO key ($fields) VALUES ($vals);");
		$sth -> execute($this -> to_array());
	}

	/**
	 * Delete key
	 */
	public function delete() {
		$sth = database::$dbh -> prepare("DELETE FROM key WHERE id = :id");
		$sth -> execute($this -> to_array());
	}

	/**
	 * Get associated rows from key_history table
	 * 
	 * @param int $start Row to begin from. Default 0 (begin from start)
	 * @param int $limit Maximum number of rows to retrieve. Default -1 (no limit)
	 */
	public function populate_list_key_history($start = 0, $limit = -1) {
		$this -> list_key_history = key_history_model::list_by_key_id($key_id, $start, $limit);
	}

	public static function get($id) {
		$sth = database::$dbh -> prepare("SELECT key.id, key.serial, key.person_id, key.is_spare, key.key_type_id, key.key_status_id, person.id, person.code, person.is_staff, person.is_active, person.firstname, person.surname, key_type.id, key_type.name, key_status.id, key_status.name FROM key JOIN person ON key.person_id = person.id JOIN key_type ON key.key_type_id = key_type.id JOIN key_status ON key.key_status_id = key_status.id WHERE key.id = :id;");
		$sth -> execute(array('id' => $id));
		$row = $sth -> fetch(PDO::FETCH_NUM);
		$assoc = self::row_to_assoc($row);
		return new key_model($assoc);
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
		$sth = database::$dbh -> prepare("SELECT key.id, key.serial, key.person_id, key.is_spare, key.key_type_id, key.key_status_id, person.id, person.code, person.is_staff, person.is_active, person.firstname, person.surname, key_type.id, key_type.name, key_status.id, key_status.name FROM key JOIN person ON key.person_id = person.id JOIN key_type ON key.key_type_id = key_type.id JOIN key_status ON key.key_status_id = key_status.id WHERE key.person_id = :person_id" . $ls . ";");
		$sth -> execute(array('person_id' => $person_id));
		$rows = $sth -> fetchAll(PDO::FETCH_NUM);
		$ret = array();
		foreach($rows as $row) {
			$assoc = self::row_to_assoc($row);
			$ret[] = new key_model($assoc);
		}
		return $ret;
	}

	/**
	 * List rows by key_type_id index
	 * 
	 * @param int $start Row to begin from. Default 0 (begin from start)
	 * @param int $limit Maximum number of rows to retrieve. Default -1 (no limit)
	 */
	public static function list_by_key_type_id($key_type_id, $start = 0, $limit = -1) {
		$ls = "";
		$start = (int)$start;
		$limit = (int)$limit;
		if($start > 0 && $limit > 0) {
			$ls = " LIMIT $start, " . ($start + $limit);
		}
		$sth = database::$dbh -> prepare("SELECT key.id, key.serial, key.person_id, key.is_spare, key.key_type_id, key.key_status_id, person.id, person.code, person.is_staff, person.is_active, person.firstname, person.surname, key_type.id, key_type.name, key_status.id, key_status.name FROM key JOIN person ON key.person_id = person.id JOIN key_type ON key.key_type_id = key_type.id JOIN key_status ON key.key_status_id = key_status.id WHERE key.key_type_id = :key_type_id" . $ls . ";");
		$sth -> execute(array('key_type_id' => $key_type_id));
		$rows = $sth -> fetchAll(PDO::FETCH_NUM);
		$ret = array();
		foreach($rows as $row) {
			$assoc = self::row_to_assoc($row);
			$ret[] = new key_model($assoc);
		}
		return $ret;
	}

	/**
	 * List rows by key_status_id index
	 * 
	 * @param int $start Row to begin from. Default 0 (begin from start)
	 * @param int $limit Maximum number of rows to retrieve. Default -1 (no limit)
	 */
	public static function list_by_key_status_id($key_status_id, $start = 0, $limit = -1) {
		$ls = "";
		$start = (int)$start;
		$limit = (int)$limit;
		if($start > 0 && $limit > 0) {
			$ls = " LIMIT $start, " . ($start + $limit);
		}
		$sth = database::$dbh -> prepare("SELECT key.id, key.serial, key.person_id, key.is_spare, key.key_type_id, key.key_status_id, person.id, person.code, person.is_staff, person.is_active, person.firstname, person.surname, key_type.id, key_type.name, key_status.id, key_status.name FROM key JOIN person ON key.person_id = person.id JOIN key_type ON key.key_type_id = key_type.id JOIN key_status ON key.key_status_id = key_status.id WHERE key.key_status_id = :key_status_id" . $ls . ";");
		$sth -> execute(array('key_status_id' => $key_status_id));
		$rows = $sth -> fetchAll(PDO::FETCH_NUM);
		$ret = array();
		foreach($rows as $row) {
			$assoc = self::row_to_assoc($row);
			$ret[] = new key_model($assoc);
		}
		return $ret;
	}
}
?>