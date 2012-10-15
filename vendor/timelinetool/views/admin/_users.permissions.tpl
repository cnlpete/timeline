<div class="modal hide" id="myPermissionModal">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" title="{$lang.confirm.modal.close}">×</button>
    <h3>{$lang.admin.timeline.permissions.users.header}</h3>
  </div>
  <div class="modal-body">
    <table id='userlist' class="table table-bordered">
      <thead>
        <tr>
          <th class="type-string">{$lang.admin.timeline.permissions.users.user}</th>
          <th width='40px'>
            {if $user.has_admin_right}
              <a class="js-create btn" 
                  href="#create"
                  title="{$lang.admin.timeline.permissions.users.create.alt}">
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
          title="{$lang.admin.timeline.permissions.users.create.alt}">
        <i class="icon-plus"></i> {$lang.admin.timeline.permissions.users.create.label}
      </a>
    {/if}
    <a href="#" class="btn" data-dismiss="modal">{$lang.confirm.modal.close}</a>
  </div>
</div>

<script id="permissions-list-template" type="text/x-handlebars-template">
  {literal}{{#each users}}{/literal}
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
              title="{$lang.admin.timeline.permissions.users.destroy.alt}">
            <i class="icon-trash"></i>
          </a>
        {/if}
      </td>
    </tr>
  {literal}{{/each}}{/literal}
</script>

<script type="text/javascript">
  var permissions_list_template = Handlebars.compile($("#permissions-list-template").html());

  $('#userlist').stupidtable();

  refreshUserList = function(data){
    var tBody = $('tbody', $('#userlist'));
    tBody.fadeOut('fast', function() {
      $('tr', tBody).remove();
      tBody.append(permissions_list_template( { 'users':data } ));
      tBody.fadeIn('fast');
    });
  }

  // the button
  $('#nav-permissions').click(function() {
    // reload the data, just to be shure
    Permission.getList('{$hash}', function(data) {
      refreshUserList(this.data);
      $('#myPermissionModal').modal( { 'backdrop':'static' } );
    });
  });

  // the delete buttons
  $('#userlist').on('click', 'a.js-destroy', function() {
    var destroyButton = $(this);
    var user = destroyButton.closest('tr.user');
    var username = user.data('user');

    if (confirm(sprintf("{$lang.admin.timeline.permissions.users.destroy.prompt}", username))) {
      destroyButton.hide();
      Permission.removeUser('{$hash}', username, function() {
        user.fadeOut(function() { user.remove(); });
      }, function() {
        // TODO proper error message
        destroyButton.show();
      });
    }
  });

  // the timeline create buttons
  $('#myPermissionModal').find('a.js-create').click(function() {
    username = prompt("{$lang.admin.timeline.permissions.users.create.prompt}", '');
    if (username != null && username != "") {
      Permission.addUser('{$hash}', username, function() {
        $('#userlist tbody').append(permissions_list_template( { 'users':[username] } ));
      });
    }
  });

</script>
