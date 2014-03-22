<?php
class technician_model {
	/**
	 * @var int id Internal technician ID
	 */
	private $id;

	/**
	 * @var string login Login to use for authentication
	 */
	private $login;

	/**
	 * @var string name Name for display purposes
	 */
	private $name;

	/**
	 * @var int is_active
	 */
	private $is_active;

	private $model_variables_changed; // Only variables which have been changed
	private $model_variables_set; // All variables which have been set (initially or with a setter)

	/* Child tables */
	public $list_software_history;
	public $list_key_history;
	public $list_device_history;

	/* Sort clause to add when listing rows from this table */
	const SORT_CLAUSE = " ORDER BY `technician`.`id`";

	/**
	 * Initialise and load related tables
	 */
	public static function init() {
		core::loadClass("database");

		/* Child tables */
		core::loadClass("software_history_model");
		core::loadClass("key_history_model");
		core::loadClass("device_history_model");
	}

	/**
	 * Construct new technician from field list
	 * 
	 * @return array
	 */
	public function __construct(array $fields = array()) {
		/* Initialise everything as blank to avoid tripping up the permissions fitlers */
		$this -> id = '';
		$this -> login = '';
		$this -> name = '';
		$this -> is_active = '';

		if(isset($fields['technician.id'])) {
			$this -> set_id($fields['technician.id']);
		}
		if(isset($fields['technician.login'])) {
			$this -> set_login($fields['technician.login']);
		}
		if(isset($fields['technician.name'])) {
			$this -> set_name($fields['technician.name']);
		}
		if(isset($fields['technician.is_active'])) {
			$this -> set_is_active($fields['technician.is_active']);
		}

		$this -> model_variables_changed = array();
		$this -> list_software_history = array();
		$this -> list_key_history = array();
		$this -> list_device_history = array();
	}

	/**
	 * Convert technician to shallow associative array
	 * 
	 * @return array
	 */
	private function to_array() {
		$values = array(
			'id' => $this -> id,
			'login' => $this -> login,
			'name' => $this -> name,
			'is_active' => $this -> is_active);
		return $values;
	}

	/**
	 * Convert technician to associative array, including only visible fields,
	 * parent tables, and loaded child tables
	 * 
	 * @param string $role The user role to use
	 */
	public function to_array_filtered($role = "anon") {
		if(core::$permission[$role]['technician']['read'] === false) {
			return false;
		}
		$values = array();
		$everything = $this -> to_array();
		foreach(core::$permission[$role]['technician']['read'] as $field) {
			if(!isset($everything[$field])) {
				throw new Exception("Check permissions: '$field' is not a real field in technician");
			}
			$values[$field] = $everything[$field];
		}

		/* Add filtered versions of everything that's been loaded */
		$values['software_history'] = array();
		$values['key_history'] = array();
		$values['device_history'] = array();
		foreach($this -> list_software_history as $software_history) {
			$values['software_history'][] = $software_history -> to_array_filtered($role);
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
			"technician.id" => $row[0],
			"technician.login" => $row[1],
			"technician.name" => $row[2],
			"technician.is_active" => $row[3]);
		return $values;
	}

	/**
	 * Get id
	 * 
	 * @return int
	 */
	public function get_id() {
		if(!isset($this -> model_variables_set['id'])) {
			throw new Exception("technician.id has not been initialised.");
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
			throw new Exception("technician.id must be numeric");
		}
		$this -> id = $id;
		$this -> model_variables_changed['id'] = true;
		$this -> model_variables_set['id'] = true;
	}

	/**
	 * Get login
	 * 
	 * @return string
	 */
	public function get_login() {
		if(!isset($this -> model_variables_set['login'])) {
			throw new Exception("technician.login has not been initialised.");
		}
		return $this -> login;
	}

	/**
	 * Set login
	 * 
	 * @param string $login
	 */
	public function set_login($login) {
		if(strlen($login) > 45) {
			throw new Exception("technician.login cannot be longer than 45 characters");
		}
		$this -> login = $login;
		$this -> model_variables_changed['login'] = true;
		$this -> model_variables_set['login'] = true;
	}

	/**
	 * Get name
	 * 
	 * @return string
	 */
	public function get_name() {
		if(!isset($this -> model_variables_set['name'])) {
			throw new Exception("technician.name has not been initialised.");
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
			throw new Exception("technician.name cannot be longer than 45 characters");
		}
		$this -> name = $name;
		$this -> model_variables_changed['name'] = true;
		$this -> model_variables_set['name'] = true;
	}

	/**
	 * Get is_active
	 * 
	 * @return int
	 */
	public function get_is_active() {
		if(!isset($this -> model_variables_set['is_active'])) {
			throw new Exception("technician.is_active has not been initialised.");
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
			throw new Exception("technician.is_active must be numeric");
		}
		$this -> is_active = $is_active;
		$this -> model_variables_changed['is_active'] = true;
		$this -> model_variables_set['is_active'] = true;
	}

