
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
	e.preventDefault();//prevent the form from actually submitting
	if ($('.usrname').val() == "test" && $('.pswrd').val() == "1234"){
		window.location = "/dashboard"; 		
	} else{
		$('.login-error').show();
	}
	
});
