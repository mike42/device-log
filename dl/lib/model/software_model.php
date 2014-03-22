<?php
class software_model {
	/**
	 * @var int id ID for this software installation
	 */
	private $id;

	/**
	 * @var string code Vendor-issued code or serial number associated with this installation
	 */
	private $code;

	/**
	 * @var int software_type_id Type of software which has been installed
	 */
	private $software_type_id;

	/**
	 * @var int software_status_id Current status code for the software
	 */
	private $software_status_id;

	/**
	 * @var int person_id Person with this software installation
	 */
	private $person_id;

	/**
	 * @var int is_bought 1 for bought out software, 0 for organisation-owned
	 */
	private $is_bought;

	private $model_variables_changed; // Only variables which have been changed
	private $model_variables_set; // All variables which have been set (initially or with a setter)

	/* Parent tables */
	public $software_type;
	public $software_status;
	public $person;

	/* Child tables */
	public $list_software_history;

	/* Sort clause to add when listing rows from this table */
	const SORT_CLAUSE = " ORDER BY `software`.`id`";

	/**
	 * Initialise and load related tables
	 */
	public static function init() {
		core::loadClass("database");
		core::loadClass("software_type_model");
		core::loadClass("software_status_model");
		core::loadClass("person_model");

		/* Child tables */
		core::loadClass("software_history_model");
	}

	/**
	 * Construct new software from field list
	 * 
	 * @return array
	 */
	public function __construct(array $fields = array()) {
		/* Initialise everything as blank to avoid tripping up the permissions fitlers */
		$this -> id = '';
		$this -> code = '';
		$this -> software_type_id = '';
		$this -> software_status_id = '';
		$this -> person_id = '';
		$this -> is_bought = '';

		if(isset($fields['software.id'])) {
			$this -> set_id($fields['software.id']);
		}
		if(isset($fields['software.code'])) {
			$this -> set_code($fields['software.code']);
		}
		if(isset($fields['software.software_type_id'])) {
			$this -> set_software_type_id($fields['software.software_type_id']);
		}
		if(isset($fields['software.software_status_id'])) {
			$this -> set_software_status_id($fields['software.software_status_id']);
		}
		if(isset($fields['software.person_id'])) {
			$this -> set_person_id($fields['software.person_id']);
		}
		if(isset($fields['software.is_bought'])) {
			$this -> set_is_bought($fields['software.is_bought']);
		}

		$this -> model_variables_changed = array();
		$this -> software_type = new software_type_model($fields);
		$this -> software_status = new software_status_model($fields);
		$this -> person = new person_model($fields);
		$this -> list_software_history = array();
	}

	/**
	 * Convert software to shallow associative array
	 * 
	 * @return array
	 */
	private function to_array() {
		$values = array(
			'id' => $this -> id,
			'code' => $this -> code,
			'software_type_id' => $this -> software_type_id,
			'software_status_id' => $this -> software_status_id,
			'person_id' => $this -> person_id,
			'is_bought' => $this -> is_bought);
		return $values;
	}

	/**
	 * Convert software to associative array, including only visible fields,
	 * parent tables, and loaded child tables
	 * 
	 * @param string $role The user role to use
	 */
	public function to_array_filtered($role = "anon") {
		if(core::$permission[$role]['software']['read'] === false) {
			return false;
		}
		$values = array();
		$everything = $this -> to_array();
		foreach(core::$permission[$role]['software']['read'] as $field) {
			if(!isset($everything[$field])) {
				throw new Exception("Check permissions: '$field' is not a real field in software");
			}
			$values[$field] = $everything[$field];
		}
		$values['software_type'] = $this -> software_type -> to_array_filtered($role);
		$values['software_status'] = $this -> software_status -> to_array_filtered($role);
		$values['person'] = $this -> person -> to_array_filtered($role);

		/* Add filtered versions of everything that's been loaded */
		$values['software_history'] = array();
		foreach($this -> list_software_history as $software_history) {
			$values['software_history'][] = $software_history -> to_array_filtered($role);
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
			"software.id" => $row[0],
			"software.code" => $row[1],
			"software.software_type_id" => $row[2],
			"software.software_status_id" => $row[3],
			"software.person_id" => $row[4],
			"software.is_bought" => $row[5],
			"software_type.id" => $row[6],
			"software_type.name" => $row[7],
			"software_status.id" => $row[8],
			"software_status.tag" => $row[9],
			"person.id" => $row[10],
			"person.code" => $row[11],
			"person.is_staff" => $row[12],
			"person.is_active" => $row[13],
			"person.firstname" => $row[14],
			"person.surname" => $row[15]);
		return $values;
	}

