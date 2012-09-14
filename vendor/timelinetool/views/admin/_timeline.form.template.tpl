<script id="timeline-form-template" type="text/x-handlebars-template">
{literal}
  <form class="form-horizontal">
    <fieldset>
      <div class="control-group">
        <label class="control-label" for="form-title">{/literal}{$lang.admin.timeline.title}{literal}</label>
        <div class="controls">
          <input type="text" class="input-xlarge" name="title" id="form-title" value="{{title}}">
        </div>
      </div>
    </fieldset>
    <fieldset>
      <div class="control-group">
        <label class="control-label">{/literal}{$lang.admin.timeline.date}{literal}</label>
        <div class="controls">
          <input class="span1 js-yearpicker" name="startDate" id="form-startDate" size="16" type="text" value="{{startDate}}" data-date-format="yyyy" readonly>
          -
          <input class="span1 js-yearpicker" name="endDate" id="form-endDate" size="16" type="text" value="{{endDate}}" data-date-format="yyyy" readonly>
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
        <label class="control-label" for="form-description">{/literal}{$lang.admin.timeline.description}{literal}</label>
        <div class="controls">
          <input type="text" class="input-xlarge" name="description" id="form-description" value="{{description}}">
        </div>
      </div>
    </fieldset>
    <fieldset>
      <div class="control-group">
        <label class="control-label" for="form-language">{/literal}{$lang.admin.timeline.colorclasses}{literal}</label>
        <div class="controls">
          <ul class='js-sortable sortable'>
            {{#each types}}
              <li>
                <input type="text" class="input js-types type-{{this.key}} colorful" name="types[]" id="form-types-{{this.key}}" value="{{this.value}}" data-types="{{this.key}}">
              </li>
            {{/each}}
          </ul>
        </div>
      </div>
    </fieldset>
  </form>
{/literal}
</script>
