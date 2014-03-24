<?php
class device_history_model {
	/**
	 * @var int id ID of device history entry
	 */
	private $id;

	/**
	 * @var string date Time of this log entry
	 */
	private $date;

	/**
	 * @var string comment Technician comment
	 */
	private $comment;

	/**
	 * @var int is_spare 1 if device is a 'spare', 0 if not
	 */
	private $is_spare;

	/**
	 * @var int is_damaged 1 if device is damaged, 0 otherwise
	 */
	private $is_damaged;

	/**
	 * @var int has_photos 1 if photos have been uploaded, 0 otherwise
	 */
	private $has_photos;

	/**
	 * @var int is_bought 1 for bought-out device, 0 for organisation-owned
	 */
	private $is_bought;

	/**
	 * @var string change Field which was changed with this log entry
	 */
	private $change;

	/**
	 * @var int technician_id Technician who uploaded the entryn
	 */
	private $technician_id;

	/**
	 * @var int device_id Device being referred to
	 */
	private $device_id;

	/**
	 * @var int device_status_id Status code fot the device
	 */
	private $device_status_id;

	/**
	 * @var int person_id ID of device holder at this point in timen
	 */
	private $person_id;

	private $model_variables_changed; // Only variables which have been changed
	private $model_variables_set; // All variables which have been set (initially or with a setter)
	private static $change_values = array('comment', 'photo', 'owner', 'status', 'damaged', 'spare', 'bought');

	/* Parent tables */
	public $technician;
	public $device;
	public $device_status;
	public $person;

	/* Child tables */
	public $list_device_photo;

	/* Sort clause to add when listing rows from this table */
	const SORT_CLAUSE = " ORDER BY `device_history`.`date` DESC";

	/**
	 * Initialise and load related tables
	 */
	public static function init() {
		core::loadClass("database");
		core::loadClass("technician_model");
		core::loadClass("device_model");
		core::loadClass("device_status_model");
		core::loadClass("person_model");

		/* Child tables */
		core::loadClass("device_photo_model");
	}

	/**
	 * Construct new device_history from field list
	 * 
	 * @return array
	 */
	public function __construct(array $fields = array()) {
		/* Initialise everything as blank to avoid tripping up the permissions fitlers */
		$this -> id = '';
		$this -> date = '';
		$this -> comment = '';
		$this -> is_spare = '';
		$this -> is_damaged = '';
		$this -> has_photos = '';
		$this -> is_bought = '';
		$this -> change = '';
		$this -> technician_id = '';
		$this -> device_id = '';
		$this -> device_status_id = '';
		$this -> person_id = '';

		if(isset($fields['device_history.id'])) {
			$this -> set_id($fields['device_history.id']);
		}
		if(isset($fields['device_history.date'])) {
			$this -> set_date($fields['device_history.date']);
		}
		if(isset($fields['device_history.comment'])) {
			$this -> set_comment($fields['device_history.comment']);
		}
		if(isset($fields['device_history.is_spare'])) {
			$this -> set_is_spare($fields['device_history.is_spare']);
		}
		if(isset($fields['device_history.is_damaged'])) {
			$this -> set_is_damaged($fields['device_history.is_damaged']);
		}
		if(isset($fields['device_history.has_photos'])) {
			$this -> set_has_photos($fields['device_history.has_photos']);
		}
		if(isset($fields['device_history.is_bought'])) {
			$this -> set_is_bought($fields['device_history.is_bought']);
		}
		if(isset($fields['device_history.change'])) {
			$this -> set_change($fields['device_history.change']);
		}
		if(isset($fields['device_history.technician_id'])) {
			$this -> set_technician_id($fields['device_history.technician_id']);
		}
		if(isset($fields['device_history.device_id'])) {
			$this -> set_device_id($fields['device_history.device_id']);
		}
		if(isset($fields['device_history.device_status_id'])) {
			$this -> set_device_status_id($fields['device_history.device_status_id']);
		}
		if(isset($fields['device_history.person_id'])) {
			$this -> set_person_id($fields['device_history.person_id']);
		}

		$this -> model_variables_changed = array();
		$this -> technician = new technician_model($fields);
		$this -> device = new device_model($fields);
		$this -> device_status = new device_status_model($fields);
		$this -> person = new person_model($fields);
		$this -> list_device_photo = array();
	}

