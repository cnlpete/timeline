<script id="login-form-template" type="text/x-handlebars-template">
{literal}
  <form class="form-horizontal">
    <fieldset>
      <div class="control-group">
        <label class="control-label" for="form-user">{/literal}{$lang.login_form.name}{literal}</label>
        <div class="controls">
          <input type="text" class="input-xlarge" name="user" id="form-user" value="">
        </div>
      </div>
    </fieldset>
    <fieldset>
      <div class="control-group">
        <label class="control-label" for="form-password">{/literal}{$lang.login_form.password}{literal}</label>
        <div class="controls">
          <input type="password" class="input-xlarge" name="password" id="form-password" value="">
        </div>
      </div>
    </fieldset>
  </form>
{/literal}
</script>