  $(document).ready(function(){

  	// masonry
    $('#projects').masonry({
      itemSelector: '#project',
    });

    // modal
    $('.modal-trigger').leanModal();

    // side nav?
    $('.button-collapse').sideNav();

    async.series({
    	// compile template
    	template: function(callback) {
    		$.get('ui/projects/project-card.html', function(data) { 
    			callback(null, Handlebars.compile(data)); 
    		}, 'html');
    	},
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

				card.find('.delete').click(function(e) {
					api.project_delete(e.target.id, function(result) {
						if (result) {
							card.remove();
							Materialize.toast('Project deleted.', 4000)
						} else {
							Materialize.toast('Unable to delete project.', 4000)
						}
					});
					
				});

				$('#projects').append(card);
			});
	});
  });