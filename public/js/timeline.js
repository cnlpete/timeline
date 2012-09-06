
/* a regular expression for detecting urls */
var urlpattern = /(http(s)?:\/\/([\w-]+\.)+[\w]{1,4}(\/[\w-\.,?=%#_]+)*\/?)/gi;

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

String.prototype.nl2br = function() {
  var text = escape(this);
  var re_nlchar = null;

  if(text.indexOf('%0D%0A') > -1) {
    re_nlchar = /%0D%0A/g ;
  }
  else if(text.indexOf('%0A') > -1) {
    re_nlchar = /%0A/g ;
  }
  else if(text.indexOf('%0D') > -1) {
    re_nlchar = /%0D/g ;
  }

  text = (re_nlchar != null) ? unescape(text.replace(re_nlchar,'<br />')) : unescape(text);
  return text
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
      - $('#options').outerHeight(true);
  $('#scroller').css('height', (height - 15) + 'px');
  $('#wrapper').css('height', (height) + 'px');
  $('#timeline').css('height', height + 'px');
};

Timeline = {
  scrollTo: function(x) {
    timeline.scrollTo(x*-1, 300);
  }
};

Options = {
  enableCCButtons: function() {
    $('#colorclasses').on('click', 'li', function(e){
      var button = $(this);
      button.toggleClass('selected');
      var flag = button.hasClass('selected');

      $('.type-' + $(this).text() + ' .asset').each(function() {
        if (flag)
          Event.showEvent($(this));
        else
          Event.hideEvent($(this));
      });
    });
  }
}

var zIndices = [1];
var scroller = $('#scroller');
Event = {
  hoverInFunction: function($this, e, timelineOffset){
    var details = $this.find('.content');
    var title = $this.find('.title span').last();
    var newPos = e ? e.clientX - $this.offset().left - (title.width() * 0.5) : 0;

    // check for content
    if (details.text().length) {
      // center title under mouse position
      Title.scrollToPosition(newPos, title);

      // change details position
      // avoid that details move out of container
      newPos = Math.max(Math.min(newPos, $this.width() - details.width()), 0);
      if (!Event.isSticky($this))
        Event.fadeIn($this, newPos);
      else
        Event.scrollDetailContainer($this, newPos);

      $('#minimap-' + $this.data('hash')).addClass('hoveredAsset');

      //create new max of zIndices, so element will hover on top...
      zIndices.push(zIndices.last()+1);
      $this.css("zIndex", zIndices.last().toString());

      xE = details.offset().top + details.outerHeight();
      xB = scroller.height() + scroller.offset().top;
      if (xE >= xB) {
        //event is too big, we need to shift it up a bit ...
        //FIXME, this doese not yet work with the new div approach
        details.css('bottom', 0).css('position', 'relative');
        details.parent().css('bottom', 0).css('top', '');
      }
    }
  },
  hoverOutFunction: function($this, e) {
    // change title position back
    var title = $this.find('.title span').last();
    Title.scrollToPosition(0, title);

    var details = $this.find('.content');
    details.css('bottom', '').css('position', '');
    details.parent().css('bottom', '');

    $('#minimap-' + $this.data('hash')).removeClass('hoveredAsset');

    if (!Event.isSticky($this))
      Event.fadeOut($this);
  },
  fadeOut: function(event) {
    var details = event.find('.content');
    details.stop(true, true).animate({ opacity: 0 }, function() {
      details.css('display', 'none')
      //remove self from list
      zIndices.rmElem(parseInt(event.css("zIndex")));
      event.css("zIndex", "1");
    });
  },
  fadeIn: function(event, newPos) {
    var details = event.find('.content');
    details.stop(true, true).css('display', 'inline-block').animate({ opacity: 1, left: newPos + 'px' });
  },
  hideEvent: function(event) {
    if (Event.isSticky(event))
      Event.removeSticky(event);
    event.fadeOut();
    $('#minimap-' + event.data('hash')).addClass('hiddenAsset');
  },
  showEvent: function(event) {
    event.fadeIn();
    $('#minimap-' + event.data('hash')).removeClass('hiddenAsset');
  },
  scrollDetailContainer: function(event, newPos) {
    var details = event.find('.content');
    details.stop(true, true).animate({ left: newPos + 'px' });
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
    $('#minimap-' + event.data('hash')).addClass('stickyAsset');
  },
  removeSticky: function(event) {
    event.removeClass('sticky');
    $('#minimap-' + event.data('hash')).removeClass('stickyAsset');
  },
  buildContent: function(jEvents) {
    jEvents.each(function(index) {
      var content = $(this).find('.content');

      var source = $(this).find('.source');
      source.html(source.html().replace(urlpattern, '<a href="$1" class="extern" target="_blank"> $1 </a>').nl2br());

    });
  },
  scrollTo: function(event) {
    Timeline.scrollTo(event.offset().left + Math.round(event.width()/2) - Math.round($(window).width()/2));
  }
};
