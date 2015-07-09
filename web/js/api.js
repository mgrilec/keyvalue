var api = (function($) {

	var api = {};
	api.url = '../api/';

	var get = function(route, success, fail) {
		var url = api.url + route;
		return $.get(url, success, 'json').fail(fail);
	};

	var post = function(route, params, success, fail) {
		var url = this.url + route;
		return $.post(url, params, success, 'json').fail(fail);
	};

	api.projects = function(success) {
		return get('projects', function(data) { success(data['result']) });
	};

	return api;
})(jQuery);