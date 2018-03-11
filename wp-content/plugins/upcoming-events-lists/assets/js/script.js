(function( $ ) {
	$( '#sis-event-start-date' ).datepicker({
		dateFormat: 'yy/mm/dd',
		onClose: function( selectedDate ){
			$( '#sis-event-end-date' ).datepicker( 'option', 'minDate', selectedDate );
		}
	});
	$( '#sis-event-end-date' ).datepicker({
		dateFormat: 'yy/mm/dd',
		onClose: function( selectedDate ){
			$( '#sis-event-start-date' ).datepicker( 'option', 'maxDate', selectedDate );
		}
	});
})( jQuery );