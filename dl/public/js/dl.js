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

var PersonSoftwareRowView = Backbone.View.extend({
	template : _.template($('#person-software-template-tr').html()),
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


var PersonKeyRowView = Backbone.View.extend({
	template : _.template($('#person-key-template-tr').html()),
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

var DeviceTypeRowView = Backbone.View.extend({
	template : _.template($('#device-type-tr').html()),
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

var DeviceStatusRowView = Backbone.View.extend({
	template : _.template($('#device-status-tr').html()),
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

var SoftwareDetailView = Backbone.View.extend({
	template : _.template($('#software-template-detail').html()),
	el : 'div#softwareDetailTop',
	initialize : function(options) {
		_.bindAll(this, 'render');
		this.model.bind('change', this.render);
	},

	render : function() {
		this.$el.html(this.template(this.model.toJSON()));
		return this;
	}
});

var KeyDetailView = Backbone.View.extend({
	template : _.template($('#key-template-detail').html()),
	el : 'div#keyDetailTop',
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

var SoftwareTableView = Backbone.View.extend({
	collection : null,
	el : 'tbody#software-tbody',

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
			var itemView = new SoftwareRowView({
				model : item
			});
			element.append(itemView.template(itemView.model.toJSON()));
		});
		return this;
	}
});

var KeyTableView = Backbone.View.extend({
	collection : null,
	el : 'tbody#key-tbody',

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
			var itemView = new KeyRowView({
				model : item
			});
			element.append(itemView.template(itemView.model.toJSON()));
		});
		return this;
	}
});

var SoftwareRowView = Backbone.View.extend({
	template : _.template($('#software-template-tr').html()),
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


var KeyRowView = Backbone.View.extend({
	template : _.template($('#key-template-tr').html()),
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

var PersonSoftwareTableView = Backbone.View.extend({
	collection : null,
	el : 'tbody#person-software-tbody',

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
			var itemView = new PersonSoftwareRowView({
				model : item
			});
			element.append(itemView.template(itemView.model.toJSON()));
		});
		return this;
	}
});

var PersonKeyTableView = Backbone.View.extend({
	collection : null,
	el : 'tbody#person-key-tbody',

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
			var itemView = new PersonKeyRowView({
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

var DeviceTypeTableView = Backbone.View.extend({
	collection : null,
	el : 'tbody#device-type-tbody',

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
			var itemView = new DeviceTypeRowView({
				model : item
			});
			element.append(itemView.template(itemView.model.toJSON()));
		});
		return this;
	}
});

var DeviceStatusTableView = Backbone.View.extend({
	collection : null,
	el : 'tbody#device-status-tbody',

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
			var itemView = new DeviceStatusRowView({
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

var SoftwareTypeSelectView = Backbone.View.extend({
	collection : null,
	el : '',
	template : _.template($('#software-type-select').html()),

	render : function() {
		this.$el.html(this.template({
			software_types : this.collection.toJSON()
		}));
		return this;
	}
});

var SoftwareStatusSelectView = Backbone.View.extend({
	collection : null,
	el : '',
	template : _.template($('#software-status-select').html()),

	render : function() {
		this.$el.html(this.template({
			software_statuses : this.collection.toJSON()
		}));
		return this;
	}
});

var KeyTypeSelectView = Backbone.View.extend({
	collection : null,
	el : '',
	template : _.template($('#key-type-select').html()),

	render : function() {
		this.$el.html(this.template({
			key_types : this.collection.toJSON()
		}));
		return this;
	}
});

var KeyStatusSelectView = Backbone.View.extend({
	collection : null,
	el : '',
	template : _.template($('#key-status-select').html()),

	render : function() {
		this.$el.html(this.template({
			key_statuses : this.collection.toJSON()
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

function doLoadPeople(page) {
	var count = 100;
	if (typeof page == "undefined") {
		page = 1;
	}
	$('tbody#person-tbody').empty();
	$('#personDetail').hide();
	$('#personList').show();
	var people = new person_collection();
	people.fetch({
		url : '/dl/api/person/list_all/' + page + '/' + count,
		success : function(results) {
			var db = new PersonTableView({
				collection : people
			});
			db.render();
			$('#personQuickSearch').focus();

			// Only include the prev link if page > 1
			$('#peoplePrevLink').off('click');
			if (page > 1) {
				$('#peoplePrevLink').on('click', function(e) {
					e.preventDefault();
					doLoadPeople(page - 1);
				});
				$('#peoplePrevLi').removeClass('disabled');
			} else {
				$('#peoplePrevLink').on('click', function(e) {
					e.preventDefault();
				});
				$('#peoplePrevLi').addClass('disabled');
			}

			// If this page is full, we need the 'Next link'
			$('#peopleNextLink').off('click');
			if (db.collection.length == count) {
				$('#peopleNextLink').on('click', function(e) {
					e.preventDefault();
					doLoadPeople(page + 1);
				});
				$('#peopleNextLi').removeClass('disabled');
			} else {
				$('#peopleNextLink').on('click', function(e) {
					e.preventDefault();
				});
				$('#peopleNextLi').addClass('disabled');
			}
		},
		error : function(model, response) {
			handleFailedRequest(response);
		}
	});
}

function doLoadDevices(page) {
	var count = 100;
	if (typeof page == "undefined") {
		page = 1;
	}
	$('tbody#device-tbody').empty();
	$('#deviceDetail').hide();
	$('#deviceList').show();
	var devices = new device_collection();
	devices.fetch({
		url : '/dl/api/device/list_all/' + page + '/' + count,
		success : function(results) {
			var db = new DeviceTableView({
				collection : devices
			});
			db.render();
			$('#deviceQuickSearch').focus();

			// Only include the prev link if page > 1
			$('#devicesPrevLink').off('click');
			if (page > 1) {
				$('#devicesPrevLink').on('click', function(e) {
					e.preventDefault();
					doLoadDevices(page - 1);
				});
				$('#devicesPrevLi').removeClass('disabled');
			} else {
				$('#devicesPrevLink').on('click', function(e) {
					e.preventDefault();
				});
				$('#devicesPrevLi').addClass('disabled');
			}

			// If this page is full, we need the 'Next link'
			$('#devicesNextLink').off('click');
			if (db.collection.length == count) {
				$('#devicesNextLink').on('click', function(e) {
					e.preventDefault();
					doLoadDevices(page + 1);
				});
				$('#devicesNextLi').removeClass('disabled');
			} else {
				$('#devicesNextLink').on('click', function(e) {
					e.preventDefault();
				});
				$('#devicesNextLi').addClass('disabled');
			}
		},
		error : function(model, response) {
			handleFailedRequest(response);
		}
	});
}

function doLoadSoftware(page) {
	var count = 100;
	if (typeof page == "undefined") {
		page = 1;
	}
	$('tbody#software-tbody').empty();
	$('#softwareDetail').hide();
	$('#softwareList').show();
	
	var software = new software_collection();
	software.fetch({
		url : '/dl/api/software/list_all/' + page + '/' + count,
		success : function(results) {
			var db = new SoftwareTableView({
				collection : software
			});
			db.render();
			$('#softwareQuickSearch').focus();

			// Only include the prev link if page > 1
			$('#softwarePrevLink').off('click');
			if (page > 1) {
				$('#softwarePrevLink').on('click', function(e) {
					e.preventDefault();
					doLoadSoftware(page - 1);
				});
				$('#softwarePrevLi').removeClass('disabled');
			} else {
				$('#softwarePrevLink').on('click', function(e) {
					e.preventDefault();
				});
				$('#softwarePrevLi').addClass('disabled');
			}

			// If this page is full, we need the 'Next link'
			$('#softwareNextLink').off('click');
			if (db.collection.length == count) {
				$('#softwareNextLink').on('click', function(e) {
					e.preventDefault();
					doLoadSoftware(page + 1);
				});
				$('#softwareNextLi').removeClass('disabled');
			} else {
				$('#softwareNextLink').on('click', function(e) {
					e.preventDefault();
				});
				$('#softwareNextLi').addClass('disabled');
			}
		},
		error : function(model, response) {
			handleFailedRequest(response);
		}
	});
}

function doLoadKeys(page) {
	var count = 100;
	if (typeof page == "undefined") {
		page = 1;
	}
	$('tbody#keys-tbody').empty();
	$('#keyDetail').hide();
	$('#keyList').show();
	
	var keys = new doorkey_collection();
	keys.fetch({
		url : '/dl/api/doorkey/list_all/' + page + '/' + count,
		success : function(results) {
			var db = new KeyTableView({
				collection : keys
			});
			db.render();
			$('#keyQuickSearch').focus();

			// Only include the prev link if page > 1
			$('#keyPrevLink').off('click');
			if (page > 1) {
				$('#keyPrevLink').on('click', function(e) {
					e.preventDefault();
					doLoadKeys(page - 1);
				});
				$('#keyPrevLi').removeClass('disabled');
			} else {
				$('#keyPrevLink').on('click', function(e) {
					e.preventDefault();
				});
				$('#keyPrevLi').addClass('disabled');
			}

			// If this page is full, we need the 'Next link'
			$('#keyNextLink').off('click');
			if (db.collection.length == count) {
				$('#keyNextLink').on('click', function(e) {
					e.preventDefault();
					doLoadKeys(page + 1);
				});
				$('#keyNextLi').removeClass('disabled');
			} else {
				$('#keyNextLink').on('click', function(e) {
					e.preventDefault();
				});
				$('#keyNextLi').addClass('disabled');
			}
		},
		error : function(model, response) {
			handleFailedRequest(response);
		}
	});
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

	/* Recorded person ID */
	$('#addDeviceOwnerFrmGroup').removeClass('has-error');
	$('#addDeviceOwnerFrmGroup').removeClass('has-success');
	$('#addDevicePersonId').val('');

	$('#addSoftwareOwnerFrmGroup').removeClass('has-error');
	$('#addSoftwareOwnerFrmGroup').removeClass('has-success');
	$('#addSoftwarePersonId').val('');

	$('#addKeyOwnerFrmGroup').removeClass('has-error');
	$('#addKeyOwnerFrmGroup').removeClass('has-success');
	$('#addKeyPersonId').val('');

	/* Fill combo boxes */
	renderDeviceTypes('select#addDeviceSelectType', '');
	renderDeviceStatuses('select#addDeviceSelectStatus', '');
	renderSoftwareTypes('select#addSoftwareSelectType', '');
	renderSoftwareStatuses('select#addSoftwareSelectStatus', '');
	renderKeyTypes('select#addKeySelectType', '');
	renderKeyStatuses('select#addKeySelectStatus', '');

	$("#modalAddNew").modal();
	return false;
});

$('#cboAddNew').change(function() {
	$('.show-hide').hide()
	$('#' + this.value).show();
});

$('#submitAddNew')
		.click(
				function() {
					switch ($('#cboAddNew').val()) {
					case 'addperson':
						var person = new person_model(
								{
									code : $('#addPersonUserCode').val(),
									firstname : $('#addPersonFirstName').val(),
									surname : $('#addPersonSurname').val(),
									is_staff : ($('#addPersonIsStaff').prop(
											'checked') ? '1' : 0),
									is_active : ($('#addPersonIsActive').prop(
											'checked') ? '1' : 0)
								});

						person
								.save(
										null,
										{
											success : function(model, response) {
												var id = model.get('id');
												app_router.navigate('person/'
														+ id, {
													trigger : true
												});
												$("#modalAddNew").modal('hide');
											},
											error : function(model, response) {
												$('#addPersonStatus')
														.html(
																"Could not add person! Check that you have included all of the information.");
												$('#addPersonStatus').show();
											}
										});
						break;
					case 'adddevice':
						var device = new device_model(
								{
									sn : $('#addDeviceSn').val(),
									mac_eth0 : $('#addDeviceMacEth0').val(),
									mac_wlan0 : $('#addDeviceMacWlan0').val(),
									person_id : $('#addDevicePersonId').val(),
									device_status_id : $(
											'#addDeviceSelectStatus').val(),
									device_type_id : $('#addDeviceSelectType')
											.val(),
									is_bought : ($('#addDeviceIsBought').prop(
											'checked') ? '1' : 0),
									is_spare : ($('#addDeviceIsSpare').prop(
											'checked') ? '1' : 0),
									is_damaged : ($('#addDeviceIsDamaged')
											.prop('checked') ? '1' : 0)
								});

						device
								.save(
										null,
										{
											success : function(model, response) {
												var id = model.get('id');
												app_router.navigate('device/'
														+ id, {
													trigger : true
												});
												$("#modalAddNew").modal('hide');
											},
											error : function(model, response) {
												$('#addDeviceStatus')
														.html(
																"Could not add device! Check that you have included all of the information.");
												$('#addDeviceStatus').show();
											}
										});
						break;
					case 'addsoftware':
						var software = new software_model({
							code : $('#addSoftwareCode').val(),
							software_type_id : $('#addSoftwareSelectType')
									.val(),
							software_status_id : $('#addSoftwareSelectStatus')
									.val(),
							person_id : $('#addSoftwarePersonId').val(),
							is_bought : ($('#addSoftwareIsBought').prop(
									'checked') ? '1' : 0)
						});
						software
								.save(
										null,
										{
											success : function(model, response) {
												var id = model.get('id');
												app_router.navigate('licence/'
														+ id, {
													trigger : true
												});
												$("#modalAddNew").modal('hide');
											},
											error : function(model, response) {
												$('#addSoftwareStatus')
														.html(
																"Could not add software! Check that you have included all of the information.");
												$('#addSoftwareStatus').show();
											}
										});
						break;
					case 'addkey':
						var key = new doorkey_model(
								{
									serial : $('#addKeySerial').val(),
									person_id : $('#addKeyPersonId').val(),
									is_spare : ($('#addKeyIsSpare').prop(
											'checked') ? '1' : 0),
									key_type_id : $('#addKeySelectType').val(),
									key_status_id : $('#addKeySelectStatus')
											.val()
								});
						key
								.save(
										null,
										{
											success : function(model, response) {
												var id = model.get('id');
												app_router.navigate(
														'key/' + id, {
															trigger : true
														});
												$("#modalAddNew").modal('hide');
											},
											error : function(model, response) {
												$('#addKeyStatus')
														.html(
																"Could not add key! Check that you have included all of the information.");
												$('#addKeyStatus').show();
											}
										});
						break;
					default:
						// Do nothing on the main page
					}
				});

// Switch on autocomplete
var personSearch = new Bloodhound({
	datumTokenizer : Bloodhound.tokenizers.obj.whitespace('value'),
	queryTokenizer : Bloodhound.tokenizers.whitespace,
	remote : '/dl/api/person/search/1/15?q=%QUERY'
});
personSearch.initialize();

var deviceSearch = new Bloodhound({
	datumTokenizer : Bloodhound.tokenizers.obj.whitespace('value'),
	queryTokenizer : Bloodhound.tokenizers.whitespace,
	remote : '/dl/api/device/search/1/15?q=%QUERY'
});
deviceSearch.initialize();

var softwareSearch = new Bloodhound({
	datumTokenizer : Bloodhound.tokenizers.obj.whitespace('value'),
	queryTokenizer : Bloodhound.tokenizers.whitespace,
	remote : '/dl/api/software/search/1/15?q=%QUERY'
});
softwareSearch.initialize();

var keySearch = new Bloodhound({
	datumTokenizer : Bloodhound.tokenizers.obj.whitespace('value'),
	queryTokenizer : Bloodhound.tokenizers.whitespace,
	remote : '/dl/api/doorkey/search/1/15?q=%QUERY'
});
keySearch.initialize();

$('#personQuickSearch').typeahead({
	minLength : 1
}, {
	name : 'person-search',
	displayKey : function(item) {
		return item.code + ' - ' + item.firstname + ' ' + item.surname;
	},
	source : personSearch.ttAdapter()
});

$('#deviceQuickSearch').typeahead(
		{
			minLength : 1
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
	minLength : 1
}, {
	name : 'person-search',
	displayKey : function(item) {
		return item.code + ' - ' + item.firstname + ' ' + item.surname;
	},
	source : personSearch.ttAdapter()
});

$('#addKeyOwner').typeahead({
	minLength : 1
}, {
	name : 'person-search',
	displayKey : function(item) {
		return item.code + ' - ' + item.firstname + ' ' + item.surname;
	},
	source : personSearch.ttAdapter()
});

$('#addSoftwareOwner').typeahead({
	minLength : 1
}, {
	name : 'person-search',
	displayKey : function(item) {
		return item.code + ' - ' + item.firstname + ' ' + item.surname;
	},
	source : personSearch.ttAdapter()
});

$('#softwareQuickSearch').typeahead(
		{
			minLength : 1
		},
		{
			name : 'software-search',
			displayKey : function(item) {
				return item.code + ' - ' + item.person.firstname + ' '
						+ item.person.surname;
			},
			source : softwareSearch.ttAdapter()
		});

$('#keyQuickSearch').typeahead(
		{
			minLength : 1
		},
		{
			name : 'key-search',
			displayKey : function(item) {
				return item.serial + ' - ' + item.key_type.name + ' - '
						+ item.person.firstname + ' ' + item.person.surname;
			},
			source : keySearch.ttAdapter()
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

$('#addSoftwareOwner').on('typeahead:selected', function(evt, item) {
	$('#addSoftwareOwnerFrmGroup').removeClass('has-error');
	$('#addSoftwareOwnerFrmGroup').addClass('has-success');
	$('#addSoftwarePersonId').val(item.id);
});

$('#addSoftwareOwner').on('input', function() {
	// Visual cue that a person has not been selected
	$('#addSoftwareOwnerFrmGroup').addClass('has-error');
	$('#addSoftwareOwnerFrmGroup').removeClass('has-success');
	$('#addSoftwarePersonId').val('');
});

$('#addKeyOwner').on('typeahead:selected', function(evt, item) {
	$('#addKeyOwnerFrmGroup').removeClass('has-error');
	$('#addKeyOwnerFrmGroup').addClass('has-success');
	$('#addKeyPersonId').val(item.id);
});

$('#addKeyOwner').on('input', function() {
	// Visual cue that a person has not been selected
	$('#addKeyOwnerFrmGroup').addClass('has-error');
	$('#addKeyOwnerFrmGroup').removeClass('has-success');
	$('#addKeyPersonId').val('');
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

$('#softwareQuickSearch').on('typeahead:selected', function(evt, item) {
	app_router.navigate('licence/' + item.id, {
		trigger : true
	});
});

$('#keyQuickSearch').on('typeahead:selected', function(evt, item) {
	app_router.navigate('key/' + item.id, {
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

function logSoftwareChange(software_status_id) {
	$('#modalLogSoftwareChange').modal();
	//shChangeSelect('comment');
	//renderSoftwareStatuses("select#shSelectStatus", software_status_id);
	return false;
}

function logKeyChange(key_status_id) {
	$('#modalLogKeyChange').modal();
	//khChangeSelect('comment');
	//renderKeyStatuses("select#khSelectStatus", key_status_id);
	return false;
}

function editSoftware(software_type_id) {
	$('#modalEditSoftware').modal();
	renderSoftwareTypes('select#editSoftwareSelectType', software_type_id);
	return false;
}

function editKey(key_type_id) {
	$('#modalEditKey').modal();
	renderKeyTypes('select#editKeySelectType', key_type_id);
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
	var device_statuses = new device_status_collection();
	device_statuses.fetch({
		success : function(results) {
			var db = new DeviceStatusSelectView({
				collection : device_statuses,
				el : dest
			});
			db.render();
			if (device_status_id != '') {
				$(dest + " option[value='" + device_status_id + "']").attr(
						"selected", "selected");
			}
		},
		error : function(model, response) {
			handleFailedRequest(response);
		}
	});
}

function renderSoftwareTypes(dest, software_type_id) {
	var software_types = new software_type_collection();
	software_types.fetch({
		success : function(results) {
			var db = new SoftwareTypeSelectView({
				collection : software_types,
				el : dest
			});
			db.render();
			if (software_type_id != '') {
				$(dest + " option[value='" + software_type_id + "']").attr(
						"selected", "selected");
			}
		},
		error : function(model, response) {
			handleFailedRequest(response);
		}
	});
}

function renderSoftwareStatuses(dest, software_status_id) {
	var software_statuses = new software_status_collection();
	software_statuses.fetch({
		success : function(results) {
			var db = new SoftwareStatusSelectView({
				collection : software_statuses,
				el : dest
			});
			db.render();
			if (software_status_id != '') {
				$(dest + " option[value='" + software_status_id + "']").attr(
						"selected", "selected");
			}
		},
		error : function(model, response) {
			handleFailedRequest(response);
		}
	});
}

function renderKeyTypes(dest, key_type_id) {
	var key_types = new key_type_collection();
	key_types.fetch({
		success : function(results) {
			var db = new KeyTypeSelectView({
				collection : key_types,
				el : dest
			});
			db.render();
			if (key_type_id != '') {
				$(dest + " option[value='" + key_type_id + "']").attr(
						"selected", "selected");
			}
		},
		error : function(model, response) {
			handleFailedRequest(response);
		}
	});
}

function renderKeyStatuses(dest, key_status_id) {
	var key_statuses = new key_status_collection();
	key_statuses.fetch({
		success : function(results) {
			var db = new KeyStatusSelectView({
				collection : key_statuses,
				el : dest
			});
			db.render();
			if (key_status_id != '') {
				$(dest + " option[value='" + key_status_id + "']").attr(
						"selected", "selected");
			}
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
			$('#editPersonStatus').html(response.responseJSON.error);
			$('#editPersonStatus').show();
		}
	});
}

function editPersonDelete() {
	var person = new person_model({
		id : $('#editPersonId').val()
	});
	person.destroy({
		success : function(model, response) {
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
		success : function(model, response) {
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
	$('#logIncidentStatus').hide();
	return false;
}

function logIncidentSave(receipt) {
	var change = $('#dhChange').val();
	var device_history = new device_history_model({
		device_id : $('#dhDeviceId').val(),
		receipt : receipt
	});

	switch (change) {
	case 'comment':
	case 'photo':
		var att = {
			change : change,
			comment : $('#dhComment').val()
		};
		break;
	case 'owner':
		var att = {
			change : change,
			comment : $('#dhComment').val(),
			person_id : $('#dhPersonId').val()
		};
		break;
	case 'status':
		var att = {
			change : change,
			comment : $('#dhComment').val(),
			device_status_id : $('#dhSelectStatus').val()
		};
		break;
	case 'damaged':
		var att = {
			change : change,
			comment : $('#dhComment').val(),
			is_damaged : ($('#dhIsDamaged').prop('checked') ? '1' : '0')
		};
		break;
	case 'spare':
		var att = {
			change : change,
			comment : $('#dhComment').val(),
			is_spare : ($('#dhIsSpare').prop('checked') ? '1' : '0')
		};
		break;
	case 'bought':
		var att = {
			change : change,
			comment : $('#dhComment').val(),
			is_bought : ($('#dhIsBought').prop('checked') ? '1' : '0')
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
				device = new device_model({
					id : device_history.get('device_id')
				});
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
			$('#logIncidentStatus').hide();
			$('#logIncidentStatus').html(response.responseJSON.error);
			$('#logIncidentStatus').show(300);
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
		"licence/:id" : "loadSoftware",
		"key/:id" : "loadKey",
		"*actions" : "defaultRoute"
	}
});

function showDeviceStatuses() {
	var statuses = new device_status_collection();
	statuses.fetch({
		success : function(results) {
			var db = new DeviceStatusTableView({
				collection : statuses
			});
			db.render();

			$('#modalDeviceStatus').modal();
		},
		error : function(model, response) {
			handleFailedRequest(response);
		}
	});
	return false;
}

function showDeviceTypes() {
	var types = new device_type_collection();
	types.fetch({
		success : function(results) {
			var db = new DeviceTypeTableView({
				collection : types
			});
			db.render();

			$('#modalDeviceType').modal();
		},
		error : function(model, response) {
			handleFailedRequest(response);
		}
	});
	return false;
}

function showDeviceDetail(results) {
	tabTo('devices');
	$('#deviceList').hide();
	var itemView = new DeviceDetailView({
		model : results
	});
	itemView.render();
	$('#deviceDetail').show();

	/* Load history */
	var deviceHistoryList = new device_history_collection(results
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
		url : "/dl/api/device_photo/upload/" + results.get('id'),
		uploadMultiple : true,
		acceptedFiles : "image/*",
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
	var devices = new device_collection(results.get('device'));
	var devicesView = new PersonDeviceTableView({
		collection : devices
	});
	devicesView.render();
	
	/* Load licenses */
	var software = new software_collection(results.get('software'));
	var softwareView = new PersonSoftwareTableView({
		collection : software
	});
	softwareView.render();
	
	/* Load keys */
	var doorkeys = new doorkey_collection(results.get('doorkey'));
	var keyView = new PersonKeyTableView({
		collection : doorkeys
	});
	keyView.render();

	/* Load history */
	var deviceHistoryList = new device_history_collection(results
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
	$('#btnPersonSoftwareList').click(function() {
		$("#modalPersonSoftwareList").modal();
		return false;
	});
	$('#btnPersonKeyList').click(function() {
		$("#modalPersonKeyList").modal();
		return false;
	});
	
	$('#person-device-tbody a').click(function() {
		$("#modalPersonDeviceList").modal('hide');
	});
	$('#person-software-tbody a').click(function() {
		$("#modalPersonSoftwareList").modal('hide');
	});
	$('#person-key-tbody a').click(function() {
		$("#modalPersonKeyList").modal('hide');
	});
}

function showSoftwareDetail(results) {
	tabTo('software');
	$('#softwareList').hide();
	var itemView = new SoftwareDetailView({
		model : results
	});
	itemView.render();
	$('#softwareDetail').show();
}

function showKeyDetail(results) {
	tabTo('keys');
	$('#keyList').hide();
	var itemView = new KeyDetailView({
		model : results
	});
	itemView.render();
	$('#keyDetail').show();
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

app_router.on('route:loadSoftware', function(id) {
	var software = new software_model({
		id : id
	});
	software.fetch({
		success : function(results) {
			showSoftwareDetail(results);
		},
		error : function(model, response) {
			handleFailedRequest(response);
		}
	});
});

app_router.on('route:loadKey', function(id) {
	var key = new doorkey_model({
		id : id
	});
	key.fetch({
		success : function(results) {
			showKeyDetail(results);
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