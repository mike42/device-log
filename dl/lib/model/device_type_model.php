<?php
class device_type_model {
	/**
	 * @var int id ID for this device type
	 */
	private $id;

	/**
	 * @var string name Human-readable device family description
	 */
	private $name;

	/**
	 * @var string model_no Manufacturer model number
	 */
	private $model_no;

	private $model_variables_changed; // Only variables which have been changed
	private $model_variables_set; // All variables which have been set (initially or with a setter)

	/* Child tables */
	public $list_device;

	/* Sort clause to add when listing rows from this table */
	const SORT_CLAUSE = " ORDER BY `device_type`.`name`";

	/**
	 * Initialise and load related tables
	 */
	public static function init() {
		core::loadClass("database");

		/* Child tables */
		core::loadClass("device_model");
	}

	/**
	 * Construct new device_type from field list
	 * 
	 * @return array
	 */
	public function __construct(array $fields = array()) {
		/* Initialise everything as blank to avoid tripping up the permissions fitlers */
		$this -> id = '';
		$this -> name = '';
		$this -> model_no = '';

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
		$this -> list_device = array();
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
		if(core::$permission[$role]['device_type']['read'] === false) {
			return false;
		}
		$values = array();
		$everything = $this -> to_array();
		foreach(core::$permission[$role]['device_type']['read'] as $field) {
			if(!isset($everything[$field])) {
				throw new Exception("Check permissions: '$field' is not a real field in device_type");
			}
			$values[$field] = $everything[$field];
		}

		/* Add filtered versions of everything that's been loaded */
		$values['device'] = array();
		foreach($this -> list_device as $device) {
			$values['device'][] = $device -> to_array_filtered($role);
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
		$everything = $this -> to_array();
		$data['id'] = $this -> get_id();
		foreach($this -> model_variables_changed as $col => $changed) {
			$fieldset[] = "`$col` = :$col";
			$data[$col] = $everything[$col];
		}
		$fields = implode(", ", $fieldset);

		/* Execute query */
		$sth = database::$dbh -> prepare("UPDATE `device_type` SET $fields WHERE `device_type`.`id` = :id");
		$sth -> execute($data);
	}

	/**
	 * Add new device_type
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
		$sth = database::$dbh -> prepare("INSERT INTO `device_type` ($fields) VALUES ($vals);");
		$sth -> execute($data);
		$this -> set_id(database::$dbh->lastInsertId());
	}

	/**
	 * Delete device_type
	 */
	public function delete() {
		$sth = database::$dbh -> prepare("DELETE FROM `device_type` WHERE `device_type`.`id` = :id");
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
		$device_type_id = $this -> get_id();
		$this -> list_device = device_model::list_by_device_type_id($device_type_id, $start, $limit);
	}

	/**
	 * Retrieve by primary key
	 */
	public static function get($id) {
		$sth = database::$dbh -> prepare("SELECT `device_type`.`id`, `device_type`.`name`, `device_type`.`model_no` FROM device_type  WHERE `device_type`.`id` = :id;");
		$sth -> execute(array('id' => $id));
		$row = $sth -> fetch(PDO::FETCH_NUM);
		if($row === false){
			return false;
		}
		$assoc = self::row_to_assoc($row);
		return new device_type_model($assoc);
	}

	/**
	 * Retrieve by name
	 */
	public static function get_by_name($name) {
		$sth = database::$dbh -> prepare("SELECT `device_type`.`id`, `device_type`.`name`, `device_type`.`model_no` FROM device_type  WHERE `device_type`.`name` = :name;");
		$sth -> execute(array('name' => $name));
		$row = $sth -> fetch(PDO::FETCH_NUM);
		if($row === false){
			return false;
		}
		$assoc = self::row_to_assoc($row);
		return new device_type_model($assoc);
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
		$sth = database::$dbh -> prepare("SELECT `device_type`.`id`, `device_type`.`name`, `device_type`.`model_no` FROM `device_type` " . self::SORT_CLAUSE . $ls . ";");
		$sth -> execute();
		$rows = $sth -> fetchAll(PDO::FETCH_NUM);
		$ret = array();
		foreach($rows as $row) {
			$assoc = self::row_to_assoc($row);
			$ret[] = new device_type_model($assoc);
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
		$sth = database::$dbh -> prepare("SELECT `device_type`.`id`, `device_type`.`name`, `device_type`.`model_no` FROM `device_type`  WHERE name LIKE :search" . self::SORT_CLAUSE . $ls . ";");
		$sth -> execute(array('search' => "%".$search."%"));
		$rows = $sth -> fetchAll(PDO::FETCH_NUM);
		$ret = array();
		foreach($rows as $row) {
			$assoc = self::row_to_assoc($row);
			$ret[] = new device_type_model($assoc);
		}
		return $ret;
	}

	/**
	 * Simple search within model_no field
	 * 
	 * @param int $start Row to begin from. Default 0 (begin from start)
	 * @param int $limit Maximum number of rows to retrieve. Default -1 (no limit)
	 */
	public static function search_by_model_no($search, $start = 0, $limit = -1) {
		$ls = "";
		$start = (int)$start;
		$limit = (int)$limit;
		if($start >= 0 && $limit > 0) {
			$ls = " LIMIT $start, $limit";
		}
		$sth = database::$dbh -> prepare("SELECT `device_type`.`id`, `device_type`.`name`, `device_type`.`model_no` FROM `device_type`  WHERE model_no LIKE :search" . self::SORT_CLAUSE . $ls . ";");
		$sth -> execute(array('search' => "%".$search."%"));
		$rows = $sth -> fetchAll(PDO::FETCH_NUM);
		$ret = array();
		foreach($rows as $row) {
			$assoc = self::row_to_assoc($row);
			$ret[] = new device_type_model($assoc);
		}
		return $ret;
	}
}
?>