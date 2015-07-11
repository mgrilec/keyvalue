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

	api.projects = function(success, fail) {
		return get('projects', 
			function(data) { success(data.result); }, 
			function(error) { fail(error); }
		);
	};

	api.project_create = function(project_title, project_description, success, fail) {
		return post('projects/create', { 
			project: { 
				title: project_title, 
				description: project_description 
			}
		}, function(data) { success(data.result); },
		function(error) { fail(error); });
	};

	api.project_get = function(project_id, success, fail) {
		return get('projects/' + project_id, 
			function(data) { success(data.result); }, 
			function(error) { fail(error); }
		);
	};

	api.project_delete = function(project_id, success, fail) {
		return post('projects/delete', { 
			project: { 
				id: project_id 
			}
		}, function(data) { success(data.result); },
		function(error) { fail(error); }
		);
	};

	api.keys_count = function(project_id, success, fail) {
		return get('keys/' + project_id + "/count", 
			function(data) { success(data.result); },
			function(error) { fail(error); });
	};

	api.keys_get_all = function(project_id, success, fail) {
		return get('keys/' + project_id, 
			function(data) { success(data.result); },
			function(error) { fail(error); });
	};

	return api;
})(jQuery);