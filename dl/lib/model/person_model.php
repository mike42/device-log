<?php
class person_model {
	/**
	 * @var int id Person's code, as found on their identification
	 */
	private $id;

	/**
	 * @var string code Identification code, as scanned from ID card
	 */
	private $code;

	/**
	 * @var int is_staff 1 for staff, 0 for non-staff
	 */
	private $is_staff;

	/**
	 * @var int is_active 0 for inactive people, 1 for active
	 */
	private $is_active;

	/**
	 * @var string firstname Person's given name
	 */
	private $firstname;

	/**
	 * @var string surname Person's surname
	 */
	private $surname;

	private $model_variables_changed; // Only variables which have been changed
	private $model_variables_set; // All variables which have been set (initially or with a setter)

	/* Child tables */
	public $list_device;
	public $list_software;
	public $list_software_history;
	public $list_key;
	public $list_key_history;
	public $list_device_history;

	/**
	 * Initialise and load related tables
	 */
	public static function init() {
		core::loadClass("database");

		/* Child tables */
		core::loadClass("device_model");
		core::loadClass("software_model");
		core::loadClass("software_history_model");
		core::loadClass("key_model");
		core::loadClass("key_history_model");
		core::loadClass("device_history_model");
	}

	/**
	 * Construct new person from field list
	 * 
	 * @return array
	 */
	public function __construct(array $fields = array()) {
/* Initialise everything as blank to avoid tripping up the permissions fitlers */
		$this -> id = '';
		$this -> code = '';
		$this -> is_staff = '';
		$this -> is_active = '';
		$this -> firstname = '';
		$this -> surname = '';

		if(isset($fields['person.id'])) {
			$this -> set_id($fields['person.id']);
		}
		if(isset($fields['person.code'])) {
			$this -> set_code($fields['person.code']);
		}
		if(isset($fields['person.is_staff'])) {
			$this -> set_is_staff($fields['person.is_staff']);
		}
		if(isset($fields['person.is_active'])) {
			$this -> set_is_active($fields['person.is_active']);
		}
		if(isset($fields['person.firstname'])) {
			$this -> set_firstname($fields['person.firstname']);
		}
		if(isset($fields['person.surname'])) {
			$this -> set_surname($fields['person.surname']);
		}

		$this -> model_variables_changed = array();
		$this -> list_device = array();
		$this -> list_software = array();
		$this -> list_software_history = array();
		$this -> list_key = array();
		$this -> list_key_history = array();
		$this -> list_device_history = array();
	}

	/**
	 * Convert person to shallow associative array
	 * 
	 * @return array
	 */
	private function to_array() {
		$values = array(
			'id' => $this -> id,
			'code' => $this -> code,
			'is_staff' => $this -> is_staff,
			'is_active' => $this -> is_active,
			'firstname' => $this -> firstname,
			'surname' => $this -> surname);
		return $values;
	}

	/**
	 * Convert person to associative array, including only visible fields,
	 * parent tables, and loaded child tables
	 * 
	 * @param string $role The user role to use
	 */
	public function to_array_filtered($role = "anon") {
		if(core::$permission[$role]['person']['read'] === false) {
			return false;
		}
		$values = array();
		$everything = $this -> to_array();
		foreach(core::$permission[$role]['person']['read'] as $field) {
			if(!isset($everything[$field])) {
				throw new Exception("Check permissions: '$field' is not a real field in person");
			}
			$values[$field] = $everything[$field];
		}

		/* Add filtered versions of everything that's been loaded */
		$values['device'] = array();
		$values['software'] = array();
		$values['software_history'] = array();
		$values['key'] = array();
		$values['key_history'] = array();
		$values['device_history'] = array();
		foreach($this -> list_device as $device) {
			$values['device'][] = $device -> to_array_filtered($role);
		}
		foreach($this -> list_software as $software) {
			$values['software'][] = $software -> to_array_filtered($role);
		}
		foreach($this -> list_software_history as $software_history) {
			$values['software_history'][] = $software_history -> to_array_filtered($role);
		}
		foreach($this -> list_key as $key) {
			$values['key'][] = $key -> to_array_filtered($role);
		}
		foreach($this -> list_key_history as $key_history) {
			$values['key_history'][] = $key_history -> to_array_filtered($role);
		}
		foreach($this -> list_device_history as $device_history) {
			$values['device_history'][] = $device_history -> to_array_filtered($role);
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
			"person.id" => $row[0],
			"person.code" => $row[1],
			"person.is_staff" => $row[2],
			"person.is_active" => $row[3],
			"person.firstname" => $row[4],
			"person.surname" => $row[5]);
		return $values;
	}

