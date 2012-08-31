<style type="text/css">
  {foreach $colorclasses as $colorclass}
    .type-{$colorclass.name} {$selector} {
      {$colorclass.css}
    }
  {/foreach}
</style>
