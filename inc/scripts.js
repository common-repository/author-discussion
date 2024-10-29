jQuery(document).ready(function($){

	// Quick Message Show/Hide
	$("#auth_discuss_quick_msg").click(function(){
		event.preventDefault();
		$("#auth_discuss_form").slideToggle();
		if($(this).html() == 'Show'){
			$(this).html('Hide')
		}else{
			$(this).html('Show')
		}
	});
	
	// Fade Effect
	$('.auth_discuss_fade').click(function(){
		$(this).fadeOut()
	});
});