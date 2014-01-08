<?php
class technician_model {
	private $id;
	private $login;
	private $name;
	private $model_variables_changed; // Only variables which have been changed
	private $model_variables_set; // All variables which have been set (initially or with a setter)

	/* Child tables */
	public $list_software_history;
	public $list_key_history;
	public $list_device_history;

	/**
	 * Construct new technician from field list
	 * 
	 * @return array
	 */
	public function __construct(array $fields = array()) {
		if(isset($fields['technician.id'])) {
			$this -> set_id($fields['technician.id']);
		}
		if(isset($fields['technician.login'])) {
			$this -> set_login($fields['technician.login']);
		}
		if(isset($fields['technician.name'])) {
			$this -> set_name($fields['technician.name']);
		}

		$this -> model_variables_changed = array();
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
			'name' => $this -> name);
		return $values;
	}

	/**
	 * Convert technician to associative array, including only visible fields,
	 * parent tables, and loaded child tables
	 * 
	 * @param string $role The user role to use
	 */
	public function to_array_filtered($role = "anon") {
		// TODO: Insert code for technician permission-check
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
			"technician.name" => $row[2]);
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
	 * Update technician
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
		$sth = database::$dbh -> prepare("UPDATE technician SET $fields WHERE id = :id");
		$sth -> execute($this -> to_array());
	}

	/**
	 * Add new technician
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
		$sth = database::$dbh -> prepare("INSERT INTO technician ($fields) VALUES ($vals);");
		$sth -> execute($this -> to_array());
	}

	/**
	 * Delete technician
	 */
	public function delete() {
		$sth = database::$dbh -> prepare("DELETE FROM technician WHERE id = :id");
		$sth -> execute($this -> to_array());
	}

	/**
	 * Get associated rows from software_history table
	 * 
	 * @param int $start Row to begin from. Default 0 (begin from start)
	 * @param int $limit Maximum number of rows to retrieve. Default -1 (no limit)
	 */
	public function populate_list_software_history($start = 0, $limit = -1) {
		$this -> list_software_history = software_history_model::list_by_technician_id($technician_id, $start, $limit);
	}

	/**
	 * Get associated rows from key_history table
	 * 
	 * @param int $start Row to begin from. Default 0 (begin from start)
	 * @param int $limit Maximum number of rows to retrieve. Default -1 (no limit)
	 */
	public function populate_list_key_history($start = 0, $limit = -1) {
		$this -> list_key_history = key_history_model::list_by_technician_id($technician_id, $start, $limit);
	}

	/**
	 * Get associated rows from device_history table
	 * 
	 * @param int $start Row to begin from. Default 0 (begin from start)
	 * @param int $limit Maximum number of rows to retrieve. Default -1 (no limit)
	 */
	public function populate_list_device_history($start = 0, $limit = -1) {
		$this -> list_device_history = device_history_model::list_by_technician_id($technician_id, $start, $limit);
	}

	public static function get($id) {
		$sth = database::$dbh -> prepare("SELECT technician.id, technician.login, technician.name FROM technician  WHERE technician.id = :id;");
		$sth -> execute(array('id' => $id));
		$row = $sth -> fetch(PDO::FETCH_NUM);
		$assoc = self::row_to_assoc($row);
		return new technician_model($assoc);
	}

	public static function get_by_technician_name($name) {
		$sth = database::$dbh -> prepare("SELECT technician.id, technician.login, technician.name FROM technician  WHERE technician.name = :name;");
		$sth -> execute(array('name' => $name));
		$row = $sth -> fetch(PDO::FETCH_NUM);
		$assoc = self::row_to_assoc($row);
		return new technician_model($assoc);
	}

	public static function get_by_technician_login($login) {
		$sth = database::$dbh -> prepare("SELECT technician.id, technician.login, technician.name FROM technician  WHERE technician.login = :login;");
		$sth -> execute(array('login' => $login));
		$row = $sth -> fetch(PDO::FETCH_NUM);
		$assoc = self::row_to_assoc($row);
		return new technician_model($assoc);
	}
}
?>