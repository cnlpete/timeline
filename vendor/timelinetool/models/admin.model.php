<?php

namespace Timelinetool\Models;

use \Timelinetool\Helpers\Session;

class Admin extends Main {

  public function __init() {
    
  }

  public function getAllEditableTimelines() {
    $sStoragePath = $this->_aSession['config']['paths']['storage'] . '/timeline/';

    $oSession = Session::getUserSession();
    $aTimelines = array();
    if ($oSession->hasPermission('admin')) {
      $sPattern = '/^([\w\s-_]+)[.]json$/';
      if ($oDirHandle = opendir($sStoragePath)) {
        while (false !== ($sFile = readdir($oDirHandle))) {
          if(preg_match($sPattern, $sFile, $aTreffer))
            $aTimelines[] = substr($sFile, 0, strlen($sFile) - 5);
        }
        closedir($oDirHandle);
      }
    }
    else
      $aTimelines = $oSession->getEditableTimelineHashes();

    // load up the data
    $aTimelineData = array();
    foreach ($aTimelines as $sTimeline) {
      if (file_exists($sStoragePath . $sTimeline . '.json')) {
        $aData = (array)json_decode(file_get_contents($sStoragePath . $sTimeline . '.json'));
        $aData['hash'] = $sTimeline;
        $aTimelineData[] = $aData;
      }
    }
    
    return $aTimelineData;
  }

}

