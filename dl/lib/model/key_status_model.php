<?php
class key_status_model {
	private $id;
	private $name;
	private $model_variables_changed; // Only variables which have been changed
	private $model_variables_set; // All variables which have been set (initially or with a setter)

	/* Child tables */
	public $list_key;
	public $list_key_history;

	/**
	 * Construct new key_status from field list
	 * 
	 * @return array
	 */
	public function __construct(array $fields = array()) {
		if(isset($fields['key_status.id'])) {
			$this -> set_id($fields['key_status.id']);
		}
		if(isset($fields['key_status.name'])) {
			$this -> set_name($fields['key_status.name']);
		}

		$this -> model_variables_changed = array();
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
		// TODO: Insert code for key_status permission-check
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
		foreach($this -> model_variables_changed as $col => $changed) {
			$fieldset[] = "$col = :$col";
		}
		$fields = implode(", ", $fieldset);

		/* Execute query */
		$sth = database::$dbh -> prepare("UPDATE key_status SET $fields WHERE id = :id");
		$sth -> execute($this -> to_array());
	}

	/**
	 * Add new key_status
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
		$sth = database::$dbh -> prepare("INSERT INTO key_status ($fields) VALUES ($vals);");
		$sth -> execute($this -> to_array());
	}

	/**
	 * Delete key_status
	 */
	public function delete() {
		$sth = database::$dbh -> prepare("DELETE FROM key_status WHERE id = :id");
		$sth -> execute($this -> to_array());
	}

	/**
	 * Get associated rows from key table
	 * 
	 * @param int $start Row to begin from. Default 0 (begin from start)
	 * @param int $limit Maximum number of rows to retrieve. Default -1 (no limit)
	 */
	public function populate_list_key($start = 0, $limit = -1) {
		$this -> list_key = key_model::list_by_key_status_id($key_status_id, $start, $limit);
	}

	/**
	 * Get associated rows from key_history table
	 * 
	 * @param int $start Row to begin from. Default 0 (begin from start)
	 * @param int $limit Maximum number of rows to retrieve. Default -1 (no limit)
	 */
	public function populate_list_key_history($start = 0, $limit = -1) {
		$this -> list_key_history = key_history_model::list_by_key_status_id($key_status_id, $start, $limit);
	}

	public static function get($id) {
		$sth = database::$dbh -> prepare("SELECT key_status.id, key_status.name FROM key_status  WHERE key_status.id = :id;");
		$sth -> execute(array('id' => $id));
		$row = $sth -> fetch(PDO::FETCH_NUM);
		$assoc = self::row_to_assoc($row);
		return new key_status_model($assoc);
	}

	public static function get_by_name($name) {
		$sth = database::$dbh -> prepare("SELECT key_status.id, key_status.name FROM key_status  WHERE key_status.name = :name;");
		$sth -> execute(array('name' => $name));
		$row = $sth -> fetch(PDO::FETCH_NUM);
		$assoc = self::row_to_assoc($row);
		return new key_status_model($assoc);
	}
}
?>