	/**
	 * Get id
	 * 
	 * @return int
	 */
	public function get_id() {
		if(!isset($this -> model_variables_set['id'])) {
			throw new Exception("person.id has not been initialised.");
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
			throw new Exception("person.id must be numeric");
		}
		$this -> id = $id;
		$this -> model_variables_changed['id'] = true;
		$this -> model_variables_set['id'] = true;
	}

	/**
	 * Get code
	 * 
	 * @return string
	 */
	public function get_code() {
		if(!isset($this -> model_variables_set['code'])) {
			throw new Exception("person.code has not been initialised.");
		}
		return $this -> code;
	}

	/**
	 * Set code
	 * 
	 * @param string $code
	 */
	public function set_code($code) {
		if(strlen($code) > 5) {
			throw new Exception("person.code cannot be longer than 5 characters");
		}
		$this -> code = $code;
		$this -> model_variables_changed['code'] = true;
		$this -> model_variables_set['code'] = true;
	}

	/**
	 * Get is_staff
	 * 
	 * @return int
	 */
	public function get_is_staff() {
		if(!isset($this -> model_variables_set['is_staff'])) {
			throw new Exception("person.is_staff has not been initialised.");
		}
		return $this -> is_staff;
	}

	/**
	 * Set is_staff
	 * 
	 * @param int $is_staff
	 */
	public function set_is_staff($is_staff) {
		if(!is_numeric($is_staff)) {
			throw new Exception("person.is_staff must be numeric");
		}
		$this -> is_staff = $is_staff;
		$this -> model_variables_changed['is_staff'] = true;
		$this -> model_variables_set['is_staff'] = true;
	}

	/**
	 * Get is_active
	 * 
	 * @return int
	 */
	public function get_is_active() {
		if(!isset($this -> model_variables_set['is_active'])) {
			throw new Exception("person.is_active has not been initialised.");
		}
		return $this -> is_active;
	}

	/**
	 * Set is_active
	 * 
	 * @param int $is_active
	 */
	public function set_is_active($is_active) {
		if(!is_numeric($is_active)) {
			throw new Exception("person.is_active must be numeric");
		}
		$this -> is_active = $is_active;
		$this -> model_variables_changed['is_active'] = true;
		$this -> model_variables_set['is_active'] = true;
	}

	/**
	 * Get firstname
	 * 
	 * @return string
	 */
	public function get_firstname() {
		if(!isset($this -> model_variables_set['firstname'])) {
			throw new Exception("person.firstname has not been initialised.");
		}
		return $this -> firstname;
	}

	/**
	 * Set firstname
	 * 
	 * @param string $firstname
	 */
	public function set_firstname($firstname) {
		if(strlen($firstname) > 64) {
			throw new Exception("person.firstname cannot be longer than 64 characters");
		}
		$this -> firstname = $firstname;
		$this -> model_variables_changed['firstname'] = true;
		$this -> model_variables_set['firstname'] = true;
	}

	/**
	 * Get surname
	 * 
	 * @return string
	 */
	public function get_surname() {
		if(!isset($this -> model_variables_set['surname'])) {
			throw new Exception("person.surname has not been initialised.");
		}
		return $this -> surname;
	}

	/**
	 * Set surname
	 * 
	 * @param string $surname
	 */
	public function set_surname($surname) {
		if(strlen($surname) > 64) {
			throw new Exception("person.surname cannot be longer than 64 characters");
		}
		$this -> surname = $surname;
		$this -> model_variables_changed['surname'] = true;
		$this -> model_variables_set['surname'] = true;
	}

