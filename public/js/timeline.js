
/* a regular expression for detecting urls */
var urlpattern = /(http(s)?:\/\/([\w-]+\.)+[\w]{1,4}(\/[\w-\.,?=%#_]+)*\/?)/gi;

/* a persistant stateuid to check for backward and forward jumps */
var persistandStateUID = 1;

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

      $('.type-' + $(this).data('key') + ' .asset').each(function() {
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
      //do not scroll the title, since the popup hides this anyway
      //Title.scrollToPosition(newPos, title);

      // change details position
      // avoid that details move out of container
      newPos = Math.max(Math.min(newPos, $this.width() - details.width()), 0);
      if (!Event.isSticky($this))
        Event.fadeIn($this, newPos);
      else
        Event.scrollDetailContainer($this, newPos);

      $('#minimap-' + $this.data('hash')).addClass('hoveredAsset');

      //create new max of zIndices, so element will hover on top...
      var newZ = zIndices.last()+1;
      zIndices.push(newZ);
      $this.css("zIndex", newZ.toString());

      xE = title.offset().top + details.outerHeight();
      xB = scroller.height() + scroller.offset().top;
      if (xE >= xB) {
        //event is too big, we need to shift it up a bit ...
        details.css('top', xB-xE-10);
      }
    }
  },
  hoverOutFunction: function($this, e) {
    // change title position back
    //var title = $this.find('.title span').last();
    //do not scroll the title, since the popup hides this anyway
    //Title.scrollToPosition(0, title);

    var details = $this.find('.content');

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
    var maxHeight = scroller.height() - 100;
    jEvents.each(function(index) {
      var asset = $(this);
      var content = asset.find('.content');
      /* build video links */
      content.find('.js-url2video').each(function(e) {
        var $this = $(this);
        $.ajax({
          url: 'http://url2video.com/',
          data: { 'w': 300, 'h': 200, 'url' : encodeURI($this.attr('title')) },
          dataType: 'jsonp',
          success: function(data) { $this.html(data['html']); }
        });
      });

      /* build internal links */
      var a = content.find('a[href^="timeline:"]').click(function(e) {
        e.preventDefault();
        var hash = $(this).attr('href').substr(9);
        var event = $('#asset-' + hash);
        if (event.length) {
          if (history.pushState)
            if (!history.state || history.state.hash != asset.data('hash'))
              history.pushState({ 
                    'hash' : asset.data('hash'),
                    'stateuid' : persistandStateUID++
                }, 
                asset.find('.title span').last(), 
                window.location.protocol + '//' + window.location.hostname + window.location.pathname + '#' + asset.data('hash'));
          if (history.pushState)
            history.pushState({ 
                  'hash' : hash,
                  'stateuid' : persistandStateUID++
              }, 
              event.find('.title span').last(), 
              window.location.protocol + '//' + window.location.hostname + window.location.pathname + '#' + hash);
          Event.scrollTo(event);
          if (!Event.isSticky(event)) {
            Event.hoverInFunction(event, event, 0);
            Event.makeSticky(event);
          }
        }
        return false;
      });

      /* check the height */
      if (content.height() > 300) {
        content.width(400);
        if (content.height() > maxHeight) {
          content.width(610);
          if (content.height() > maxHeight)
            content.width(content.width() + 310);
        }
      }

      /* build links in sources */
      var source = $(this).find('.source');
      if (source.length)
        source.html(source.html().replace(urlpattern, '<a href="$1" class="extern" target="_blank"> $1 </a>').nl2br());
    });
  },
  scrollTo: function(event) {
    var newX = (timeline.x - event.offset().left - Math.round((event.width() - $(window).width()) / 2)) * -1;
    Timeline.scrollTo(newX);
  }
};

window.addEventListener("popstate", function(e) {
  var t = '';
  if (e.state.stateuid < persistandStateUID || e.state.stateuid == undefined) {
    t = e.state.hash;
  }
  else {
    t = window.location.hash.substr(1);
  }
  persistandStateUID = e.state.stateuid;

  var event = $('#asset-' + t);
  if (event.length) {
    Event.scrollTo(event);
    if (!Event.isSticky(event)) {
      Event.hoverInFunction(event, event, 0);
      Event.makeSticky(event);
    }
  }
});
