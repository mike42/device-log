<?php
class device_photo_controller {
	private static $mime;
	const IMG_LARGE_SIZE = 1024;
	const IMG_SMALL_SIZE = 100;
	
	public static function init() {
		self::$mime = array('jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif');
		core::loadClass("session");
		core::loadClass("device_photo_model");
	}

	public static function create() {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['device_photo']['create']) || core::$permission[$role]['device_photo']['create'] != true) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Find fields to insert */
		$fields = array('id', 'checksum', 'filename', 'device_history_id');
		$init = array();
		$received = json_decode(file_get_contents('php://input'), true, 2);
		foreach($fields as $field) {
			if(isset($received[$field])) {
				$init["device_photo.$field"] = $received[$field];
			}
		}
			$device_photo = new device_photo_model($init);

		/* Check parent tables */
		if(!device_history_model::get($device_photo -> get_device_history_id())) {
			return array('error' => 'device_photo is invalid because related device_history does not exist', 'code' => '400');
		}

		/* Insert new row */
		try {
			$device_photo -> insert();
			return $device_photo -> to_array_filtered($role);
		} catch(Exception $e) {
			return array('error' => 'Failed to add to database', 'code' => '500');
		}
	}

	public static function read($id = null) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['device_photo']['read']) || count(core::$permission[$role]['device_photo']['read']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load device_photo */
		$device_photo = device_photo_model::get($id);
		if(!$device_photo) {
			return array('error' => 'device_photo not found', 'code' => '404');
		}
		return $device_photo -> to_array_filtered($role);
	}

	public static function update($id = null) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['device_photo']['update']) || count(core::$permission[$role]['device_photo']['update']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load device_photo */
		$device_photo = device_photo_model::get($id);
		if(!$device_photo) {
			return array('error' => 'device_photo not found', 'code' => '404');
		}

		/* Find fields to update */
		$update = false;
		$received = json_decode(file_get_contents('php://input'), true);
		if(isset($received['checksum']) && in_array('checksum', core::$permission[$role]['device_photo']['update'])) {
			$device_photo -> set_checksum($received['checksum']);
		}
		if(isset($received['filename']) && in_array('filename', core::$permission[$role]['device_photo']['update'])) {
			$device_photo -> set_filename($received['filename']);
		}
		if(isset($received['device_history_id']) && in_array('device_history_id', core::$permission[$role]['device_photo']['update'])) {
			$device_photo -> set_device_history_id($received['device_history_id']);
		}

		/* Check parent tables */
		if(!device_history_model::get($device_photo -> get_device_history_id())) {
			return array('error' => 'device_photo is invalid because related device_history does not exist', 'code' => '400');
		}

		/* Update the row */
		try {
			$device_photo -> update();
			return $device_photo -> to_array_filtered($role);
		} catch(Exception $e) {
			return array('error' => 'Failed to update row', 'code' => '500');
		}
	}

	public static function delete($id = null) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['device_photo']['delete']) || core::$permission[$role]['device_photo']['delete'] != true) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}

		/* Load device_photo */
		$device_photo = device_photo_model::get($id);
		if(!$device_photo) {
			return array('error' => 'device_photo not found', 'code' => '404');
		}


		/* Delete it */
		try {
			$device_photo -> delete();
			return array('success' => 'yes');
		} catch(Exception $e) {
			return array('error' => 'Failed to delete', 'code' => '500');
		}
	}

	public static function list_all($page = 1, $itemspp = 20) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['device_photo']['read']) || count(core::$permission[$role]['device_photo']['read']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}
		if((int)$page < 1 || (int)$itemspp < 1) {
			$start = 0;
			$limit = -1;
		} else {
			$start = ($page - 1) * $itemspp;
			$limit = $itemspp;
		}

		/* Retrieve and filter rows */
		try {
			$device_photo_list = device_photo_model::list_all($start, $limit);
			$ret = array();
			foreach($device_photo_list as $device_photo) {
				$ret[] = $device_photo -> to_array_filtered($role);
			}
			return $ret;
		} catch(Exception $e) {
			return array('error' => 'Failed to list', 'code' => '500');
		}
	}
	
	public static function upload($device_id) {
		/* Need permission to upload and to read device info, and to edit device hitory */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['device_photo']['create']) || core::$permission[$role]['device_photo']['create'] != true) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}
		if(!isset(core::$permission[$role]['device']['read']) || count(core::$permission[$role]['device']['read']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}
		if(!isset(core::$permission[$role]['device_history']['create']) || core::$permission[$role]['device_history']['create'] != true) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}
		
		/* Load up related device */
		$device = device_model::get($device_id);
		if(!$device) {
			return array('error' => 'device not found', 'code' => '404');
		}
		if(!$technician = technician_model::get_by_technician_login(session::getUsername())) {
			return array('error' => 'Failed to find out the technician submitting this.', 'code' => '400');
		}
		if(!isset($_FILES['file'])) {
			return array('error' => 'No files specified', 'code' => '400');
		}
		
		/* Get most recent history entry */
		$device -> populate_list_device_history(0, 1);
		if(count($device -> list_device_history) == 0 || $device -> list_device_history[0] -> get_change() != 'photo' || $device -> list_device_history[0] -> get_comment() != '') {
			/* Make a new dummy history entry */
			$device_history = new device_history_model();
			$device_history -> set_change('photo');
			$device_history -> set_date(date('Y-m-d H:i:s'));
			$device_history -> set_technician_id($technician -> get_id());
			$device_history -> set_device_id($device -> get_id());
			$device_history -> set_person_id($device -> get_person_id());
			$device_history -> set_device_status_id($device -> get_device_status_id());
			$device_history -> set_is_damaged($device -> get_is_damaged());
			$device_history -> set_is_spare($device -> get_is_spare());
			$device_history -> set_is_bought($device -> get_is_bought());
			$device_history -> set_comment('');
			$device_history -> set_has_photos(1);
			$device_history -> insert();
		} else {
			$device_history = $device -> list_device_history[0];
		}
		
		$device_history -> populate_list_device_photo();
		
		/* Process all the files */
		try {
			if(!class_exists("Imagick") ){
				throw new Exception("Imagick for PHP is not installed");
			}
			if(!isset($_FILES['file']['error']) || !is_array($_FILES['file']['error'])) {
				throw new Exception("Bad input");
			}
			foreach($_FILES['file']['error'] as $i => $code) {
				switch ($code) {
					case UPLOAD_ERR_OK:
						self::handle_uploaded_file($device_history, $_FILES['file']['name'][$i], $_FILES['file']['tmp_name'][$i], $_FILES['file']['size'][$i]);
						break;
					case UPLOAD_ERR_NO_FILE:
						throw new Exception('No file sent.');
					case UPLOAD_ERR_INI_SIZE:
					case UPLOAD_ERR_FORM_SIZE:
						throw new Exception('File too big.');
					default:
						throw new Exception('Unknown error.');
				}
			}
		} catch(Exception $e) {
			/* Very simple errors for dropzone.js to pick up */
			header('HTTP/1.1 400 Bad Request');
			die($e -> getMessage());
		}
		return array('success' => 'ok');
	}
	
	private static function handle_uploaded_file(device_history_model $device_history, $filename, $tmp_name, $size) {
		if(!file_exists($tmp_name)) {
			throw new Exception("Couldn't find uploaded file");
		}
		
		$checksum = hash_file('sha256', $tmp_name);
		if(!$checksum || strlen($checksum) != '64') {
			throw new Exception("Couldn't generate checksum");
		}
		
		$path = self::path_from_checksum($checksum, $filename);
		if(!file_exists($path['path'])) {
			@mkdir($path['path'], 0777, true);
			if(!file_exists($path['path'])) {
				throw new Exception("Unable to make upload foler. Check permissions.");
			}
		}
		move_uploaded_file($tmp_name, $path['path'] . '/' . $checksum . "." . $path['ext']);
		self::make_thumb($path['path'] . '/' . $checksum . "." . $path['ext'], $path['path'] . '/' . $checksum . "-small." . $path['ext'], self::IMG_SMALL_SIZE);
		self::make_thumb($path['path'] . '/' . $checksum . "." . $path['ext'], $path['path'] . '/' . $checksum . "-large." . $path['ext'], self::IMG_LARGE_SIZE);

		$device_photo = new device_photo_model();
		$device_photo -> set_device_history_id($device_history -> get_id());
		$device_photo -> set_checksum($checksum);
		$device_photo -> set_filename($filename);
		$device_photo -> insert();
	}
	
	private static function path_from_checksum($checksum, $filename) {
		$a = substr($checksum, 0, 1);
		$b = substr($checksum, 1, 1);
		$path = dirname(__FILE__) . "/../../upload/$a/$b/";
		$pathinfo = pathinfo($filename);
		$ext = strtolower($pathinfo['extension']);
		if(!isset(self::$mime[$ext])) {
				throw new Exception("File extension not allowed.");
		}
		return array('path' => $path, 'ext' => $ext);
	}
	
	private static function make_thumb($source, $dest, $size) {
		$thumb = new Imagick($source);
		$thumb->resizeImage($size,$size,Imagick::FILTER_LANCZOS,1);
		$thumb->writeImage($dest);
		$thumb->destroy();
	}
	
	public static function large($id) {
		return self::getPhoto($id, '-large');
	}
	
	public static function small($id) {
		return self::getPhoto($id, '-small');
	}
	
	public static function original($id) {
		return self::getPhoto($id, '');
	}
	
	private static function getPhoto($id, $size) {
		/* Check permission */
		$role = session::getRole();
		if(!isset(core::$permission[$role]['device_photo']['read']) || count(core::$permission[$role]['device_photo']['read']) == 0) {
			return array('error' => 'You do not have permission to do that', 'code' => '403');
		}
		
		/* Load device_photo */
		$device_photo = device_photo_model::get($id);
		if(!$device_photo) {
			return array('error' => 'device_photo not found', 'code' => '404');
		}
		$path = self::path_from_checksum($device_photo -> get_checksum(), $device_photo -> get_filename());
		$fn = $path['path'] . '/' . $device_photo -> get_checksum() . $size . "." . $path['ext'];
		if(!file_exists($fn)) {
			return array('error' => 'Photo appears to have been deleted on server', 'code' => '500');
		}
		$type = self::$mime[strtolower($path['ext'])];
		
		/* Send picture */
		$fp = fopen($fn, 'rb');
		header("Content-Type: $type");
		header("Content-Length: " . filesize($fn));
		fpassthru($fp);
		exit();
	}
}
?>