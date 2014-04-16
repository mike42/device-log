var DeviceCollection = Backbone.Collection.extend({
	url : '/dl/api/device/list_all/1/100',
	model : device_model
});

var DeviceTypeCollection = Backbone.Collection.extend({
	url : '/dl/api/device_type/list_all/1/100',
	model : device_model
});

var PersonCollection = Backbone.Collection.extend({
	url : '/dl/api/person/list_all/1/100',
	model : person_model
});

var DeviceHistoryCollection = Backbone.Collection.extend({
	url : '/dl/api/device_history/list_all/1/100',
	model : device_history_model
});

var DeviceStatusCollection = Backbone.Collection.extend({
	url : '/dl/api/device_status/list_all/1/100',
	model : device_status_model
});

var PersonDeviceRowView = Backbone.View.extend({
	template : _.template($('#person-device-template-tr').html()),
	tagName : 'tr',

	initialize : function(options) {
		_.bindAll(this, 'render');
		this.model.bind('change', this.render);
	},

	render : function() {
		this.$el.html(this.template(this.model.toJSON()));
		return this;
	}
});

var DeviceRowView = Backbone.View.extend({
	template : _.template($('#device-template-tr').html()),
	tagName : 'tr',

	initialize : function(options) {
		_.bindAll(this, 'render');
		this.model.bind('change', this.render);
	},

	render : function() {
		this.$el.html(this.template(this.model.toJSON()));
		return this;
	}
});

var PersonRowView = Backbone.View.extend({
	template : _.template($('#person-template-tr').html()),
	tagName : 'tr',

	initialize : function(options) {
		_.bindAll(this, 'render');
		this.model.bind('change', this.render);
	},

	render : function() {
		this.$el.html(this.template(this.model.toJSON()));
		return this;
	}
});

var PersonDeviceHistoryDivView = Backbone.View.extend({
	template : _.template($('#device-history-div').html()),
	tagName : 'div',

	initialize : function(options) {
		_.bindAll(this, 'render');
		this.model.bind('change', this.render);
	},

	render : function() {
		this.$el.html(this.template(this.model.toJSON()));
		return this;
	}
});

var PersonDetailView = Backbone.View.extend({
	template : _.template($('#person-template-detail').html()),
	el : 'div#personDetailTop',
	initialize : function(options) {
		_.bindAll(this, 'render');
		this.model.bind('change', this.render);
	},

	render : function() {
		this.$el.html(this.template(this.model.toJSON()));
		return this;
	}
});

var DeviceDetailView = Backbone.View.extend({
	template : _.template($('#device-template-detail').html()),
	el : 'div#deviceDetailTop',
	initialize : function(options) {
		_.bindAll(this, 'render');
		this.model.bind('change', this.render);
	},

	render : function() {
		this.$el.html(this.template(this.model.toJSON()));
		return this;
	}
});

var DeviceTableView = Backbone.View.extend({
	collection : null,
	el : 'tbody#device-tbody',

	initialize : function(options) {
		this.collection = options.collection;
		this.collection.bind('reset', this.render);
		this.collection.bind('add', this.render);
		this.collection.bind('remove', this.render);
	},

	render : function() {
		var element = this.$el;
		element.empty();

		this.collection.forEach(function(item) {
			var itemView = new DeviceRowView({
				model : item
			});
			element.append(itemView.template(itemView.model.toJSON()));
		});
		return this;
	}
});

var PersonDeviceTableView = Backbone.View.extend({
	collection : null,
	el : 'tbody#person-device-tbody',

	initialize : function(options) {
		this.collection = options.collection;
		this.collection.bind('reset', this.render);
		this.collection.bind('add', this.render);
		this.collection.bind('remove', this.render);
	},

	render : function() {
		var element = this.$el;
		element.empty();

		this.collection.forEach(function(item) {
			var itemView = new PersonDeviceRowView({
				model : item
			});
			element.append(itemView.template(itemView.model.toJSON()));
		});
		return this;
	}
});

var PersonDeviceHistoryView = Backbone.View.extend({
	collection : null,
	el : 'div#personDetailHistory',

	initialize : function(options) {
		this.collection = options.collection;
		this.collection.bind('reset', this.render);
		this.collection.bind('add', this.render);
		this.collection.bind('remove', this.render);
	},

	render : function() {
		var element = this.$el;
		element.empty();

		this.collection.forEach(function(item) {
			var itemView = new PersonDeviceHistoryDivView({
				model : item
			});
			element.append(itemView.template(itemView.model.toJSON()));
		});
		return this;
	}
});

