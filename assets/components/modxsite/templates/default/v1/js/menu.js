$(document).ready(function(){
	$.easing.def = &quot;easeInOutQuad&quot;;
	$('menu-inner').click(function(e){
		var dropDown = $(this).parent().next();
		$('.dropdown').not(dropDown).slideUp('slow');
		dropDown.slideToggle('slow');
		e.preventDefault();
	})	
});