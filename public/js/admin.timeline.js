
/* do some logging of debug messages, if given */
showDebugMsg = function(msgs){
  if(console && $.isArray(msgs)){
    $.each(msgs, function() {
      console.log(this);
    });
  }
}

/* refresh the list with some given data */
refreshList = function(list, data){
  var tBody = $('tbody', list);
  tBody.fadeOut('fast', function() {
    $('tr', tBody).remove();
    tBody.append(list_template({'entries':data}));
    tBody.fadeIn('fast');
  });
}

/* refresh the list with some given data */
refreshInfo = function(data){
  var oldTitle = $('a.brand').html().trim();
  $('a.brand').html(data.title);
  var jTitle = $('title');
  jTitle.html(jTitle.html().replace(oldTitle, data.title));
  
  $('#timelinedata .description').html(data.description);
  $('#timelinedata .language').html(data.language);
  $('#timelinedata .js-startDate').html(data.startDate);
  $('#timelinedata .js-endDate').html(data.endDate);
}
