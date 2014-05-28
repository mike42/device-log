<?php
// This has been borrowed from http://github.com/mike42/Auth for re-working --Mike
class ReceiptPrinter {
	private static $conf; /* Config */
	
	public static function init() {
		require_once(dirname(__FILE__) . "/../vendor/escpos-php/escpos.php");
		self::$conf = core::getConfig(__CLASS__);
	}

	public static function dhReceipt(device_history_model $device_history) {
		if(!$fp = self::openPrinter()) {
			return;
		}
		$printer = new escpos($fp);
		
		/* Receipt */
		self::header($printer, $device_history -> get_date());
		self::person_details($printer, $device_history -> person);
		
		/* Device details */
		$printer -> set_emphasis(true);
		$printer -> text("Device details:\n");
		$printer -> set_emphasis(false);
		$model = trim($device_history -> device -> device_type -> get_name() . " " . $device_history -> device -> device_type -> get_model_no());
		if($model == "") {
			$model = "Not recorded";
		}
		$printer -> text(" - Model: " . $model . "\n");
		$sn = trim($device_history -> device -> get_sn());
		if($sn == "") {
			$sn = "Not recorded";
		}
		$printer -> text(" - Serial #: " . $sn . "\n");
		if($device_history -> device -> get_mac_eth0() != "") {
			$printer -> text(" - Wired MAC: " . $device_history -> device -> get_mac_eth0() . "\n");
		}
		if($device_history -> device -> get_mac_wlan0() != "") {
			$printer -> text(" - Wireless MAC: " . $device_history -> device -> get_mac_wlan0() . "\n");
		}
		$printer -> text(" - Spare: " . ($device_history -> device -> get_is_spare() == '1' ? "Y" : "N") . "   Damaged: " . ($device_history -> device -> get_is_damaged() == '1' ? "Y" : "N") . "   Bought: " . ($device_history -> device -> get_is_bought() == '1' ? "Y" : "N") . "\n");
		$printer -> text(" - Status: " . $device_history -> device_status -> get_tag() . "\n\n");
		
		/* Device history details */
		$printer -> set_emphasis(true);
		$printer -> text("Notes:\n");
		$printer -> set_emphasis(false);
		$printer -> text(wordwrap($device_history -> get_comment(), 47, "\n", true) . "\n");
		

		$printer -> text("Technician: " . $device_history -> technician -> get_name() . "\n");
		$printer -> feed();
		
		self::footer($printer, $device_history -> person);
		fclose($fp);
	}
	
	public static function khReceipt(key_history_model $key_history) {
		if(!$fp = self::openPrinter()) {
			return;
		}
		$printer = new escpos($fp);
		
		self::header($printer, $key_history -> get_date());
		self::person_details($printer, $key_history -> person);
		
		/* Device details */
		$printer -> set_emphasis(true);
		$printer -> text("Key details:\n");
		$printer -> set_emphasis(false);
		$model = trim($key_history -> doorkey -> key_type -> get_name());
		if($model == "") {
			$model = "Not recorded";
		}
		$printer -> text(" - Model: " . $model . "\n");
		$sn = trim($key_history -> doorkey -> get_serial());
		if($sn == "") {
			$sn = "Not recorded";
		}
		$printer -> text(" - Serial #: " . $sn . "\n");
		$printer -> text(" - Spare: " . ($key_history -> doorkey -> get_is_spare() == '1' ? "Y" : "N") . "\n");
		$printer -> text(" - Status: " . $key_history -> doorkey -> key_status -> get_name() . "\n\n");
		
		/* Device history details */
		$printer -> set_emphasis(true);
		$printer -> text("Notes:\n");
		$printer -> set_emphasis(false);
		$printer -> text(wordwrap($key_history -> get_comment(), 47, "\n", true) . "\n");
		
		$printer -> text("Technician: " . $key_history -> technician -> get_name() . "\n");
		$printer -> feed();	
		
		self::footer($printer, $key_history -> person);
		fclose($fp);
	}
	
	
	/**
	 * Open printer and return a file handle to it
	 * 
	 * @throws Exception
	 * @return boolean|resource
	 */
	private static function openPrinter() {
		if(!isset(self::$conf['ip']) || self::$conf['ip'] == "0.0.0.0") {
			// No printer set
			return false;
		}
		
		if(!$fp = fsockopen(self::$conf['ip'], self::$conf['port'], $errno, $errstr, 2)) {
			throw new Exception("Couldn't connect to receipt printer: $errno $errstr");
		}
		
		return $fp;
	}
	
	/**
	 * Print person details
	 * 
	 * @param escpos $printer
	 * @param person_model $person
	 */
	private static function person_details(escpos $printer, person_model $person) {
		/* Person details */
		$printer -> set_emphasis(true);
		$printer -> text(($person -> get_is_staff() == '1' ? "Staff member" : "Student") . " details:\n");
		$printer -> set_emphasis(false);
		$printer -> text(" - " . $person -> get_firstname() . " " . $person -> get_surname() . " (" . $person -> get_code() . ")\n\n");
	}
	
	/**
	 * Print header
	 * 
	 * @param escpos $printer
	 * @param unknown_type $date
	 */
	private static function header(escpos $printer, $date) {
		/* Header */
		$printer -> set_justification(escpos::JUSTIFY_CENTER);
		$printer -> set_emphasis(true);
		$printer -> text(self::$conf['header'] . "\n");
		$printer -> text($date . "\n");
		$printer -> set_emphasis(false);
		$printer -> set_justification(escpos::JUSTIFY_LEFT);
		$printer -> feed();
	}
	
	/**
	 * Print footer
	 * 
	 * @param escpos $printer
	 * @param person_model $person
	 */
	private static function footer(escpos $printer, person_model $person) {
		/* Footer */
		$printer -> set_emphasis(false);
		
		if(self::$conf['footer'] != "") {
			$printer -> text(self::$conf['footer']  . "\n");
			$printer -> feed();
		}
		
		/* Barcode */
		if(is_numeric($person -> get_code())) {
			$printer -> set_justification(escpos::JUSTIFY_CENTER);
			$printer -> barcode($person -> get_code(), escpos::BARCODE_CODE39);
			$printer -> feed();
			$printer -> text($person -> get_code());
			$printer -> feed(1);
			$printer -> set_justification(escpos::JUSTIFY_LEFT);
		}
		
		$printer -> cut();
	}
}
