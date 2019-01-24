jQuery(function($){
	$('.pay-fees-btn').on('click',function(){
		$(this).slideUp(1000);
		$('.payment-form').slideDown(800);
	});
	

	$('.send-sms-btn').on('click',function(){
		$(this).slideUp(1000);
		$('.sms-form').slideDown(800);
	});


});