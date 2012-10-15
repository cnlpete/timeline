<?php

/**
 * Provide many helper methods.
 *
 * @author Hauke Schade <http://hauke-schade.de>
 * @license MIT
 * @since 1.0
 *
 */

namespace Timelinetool\Helpers;

class Session {

  /**
   *
   * @var static
   * @access private
   *
   */
  private static $_oInstance = null;

  public static function getUserSession(&$aSession = null, &$aCookie = null) {
    if (self::$_oInstance === null) {
      self::$_oInstance = new self($aSession, $aCookie);
    }

    return self::$_oInstance;
  }

  protected function _parseUserDataFromXML($oData) {
    $this->_aData = array(
      'username' => strtolower((string)$oData->username),
      'firstname' => (string)$oData->firstName,
      'lastname' => (string)$oData->lastName,
      'authenticated' => (int)$oData->authenticated == 1,
      'editableTimelines' => array());

    // admin permission?
    $sStoragePath = $this->_aSession['config']['paths']['storage'];
    $sAdminsFile = $sStoragePath . '/admin_users.json';
    $aAdmins = File::_readData($sAdminsFile);
    $this->_aData['isAdmin'] = in_array($this->_aData['username'], $aAdmins);

    // go through all timelines and check whether this is has right to edit (is in list)
    $this->_parseEditabletimelines();
  }
  
  protected function _parseEditabletimelines() {
    $sStoragePath = $this->_aSession['config']['paths']['storage'] . '/timeline/';

    $aTimelines = array();
    $sPattern = '/^([\w\s-_]+)_users[.]json$/';
    if ($oDirHandle = opendir($sStoragePath)) {
      while (false !== ($sFile = readdir($oDirHandle))) {
        if(preg_match($sPattern, $sFile, $aTreffer)) {
          $sTimelineHash = substr($sFile, 0, strlen($sFile) - 11);
          $aUsernames = File::_readData($sStoragePath . $sFile);
          if (in_array($this->_aData['username'], $aUsernames))
            $this->_aData['editableTimelines'][] = $sTimelineHash;
        }
      }
      closedir($oDirHandle);
    }
  }

  protected function _parseDummyData($sUsername) {
    $this->_aData = array(
      'username' => strtolower((string)$sUsername),
      'firstname' => (string)$sUsername,
      'lastname' => (string)'',
      'authenticated' => (int)true,
      'isAdmin' => false,
      'editableTimelines' => array());

    // admin permission?
    $sStoragePath = $this->_aSession['config']['paths']['storage'];
    $sAdminsFile = $sStoragePath . '/admin_users.json';
    $aAdmins = File::_readData($sAdminsFile);
    $this->_aData['isAdmin'] = in_array($this->_aData['username'], $aAdmins);

    // fallback, if no admin specified yet
    if (count($aAdmins) == 0) {
      $adminname = $this->_aSession['config']['permissions']['adminusername'];
      $this->_aData['isAdmin'] = $this->_aData['username'] == strtolower($adminname)
    }

    $this->_parseEditabletimelines();
  }

  public function login($sUsername, $sPassword) {
    if (!$this->_aSession['config']['permissions']['use_permissions']) {
      $this->_parseDummyData(empty($sUsername) ? I18n::get('global.guestname') : $sUsername);
      setcookie('loginname', $sUsername, time() + 60*60*24*30);
      return true;
    }

    //TODO check if valid name and password
    // do the call
    $sHost        = $this->_aSession['config']['authserver']['host'];
    $sScriptPath  = $this->_aSession['config']['authserver']['scriptpath'];
    $iPort        = $this->_aSession['config']['authserver']['port'];
    $sData = Helper::loadDataFromUrl($sHost, $iPort, $sScriptPath.'?op=user_login&user='.$sUsername.'&password='.$sPassword);
    //parse Data
    $this->_parseUserDataFromXML(simplexml_load_string($sData));
    if ($this->isLoggedIn())
      setcookie('loginname', $sUsername, time() + 60*60*24*30); // keep the cookie for 30 days
    return $this->isLoggedIn();
  }

  public function logout() {
    $this->_aData = null;
    unset($this->_aSession['session']);
    unset($this->_aData);
    if (isset($this->_aCookie['loginname']) && !empty($this->_aCookie['loginname']))
      setcookie('loginname', '', time()); // invalidate the cookie by setting it to '' and invalidating the time
    return true;
  }

  protected function _loadUser($sUsername) {
    if (!$this->_aSession['config']['permissions']['use_permissions']) {
      $this->_parseDummyData($sUsername);
      return;
    }

    //TODO check if valid name
    // do the call
    $sHost = $this->_aSession['config']['authserver']['host'];
    $sScriptPath = $this->_aSession['config']['authserver']['scriptpath'];
    $iPort = $this->_aSession['config']['authserver']['port'];
    $sData = Helper::loadDataFromUrl($sHost, $iPort, $sScriptPath.'?op=user_get&user='.$sUsername);
    //parse Data
    $this->_parseUserDataFromXML(simplexml_load_string($sData));
  }

  function __construct(&$aSession = null, &$aCookie = null) {
    $this->_aSession  = &$aSession;
    $this->_aCookie   = &$aCookie;

    // check if logged in in session
    if (isset($aSession['session']))
      $this->_aData = &$aSession['session'];
    // check for cookie
    else if (isset($aCookie['loginname']) && !empty($aCookie['loginname'])) {
      $this->_loadUser($aCookie['loginname']);
      //FIXME this is unsecure
      $this->_aData['authenticated'] = true;
    }
    if ($aSession != null)
      $aSession['session'] = & $this->_aData;
  }

  public function isLoggedIn() {
    return (bool)$this->_aData['authenticated'];
  }

  public function isAdmin() {
    return $this->_aData['isAdmin'];
  }

  public function canEditTimeline($sHash) {
    return $this->isAdmin() || in_array($sHash, $this->_aData['editableTimelines']);
  }

  public function getEditableTimelineHashes() {
    if (isset($this->_aData['editableTimelines']))
      return $this->_aData['editableTimelines'];
    else
      return array();
  }

  public function getUsername() {
    return trim($this->_aData['username']);
  }

  public function getName() {
    $sName = trim($this->_aData['firstname'] . ' ' . $this->_aData['lastname']);
    return empty($sName) ? I18n::get('global.guestname') : $sName;
  }
}
