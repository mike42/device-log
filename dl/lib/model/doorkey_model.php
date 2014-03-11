<?php
class doorkey_model {
	/**
	 * @var int id ID for this key
	 */
	private $id;

	/**
	 * @var string serial Serial number appearing on the key
	 */
	private $serial;

	/**
	 * @var int person_id Person who currently has the key
	 */
	private $person_id;

	/**
	 * @var int is_spare 1 if the key is 'spare', 0 if it is not
	 */
	private $is_spare;

	/**
	 * @var int key_type_id ID of the type of key
	 */
	private $key_type_id;

	/**
	 * @var int key_status_id Current leu status
	 */
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
	 * Initialise and load related tables
	 */
	public static function init() {
		core::loadClass("database");
		core::loadClass("person_model");
		core::loadClass("key_type_model");
		core::loadClass("key_status_model");

		/* Child tables */
		core::loadClass("key_history_model");
	}

	/**
	 * Construct new doorkey from field list
	 * 
	 * @return array
	 */
	public function __construct(array $fields = array()) {
		/* Initialise everything as blank to avoid tripping up the permissions fitlers */
		$this -> id = '';
		$this -> serial = '';
		$this -> person_id = '';
		$this -> is_spare = '';
		$this -> key_type_id = '';
		$this -> key_status_id = '';

		if(isset($fields['doorkey.id'])) {
			$this -> set_id($fields['doorkey.id']);
		}
		if(isset($fields['doorkey.serial'])) {
			$this -> set_serial($fields['doorkey.serial']);
		}
		if(isset($fields['doorkey.person_id'])) {
			$this -> set_person_id($fields['doorkey.person_id']);
		}
		if(isset($fields['doorkey.is_spare'])) {
			$this -> set_is_spare($fields['doorkey.is_spare']);
		}
		if(isset($fields['doorkey.key_type_id'])) {
			$this -> set_key_type_id($fields['doorkey.key_type_id']);
		}
		if(isset($fields['doorkey.key_status_id'])) {
			$this -> set_key_status_id($fields['doorkey.key_status_id']);
		}

		$this -> model_variables_changed = array();
		$this -> person = new person_model($fields);
		$this -> key_type = new key_type_model($fields);
		$this -> key_status = new key_status_model($fields);
		$this -> list_key_history = array();
	}

	/**
	 * Convert doorkey to shallow associative array
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
	 * Convert doorkey to associative array, including only visible fields,
	 * parent tables, and loaded child tables
	 * 
	 * @param string $role The user role to use
	 */
	public function to_array_filtered($role = "anon") {
		if(core::$permission[$role]['doorkey']['read'] === false) {
			return false;
		}
		$values = array();
		$everything = $this -> to_array();
		foreach(core::$permission[$role]['doorkey']['read'] as $field) {
			if(!isset($everything[$field])) {
				throw new Exception("Check permissions: '$field' is not a real field in doorkey");
			}
			$values[$field] = $everything[$field];
		}
		$values['person'] = $this -> person -> to_array_filtered($role);
		$values['key_type'] = $this -> key_type -> to_array_filtered($role);
		$values['key_status'] = $this -> key_status -> to_array_filtered($role);

		/* Add filtered versions of everything that's been loaded */
		$values['key_history'] = array();
		foreach($this -> list_key_history as $key_history) {
			$values['key_history'][] = $key_history -> to_array_filtered($role);
		}
		return $values;
	}

