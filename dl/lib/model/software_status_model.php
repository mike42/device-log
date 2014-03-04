<?php
class software_status_model {
	/**
	 * @var int id ID for the software status
	 */
	private $id;

	/**
	 * @var string tag Human-readable status tag
	 */
	private $tag;

	private $model_variables_changed; // Only variables which have been changed
	private $model_variables_set; // All variables which have been set (initially or with a setter)

	/* Child tables */
	public $list_software;
	public $list_software_history;

	/**
	 * Initialise and load related tables
	 */
	public static function init() {
		core::loadClass("database");

		/* Child tables */
		core::loadClass("software_model");
		core::loadClass("software_history_model");
	}

	/**
	 * Construct new software_status from field list
	 * 
	 * @return array
	 */
	public function __construct(array $fields = array()) {
/* Initialise everything as blank to avoid tripping up the permissions fitlers */
		$this -> id = '';
		$this -> tag = '';

		if(isset($fields['software_status.id'])) {
			$this -> set_id($fields['software_status.id']);
		}
		if(isset($fields['software_status.tag'])) {
			$this -> set_tag($fields['software_status.tag']);
		}

		$this -> model_variables_changed = array();
		$this -> list_software = array();
		$this -> list_software_history = array();
	}

	/**
	 * Convert software_status to shallow associative array
	 * 
	 * @return array
	 */
	private function to_array() {
		$values = array(
			'id' => $this -> id,
			'tag' => $this -> tag);
		return $values;
	}

	/**
	 * Convert software_status to associative array, including only visible fields,
	 * parent tables, and loaded child tables
	 * 
	 * @param string $role The user role to use
	 */
	public function to_array_filtered($role = "anon") {
		if(core::$permission[$role]['software_status']['read'] === false) {
			return false;
		}
		$values = array();
		$everything = $this -> to_array();
		foreach(core::$permission[$role]['software_status']['read'] as $field) {
			if(!isset($everything[$field])) {
				throw new Exception("Check permissions: '$field' is not a real field in software_status");
			}
			$values[$field] = $everything[$field];
		}

		/* Add filtered versions of everything that's been loaded */
		$values['software'] = array();
		$values['software_history'] = array();
		foreach($this -> list_software as $software) {
			$values['software'][] = $software -> to_array_filtered($role);
		}
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
			"software_status.id" => $row[0],
			"software_status.tag" => $row[1]);
		return $values;
	}

	/**
	 * Get id
	 * 
	 * @return int
	 */
	public function get_id() {
		if(!isset($this -> model_variables_set['id'])) {
			throw new Exception("software_status.id has not been initialised.");
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
			throw new Exception("software_status.id must be numeric");
		}
		$this -> id = $id;
		$this -> model_variables_changed['id'] = true;
		$this -> model_variables_set['id'] = true;
	}

	/**
	 * Get tag
	 * 
	 * @return string
	 */
	public function get_tag() {
		if(!isset($this -> model_variables_set['tag'])) {
			throw new Exception("software_status.tag has not been initialised.");
		}
		return $this -> tag;
	}

	/**
	 * Set tag
	 * 
	 * @param string $tag
	 */
	public function set_tag($tag) {
		if(strlen($tag) > 45) {
			throw new Exception("software_status.tag cannot be longer than 45 characters");
		}
		$this -> tag = $tag;
		$this -> model_variables_changed['tag'] = true;
		$this -> model_variables_set['tag'] = true;
	}

	/**
	 * Update software_status
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
		$sth = database::$dbh -> prepare("UPDATE software_status SET $fields WHERE id = :id");
		$sth -> execute($data);
	}

	/**
	 * Add new software_status
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
		$sth = database::$dbh -> prepare("INSERT INTO software_status ($fields) VALUES ($vals);");
		$sth -> execute($data);
		$this -> set_id(database::$dbh->lastInsertId());
	}

	/**
	 * Delete software_status
	 */
	public function delete() {
		$sth = database::$dbh -> prepare("DELETE FROM software_status WHERE id = :id");
		$data['id'] = $this -> get_id();
		$sth -> execute($data);
	}

	/**
	 * List associated rows from software table
	 * 
	 * @param int $start Row to begin from. Default 0 (begin from start)
	 * @param int $limit Maximum number of rows to retrieve. Default -1 (no limit)
	 */
	public function populate_list_software($start = 0, $limit = -1) {
		$software_status_id = $this -> get_id();
		$this -> list_software = software_model::list_by_software_status_id($software_status_id, $start, $limit);
	}

	/**
	 * List associated rows from software_history table
	 * 
	 * @param int $start Row to begin from. Default 0 (begin from start)
	 * @param int $limit Maximum number of rows to retrieve. Default -1 (no limit)
	 */
	public function populate_list_software_history($start = 0, $limit = -1) {
		$software_status_id = $this -> get_id();
		$this -> list_software_history = software_history_model::list_by_software_status_id($software_status_id, $start, $limit);
	}

	/**
	 * Retrieve by primary key
	 */
	public static function get($id) {
		$sth = database::$dbh -> prepare("SELECT software_status.id, software_status.tag FROM software_status  WHERE software_status.id = :id;");
		$sth -> execute(array('id' => $id));
		$row = $sth -> fetch(PDO::FETCH_NUM);
		if($row === false){
			return false;
		}
		$assoc = self::row_to_assoc($row);
		return new software_status_model($assoc);
	}

	/**
	 * Retrieve by tag
	 */
	public static function get_by_tag($tag) {
		$sth = database::$dbh -> prepare("SELECT software_status.id, software_status.tag FROM software_status  WHERE software_status.tag = :tag;");
		$sth -> execute(array('tag' => $tag));
		$row = $sth -> fetch(PDO::FETCH_NUM);
		if($row === false){
			return false;
		}
		$assoc = self::row_to_assoc($row);
		return new software_status_model($assoc);
	}
}
?>