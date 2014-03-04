<?php
class device_photo_model {
	/**
	 * @var int id ID of the photo
	 */
	private $id;

	/**
	 * @var string checksum ASCII rendering of SHA-256 hash of the file, for storage
	 */
	private $checksum;

	/**
	 * @var string filename Filename at upload, used for labelling only
	 */
	private $filename;

	/**
	 * @var int device_history_id Associated log entry
	 */
	private $device_history_id;

	private $model_variables_changed; // Only variables which have been changed
	private $model_variables_set; // All variables which have been set (initially or with a setter)

	/* Parent tables */
	public $device_history;

	/**
	 * Initialise and load related tables
	 */
	public static function init() {
		core::loadClass("database");
		core::loadClass("device_history_model");
	}

	/**
	 * Construct new device_photo from field list
	 * 
	 * @return array
	 */
	public function __construct(array $fields = array()) {
/* Initialise everything as blank to avoid tripping up the permissions fitlers */
		$this -> id = '';
		$this -> checksum = '';
		$this -> filename = '';
		$this -> device_history_id = '';

		if(isset($fields['device_photo.id'])) {
			$this -> set_id($fields['device_photo.id']);
		}
		if(isset($fields['device_photo.checksum'])) {
			$this -> set_checksum($fields['device_photo.checksum']);
		}
		if(isset($fields['device_photo.filename'])) {
			$this -> set_filename($fields['device_photo.filename']);
		}
		if(isset($fields['device_photo.device_history_id'])) {
			$this -> set_device_history_id($fields['device_photo.device_history_id']);
		}

		$this -> model_variables_changed = array();
		$this -> device_history = new device_history_model($fields);
	}

	/**
	 * Convert device_photo to shallow associative array
	 * 
	 * @return array
	 */
	private function to_array() {
		$values = array(
			'id' => $this -> id,
			'checksum' => $this -> checksum,
			'filename' => $this -> filename,
			'device_history_id' => $this -> device_history_id);
		return $values;
	}

