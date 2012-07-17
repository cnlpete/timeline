<?php

namespace Timelinetool\Models;

class Timeline extends Main {

  public function __init() {
    //TODO database
    
  }

  public function isValidHash($sHash) {
    $sStoragePath = $this->_aSession['config']['paths']['storage'];
    $sFilename = $sStoragePath . '/timeline/' . $sHash . '.json';

    return strlen($sHash) > 0 && file_exists($sFilename);
  }

  public function getTimelineForHash($sHash) {
    $sStoragePath = $this->_aSession['config']['paths']['storage'];
    $sFilename = $sStoragePath . '/timeline/' . $sHash . '.json';

    if (file_exists($sFilename)) {
      return json_decode(file_get_contents($sFilename));
    }
    else
      return false;
  }

  public function getTimelineAssetsForHash($sHash) {
    $sStoragePath = $this->_aSession['config']['paths']['storage'];
    $sAssetPath = $sStoragePath . '/timeline/' . $sHash . '/';

    $sPattern = '/^([\w\s-_]+)[.]json$/';

    $aAssets = array();
    // load all assets
    if ($oDirHandle = opendir($sAssetPath)) {
      while (false !== ($sFile = readdir($oDirHandle))) {
        if(preg_match($sPattern, $sFile, $aTreffer))
          $aAssets[$sFile] = json_decode(file_get_contents($sAssetPath . $sFile));;
      }
      closedir($oDirHandle);
    }

    return $aAssets;
  }
}

