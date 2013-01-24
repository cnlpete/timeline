<section id='admin-timeline-overview'>

  <link href="{$path.css}/admin.css" rel="stylesheet" />

{include file='../_colorclasses.tpl' selectors=['.colorful']}

  <h2>{$lang.admin.timeline.timeline_list}</h2>
  <!-- all events -->
  <table id='eventlist' class="table table-bordered">
    <thead>
      <tr>
        <th class="type-string">{$lang.admin.timeline.title}</th>
        <th>{$lang.admin.timeline.description}</th>
        <th class="type-string">{$lang.admin.timeline.date}</th>
        <th width='180px'>
          {if $user.has_admin_right}
            <a class="js-create btn" 
                href="#create"
                title="{$lang.admin.timeline.create.alt}">
              <i class="icon-plus"></i>
            </a>
          {/if}
        </th>
      </tr>
    </thead>
    <tbody>
    </tbody>
  </table>

</section>

<script id="list-template" type="text/x-handlebars-template">
{literal}
  {{#each entries}}
    <tr class="timeline" id="hash-{{hash}}" data-hash='{{hash}}'>
      {{> list-item}}
    </tr>
  {{/each}}
{/literal}
</script>
<script id="list-item-template" type="text/x-handlebars-template">
{literal}
  <td class="table-title">{{title}}</td>
  <td class="table-description">{{description}}</td>
  <td class="table-date">{{startDate}} - {{endDate}}</td>
  <td class="table-options">
    <a class="js-play btn" 
        href="{/literal}{$meta.url}{literal}/{{hash}}.html"
        title="{/literal}{$lang.admin.timeline.play.alt}{literal}">
      <i class="icon-play-circle"></i>
    </a>
    <a class="js-show btn" 
        href="{/literal}{$meta.url}{literal}/admin/{{hash}}.html"
        title="{/literal}{$lang.admin.timeline.show.alt}{literal}">
      <i class="icon-eye-open"></i>
    </a>
    <a class="js-edit btn" 
        href="#edit-{{hash}}"
        title="{/literal}{$lang.admin.timeline.update.alt}{literal}">
      <i class="icon-wrench"></i>
    </a>
    <a class="js-destroy btn btn-danger"
        href="#delete-{{hash}}"
        title="{/literal}{$lang.admin.timeline.destroy.alt}{literal}">
      <i class="icon-trash"></i>
    </a>
  </td>
{/literal}
</script>

{include file='_timeline.form.template.tpl'}

<script src="{$path.js}/jquery.stupidtable.js"></script>
<script src="{$path.js}/jquery-ui.custom.min.js"></script>
<script src="{$path.js}/bootstrap-datepicker.js"></script>
<script src="{$path.js}/bootstrap-datepicker.de.js"></script>
<script src="{$path.js}/admin.timeline.js"></script>
{if $user.has_admin_right}
  {include file='_admins.permissions.tpl'}
{/if}
<script type="text/javascript">
  Handlebars.registerPartial("list-item", $("#list-item-template").html());
  var list_template           = Handlebars.compile($("#list-template").html());
  var list_item_template      = Handlebars.compile($("#list-item-template").html());
  var timeline_form_template  = Handlebars.compile($("#timeline-form-template").html());

  $('#eventlist').stupidtable();

  // enable the refresh button
  $('#nav-update').click(function() {
    getTimelines(function(data) { 
      refreshList($('#eventlist'), data.timelines);
    });
  });
  
  // initialize the event list
  refreshList($('#eventlist'), {$timelines_json});

  // the delete buttons
  $('#eventlist').on('click', 'a.js-destroy', function() {
    var destroyButton = $(this);
    var timeline = destroyButton.closest('tr.timeline');
    var timelineHash = timeline.data('hash');

    if (confirm(sprintf('{$lang.confirm.timeline}', timeline.find('.table-title').html()))) {
      destroyButton.hide();
      destroyTimeline(timelineHash, function() {
        timeline.fadeOut(function() { timeline.remove(); });
      }, function() {
        // TODO proper error message
        alert(sprintf('{$lang.admin.error.timeline_not_destroyed}', timelinehash));
        destroyButton.show();
      });
    }
  });

  // the assets update buttons
  $('#eventlist').on('click', 'a.js-edit', function() {
    var updateButton = $(this);
    var timeline = updateButton.closest('tr.timeline');
    var timelineHash = timeline.data('hash');
    // reload the data, just to be shure
    getTimeline(timelineHash, function(data) {
      refreshItem(data.timeline);
      $('#myModal .modal-body').html(timeline_form_template(data.timeline));
      $('#myModal .modal-header h3').html(sprintf('{$lang.admin.timeline.update.header}', timeline.find('.table-title').html()));
      $('#myModal #form-save').click(function() {
        // get the data
        var data = cleverSerialize('#myModal .modal-body form');
        // send to server
        saveTimeline(timelineHash, data, function() {
          $('#myModal').modal('hide');
          data.hash = timelineHash;
          refreshItem(data);
        });
      });
      $('#myModal').modal( { 'backdrop':'static' } );
    });
  });

  // the timeline create buttons
  $('#eventlist').on('click', 'a.js-create', function() {
    $('#myModal .modal-body').html(timeline_form_template({}));
    $('#myModal .modal-header h3').html('{$lang.admin.timeline.create.header}');
    $('#myModal #form-save').click(function() {
      // get the data
      var data = cleverSerialize('#myModal .modal-body form');
      // send to server
      createTimeline(data, function() {
        $('#myModal').modal('hide');
        data.hash = this.hash;
        $('#eventlist tbody').append(list_template( { 'entries':[data] } ));
      });
    });
    $('#myModal').modal( { 'backdrop':'static' } );
  });
</script>
