function foobar() {
	var a = new device_model({id: 16});
	a.fetch({
		success: function(results) {
			a.destroy();
		}
	});
	//var a = new device_model({sn: 'aaa', person_id: 1, device_status_id: 1, device_type_id: 1});
	//a.save();
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