	/**
	 * Update technician
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
			$fieldset[] = "`$col` = :$col";
			$data[$col] = $everything[$col];
		}
		$fields = implode(", ", $fieldset);

		/* Execute query */
		$sth = database::$dbh -> prepare("UPDATE `technician` SET $fields WHERE `technician`.`id` = :id");
		$sth -> execute($data);
	}

	/**
	 * Add new technician
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
			$fieldset[] = "`$col`";
			$fieldset_colon[] = ":$col";
			$data[$col] = $everything[$col];
		}
		$fields = implode(", ", $fieldset);
		$vals = implode(", ", $fieldset_colon);

		/* Execute query */
		$sth = database::$dbh -> prepare("INSERT INTO `technician` ($fields) VALUES ($vals);");
		$sth -> execute($data);
		$this -> set_id(database::$dbh->lastInsertId());
	}

	/**
	 * Delete technician
	 */
	public function delete() {
		$sth = database::$dbh -> prepare("DELETE FROM `technician` WHERE `technician`.`id` = :id");
		$data['id'] = $this -> get_id();
		$sth -> execute($data);
	}

	/**
	 * List associated rows from software_history table
	 * 
	 * @param int $start Row to begin from. Default 0 (begin from start)
	 * @param int $limit Maximum number of rows to retrieve. Default -1 (no limit)
	 */
	public function populate_list_software_history($start = 0, $limit = -1) {
		$technician_id = $this -> get_id();
		$this -> list_software_history = software_history_model::list_by_technician_id($technician_id, $start, $limit);
	}

	/**
	 * List associated rows from key_history table
	 * 
	 * @param int $start Row to begin from. Default 0 (begin from start)
	 * @param int $limit Maximum number of rows to retrieve. Default -1 (no limit)
	 */
	public function populate_list_key_history($start = 0, $limit = -1) {
		$technician_id = $this -> get_id();
		$this -> list_key_history = key_history_model::list_by_technician_id($technician_id, $start, $limit);
	}

	/**
	 * List associated rows from device_history table
	 * 
	 * @param int $start Row to begin from. Default 0 (begin from start)
	 * @param int $limit Maximum number of rows to retrieve. Default -1 (no limit)
	 */
	public function populate_list_device_history($start = 0, $limit = -1) {
		$technician_id = $this -> get_id();
		$this -> list_device_history = device_history_model::list_by_technician_id($technician_id, $start, $limit);
	}

	/**
	 * Retrieve by primary key
	 */
	public static function get($id) {
		$sth = database::$dbh -> prepare("SELECT `technician`.`id`, `technician`.`login`, `technician`.`name`, `technician`.`is_active` FROM technician  WHERE `technician`.`id` = :id;");
		$sth -> execute(array('id' => $id));
		$row = $sth -> fetch(PDO::FETCH_NUM);
		if($row === false){
			return false;
		}
		$assoc = self::row_to_assoc($row);
		return new technician_model($assoc);
	}

	/**
	 * Retrieve by technician_name
	 */
	public static function get_by_technician_name($name) {
		$sth = database::$dbh -> prepare("SELECT `technician`.`id`, `technician`.`login`, `technician`.`name`, `technician`.`is_active` FROM technician  WHERE `technician`.`name` = :name;");
		$sth -> execute(array('name' => $name));
		$row = $sth -> fetch(PDO::FETCH_NUM);
		if($row === false){
			return false;
		}
		$assoc = self::row_to_assoc($row);
		return new technician_model($assoc);
	}

	/**
	 * Retrieve by technician_login
	 */
	public static function get_by_technician_login($login) {
		$sth = database::$dbh -> prepare("SELECT `technician`.`id`, `technician`.`login`, `technician`.`name`, `technician`.`is_active` FROM technician  WHERE `technician`.`login` = :login;");
		$sth -> execute(array('login' => $login));
		$row = $sth -> fetch(PDO::FETCH_NUM);
		if($row === false){
			return false;
		}
		$assoc = self::row_to_assoc($row);
		return new technician_model($assoc);
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
		$sth = database::$dbh -> prepare("SELECT `technician`.`id`, `technician`.`login`, `technician`.`name`, `technician`.`is_active` FROM `technician` " . self::SORT_CLAUSE . $ls . ";");
		$sth -> execute();
		$rows = $sth -> fetchAll(PDO::FETCH_NUM);
		$ret = array();
		foreach($rows as $row) {
			$assoc = self::row_to_assoc($row);
			$ret[] = new technician_model($assoc);
		}
		return $ret;
	}

	/**
	 * Simple search within login field
	 * 
	 * @param int $start Row to begin from. Default 0 (begin from start)
	 * @param int $limit Maximum number of rows to retrieve. Default -1 (no limit)
	 */
	public static function search_by_login($search, $start = 0, $limit = -1) {
		$ls = "";
		$start = (int)$start;
		$limit = (int)$limit;
		if($start >= 0 && $limit > 0) {
			$ls = " LIMIT $start, $limit";
		}
		$sth = database::$dbh -> prepare("SELECT `technician`.`id`, `technician`.`login`, `technician`.`name`, `technician`.`is_active` FROM `technician`  WHERE login LIKE :search" . self::SORT_CLAUSE . $ls . ";");
		$sth -> execute(array('search' => "%".$search."%"));
		$rows = $sth -> fetchAll(PDO::FETCH_NUM);
		$ret = array();
		foreach($rows as $row) {
			$assoc = self::row_to_assoc($row);
			$ret[] = new technician_model($assoc);
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
		$sth = database::$dbh -> prepare("SELECT `technician`.`id`, `technician`.`login`, `technician`.`name`, `technician`.`is_active` FROM `technician`  WHERE name LIKE :search" . self::SORT_CLAUSE . $ls . ";");
		$sth -> execute(array('search' => "%".$search."%"));
		$rows = $sth -> fetchAll(PDO::FETCH_NUM);
		$ret = array();
		foreach($rows as $row) {
			$assoc = self::row_to_assoc($row);
			$ret[] = new technician_model($assoc);
		}
		return $ret;
	}
}
?>