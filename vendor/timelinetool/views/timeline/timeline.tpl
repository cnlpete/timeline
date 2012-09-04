<section>
  <link href="{$path.css}/timeline.css" rel="stylesheet" />
{include file='../_colorclasses.tpl' selector='.asset .title'}

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
            {foreach $assets as $type=>$assetsintype}
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
                        <span>{$asset.title}</span>
                        <span class="pin"></span>
                      </h4>
                      <div class="content" style="display: none;">
                        <span class="date">{$year}</span>
                        <div class="text">{$asset.text}</div>
                        <div class="source">{$asset.source}</div>
                      </div>
                    </div>
                  {/foreach}
                {/foreach}
              </div>
            {/foreach}
          </div>
        </div>
      </div>
    </div>
    <!-- END Timeline -->
    <div id="options">
      <div id="colorclasses">
        <p>{$lang.timeline.colorclasses}</p>
        Colorclasses
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

    // show event details on hover
    $('#scroller').on('mouseenter mouseleave', '.asset', function(e) {
      var offset = timeline.x ? timeline.x : 0;
      if (e.type == 'mouseenter')
        Event.hoverInFunction($(this), e, offset);
      else
        Event.hoverOutFunction($(this), e);
    });

    // make events sticky on click
    $('#scroller').on('click', '.asset', function(e) {
      Event.click($(this), e);
    });

    // fancy switch
    var sources = $('#scroller').find('.source');
    $('.toggle').Switch("off", function() {
      sources.slideDown();
    }, function() {
      sources.filter(":visible").slideUp();
      sources.filter(":not(:visible)").hide();
    });
  });
</script>