	/**
	 * Get id
	 * 
	 * @return int
	 */
	public function get_id() {
		if(!isset($this -> model_variables_set['id'])) {
			throw new Exception("software.id has not been initialised.");
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
			throw new Exception("software.id must be numeric");
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
			throw new Exception("software.code has not been initialised.");
		}
		return $this -> code;
	}

	/**
	 * Set code
	 * 
	 * @param string $code
	 */
	public function set_code($code) {
		if(strlen($code) > 128) {
			throw new Exception("software.code cannot be longer than 128 characters");
		}
		$this -> code = $code;
		$this -> model_variables_changed['code'] = true;
		$this -> model_variables_set['code'] = true;
	}

	/**
	 * Get software_type_id
	 * 
	 * @return int
	 */
	public function get_software_type_id() {
		if(!isset($this -> model_variables_set['software_type_id'])) {
			throw new Exception("software.software_type_id has not been initialised.");
		}
		return $this -> software_type_id;
	}

	/**
	 * Set software_type_id
	 * 
	 * @param int $software_type_id
	 */
	public function set_software_type_id($software_type_id) {
		if(!is_numeric($software_type_id)) {
			throw new Exception("software.software_type_id must be numeric");
		}
		$this -> software_type_id = $software_type_id;
		$this -> model_variables_changed['software_type_id'] = true;
		$this -> model_variables_set['software_type_id'] = true;
	}

	/**
	 * Get software_status_id
	 * 
	 * @return int
	 */
	public function get_software_status_id() {
		if(!isset($this -> model_variables_set['software_status_id'])) {
			throw new Exception("software.software_status_id has not been initialised.");
		}
		return $this -> software_status_id;
	}

	/**
	 * Set software_status_id
	 * 
	 * @param int $software_status_id
	 */
	public function set_software_status_id($software_status_id) {
		if(!is_numeric($software_status_id)) {
			throw new Exception("software.software_status_id must be numeric");
		}
		$this -> software_status_id = $software_status_id;
		$this -> model_variables_changed['software_status_id'] = true;
		$this -> model_variables_set['software_status_id'] = true;
	}

	/**
	 * Get person_id
	 * 
	 * @return int
	 */
	public function get_person_id() {
		if(!isset($this -> model_variables_set['person_id'])) {
			throw new Exception("software.person_id has not been initialised.");
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
			throw new Exception("software.person_id must be numeric");
		}
		$this -> person_id = $person_id;
		$this -> model_variables_changed['person_id'] = true;
		$this -> model_variables_set['person_id'] = true;
	}

	/**
	 * Get is_bought
	 * 
	 * @return int
	 */
	public function get_is_bought() {
		if(!isset($this -> model_variables_set['is_bought'])) {
			throw new Exception("software.is_bought has not been initialised.");
		}
		return $this -> is_bought;
	}

	/**
	 * Set is_bought
	 * 
	 * @param int $is_bought
	 */
	public function set_is_bought($is_bought) {
		if(!is_numeric($is_bought)) {
			throw new Exception("software.is_bought must be numeric");
		}
		$this -> is_bought = $is_bought;
		$this -> model_variables_changed['is_bought'] = true;
		$this -> model_variables_set['is_bought'] = true;
	}

	/**
	 * Update software
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
		$sth = database::$dbh -> prepare("UPDATE `software` SET $fields WHERE `software`.`id` = :id");
		$sth -> execute($data);
	}

	/**
	 * Add new software
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
		$sth = database::$dbh -> prepare("INSERT INTO `software` ($fields) VALUES ($vals);");
		$sth -> execute($data);
		$this -> set_id(database::$dbh->lastInsertId());
	}

	/**
	 * Delete software
	 */
	public function delete() {
		$sth = database::$dbh -> prepare("DELETE FROM `software` WHERE `software`.`id` = :id");
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
		$software_id = $this -> get_id();
		$this -> list_software_history = software_history_model::list_by_software_id($software_id, $start, $limit);
	}

