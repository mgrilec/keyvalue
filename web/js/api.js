var api = (function($) {

	var api = {};
	api.url = '../api/';

	var get = function(route, success, fail) {
		var url = api.url + route;
		return $.get(url, success, 'json').fail(fail);
	};

	var post = function(route, params, success, fail) {
		var url = api.url + route;
		return $.post(url, params, success, 'json').fail(fail);
	};

	api.projects = function(success) {
		return get('projects', function(data) { success(data['result']); });
	};

	api.project_delete = function(project_id, success) {
		return post('projects/delete', { project: { id: project_id }}, function(data) { success(data['result'])});
	};

	api.keys_count = function(project_id, success) {
		return get('keys/' + project_id + "/count", function(data) { success(data['result']);})
	};

	api.keys_get_all = function(project_id, success) {
		return get('keys/' + project_id, function(data) { success(data['result']);})
	};

	return api;
})(jQuery);