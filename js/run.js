jQuery(document).ready(function($){

	/* messages */
	$().message();

	/* iScroll */
	var timeline = new iScroll('wrapper',{
		checkDOMChanges: true,
		scrollbarClass: 'scrollbar',
		vScroll: false,
		vScrollbar: false,
		onScrollEnd: function(){
			$('#current-view').css('left', -timeline.x/8);
		}
	});

	/* show mini map of the timeline */
	$('#timeline').minimap(timeline);

	/* show/hide event names */
	var events = $('.event');
	$('#options-container').find('.button').click(function(){
		var elem = $(this);
		var input = elem.next();
		var li = elem.parent();

		// check radio button
		input.attr("checked","checked");

		// change button
		li.siblings().find('.button').removeClass('selected');
		li.find('a').addClass('selected');

		// change events
		events.each(function(){
			var event = $(this);
			switch (input.val()) {
				case 'long':
					event.show().width(event.attr('data-width')).html(event.attr('data-title'));
					var id = event.attr('data-event');
					var obj = event.find('<h1>').hide();
					break;
				case 'short':
					event.show().width('').html('+');
					var id = event.attr('data-event');
					var obj = event.find('<h1>').show();
					break;
				case 'hidden':
					event.hide();
					break;
			}
		});
	});

	/* show event details */
	events.each(function(){
		var event = $(this);
		var id = event.attr('data-event');

		event.hovercard({
			detailsHTML: $('#event-' + id).html()
		});
	});
});
