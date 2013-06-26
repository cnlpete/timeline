/* show div and hide, if positive delay si given */
function show(jDiv, iDelay) {
  jDiv.slideDown();

  if(iDelay > 0)
    hide(jDiv, iDelay);
}

/* Hide div after some delay */
function hide(jDiv, iDelay) {
  jDiv.delay(iDelay).slideUp();
}

/* Show success and error messages */
if($('#js-flash_message').length) {
  show($('#js-flash_message'), 10000);
}

/* make the flash message hide on click */
$('#js-flash_message .close').click(function(e) {
  e.preventDefault();
  hide($(this).closest('#js-flash_message'), 0);
});


/* call the login api */
login = function(logindata, success, error) {
  $.post(meta.url + '/login.json', logindata, function(data) {
    if (data) {
      if ($.isFunction(success))
        success.call();
    }
    else {
      if ($.isFunction(error))
        error.call();
    }
  });
}

/* call the login api */
logout = function(success, error) {
  $.post(meta.url + '/logout.json', function(data) {
    if (data) {
      if ($.isFunction(success))
        success.call();
    }
    else {
      if ($.isFunction(error))
        error.call();
    }
  });
}

supportsFullscreen = function() {
  var element = document.documentElement;
  return document.fullScreenEnabled || document.mozFullScreenEnabled || document.webkitFullScreenEnabled;
}

cancelFullscreen = function() {
  if(document.cancelFullScreen) {
    document.cancelFullScreen();
  } else if(document.mozCancelFullScreen) {
    document.mozCancelFullScreen();
  } else if(document.webkitCancelFullScreen) {
    document.webkitCancelFullScreen();
  }
}

launchFullScreen = function() {
  var element = document.documentElement;
  if(element.requestFullScreen) {
    element.requestFullScreen();
  } else if(element.mozRequestFullScreen) {
    element.mozRequestFullScreen();
  } else if(element.webkitRequestFullScreen) {
    element.webkitRequestFullScreen();
  }
}

toggleFullscreen = function() {
  var fullscreenEnabled = document.fullScreen || document.mozFullScreen || document.webkitIsFullScreen;
  if (fullscreenEnabled)
    cancelFullscreen();
  else
    launchFullScreen();
}
