$(document).ready(function(){

  	// masonry
    var grid = $('#projects').masonry({
      itemSelector: '.project',
    });

    // modal
    $('.modal-trigger').leanModal();

    // side nav?
    $('.button-collapse').sideNav();

    var add = function(card) {
		// on delete
		card.find('.delete').click(function(e) {
			api.project_delete(e.target.id, function(result) {
				if (result) {
					remove(card);
					Materialize.toast('Project deleted.', 4000);
				} else {
					Materialize.toast('Unable to delete project.', 4000);
				}
			});
			
		});

		grid.prepend(card);
		grid.masonry('prepended', card);
		grid.masonry();
    };

    var remove = function(card) {
    	grid.masonry('remove', card);
    	grid.masonry();
    }

    // get all projects
    async.series({
    	// compile template
    	template: function(callback) {
    		$.get('ui/projects/project-card.html', function(data) { 
    			callback(null, Handlebars.compile(data)); 
    		}, 'html');
    	},
    	// get projects
    	projects: function(projectsCallback) {
    		api.projects(function(projects) {

    			// get keys count for each project
    			async.each(projects, function(project, countCallback) {
    				api.keys_count(project.id, function(count) {
    					project.empty = count == 0;
    					countCallback();
    				});
    			}, function(error) {
					projectsCallback(null, projects);
    			});
    			
    		});
    	}
	},
	function(error, results) {
		results.projects.forEach(function(project, index) {
				var card = $(results.template({ project: project }));
				add(card);
			});
	});

	// create new project
	$('#create').submit(function(e) {
		var formData = $(this).serializeArray();
		var data = {};
		formData.forEach(function(item) {
			data[item.name] = item.value;
		});

		api.project_create(data['project_title'], data['project_create'], function(id) {

			// add a project card
			async.parallel({
				template: function(callback) {
					$.get('ui/projects/project-card.html', function(data) { 
		    			callback(null, Handlebars.compile(data)); 
		    		}, 'html');
				},
				project: function(callback) {
					api.project_get(id, function(project) {
						callback(null, project);
					});
				}
			}, function(error, results) {
				var card = $(results.template({ project: results.project }));
				add(card);
				Materialize.toast('Project created.', 4000);
			});
		});

		// reset form fields
		$(this).trigger('reset');

		// prevent form going off
		e.preventDefault();
	});
});