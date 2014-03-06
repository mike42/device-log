var DeviceCollection = Backbone.Collection.extend({
	url : '/dl/api/device/list_all/1/100',
	model : device_model
});

var DeviceView = Backbone.View.extend({
	template : _.template($('#device-template').html()),
	tagName : 'tr',
	// el: 'div#device-table',

	initialize : function(options) {
		_.bindAll(this, 'render');
		this.model.bind('change', this.render);
	},

	render : function() {
		this.$el.html(this.template(this.model.toJSON()));
		return this;
	}
});

var DevicesView = Backbone.View.extend({
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
			var itemView = new DeviceView({
				model : item
			});
			element.append(itemView.template(itemView.model.toJSON()));
		});
		return this;
	}
});

function foobar() {
	var devices = new DeviceCollection();
	devices.fetch({
		success : function(results) {
			var db = new DevicesView({
				collection : devices
			});
			db.render();
			console.log(results);
		}
	});

	// var a = new device_model({
	// id : 23
	// });
	// a.fetch({
	// success : function(results) {
	// var firstView = new DeviceView({
	// model : a
	// });
	// console.log(results);
	// }
	// });
	// var a = new device_model({sn: 'aaa', person_id: 1, device_status_id: 1,
	// device_type_id: 1});

	// create new view
	// var firstView = new DeviceView({
	// model : a
	// });

	// a.save();

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
