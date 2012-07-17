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

