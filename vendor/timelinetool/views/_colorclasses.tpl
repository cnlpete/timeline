<style type="text/css">
  {foreach $colorclasses as $colorclass}
    {foreach name=sels from=$selectors item=selector}.type-{$colorclass.name}{$selector}{if !$smarty.foreach.sels.last}, {/if}{/foreach} {
      {$colorclass.css}
    }
  {/foreach}
</style>
