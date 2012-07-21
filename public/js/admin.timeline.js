
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

/* save the timeline */
saveTimeline = function(hash, timelinedata, success, error) {
  $.post('/admin/' + hash + '/update.json', {'data' : timelinedata}, function(data) {
    if (data.result) {
      if ($.isFunction(success))
        success.call();
    }
    else {
      if ($.isFunction(error))
        error.call();
    }
  });
}

/* get the timeline */
getTimeline = function(hash, callback) {
  $.getJSON('/admin/' + hash + '.json', callback);
}

/* destroy the timeline */
destroyTimeline = function(hash, success, error) {
  $.getJSON('/admin/' + hash + '/destroy.json', function(data) {
    if (data.result) {
      if ($.isFunction(success))
        success.call();
      parent.location.href = '/admin.html';
    }
    else {
      if ($.isFunction(error))
        error.call();
    }
  });
}




$('#myModal').on('hidden', function () {
  // unbind the old click function
  $('#myModal #form-save').off('click');
})

$('#myModal').on('shown', function () {
  // bind all datepickers
  $('#myModal .js-datepicker').datepicker( { 'weekStart':1, 'autoclose':true, 'startView':'decade', 'language':'de' } );
})
