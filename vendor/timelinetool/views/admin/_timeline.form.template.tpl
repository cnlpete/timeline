<script id="timeline-form-template" type="text/x-handlebars-template">
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
