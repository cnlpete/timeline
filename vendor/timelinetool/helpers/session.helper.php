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
      'username' => (string)$oData->username,
      'firstname' => (string)$oData->firstName,
      'lastname' => (string)$oData->lastName,
      'authenticated' => (int)$oData->authenticated == 1,
      'permissions' => array());

    // find our scope
    $sScopeName = $this->_aSession['config']['permissions']['scope'];
    $oPermissionsScope = null;
    foreach ($oData->permissions->scope as $oScope) {
      if ($sScopeName == (string)$oScope['name']) {
        foreach ($oScope->permission as $oPermission) {
          if (!isset($this->_aData['permissions'][(string)$oPermission['name']]))
            $this->_aData['permissions'][(string)$oPermission['name']] = array((string)$oPermission);
          else
            $this->_aData['permissions'][(string)$oPermission['name']][] = (string)$oPermission;
        }
        break;
      }
    }
  }

  protected function _parseDummyData($sUsername) {
    $this->_aData = array(
      'username' => (string)$sUsername,
      'firstname' => (string)$sUsername,
      'lastname' => (string)'',
      'authenticated' => (int)true,
      'permissions' => array('admin' => array('true')));
  }

  public function login($sUsername, $sPassword) {
    if (!$this->_aSession['config']['permissions']['use_permissions']) {
      $this->_parseDummyData(I18n::get('global.guestname'));
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
    if ($aSession['session'])
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

  public function hasPermission($sPermissionIdentifier) {
    return isset($this->_aData['permissions'][$sPermissionIdentifier]) && 
      count($this->_aData['permissions'][$sPermissionIdentifier]) > 0;
  }

  private function hasTimelineHashInPermission($sHash, $sPermission) {
    foreach ($this->_aData['permissions'][$sPermission] as $sTimeline)
      if ($sTimeline == $sHash)
        return true;
  }

  public function canEditTimeline($sHash) {
    return $this->hasPermission('admin') || 
      ($this->hasPermission('edit_timeline') && $this->hasTimelineHashInPermission($sHash, 'edit_timeline')) ;
  }

  public function getEditableTimelineHashes() {
    $sPermissionName = $this->_aSession['config']['permissions']['edit_timeline'];
    if (isset($this->_aData['permissions'][$sPermissionName]))
      return $this->_aData['permissions'][$sPermissionName];
    else
      return array();
  }

  public function getName() {
    $sName = trim($this->_aData['firstname'] . ' ' . $this->_aData['lastname']);
    return empty($sName) ? I18n::get('global.guestname') : $sName;
  }
}
