$('.google-maps').click(function () {
    $('.google-maps iframe').css("pointer-events", "auto");
});

$( ".google-maps" ).mouseleave(function() {
  $('.google-maps iframe').css("pointer-events", "none"); 
});