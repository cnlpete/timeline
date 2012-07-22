<script id="asset-form-template" type="text/x-handlebars-template">
{literal}
  <form class="form-horizontal">
    <fieldset>
      <div class="control-group">
        <label class="control-label" for="form-language">{/literal}{$lang.admin.timeline.assets.title}{literal}</label>
        <div class="controls">
          <input type="text" class="input-xlarge" name="title" id="form-title" value="{{title}}">
        </div>
      </div>
    </fieldset>
    <fieldset>
      <div class="control-group">
        <label class="control-label">{/literal}{$lang.admin.timeline.assets.date}{literal}</label>
        <div class="controls">
          <input class="span1 js-datepicker" name="startDate" id="form-startDate" size="16" type="text" value="{{startDate}}" data-date-format="yyyy-mm-dd" readonly>
          -
          <input class="span1 js-datepicker" name="endDate" id="form-endDate" size="16" type="text" value="{{endDate}}" data-date-format="yyyy-mm-dd" readonly>
        </div>
      </div>
    </fieldset>
  </form>
{/literal}
</script>
