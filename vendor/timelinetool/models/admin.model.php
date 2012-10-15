<?php

namespace Timelinetool\Models;

use \Timelinetool\Helpers\Session;
use \Timelinetool\Helpers\File;

class Admin extends Main {

  public function __init() {
    
  }

  public function getAllEditableTimelines() {
    $sStoragePath = $this->_aSession['config']['paths']['storage'] . '/timeline/';

    $oSession = Session::getUserSession();
    $aTimelines = array();
    if ($oSession->isAdmin()) {
      $sPattern = '/^([\w\s-_]+)[.]json$/';
      $sPatternUser = '/^([\w\s-_]+)_users[.]json$/';
      if ($oDirHandle = opendir($sStoragePath)) {
        while (false !== ($sFile = readdir($oDirHandle))) {
          if(preg_match($sPattern, $sFile, $aTreffer) &&
            !preg_match($sPatternUser, $sFile, $aTreffer))
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
  
  public function addAdminUser($sUsername) {
    $sStoragePath = $this->_aSession['config']['paths']['storage'];
    $sAdminsFile = $sStoragePath . '/admin_users.json';

    // read admin list
    $aAdmins = File::_readData($sAdminsFile);

    $sUsername = strtolower($sUsername);

    // add admin to list, check for duplicates
    // important, only allow this if user is admin
    if (!in_array($sUsername, $aAdmins))
      $aAdmins[] = $sUsername;

    // and save
    File::_writeData($aAdmins, $sAdminsFile);
    return true;
  }

  public function removeAdminUser($sUsername) {
    $sStoragePath = $this->_aSession['config']['paths']['storage'];
    $sAdminsFile = $sStoragePath . '/admin_users.json';
    // read admin list
    $aAdmins = File::_readData($sAdminsFile);

    $sUsername = strtolower($sUsername);

    // remove admin from list
    // important, do not remove self
    // important, only allow this if user is admin
    if (in_array($sUsername, $aAdmins)) {
      $ikey = array_search($sUsername, $aAdmins);
      array_splice($aAdmins, $ikey, 1);
    }

    // and save
    File::_writeData($aAdmins, $sAdminsFile);
    return true;
  }

  public function listAdminUsers() {
    $sStoragePath = $this->_aSession['config']['paths']['storage'];
    $sAdminsFile = $sStoragePath . '/admin_users.json';

    // read admin list and return
    return File::_readData($sAdminsFile);
  }
}