var PersonTableView = Backbone.View.extend({
	collection : null,
	el : 'tbody#person-tbody',

	initialize : function(options) {
		this.collection = options.collection;
		this.collection.bind('reset', this.render);
		this.collection.bind('add', this.render);
		this.collection.bind('remove', this.render);
	},

	render : function() {
		var element = this.$el;
		element.empty();

		this.collection.forEach(function(item) {
			var itemView = new PersonRowView({
				model : item
			});
			element.append(itemView.template(itemView.model.toJSON()));
		});
		return this;
	}
});

var DeviceTypeSelectView = Backbone.View.extend({
	collection : null,
	el : '',
	template : _.template($('#device-type-select').html()),

	render : function() {
		this.$el.html(this.template({
			device_types : this.collection.toJSON()
		}));
		return this;
	}
});

var DeviceStatusSelectView = Backbone.View.extend({
	collection : null,
	el : '',
	template : _.template($('#device-status-select').html()),

	render : function() {
		this.$el.html(this.template({
			device_statuses : this.collection.toJSON()
		}));
		return this;
	}
});

function handleFailedRequest(response) {
	if (response.status == '403') {
		sessionExpired();
	} else {
		var responseObj = $.parseJSON(response.responseText);
		warn('Error: ' + responseObj.error);
	}
}

function doLoadPeople() {
	$('#personDetail').hide();
	$('#personList').show();
	var people = new PersonCollection();
	people.fetch({
		success : function(results) {
			var db = new PersonTableView({
				collection : people
			});
			db.render();
			$('#personQuickSearch').focus();
		},
	});
}

function doLoadDevices() {
	$('#deviceDetail').hide();
	$('#deviceList').show();
	var devices = new DeviceCollection();
	devices.fetch({
		success : function(results) {
			var db = new DeviceTableView({
				collection : devices
			});
			db.render();
			$('#deviceQuickSearch').focus();
		},
		error : function(model, response) {
			handleFailedRequest(response);
		}
	});
}

function doLoadSoftware() {
	// Nothing to do yet
}

function doLoadKeys() {
	// Also nothing to do here
}

function warn(message) {
	console.log(message);
}

function doLogout() {
	$("#btnLogout").prop('disabled', true);

	var jqxhr = $.get("api/session/logout/").done(function(data) {
		sessionExpired();
	}).fail(function() {
		warn("Couldn't contact the server");
	});
}

/* Return 'Y' or 'N' for an integer value */
function yn(val) {
	if (val == 1) {
		return 'Y';
	}
	return 'N';
}

/* Return ' checked="yes"' or "" for an integer value */
function checked(val) {
	if (val == 1) {
		return ' checked="yes"';
	}
	return '';
}

// "Add new" dialog
$('#btnAddNew').on('click', function(event) {
	/* Hide callout boxes and clear all input */
	$("#modalAddNew .bs-callout-warning").hide();
	$("#modalAddNew input[type=text]").val('');
	$("#modalAddNew input[type=checkbox]").removeAttr('checked');
	$("#cboAddNew").val('addselect');
	$("#cboAddNew").change()

	/* Fill device type combo */
	renderDeviceTypes('select#addDeviceSelectType', '');

	/* Fill device status combo */
	renderDeviceStatuses('select#addDeviceSelectStatus', '');

	$("#modalAddNew").modal();
	return false;
});

$('#cboAddNew').change(function() {
	$('.show-hide').hide()
	$('#' + this.value).show();
});

