<?php
class key_status_model {
	/**
	 * @var int id ID for the status
	 */
	private $id;

	/**
	 * @var string name Human-readable status code
	 */
	private $name;

	private $model_variables_changed; // Only variables which have been changed
	private $model_variables_set; // All variables which have been set (initially or with a setter)

	/* Child tables */
	public $list_doorkey;
	public $list_key_history;

	/**
	 * Initialise and load related tables
	 */
	public static function init() {
		core::loadClass("database");

		/* Child tables */
		core::loadClass("doorkey_model");
		core::loadClass("key_history_model");
	}

	/**
	 * Construct new key_status from field list
	 * 
	 * @return array
	 */
	public function __construct(array $fields = array()) {
		/* Initialise everything as blank to avoid tripping up the permissions fitlers */
		$this -> id = '';
		$this -> name = '';

		if(isset($fields['key_status.id'])) {
			$this -> set_id($fields['key_status.id']);
		}
		if(isset($fields['key_status.name'])) {
			$this -> set_name($fields['key_status.name']);
		}

		$this -> model_variables_changed = array();
		$this -> list_doorkey = array();
		$this -> list_key_history = array();
	}

	/**
	 * Convert key_status to shallow associative array
	 * 
	 * @return array
	 */
	private function to_array() {
		$values = array(
			'id' => $this -> id,
			'name' => $this -> name);
		return $values;
	}

	/**
	 * Convert key_status to associative array, including only visible fields,
	 * parent tables, and loaded child tables
	 * 
	 * @param string $role The user role to use
	 */
	public function to_array_filtered($role = "anon") {
		if(core::$permission[$role]['key_status']['read'] === false) {
			return false;
		}
		$values = array();
		$everything = $this -> to_array();
		foreach(core::$permission[$role]['key_status']['read'] as $field) {
			if(!isset($everything[$field])) {
				throw new Exception("Check permissions: '$field' is not a real field in key_status");
			}
			$values[$field] = $everything[$field];
		}

		/* Add filtered versions of everything that's been loaded */
		$values['doorkey'] = array();
		$values['key_history'] = array();
		foreach($this -> list_doorkey as $doorkey) {
			$values['doorkey'][] = $doorkey -> to_array_filtered($role);
		}
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
			"key_status.id" => $row[0],
			"key_status.name" => $row[1]);
		return $values;
	}

	/**
	 * Get id
	 * 
	 * @return int
	 */
	public function get_id() {
		if(!isset($this -> model_variables_set['id'])) {
			throw new Exception("key_status.id has not been initialised.");
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
			throw new Exception("key_status.id must be numeric");
		}
		$this -> id = $id;
		$this -> model_variables_changed['id'] = true;
		$this -> model_variables_set['id'] = true;
	}

	/**
	 * Get name
	 * 
	 * @return string
	 */
	public function get_name() {
		if(!isset($this -> model_variables_set['name'])) {
			throw new Exception("key_status.name has not been initialised.");
		}
		return $this -> name;
	}

	/**
	 * Set name
	 * 
	 * @param string $name
	 */
	public function set_name($name) {
		if(strlen($name) > 45) {
			throw new Exception("key_status.name cannot be longer than 45 characters");
		}
		$this -> name = $name;
		$this -> model_variables_changed['name'] = true;
		$this -> model_variables_set['name'] = true;
	}

	/**
	 * Update key_status
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
		$sth = database::$dbh -> prepare("UPDATE key_status SET $fields WHERE id = :id");
		$sth -> execute($data);
	}

	/**
	 * Add new key_status
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
		$sth = database::$dbh -> prepare("INSERT INTO key_status ($fields) VALUES ($vals);");
		$sth -> execute($data);
		$this -> set_id(database::$dbh->lastInsertId());
	}

	/**
	 * Delete key_status
	 */
	public function delete() {
		$sth = database::$dbh -> prepare("DELETE FROM key_status WHERE id = :id");
		$data['id'] = $this -> get_id();
		$sth -> execute($data);
	}

	/**
	 * List associated rows from doorkey table
	 * 
	 * @param int $start Row to begin from. Default 0 (begin from start)
	 * @param int $limit Maximum number of rows to retrieve. Default -1 (no limit)
	 */
	public function populate_list_doorkey($start = 0, $limit = -1) {
		$key_status_id = $this -> get_id();
		$this -> list_doorkey = doorkey_model::list_by_key_status_id($key_status_id, $start, $limit);
	}

	/**
	 * List associated rows from key_history table
	 * 
	 * @param int $start Row to begin from. Default 0 (begin from start)
	 * @param int $limit Maximum number of rows to retrieve. Default -1 (no limit)
	 */
	public function populate_list_key_history($start = 0, $limit = -1) {
		$key_status_id = $this -> get_id();
		$this -> list_key_history = key_history_model::list_by_key_status_id($key_status_id, $start, $limit);
	}

	/**
	 * Retrieve by primary key
	 */
	public static function get($id) {
		$sth = database::$dbh -> prepare("SELECT key_status.id, key_status.name FROM key_status  WHERE key_status.id = :id;");
		$sth -> execute(array('id' => $id));
		$row = $sth -> fetch(PDO::FETCH_NUM);
		if($row === false){
			return false;
		}
		$assoc = self::row_to_assoc($row);
		return new key_status_model($assoc);
	}

	/**
	 * Retrieve by name
	 */
	public static function get_by_name($name) {
		$sth = database::$dbh -> prepare("SELECT key_status.id, key_status.name FROM key_status  WHERE key_status.name = :name;");
		$sth -> execute(array('name' => $name));
		$row = $sth -> fetch(PDO::FETCH_NUM);
		if($row === false){
			return false;
		}
		$assoc = self::row_to_assoc($row);
		return new key_status_model($assoc);
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
		$sth = database::$dbh -> prepare("SELECT key_status.id, key_status.name FROM key_status " . $ls . ";");
		$sth -> execute();
		$rows = $sth -> fetchAll(PDO::FETCH_NUM);
		$ret = array();
		foreach($rows as $row) {
			$assoc = self::row_to_assoc($row);
			$ret[] = new key_status_model($assoc);
		}
		return $ret;
	}

	/**
	 * Simple search within name field
	 * 
	 * @param int $start Row to begin from. Default 0 (begin from start)
	 * @param int $limit Maximum number of rows to retrieve. Default -1 (no limit)
	 */
	public static function search_by_name($search, $start = 0, $limit = -1) {
		$ls = "";
		$start = (int)$start;
		$limit = (int)$limit;
		if($start >= 0 && $limit > 0) {
			$ls = " LIMIT $start, $limit";
		}
		$sth = database::$dbh -> prepare("SELECT key_status.id, key_status.name FROM key_status  WHERE name LIKE :search" . $ls . ";");
		$sth -> execute(array('search' => "%".$search."%"));
		$rows = $sth -> fetchAll(PDO::FETCH_NUM);
		$ret = array();
		foreach($rows as $row) {
			$assoc = self::row_to_assoc($row);
			$ret[] = new key_status_model($assoc);
		}
		return $ret;
	}
}
?>