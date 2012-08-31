<style type="text/css">
  {foreach $colorclasses as $colorclass}
    .type-{$colorclass.name} .asset .title {
      {$colorclass.css}
    }
  {/foreach}
</style>
