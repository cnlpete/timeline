/* do some logging of debug messages, if given */
showDebugMsg = function(msgs){
  if(console && $.isArray(msgs)){
    $.each(msgs, function() {
      console.log(this);
    });
  }
}

/* refresh the list with some given data */
refreshItem = function(data){
  $('#hash-' + data.hash).html(list_item_template(data));
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

/* create a timeline */
createTimeline = function(timelinedata, success, error) {
  $.post(meta.url + '/admin/create.json', {'data' : timelinedata}, function(data) {
    if (data.result) {
      if ($.isFunction(success))
        success.call(data);
    }
    else {
      if ($.isFunction(error))
        error.call(data);
    }
  });
}

/* save the timeline */
saveTimeline = function(hash, timelinedata, success, error) {
  $.post(meta.url + '/admin/' + hash + '/update.json', {'data' : timelinedata}, function(data) {
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
getTimelines = function(callback) {
  $.getJSON(meta.url + '/admin.json', callback);
}

/* get the timeline */
getTimeline = function(hash, callback) {
  $.getJSON(meta.url + '/admin/' + hash + '.json', callback);
}

/* destroy the timeline */
destroyTimeline = function(hash, success, error) {
  $.getJSON(meta.url + '/admin/' + hash + '/destroy.json', function(data) {
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

/* get an asset */
getAsset = function(timelinehash, assethash, callback) {
  $.getJSON(meta.url + '/admin/' + timelinehash + '/' + assethash + '.json', callback);
}

/* save the asset */
saveAsset = function(timelinehash, assethash, assetdata, success, error) {
  $.post(meta.url + '/admin/' + timelinehash + '/' + assethash + '/update.json', {'data' : assetdata}, function(data) {
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

/* create an asset */
createAsset = function(timelinehash, assetdata, success, error) {
  $.post(meta.url + '/admin/' + timelinehash + '/create.json', {'data' : assetdata}, function(data) {
    if (data.result) {
      if ($.isFunction(success))
        success.call(data);
    }
    else {
      if ($.isFunction(error))
        error.call(data);
    }
  });
}

/* cleverly serialzie a form which can have lists inside :) */
cleverSerialize = function(selector) {
  selector = selector || '#myModal .modal-body form';
  var data = {};
  $.each($(selector).serializeArray(), function(index, item){
    if(item.name.substring(item.name.length-2) == "[]"){
      var x = item.name.substring(0,item.name.length-2);
      if(!data[x])
        data[x] = [];
      data[x].push({ 'key': $($(selector + ' input.js-' + x)[data[x].length]).data(x), 'value': item.value });
    }
    else
      data[item.name] = item.value;
  });
  return data;
}

$('#myModal').on('hidden', function () {
  // unbind the old click function
  $('#myModal #form-save').off('click');
})

$('#myModal').on('shown', function () {
  // bind all datepickers
  $('#myModal .js-yearpicker').datepicker({
    'weekStart':1, 
    'autoclose':true, 
    'startView':'decade', 
    'language':'de', 
    'format': 'yyyy'
  }).on('changeYear', function(e) {
    var dp = $(e.currentTarget).data('datepicker');
    dp.date = e.date;
    dp.setValue();
    dp.hide();
  });
  $('#myModal .js-monthpicker').datepicker({
    'weekStart':1, 
    'autoclose':true, 
    'startView':'decade', 
    'language':'de', 
    'format': 'yyyy-mm'
  }).on('changeMonth', function(e) {
    var dp = $(e.currentTarget).data('datepicker');
    dp.date = e.date;
    dp.setValue();
    dp.hide();
  });

  // jquery sortable
  var sortables = $('.js-sortable');
  if (sortables.length)
    sortables.sortable({'placeholder': 'ui-sortable-placeholder'});

  // the text editor
  var textarea = $('#form-text');
  if (textarea.length) {
    if ($('#wysihtml5-editor-toolbar').length)
      toolbar = "wysihtml5-editor-toolbar";
    else
      toolbar = false;
    var editor = new wysihtml5.Editor("form-text", {
      toolbar:     toolbar,
      parserRules: wysihtml5ParserRules,
      stylesheets: [path.css + "/wysihtml5.css"]
    });
  }

});
