  $(document).ready(function(){
    $('#cards .row').masonry({
      itemSelector: '#cards .col',
    });
    $('.modal-trigger').leanModal();
    $('.button-collapse').sideNav();
  });