	/**
	 * Convert device_history to shallow associative array
	 * 
	 * @return array
	 */
	private function to_array() {
		$values = array(
			'id' => $this -> id,
			'date' => $this -> date,
			'comment' => $this -> comment,
			'is_spare' => $this -> is_spare,
			'is_damaged' => $this -> is_damaged,
			'has_photos' => $this -> has_photos,
			'is_bought' => $this -> is_bought,
			'change' => $this -> change,
			'technician_id' => $this -> technician_id,
			'device_id' => $this -> device_id,
			'device_status_id' => $this -> device_status_id,
			'person_id' => $this -> person_id);
		return $values;
	}

	/**
	 * Convert device_history to associative array, including only visible fields,
	 * parent tables, and loaded child tables
	 * 
	 * @param string $role The user role to use
	 */
	public function to_array_filtered($role = "anon") {
		if(core::$permission[$role]['device_history']['read'] === false) {
			return false;
		}
		$values = array();
		$everything = $this -> to_array();
		foreach(core::$permission[$role]['device_history']['read'] as $field) {
			if(!isset($everything[$field])) {
				throw new Exception("Check permissions: '$field' is not a real field in device_history");
			}
			$values[$field] = $everything[$field];
		}
		$values['technician'] = $this -> technician -> to_array_filtered($role);
		$values['device'] = $this -> device -> to_array_filtered($role);
		$values['device_status'] = $this -> device_status -> to_array_filtered($role);
		$values['person'] = $this -> person -> to_array_filtered($role);

		/* Add filtered versions of everything that's been loaded */
		$values['device_photo'] = array();
		foreach($this -> list_device_photo as $device_photo) {
			$values['device_photo'][] = $device_photo -> to_array_filtered($role);
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
			"device_history.id" => $row[0],
			"device_history.date" => $row[1],
			"device_history.comment" => $row[2],
			"device_history.is_spare" => $row[3],
			"device_history.is_damaged" => $row[4],
			"device_history.has_photos" => $row[5],
			"device_history.is_bought" => $row[6],
			"device_history.change" => $row[7],
			"device_history.technician_id" => $row[8],
			"device_history.device_id" => $row[9],
			"device_history.device_status_id" => $row[10],
			"device_history.person_id" => $row[11],
			"technician.id" => $row[12],
			"technician.login" => $row[13],
			"technician.name" => $row[14],
			"technician.is_active" => $row[15],
			"device.id" => $row[16],
			"device.is_spare" => $row[17],
			"device.is_damaged" => $row[18],
			"device.sn" => $row[19],
			"device.mac_eth0" => $row[20],
			"device.mac_wlan0" => $row[21],
			"device.is_bought" => $row[22],
			"device.person_id" => $row[23],
			"device.device_status_id" => $row[24],
			"device.device_type_id" => $row[25],
			"device_status.id" => $row[26],
			"device_status.tag" => $row[27],
			"person.id" => $row[28],
			"person.code" => $row[29],
			"person.is_staff" => $row[30],
			"person.is_active" => $row[31],
			"person.firstname" => $row[32],
			"person.surname" => $row[33],
			"device_type.id" => $row[34],
			"device_type.name" => $row[35],
			"device_type.model_no" => $row[36]);
		return $values;
	}

	/**
	 * Get id
	 * 
	 * @return int
	 */
	public function get_id() {
		if(!isset($this -> model_variables_set['id'])) {
			throw new Exception("device_history.id has not been initialised.");
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
			throw new Exception("device_history.id must be numeric");
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
			throw new Exception("device_history.date has not been initialised.");
		}
		return $this -> date;
	}

	/**
	 * Set date
	 * 
	 * @param string $date
	 */
	public function set_date($date) {
		// TODO: Add validation to device_history.date
		$this -> date = $date;
		$this -> model_variables_changed['date'] = true;
		$this -> model_variables_set['date'] = true;
	}

	/**
	 * Get comment
	 * 
	 * @return string
	 */
	public function get_comment() {
		if(!isset($this -> model_variables_set['comment'])) {
			throw new Exception("device_history.comment has not been initialised.");
		}
		return $this -> comment;
	}

	/**
	 * Set comment
	 * 
	 * @param string $comment
	 */
	public function set_comment($comment) {
		// TODO: Add TEXT validation to device_history.comment
		$this -> comment = $comment;
		$this -> model_variables_changed['comment'] = true;
		$this -> model_variables_set['comment'] = true;
	}