	/**
	 * Update person
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
		$sth = database::$dbh -> prepare("UPDATE person SET $fields WHERE id = :id");
		$sth -> execute($data);
	}

	/**
	 * Add new person
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
		$sth = database::$dbh -> prepare("INSERT INTO person ($fields) VALUES ($vals);");
		$sth -> execute($data);
		$this -> set_id(database::$dbh->lastInsertId());
	}

	/**
	 * Delete person
	 */
	public function delete() {
		$sth = database::$dbh -> prepare("DELETE FROM person WHERE id = :id");
		$data['id'] = $this -> get_id();
		$sth -> execute($data);
	}

	/**
	 * List associated rows from device table
	 * 
	 * @param int $start Row to begin from. Default 0 (begin from start)
	 * @param int $limit Maximum number of rows to retrieve. Default -1 (no limit)
	 */
	public function populate_list_device($start = 0, $limit = -1) {
		$person_id = $this -> get_id();
		$this -> list_device = device_model::list_by_person_id($person_id, $start, $limit);
	}

	/**
	 * List associated rows from software table
	 * 
	 * @param int $start Row to begin from. Default 0 (begin from start)
	 * @param int $limit Maximum number of rows to retrieve. Default -1 (no limit)
	 */
	public function populate_list_software($start = 0, $limit = -1) {
		$person_id = $this -> get_id();
		$this -> list_software = software_model::list_by_software_person_id($person_id, $start, $limit);
	}

	/**
	 * List associated rows from software_history table
	 * 
	 * @param int $start Row to begin from. Default 0 (begin from start)
	 * @param int $limit Maximum number of rows to retrieve. Default -1 (no limit)
	 */
	public function populate_list_software_history($start = 0, $limit = -1) {
		$person_id = $this -> get_id();
		$this -> list_software_history = software_history_model::list_by_person_id($person_id, $start, $limit);
	}

	/**
	 * List associated rows from key table
	 * 
	 * @param int $start Row to begin from. Default 0 (begin from start)
	 * @param int $limit Maximum number of rows to retrieve. Default -1 (no limit)
	 */
	public function populate_list_key($start = 0, $limit = -1) {
		$person_id = $this -> get_id();
		$this -> list_key = key_model::list_by_person_id($person_id, $start, $limit);
	}

	/**
	 * List associated rows from key_history table
	 * 
	 * @param int $start Row to begin from. Default 0 (begin from start)
	 * @param int $limit Maximum number of rows to retrieve. Default -1 (no limit)
	 */
	public function populate_list_key_history($start = 0, $limit = -1) {
		$person_id = $this -> get_id();
		$this -> list_key_history = key_history_model::list_by_person_id($person_id, $start, $limit);
	}

	/**
	 * List associated rows from device_history table
	 * 
	 * @param int $start Row to begin from. Default 0 (begin from start)
	 * @param int $limit Maximum number of rows to retrieve. Default -1 (no limit)
	 */
	public function populate_list_device_history($start = 0, $limit = -1) {
		$person_id = $this -> get_id();
		$this -> list_device_history = device_history_model::list_by_person_id($person_id, $start, $limit);
	}

	/**
	 * Retrieve by primary key
	 */
	public static function get($id) {
		$sth = database::$dbh -> prepare("SELECT person.id, person.code, person.is_staff, person.is_active, person.firstname, person.surname FROM person  WHERE person.id = :id;");
		$sth -> execute(array('id' => $id));
		$row = $sth -> fetch(PDO::FETCH_NUM);
		if($row === false){
			return false;
		}
		$assoc = self::row_to_assoc($row);
		return new person_model($assoc);
	}

	/**
	 * Retrieve by person_code
	 */
	public static function get_by_person_code($code) {
		$sth = database::$dbh -> prepare("SELECT person.id, person.code, person.is_staff, person.is_active, person.firstname, person.surname FROM person  WHERE person.code = :code;");
		$sth -> execute(array('code' => $code));
		$row = $sth -> fetch(PDO::FETCH_NUM);
		if($row === false){
			return false;
		}
		$assoc = self::row_to_assoc($row);
		return new person_model($assoc);
	}
}
?>