	/**
	 * Retrieve by primary key
	 */
	public static function get($id) {
		$sth = database::$dbh -> prepare("SELECT `software`.`id`, `software`.`code`, `software`.`software_type_id`, `software`.`software_status_id`, `software`.`person_id`, `software`.`is_bought`, `software_type`.`id`, `software_type`.`name`, `software_status`.`id`, `software_status`.`tag`, `person`.`id`, `person`.`code`, `person`.`is_staff`, `person`.`is_active`, `person`.`firstname`, `person`.`surname` FROM software JOIN `software_type` ON `software`.`software_type_id` = `software_type`.`id` JOIN `software_status` ON `software`.`software_status_id` = `software_status`.`id` JOIN `person` ON `software`.`person_id` = `person`.`id` WHERE `software`.`id` = :id;");
		$sth -> execute(array('id' => $id));
		$row = $sth -> fetch(PDO::FETCH_NUM);
		if($row === false){
			return false;
		}
		$assoc = self::row_to_assoc($row);
		return new software_model($assoc);
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
		$sth = database::$dbh -> prepare("SELECT `software`.`id`, `software`.`code`, `software`.`software_type_id`, `software`.`software_status_id`, `software`.`person_id`, `software`.`is_bought`, `software_type`.`id`, `software_type`.`name`, `software_status`.`id`, `software_status`.`tag`, `person`.`id`, `person`.`code`, `person`.`is_staff`, `person`.`is_active`, `person`.`firstname`, `person`.`surname` FROM `software` JOIN `software_type` ON `software`.`software_type_id` = `software_type`.`id` JOIN `software_status` ON `software`.`software_status_id` = `software_status`.`id` JOIN `person` ON `software`.`person_id` = `person`.`id`" . self::SORT_CLAUSE . $ls . ";");
		$sth -> execute();
		$rows = $sth -> fetchAll(PDO::FETCH_NUM);
		$ret = array();
		foreach($rows as $row) {
			$assoc = self::row_to_assoc($row);
			$ret[] = new software_model($assoc);
		}
		return $ret;
	}

	/**
	 * List rows by software_type_id index
	 * 
	 * @param int $start Row to begin from. Default 0 (begin from start)
	 * @param int $limit Maximum number of rows to retrieve. Default -1 (no limit)
	 */
	public static function list_by_software_type_id($software_type_id, $start = 0, $limit = -1) {
		$ls = "";
		$start = (int)$start;
		$limit = (int)$limit;
		if($start >= 0 && $limit > 0) {
			$ls = " LIMIT $start, $limit";
		}
		$sth = database::$dbh -> prepare("SELECT `software`.`id`, `software`.`code`, `software`.`software_type_id`, `software`.`software_status_id`, `software`.`person_id`, `software`.`is_bought`, `software_type`.`id`, `software_type`.`name`, `software_status`.`id`, `software_status`.`tag`, `person`.`id`, `person`.`code`, `person`.`is_staff`, `person`.`is_active`, `person`.`firstname`, `person`.`surname` FROM `software` JOIN `software_type` ON `software`.`software_type_id` = `software_type`.`id` JOIN `software_status` ON `software`.`software_status_id` = `software_status`.`id` JOIN `person` ON `software`.`person_id` = `person`.`id` WHERE software.software_type_id = :software_type_id" . self::SORT_CLAUSE . $ls . ";");
		$sth -> execute(array('software_type_id' => $software_type_id));
		$rows = $sth -> fetchAll(PDO::FETCH_NUM);
		$ret = array();
		foreach($rows as $row) {
			$assoc = self::row_to_assoc($row);
			$ret[] = new software_model($assoc);
		}
		return $ret;
	}

