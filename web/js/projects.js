  $(document).ready(function(){

  	// masonry
    $('#cards .row').masonry({
      itemSelector: '#cards .col',
    });

    // modal
    $('.modal-trigger').leanModal();

    // side nav?
    $('.button-collapse').sideNav();

    api.projects(function(data) {
		console.log(data);
    });
  });