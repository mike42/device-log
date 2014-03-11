/* person */
person_model = Backbone.Model.extend({
	urlRoot: '/dl/api/person',
	defaults: {
		code: '',
		is_staff: 0,
		is_active: 0,
		firstname: '',
		surname: ''
	}
});

/* device_status */
device_status_model = Backbone.Model.extend({
	urlRoot: '/dl/api/device_status',
	defaults: {
		tag: ''
	}
});

/* device_type */
device_type_model = Backbone.Model.extend({
	urlRoot: '/dl/api/device_type',
	defaults: {
		name: '',
		model_no: ''
	}
});

/* device */
device_model = Backbone.Model.extend({
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

/* software_type */
software_type_model = Backbone.Model.extend({
	urlRoot: '/dl/api/software_type',
	defaults: {
		name: ''
	}
});

/* software_status */
software_status_model = Backbone.Model.extend({
	urlRoot: '/dl/api/software_status',
	defaults: {
		tag: ''
	}
});

/* software */
software_model = Backbone.Model.extend({
	urlRoot: '/dl/api/software',
	defaults: {
		code: '',
		software_type_id: 0,
		software_status_id: 0,
		person_id: 0,
		is_bought: 0
	}
});

/* key_type */
key_type_model = Backbone.Model.extend({
	urlRoot: '/dl/api/key_type',
	defaults: {
		name: ''
	}
});

/* technician */
technician_model = Backbone.Model.extend({
	urlRoot: '/dl/api/technician',
	defaults: {
		login: '',
		name: ''
	}
});

/* software_history */
software_history_model = Backbone.Model.extend({
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

/* key_status */
key_status_model = Backbone.Model.extend({
	urlRoot: '/dl/api/key_status',
	defaults: {
		name: ''
	}
});

/* doorkey */
doorkey_model = Backbone.Model.extend({
	urlRoot: '/dl/api/doorkey',
	defaults: {
		serial: '',
		person_id: 0,
		is_spare: 0,
		key_type_id: 0,
		key_status_id: 0
	}
});

/* key_history */
key_history_model = Backbone.Model.extend({
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

/* device_history */
device_history_model = Backbone.Model.extend({
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

/* device_photo */
device_photo_model = Backbone.Model.extend({
	urlRoot: '/dl/api/device_photo',
	defaults: {
		checksum: '',
		filename: '',
		device_history_id: 0
	}
});

