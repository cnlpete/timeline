<section>
  <link href="{$path.css}/timeline.css" rel="stylesheet" />
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
              <div class="timelinetype type-{$type}" style="position:relative; height: {($assetsintype.maxline + 1) * 30}px;">
                {foreach $assetsintype.data as $year=>$assetsinyear}
                  {foreach $assetsinyear as $asset}
                    <div class="asset"
                      style="top: {$asset.line * 30}px; 
                        left: {($year - $range.start) * 100}px; border: 1px solid black;
                        width: {$asset.width * 100}px;">
                      <div class="assetcontent">
                        <h4 class="title">{$asset.title}</h4>
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
  </div>
</section>
