$(window).load(function() {
var f = document.createElement('iframe');
f.src = "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2860.009099170561!2d28.621428165342632!3d44.2068789791062!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40baf00e6dc07ee5%3A0x75cec2ffd53471e1!2sBloc+A%2C+Strada+Viitorului+8%2C+Constan%C8%9Ba+900483%2C+Rom%C3%A2nia!5e0!3m2!1sro!2suk!4v1526290100692"; 
f.width = 600; 
f.height = 450;
f.style = "border:0";
f.setAttribute('allowFullScreen', '');
$('div.google-maps').append(f);
});

$('.google-maps').click(function () {
    $('.google-maps iframe').css("pointer-events", "auto");
});

$( ".google-maps" ).mouseleave(function() {
  $('.google-maps iframe').css("pointer-events", "none"); 
});