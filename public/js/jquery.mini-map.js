/* 
jQuery Mini-Map plugin copyright Sam Croft <samcroft@gmail.com>
extended for timeline use copyright Lars Borchert <borchert.lars@gmail.com> and Hauke Schade <cnlpete@cnlpete.de>
Licensed like jQuery - http://docs.jquery.com/License
*/
(function($){
	$.fn.minimap = function(timeline, size, minfactor){
		if (!size) {
			size = 500;
		}
		if (!minfactor) {
			minfactor = 4;
		}

		var el = this;
		var years = el.find('.dates .date');
		var events = el.find('.asset');
		var clicked = false;
		var miniMap = $('#mini-map');
		var miniMapCurrentView = $('#current-view', miniMap);
		var topOffset = years.first().offset().top;
		
		//factor-calc:
		var factor = Math.max(minfactor, Math.ceil(el.width()/size));
		timeline.options.onScrollEnd = function(d){
			miniMapCurrentView.css('left', -timeline.x/factor);
		};
		timeline.options.onScrollMove = function(d){
			miniMapCurrentView.css('left', -timeline.x/factor);
		}

		var topOffset = years.first().offset().top;

		// show every 5th year
		years.each(function(i,t) {
			if (i % 5 == 0) {
				var year = $(this);
				var yearCoords = year.offset();

				var mapIcon = $('<div>' + year.text() + '</div>');
				mapIcon
					.css({
						'width': 18, 
						'left': Math.round(yearCoords.left/factor)
					})
					.addClass(t.tagName.toLowerCase())
					.appendTo(miniMap);
			}
		});

		// show events
		var height = 0;
		events.each(function(i,t) {
			var event = $(this);
			var eventCoords = event.offset();

			var mapIcon = $('<div>');
			mapIcon
				.attr('id', 'minimap-'+event.data('hash'))
				.css({
					'height': Math.round(event.height()/factor), 
					'width': Math.round(event.width()/factor), 
					'left': Math.round(eventCoords.left/factor),
					'top': Math.round((eventCoords.top - topOffset)/factor) + 16
				})
				.addClass('asset')
				.appendTo(miniMap);
			var tmp = Math.round((eventCoords.top - topOffset)/factor) + 16 + Math.round(event.height()/factor);
			if (tmp > height) height = tmp;
		});

		// minimap size
		var width = Math.round(el.width()/factor);
		miniMap.height(height + 5);
		miniMap.width(width);
		$('#map-container').width(width);
		miniMapOffsetLeft = miniMap.offset().left;

		// size of current view
		var miniMapCurrentViewCenter = Math.round($(window).width() / factor);
		miniMapCurrentView.height(height + 5);
		miniMapCurrentView.width(miniMapCurrentViewCenter);
		miniMapCurrentViewCenter /= 2;

		miniMapCurrentView.mousedown(function(){
			clicked = true;
			miniMapCurrentView.css({'cursor': 'ew-resize'});
		});

		miniMap.mouseup(function(e){
			clicked = false;
			miniMapCurrentView.css('cursor', 'pointer');

			var newPosition = e.pageX - miniMapCurrentViewCenter - miniMapOffsetLeft;
			timeline.scrollTo(newPosition * factor * -1, 0, 200);
		});

		miniMapCurrentView.mousemove(function(e){
			if (clicked) {
				var newPosition = e.pageX - miniMapCurrentViewCenter - miniMapOffsetLeft;
				miniMapCurrentView.css({'left': newPosition});
				timeline.scrollTo(newPosition * factor * -1, 0, 200);
			}
		});
	};

})(jQuery);
