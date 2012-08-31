<?php

namespace Timelinetool\Models;

class Colorclass extends Main {

  public function __init() {
    
  }

  public function getPublicColorclasses() {
    $sStoragePath = $this->_aSession['config']['paths']['storage'];
    $aCCPath = $sStoragePath . '/colorclasses/';

    $sPattern = '/^([\w\s-_]+)[.]css$/';

    $aCCs = array();
    // load all assets
    if ($oDirHandle = opendir($aCCPath)) {
      while (false !== ($sFile = readdir($oDirHandle))) {
        if(preg_match($sPattern, $sFile, $aTreffer)) {
          $aCCs[] = array(
            'css' =>  file_get_contents($aCCPath . $sFile),
            'name' => substr($sFile, 0, strlen($sFile) - 4));
        }
      }
      closedir($oDirHandle);
    }

    return $aCCs;
  }


}

