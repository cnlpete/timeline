<section id='admin-timeline'>

  <!-- the timeline info -->
  <div id='timelinedata' class="row-fluid">
    <div class="span6">
      <p>{$lang.admin.timeline.date}: <span class='js-startDate'>{$timeline.startDate}</span> - 
        <span class='js-endDate'>{$timeline.endDate}</span></p>
      <p>{$lang.admin.timeline.language}: <span class='language'>{$timeline.language}</span></p>
    </div>
    <div class="span6">
      <h3>{$lang.admin.timeline.description}</h3>
      <div class='description'>{$timeline.description}</div>
    </div>
  </div>

  <h2>{$lang.admin.timeline.asset_list}</h2>
  <!-- all events -->
  <table id='eventlist' class="table table-bordered">
    <thead>
      <tr>
        <td>{$lang.admin.timeline.assets.title}</td>
        <td>{$lang.admin.timeline.assets.date}</td>
        <td width='120px'>{$lang.admin.timeline.assets.options}</td>
      </tr>
    </thead>
    <tbody>
    </tbody>
  </table>

</section>

<div class="modal hide" id="myModal">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h3>Modal header</h3>
  </div>
  <div class="modal-body">
    <p>One fine body…</p>
  </div>
  <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal">Close</a>
    <a href="#" class="btn btn-primary" id="form-save">Save changes</a>
  </div>
</div>

<script id="list-template" type="text/x-handlebars-template">
{literal}
  {{#each entries}}
    <tr class="asset" data-hash='{{hash}}'>
      <td class="table-title">{{title}}</td>
      <td class="table-date">{{date}}</td>
      <td class="table-options">
        <a class="btn" href="#show-{{hash}}"><i class="icon-search"></i></a>
        <a class="btn" href="#edit-{{hash}}"><i class="icon-wrench"></i></a>
        <a class="btn btn-danger js-destroy" href="#delete-{{hash}}"><i class="icon-trash"></i></a>
      </td>
    </tr>
  {{/each}}
{/literal}
</script>

<script id="timeline-edit-template" type="text/x-handlebars-template">
{literal}
  <form class="form-horizontal">
    <fieldset>
      <div class="control-group">
        <label class="control-label" for="form-language">{/literal}{$lang.admin.timeline.title}{literal}</label>
        <div class="controls">
          <input type="text" class="input-xlarge" name="title" id="form-title" value="{{title}}">
        </div>
      </div>
    </fieldset>
    <fieldset>
      <div class="control-group">
        <label class="control-label">{/literal}{$lang.admin.timeline.date}{literal}</label>
        <div class="controls">
          <input class="span1 js-datepicker" name="startDate" id="form-startDate" size="16" type="text" value="{{startDate}}" data-date-format="yyyy-mm-dd" readonly>
          -
          <input class="span1 js-datepicker" name="endDate" id="form-endDate" size="16" type="text" value="{{endDate}}" data-date-format="yyyy-mm-dd" readonly>
        </div>
      </div>
    </fieldset>
    <fieldset>
      <div class="control-group">
        <label class="control-label" for="form-language">{/literal}{$lang.admin.timeline.language}{literal}</label>
        <div class="controls">
          <input type="text" class="input-xlarge" name="language" id="form-language" value="{{language}}">
        </div>
      </div>
    </fieldset>
    <fieldset>
      <div class="control-group">
        <label class="control-label" for="form-language">{/literal}{$lang.admin.timeline.description}{literal}</label>
        <div class="controls">
          <input type="text" class="input-xlarge" name="description" id="form-description" value="{{description}}">
        </div>
      </div>
    </fieldset>
  </form>
{/literal}
</script>

<script id="asset-edit-template" type="text/x-handlebars-template">
{literal}
  <form class="form-horizontal">
    <fieldset>
      <div class="control-group">
        <label class="control-label" for="form-startDate">{/literal}{$timeline.startDate}{literal}</label>
        <div class="controls">
          <input type="text" class="input-xlarge" id="form-startDate">
        </div>
      </div>
    </fieldset>
  </form>
{/literal}
</script>

<script src="{$path.js}/bootstrap-datepicker.js"></script>
<script src="{$path.js}/bootstrap-datepicker.de.js"></script>
<script src="{$path.js}/admin.timeline.js"></script>
<script type="text/javascript">
  var list_template           = Handlebars.compile($("#list-template").html());
  var timeline_edit_template  = Handlebars.compile($("#timeline-edit-template").html());
  var asset_edit_template     = Handlebars.compile($("#asset-edit-template").html());

  // enable the refresh button
  $('#nav-update').click(function() {
    getTimeline('{$hash}', function(data) { 
      refreshList($('#eventlist'), data.assets);
      refreshInfo(data.timeline);
      if (data.debug)
        showDebugMsg(data.debug);
    });
  });
  
  // initialize the event list
  refreshList($('#eventlist'), {$assets_json});
  
  // the timeline delete button
  $('#nav-delete').click(function() {
    if (confirm('{$title|string_format:$lang.confirm.timeline}')) {
      $('#nav-delete').hide();
      destroyTimeline('{$hash}', null, function() {
        // TODO proper error message
        alert('{$title|string_format:$lang.admin.error.timeline_not_destroyed}');
        $('#nav-delete').show();
      });
    }
  });
  
  // the assets update buttons
  $('#nav-edit').click(function() {
    var updateButton = $(this);
    // reload the data, just to be shure
    getTimeline('{$hash}', function(data) {
      refreshList($('#eventlist'), data.assets);
      refreshInfo(data.timeline);
      $('#myModal .modal-body').html(timeline_edit_template(data.timeline));
      $('#myModal .modal-header h3').html('{$title|string_format:$lang.admin.timeline.update.header}');
      $('#myModal #form-save').click(function() {
        // get the data
        var data = {};
        $.each($('#myModal .modal-body form').serializeArray(), function(index, item){
            data[item.name] = item.value;
        });
        // send to server
        saveTimeline('{$hash}', data, function() {
          $('#myModal').modal('hide');
          refreshInfo(data);
        });
      });
      $('#myModal').modal( { 'backdrop':'static' } );
    });
  });

  // the assets delete buttons
  $('#eventlist').on('click', 'a.js-destroy', function() {
    var destroyButton = $(this);
    var asset = destroyButton.closest('tr.asset');
    var assetHash = asset.data('hash');
    if (confirm('{$lang.confirm.asset}')) {
      destroyButton.hide();
      $.getJSON('/admin/{$hash}/' + assetHash + '/destroy.json', function(data) {
        if (data.result) {
          asset.fadeOut(function() { asset.remove(); });
        }
        else {
          // TODO proper error message
          alert('{$lang.admin.error.asset_not_destroyed}');
          destroyButton.show();
        }
      });
    }
  });

  // the assets update buttons
  $('#eventlist').on('click', 'a.js-update', function() {
    var updateButton = $(this);
    var asset = updateButton.closest('tr.asset');
    var assetHash = asset.data('hash');
    // show modal form
    //$.getJSON('/admin/{$hash}/' + assetHash + '/update.json', data, function(data) {
      
    //  if (data.result) {
    //    asset.fadeOut(function() { /* TODO update data */ });
    //  }
    //  else {
        // TODO proper error message
    //    alert('{$lang.admin.error.asset_not_updated}');
    //  }
    //});
  });
</script>
