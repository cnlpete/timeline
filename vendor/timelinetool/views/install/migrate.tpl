<section>
  <ul class='span12'>
    {foreach $files as $file}
      <li>
        <a class='js-tooltip js-migration' href='#' title='{$file.desc}' data-file='{$file.file}'>
          {$file.date} {$file.desc}
        </a>
      </li>
    {/foreach}
  </ul>
</section>
<script type='text/javascript'>
  $('.js-migration').click(function () {
    jTarget = $(this).parent();
    $.getJSON('?file=' + $(this).data('file') + '&action=migrate', function (data) {
      if (data) {
        jTarget.addClass('alert alert-success');
        jTarget.fadeOut();
      }
      else {
        jTarget.addClass('alert alert-error');
      }
    });
    return false;
  });
</script>