	/**
	 * Convert retrieved database row from numbered to named keys, including table name
	 * 
	 * @param array $row ror retrieved from database
	 * @return array row with indices
	 */
	private static function row_to_assoc(array $row) {
		$values = array(
			"doorkey.id" => $row[0],
			"doorkey.serial" => $row[1],
			"doorkey.person_id" => $row[2],
			"doorkey.is_spare" => $row[3],
			"doorkey.key_type_id" => $row[4],
			"doorkey.key_status_id" => $row[5],
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
			throw new Exception("doorkey.id has not been initialised.");
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
			throw new Exception("doorkey.id must be numeric");
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
			throw new Exception("doorkey.serial has not been initialised.");
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
			throw new Exception("doorkey.serial cannot be longer than 128 characters");
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
			throw new Exception("doorkey.person_id has not been initialised.");
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
			throw new Exception("doorkey.person_id must be numeric");
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
			throw new Exception("doorkey.is_spare has not been initialised.");
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
			throw new Exception("doorkey.is_spare must be numeric");
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
			throw new Exception("doorkey.key_type_id has not been initialised.");
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
			throw new Exception("doorkey.key_type_id must be numeric");
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
			throw new Exception("doorkey.key_status_id has not been initialised.");
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
			throw new Exception("doorkey.key_status_id must be numeric");
		}
		$this -> key_status_id = $key_status_id;
		$this -> model_variables_changed['key_status_id'] = true;
		$this -> model_variables_set['key_status_id'] = true;
	}

	/**
	 * Update doorkey
	 */
	public function update() {
		if(count($this -> model_variables_changed) == 0) {
			throw new Exception("Nothing to update");
		}

		/* Compose list of changed fields */
		$fieldset = array();
		$everything = $this -> to_array();
		$data['id'] = $this -> get_id();
		foreach($this -> model_variables_changed as $col => $changed) {
			$fieldset[] = "$col = :$col";
			$data[$col] = $everything[$col];
		}
		$fields = implode(", ", $fieldset);

		/* Execute query */
		$sth = database::$dbh -> prepare("UPDATE doorkey SET $fields WHERE id = :id");
		$sth -> execute($data);
	}

	/**
	 * Add new doorkey
	 */
	public function insert() {
		if(count($this -> model_variables_set) == 0) {
			throw new Exception("No fields have been set!");
		}

		/* Compose list of set fields */
		$fieldset = array();
		$data = array();
		$everything = $this -> to_array();
		foreach($this -> model_variables_set as $col => $changed) {
			$fieldset[] = $col;
			$fieldset_colon[] = ":$col";
			$data[$col] = $everything[$col];
		}
		$fields = implode(", ", $fieldset);
		$vals = implode(", ", $fieldset_colon);

		/* Execute query */
		$sth = database::$dbh -> prepare("INSERT INTO doorkey ($fields) VALUES ($vals);");
		$sth -> execute($data);
		$this -> set_id(database::$dbh->lastInsertId());
	}

	/**
	 * Delete doorkey
	 */
	public function delete() {
		$sth = database::$dbh -> prepare("DELETE FROM doorkey WHERE id = :id");
		$data['id'] = $this -> get_id();
		$sth -> execute($data);
	}

	/**
	 * List associated rows from key_history table
	 * 
	 * @param int $start Row to begin from. Default 0 (begin from start)
	 * @param int $limit Maximum number of rows to retrieve. Default -1 (no limit)
	 */
	public function populate_list_key_history($start = 0, $limit = -1) {
		$key_id = $this -> get_id();
		$this -> list_key_history = key_history_model::list_by_key_id($key_id, $start, $limit);
	}

	/**
	 * Retrieve by primary key
	 */
	public static function get($id) {
		$sth = database::$dbh -> prepare("SELECT doorkey.id, doorkey.serial, doorkey.person_id, doorkey.is_spare, doorkey.key_type_id, doorkey.key_status_id, person.id, person.code, person.is_staff, person.is_active, person.firstname, person.surname, key_type.id, key_type.name, key_status.id, key_status.name FROM doorkey JOIN person ON doorkey.person_id = person.id JOIN key_type ON doorkey.key_type_id = key_type.id JOIN key_status ON doorkey.key_status_id = key_status.id WHERE doorkey.id = :id;");
		$sth -> execute(array('id' => $id));
		$row = $sth -> fetch(PDO::FETCH_NUM);
		if($row === false){
			return false;
		}
		$assoc = self::row_to_assoc($row);
		return new doorkey_model($assoc);
	}

