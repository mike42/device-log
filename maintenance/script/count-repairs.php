<?php
/**
 * Count the number of devices which have been handed in for repair this week
 */
require_once(dirname(__FILE__)."/../../dl/lib/core.php");
core::loadClass("database");

/* Prepare query */
$query = "SELECT count(device_id) FROM device_history JOIN device_status ON device_status.id = device_history.device_status_id WHERE device_history.change = 'status' AND device_status.tag = 'In for Repair' AND device_history.date > :date";
$sth = database::$dbh -> prepare($query);

/* Find reference point and run query */
$data['date'] = date("Y-m-d H:i:s", strtotime('Monday this week'));
$sth -> execute($data);

/* Fetch row and print output */
$rows = $sth -> fetchAll(PDO::FETCH_NUM);
echo $rows[0][0] . "\n";