$('#submitAddNew').click(function() {
	switch ($('#cboAddNew').val()) {
	case 'addperson':
		var person = new person_model({
			code : $('#addPersonUserCode').val(),
			firstname : $('#addPersonFirstName').val(),
			surname : $('#addPersonSurname').val(),
			is_staff : ($('#addPersonIsStaff').prop('checked') ? '1' : 0),
			is_active : ($('#addPersonIsActive').prop('checked') ? '1' : 0)
		});

		person.save(null, {
			success : function(model, response) {
				var id = model.get('id');
				app_router.navigate('person/' + id, {
					trigger : true
				});
				$("#modalAddNew").modal('hide');
			},
			error : function(model, response) {
				$('#addPersonStatus').html("Could not add person!");
				$('#addPersonStatus').show();
			}
		});
		break;
	case 'adddevice':
		var device = new device_model({
			sn : $('#addDeviceSn').val(),
			mac_eth0 : $('#addDeviceMacEth0').val(),
			mac_wlan0 : $('#addDeviceMacWlan0').val(),
			person_id : $('#addDevicePersonId').val(),
			device_status_id : $('#addDeviceSelectStatus').val(),
			device_type_id : $('#addDeviceSelectType').val(),
			is_bought : ($('#addDeviceIsBought').prop('checked') ? '1' : 0),
			is_spare : ($('#addDeviceIsSpare').prop('checked') ? '1' : 0),
			is_damaged : ($('#addDeviceIsDamaged').prop('checked') ? '1' : 0)
		});

		device.save(null, {
			success : function(model, response) {
				var id = model.get('id');
				app_router.navigate('device/' + id, {
					trigger : true
				});
				$("#modalAddNew").modal('hide');
			},
			error : function(model, response) {
				$('#addDeviceStatus').html("Could not add device!");
				$('#addDeviceStatus').show();
			}
		});
		break;
	case 'addsoftware':
		$('#addSoftwareStatus').html("You cannot add <b>Software</b> yet.");
		$('#addSoftwareStatus').show();
		break;
	case 'addkey':
		$('#addKeyStatus').html("You cannot add a <b>Key</b> yet.");
		$('#addKeyStatus').show();
		break;
	default:
		// Do nothing on the main page
}
});

// Switch on autocomplete
var personSearch = new Bloodhound({
	datumTokenizer : Bloodhound.tokenizers.obj.whitespace('value'),
	queryTokenizer : Bloodhound.tokenizers.whitespace,
	remote : '/dl/api/person/search/1/10?q=%QUERY'
});
personSearch.initialize();

var deviceSearch = new Bloodhound({
	datumTokenizer : Bloodhound.tokenizers.obj.whitespace('value'),
	queryTokenizer : Bloodhound.tokenizers.whitespace,
	remote : '/dl/api/device/search/1/10?q=%QUERY'
});
deviceSearch.initialize();

$('#personQuickSearch').typeahead({
	minLength : 2
}, {
	name : 'person-search',
	displayKey : function(item) {
		return item.code + ' - ' + item.firstname + ' ' + item.surname;
	},
	source : personSearch.ttAdapter()
});

$('#deviceQuickSearch').typeahead(
		{
			minLength : 2
		},
		{
			name : 'device-search',
			displayKey : function(item) {
				return item.sn + ' - ' + item.person.firstname + ' '
						+ item.person.surname;
			},
			source : deviceSearch.ttAdapter()
		});

$('#addDeviceOwner').typeahead({
	minLength : 2
}, {
	name : 'person-search',
	displayKey : function(item) {
		return item.code + ' - ' + item.firstname + ' ' + item.surname;
	},
	source : personSearch.ttAdapter()
});

$('#addDeviceOwner').on('typeahead:selected', function(evt, item) {
	$('#addDeviceOwnerFrmGroup').removeClass('has-error');
	$('#addDeviceOwnerFrmGroup').addClass('has-success');
	$('#addDevicePersonId').val(item.id);
});

$('#addDeviceOwner').on('input', function() {
	// Visual cue that a person has not been selected
	$('#addDeviceOwnerFrmGroup').addClass('has-error');
	$('#addDeviceOwnerFrmGroup').removeClass('has-success');
	$('#addDevicePersonId').val('');
});


$('#personQuickSearch').on('typeahead:selected', function(evt, item) {
	app_router.navigate('person/' + item.id, {
		trigger : true
	});
});

$('#deviceQuickSearch').on('typeahead:selected', function(evt, item) {
	app_router.navigate('device/' + item.id, {
		trigger : true
	});
});

/* Buttons to show dialogs */
function logIncident(device_status_id) {
	$('#modalLogIncident').modal();
	dhChangeSelect('comment');
	renderDeviceStatuses("select#dhSelectStatus", device_status_id);
	return false;
}

function editDevice(device_type_id) {
	$('#modalEditDevice').modal();
	renderDeviceTypes('select#editDeviceSelectType', device_type_id);
	return false;
}

function renderDeviceTypes(dest, device_type_id) {
	var device_types = new device_type_collection();
	device_types.fetch({
		success : function(results) {
			var db = new DeviceTypeSelectView({
				collection : device_types,
				el : dest
			});
			db.render();
			if (device_type_id != '') {
				$(dest + " option[value='" + device_type_id + "']").attr(
						"selected", "selected");
			}
		},
		error : function(model, response) {
			handleFailedRequest(response);
		}
	});
}

