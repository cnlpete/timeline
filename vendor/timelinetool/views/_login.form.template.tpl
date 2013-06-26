<div class="modal hide" id="myLoginModal">
  <div class="modal-header">
    <button 
        type="button" 
        class="close" 
        data-dismiss="modal" 
        title="{$lang.confirm.modal.cancel}">
      ×
    </button>
    <h3>{$meta.title|string_format:$lang.navigation.login.label}</h3>
  </div>
  <form class="form-horizontal">
    <div class="modal-body">
      <fieldset>
        <div class="control-group">
          <label class="control-label" for="form-user">{$lang.login_form.name}</label>
          <div class="controls">
            <input type="text" class="input-xlarge" name="user" id="form-user" value="">
          </div>
        </div>
      </fieldset>
      {if $use_permission}
        <fieldset>
          <div class="control-group">
            <label class="control-label" for="form-password">{$lang.login_form.password}</label>
            <div class="controls">
              <input type="password" class="input-xlarge" name="password" id="form-password" value="">
            </div>
          </div>
        </fieldset>
      {/if}
    </div>
    <div class="modal-footer">
      <input type="reset" class="btn" class="input-xlarge" name="submit" value="{$lang.confirm.modal.cancel}" data-dismiss="modal" />
      <input type="submit" class="btn btn-primary" class="input-xlarge" name="submit" value="{$lang.confirm.modal.save}" />
    </div>
  </form>
</div>
