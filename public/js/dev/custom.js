
// Bootstrap Stuff
// --------------------------------------------------

$('.btn').button();
$('.dropdown-toggle').dropdown();


// Navigation Toggle
// --------------------------------------------------

function toggleNav() {
	$('.menu-btn').click(function() {
		$('.left-nav, .wrapper, body').toggleClass('active');
	});         
}
toggleNav();


// Makeshift Login
// --------------------------------------------------

$(".l-temp").submit(function(e) {
	e.preventDefault();
	if ($('.usrname').val() == "test" && $('.pswrd').val() == "1234"){
		window.location = "/pc/dashboard"; 		
	} else{
		$('.login-error').show();
	}
});

// Full Width Prevent
// --------------------------------------------------


var msg = "<div class='mt-5' style='padding:1em;'><h1 class='c-green'>Property Crunch and large screens weren't meant to be! :( </h1> <br>Please view this on a mobile phone or resize your browser and <b>refresh</b> the page.</div>";

if ($(window).width() >= 500){$('body').html(msg);}

$(window).resize(function(){
	if ($(window).width() >= 500){$('body').html(msg);}
});

