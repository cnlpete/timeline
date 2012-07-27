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
      exit(header('Location:' . $sUrl));
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

/*    $timeout = 30;
die('trying to load: ' . $sHost."/".$sUrl);
    $fp = fsockopen($sHost, $iPort, $errno, $errstr, $timeout);
    if($fp) {
      $request = "GET ".$sHost."/".$sUrl." HTTP/1.1\r\n";
      $request.= "Host: ".$sHost."\r\n";
      $request.= "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; de-DE; rv:1.7.12) Gecko/20050919 Firefox/1.0.7\r\n";
      $request.= "Connection: Close\r\n\r\n";

      fwrite($fp, $request);
      while (!feof($fp))
        $data .= fgets($fp, 128);

      fclose($fp);
      return $data;
    }
    else
      return '';*/
  }
}

