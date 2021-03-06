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

class Helper {

  /**
   * Display a success message after an action is done.
   *
   * @static
   * @access public
   * @param string $sMessage message to provide
   * @param string $sRedirectTo site to redirect to
   * @return boolean true
   * @todo store in main session object
   *
   */
  public static function successMessage($sMessage, $sRedirectTo = '') {
    $_SESSION['flash_message'] = array(
        'type'    => 'success',
        'message' => $sMessage,
        'headline'=> I18n::get('flash.success'));
    return $sRedirectTo ? Helper::redirectTo ($sRedirectTo) : true;
  }

  /**
   * Display a warning message after an action is done.
   *
   * @static
   * @access public
   * @param string $sMessage message to provide
   * @param string $sRedirectTo site to redirect to
   * @return boolean false
   * @todo store in main session object
   *
   */
  public static function warningMessage($sMessage, $sRedirectTo = '') {
    $_SESSION['flash_message'] = array(
        'type'    => 'warning',
        'message' => $sMessage,
        'headline'=> I18n::get('flash.warning'));
    return $sRedirectTo ? Helper::redirectTo ($sRedirectTo) : false;
  }

  /**
   * Display an error message after an action is done.
   *
   * @static
   * @access public
   * @param string $sMessage message to provide
   * @param string $sRedirectTo site to redirect to
   * @return boolean false
   * @todo store in main session object
   *
   */
  public static function errorMessage($sMessage, $sRedirectTo = '') {
    $_SESSION['flash_message'] = array(
        'type'    => 'error',
        'message' => $sMessage,
        'headline'=> I18n::get('flash.error'));
    return $sRedirectTo ? Helper::redirectTo ($sRedirectTo) : false;
  }

  /**
   * Redirect user to a specified page.
   *
   * @static
   * @access public
   * @param string $sUrl URL to redirect the user to
   *
   */
  public static function redirectTo($sUrl) {
    if (CRAWLER && $sUrl == '/errors/404') {
      header('Status: 404 Not Found');
      header('HTTP/1.0 404 Not Found');
    }
    else
      exit(header('Location:' . $_SESSION['config']['page']['url'] . $sUrl));
  }

  public static function recursive_array_replace(&$aAr1, &$aAr2) {
    foreach ($aAr2 as $sKey => &$mValue) {
      if (isset($aAr2[$sKey])) {
        if (is_array($aAr2[$sKey]))
          self::recursive_array_replace($aAr1[$sKey], $aAr2[$sKey]);
        else {
          $aAr1[$sKey] = $aAr2[$sKey];
        }
      }
    }
  }

  public static function array_has_item(&$aAr, &$sItem, $sKey = 'key') {
    foreach ($aAr as &$aArItem) {
      $aArItem = (array)$aArItem;
      if ($aArItem[$sKey] == $sItem)
        return true;
    }
    return false;
  }

  public static function array_sort_with_target(&$aArTarget, &$aArOther) {
    foreach ($aArOther as &$sItem) {
      // check for existance
      if (!self::array_has_item($aArTarget, $sItem))
        $aArTarget[] = array('key' => $sItem, 'value' => $sItem);
    }
  }

  public static function array_unique_merge(&$aAr1, &$aAr2) {
    $aArTmp = array_unique(array_merge($aAr1, $aAr2));
    $aArTarget = array();
    foreach ($aArTmp as $sString)
      $aArTarget[] = $sString;
    return $aArTarget;
  }

  /**
   * Removes first slash at dirs.
   *
   * @static
   * @access public
   * @param string $sStr
   * @return string without slash
   *
   */
  public static function removeSlash($sStr) {
    return substr($sStr, 0, 1) == '/' ? substr($sStr, 1) : $sStr;
  }

  /**
   * Adds slash at beginning of string.
   *
   * @static
   * @access public
   * @param string $sStr
   * @return string with slash
   *
   */
  public static function addSlash($sStr) {
    return substr($sStr, 0, 1) == '/' ? $sStr : '/' . $sStr;
  }

  public static function getController($sController) {
    if (file_exists(PATH_STANDARD . '/vendor/timelinetool/controllers/' . $sController . '.controller.php')) {
      require_once PATH_STANDARD . '/vendor/timelinetool/controllers/main.controller.php';
      require_once PATH_STANDARD . '/vendor/timelinetool/controllers/' . $sController . '.controller.php';

      return '\Timelinetool\Controllers\\' . ucfirst($sController);
    }
    return false;
  }

  public static function loadDataFromUrl($sHost, $iPort, $sUrl) {
    return file_get_contents($sHost."/".$sUrl);
  }
}

