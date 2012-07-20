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

<script id="list-template" type="text/x-handlebars-template">
{literal}
  {{#each entries}}
    <tr class="asset">
      <td class="table-title">{{title}}</td>
      <td class="table-date">{{date}}</td>
      <td class="table-options">
        <a class="btn" href="#show-{{hash}}"><i class="icon-search"></i></a>
        <a class="btn" href="#edit-{{hash}}"><i class="icon-wrench"></i></a>
        <a class="btn btn-danger" href="#delete-{{hash}}"><i class="icon-trash"></i></a>
      </td>
    </tr>
  {{/each}}
{/literal}
</script>

<script id="edit-timeline-template" type="text/x-handlebars-template">
{literal}
  <form class="form-horizontal">
    <fieldset>
      <legend>{/literal}{$timeline.startDate}{literal}</legend>
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

<script src="{$path.js}/admin.timeline.js"></script>
<script type="text/javascript">
  var list_template = Handlebars.compile($("#list-template").html());

  // enable the refresh button
  $('#nav-update').click(function() {
    $.getJSON('/admin/{$hash}.json', function(data) { 
      refreshList($('#eventlist'), data.assets);
      refreshInfo(data.timeline);
      if (data.debug)
        showDebugMsg(data.debug);
    });
  });
  
  // initialize
  refreshList($('#eventlist'), {$assets_json});
  
  // the timeline delete button
  $('#nav-delete').click(function() {
    if (confirm('{$title|string_format:$lang.confirm.timeline}')) {
      $('#nav-delete').hide();
      $.getJSON('/admin/{$hash}/destroy.json', function(data) {
        if (data.result) {
          parent.location.href = '/admin.html';
        }
        else {
          // TODO proper error message
          alert('{$title|string_format:$lang.admin.error.timeline_not_destroyed}');
          $('#nav-delete').show();
        }
      });
    }
  });
</script>