	/**
	 * Get is_spare
	 * 
	 * @return int
	 */
	public function get_is_spare() {
		if(!isset($this -> model_variables_set['is_spare'])) {
			throw new Exception("device_history.is_spare has not been initialised.");
		}
		return $this -> is_spare;
	}

	/**
	 * Set is_spare
	 * 
	 * @param int $is_spare
	 */
	public function set_is_spare($is_spare) {
		if(!is_numeric($is_spare)) {
			throw new Exception("device_history.is_spare must be numeric");
		}
		$this -> is_spare = $is_spare;
		$this -> model_variables_changed['is_spare'] = true;
		$this -> model_variables_set['is_spare'] = true;
	}

	/**
	 * Get is_damaged
	 * 
	 * @return int
	 */
	public function get_is_damaged() {
		if(!isset($this -> model_variables_set['is_damaged'])) {
			throw new Exception("device_history.is_damaged has not been initialised.");
		}
		return $this -> is_damaged;
	}

	/**
	 * Set is_damaged
	 * 
	 * @param int $is_damaged
	 */
	public function set_is_damaged($is_damaged) {
		if(!is_numeric($is_damaged)) {
			throw new Exception("device_history.is_damaged must be numeric");
		}
		$this -> is_damaged = $is_damaged;
		$this -> model_variables_changed['is_damaged'] = true;
		$this -> model_variables_set['is_damaged'] = true;
	}

	/**
	 * Get has_photos
	 * 
	 * @return int
	 */
	public function get_has_photos() {
		if(!isset($this -> model_variables_set['has_photos'])) {
			throw new Exception("device_history.has_photos has not been initialised.");
		}
		return $this -> has_photos;
	}

	/**
	 * Set has_photos
	 * 
	 * @param int $has_photos
	 */
	public function set_has_photos($has_photos) {
		if(!is_numeric($has_photos)) {
			throw new Exception("device_history.has_photos must be numeric");
		}
		$this -> has_photos = $has_photos;
		$this -> model_variables_changed['has_photos'] = true;
		$this -> model_variables_set['has_photos'] = true;
	}

	/**
	 * Get is_bought
	 * 
	 * @return int
	 */
	public function get_is_bought() {
		if(!isset($this -> model_variables_set['is_bought'])) {
			throw new Exception("device_history.is_bought has not been initialised.");
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
			throw new Exception("device_history.is_bought must be numeric");
		}
		$this -> is_bought = $is_bought;
		$this -> model_variables_changed['is_bought'] = true;
		$this -> model_variables_set['is_bought'] = true;
	}

	/**
	 * Get change
	 * 
	 * @return string
	 */
	public function get_change() {
		if(!isset($this -> model_variables_set['change'])) {
			throw new Exception("device_history.change has not been initialised.");
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
			throw new Exception("device_history.change must be one of the defined values.");
		}
		$this -> change = $change;
		$this -> model_variables_changed['change'] = true;
		$this -> model_variables_set['change'] = true;
	}

	/**
	 * Get technician_id
	 * 
	 * @return int
	 */
	public function get_technician_id() {
		if(!isset($this -> model_variables_set['technician_id'])) {
			throw new Exception("device_history.technician_id has not been initialised.");
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
			throw new Exception("device_history.technician_id must be numeric");
		}
		$this -> technician_id = $technician_id;
		$this -> model_variables_changed['technician_id'] = true;
		$this -> model_variables_set['technician_id'] = true;
	}

	/**
	 * Get device_id
	 * 
	 * @return int
	 */
	public function get_device_id() {
		if(!isset($this -> model_variables_set['device_id'])) {
			throw new Exception("device_history.device_id has not been initialised.");
		}
		return $this -> device_id;
	}

	/**
	 * Set device_id
	 * 
	 * @param int $device_id
	 */
	public function set_device_id($device_id) {
		if(!is_numeric($device_id)) {
			throw new Exception("device_history.device_id must be numeric");
		}
		$this -> device_id = $device_id;
		$this -> model_variables_changed['device_id'] = true;
		$this -> model_variables_set['device_id'] = true;
	}