	/**
	 * List all rows
	 * 
	 * @param int $start Row to begin from. Default 0 (begin from start)
	 * @param int $limit Maximum number of rows to retrieve. Default -1 (no limit)
	 */
	public static function list_all($start = 0, $limit = -1) {
		$ls = "";
		$start = (int)$start;
		$limit = (int)$limit;
		if($start >= 0 && $limit > 0) {
			$ls = " LIMIT $start, $limit";
		}
		$sth = database::$dbh -> prepare("SELECT doorkey.id, doorkey.serial, doorkey.person_id, doorkey.is_spare, doorkey.key_type_id, doorkey.key_status_id, person.id, person.code, person.is_staff, person.is_active, person.firstname, person.surname, key_type.id, key_type.name, key_status.id, key_status.name FROM doorkey JOIN person ON doorkey.person_id = person.id JOIN key_type ON doorkey.key_type_id = key_type.id JOIN key_status ON doorkey.key_status_id = key_status.id" . $ls . ";");
		$sth -> execute();
		$rows = $sth -> fetchAll(PDO::FETCH_NUM);
		$ret = array();
		foreach($rows as $row) {
			$assoc = self::row_to_assoc($row);
			$ret[] = new doorkey_model($assoc);
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
		if($start >= 0 && $limit > 0) {
			$ls = " LIMIT $start, $limit";
		}
		$sth = database::$dbh -> prepare("SELECT doorkey.id, doorkey.serial, doorkey.person_id, doorkey.is_spare, doorkey.key_type_id, doorkey.key_status_id, person.id, person.code, person.is_staff, person.is_active, person.firstname, person.surname, key_type.id, key_type.name, key_status.id, key_status.name FROM doorkey JOIN person ON doorkey.person_id = person.id JOIN key_type ON doorkey.key_type_id = key_type.id JOIN key_status ON doorkey.key_status_id = key_status.id WHERE doorkey.person_id = :person_id" . $ls . ";");
		$sth -> execute(array('person_id' => $person_id));
		$rows = $sth -> fetchAll(PDO::FETCH_NUM);
		$ret = array();
		foreach($rows as $row) {
			$assoc = self::row_to_assoc($row);
			$ret[] = new doorkey_model($assoc);
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
		if($start >= 0 && $limit > 0) {
			$ls = " LIMIT $start, $limit";
		}
		$sth = database::$dbh -> prepare("SELECT doorkey.id, doorkey.serial, doorkey.person_id, doorkey.is_spare, doorkey.key_type_id, doorkey.key_status_id, person.id, person.code, person.is_staff, person.is_active, person.firstname, person.surname, key_type.id, key_type.name, key_status.id, key_status.name FROM doorkey JOIN person ON doorkey.person_id = person.id JOIN key_type ON doorkey.key_type_id = key_type.id JOIN key_status ON doorkey.key_status_id = key_status.id WHERE doorkey.key_type_id = :key_type_id" . $ls . ";");
		$sth -> execute(array('key_type_id' => $key_type_id));
		$rows = $sth -> fetchAll(PDO::FETCH_NUM);
		$ret = array();
		foreach($rows as $row) {
			$assoc = self::row_to_assoc($row);
			$ret[] = new doorkey_model($assoc);
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
		if($start >= 0 && $limit > 0) {
			$ls = " LIMIT $start, $limit";
		}
		$sth = database::$dbh -> prepare("SELECT doorkey.id, doorkey.serial, doorkey.person_id, doorkey.is_spare, doorkey.key_type_id, doorkey.key_status_id, person.id, person.code, person.is_staff, person.is_active, person.firstname, person.surname, key_type.id, key_type.name, key_status.id, key_status.name FROM doorkey JOIN person ON doorkey.person_id = person.id JOIN key_type ON doorkey.key_type_id = key_type.id JOIN key_status ON doorkey.key_status_id = key_status.id WHERE doorkey.key_status_id = :key_status_id" . $ls . ";");
		$sth -> execute(array('key_status_id' => $key_status_id));
		$rows = $sth -> fetchAll(PDO::FETCH_NUM);
		$ret = array();
		foreach($rows as $row) {
			$assoc = self::row_to_assoc($row);
			$ret[] = new doorkey_model($assoc);
		}
		return $ret;
	}
}
?>