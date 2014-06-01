<?php
class device_status_model {
	/**
	 * @var int id Number for this status
	 */
	private $id;

	/**
	 * @var string tag Human-readable status code
	 */
	private $tag;

	/**
	 * @var int progress_flag 1 if the device is awaiting attention when in this status
	 */
	private $progress_flag;

	private $model_variables_changed; // Only variables which have been changed
	private $model_variables_set; // All variables which have been set (initially or with a setter)

	/* Sort clause to add when listing rows from this table */
	const SORT_CLAUSE = " ORDER BY `device_status`.`tag`";

	/**
	 * Initialise and load related tables
	 */
	public static function init() {
		core::loadClass("database");
	}

	/**
	 * Construct new device_status from field list
	 * 
	 * @return array
	 */
	public function __construct(array $fields = array()) {
		/* Initialise everything as blank to avoid tripping up the permissions fitlers */
		$this -> id = '';
		$this -> tag = '';
		$this -> progress_flag = '';

		if(isset($fields['device_status.id'])) {
			$this -> set_id($fields['device_status.id']);
		}
		if(isset($fields['device_status.tag'])) {
			$this -> set_tag($fields['device_status.tag']);
		}
		if(isset($fields['device_status.progress_flag'])) {
			$this -> set_progress_flag($fields['device_status.progress_flag']);
		}

		$this -> model_variables_changed = array();
	}

	/**
	 * Convert device_status to shallow associative array
	 * 
	 * @return array
	 */
	private function to_array() {
		$values = array(
			'id' => $this -> id,
			'tag' => $this -> tag,
			'progress_flag' => $this -> progress_flag);
		return $values;
	}

	/**
	 * Convert device_status to associative array, including only visible fields,
	 * parent tables, and loaded child tables
	 * 
	 * @param string $role The user role to use
	 */
	public function to_array_filtered($role = "anon") {
		if(core::$permission[$role]['device_status']['read'] === false) {
			return false;
		}
		$values = array();
		$everything = $this -> to_array();
		foreach(core::$permission[$role]['device_status']['read'] as $field) {
			if(!isset($everything[$field])) {
				throw new Exception("Check permissions: '$field' is not a real field in device_status");
			}
			$values[$field] = $everything[$field];
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
			"device_status.id" => $row[0],
			"device_status.tag" => $row[1],
			"device_status.progress_flag" => $row[2]);
		return $values;
	}

	/**
	 * Get id
	 * 
	 * @return int
	 */
	public function get_id() {
		if(!isset($this -> model_variables_set['id'])) {
			throw new Exception("device_status.id has not been initialised.");
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
			throw new Exception("device_status.id must be numeric");
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
			throw new Exception("device_status.tag has not been initialised.");
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
			throw new Exception("device_status.tag cannot be longer than 45 characters");
		}
		$this -> tag = $tag;
		$this -> model_variables_changed['tag'] = true;
		$this -> model_variables_set['tag'] = true;
	}

	/**
	 * Get progress_flag
	 * 
	 * @return int
	 */
	public function get_progress_flag() {
		if(!isset($this -> model_variables_set['progress_flag'])) {
			throw new Exception("device_status.progress_flag has not been initialised.");
		}
		return $this -> progress_flag;
	}

	/**
	 * Set progress_flag
	 * 
	 * @param int $progress_flag
	 */
	public function set_progress_flag($progress_flag) {
		if(!is_numeric($progress_flag)) {
			throw new Exception("device_status.progress_flag must be numeric");
		}
		$this -> progress_flag = $progress_flag;
		$this -> model_variables_changed['progress_flag'] = true;
		$this -> model_variables_set['progress_flag'] = true;
	}

	/**
	 * Update device_status
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
		$sth = database::$dbh -> prepare("UPDATE `device_status` SET $fields WHERE `device_status`.`id` = :id");
		$sth -> execute($data);
	}

	/**
	 * Add new device_status
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
		$sth = database::$dbh -> prepare("INSERT INTO `device_status` ($fields) VALUES ($vals);");
		$sth -> execute($data);
		$this -> set_id(database::$dbh->lastInsertId());
	}

	/**
	 * Delete device_status
	 */
	public function delete() {
		$sth = database::$dbh -> prepare("DELETE FROM `device_status` WHERE `device_status`.`id` = :id");
		$data['id'] = $this -> get_id();
		$sth -> execute($data);
	}

	/**
	 * Retrieve by primary key
	 */
	public static function get($id) {
		$sth = database::$dbh -> prepare("SELECT `device_status`.`id`, `device_status`.`tag`, `device_status`.`progress_flag` FROM device_status  WHERE `device_status`.`id` = :id;");
		$sth -> execute(array('id' => $id));
		$row = $sth -> fetch(PDO::FETCH_NUM);
		if($row === false){
			return false;
		}
		$assoc = self::row_to_assoc($row);
		return new device_status_model($assoc);
	}

	/**
	 * Retrieve by tag_UNIQUE
	 */
	public static function get_by_tag_UNIQUE($tag) {
		$sth = database::$dbh -> prepare("SELECT `device_status`.`id`, `device_status`.`tag`, `device_status`.`progress_flag` FROM device_status  WHERE `device_status`.`tag` = :tag;");
		$sth -> execute(array('tag' => $tag));
		$row = $sth -> fetch(PDO::FETCH_NUM);
		if($row === false){
			return false;
		}
		$assoc = self::row_to_assoc($row);
		return new device_status_model($assoc);
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
		$sth = database::$dbh -> prepare("SELECT `device_status`.`id`, `device_status`.`tag`, `device_status`.`progress_flag` FROM `device_status` " . self::SORT_CLAUSE . $ls . ";");
		$sth -> execute();
		$rows = $sth -> fetchAll(PDO::FETCH_NUM);
		$ret = array();
		foreach($rows as $row) {
			$assoc = self::row_to_assoc($row);
			$ret[] = new device_status_model($assoc);
		}
		return $ret;
	}

	/**
	 * Simple search within tag field
	 * 
	 * @param int $start Row to begin from. Default 0 (begin from start)
	 * @param int $limit Maximum number of rows to retrieve. Default -1 (no limit)
	 */
	public static function search_by_tag($search, $start = 0, $limit = -1) {
		$ls = "";
		$start = (int)$start;
		$limit = (int)$limit;
		if($start >= 0 && $limit > 0) {
			$ls = " LIMIT $start, $limit";
		}
		$sth = database::$dbh -> prepare("SELECT `device_status`.`id`, `device_status`.`tag`, `device_status`.`progress_flag` FROM `device_status`  WHERE tag LIKE :search" . self::SORT_CLAUSE . $ls . ";");
		$sth -> execute(array('search' => "%".$search."%"));
		$rows = $sth -> fetchAll(PDO::FETCH_NUM);
		$ret = array();
		foreach($rows as $row) {
			$assoc = self::row_to_assoc($row);
			$ret[] = new device_status_model($assoc);
		}
		return $ret;
	}
}
?>