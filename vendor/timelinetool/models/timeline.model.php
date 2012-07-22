<?php

namespace Timelinetool\Models;

class Timeline extends Main {

  private $_sLastHash;

  public function __init() {
    
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
      $aTimelineData = (array)json_decode(file_get_contents($sFilename));
      $aTimelineData['hash'] = $sHash;
      return $aTimelineData;
    }
    else
      return false;
  }

  public function lastHash() {
    return $this->_sLastHash;
  }

  public function getTimelineAssetsForHash($sHash) {
    $sStoragePath = $this->_aSession['config']['paths']['storage'];
    $sAssetPath = $sStoragePath . '/timeline/' . $sHash . '/';

    $sPattern = '/^([\w\s-_]+)[.]json$/';

    $aAssets = array();
    // load all assets
    if ($oDirHandle = opendir($sAssetPath)) {
      while (false !== ($sFile = readdir($oDirHandle))) {
        if(preg_match($sPattern, $sFile, $aTreffer)) {
          $aData = (array)json_decode(file_get_contents($sAssetPath . $sFile));
          $aData['hash'] = substr($sFile, 0, strlen($sFile) - 5);
          $aAssets[] = $aData;
        }
      }
      closedir($oDirHandle);
    }

    return $aAssets;
  }

  public function createTimeline($sHash = '') {
    $sStoragePath = $this->_aSession['config']['paths']['storage'];
    $sTimelinePath = $sStoragePath . '/timeline/';

    // generate some hash
    $sSeed = 'JvKnrQWPsThuJteNQAuH';
    while ($sHash == '' || file_exists($sTimelinePath . $sHash . '.json'))
      $sHash = sha1(uniqid($sSeed . mt_rand(), true));
    $this->_sLastHash = $sHash;

    $sAssetPath = $sStoragePath . '/timeline/' . $sHash . '/';

    // generate the folder for the assets
    if (!mkdir($sAssetPath, 0777))
      return false;

    // create the timeline-file
    return $this->_writeData(array('title' => 'Dummy Title', 'date' => time()), $sTimelinePath . $sHash . '.json');
  }

  public function updateTimeline($sHash) {
    $sStoragePath = $this->_aSession['config']['paths']['storage'];
    $sTimelinePath = $sStoragePath . '/timeline/';
    $sAssetPath = $sStoragePath . '/timeline/' . $sHash . '/';

    if (!file_exists($sTimelinePath . $sHash . '.json'))
      return false;

    // rewrite the timeline-file
    return $this->_writeData($this->_aRequest['data'], $sTimelinePath . $sHash . '.json');
  }

  public function destroyTimeline($sHash) {
    $sStoragePath = $this->_aSession['config']['paths']['storage'];
    $sTimelinePath = $sStoragePath . '/timeline/';
    $sAssetPath = $sStoragePath . '/timeline/' . $sHash . '/';

    if (!file_exists($sTimelinePath . $sHash . '.json'))
      return false;

    // delete all asset-files
    if ($oDirHandle = opendir($sAssetPath)) {
      while (false !== ($sFile = readdir($oDirHandle))) {
        if($sFile == '.' || $sFile == '..')
          continue;
        unlink($sAssetPath . $sFile);
      }
      closedir($oDirHandle);
    }
    else
      return false;

    // delete the assets folder
    if (!rmdir($sAssetPath))
      return false;

    // delete the timeline-file
    return unlink($sTimelinePath . $sHash . '.json');
  }

  public function createAsset($sHash, $sAssetHash = '') {
    $sStoragePath = $this->_aSession['config']['paths']['storage'];
    $sAssetPath = $sStoragePath . '/timeline/' . $sHash . '/';

    // generate some hash
    $sSeed = 'JvKnrQWPsThuJteNQAuH';
    while ($sAssetHash == '' || file_exists($sAssetPath . $sAssetHash . '.json'))
      $sAssetHash = sha1(uniqid($sSeed . mt_rand(), true));
    $this->_sLastHash = $sAssetHash;

    // create a new asset-file
    return $this->_writeData($this->_aRequest['data'], $sAssetPath . $sAssetHash . '.json');
  }

  public function showAsset($sHash, $sAssetHash) {
    $sStoragePath = $this->_aSession['config']['paths']['storage'];
    $sAssetPath = $sStoragePath . '/timeline/' . $sHash . '/';

    if (file_exists($sAssetPath . $sAssetHash . '.json')) {
      $aData = (array) json_decode(file_get_contents($sAssetPath . $sAssetHash . '.json'));
      $aData['hash'] = $sAssetHash;
      return $aData;
    }
    else
      return false;
  }

  public function updateAsset($sHash, $sAssetHash) {
    $sStoragePath = $this->_aSession['config']['paths']['storage'];
    $sAssetPath = $sStoragePath . '/timeline/' . $sHash . '/';

    if (!file_exists($sAssetPath . $sAssetHash . '.json'))
      return false;

    // rewrite the asset-file, overwriting the existing file
    return $this->_writeData($this->_aRequest['data'], $sAssetPath . $sAssetHash . '.json');
  }

  public function destroyAsset($sHash, $sAssetHash) {
    $sStoragePath = $this->_aSession['config']['paths']['storage'];
    $sAssetPath = $sStoragePath . '/timeline/' . $sHash . '/';

    if (!file_exists($sAssetPath . $sAssetHash . '.json'))
      return false;

    // delete the asset-file
    return unlink($sAssetPath . $sAssetHash . '.json');
  }

  private function _writeData($aData, $sFileName) {
    $file_pointer = fopen($sFileName,'w');
    if (!$file_pointer)
      return false;

    // write the data
    fwrite($file_pointer, json_encode($aData));
    // and close the file
    fclose($file_pointer);
    return true;
  }
}

