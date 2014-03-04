<?php
class key_history_model {
	/**
	 * @var int id ID of the key history entry
	 */
	private $id;

	/**
	 * @var string date Date that this log entry was added
	 */
	private $date;

	/**
	 * @var int person_id ID of person who had the key at this point in time
	 */
	private $person_id;

	/**
	 * @var int key_id ID of the associated key
	 */
	private $key_id;

	/**
	 * @var int technician_id Technician who added this entry
	 */
	private $technician_id;

	/**
	 * @var int key_status_id Status code for the key
	 */
	private $key_status_id;

	/**
	 * @var string comment Technician comment
	 */
	private $comment;

	/**
	 * @var string change field which was changed by this entry
	 */
	private $change;

	/**
	 * @var int is_spare 1 if the key is currently spare, 0 otherwise
	 */
	private $is_spare;

	private $model_variables_changed; // Only variables which have been changed
	private $model_variables_set; // All variables which have been set (initially or with a setter)
	private static $change_values = array('status', 'comment');

	/* Parent tables */
	public $person;
	public $key;
	public $technician;
	public $key_status;

	/**
	 * Initialise and load related tables
	 */
	public static function init() {
		core::loadClass("database");
		core::loadClass("person_model");
		core::loadClass("key_model");
		core::loadClass("technician_model");
		core::loadClass("key_status_model");
	}

	/**
	 * Construct new key_history from field list
	 * 
	 * @return array
	 */
	public function __construct(array $fields = array()) {
/* Initialise everything as blank to avoid tripping up the permissions fitlers */
		$this -> id = '';
		$this -> date = '';
		$this -> person_id = '';
		$this -> key_id = '';
		$this -> technician_id = '';
		$this -> key_status_id = '';
		$this -> comment = '';
		$this -> change = '';
		$this -> is_spare = '';

		if(isset($fields['key_history.id'])) {
			$this -> set_id($fields['key_history.id']);
		}
		if(isset($fields['key_history.date'])) {
			$this -> set_date($fields['key_history.date']);
		}
		if(isset($fields['key_history.person_id'])) {
			$this -> set_person_id($fields['key_history.person_id']);
		}
		if(isset($fields['key_history.key_id'])) {
			$this -> set_key_id($fields['key_history.key_id']);
		}
		if(isset($fields['key_history.technician_id'])) {
			$this -> set_technician_id($fields['key_history.technician_id']);
		}
		if(isset($fields['key_history.key_status_id'])) {
			$this -> set_key_status_id($fields['key_history.key_status_id']);
		}
		if(isset($fields['key_history.comment'])) {
			$this -> set_comment($fields['key_history.comment']);
		}
		if(isset($fields['key_history.change'])) {
			$this -> set_change($fields['key_history.change']);
		}
		if(isset($fields['key_history.is_spare'])) {
			$this -> set_is_spare($fields['key_history.is_spare']);
		}

		$this -> model_variables_changed = array();
		$this -> person = new person_model($fields);
		$this -> key = new key_model($fields);
		$this -> technician = new technician_model($fields);
		$this -> key_status = new key_status_model($fields);
	}

	/**
	 * Convert key_history to shallow associative array
	 * 
	 * @return array
	 */
	private function to_array() {
		$values = array(
			'id' => $this -> id,
			'date' => $this -> date,
			'person_id' => $this -> person_id,
			'key_id' => $this -> key_id,
			'technician_id' => $this -> technician_id,
			'key_status_id' => $this -> key_status_id,
			'comment' => $this -> comment,
			'change' => $this -> change,
			'is_spare' => $this -> is_spare);
		return $values;
	}

