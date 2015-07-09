  $(document).ready(function(){

  	// masonry
    $('#projects').masonry({
      itemSelector: '#project',
    });

    // modal
    $('.modal-trigger').leanModal();

    // side nav?
    $('.button-collapse').sideNav();

    async.series([
    	function(callback) {
    		console.log('hello');
    		callback(null, 'h');
    	},
    	function(callback) {
    		console.log('hello2');
    		callback(null, 'h2');
    	}
	]);

    api.projects(function(data) {
		console.log(data);
    });
  });