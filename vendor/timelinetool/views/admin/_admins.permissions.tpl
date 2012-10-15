<div class="modal hide" id="myAdminPermissionModal">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" title="{$lang.confirm.modal.close}">×</button>
    <h3>{$lang.admin.timeline.permissions.admins.header}</h3>
  </div>
  <div class="modal-body">
    <table id='adminlist' class="table table-bordered">
      <thead>
        <tr>
          <th class="type-string">{$lang.admin.timeline.permissions.admins.user}</th>
          <th width='40px'>
            {if $user.has_admin_right}
              <a class="js-create btn" 
                  href="#create"
                  title="{$lang.admin.timeline.permissions.admins.create.alt}">
                <i class="icon-plus"></i>
              </a>
            {/if}
          </th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
  </div>
  <div class="modal-footer">
    {if $user.has_admin_right}
      <a class="js-create btn btn-primary" 
          href="#create"
          title="{$lang.admin.timeline.permissions.admins.create.alt}">
        <i class="icon-plus"></i> {$lang.admin.timeline.permissions.admins.create.label}
      </a>
    {/if}
    <a href="#" class="btn" data-dismiss="modal">{$lang.confirm.modal.close}</a>
  </div>
</div>

<script id="permissions-list-template" type="text/x-handlebars-template">
  {literal}{{#each admins}}{/literal}
    <tr class="user" 
        id="user-{literal}{{this}}{/literal}" 
        data-user='{literal}{{this}}{/literal}'>
      <td class="table-user">
        {literal}{{this}}{/literal}
      </td>
      <td class="table-options">
        {if $user.has_admin_right}
          <a class="js-destroy btn btn-danger" 
              href="#delete-{literal}{{this}}{/literal}"
              title="{$lang.admin.timeline.permissions.admins.destroy.alt}">
            <i class="icon-trash"></i>
          </a>
        {/if}
      </td>
    </tr>
  {literal}{{/each}}{/literal}
</script>

<script type="text/javascript">
  var permissions_list_template = Handlebars.compile($("#permissions-list-template").html());

  $('#adminlist').stupidtable();

  refreshAdminList = function(data){
    var tBody = $('tbody', $('#adminlist'));
    tBody.fadeOut('fast', function() {
      $('tr', tBody).remove();
      tBody.append(permissions_list_template( { 'admins':data } ));
      tBody.fadeIn('fast');
    });
  }

  // the button
  $('#nav-permissions').click(function() {
    // reload the data, just to be shure
    Permission.getAdminList(function(data) {
      refreshAdminList(this.data);
      $('#myAdminPermissionModal').modal( { 'backdrop':'static' } );
    });
  });

  // the delete buttons
  $('#adminlist').on('click', 'a.js-destroy', function() {
    var destroyButton = $(this);
    var user = destroyButton.closest('tr.user');
    var username = user.data('user');
    
    if ('{$user.username}' == username) {
      destroyButton.hide();
    }
    else {
      if (confirm(sprintf("{$lang.admin.timeline.permissions.admins.destroy.prompt}", username))) {
        destroyButton.hide();
        Permission.removeAdmin(username, function() {
          user.fadeOut(function() { user.remove(); });
        }, function() {
          // TODO proper error message
          destroyButton.show();
        });
      }
    }
  });

  // the add Admin buttons
  $('#myAdminPermissionModal').find('a.js-create').click(function() {
    username = prompt("{$lang.admin.timeline.permissions.admins.create.prompt}", '');
    if (username != null && username != "") {
      Permission.addAdmin(username, function() {
        $('#adminlist tbody').append(permissions_list_template( { 'admins':[username] } ));
      });
    }
  });

</script>
