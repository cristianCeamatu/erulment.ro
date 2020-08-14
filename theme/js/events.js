$(window).load(function() {
     var pgurl = window.location.href.substr(window.location.href.lastIndexOf("/")+1);
     $("#nav li a").each(function(){
          if($(this).attr("href") == pgurl || $(this).attr("href") == '' ){
            $("#nav li a").removeClass('active');
            $(this).addClass("active");
            }
     });
          $(".informatiiNav a").each(function(){
          if($(this).attr("href") == pgurl || $(this).attr("href") == pgurl+"#content" ){
            $(".informatiiNav a").removeClass('active');
            $(this).addClass("active");
            }
     });
});

var main = function(){
//Submit Glow
$('input[type="submit"], button[type="submit"]').click(function() {
$(this).addClass('partial-fade');
$(this).animate({
opacity: 0.1
}, 8).animate({
opacity: 0.9
}, 226).animate({
opacity: .5
}, 86);
$('input[type="submit"], button[type="submit"]').removeClass('partial-fade').animate({opacity: 1}, 86);
});

$('.menuButton li a').click(function(event) {
    $('.mobileMenu').toggleClass('hidden');
    event.preventDefault();
  });
}
$(window).ready(main);

equalheight = function(container){

var currentTallest = 0,
     currentRowStart = 0,
     rowDivs = new Array(),
     $el,
     topPosition = 0;
 $(container).each(function() {

   $el = $(this);
   $($el).height('auto')
   topPostion = $el.position().top;

   if (currentRowStart != topPostion) {
     for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
       rowDivs[currentDiv].height(currentTallest);
     }
     rowDivs.length = 0; // empty the array
     currentRowStart = topPostion;
     currentTallest = $el.height();
     rowDivs.push($el);
   } else {
     rowDivs.push($el);
     currentTallest = (currentTallest < $el.height()) ? ($el.height()) : (currentTallest);
  }
   for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
     rowDivs[currentDiv].height(currentTallest);
   }
 });
}

$(window).load(function() {
  equalheight('.infoItem');
});


$(window).resize(function(){
  equalheight('.infoItem');
});
