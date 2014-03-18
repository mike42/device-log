var DeviceCollection = Backbone.Collection.extend({
	url : '/dl/api/device/list_all/1/100',
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
	el: 'div#personDetailTop',
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
	el: 'div#deviceDetailTop',
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

function handleFailedRequest(response) {
	if(response.status == '403') {
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

function yn(val) {
	if(val == 1) {
		return 'Y';
	}
	return 'N';
}


// "Add new" dialog
$('#btnAddNew').on('click', function(event) {
	/* Hide callout boxes and clear all input */
	$("#modalAddNew .bs-callout-warning").hide();
	$("#modalAddNew input[type=text]").val('');
	$("#modalAddNew input[type=checkbox]").removeAttr('checked');
	$("#cboAddNew").val('addselect');
	$("#cboAddNew").change()
	
	$("#modalAddNew").modal();
	return false;
});

$('#cboAddNew').change(function () {
	$('.show-hide').hide()
	$('#' + this.value).show();
});

$('#submitAddNew').click(function () {
	switch($('#cboAddNew').val()) {
	case 'addperson':
		var person = new person_model({
			code: $('#addPersonUserCode').val(),
			firstname: $('#addPersonFirstName').val(),
			surname: $('#addPersonSurname').val(),
			is_active: $('#addPersonIsStaff').attr('checked') ? '1' : 0,
			is_staff: $('#addPersonIsActive').attr('checked') ? '1' : 0
		});
		
		person.save(null, {
			success: function(model, response) {
				var id = model.get('id');
				app_router.navigate('person/' + id, {trigger: true});
				$("#modalAddNew").modal('hide');
			},
			error: function(model, response) {
				$('#addPersonStatus').html("Could not add person!");
				$('#addPersonStatus').show();
			}
		});
		break;
	case 'adddevice':
		$('#addDeviceStatus').html("You cannot add a <b>Device</b> yet.");
		$('#addDeviceStatus').show();
		break;
	case 'addsoftware':
		$('#addSoftwareStatus').html("You cannot add <b>Software</b> yet.");
		$('#addSoftwareStatus').show();
		break;on
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
	  datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
	  queryTokenizer: Bloodhound.tokenizers.whitespace,
	  remote: '/dl/api/person/search/1/10?q=%QUERY'
});
personSearch.initialize();

var deviceSearch = new Bloodhound({
	  datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
	  queryTokenizer: Bloodhound.tokenizers.whitespace,
	  remote: '/dl/api/device/search/1/10?q=%QUERY'
});
deviceSearch.initialize();

$('#personQuickSearch').typeahead({
	minLength: 2
},
{
	name: 'person-search',
	displayKey: function(item) { return item.code + ' - ' + item.firstname + ' ' + item.surname; },
	source: personSearch.ttAdapter()
});

$('#deviceQuickSearch').typeahead({
	minLength: 2
},
{
	name: 'device-search',
	displayKey: function(item) { return item.sn + ' - ' + item.person.firstname + ' ' + item.person.surname; },
	source: deviceSearch.ttAdapter()
});

$('#inputOwner').typeahead({
	minLength: 2
},
{
	name: 'person-search',
	displayKey: function(item) { return item.code + ' - ' + item.firstname + ' ' + item.surname; },
	source: personSearch.ttAdapter()
});

$('#personQuickSearch').on('typeahead:selected', function(evt, item) {
	app_router.navigate('person/' + item.id, {trigger: true});
});

$('#deviceQuickSearch').on('typeahead:selected', function(evt, item) {
	app_router.navigate('device/' + item.id, {trigger: true});
});

/* Buttons to show dialogs */
function logIncident() {
	$('#modalLogIncident').modal();
	return false;
}

function editDevice() {
	$('#modalEditDevice').modal();
	return false;
}

function editPerson() {
	$('#modalEditPerson').modal();
	return false;
}

function editPersonSave() {
	alert('not implemented');
	$('#modalEditPerson').modal('hide');
}

/* Navigation */
function tabTo(tab) {
	$('ul.nav a[href="#' + tab + '"]').tab('show');
}

function sessionExpired() {
	window.location.href = 'index.html';
}

var AppRouter = Backbone.Router.extend({
    routes: {
        "person/:id": "loadPerson",
        "device/:id": "loadDevice",
        "*actions": "defaultRoute" // Backbone will try match the route above
									// first
    }
});

var app_router = new AppRouter;
app_router.on('route:loadPerson', function (id) {
	var person = new person_model({
		id : id
	});
	person.fetch({
		success : function(results) {
	    	tabTo('people');
	    	$('#personList').hide();
			var itemView = new PersonDetailView({
				model : results
			});
			itemView.render();
	    	$('#personDetail').show();

	    	/* Load device list */
	    	var devices = new DeviceCollection(results.get('device'));
	    	var devicesView = new PersonDeviceTableView({collection: devices});
	    	devicesView.render();
	    	
	    	/* Load history */
	    	var deviceHistoryList = new DeviceHistoryCollection(results.get('device_history'));
	    	var deviceHistoryListView = new PersonDeviceHistoryView({el: 'div#personDetailHistory',collection: deviceHistoryList});
	    	deviceHistoryListView.render();
	    	
	    	$('#btnPersonDeviceList').click(function() {
	    		$("#modalPersonDeviceList").modal();
	    		return false;
	    	});
	    	
	    	$('#person-device-tbody a').click(function() {
	    		$("#modalPersonDeviceList").modal('hide');
	    	});
		},
		error : function(model, response) {
			handleFailedRequest(response);
		}
	});
});

app_router.on('route:loadDevice', function (id) {
	var device = new device_model({
		id : id
	});
	device.fetch({
		success : function(results) {
	    	tabTo('devices');
	    	$('#deviceList').hide();
			var itemView = new DeviceDetailView({
				model : results
			});
			itemView.render();
	    	$('#deviceDetail').show();
	    	
	    	/* Load history */
	    	var deviceHistoryList = new DeviceHistoryCollection(results.get('device_history'));
	    	var deviceHistoryListView = new PersonDeviceHistoryView({el: 'div#deviceDetailHistory',collection: deviceHistoryList});
	    	deviceHistoryListView.render();
		},
		error : function(model, response) {
			handleFailedRequest(response);
		}
	});
});

app_router.on('route:defaultRoute', function (actions) {
    switch(actions) {
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

$('.nav-tabs a').click(function (e) {
	window.location.hash = this.hash;
});

Backbone.history.start();