	/**
	 * Convert key_history to associative array, including only visible fields,
	 * parent tables, and loaded child tables
	 * 
	 * @param string $role The user role to use
	 */
	public function to_array_filtered($role = "anon") {
		if(core::$permission[$role]['key_history']['read'] === false) {
			return false;
		}
		$values = array();
		$everything = $this -> to_array();
		foreach(core::$permission[$role]['key_history']['read'] as $field) {
			if(!isset($everything[$field])) {
				throw new Exception("Check permissions: '$field' is not a real field in key_history");
			}
			$values[$field] = $everything[$field];
		}
		$values['person'] = $this -> person -> to_array_filtered($role);
		$values['key'] = $this -> key -> to_array_filtered($role);
		$values['technician'] = $this -> technician -> to_array_filtered($role);
		$values['key_status'] = $this -> key_status -> to_array_filtered($role);
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
			"key_history.id" => $row[0],
			"key_history.date" => $row[1],
			"key_history.person_id" => $row[2],
			"key_history.key_id" => $row[3],
			"key_history.technician_id" => $row[4],
			"key_history.key_status_id" => $row[5],
			"key_history.comment" => $row[6],
			"key_history.change" => $row[7],
			"key_history.is_spare" => $row[8],
			"person.id" => $row[9],
			"person.code" => $row[10],
			"person.is_staff" => $row[11],
			"person.is_active" => $row[12],
			"person.firstname" => $row[13],
			"person.surname" => $row[14],
			"key.id" => $row[15],
			"key.serial" => $row[16],
			"key.person_id" => $row[17],
			"key.is_spare" => $row[18],
			"key.key_type_id" => $row[19],
			"key.key_status_id" => $row[20],
			"technician.id" => $row[21],
			"technician.login" => $row[22],
			"technician.name" => $row[23],
			"key_status.id" => $row[24],
			"key_status.name" => $row[25],
			"key_type.id" => $row[26],
			"key_type.name" => $row[27]);
		return $values;
	}

	/**
	 * Get id
	 * 
	 * @return int
	 */
	public function get_id() {
		if(!isset($this -> model_variables_set['id'])) {
			throw new Exception("key_history.id has not been initialised.");
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
			throw new Exception("key_history.id must be numeric");
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
			throw new Exception("key_history.date has not been initialised.");
		}
		return $this -> date;
	}

	/**
	 * Set date
	 * 
	 * @param string $date
	 */
	public function set_date($date) {
		// TODO: Add validation to key_history.date
		$this -> date = $date;
		$this -> model_variables_changed['date'] = true;
		$this -> model_variables_set['date'] = true;
	}

	/**
	 * Get person_id
	 * 
	 * @return int
	 */
	public function get_person_id() {
		if(!isset($this -> model_variables_set['person_id'])) {
			throw new Exception("key_history.person_id has not been initialised.");
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
			throw new Exception("key_history.person_id must be numeric");
		}
		$this -> person_id = $person_id;
		$this -> model_variables_changed['person_id'] = true;
		$this -> model_variables_set['person_id'] = true;
	}

	/**
	 * Get key_id
	 * 
	 * @return int
	 */
	public function get_key_id() {
		if(!isset($this -> model_variables_set['key_id'])) {
			throw new Exception("key_history.key_id has not been initialised.");
		}
		return $this -> key_id;
	}

	/**
	 * Set key_id
	 * 
	 * @param int $key_id
	 */
	public function set_key_id($key_id) {
		if(!is_numeric($key_id)) {
			throw new Exception("key_history.key_id must be numeric");
		}
		$this -> key_id = $key_id;
		$this -> model_variables_changed['key_id'] = true;
		$this -> model_variables_set['key_id'] = true;
	}

	/**
	 * Get technician_id
	 * 
	 * @return int
	 */
	public function get_technician_id() {
		if(!isset($this -> model_variables_set['technician_id'])) {
			throw new Exception("key_history.technician_id has not been initialised.");
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
			throw new Exception("key_history.technician_id must be numeric");
		}
		$this -> technician_id = $technician_id;
		$this -> model_variables_changed['technician_id'] = true;
		$this -> model_variables_set['technician_id'] = true;
	}

	/**
	 * Get key_status_id
	 * 
	 * @return int
	 */
	public function get_key_status_id() {
		if(!isset($this -> model_variables_set['key_status_id'])) {
			throw new Exception("key_history.key_status_id has not been initialised.");
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
			throw new Exception("key_history.key_status_id must be numeric");
		}
		$this -> key_status_id = $key_status_id;
		$this -> model_variables_changed['key_status_id'] = true;
		$this -> model_variables_set['key_status_id'] = true;
	}

	/**
	 * Get comment
	 * 
	 * @return string
	 */
	public function get_comment() {
		if(!isset($this -> model_variables_set['comment'])) {
			throw new Exception("key_history.comment has not been initialised.");
		}
		return $this -> comment;
	}

	/**
	 * Set comment
	 * 
	 * @param string $comment
	 */
	public function set_comment($comment) {
		if(strlen($comment) > 45) {
			throw new Exception("key_history.comment cannot be longer than 45 characters");
		}
		$this -> comment = $comment;
		$this -> model_variables_changed['comment'] = true;
		$this -> model_variables_set['comment'] = true;
	}

	/**
	 * Get change
	 * 
	 * @return string
	 */
	public function get_change() {
		if(!isset($this -> model_variables_set['change'])) {
			throw new Exception("key_history.change has not been initialised.");
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
			throw new Exception("key_history.change must be one of the defined values.");
		}
		$this -> change = $change;
		$this -> model_variables_changed['change'] = true;
		$this -> model_variables_set['change'] = true;
	}

	/**
	 * Get is_spare
	 * 
	 * @return int
	 */
	public function get_is_spare() {
		if(!isset($this -> model_variables_set['is_spare'])) {
			throw new Exception("key_history.is_spare has not been initialised.");
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
			throw new Exception("key_history.is_spare must be numeric");
		}
		$this -> is_spare = $is_spare;
		$this -> model_variables_changed['is_spare'] = true;
		$this -> model_variables_set['is_spare'] = true;
	}

	/**
	 * Update key_history
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
		$sth = database::$dbh -> prepare("UPDATE key_history SET $fields WHERE id = :id");
		$sth -> execute($data);
	}

	/**
	 * Add new key_history
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
		$sth = database::$dbh -> prepare("INSERT INTO key_history ($fields) VALUES ($vals);");
		$sth -> execute($data);
		$this -> set_id(database::$dbh->lastInsertId());
	}

	/**
	 * Delete key_history
	 */
	public function delete() {
		$sth = database::$dbh -> prepare("DELETE FROM key_history WHERE id = :id");
		$data['id'] = $this -> get_id();
		$sth -> execute($data);
	}

	/**
	 * Retrieve by primary key
	 */
	public static function get($id) {
		$sth = database::$dbh -> prepare("SELECT key_history.id, key_history.date, key_history.person_id, key_history.key_id, key_history.technician_id, key_history.key_status_id, key_history.comment, key_history.change, key_history.is_spare, person.id, person.code, person.is_staff, person.is_active, person.firstname, person.surname, key.id, key.serial, key.person_id, key.is_spare, key.key_type_id, key.key_status_id, technician.id, technician.login, technician.name, key_status.id, key_status.name, key_type.id, key_type.name FROM key_history JOIN person ON key_history.person_id = person.id JOIN key ON key_history.key_id = key.id JOIN technician ON key_history.technician_id = technician.id JOIN key_status ON key_history.key_status_id = key_status.id JOIN key_type ON key.key_type_id = key_type.id WHERE key_history.id = :id;");
		$sth -> execute(array('id' => $id));
		$row = $sth -> fetch(PDO::FETCH_NUM);
		if($row === false){
			return false;
		}
		$assoc = self::row_to_assoc($row);
		return new key_history_model($assoc);
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
		if($start > 0 && $limit > 0) {
			$ls = " LIMIT $start, " . ($start + $limit);
		}
		$sth = database::$dbh -> prepare("SELECT key_history.id, key_history.date, key_history.person_id, key_history.key_id, key_history.technician_id, key_history.key_status_id, key_history.comment, key_history.change, key_history.is_spare, person.id, person.code, person.is_staff, person.is_active, person.firstname, person.surname, key.id, key.serial, key.person_id, key.is_spare, key.key_type_id, key.key_status_id, technician.id, technician.login, technician.name, key_status.id, key_status.name, key_type.id, key_type.name FROM key_history JOIN person ON key_history.person_id = person.id JOIN key ON key_history.key_id = key.id JOIN technician ON key_history.technician_id = technician.id JOIN key_status ON key_history.key_status_id = key_status.id JOIN key_type ON key.key_type_id = key_type.id" . $ls . ";");
		$sth -> execute();
		$rows = $sth -> fetchAll(PDO::FETCH_NUM);
		$ret = array();
		foreach($rows as $row) {
			$assoc = self::row_to_assoc($row);
			$ret[] = new key_history_model($assoc);
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
		$sth = database::$dbh -> prepare("SELECT key_history.id, key_history.date, key_history.person_id, key_history.key_id, key_history.technician_id, key_history.key_status_id, key_history.comment, key_history.change, key_history.is_spare, person.id, person.code, person.is_staff, person.is_active, person.firstname, person.surname, key.id, key.serial, key.person_id, key.is_spare, key.key_type_id, key.key_status_id, technician.id, technician.login, technician.name, key_status.id, key_status.name, key_type.id, key_type.name FROM key_history JOIN person ON key_history.person_id = person.id JOIN key ON key_history.key_id = key.id JOIN technician ON key_history.technician_id = technician.id JOIN key_status ON key_history.key_status_id = key_status.id JOIN key_type ON key.key_type_id = key_type.id WHERE key_history.person_id = :person_id" . $ls . ";");
		$sth -> execute(array('person_id' => $person_id));
		$rows = $sth -> fetchAll(PDO::FETCH_NUM);
		$ret = array();
		foreach($rows as $row) {
			$assoc = self::row_to_assoc($row);
			$ret[] = new key_history_model($assoc);
		}
		return $ret;
	}

	/**
	 * List rows by key_id index
	 * 
	 * @param int $start Row to begin from. Default 0 (begin from start)
	 * @param int $limit Maximum number of rows to retrieve. Default -1 (no limit)
	 */
	public static function list_by_key_id($key_id, $start = 0, $limit = -1) {
		$ls = "";
		$start = (int)$start;
		$limit = (int)$limit;
		if($start > 0 && $limit > 0) {
			$ls = " LIMIT $start, " . ($start + $limit);
		}
		$sth = database::$dbh -> prepare("SELECT key_history.id, key_history.date, key_history.person_id, key_history.key_id, key_history.technician_id, key_history.key_status_id, key_history.comment, key_history.change, key_history.is_spare, person.id, person.code, person.is_staff, person.is_active, person.firstname, person.surname, key.id, key.serial, key.person_id, key.is_spare, key.key_type_id, key.key_status_id, technician.id, technician.login, technician.name, key_status.id, key_status.name, key_type.id, key_type.name FROM key_history JOIN person ON key_history.person_id = person.id JOIN key ON key_history.key_id = key.id JOIN technician ON key_history.technician_id = technician.id JOIN key_status ON key_history.key_status_id = key_status.id JOIN key_type ON key.key_type_id = key_type.id WHERE key_history.key_id = :key_id" . $ls . ";");
		$sth -> execute(array('key_id' => $key_id));
		$rows = $sth -> fetchAll(PDO::FETCH_NUM);
		$ret = array();
		foreach($rows as $row) {
			$assoc = self::row_to_assoc($row);
			$ret[] = new key_history_model($assoc);
		}
		return $ret;
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
		$sth = database::$dbh -> prepare("SELECT key_history.id, key_history.date, key_history.person_id, key_history.key_id, key_history.technician_id, key_history.key_status_id, key_history.comment, key_history.change, key_history.is_spare, person.id, person.code, person.is_staff, person.is_active, person.firstname, person.surname, key.id, key.serial, key.person_id, key.is_spare, key.key_type_id, key.key_status_id, technician.id, technician.login, technician.name, key_status.id, key_status.name, key_type.id, key_type.name FROM key_history JOIN person ON key_history.person_id = person.id JOIN key ON key_history.key_id = key.id JOIN technician ON key_history.technician_id = technician.id JOIN key_status ON key_history.key_status_id = key_status.id JOIN key_type ON key.key_type_id = key_type.id WHERE key_history.technician_id = :technician_id" . $ls . ";");
		$sth -> execute(array('technician_id' => $technician_id));
		$rows = $sth -> fetchAll(PDO::FETCH_NUM);
		$ret = array();
		foreach($rows as $row) {
			$assoc = self::row_to_assoc($row);
			$ret[] = new key_history_model($assoc);
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
		$sth = database::$dbh -> prepare("SELECT key_history.id, key_history.date, key_history.person_id, key_history.key_id, key_history.technician_id, key_history.key_status_id, key_history.comment, key_history.change, key_history.is_spare, person.id, person.code, person.is_staff, person.is_active, person.firstname, person.surname, key.id, key.serial, key.person_id, key.is_spare, key.key_type_id, key.key_status_id, technician.id, technician.login, technician.name, key_status.id, key_status.name, key_type.id, key_type.name FROM key_history JOIN person ON key_history.person_id = person.id JOIN key ON key_history.key_id = key.id JOIN technician ON key_history.technician_id = technician.id JOIN key_status ON key_history.key_status_id = key_status.id JOIN key_type ON key.key_type_id = key_type.id WHERE key_history.key_status_id = :key_status_id" . $ls . ";");
		$sth -> execute(array('key_status_id' => $key_status_id));
		$rows = $sth -> fetchAll(PDO::FETCH_NUM);
		$ret = array();
		foreach($rows as $row) {
			$assoc = self::row_to_assoc($row);
			$ret[] = new key_history_model($assoc);
		}
		return $ret;
	}
}
?>