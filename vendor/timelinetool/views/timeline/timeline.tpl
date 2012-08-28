<section>
  <div>
    <!-- BEGIN Minimap -->
    <div id="minimap">
      <div id="current-view">
        <div class="line"></div>
      </div>
    </div>
    <!-- END Minimap -->

    <!-- BEGIN Timeline -->
    <div id="timeline">
      <div id="wrapper">
        <div id="scroller" style="position: relative;">
          <div class="dates" style="position:relative; height: 30px;">
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
            <div class="type type-{$type}" style="position:relative; height: {($assetsintype.maxline + 1) * 30}px;">
              {foreach $assetsintype.data as $year=>$assetsinyear}
                {foreach $assetsinyear as $asset}
                  <div class="asset"
                    style="position: absolute; 
                      display: inline-block;
                      top: {$asset.line * 30}px; 
                      left: {($year - $range.start) * 100}px; border: 1px solid black;
                      width: {$asset.width * 100}px;">
                    <h4>{$year} - {$asset.title}</h4>
                  </div>
                {/foreach}
              {/foreach}
            </div>
          {/foreach}
        </div>
      </div>
    </div>
    <!-- END Timeline -->
  </div>
</section>
