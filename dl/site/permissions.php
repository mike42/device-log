<?php
/* Permissions for database fields */
$permission['user'] = array(
	'person' => array(
		'create' => true,
		'read' => array(
			'id',
			'code',
			'is_staff',
			'is_active',
			'firstname',
			'surname'),
		'update' => array(
			'id',
			'code',
			'is_staff',
			'is_active',
			'firstname',
			'surname'),
		'delete' => true),
	'device_status' => array(
		'create' => true,
		'read' => array(
			'id',
			'tag'),
		'update' => array(
			'id',
			'tag'),
		'delete' => true),
	'device_type' => array(
		'create' => true,
		'read' => array(
			'id',
			'name',
			'model_no'),
		'update' => array(
			'id',
			'name',
			'model_no'),
		'delete' => true),
	'device' => array(
		'create' => true,
		'read' => array(
			'id',
			'is_spare',
			'is_damaged',
			'sn',
			'mac_eth0',
			'mac_wlan0',
			'is_bought',
			'person_id',
			'device_status_id',
			'device_type_id'),
		'update' => array(
			'id',
			'is_spare',
			'is_damaged',
			'sn',
			'mac_eth0',
			'mac_wlan0',
			'is_bought',
			'person_id',
			'device_status_id',
			'device_type_id'),
		'delete' => true),
	'software_type' => array(
		'create' => true,
		'read' => array(
			'id',
			'name'),
		'update' => array(
			'id',
			'name'),
		'delete' => true),
	'software_status' => array(
		'create' => true,
		'read' => array(
			'id',
			'tag'),
		'update' => array(
			'id',
			'tag'),
		'delete' => true),
	'software' => array(
		'create' => true,
		'read' => array(
			'id',
			'code',
			'software_type_id',
			'software_status_id',
			'person_id',
			'is_bought'),
		'update' => array(
			'id',
			'code',
			'software_type_id',
			'software_status_id',
			'person_id',
			'is_bought'),
		'delete' => true),
	'key_type' => array(
		'create' => true,
		'read' => array(
			'id',
			'name'),
		'update' => array(
			'id',
			'name'),
		'delete' => true),
	'technician' => array(
		'create' => true,
		'read' => array(
			'id',
			'login',
			'name'),
		'update' => array(
			'id',
			'login',
			'name'),
		'delete' => true),
	'software_history' => array(
		'create' => true,
		'read' => array(
			'id',
			'date',
			'person_id',
			'software_id',
			'technician_id',
			'software_status_id',
			'comment',
			'change',
			'is_bought'),
		'update' => array(
			'id',
			'date',
			'person_id',
			'software_id',
			'technician_id',
			'software_status_id',
			'comment',
			'change',
			'is_bought'),
		'delete' => true),
	'key_status' => array(
		'create' => true,
		'read' => array(
			'id',
			'name'),
		'update' => array(
			'id',
			'name'),
		'delete' => true),
	'doorkey' => array(
		'create' => true,
		'read' => array(
			'id',
			'serial',
			'person_id',
			'is_spare',
			'key_type_id',
			'key_status_id'),
		'update' => array(
			'id',
			'serial',
			'person_id',
			'is_spare',
			'key_type_id',
			'key_status_id'),
		'delete' => true),
	'key_history' => array(
		'create' => true,
		'read' => array(
			'id',
			'date',
			'person_id',
			'key_id',
			'technician_id',
			'key_status_id',
			'comment',
			'change',
			'is_spare'),
		'update' => array(
			'id',
			'date',
			'person_id',
			'key_id',
			'technician_id',
			'key_status_id',
			'comment',
			'change',
			'is_spare'),
		'delete' => true),
	'device_history' => array(
		'create' => true,
		'read' => array(
			'id',
			'date',
			'comment',
			'is_spare',
			'is_damaged',
			'has_photos',
			'is_bought',
			'change',
			'technician_id',
			'device_id',
			'device_status_id',
			'person_id'),
		'update' => array(
			'id',
			'date',
			'comment',
			'is_spare',
			'is_damaged',
			'has_photos',
			'is_bought',
			'change',
			'technician_id',
			'device_id',
			'device_status_id',
			'person_id'),
		'delete' => true),
	'device_photo' => array(
		'create' => true,
		'read' => array(
			'id',
			'checksum',
			'filename',
			'device_history_id'),
		'update' => array(
			'id',
			'checksum',
			'filename',
			'device_history_id'),
		'delete' => true));
