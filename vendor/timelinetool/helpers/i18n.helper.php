<?php

/**
 * Translate a string.
 *
 * @author Hauke Schade <http://hauke-schade.de>
 * @license MIT
 * @since 1.0
 *
 */

namespace Timelinetool\Helpers;

class I18n {

  /**
   *
   * holds all translations
   *
   * @var static
   * @access private
   *
   */
  private static $_aLang = null;

  /**
   *
   * holds the wanted Language
   *
   * @var static
   * @access private
   *
   */
  private static $_sLanguage = null;

  /**
   *
   * holds the object
   *
   * @var static
   * @access private
   *
   */
  private static $_oInstance = null;

  /**
   * Read the language yaml and save information into session due to fast access.
   *
   * @access public
   * @param string $sLanguage language to load
   * @param array $aSession the session object, if given save the translations in S_SESSION['lang']
   *
   */
  public function __construct(&$aSession = null) {
    if ($aSession)
      $this->_aSession = $aSession;

    self::$_oInstance = $this;

    // first call
    if (!isset(self::$_aLang)) {
    
      // the session also has no language-strings loaded yet
      if (!isset($aSession['lang'])) {
        self::$_aLang = array();

        if ($aSession != null)
          $aSession['lang'] = & I18n::$_aLang;
      }
      // use the already loaded session stuff
      else
        self::$_aLang = & $aSession['lang'];

      // load the default language
      self::load($this->_aSession['config']['language']);

    }
  }

  public static function load($sLanguage) {
    // already loaded?
    if (isset(I18n::$_aLang[$sLanguage])) {
      self::$_sLanguage = $sLanguage;
      MySmarty::getInstance()->setDefaultLanguage(self::$_aLang[$sLanguage]);
      return true;
    }

    // have to load from yml-files
    $sLanguageFile        = $sLanguage . '.language.yml';
    $sCustomLanguageFile  = PATH_STANDARD . '/app/languages/' . $sLanguageFile;
    $sDefaultLanguageFile = PATH_STANDARD . '/vendor/timelinetool/languages/' . $sLanguageFile;

    // language does not exist
    if (!file_exists($sCustomLanguageFile))
      return false;

    // load the core language file
    self::$_aLang[$sLanguage] = \Symfony\Component\Yaml\Yaml::parse(file_get_contents($sDefaultLanguageFile));

    // merge all that with the users cusom language file
    $aUserLang = \Symfony\Component\Yaml\Yaml::parse(file_get_contents($sCustomLanguageFile));
    Helper::recursive_array_replace(I18n::$_aLang[$sLanguage], $aUserLang);

    self::$_sLanguage = $sLanguage;
    MySmarty::getInstance()->setDefaultLanguage(self::$_aLang[$sLanguage]);

    return true;
  }

  /**
   * Return the language array.
   *
   * @static
   * @access public
   * @param string $sPart main part of the array to return string from
   * @return array $_SESSION['lang'] session array with language data
   *
   */
  public static function getArray($sPart = '') {
    return !$sPart ? self::$_aLang[self::$_sLanguage] : self::$_aLang[self::$_sLanguage][$sPart];
  }

  /**
   * Get language as JSON for JavaScript.
   *
   * @static
   * @access public
   * @return string JSON
   *
   */
  public static function getJson() {
    return json_encode(self::getArray('javascript'));
  }

  /**
   * Get a specific language string.
   *
   * @static
   * @access public
   * @param string $sLanguagePart language part we want to load. Separated by dots.
   * @return string $mTemp
   *
   */
  public static function get($sLanguagePart) {
    if (isset( self::$_aLang[self::$_sLanguage])) {
      $mTemp =  self::$_aLang[self::$_sLanguage];
      foreach (explode('.', $sLanguagePart) as $sPart) {
        if (!is_string($mTemp)) {
          if (array_key_exists($sPart, $mTemp)) {
            $mTemp = & $mTemp[$sPart];
          }
        }
      }

      # Do we have other parameters?
      $iNumArgs = func_num_args();
      if ($iNumArgs > 1) {
        # use sprintf
        $aArgs = func_get_args();
        array_shift($aArgs);
        $mTemp = vsprintf($mTemp, $aArgs);
      }

      return is_string($mTemp) ? (string) $mTemp : '';
    }
  }

  /**
   * Unset the language saved in the session.
   *
   * @static
   * @param string $sLanguage language part we want to unload. Unload all if not set
   * @access public
   *
   */
  public static function unsetLanguage($sLanguage = '') {
    if ($sLanguage == '') {
      self::$_aLang = null;
      if (self::$_oObject != null)
        unset(self::$_oObject->_aSession['lang']);
    }
    else {
      self::$_aLang[$sLanguage] = null;
      if (self::$_oObject != null)
        unset(self::$_oObject->_aSession['lang'][$sLanguage]);
    }
  }
}
