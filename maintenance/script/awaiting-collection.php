#!/usr/bin/env php
<?php
/**
 * Return a list of devices which are "Awaiting Collection", for reporting and notification
 */
require_once(dirname(__FILE__)."/../../dl/lib/core.php");
core::loadClass("database");
core::loadClass("device_model");

$status = device_status_model::get_by_tag_UNIQUE("Awaiting Collection");
$devices = device_model::list_by_device_status_id($status -> get_id());

$ret = array();
foreach($devices as $device) {
	$ret[] = $device -> to_array_filtered('user');
}
echo json_encode($ret) . "\n";