$permission['admin'] = array(
	'person' => array(
		'create' => true,
		'read' => array(
			'id',
			'code',
			'is_staff',
			'is_active',
			'firstname',
			'surname'),
		'update' => array(
			'id',
			'code',
			'is_staff',
			'is_active',
			'firstname',
			'surname'),
		'delete' => true),
	'device_status' => array(
		'create' => true,
		'read' => array(
			'id',
			'tag'),
		'update' => array(
			'id',
			'tag'),
		'delete' => true),
	'device_type' => array(
		'create' => true,
		'read' => array(
			'id',
			'name',
			'model_no'),
		'update' => array(
			'id',
			'name',
			'model_no'),
		'delete' => true),
	'device' => array(
		'create' => true,
		'read' => array(
			'id',
			'is_spare',
			'is_damaged',
			'sn',
			'mac_eth0',
			'mac_wlan0',
			'is_bought',
			'person_id',
			'device_status_id',
			'device_type_id'),
		'update' => array(
			'id',
			'is_spare',
			'is_damaged',
			'sn',
			'mac_eth0',
			'mac_wlan0',
			'is_bought',
			'person_id',
			'device_status_id',
			'device_type_id'),
		'delete' => true),
	'software_type' => array(
		'create' => true,
		'read' => array(
			'id',
			'name'),
		'update' => array(
			'id',
			'name'),
		'delete' => true),
	'software_status' => array(
		'create' => true,
		'read' => array(
			'id',
			'tag'),
		'update' => array(
			'id',
			'tag'),
		'delete' => true),
	'software' => array(
		'create' => true,
		'read' => array(
			'id',
			'code',
			'software_type_id',
			'software_status_id',
			'person_id',
			'is_bought'),
		'update' => array(
			'id',
			'code',
			'software_type_id',
			'software_status_id',
			'person_id',
			'is_bought'),
		'delete' => true),
	'key_type' => array(
		'create' => true,
		'read' => array(
			'id',
			'name'),
		'update' => array(
			'id',
			'name'),
		'delete' => true),
	'technician' => array(
		'create' => true,
		'read' => array(
			'id',
			'login',
			'name'),
		'update' => array(
			'id',
			'login',
			'name'),
		'delete' => true),
	'software_history' => array(
		'create' => true,
		'read' => array(
			'id',
			'date',
			'person_id',
			'software_id',
			'technician_id',
			'software_status_id',
			'comment',
			'change',
			'is_bought'),
		'update' => array(
			'id',
			'date',
			'person_id',
			'software_id',
			'technician_id',
			'software_status_id',
			'comment',
			'change',
			'is_bought'),
		'delete' => true),
	'key_status' => array(
		'create' => true,
		'read' => array(
			'id',
			'name'),
		'update' => array(
			'id',
			'name'),
		'delete' => true),
	'doorkey' => array(
		'create' => true,
		'read' => array(
			'id',
			'serial',
			'person_id',
			'is_spare',
			'key_type_id',
			'key_status_id'),
		'update' => array(
			'id',
			'serial',
			'person_id',
			'is_spare',
			'key_type_id',
			'key_status_id'),
		'delete' => true),
	'key_history' => array(
		'create' => true,
		'read' => array(
			'id',
			'date',
			'person_id',
			'key_id',
			'technician_id',
			'key_status_id',
			'comment',
			'change',
			'is_spare'),
		'update' => array(
			'id',
			'date',
			'person_id',
			'key_id',
			'technician_id',
			'key_status_id',
			'comment',
			'change',
			'is_spare'),
		'delete' => true),
	'device_history' => array(
		'create' => true,
		'read' => array(
			'id',
			'date',
			'comment',
			'is_spare',
			'is_damaged',
			'has_photos',
			'is_bought',
			'change',
			'technician_id',
			'device_id',
			'device_status_id',
			'person_id'),
		'update' => array(
			'id',
			'date',
			'comment',
			'is_spare',
			'is_damaged',
			'has_photos',
			'is_bought',
			'change',
			'technician_id',
			'device_id',
			'device_status_id',
			'person_id'),
		'delete' => true),
	'device_photo' => array(
		'create' => true,
		'read' => array(
			'id',
			'checksum',
			'filename',
			'device_history_id'),
		'update' => array(
			'id',
			'checksum',
			'filename',
			'device_history_id'),
		'delete' => true));
