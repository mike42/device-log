Device = Backbone.Model.extend({
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
    },
	initialize: function(){
	    // alert("Welcome to this world");
	}
});

function foobar() {
	var a = new Device({sn: 'aaa', person_id: 1, device_status_id: 1, device_type_id: 1});
	a.save();
}

$('#btnLogout').on('click', function(event) {
	event.preventDefault();
	$("#btnLogout").prop('disabled', true);

	var jqxhr = $.get("api/session/logout/").done(function(data) {
		window.location.href = 'index.html';
	}).fail(function() {
		warn("Couldn't contact the server");
	});
	return false;
});

$('#btnAddNew').on('click', function(event) {
	$("#myModal").modal();
	return false;
});

function warn(message) {
	console.log(message);
}