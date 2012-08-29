/* set wrapper height to current browser viewport */
setWrapperHeight = function() {
  var scroller = $('#scroller');
  var wrapper = $('#wrapper');
  var full_width = $(window).width();
  var full_height = $(window).height();
  scroller.css('height', full_height - parseInt(wrapper.css('top')) - 70 + 'px');
};