function renderDeviceStatuses(dest, device_status_id) {
	var device_statuses = new DeviceStatusCollection();
	device_statuses.fetch({
		success : function(results) {
			var db = new DeviceStatusSelectView({
				collection : device_statuses,
				el : dest
			});
			db.render();
		},
		error : function(model, response) {
			handleFailedRequest(response);
		}
	});
}

function editPerson() {
	$('#modalEditPerson').modal();
	return false;
}

function editPersonSave() {
	var person = new person_model({
		id : $('#editPersonId').val()
	});
	person.save({
		code : $('#editPersonUserCode').val(),
		firstname : $('#editPersonFirstName').val(),
		surname : $('#editPersonSurname').val(),
		is_staff : ($('#editPersonIsStaff').prop('checked') ? '1' : '0'),
		is_active : ($('#editPersonIsActive').prop('checked') ? '1' : '0')
	}, {
		patch : true,
		success : function(model, response) {
			$('#modalEditPerson').on('hidden.bs.modal', function(e) {
				person.fetch({
					success : function(results) {
						showPersonDetail(model);
					},
					error : function(model, response) {
						handleFailedRequest(response);
					}
				});
			})
			$('#modalEditPerson').modal('hide');
		},
		error : function(model, response) {
			$('#editPersonStatus').html("Could not save changes!");
			$('#editPersonStatus').show();
		}
	});
}

function editPersonDelete() {
	var person = new person_model({
		id : $('#editPersonId').val()
	});
	person.destroy({
		success: function(model, response) {
			$('#modalEditPerson').modal('hide');
			app_router.navigate('people', {
				trigger : true
			});
		},
		error : function(model, response) {
			console.log(model);
			$('#editPersonStatus').html(response.responseJSON.error);
			$('#editPersonStatus').show();
		}
	});
}

function editDeviceSave() {
	var device = new device_model({
		id : $('#editDeviceId').val()
	});
	device.save({
		sn : $('#editDeviceSn').val(),
		mac_eth0 : $('#editDeviceMacEth0').val(),
		mac_wlan0 : $('#editDeviceMacWlan0').val(),
		device_type_id : $('#editDeviceSelectType').val()
	}, {
		patch : true,
		success : function(model, response) {
			$('#modalEditDevice').on('hidden.bs.modal', function(e) {
				device.fetch({
					success : function(results) {
						showDeviceDetail(results);
					},
					error : function(model, response) {
						handleFailedRequest(response);
					}
				});
			})
			$('#modalEditDevice').modal('hide');
		},
		error : function(model, response) {
			$('#editDeviceStatus').html(response.responseJSON.error);
			$('#editDeviceStatus').show();
		}
	});
}

function editDeviceDelete() {
	var device = new device_model({
		id : $('#editDeviceId').val()
	});
	device.destroy({
		success: function(model, response) {
			$('#modalEditDevice').modal('hide');
			app_router.navigate('devices', {
				trigger : true
			});
		},
		error : function(model, response) {
			console.log(response);
			$('#editDeviceStatus').html(response.responseJSON.error);
			$('#editDeviceStatus').show();
		}
	});
}

function dhChangeSelect(select) {
	$('#dh-changeselect li').removeClass('active');
	$('#dh-select-' + select).addClass('active');
	$('#dhChange').val(select);
	$('.dh-changebox').hide();
	$('#dh-' + select).show();	
	return false;
}

function logIncidentSave() {
	var change = $('#dhChange').val();
	var device_history = new device_history_model({
		device_id : $('#dhDeviceId').val(),
	});

	switch(change) {
	case 'comment':
	case 'photo':
		var att = {
			change: change,
			comment: $('#dhComment').val()
		};
		break;
	case 'owner':
		var att = {
			change: change,
			comment: $('#dhComment').val(),
			person_id: $('#dhPersonId').val()
		};
		break;
	case 'status':
		var att = {
			change: change,
			comment: $('#dhComment').val(),
			device_status_id: $('#dhSelectStatus').val()
		};
		break;
	case 'damaged':
		var att = {
			change: change,
			comment: $('#dhComment').val(),
			is_damaged : ($('#dhIsDamaged').prop('checked') ? '1' : '0')
		};
		break;
	case 'spare':
		var att = {
			change: change,
			comment: $('#dhComment').val(),
			is_spare: ($('#dhIsSpare').prop('checked') ? '1' : '0')
		};
		break;
	case 'bought':
		var att = {
			change: change,
			comment: $('#dhComment').val(),
			is_bought: ($('#dhIsBought').prop('checked') ? '1' : '0')
		};
		break;
	default:
		$('#modalLogIncident').modal('hide');
		return false;
	}
	device_history.save(att, {
		patch : true,
		success : function(model, response) {
			$('#modalLogIncident').on('hidden.bs.modal', function(e) {
				device = new device_model({id: device_history.get('device_id')});
				device.fetch({
					success : function(results) {
						showDeviceDetail(results);
					},
					error : function(model, response) {
						handleFailedRequest(response);
					}
				});
			})
			$('#modalLogIncident').modal('hide');
		},
		error : function(model, response) {
			console.log(response);
			$('#logIncidentStatus').html(response.responseJSON.error);
			$('#logIncidentStatus').show();
		}
	});
}

