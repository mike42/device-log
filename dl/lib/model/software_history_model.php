<?php
class software_history_model {
	/**
	 * @var int id ID for this history entry
	 */
	private $id;

	/**
	 * @var string date Date of the entry
	 */
	private $date;

	/**
	 * @var int person_id ID of person who has the software at this stage
	 */
	private $person_id;

	/**
	 * @var int software_id ID of the associated software installation
	 */
	private $software_id;

	/**
	 * @var int technician_id Technician who added the entry
	 */
	private $technician_id;

	/**
	 * @var int software_status_id Status code aplicable to the installation at this time
	 */
	private $software_status_id;

	/**
	 * @var string comment Technician comment
	 */
	private $comment;

	/**
	 * @var string change Field which was affected by this history entry
	 */
	private $change;

	/**
	 * @var int is_bought 1 if the software was bought-out at this point in time
	 */
	private $is_bought;

	private $model_variables_changed; // Only variables which have been changed
	private $model_variables_set; // All variables which have been set (initially or with a setter)
	private static $change_values = array('comment', 'status', 'bought');

	/* Parent tables */
	public $person;
	public $software;
	public $technician;
	public $software_status;

	/**
	 * Initialise and load related tables
	 */
	public static function init() {
		core::loadClass("database");
		core::loadClass("person_model");
		core::loadClass("software_model");
		core::loadClass("technician_model");
		core::loadClass("software_status_model");
	}

	/**
	 * Construct new software_history from field list
	 * 
	 * @return array
	 */
	public function __construct(array $fields = array()) {
		if(isset($fields['software_history.id'])) {
			$this -> set_id($fields['software_history.id']);
		}
		if(isset($fields['software_history.date'])) {
			$this -> set_date($fields['software_history.date']);
		}
		if(isset($fields['software_history.person_id'])) {
			$this -> set_person_id($fields['software_history.person_id']);
		}
		if(isset($fields['software_history.software_id'])) {
			$this -> set_software_id($fields['software_history.software_id']);
		}
		if(isset($fields['software_history.technician_id'])) {
			$this -> set_technician_id($fields['software_history.technician_id']);
		}
		if(isset($fields['software_history.software_status_id'])) {
			$this -> set_software_status_id($fields['software_history.software_status_id']);
		}
		if(isset($fields['software_history.comment'])) {
			$this -> set_comment($fields['software_history.comment']);
		}
		if(isset($fields['software_history.change'])) {
			$this -> set_change($fields['software_history.change']);
		}
		if(isset($fields['software_history.is_bought'])) {
			$this -> set_is_bought($fields['software_history.is_bought']);
		}

		$this -> model_variables_changed = array();
		$this -> person = new person_model($fields);
		$this -> software = new software_model($fields);
		$this -> technician = new technician_model($fields);
		$this -> software_status = new software_status_model($fields);
	}

	/**
	 * Convert software_history to shallow associative array
	 * 
	 * @return array
	 */
	private function to_array() {
		$values = array(
			'id' => $this -> id,
			'date' => $this -> date,
			'person_id' => $this -> person_id,
			'software_id' => $this -> software_id,
			'technician_id' => $this -> technician_id,
			'software_status_id' => $this -> software_status_id,
			'comment' => $this -> comment,
			'change' => $this -> change,
			'is_bought' => $this -> is_bought);
		return $values;
	}

