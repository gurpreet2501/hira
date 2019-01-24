$(function(){
	$('.datepicker-input').datepicker({
			dateFormat: js_date_format, 
			showButtonPanel: true,
			changeMonth: true,
			changeYear: true,
			yearRange: '1950:'+(new Date()).getFullYear() 
	});
	 
	$('.datepicker-input-clear').button();
	
	$('.datepicker-input-clear').click(function(){
		$(this).parent().find('.datepicker-input').val("");
		return false;
	});
	
});
