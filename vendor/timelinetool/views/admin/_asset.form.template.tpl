<script id="asset-form-template" type="text/x-handlebars-template">
{literal}
  <form class="form-horizontal">
    <fieldset>
      <div class="control-group">
        <label class="control-label" for="form-title">{/literal}{$lang.admin.timeline.assets.title}{literal}</label>
        <div class="controls">
          <input type="text" class="input-xlarge" name="title" id="form-title" value="{{title}}">
        </div>
      </div>
    </fieldset>
    <fieldset>
      <div class="control-group">
        <label class="control-label">{/literal}{$lang.admin.timeline.assets.date}{literal}</label>
        <div class="controls">
          <input class="span1 js-monthpicker" name="startDate" id="form-startDate" size="16" type="text" value="{{startDate}}" data-date-format="yyyy-mm" readonly>
          -
          <input class="span1 js-monthpicker" name="endDate" id="form-endDate" size="16" type="text" value="{{endDate}}" data-date-format="yyyy-mm" readonly>
        </div>
      </div>
    </fieldset>
    <fieldset>
      <div class="control-group">
        <label class="control-label" for="form-texttype">{/literal}{$lang.admin.timeline.assets.texttype}{literal}</label>
        <div class="controls">
          <select name="texttype" class="input-xlarge" id="form-texttype" size="1">
            {{#each texttypes}}
              {{#compare ../texttype this}}
                <option type="text" value="{{this}}" selected="selected">{{this}}</option>
              {{^}}
                <option type="text" value="{{this}}">{{this}}</option>
              {{/compare}}
            {{/each}}
          </select>
        </div>
      </div>
    </fieldset>
    <fieldset>
      <div class="control-group">
        <label class="control-label" for="form-image">{/literal}{$lang.admin.timeline.assets.image}{literal}</label>
        <div class="controls">
          <input type="text" class="input-xlarge" name="image" id="form-image" value="{{image}}">
        </div>
      </div>
    </fieldset>
    <fieldset>
      <div class="control-group">
        <label class="control-label" for="form-text">{/literal}{$lang.admin.timeline.assets.text}{literal}</label>
        <div class="controls">
          {/literal}{include file='_texteditor.toolbar.tpl'}{literal}
          <textarea type="text" class="input-xlarge" name="text" id="form-text" rows="10" cols="40" style="width: 330px;">
          {{text}}
          </textarea>
        </div>
      </div>
    </fieldset>
    <fieldset>
      <div class="control-group">
        <label class="control-label" for="form-type">{/literal}{$lang.admin.timeline.assets.type}{literal}</label>
        <div class="controls">
          <select name="type" class="input-xlarge" id="form-type" size="1">
            {{#each types}}
              {{#compare ../type this.key}}
                <option type="text" value="{{this.key}}" selected="selected">{{this.value}}</option>
              {{^}}
                <option type="text" value="{{this.key}}">{{this.value}}</option>
              {{/compare}}
            {{/each}}
          </select>
        </div>
      </div>
    </fieldset>
    <fieldset>
      <div class="control-group">
        <label class="control-label" for="form-source">{/literal}{$lang.admin.timeline.assets.source}{literal}</label>
        <div class="controls">
          <textarea class="input-xlarge" name="source" id="form-source">{{source}}</textarea>
        </div>
      </div>
    </fieldset>
  </form>
{/literal}
</script>
