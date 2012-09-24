<div id="wysihtml5-editor-toolbar">
  <ul class="commands">
    <li data-wysihtml5-command="bold" title="Make text bold (CTRL + B)" class="command js-bold"></li>
    <li data-wysihtml5-command="italic" title="Make text italic (CTRL + I)" class="command js-italic"></li>
    <li data-wysihtml5-command="insertUnorderedList" title="Insert an unordered list" class="command js-ul"></li>
    <li data-wysihtml5-command="insertOrderedList" title="Insert an ordered list" class="command js-ol"></li>
    <li data-wysihtml5-command="createLink" title="Insert a link" class="command js-a"></li>
    <li data-wysihtml5-command="insertImage" title="Insert an image" class="command js-img"></li>
    <li data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h1" title="Insert headline 1" class="command js-h"></li>
    <li data-wysihtml5-command-group="foreColor" class="fore-color js-color" title="Color the selected text" class="command">
      <ul>
        <li data-wysihtml5-command="foreColor" data-wysihtml5-command-value="silver"></li>
        <li data-wysihtml5-command="foreColor" data-wysihtml5-command-value="gray"></li>
        <li data-wysihtml5-command="foreColor" data-wysihtml5-command-value="maroon"></li>
        <li data-wysihtml5-command="foreColor" data-wysihtml5-command-value="red"></li>
        <li data-wysihtml5-command="foreColor" data-wysihtml5-command-value="purple"></li>
        <li data-wysihtml5-command="foreColor" data-wysihtml5-command-value="green"></li>
        <li data-wysihtml5-command="foreColor" data-wysihtml5-command-value="olive"></li>
        <li data-wysihtml5-command="foreColor" data-wysihtml5-command-value="navy"></li>
        <li data-wysihtml5-command="foreColor" data-wysihtml5-command-value="blue"></li>
      </ul>
    </li>
    <li data-wysihtml5-action="change_view" title="Show HTML" class="action js-src"></li>
  </ul>
  <div data-wysihtml5-dialog="createLink" style="display: none;" class="js-dialog js-dialog-link">
    <label>
      Link:
      <input data-wysihtml5-dialog-field="href" value="http://">
    </label>
    <a class="btn btn-primary" data-wysihtml5-dialog-action="save">{$lang.confirm.modal.save}</a>
    &nbsp;
    <a class="btn" data-wysihtml5-dialog-action="cancel">{$lang.confirm.modal.cancel}</a>
  </div>

  <div data-wysihtml5-dialog="insertImage" style="display: none;" class="js-dialog js-dialog-img">
    <label>
      Image:
      <input data-wysihtml5-dialog-field="src" value="http://">
    </label>
    <a class="btn btn-primary" data-wysihtml5-dialog-action="save">{$lang.confirm.modal.save}</a>
    &nbsp;
    <a class="btn" data-wysihtml5-dialog-action="cancel">{$lang.confirm.modal.cancel}</a>
  </div>
</div>
