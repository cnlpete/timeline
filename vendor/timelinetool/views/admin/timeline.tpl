<section id='admin-timeline'>

  <link href="{$path.css}/admin.css" rel="stylesheet" />

{include file='../_colorclasses.tpl' selectors=[' div', '.colorful']}

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
        <th class="type-string" style="width: 30px;"></th>
        <th class="type-string">{$lang.admin.timeline.assets.title}</th>
        <th class="type-int">{$lang.admin.timeline.assets.date}</th>
        <th width='120px'><a class="js-create btn" href="#create"><i class="icon-plus"></i></a></th>
      </tr>
    </thead>
    <tbody>
    </tbody>
  </table>

</section>

<script id="list-template" type="text/x-handlebars-template">
{literal}
  {{#each entries}}
    <tr class="asset" id="hash-{{hash}}" data-hash='{{hash}}'>
      {{> list-item}}
    </tr>
  {{/each}}
{/literal}
</script>
<script id="list-item-template" type="text/x-handlebars-template">
{literal}
  <td class="type-{{type}}" style="text-align: center;" data-order-by="{{type}}">
    <div style="width: 20px; height: 20px; display: inline-block"></div></td>
  <td class="table-title">{{title}}</td>
  <td class="table-date" data-order-by="{{startDate}}">{{startDate}} - {{endDate}}</td>
  <td class="table-options">
    <a class="js-play btn" href="{/literal}{$meta.url}/{$hash}{literal}.html#{{hash}}"><i class="icon-play-circle"></i></a>
    <a class="js-edit btn" href="#edit-{{hash}}"><i class="icon-wrench"></i></a>
    <a class="js-destroy btn btn-danger" href="#delete-{{hash}}"><i class="icon-trash"></i></a>
  </td>
{/literal}
</script>

{include file='_timeline.form.template.tpl'}

{include file='_asset.form.template.tpl'}

<script src="{$path.js}/wysihtml5.parser_rules.advanced.js" ></script>
<script src="{$path.js}/wysihtml5.min.js" ></script>
<script src="{$path.js}/jquery.stupidtable.js"></script>
<script src="{$path.js}/jquery-ui.custom.min.js"></script>
<script src="{$path.js}/bootstrap-datepicker.js"></script>
<script src="{$path.js}/bootstrap-datepicker.de.js"></script>
<script src="{$path.js}/admin.timeline.js"></script>
<script type="text/javascript">
  Handlebars.registerPartial("list-item", $("#list-item-template").html());
  var list_template           = Handlebars.compile($("#list-template").html());
  var list_item_template      = Handlebars.compile($("#list-item-template").html());
  var timeline_form_template  = Handlebars.compile($("#timeline-form-template").html());
  var asset_form_template     = Handlebars.compile($("#asset-form-template").html());

  $('#eventlist').stupidtable();

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
      $('#myModal .modal-body').html(timeline_form_template(data.timeline));
      $('#myModal .modal-header h3').html('{$title|string_format:$lang.admin.timeline.update.header}');
      $('#myModal #form-save').click(function() {
        // get the data
        var data = cleverSerialize('#myModal .modal-body form');
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
    if (confirm(sprintf('{$lang.confirm.asset}', asset.find('.table-title').html()))) {
      destroyButton.hide();
      $.getJSON('/admin/{$hash}/' + assetHash + '/destroy.json', function(data) {
        if (data.result) {
          asset.fadeOut(function() { asset.remove(); });
        }
        else {
          // TODO proper error message
          alert(sprintf('{$lang.admin.error.asset_not_destroyed}', asset.find('.table-title').html()));
          destroyButton.show();
        }
      });
    }
  });

  // the assets update buttons
  $('#eventlist').on('click', 'a.js-edit', function() {
    var updateButton = $(this);
    var asset = updateButton.closest('tr.asset');
    var assetHash = asset.data('hash');
    // reload the data, just to be shure
    getAsset('{$hash}', assetHash, function(data) {
      refreshItem(data);
      data['types'] = {$types};
      data['texttypes'] = ['text', 'quote', 'image', 'video'];
      $('#myModal .modal-body').html(asset_form_template(data));
      $('#myModal .modal-header h3').html('{$lang.admin.timeline.assets.update.header}');
      $('#myModal #form-save').click(function() {
        // get the data
        var data = {};
        $.each($('#myModal .modal-body form').serializeArray(), function(index, item){
            data[item.name] = item.value;
        });
        // send to server
        saveAsset('{$hash}', assetHash, data, function() {
          $('#myModal').modal('hide');
          data.hash = assetHash;
          refreshItem(data);
        });
      });
      $('#myModal').modal( { 'backdrop':'static' } );
    });
  });

  // the assets create buttons
  $('#eventlist').on('click', 'a.js-create', function() {
    var data = {};
    data['types'] = {$types};
    data['texttypes'] = ['text', 'quote', 'image', 'video'];
    $('#myModal .modal-body').html(asset_form_template(data));
    $('#myModal .modal-header h3').html('{$lang.admin.timeline.assets.create.header}');
    $('#myModal #form-save').click(function() {
      // get the data
      var data = {};
      $.each($('#myModal .modal-body form').serializeArray(), function(index, item){
          data[item.name] = item.value;
      });
      // send to server
      createAsset('{$hash}', data, function() {
        $('#myModal').modal('hide');
        data.hash = this.hash;
        $('#eventlist tbody').append(list_template( { 'entries':[data] } ));
      });
    });
    $('#myModal').modal( { 'backdrop':'static' } );
  });
</script>