/* Navigation */
function tabTo(tab) {
	$('ul.nav a[href="#' + tab + '"]').tab('show');
}

function sessionExpired() {
	window.location.href = 'index.html';
}

var AppRouter = Backbone.Router.extend({
	routes : {
		"person/:id" : "loadPerson",
		"device/:id" : "loadDevice",
		"*actions" : "defaultRoute"
	}
});

function showDeviceDetail(results) {
	tabTo('devices');
	$('#deviceList').hide();
	var itemView = new DeviceDetailView({
		model : results
	});
	itemView.render();
	$('#deviceDetail').show();

	/* Load history */
	var deviceHistoryList = new DeviceHistoryCollection(results
			.get('device_history'));
	var deviceHistoryListView = new PersonDeviceHistoryView({
		el : 'div#deviceDetailHistory',
		collection : deviceHistoryList
	});
	deviceHistoryListView.render();
	
	/* Set type-ahead */
	$('#dhPersonSelect').typeahead({
		minLength : 2
	}, {
		name : 'person-search',
		displayKey : function(item) {
			return item.code + ' - ' + item.firstname + ' ' + item.surname;
		},
		source : personSearch.ttAdapter()
	});

	$('#dhPersonSelect').on('typeahead:selected', function(evt, item) {
		$('#dh-owner').removeClass('has-error');
		$('#dh-owner').addClass('has-success');
		$('#dhPersonId').val(item.id);
	});

	$('#dhPersonSelect').on('input', function() {
		// Visual cue that a person has not been selected
		$('#dh-owner').addClass('has-error');
		$('#dh-owner').removeClass('has-success');
		$('#dhPersonId').val('');
	});

	$('div#incident-photo-dropzone').dropzone({
		url: "/dl/api/device_photo/upload/" + results.get('id'),
		uploadMultiple: true,
		acceptedFiles: "image/*",
	});
}

function showPersonDetail(results) {
	tabTo('people');
	$('#personList').hide();
	var itemView = new PersonDetailView({
		model : results
	});
	itemView.render();
	$('#personDetail').show();

	/* Load device list */
	var devices = new DeviceCollection(results.get('device'));
	var devicesView = new PersonDeviceTableView({
		collection : devices
	});
	devicesView.render();

	/* Load history */
	var deviceHistoryList = new DeviceHistoryCollection(results
			.get('device_history'));
	var deviceHistoryListView = new PersonDeviceHistoryView({
		el : 'div#personDetailHistory',
		collection : deviceHistoryList
	});
	deviceHistoryListView.render();

	$('#btnPersonDeviceList').click(function() {
		$("#modalPersonDeviceList").modal();
		return false;
	});

	$('#person-device-tbody a').click(function() {
		$("#modalPersonDeviceList").modal('hide');
	});
}

var app_router = new AppRouter;
app_router.on('route:loadPerson', function(id) {
	var person = new person_model({
		id : id
	});
	person.fetch({
		success : function(results) {
			showPersonDetail(results);
		},
		error : function(model, response) {
			handleFailedRequest(response);
		}
	});
});

app_router.on('route:loadDevice', function(id) {
	var device = new device_model({
		id : id
	});
	device.fetch({
		success : function(results) {
			showDeviceDetail(results);
		},
		error : function(model, response) {
			handleFailedRequest(response);
		}
	});
});

app_router.on('route:defaultRoute', function(actions) {
	switch (actions) {
	case 'logout':
		doLogout();
		break;
	case 'people':
		tabTo('people');
		$('#personQuickSearch').val('');
		doLoadPeople();
		break;
	case 'devices':
		$('#deviceQuickSearch').val('');
		tabTo('devices');
		doLoadDevices();
		break;
	case 'software':
		tabTo('software');
		doLoadSoftware();
		break;
	case 'keys':
		tabTo('keys');
		doLoadKeys();
		break;
	default:

	}
});

$('.nav-tabs a').click(function(e) {
	window.location.hash = this.hash;
});

Backbone.history.start();