	/**
	 * Convert device_photo to associative array, including only visible fields,
	 * parent tables, and loaded child tables
	 * 
	 * @param string $role The user role to use
	 */
	public function to_array_filtered($role = "anon") {
		if(core::$permission[$role]['device_photo']['read'] === false) {
			return false;
		}
		$values = array();
		$everything = $this -> to_array();
		foreach(core::$permission[$role]['device_photo']['read'] as $field) {
			if(!isset($everything[$field])) {
				throw new Exception("Check permissions: '$field' is not a real field in device_photo");
			}
			$values[$field] = $everything[$field];
		}
		$values['device_history'] = $this -> device_history -> to_array_filtered($role);
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
			"device_photo.id" => $row[0],
			"device_photo.checksum" => $row[1],
			"device_photo.filename" => $row[2],
			"device_photo.device_history_id" => $row[3],
			"device_history.id" => $row[4],
			"device_history.date" => $row[5],
			"device_history.comment" => $row[6],
			"device_history.is_spare" => $row[7],
			"device_history.is_damaged" => $row[8],
			"device_history.has_photos" => $row[9],
			"device_history.is_bought" => $row[10],
			"device_history.change" => $row[11],
			"device_history.technician_id" => $row[12],
			"device_history.device_id" => $row[13],
			"device_history.device_status_id" => $row[14],
			"device_history.person_id" => $row[15],
			"technician.id" => $row[16],
			"technician.login" => $row[17],
			"technician.name" => $row[18],
			"device.id" => $row[19],
			"device.is_spare" => $row[20],
			"device.is_damaged" => $row[21],
			"device.sn" => $row[22],
			"device.mac_eth0" => $row[23],
			"device.mac_wlan0" => $row[24],
			"device.is_bought" => $row[25],
			"device.person_id" => $row[26],
			"device.device_status_id" => $row[27],
			"device.device_type_id" => $row[28],
			"device_status.id" => $row[29],
			"device_status.tag" => $row[30],
			"person.id" => $row[31],
			"person.code" => $row[32],
			"person.is_staff" => $row[33],
			"person.is_active" => $row[34],
			"person.firstname" => $row[35],
			"person.surname" => $row[36],
			"device_type.id" => $row[37],
			"device_type.name" => $row[38],
			"device_type.model_no" => $row[39]);
		return $values;
	}

	/**
	 * Get id
	 * 
	 * @return int
	 */
	public function get_id() {
		if(!isset($this -> model_variables_set['id'])) {
			throw new Exception("device_photo.id has not been initialised.");
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
			throw new Exception("device_photo.id must be numeric");
		}
		$this -> id = $id;
		$this -> model_variables_changed['id'] = true;
		$this -> model_variables_set['id'] = true;
	}

	/**
	 * Get checksum
	 * 
	 * @return string
	 */
	public function get_checksum() {
		if(!isset($this -> model_variables_set['checksum'])) {
			throw new Exception("device_photo.checksum has not been initialised.");
		}
		return $this -> checksum;
	}

	/**
	 * Set checksum
	 * 
	 * @param string $checksum
	 */
	public function set_checksum($checksum) {
		if(strlen($checksum) != 64) {
			throw new Exception("device_photo.checksum must consist of 64 characters");
		}
		$this -> checksum = $checksum;
		$this -> model_variables_changed['checksum'] = true;
		$this -> model_variables_set['checksum'] = true;
	}

	/**
	 * Get filename
	 * 
	 * @return string
	 */
	public function get_filename() {
		if(!isset($this -> model_variables_set['filename'])) {
			throw new Exception("device_photo.filename has not been initialised.");
		}
		return $this -> filename;
	}

	/**
	 * Set filename
	 * 
	 * @param string $filename
	 */
	public function set_filename($filename) {
		// TODO: Add TEXT validation to device_photo.filename
		$this -> filename = $filename;
		$this -> model_variables_changed['filename'] = true;
		$this -> model_variables_set['filename'] = true;
	}

	/**
	 * Get device_history_id
	 * 
	 * @return int
	 */
	public function get_device_history_id() {
		if(!isset($this -> model_variables_set['device_history_id'])) {
			throw new Exception("device_photo.device_history_id has not been initialised.");
		}
		return $this -> device_history_id;
	}

	/**
	 * Set device_history_id
	 * 
	 * @param int $device_history_id
	 */
	public function set_device_history_id($device_history_id) {
		if(!is_numeric($device_history_id)) {
			throw new Exception("device_photo.device_history_id must be numeric");
		}
		$this -> device_history_id = $device_history_id;
		$this -> model_variables_changed['device_history_id'] = true;
		$this -> model_variables_set['device_history_id'] = true;
	}

	/**
	 * Update device_photo
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
		$sth = database::$dbh -> prepare("UPDATE device_photo SET $fields WHERE id = :id");
		$sth -> execute($data);
	}

	/**
	 * Add new device_photo
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
		$sth = database::$dbh -> prepare("INSERT INTO device_photo ($fields) VALUES ($vals);");
		$sth -> execute($data);
		$this -> set_id(database::$dbh->lastInsertId());
	}

	/**
	 * Delete device_photo
	 */
	public function delete() {
		$sth = database::$dbh -> prepare("DELETE FROM device_photo WHERE id = :id");
		$data['id'] = $this -> get_id();
		$sth -> execute($data);
	}

	/**
	 * Retrieve by primary key
	 */
	public static function get($id) {
		$sth = database::$dbh -> prepare("SELECT device_photo.id, device_photo.checksum, device_photo.filename, device_photo.device_history_id, device_history.id, device_history.date, device_history.comment, device_history.is_spare, device_history.is_damaged, device_history.has_photos, device_history.is_bought, device_history.change, device_history.technician_id, device_history.device_id, device_history.device_status_id, device_history.person_id, technician.id, technician.login, technician.name, device.id, device.is_spare, device.is_damaged, device.sn, device.mac_eth0, device.mac_wlan0, device.is_bought, device.person_id, device.device_status_id, device.device_type_id, device_status.id, device_status.tag, person.id, person.code, person.is_staff, person.is_active, person.firstname, person.surname, device_type.id, device_type.name, device_type.model_no FROM device_photo JOIN device_history ON device_photo.device_history_id = device_history.id JOIN technician ON device_history.technician_id = technician.id JOIN device ON device_history.device_id = device.id JOIN device_status ON device_history.device_status_id = device_status.id JOIN person ON device_history.person_id = person.id JOIN device_type ON device.device_type_id = device_type.id WHERE device_photo.id = :id;");
		$sth -> execute(array('id' => $id));
		$row = $sth -> fetch(PDO::FETCH_NUM);
		if($row === false){
			return false;
		}
		$assoc = self::row_to_assoc($row);
		return new device_photo_model($assoc);
	}

	/**
	 * List rows by device_history_id index
	 * 
	 * @param int $start Row to begin from. Default 0 (begin from start)
	 * @param int $limit Maximum number of rows to retrieve. Default -1 (no limit)
	 */
	public static function list_by_device_history_id($device_history_id, $start = 0, $limit = -1) {
		$ls = "";
		$start = (int)$start;
		$limit = (int)$limit;
		if($start > 0 && $limit > 0) {
			$ls = " LIMIT $start, " . ($start + $limit);
		}
		$sth = database::$dbh -> prepare("SELECT device_photo.id, device_photo.checksum, device_photo.filename, device_photo.device_history_id, device_history.id, device_history.date, device_history.comment, device_history.is_spare, device_history.is_damaged, device_history.has_photos, device_history.is_bought, device_history.change, device_history.technician_id, device_history.device_id, device_history.device_status_id, device_history.person_id, technician.id, technician.login, technician.name, device.id, device.is_spare, device.is_damaged, device.sn, device.mac_eth0, device.mac_wlan0, device.is_bought, device.person_id, device.device_status_id, device.device_type_id, device_status.id, device_status.tag, person.id, person.code, person.is_staff, person.is_active, person.firstname, person.surname, device_type.id, device_type.name, device_type.model_no FROM device_photo JOIN device_history ON device_photo.device_history_id = device_history.id JOIN technician ON device_history.technician_id = technician.id JOIN device ON device_history.device_id = device.id JOIN device_status ON device_history.device_status_id = device_status.id JOIN person ON device_history.person_id = person.id JOIN device_type ON device.device_type_id = device_type.id WHERE device_photo.device_history_id = :device_history_id" . $ls . ";");
		$sth -> execute(array('device_history_id' => $device_history_id));
		$rows = $sth -> fetchAll(PDO::FETCH_NUM);
		$ret = array();
		foreach($rows as $row) {
			$assoc = self::row_to_assoc($row);
			$ret[] = new device_photo_model($assoc);
		}
		return $ret;
	}
}
?>