/* Array functions to sort, removeObject and getLast */
Array.prototype.sortNum = function() {
  return this.sort( function (a,b) { return a-b; } );
}
Array.prototype.rmElem = function(a) {
  var index = this.indexOf(a);
  if (index >= 0) {
    this.splice(index, 1);
    return true;
  }
  else
    return false;
}
Array.prototype.last = function() {
  var length = this.length;
  if (length > 0)
   return this[length-1];
  else
    return 10; //start with 10, if no element is hovered yet
}

Title = {
  scrollToPosition: function(x, title, buffer) {
    if (!buffer)
      buffer = 60;

    var parentWidth = title.parent().width();
    var titleWidth =  title.width();

    // change title position
    // small buffer to avoid that all event titles move
    if (parentWidth > titleWidth + buffer) {
      // avoid that title move out of container
      x = Math.max(Math.min(x, parentWidth - titleWidth), 0);

      title.animate({ left: x + 'px' }, { queue: false });
      title.data('titlemoved', x);
    }
  }
}

/* set wrapper height to current browser viewport */
setWrapperHeight = function() {
  var wrapper = $('#wrapper');
  var full_height = $(window).height();
  var height = full_height - 10
      - parseInt(wrapper.css('top')) 
      - $('#options').outerHeight(true) 
      - $('footer').siblings().last().outerHeight(true)
      - $('footer').outerHeight(true);
  $('#scroller').css('height', (height - 10) + 'px');
  $('#wrapper').css('height', (height + 5) + 'px');
  $('#timeline').css('height', height + 'px');
};

var zIndices = [1];
Event = {
  hoverInFunction: function($this, e, timelineOffset){
    var details = $this.find('.content');
    var title = $this.find('.title span').first();
    var newPos = e ? e.clientX - $this.offset().left - (title.width() * 0.5) : 0;

    // check for content
    if (details.text().length) {
      // center title under mouse position
      Title.scrollToPosition(newPos, title);

      // change details position
      // avoid that details move out of container
      newPos = Math.max(Math.min(newPos, $this.width() - details.width()), 0);
      details.css('display', 'inline-block')
      details.stop(true, true).animate({ opacity: 1, left: newPos + 'px' });

      //create new max of zIndices, so element will hover on top...
      zIndices.push(zIndices.last()+1);
      $this.css("zIndex", zIndices.last().toString());

      xE = details.offset().top + details.outerHeight();
      xB = $(window).height() - $('#options').outerHeight();
      if (xE >= xB) {
        //event is too big, we need to shift it up a bit ...
        details.css('bottom', 0).css('position', 'relative');
        details.parent().css('bottom', 0).css('top', '');
      }
    }
  },
  hoverOutFunction: function($this, e) {
    // change title position back
    var title = $this.find('.title span').first();
    Title.scrollToPosition(0, title);

    var details = $this.find('.content');
    details.css('bottom', '').css('position', '');
    details.parent().css('bottom', '');

    details.stop(true, true).animate({ opacity: 0 }, function() {
      details.css('display', 'none')
      //remove self from list
      zIndices.rmElem(parseInt($this.css("zIndex")));
      $this.css("zIndex", "1");
    });
  },
  click: function($this, e) {
    if (Event.isSticky($this))
      Event.removeSticky($this);
    else
      Event.makeSticky($this);
  },
  isSticky: function(event) {
    return event.hasClass('sticky');
  },
  makeSticky: function(event) {
    event.addClass('sticky');
  },
  removeSticky: function(event) {
    event.removeClass('sticky');
  }
};
