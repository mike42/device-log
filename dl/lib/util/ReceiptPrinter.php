<?php
// This has been borrowed from http://github.com/mike42/Auth for re-working --Mike
class ReceiptPrinter {
	private static $conf; /* Config */
	
	public static function init() {
		require_once(dirname(__FILE__) . "/../vendor/escpos-php/escpos.php");
		self::$conf = core::getConfig(__CLASS__);
	}

	public static function dhReceipt(device_history_model $device_history) {
		if(!isset(self::$conf['ip']) || self::$conf['ip'] == "0.0.0.0") {
			return false;
		}
		
		if(!$fp = fsockopen(self::$conf['ip'], self::$conf['port'], $errno, $errstr, 2)) {
			throw new Exception("Couldn't connect to receipt printer: $errno $errstr");
		}
		
		/* Header */
		$printer = new escpos($fp);
		$printer -> set_justification(escpos::JUSTIFY_CENTER);
		$printer -> set_emphasis(true);
		$printer -> text(self::$conf['header'] . "\n");
		$printer -> text($device_history -> get_date() . "\n");
		$printer -> set_emphasis(false);
		$printer -> set_justification(escpos::JUSTIFY_LEFT);
		$printer -> feed();

		/* Person details */
		$printer -> set_emphasis(true);
		$printer -> text(($device_history -> person -> get_is_staff() == '1' ? "Staff member" : "Student") . " details:\n");
		$printer -> set_emphasis(false);
		$printer -> text(" - " . $device_history -> person -> get_firstname() . " " . $device_history -> person -> get_surname() . " (" . $device_history -> person -> get_code() . ")\n\n");

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
		$printer -> text(" - Spare: " . ($device_history -> device -> get_is_spare() == '1' ? "N" : "Y") . "   Damaged: " . ($device_history -> device -> get_is_damaged() == '1' ? "N" : "Y") . "   Bought: " . ($device_history -> device -> get_is_bought() == '1' ? "N" : "Y") . "\n");
		$printer -> text(" - Status: " . $device_history -> device_status -> get_tag() . "\n\n");
		
		/* Change details */
		$printer -> set_emphasis(true);
		$printer -> text("Notes:\n");
		$printer -> set_emphasis(false);
		$printer -> text(wordwrap($device_history -> get_comment(), 47, "\n", true) . "\n");
		

		$printer -> text("Technician: " . $device_history -> technician -> get_name() . "\n");
		$printer -> feed();
		
		/* Footer */
		$printer -> set_emphasis(false);

		if(self::$conf['footer'] != "") {
			$printer -> text(self::$conf['footer']  . "\n");
			$printer -> feed();
		}

		/* Barcode */
		if(is_numeric($device_history -> person -> get_code())) {
			$printer -> set_justification(escpos::JUSTIFY_CENTER);
			$printer -> barcode($device_history -> person -> get_code(), escpos::BARCODE_CODE39);
			$printer -> feed();
			$printer -> text($device_history -> person -> get_code());
			$printer -> feed(1);
			$printer -> set_justification(escpos::JUSTIFY_LEFT);
		}
		
		$printer -> cut();
		
		fclose($fp);
	}
}