	/**
	 * List rows by software_status_id index
	 * 
	 * @param int $start Row to begin from. Default 0 (begin from start)
	 * @param int $limit Maximum number of rows to retrieve. Default -1 (no limit)
	 */
	public static function list_by_software_status_id($software_status_id, $start = 0, $limit = -1) {
		$ls = "";
		$start = (int)$start;
		$limit = (int)$limit;
		if($start >= 0 && $limit > 0) {
			$ls = " LIMIT $start, $limit";
		}
		$sth = database::$dbh -> prepare("SELECT `software`.`id`, `software`.`code`, `software`.`software_type_id`, `software`.`software_status_id`, `software`.`person_id`, `software`.`is_bought`, `software_type`.`id`, `software_type`.`name`, `software_status`.`id`, `software_status`.`tag`, `person`.`id`, `person`.`code`, `person`.`is_staff`, `person`.`is_active`, `person`.`firstname`, `person`.`surname` FROM `software` JOIN `software_type` ON `software`.`software_type_id` = `software_type`.`id` JOIN `software_status` ON `software`.`software_status_id` = `software_status`.`id` JOIN `person` ON `software`.`person_id` = `person`.`id` WHERE software.software_status_id = :software_status_id" . self::SORT_CLAUSE . $ls . ";");
		$sth -> execute(array('software_status_id' => $software_status_id));
		$rows = $sth -> fetchAll(PDO::FETCH_NUM);
		$ret = array();
		foreach($rows as $row) {
			$assoc = self::row_to_assoc($row);
			$ret[] = new software_model($assoc);
		}
		return $ret;
	}

	/**
	 * List rows by software_person_id index
	 * 
	 * @param int $start Row to begin from. Default 0 (begin from start)
	 * @param int $limit Maximum number of rows to retrieve. Default -1 (no limit)
	 */
	public static function list_by_software_person_id($person_id, $start = 0, $limit = -1) {
		$ls = "";
		$start = (int)$start;
		$limit = (int)$limit;
		if($start >= 0 && $limit > 0) {
			$ls = " LIMIT $start, $limit";
		}
		$sth = database::$dbh -> prepare("SELECT `software`.`id`, `software`.`code`, `software`.`software_type_id`, `software`.`software_status_id`, `software`.`person_id`, `software`.`is_bought`, `software_type`.`id`, `software_type`.`name`, `software_status`.`id`, `software_status`.`tag`, `person`.`id`, `person`.`code`, `person`.`is_staff`, `person`.`is_active`, `person`.`firstname`, `person`.`surname` FROM `software` JOIN `software_type` ON `software`.`software_type_id` = `software_type`.`id` JOIN `software_status` ON `software`.`software_status_id` = `software_status`.`id` JOIN `person` ON `software`.`person_id` = `person`.`id` WHERE software.person_id = :person_id" . self::SORT_CLAUSE . $ls . ";");
		$sth -> execute(array('person_id' => $person_id));
		$rows = $sth -> fetchAll(PDO::FETCH_NUM);
		$ret = array();
		foreach($rows as $row) {
			$assoc = self::row_to_assoc($row);
			$ret[] = new software_model($assoc);
		}
		return $ret;
	}

	/**
	 * Simple search within code field
	 * 
	 * @param int $start Row to begin from. Default 0 (begin from start)
	 * @param int $limit Maximum number of rows to retrieve. Default -1 (no limit)
	 */
	public static function search_by_code($search, $start = 0, $limit = -1) {
		$ls = "";
		$start = (int)$start;
		$limit = (int)$limit;
		if($start >= 0 && $limit > 0) {
			$ls = " LIMIT $start, $limit";
		}
		$sth = database::$dbh -> prepare("SELECT `software`.`id`, `software`.`code`, `software`.`software_type_id`, `software`.`software_status_id`, `software`.`person_id`, `software`.`is_bought`, `software_type`.`id`, `software_type`.`name`, `software_status`.`id`, `software_status`.`tag`, `person`.`id`, `person`.`code`, `person`.`is_staff`, `person`.`is_active`, `person`.`firstname`, `person`.`surname` FROM `software` JOIN `software_type` ON `software`.`software_type_id` = `software_type`.`id` JOIN `software_status` ON `software`.`software_status_id` = `software_status`.`id` JOIN `person` ON `software`.`person_id` = `person`.`id` WHERE code LIKE :search" . self::SORT_CLAUSE . $ls . ";");
		$sth -> execute(array('search' => "%".$search."%"));
		$rows = $sth -> fetchAll(PDO::FETCH_NUM);
		$ret = array();
		foreach($rows as $row) {
			$assoc = self::row_to_assoc($row);
			$ret[] = new software_model($assoc);
		}
		return $ret;
	}
}
?>