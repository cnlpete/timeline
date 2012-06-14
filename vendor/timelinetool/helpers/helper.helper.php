<?php

namespace Timelinetool\Helpers;

class Helper {

  public static function recursive_array_replace(&$aAr1, &$aAr2) {
    foreach ($aAr1 as $sKey => &$mValue) {
      if (isset($aAr2[$sKey])) {
        if (is_array($aAr2[$sKey]))
          self::recursive_array_replace($mValue, $aAr2[$sKey]);
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
      require_once PATH_STANDARD . '/vendor/timelinetool/controllers/' . $sController . '.controller.php';
      
      return '\Timelinetool\Controllers\\' . $sController;
    }
    return false;
  }

}

?>