	/**
	 * Get device_status_id
	 * 
	 * @return int
	 */
	public function get_device_status_id() {
		if(!isset($this -> model_variables_set['device_status_id'])) {
			throw new Exception("device_history.device_status_id has not been initialised.");
		}
		return $this -> device_status_id;
	}

	/**
	 * Set device_status_id
	 * 
	 * @param int $device_status_id
	 */
	public function set_device_status_id($device_status_id) {
		if(!is_numeric($device_status_id)) {
			throw new Exception("device_history.device_status_id must be numeric");
		}
		$this -> device_status_id = $device_status_id;
		$this -> model_variables_changed['device_status_id'] = true;
		$this -> model_variables_set['device_status_id'] = true;
	}

	/**
	 * Get person_id
	 * 
	 * @return int
	 */
	public function get_person_id() {
		if(!isset($this -> model_variables_set['person_id'])) {
			throw new Exception("device_history.person_id has not been initialised.");
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
			throw new Exception("device_history.person_id must be numeric");
		}
		$this -> person_id = $person_id;
		$this -> model_variables_changed['person_id'] = true;
		$this -> model_variables_set['person_id'] = true;
	}

	/**
	 * Update device_history
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
		$sth = database::$dbh -> prepare("UPDATE `device_history` SET $fields WHERE `device_history`.`id` = :id");
		$sth -> execute($data);
	}

	/**
	 * Add new device_history
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
		$sth = database::$dbh -> prepare("INSERT INTO `device_history` ($fields) VALUES ($vals);");
		$sth -> execute($data);
		$this -> set_id(database::$dbh->lastInsertId());
	}

	/**
	 * Delete device_history
	 */
	public function delete() {
		$sth = database::$dbh -> prepare("DELETE FROM `device_history` WHERE `device_history`.`id` = :id");
		$data['id'] = $this -> get_id();
		$sth -> execute($data);
	}

	/**
	 * List associated rows from device_photo table
	 * 
	 * @param int $start Row to begin from. Default 0 (begin from start)
	 * @param int $limit Maximum number of rows to retrieve. Default -1 (no limit)
	 */
	public function populate_list_device_photo($start = 0, $limit = -1) {
		$device_history_id = $this -> get_id();
		$this -> list_device_photo = device_photo_model::list_by_device_history_id($device_history_id, $start, $limit);
	}

