jQuery(document).ready(function($){

	// Date Pickers

	if ($('#nnr-start-datepicker').length != 0 &&
		$('#nnr-end-datepicker').length != 0) {

		$('#nnr-start-datepicker').datetimepicker({
	        format: 'MM/DD/YYYY'
	    });

		$('#nnr-end-datepicker').datetimepicker({
	        format: 'MM/DD/YYYY'
	    });

		$('#nnr-end-datepicker')
			.data("DateTimePicker")
			.minDate( $('#nnr-start-datepicker').data("DateTimePicker").date() );

		$('#nnr-start-datepicker').on("dp.change",function (e) {
	       $('#nnr-end-datepicker').data("DateTimePicker").minDate(e.date);
	    });

	    $('#nnr-end-datepicker').on("dp.change",function (e) {
	       $('#nnr-start-datepicker').data("DateTimePicker").maxDate(e.date);
	    });

	}

});