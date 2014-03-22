/* person */
var person_model = Backbone.Model.extend({
	urlRoot: '/dl/api/person',
	defaults: {
		code: '',
		is_staff: 0,
		is_active: 0,
		firstname: '',
		surname: ''
	}
});
var person_collection = Backbone.Collection.extend({
	url : '/dl/api/person/list_all/',
	model : person_model
});

/* device_status */
var device_status_model = Backbone.Model.extend({
	urlRoot: '/dl/api/device_status',
	defaults: {
		tag: ''
	}
});
var device_status_collection = Backbone.Collection.extend({
	url : '/dl/api/device_status/list_all/',
	model : device_status_model
});

/* device_type */
var device_type_model = Backbone.Model.extend({
	urlRoot: '/dl/api/device_type',
	defaults: {
		name: '',
		model_no: ''
	}
});
var device_type_collection = Backbone.Collection.extend({
	url : '/dl/api/device_type/list_all/',
	model : device_type_model
});

/* device */
var device_model = Backbone.Model.extend({
	urlRoot: '/dl/api/device',
	defaults: {
		is_spare: 0,
		is_damaged: 0,
		sn: '',
		mac_eth0: '',
		mac_wlan0: '',
		is_bought: 0,
		person_id: 0,
		device_status_id: 0,
		device_type_id: 0
	}
});
var device_collection = Backbone.Collection.extend({
	url : '/dl/api/device/list_all/',
	model : device_model
});

/* software_type */
var software_type_model = Backbone.Model.extend({
	urlRoot: '/dl/api/software_type',
	defaults: {
		name: ''
	}
});
var software_type_collection = Backbone.Collection.extend({
	url : '/dl/api/software_type/list_all/',
	model : software_type_model
});

/* software_status */
var software_status_model = Backbone.Model.extend({
	urlRoot: '/dl/api/software_status',
	defaults: {
		tag: ''
	}
});
var software_status_collection = Backbone.Collection.extend({
	url : '/dl/api/software_status/list_all/',
	model : software_status_model
});

/* software */
var software_model = Backbone.Model.extend({
	urlRoot: '/dl/api/software',
	defaults: {
		code: '',
		software_type_id: 0,
		software_status_id: 0,
		person_id: 0,
		is_bought: 0
	}
});
var software_collection = Backbone.Collection.extend({
	url : '/dl/api/software/list_all/',
	model : software_model
});

/* key_type */
var key_type_model = Backbone.Model.extend({
	urlRoot: '/dl/api/key_type',
	defaults: {
		name: ''
	}
});
var key_type_collection = Backbone.Collection.extend({
	url : '/dl/api/key_type/list_all/',
	model : key_type_model
});

/* technician */
var technician_model = Backbone.Model.extend({
	urlRoot: '/dl/api/technician',
	defaults: {
		login: '',
		name: '',
		is_active: 0
	}
});
var technician_collection = Backbone.Collection.extend({
	url : '/dl/api/technician/list_all/',
	model : technician_model
});

/* software_history */
var software_history_model = Backbone.Model.extend({
	urlRoot: '/dl/api/software_history',
	defaults: {
		date: '',
		person_id: 0,
		software_id: 0,
		technician_id: 0,
		software_status_id: 0,
		comment: '',
		change: '',
		is_bought: 0
	}
});
var software_history_collection = Backbone.Collection.extend({
	url : '/dl/api/software_history/list_all/',
	model : software_history_model
});

/* key_status */
var key_status_model = Backbone.Model.extend({
	urlRoot: '/dl/api/key_status',
	defaults: {
		name: ''
	}
});
var key_status_collection = Backbone.Collection.extend({
	url : '/dl/api/key_status/list_all/',
	model : key_status_model
});

/* doorkey */
var doorkey_model = Backbone.Model.extend({
	urlRoot: '/dl/api/doorkey',
	defaults: {
		serial: '',
		person_id: 0,
		is_spare: 0,
		key_type_id: 0,
		key_status_id: 0
	}
});
var doorkey_collection = Backbone.Collection.extend({
	url : '/dl/api/doorkey/list_all/',
	model : doorkey_model
});

/* key_history */
var key_history_model = Backbone.Model.extend({
	urlRoot: '/dl/api/key_history',
	defaults: {
		date: '',
		person_id: 0,
		key_id: 0,
		technician_id: 0,
		key_status_id: 0,
		comment: '',
		change: '',
		is_spare: 0
	}
});
var key_history_collection = Backbone.Collection.extend({
	url : '/dl/api/key_history/list_all/',
	model : key_history_model
});

/* device_history */
var device_history_model = Backbone.Model.extend({
	urlRoot: '/dl/api/device_history',
	defaults: {
		date: '',
		comment: '',
		is_spare: 0,
		is_damaged: 0,
		has_photos: 0,
		is_bought: 0,
		change: '',
		technician_id: 0,
		device_id: 0,
		device_status_id: 0,
		person_id: 0
	}
});
var device_history_collection = Backbone.Collection.extend({
	url : '/dl/api/device_history/list_all/',
	model : device_history_model
});

/* device_photo */
var device_photo_model = Backbone.Model.extend({
	urlRoot: '/dl/api/device_photo',
	defaults: {
		checksum: '',
		filename: '',
		device_history_id: 0
	}
});
var device_photo_collection = Backbone.Collection.extend({
	url : '/dl/api/device_photo/list_all/',
	model : device_photo_model
});

