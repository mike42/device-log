<?php
class device_type_model {
	private $id;
	private $name;
	private $model_no;
	private $model_variables_changed; // Only variables which have been changed
	private $model_variables_set; // All variables which have been set (initially or with a setter)

	/* Child tables */
	public $list_device;

	/**
	 * Construct new device_type from field list
	 * 
	 * @return array
	 */
	public function __construct(array $fields = array()) {
		if(isset($fields['device_type.id'])) {
			$this -> set_id($fields['device_type.id']);
		}
		if(isset($fields['device_type.name'])) {
			$this -> set_name($fields['device_type.name']);
		}
		if(isset($fields['device_type.model_no'])) {
			$this -> set_model_no($fields['device_type.model_no']);
		}

		$this -> model_variables_changed = array();
	}

	/**
	 * Convert device_type to shallow associative array
	 * 
	 * @return array
	 */
	private function to_array() {
		$values = array(
			'id' => $this -> id,
			'name' => $this -> name,
			'model_no' => $this -> model_no);
		return $values;
	}

	/**
	 * Convert device_type to associative array, including only visible fields,
	 * parent tables, and loaded child tables
	 * 
	 * @param string $role The user role to use
	 */
	public function to_array_filtered($role = "anon") {
		// TODO: Insert code for device_type permission-check
	}

	/**
	 * Convert retrieved database row from numbered to named keys, including table name
	 * 
	 * @param array $row ror retrieved from database
	 * @return array row with indices
	 */
	private static function row_to_assoc(array $row) {
		$values = array(
			"device_type.id" => $row[0],
			"device_type.name" => $row[1],
			"device_type.model_no" => $row[2]);
		return $values;
	}

	/**
	 * Get id
	 * 
	 * @return int
	 */
	public function get_id() {
		if(!isset($this -> model_variables_set['id'])) {
			throw new Exception("device_type.id has not been initialised.");
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
			throw new Exception("device_type.id must be numeric");
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
			throw new Exception("device_type.name has not been initialised.");
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
			throw new Exception("device_type.name cannot be longer than 45 characters");
		}
		$this -> name = $name;
		$this -> model_variables_changed['name'] = true;
		$this -> model_variables_set['name'] = true;
	}

	/**
	 * Get model_no
	 * 
	 * @return string
	 */
	public function get_model_no() {
		if(!isset($this -> model_variables_set['model_no'])) {
			throw new Exception("device_type.model_no has not been initialised.");
		}
		return $this -> model_no;
	}

	/**
	 * Set model_no
	 * 
	 * @param string $model_no
	 */
	public function set_model_no($model_no) {
		if(strlen($model_no) > 45) {
			throw new Exception("device_type.model_no cannot be longer than 45 characters");
		}
		$this -> model_no = $model_no;
		$this -> model_variables_changed['model_no'] = true;
		$this -> model_variables_set['model_no'] = true;
	}

	/**
	 * Update device_type
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
		$sth = database::$dbh -> prepare("UPDATE device_type SET $fields WHERE id = :id");
		$sth -> execute($this -> to_array());
	}

	/**
	 * Add new device_type
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
		$sth = database::$dbh -> prepare("INSERT INTO device_type ($fields) VALUES ($vals);");
		$sth -> execute($this -> to_array());
	}

	/**
	 * Delete device_type
	 */
	public function delete() {
		$sth = database::$dbh -> prepare("DELETE FROM device_type WHERE id = :id");
		$sth -> execute($this -> to_array());
	}

	/**
	 * Get associated rows from device table
	 * 
	 * @param int $start Row to begin from. Default 0 (begin from start)
	 * @param int $limit Maximum number of rows to retrieve. Default -1 (no limit)
	 */
	public function populate_list_device($start = 0, $limit = -1) {
		$this -> list_device = device_model::list_by_device_type_id($device_type_id, $start, $limit);
	}

	public static function get($id) {
		$sth = database::$dbh -> prepare("SELECT device_type.id, device_type.name, device_type.model_no FROM device_type  WHERE device_type.id = :id;");
		$sth -> execute(array('id' => $id));
		$row = $sth -> fetch(PDO::FETCH_NUM);
		$assoc = self::row_to_assoc($row);
		return new device_type_model($assoc);
	}

	public static function get_by_name($name) {
		$sth = database::$dbh -> prepare("SELECT device_type.id, device_type.name, device_type.model_no FROM device_type  WHERE device_type.name = :name;");
		$sth -> execute(array('name' => $name));
		$row = $sth -> fetch(PDO::FETCH_NUM);
		$assoc = self::row_to_assoc($row);
		return new device_type_model($assoc);
	}
}
?>