function foobar() {
	var a = new device_model({id: 23});
	a.fetch({
		success: function(results) {
			console.log(results);
		}
	});
	var a = new device_model({sn: 'aaa', person_id: 1, device_status_id: 1, device_type_id: 1});
	
	var ImageView = Backbone.View.extend({
	    el: 'div#img',
	    initialize: function () {
	        this.render();
	    },
	    render: function () {
	        this.$el.html( this.model.get('title') ) ;
	        return this;
	    }
	});
	// create new view
	var firstView = new ImageView({model: firstImage});


	Read more: http://mrbool.com/backbone-js-view/27972#ixzz2v3GslpLY
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
