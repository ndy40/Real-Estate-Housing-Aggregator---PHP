
// Fastclick
// --------------------------------------------------

$(function() {
    FastClick.attach(document.body);
});


// Navigation Toggle
// --------------------------------------------------

function toggleNav() {
	$('.left-nav, .wrapper, body').toggleClass('active');      
}

$('.menu-btn').click(function() {toggleNav();});  
$('.left-nav a').click(function() {toggleNav();});



// Full Width Prevent
// --------------------------------------------------


//var msg = "<div class='mt-5' style='padding:1em;'><h1 class='c-green'>Property Crunch and large screens weren't meant to be! :( </h1> <br>Please view this on a mobile phone or resize your browser and <b>refresh</b> the page.</div>";
//
//if ($(window).width() >= 640){$('body').html(msg);}
//
//$(window).resize(function(){
//	if ($(window).width() >= 640){$('body').html(msg);}
//});



// Dashboard Scrolly text
// --------------------------------------------------


$( document ).ready(function() {
	$('#container').css('width', ($(window).width()));
});

$(window).resize(function() {
	$('#container').css('width', ($(window).width()));	
});


// Commas on inputs
// --------------------------------------------------


$('input.number').keyup(function(event) {

  // skip for arrow keys
  if(event.which >= 37 && event.which <= 40){
    event.preventDefault();
  }

  $(this).val(function(index, value) {
    return value
      .replace(/\D/g, "")
      .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
    ;
  });
});