	/**
	 * Convert software_history to associative array, including only visible fields,
	 * parent tables, and loaded child tables
	 * 
	 * @param string $role The user role to use
	 */
	public function to_array_filtered($role = "anon") {
		if(core::$permission[$role]['software_history']['read'] === false) {
			return false;
		}
		$values = array();
		$everything = $this -> to_array();
		foreach(core::$permission[$role]['software_history']['read'] as $field) {
			if(!isset($everything[$field])) {
				throw new Exception("Check permissions: '$field' is not a real field in software_history");
			}
			$values[$field] = $everything[$field];
		}
		$values['person'] = $this -> person -> to_array_filtered($role);
		$values['software'] = $this -> software -> to_array_filtered($role);
		$values['technician'] = $this -> technician -> to_array_filtered($role);
		$values['software_status'] = $this -> software_status -> to_array_filtered($role);
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
			"software_history.id" => $row[0],
			"software_history.date" => $row[1],
			"software_history.person_id" => $row[2],
			"software_history.software_id" => $row[3],
			"software_history.technician_id" => $row[4],
			"software_history.software_status_id" => $row[5],
			"software_history.comment" => $row[6],
			"software_history.change" => $row[7],
			"software_history.is_bought" => $row[8],
			"person.id" => $row[9],
			"person.code" => $row[10],
			"person.is_staff" => $row[11],
			"person.is_active" => $row[12],
			"person.firstname" => $row[13],
			"person.surname" => $row[14],
			"software.id" => $row[15],
			"software.code" => $row[16],
			"software.software_type_id" => $row[17],
			"software.software_status_id" => $row[18],
			"software.person_id" => $row[19],
			"software.is_bought" => $row[20],
			"technician.id" => $row[21],
			"technician.login" => $row[22],
			"technician.name" => $row[23],
			"software_status.id" => $row[24],
			"software_status.tag" => $row[25],
			"software_type.id" => $row[26],
			"software_type.name" => $row[27]);
		return $values;
	}

	/**
	 * Get id
	 * 
	 * @return int
	 */
	public function get_id() {
		if(!isset($this -> model_variables_set['id'])) {
			throw new Exception("software_history.id has not been initialised.");
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
			throw new Exception("software_history.id must be numeric");
		}
		$this -> id = $id;
		$this -> model_variables_changed['id'] = true;
		$this -> model_variables_set['id'] = true;
	}

	/**
	 * Get date
	 * 
	 * @return string
	 */
	public function get_date() {
		if(!isset($this -> model_variables_set['date'])) {
			throw new Exception("software_history.date has not been initialised.");
		}
		return $this -> date;
	}

	/**
	 * Set date
	 * 
	 * @param string $date
	 */
	public function set_date($date) {
		// TODO: Add validation to software_history.date
		$this -> date = $date;
		$this -> model_variables_changed['date'] = true;
		$this -> model_variables_set['date'] = true;
	}

	/**
	 * Get person_id
	 * 
	 * @return int
	 */
	public function get_person_id() {
		if(!isset($this -> model_variables_set['person_id'])) {
			throw new Exception("software_history.person_id has not been initialised.");
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
			throw new Exception("software_history.person_id must be numeric");
		}
		$this -> person_id = $person_id;
		$this -> model_variables_changed['person_id'] = true;
		$this -> model_variables_set['person_id'] = true;
	}

	/**
	 * Get software_id
	 * 
	 * @return int
	 */
	public function get_software_id() {
		if(!isset($this -> model_variables_set['software_id'])) {
			throw new Exception("software_history.software_id has not been initialised.");
		}
		return $this -> software_id;
	}

	/**
	 * Set software_id
	 * 
	 * @param int $software_id
	 */
	public function set_software_id($software_id) {
		if(!is_numeric($software_id)) {
			throw new Exception("software_history.software_id must be numeric");
		}
		$this -> software_id = $software_id;
		$this -> model_variables_changed['software_id'] = true;
		$this -> model_variables_set['software_id'] = true;
	}

	/**
	 * Get technician_id
	 * 
	 * @return int
	 */
	public function get_technician_id() {
		if(!isset($this -> model_variables_set['technician_id'])) {
			throw new Exception("software_history.technician_id has not been initialised.");
		}
		return $this -> technician_id;
	}

	/**
	 * Set technician_id
	 * 
	 * @param int $technician_id
	 */
	public function set_technician_id($technician_id) {
		if(!is_numeric($technician_id)) {
			throw new Exception("software_history.technician_id must be numeric");
		}
		$this -> technician_id = $technician_id;
		$this -> model_variables_changed['technician_id'] = true;
		$this -> model_variables_set['technician_id'] = true;
	}

	/**
	 * Get software_status_id
	 * 
	 * @return int
	 */
	public function get_software_status_id() {
		if(!isset($this -> model_variables_set['software_status_id'])) {
			throw new Exception("software_history.software_status_id has not been initialised.");
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
			throw new Exception("software_history.software_status_id must be numeric");
		}
		$this -> software_status_id = $software_status_id;
		$this -> model_variables_changed['software_status_id'] = true;
		$this -> model_variables_set['software_status_id'] = true;
	}

	/**
	 * Get comment
	 * 
	 * @return string
	 */
	public function get_comment() {
		if(!isset($this -> model_variables_set['comment'])) {
			throw new Exception("software_history.comment has not been initialised.");
		}
		return $this -> comment;
	}

	/**
	 * Set comment
	 * 
	 * @param string $comment
	 */
	public function set_comment($comment) {
		// TODO: Add TEXT validation to software_history.comment
		$this -> comment = $comment;
		$this -> model_variables_changed['comment'] = true;
		$this -> model_variables_set['comment'] = true;
	}

	/**
	 * Get change
	 * 
	 * @return string
	 */
	public function get_change() {
		if(!isset($this -> model_variables_set['change'])) {
			throw new Exception("software_history.change has not been initialised.");
		}
		return $this -> change;
	}

	/**
	 * Set change
	 * 
	 * @param string $change
	 */
	public function set_change($change) {
		if(!in_array($change, self::$change_values)) {
			throw new Exception("software_history.change must be one of the defined values.");
		}
		$this -> change = $change;
		$this -> model_variables_changed['change'] = true;
		$this -> model_variables_set['change'] = true;
	}

	/**
	 * Get is_bought
	 * 
	 * @return int
	 */
	public function get_is_bought() {
		if(!isset($this -> model_variables_set['is_bought'])) {
			throw new Exception("software_history.is_bought has not been initialised.");
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
			throw new Exception("software_history.is_bought must be numeric");
		}
		$this -> is_bought = $is_bought;
		$this -> model_variables_changed['is_bought'] = true;
		$this -> model_variables_set['is_bought'] = true;
	}

	/**
	 * Update software_history
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
		$sth = database::$dbh -> prepare("UPDATE software_history SET $fields WHERE id = :id");
		$sth -> execute($data);
	}

	/**
	 * Add new software_history
	 */
	public function insert() {
		if(count($this -> model_variables_changed) == 0) {
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
		$sth = database::$dbh -> prepare("INSERT INTO software_history ($fields) VALUES ($vals);");
		$sth -> execute($data);
	}

	/**
	 * Delete software_history
	 */
	public function delete() {
		$sth = database::$dbh -> prepare("DELETE FROM software_history WHERE id = :id");
		$data['id'] = $this -> get_id();
		$sth -> execute($data);
	}

	/**
	 * Retrieve by primary key
	 */
	public static function get($id) {
		$sth = database::$dbh -> prepare("SELECT software_history.id, software_history.date, software_history.person_id, software_history.software_id, software_history.technician_id, software_history.software_status_id, software_history.comment, software_history.change, software_history.is_bought, person.id, person.code, person.is_staff, person.is_active, person.firstname, person.surname, software.id, software.code, software.software_type_id, software.software_status_id, software.person_id, software.is_bought, technician.id, technician.login, technician.name, software_status.id, software_status.tag, software_type.id, software_type.name FROM software_history JOIN person ON software_history.person_id = person.id JOIN software ON software_history.software_id = software.id JOIN technician ON software_history.technician_id = technician.id JOIN software_status ON software_history.software_status_id = software_status.id JOIN software_type ON software.software_type_id = software_type.id WHERE software_history.id = :id;");
		$sth -> execute(array('id' => $id));
		$row = $sth -> fetch(PDO::FETCH_NUM);
		if($row === false){
			return false;
		}
		$assoc = self::row_to_assoc($row);
		return new software_history_model($assoc);
	}

	/**
	 * List rows by person_id index
	 * 
	 * @param int $start Row to begin from. Default 0 (begin from start)
	 * @param int $limit Maximum number of rows to retrieve. Default -1 (no limit)
	 */
	public static function list_by_person_id($person_id, $start = 0, $limit = -1) {
		$ls = "";
		$start = (int)$start;
		$limit = (int)$limit;
		if($start > 0 && $limit > 0) {
			$ls = " LIMIT $start, " . ($start + $limit);
		}
		$sth = database::$dbh -> prepare("SELECT software_history.id, software_history.date, software_history.person_id, software_history.software_id, software_history.technician_id, software_history.software_status_id, software_history.comment, software_history.change, software_history.is_bought, person.id, person.code, person.is_staff, person.is_active, person.firstname, person.surname, software.id, software.code, software.software_type_id, software.software_status_id, software.person_id, software.is_bought, technician.id, technician.login, technician.name, software_status.id, software_status.tag, software_type.id, software_type.name FROM software_history JOIN person ON software_history.person_id = person.id JOIN software ON software_history.software_id = software.id JOIN technician ON software_history.technician_id = technician.id JOIN software_status ON software_history.software_status_id = software_status.id JOIN software_type ON software.software_type_id = software_type.id WHERE software_history.person_id = :person_id" . $ls . ";");
		$sth -> execute(array('person_id' => $person_id));
		$rows = $sth -> fetchAll(PDO::FETCH_NUM);
		$ret = array();
		foreach($rows as $row) {
			$assoc = self::row_to_assoc($row);
			$ret[] = new software_history_model($assoc);
		}
		return $ret;
	}

	/**
	 * List rows by software_id index
	 * 
	 * @param int $start Row to begin from. Default 0 (begin from start)
	 * @param int $limit Maximum number of rows to retrieve. Default -1 (no limit)
	 */
	public static function list_by_software_id($software_id, $start = 0, $limit = -1) {
		$ls = "";
		$start = (int)$start;
		$limit = (int)$limit;
		if($start > 0 && $limit > 0) {
			$ls = " LIMIT $start, " . ($start + $limit);
		}
		$sth = database::$dbh -> prepare("SELECT software_history.id, software_history.date, software_history.person_id, software_history.software_id, software_history.technician_id, software_history.software_status_id, software_history.comment, software_history.change, software_history.is_bought, person.id, person.code, person.is_staff, person.is_active, person.firstname, person.surname, software.id, software.code, software.software_type_id, software.software_status_id, software.person_id, software.is_bought, technician.id, technician.login, technician.name, software_status.id, software_status.tag, software_type.id, software_type.name FROM software_history JOIN person ON software_history.person_id = person.id JOIN software ON software_history.software_id = software.id JOIN technician ON software_history.technician_id = technician.id JOIN software_status ON software_history.software_status_id = software_status.id JOIN software_type ON software.software_type_id = software_type.id WHERE software_history.software_id = :software_id" . $ls . ";");
		$sth -> execute(array('software_id' => $software_id));
		$rows = $sth -> fetchAll(PDO::FETCH_NUM);
		$ret = array();
		foreach($rows as $row) {
			$assoc = self::row_to_assoc($row);
			$ret[] = new software_history_model($assoc);
		}
		return $ret;
	}

	/**
	 * List rows by technician_id index
	 * 
	 * @param int $start Row to begin from. Default 0 (begin from start)
	 * @param int $limit Maximum number of rows to retrieve. Default -1 (no limit)
	 */
	public static function list_by_technician_id($technician_id, $start = 0, $limit = -1) {
		$ls = "";
		$start = (int)$start;
		$limit = (int)$limit;
		if($start > 0 && $limit > 0) {
			$ls = " LIMIT $start, " . ($start + $limit);
		}
		$sth = database::$dbh -> prepare("SELECT software_history.id, software_history.date, software_history.person_id, software_history.software_id, software_history.technician_id, software_history.software_status_id, software_history.comment, software_history.change, software_history.is_bought, person.id, person.code, person.is_staff, person.is_active, person.firstname, person.surname, software.id, software.code, software.software_type_id, software.software_status_id, software.person_id, software.is_bought, technician.id, technician.login, technician.name, software_status.id, software_status.tag, software_type.id, software_type.name FROM software_history JOIN person ON software_history.person_id = person.id JOIN software ON software_history.software_id = software.id JOIN technician ON software_history.technician_id = technician.id JOIN software_status ON software_history.software_status_id = software_status.id JOIN software_type ON software.software_type_id = software_type.id WHERE software_history.technician_id = :technician_id" . $ls . ";");
		$sth -> execute(array('technician_id' => $technician_id));
		$rows = $sth -> fetchAll(PDO::FETCH_NUM);
		$ret = array();
		foreach($rows as $row) {
			$assoc = self::row_to_assoc($row);
			$ret[] = new software_history_model($assoc);
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
		if($start > 0 && $limit > 0) {
			$ls = " LIMIT $start, " . ($start + $limit);
		}
		$sth = database::$dbh -> prepare("SELECT software_history.id, software_history.date, software_history.person_id, software_history.software_id, software_history.technician_id, software_history.software_status_id, software_history.comment, software_history.change, software_history.is_bought, person.id, person.code, person.is_staff, person.is_active, person.firstname, person.surname, software.id, software.code, software.software_type_id, software.software_status_id, software.person_id, software.is_bought, technician.id, technician.login, technician.name, software_status.id, software_status.tag, software_type.id, software_type.name FROM software_history JOIN person ON software_history.person_id = person.id JOIN software ON software_history.software_id = software.id JOIN technician ON software_history.technician_id = technician.id JOIN software_status ON software_history.software_status_id = software_status.id JOIN software_type ON software.software_type_id = software_type.id WHERE software_history.software_status_id = :software_status_id" . $ls . ";");
		$sth -> execute(array('software_status_id' => $software_status_id));
		$rows = $sth -> fetchAll(PDO::FETCH_NUM);
		$ret = array();
		foreach($rows as $row) {
			$assoc = self::row_to_assoc($row);
			$ret[] = new software_history_model($assoc);
		}
		return $ret;
	}
}
?>