	/**
	 * Retrieve by primary key
	 */
	public static function get($id) {
		$sth = database::$dbh -> prepare("SELECT `device_history`.`id`, `device_history`.`date`, `device_history`.`comment`, `device_history`.`is_spare`, `device_history`.`is_damaged`, `device_history`.`has_photos`, `device_history`.`is_bought`, `device_history`.`change`, `device_history`.`technician_id`, `device_history`.`device_id`, `device_history`.`device_status_id`, `device_history`.`person_id`, `technician`.`id`, `technician`.`login`, `technician`.`name`, `technician`.`is_active`, `device`.`id`, `device`.`is_spare`, `device`.`is_damaged`, `device`.`sn`, `device`.`mac_eth0`, `device`.`mac_wlan0`, `device`.`is_bought`, `device`.`person_id`, `device`.`device_status_id`, `device`.`device_type_id`, `device_status`.`id`, `device_status`.`tag`, `person`.`id`, `person`.`code`, `person`.`is_staff`, `person`.`is_active`, `person`.`firstname`, `person`.`surname`, `device_type`.`id`, `device_type`.`name`, `device_type`.`model_no` FROM device_history JOIN `technician` ON `device_history`.`technician_id` = `technician`.`id` JOIN `device` ON `device_history`.`device_id` = `device`.`id` JOIN `device_status` ON `device_history`.`device_status_id` = `device_status`.`id` JOIN `person` ON `device_history`.`person_id` = `person`.`id` JOIN `device_type` ON `device`.`device_type_id` = `device_type`.`id` WHERE `device_history`.`id` = :id;");
		$sth -> execute(array('id' => $id));
		$row = $sth -> fetch(PDO::FETCH_NUM);
		if($row === false){
			return false;
		}
		$assoc = self::row_to_assoc($row);
		return new device_history_model($assoc);
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
		$sth = database::$dbh -> prepare("SELECT `device_history`.`id`, `device_history`.`date`, `device_history`.`comment`, `device_history`.`is_spare`, `device_history`.`is_damaged`, `device_history`.`has_photos`, `device_history`.`is_bought`, `device_history`.`change`, `device_history`.`technician_id`, `device_history`.`device_id`, `device_history`.`device_status_id`, `device_history`.`person_id`, `technician`.`id`, `technician`.`login`, `technician`.`name`, `technician`.`is_active`, `device`.`id`, `device`.`is_spare`, `device`.`is_damaged`, `device`.`sn`, `device`.`mac_eth0`, `device`.`mac_wlan0`, `device`.`is_bought`, `device`.`person_id`, `device`.`device_status_id`, `device`.`device_type_id`, `device_status`.`id`, `device_status`.`tag`, `person`.`id`, `person`.`code`, `person`.`is_staff`, `person`.`is_active`, `person`.`firstname`, `person`.`surname`, `device_type`.`id`, `device_type`.`name`, `device_type`.`model_no` FROM `device_history` JOIN `technician` ON `device_history`.`technician_id` = `technician`.`id` JOIN `device` ON `device_history`.`device_id` = `device`.`id` JOIN `device_status` ON `device_history`.`device_status_id` = `device_status`.`id` JOIN `person` ON `device_history`.`person_id` = `person`.`id` JOIN `device_type` ON `device`.`device_type_id` = `device_type`.`id`" . self::SORT_CLAUSE . $ls . ";");
		$sth -> execute();
		$rows = $sth -> fetchAll(PDO::FETCH_NUM);
		$ret = array();
		foreach($rows as $row) {
			$assoc = self::row_to_assoc($row);
			$ret[] = new device_history_model($assoc);
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
		if($start >= 0 && $limit > 0) {
			$ls = " LIMIT $start, $limit";
		}
		$sth = database::$dbh -> prepare("SELECT `device_history`.`id`, `device_history`.`date`, `device_history`.`comment`, `device_history`.`is_spare`, `device_history`.`is_damaged`, `device_history`.`has_photos`, `device_history`.`is_bought`, `device_history`.`change`, `device_history`.`technician_id`, `device_history`.`device_id`, `device_history`.`device_status_id`, `device_history`.`person_id`, `technician`.`id`, `technician`.`login`, `technician`.`name`, `technician`.`is_active`, `device`.`id`, `device`.`is_spare`, `device`.`is_damaged`, `device`.`sn`, `device`.`mac_eth0`, `device`.`mac_wlan0`, `device`.`is_bought`, `device`.`person_id`, `device`.`device_status_id`, `device`.`device_type_id`, `device_status`.`id`, `device_status`.`tag`, `person`.`id`, `person`.`code`, `person`.`is_staff`, `person`.`is_active`, `person`.`firstname`, `person`.`surname`, `device_type`.`id`, `device_type`.`name`, `device_type`.`model_no` FROM `device_history` JOIN `technician` ON `device_history`.`technician_id` = `technician`.`id` JOIN `device` ON `device_history`.`device_id` = `device`.`id` JOIN `device_status` ON `device_history`.`device_status_id` = `device_status`.`id` JOIN `person` ON `device_history`.`person_id` = `person`.`id` JOIN `device_type` ON `device`.`device_type_id` = `device_type`.`id` WHERE device_history.technician_id = :technician_id" . self::SORT_CLAUSE . $ls . ";");
		$sth -> execute(array('technician_id' => $technician_id));
		$rows = $sth -> fetchAll(PDO::FETCH_NUM);
		$ret = array();
		foreach($rows as $row) {
			$assoc = self::row_to_assoc($row);
			$ret[] = new device_history_model($assoc);
		}
		return $ret;
	}

	/**
	 * List rows by device_id index
	 * 
	 * @param int $start Row to begin from. Default 0 (begin from start)
	 * @param int $limit Maximum number of rows to retrieve. Default -1 (no limit)
	 */
	public static function list_by_device_id($device_id, $start = 0, $limit = -1) {
		$ls = "";
		$start = (int)$start;
		$limit = (int)$limit;
		if($start >= 0 && $limit > 0) {
			$ls = " LIMIT $start, $limit";
		}
		$sth = database::$dbh -> prepare("SELECT `device_history`.`id`, `device_history`.`date`, `device_history`.`comment`, `device_history`.`is_spare`, `device_history`.`is_damaged`, `device_history`.`has_photos`, `device_history`.`is_bought`, `device_history`.`change`, `device_history`.`technician_id`, `device_history`.`device_id`, `device_history`.`device_status_id`, `device_history`.`person_id`, `technician`.`id`, `technician`.`login`, `technician`.`name`, `technician`.`is_active`, `device`.`id`, `device`.`is_spare`, `device`.`is_damaged`, `device`.`sn`, `device`.`mac_eth0`, `device`.`mac_wlan0`, `device`.`is_bought`, `device`.`person_id`, `device`.`device_status_id`, `device`.`device_type_id`, `device_status`.`id`, `device_status`.`tag`, `person`.`id`, `person`.`code`, `person`.`is_staff`, `person`.`is_active`, `person`.`firstname`, `person`.`surname`, `device_type`.`id`, `device_type`.`name`, `device_type`.`model_no` FROM `device_history` JOIN `technician` ON `device_history`.`technician_id` = `technician`.`id` JOIN `device` ON `device_history`.`device_id` = `device`.`id` JOIN `device_status` ON `device_history`.`device_status_id` = `device_status`.`id` JOIN `person` ON `device_history`.`person_id` = `person`.`id` JOIN `device_type` ON `device`.`device_type_id` = `device_type`.`id` WHERE device_history.device_id = :device_id" . self::SORT_CLAUSE . $ls . ";");
		$sth -> execute(array('device_id' => $device_id));
		$rows = $sth -> fetchAll(PDO::FETCH_NUM);
		$ret = array();
		foreach($rows as $row) {
			$assoc = self::row_to_assoc($row);
			$ret[] = new device_history_model($assoc);
		}
		return $ret;
	}

	/**
	 * List rows by device_status_id index
	 * 
	 * @param int $start Row to begin from. Default 0 (begin from start)
	 * @param int $limit Maximum number of rows to retrieve. Default -1 (no limit)
	 */
	public static function list_by_device_status_id($device_status_id, $start = 0, $limit = -1) {
		$ls = "";
		$start = (int)$start;
		$limit = (int)$limit;
		if($start >= 0 && $limit > 0) {
			$ls = " LIMIT $start, $limit";
		}
		$sth = database::$dbh -> prepare("SELECT `device_history`.`id`, `device_history`.`date`, `device_history`.`comment`, `device_history`.`is_spare`, `device_history`.`is_damaged`, `device_history`.`has_photos`, `device_history`.`is_bought`, `device_history`.`change`, `device_history`.`technician_id`, `device_history`.`device_id`, `device_history`.`device_status_id`, `device_history`.`person_id`, `technician`.`id`, `technician`.`login`, `technician`.`name`, `technician`.`is_active`, `device`.`id`, `device`.`is_spare`, `device`.`is_damaged`, `device`.`sn`, `device`.`mac_eth0`, `device`.`mac_wlan0`, `device`.`is_bought`, `device`.`person_id`, `device`.`device_status_id`, `device`.`device_type_id`, `device_status`.`id`, `device_status`.`tag`, `person`.`id`, `person`.`code`, `person`.`is_staff`, `person`.`is_active`, `person`.`firstname`, `person`.`surname`, `device_type`.`id`, `device_type`.`name`, `device_type`.`model_no` FROM `device_history` JOIN `technician` ON `device_history`.`technician_id` = `technician`.`id` JOIN `device` ON `device_history`.`device_id` = `device`.`id` JOIN `device_status` ON `device_history`.`device_status_id` = `device_status`.`id` JOIN `person` ON `device_history`.`person_id` = `person`.`id` JOIN `device_type` ON `device`.`device_type_id` = `device_type`.`id` WHERE device_history.device_status_id = :device_status_id" . self::SORT_CLAUSE . $ls . ";");
		$sth -> execute(array('device_status_id' => $device_status_id));
		$rows = $sth -> fetchAll(PDO::FETCH_NUM);
		$ret = array();
		foreach($rows as $row) {
			$assoc = self::row_to_assoc($row);
			$ret[] = new device_history_model($assoc);
		}
		return $ret;
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
		if($start >= 0 && $limit > 0) {
			$ls = " LIMIT $start, $limit";
		}
		$sth = database::$dbh -> prepare("SELECT `device_history`.`id`, `device_history`.`date`, `device_history`.`comment`, `device_history`.`is_spare`, `device_history`.`is_damaged`, `device_history`.`has_photos`, `device_history`.`is_bought`, `device_history`.`change`, `device_history`.`technician_id`, `device_history`.`device_id`, `device_history`.`device_status_id`, `device_history`.`person_id`, `technician`.`id`, `technician`.`login`, `technician`.`name`, `technician`.`is_active`, `device`.`id`, `device`.`is_spare`, `device`.`is_damaged`, `device`.`sn`, `device`.`mac_eth0`, `device`.`mac_wlan0`, `device`.`is_bought`, `device`.`person_id`, `device`.`device_status_id`, `device`.`device_type_id`, `device_status`.`id`, `device_status`.`tag`, `person`.`id`, `person`.`code`, `person`.`is_staff`, `person`.`is_active`, `person`.`firstname`, `person`.`surname`, `device_type`.`id`, `device_type`.`name`, `device_type`.`model_no` FROM `device_history` JOIN `technician` ON `device_history`.`technician_id` = `technician`.`id` JOIN `device` ON `device_history`.`device_id` = `device`.`id` JOIN `device_status` ON `device_history`.`device_status_id` = `device_status`.`id` JOIN `person` ON `device_history`.`person_id` = `person`.`id` JOIN `device_type` ON `device`.`device_type_id` = `device_type`.`id` WHERE device_history.person_id = :person_id" . self::SORT_CLAUSE . $ls . ";");
		$sth -> execute(array('person_id' => $person_id));
		$rows = $sth -> fetchAll(PDO::FETCH_NUM);
		$ret = array();
		foreach($rows as $row) {
			$assoc = self::row_to_assoc($row);
			$ret[] = new device_history_model($assoc);
		}
		return $ret;
	}

	/**
	 * Simple search within date field
	 * 
	 * @param int $start Row to begin from. Default 0 (begin from start)
	 * @param int $limit Maximum number of rows to retrieve. Default -1 (no limit)
	 */
	public static function search_by_date($search, $start = 0, $limit = -1) {
		$ls = "";
		$start = (int)$start;
		$limit = (int)$limit;
		if($start >= 0 && $limit > 0) {
			$ls = " LIMIT $start, $limit";
		}
		$sth = database::$dbh -> prepare("SELECT `device_history`.`id`, `device_history`.`date`, `device_history`.`comment`, `device_history`.`is_spare`, `device_history`.`is_damaged`, `device_history`.`has_photos`, `device_history`.`is_bought`, `device_history`.`change`, `device_history`.`technician_id`, `device_history`.`device_id`, `device_history`.`device_status_id`, `device_history`.`person_id`, `technician`.`id`, `technician`.`login`, `technician`.`name`, `technician`.`is_active`, `device`.`id`, `device`.`is_spare`, `device`.`is_damaged`, `device`.`sn`, `device`.`mac_eth0`, `device`.`mac_wlan0`, `device`.`is_bought`, `device`.`person_id`, `device`.`device_status_id`, `device`.`device_type_id`, `device_status`.`id`, `device_status`.`tag`, `person`.`id`, `person`.`code`, `person`.`is_staff`, `person`.`is_active`, `person`.`firstname`, `person`.`surname`, `device_type`.`id`, `device_type`.`name`, `device_type`.`model_no` FROM `device_history` JOIN `technician` ON `device_history`.`technician_id` = `technician`.`id` JOIN `device` ON `device_history`.`device_id` = `device`.`id` JOIN `device_status` ON `device_history`.`device_status_id` = `device_status`.`id` JOIN `person` ON `device_history`.`person_id` = `person`.`id` JOIN `device_type` ON `device`.`device_type_id` = `device_type`.`id` WHERE date LIKE :search" . self::SORT_CLAUSE . $ls . ";");
		$sth -> execute(array('search' => "%".$search."%"));
		$rows = $sth -> fetchAll(PDO::FETCH_NUM);
		$ret = array();
		foreach($rows as $row) {
			$assoc = self::row_to_assoc($row);
			$ret[] = new device_history_model($assoc);
		}
		return $ret;
	}

	/**
	 * Simple search within comment field
	 * 
	 * @param int $start Row to begin from. Default 0 (begin from start)
	 * @param int $limit Maximum number of rows to retrieve. Default -1 (no limit)
	 */
	public static function search_by_comment($search, $start = 0, $limit = -1) {
		$ls = "";
		$start = (int)$start;
		$limit = (int)$limit;
		if($start >= 0 && $limit > 0) {
			$ls = " LIMIT $start, $limit";
		}
		$sth = database::$dbh -> prepare("SELECT `device_history`.`id`, `device_history`.`date`, `device_history`.`comment`, `device_history`.`is_spare`, `device_history`.`is_damaged`, `device_history`.`has_photos`, `device_history`.`is_bought`, `device_history`.`change`, `device_history`.`technician_id`, `device_history`.`device_id`, `device_history`.`device_status_id`, `device_history`.`person_id`, `technician`.`id`, `technician`.`login`, `technician`.`name`, `technician`.`is_active`, `device`.`id`, `device`.`is_spare`, `device`.`is_damaged`, `device`.`sn`, `device`.`mac_eth0`, `device`.`mac_wlan0`, `device`.`is_bought`, `device`.`person_id`, `device`.`device_status_id`, `device`.`device_type_id`, `device_status`.`id`, `device_status`.`tag`, `person`.`id`, `person`.`code`, `person`.`is_staff`, `person`.`is_active`, `person`.`firstname`, `person`.`surname`, `device_type`.`id`, `device_type`.`name`, `device_type`.`model_no` FROM `device_history` JOIN `technician` ON `device_history`.`technician_id` = `technician`.`id` JOIN `device` ON `device_history`.`device_id` = `device`.`id` JOIN `device_status` ON `device_history`.`device_status_id` = `device_status`.`id` JOIN `person` ON `device_history`.`person_id` = `person`.`id` JOIN `device_type` ON `device`.`device_type_id` = `device_type`.`id` WHERE comment LIKE :search" . self::SORT_CLAUSE . $ls . ";");
		$sth -> execute(array('search' => "%".$search."%"));
		$rows = $sth -> fetchAll(PDO::FETCH_NUM);
		$ret = array();
		foreach($rows as $row) {
			$assoc = self::row_to_assoc($row);
			$ret[] = new device_history_model($assoc);
		}
		return $ret;
	}

	/**
	 * Simple search within change field
	 * 
	 * @param int $start Row to begin from. Default 0 (begin from start)
	 * @param int $limit Maximum number of rows to retrieve. Default -1 (no limit)
	 */
	public static function search_by_change($search, $start = 0, $limit = -1) {
		$ls = "";
		$start = (int)$start;
		$limit = (int)$limit;
		if($start >= 0 && $limit > 0) {
			$ls = " LIMIT $start, $limit";
		}
		$sth = database::$dbh -> prepare("SELECT `device_history`.`id`, `device_history`.`date`, `device_history`.`comment`, `device_history`.`is_spare`, `device_history`.`is_damaged`, `device_history`.`has_photos`, `device_history`.`is_bought`, `device_history`.`change`, `device_history`.`technician_id`, `device_history`.`device_id`, `device_history`.`device_status_id`, `device_history`.`person_id`, `technician`.`id`, `technician`.`login`, `technician`.`name`, `technician`.`is_active`, `device`.`id`, `device`.`is_spare`, `device`.`is_damaged`, `device`.`sn`, `device`.`mac_eth0`, `device`.`mac_wlan0`, `device`.`is_bought`, `device`.`person_id`, `device`.`device_status_id`, `device`.`device_type_id`, `device_status`.`id`, `device_status`.`tag`, `person`.`id`, `person`.`code`, `person`.`is_staff`, `person`.`is_active`, `person`.`firstname`, `person`.`surname`, `device_type`.`id`, `device_type`.`name`, `device_type`.`model_no` FROM `device_history` JOIN `technician` ON `device_history`.`technician_id` = `technician`.`id` JOIN `device` ON `device_history`.`device_id` = `device`.`id` JOIN `device_status` ON `device_history`.`device_status_id` = `device_status`.`id` JOIN `person` ON `device_history`.`person_id` = `person`.`id` JOIN `device_type` ON `device`.`device_type_id` = `device_type`.`id` WHERE change LIKE :search" . self::SORT_CLAUSE . $ls . ";");
		$sth -> execute(array('search' => "%".$search."%"));
		$rows = $sth -> fetchAll(PDO::FETCH_NUM);
		$ret = array();
		foreach($rows as $row) {
			$assoc = self::row_to_assoc($row);
			$ret[] = new device_history_model($assoc);
		}
		return $ret;
	}
}
?>