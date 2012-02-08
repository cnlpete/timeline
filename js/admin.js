jQuery(document).ready(function($){

	/* messages */
	$().message();

	/* datepicker options */
	$.datepicker.setDefaults($.datepicker.regional['de']); // make it german
	$.datepicker.setDefaults({
			dateFormat: 'yy-mm-dd', 
			showOtherMonths: true,
			selectOtherMonths: true,
			changeMonth: true,
			changeYear: true });

	var eventList = $('#eventList');
	var eventDetails = $('#eventDetails');

	// insert event
	$('#new').click(function(){
		$.get('admin.inc.php?action=insert', function(data){
			eventDetails.html(data);
			eventDetails.find('.dateentry').datepicker();
		});
	});

	// show event
	eventList.on('click', '.event:not(.new)', function(){
		var id = $(this).parent().data('id');
		$.get('admin.inc.php?action=show&id=' + id, function(data){
			eventDetails.html(data);
		});
	});

	// edit event
	eventList.on('click', '.edit', function(){
		var id = $(this).parent().data('id');
		$.get('admin.inc.php?action=edit&id=' + id, function(data){
			eventDetails.html(data);
			eventDetails.find('.dateentry').datepicker();
		});
	});

	// delete event
	eventList.on('click', '.delete', function(){
		var id = $(this).parent().data('id');
		$(this).after("<p id=\"dialogConfirm\">Soll das Ereignis " +  id + " wirklich gel&ouml;scht werden?</p>");
		$('#dialogConfirm').dialog({
			resizable: false,
			height:140,
			modal: true,
			buttons: {
				Confirm: function() {
					//do deletion
					$.get('admin.inc.php?action=deleteconfirmation&id=' + id, function(data){
						eventDetails.html(data);
						$('#eventList').find('[data-id="' + id + '"]').fadeOut('slow');
					});
					$( this ).dialog( "close" );
					$('#dialogConfirm').remove();
				},
				Cancel: function() {
				//do nothing
					eventDetails.html('Ereignis ' + id + ' wurde nicht geloescht.');
					$( this ).dialog( "close" );
					$('#dialogConfirm').remove();
				}
			}
		});

	});

	// looking for form submits...
	eventDetails.on('submit', 'form', function(e){
		e.preventDefault();
		var form = $(this);
		var action = form.data('action');

		$.get('admin.inc.php?action=' + action + '&' + form.serialize(), function(data){
			eventDetails.html(data);
			$.get('admin.inc.php?action=refresh', function(data){
				eventList.find('div:first').html(data);
			});
		});
	});

		// databaseupdate
	$('#databaseUpdate').click(function() {
		$.get('admin.inc.php?action=databaseRefresh', function(data){
			eventDetails.html(data);
		});
	});

});
