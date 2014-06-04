#!/usr/bin/env php
<?php
/** 
 * This script automatically sets all 'confiscated' devices to 'awaiting collection' at a given interval.
 */
require_once(dirname(__FILE__)."/../../dl/lib/core.php");
core::loadClass("database");

core::loadClass("device_status_model");
core::loadClass("device_model");
$c = device_status_model::get_by_tag_UNIQUE("Confiscated");
$ac = device_status_model::get_by_tag_UNIQUE("Awaiting Collection");
if(!$technician = technician_model::get_by_technician_login("")) {
	throw new Exception("No system login found");
}

if(!$c || !$ac) {
	throw new Exception("Couldn't find one of the required statuses");
}

$device_list = device_model::list_by_device_status_id($c -> get_id());
$count = 0;
foreach($device_list as $device) {
	$device -> set_device_status_id($ac -> get_id());
	$device -> update();
	$count++;
	/* Copy device details in */
	$device_history = new device_history_model();
	$device_history -> set_device_id($device -> get_id());
	$device_history -> set_device_status_id($device -> get_device_status_id());
	$device_history -> set_has_photos(0);
	$device_history -> set_is_bought($device -> get_is_bought());
	$device_history -> set_is_damaged($device -> get_is_damaged());
	$device_history -> set_is_spare($device -> get_is_spare());
	$device_history -> set_person_id($device -> get_person_id());
	/* Details for history entry */
	$device_history -> set_technician_id($technician -> get_id());
	$device_history -> set_date(date('Y-m-d H:i:s'));
	$device_history -> set_change('status');
	$device_history -> set_comment("Automated confiscation expiry.");
	$device_history -> insert();
}
echo "$count devices updated\n";
?>
