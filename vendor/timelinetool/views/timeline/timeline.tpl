<section>
  <link href="{$path.css}/timeline.css" rel="stylesheet" />
{include file='../_colorclasses.tpl' selectors=[' .asset .title', ' .asset .content .color', '.colortarget']}

  <div>
    <!-- BEGIN Minimap -->
    <div id="map-container">
      <div id="mini-map">
        <div id="current-view">
          <div class="line"></div>
        </div>
      </div>
    </div>
    <!-- END Minimap -->

    <!-- BEGIN Timeline -->
    <div id="timeline">
      <div id="wrapper">
        <div id="scroller" style="position: relative; width: {($range.end - $range.start + 1) * 101}px">
          <div id="timelinecontent">
            <div class="dates">
              {for $year=$range.start to $range.end}
                <div class="date" 
                  style="width: 100px; 
                    display: inline-block;
                    position: absolute;
                    left: {($year - $range.start) * 100}px;">
                  {$year}
                </div>
              {/for}
            </div>
            {foreach $timeline.types as $colorclass}
              {assign "type" $colorclass.key}
              {if isset($assets.$type)}
                {assign "assetsintype" $assets.$type}
                <div class="timelinetype type-{$type}" style="position:relative; height: {($assetsintype.maxline + 1) * 35}px;">
                  {foreach $assetsintype.data as $year=>$assetsinyear}
                    {foreach $assetsinyear as $asset}
                      <div class="asset"
                        id="asset-{$asset.hash}"
                        data-hash="{$asset.hash}"
                        style="top: {$asset.line * 35}px; 
                          left: {($year - $range.start) * 100}px;
                          width: {$asset.width * 100}px;">
                        <h4 class="title">
                          <span class="pin"></span>
                          <span>{$asset.title}</span>
                        </h4>
                        <div class="content" style="display: none;">
                          <h4>
                            <span class="pin"></span>
                            <span class="color"></span>
                            {$asset.title}
                          </h4>
                          <span class="date">{$year}</span>
                          <div class="{$asset.texttype}">
                            {if $asset.texttype == 'image'}
                              <div class="big-img"><img src="{$asset.image}" alt="{$asset.title}" /></div>
                              <p class="img-text">{$asset.text}</p>
                            {elseif $asset.texttype == 'video'}
                              <div class="js-url2video" title="{$asset.image}">
                                <a href="{$asset.image}">{$asset.image}</a>
                              </div>
                              <p class="img-text">{$asset.text}</p>
                            {elseif $asset.texttype == 'quote'}
                              {if !empty($asset.image)}
                                <div class="small-img"><img src="{$asset.image}" alt="{$asset.title}" /></div>
                              {/if}
                              <blockquote>{$asset.text}</blockquote>
                            {else}
                              {if !empty($asset.image)}
                                <div class="small-img"><img src="{$asset.image}" alt="{$asset.title}" /></div>
                              {/if}
                              <p>{$asset.text}</p>
                            {/if}
                          </div>
                          {if $asset.source}
                            <div class="source">{$asset.source}</div>
                          {/if}
                        </div>
                      </div>
                    {/foreach}
                  {/foreach}
                </div>
              {/if}
            {/foreach}
          </div>
        </div>
      </div>
    </div>
    <!-- END Timeline -->
    <div id="options">
      <div id="colorclasses">
        <p>{$lang.timeline.colorclasses}</p>
        <ul>
          {foreach $timeline.types as $colorclass}
            {assign "type" $colorclass.key}
            {if isset($assets.$type)}
              <li class="type-{$colorclass.key} colortarget selected" data-key="{$colorclass.key}">{$colorclass.value}</li>
            {/if}
          {/foreach}
        </ul>
      </div>
      <div id="source">
        <p>{$lang.timeline.sources}</p>
        <span class="toggle"></span>
      </div>
    </div>
  </div>
</section>
<script src="{$path.js}/iscroll.js"></script>
<script src="{$path.js}/jquery.mini-map.js"></script>
<script src="{$path.js}/jquery.switch.js"></script>
<script src="{$path.js}/timeline.js"></script>
<script type="text/javascript">
  /* PAGE LOAD */
  jQuery(document).ready(function($){
    /* hide the footer */
    $('footer').siblings().last().hide();
    $('footer').hide();

    /*** iScroll ***/
    timeline = new iScroll('wrapper',{
        bounce: false,
        scrollbarClass: 'scrollbar',
        vScroll: false,
        vScrollbar: false
      });

    /* mini map */
    $('#scroller').minimap(timeline, $('.container').width()-4);

    /* update timeline height */
    setWrapperHeight();
    $(window).resize(function() {
      setWrapperHeight();
    });

    /* build links and stuff */
    Event.buildContent($('#scroller').find('.asset'));

    /* show event details on hover */
    $('#scroller').on('mouseenter mouseleave', '.asset', function(e) {
      var offset = timeline.x ? timeline.x : 0;
      if (e.type == 'mouseenter')
        Event.hoverInFunction($(this), e, offset);
      else
        Event.hoverOutFunction($(this), e);
    });

    /* make events sticky on click */
    $('#scroller').on('click', '.asset', function(e) {
      Event.click($(this), e);
    });

    /* fancy switch */
    var sources = $('#scroller').find('.source');
    $('.toggle').Switch("off", function() {
      sources.slideDown();
    }, function() {
      sources.filter(":visible").slideUp();
      sources.filter(":not(:visible)").hide();
    });

    /* Colorclass Buttons */
    Options.enableCCButtons();

    /* scroll to event, if starthash is given */
    if(window.location.hash) {
      var event = $('#asset-' + window.location.hash.substr(1));
      if (event.length) {
        history.pushState({ 'hash' : event.data('hash'), 'stateuid' : 0 }, null, 
              window.location.href);

        Event.scrollTo(event);
        if (!Event.isSticky(event)) {
          Event.hoverInFunction(event, event, 0);
          Event.makeSticky(event);
        }
      }
    }
  });
</script>
