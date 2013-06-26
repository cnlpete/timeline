<?php

/**
 * Make Smarty singleton aware.
 *
 * @author Hauke Schade <http://hauke-schade.de>
 * @license MIT
 * @since 1.0
 *
 */

namespace Timelinetool\Helpers;

use Smarty;

require_once PATH_STANDARD . '/vendor/smarty/smarty/distribution/libs/Smarty.class.php';

class MySmarty extends Smarty {

  protected $_aRequest;

  protected $_aSession;

  /**
   *
   * @var static
   * @access private
   *
   */
  private static $_oInstance = null;

  /**
   * Get the Smarty instance
   *
   * @static
   * @access public
   * @param array $aRequest the $_REQUEST array
   * @param array $aSession the $_SESSION array
   * @return object self::$_oInstance Smarty instance that was found or generated
   *
   */
  public static function getInstance(&$aRequest = null, &$aSession = null) {
    if (self::$_oInstance === null) {
      self::$_oInstance = new self($aRequest, $aSession);
    }

    return self::$_oInstance;
  }

  /**
   * Set all default smarty values.
   *
   * @access public
   * @param array $aRequest the $_REQUEST array
   * @param array $aSession the $_SESSION array
   *
   */
  public function __construct(&$aRequest = null, &$aSession = null) {
    parent::__construct();

    $this->_aSession = $aSession;
    $this->_aRequest = $aRequest;

    $this->setCacheDir(PATH_STANDARD . '/' . $aSession['config']['paths']['smarty']['cache']);
    $this->setCompileDir(PATH_STANDARD . '/' . $aSession['config']['paths']['smarty']['compile']);
    $this->setPluginsDir(PATH_STANDARD . '/vendor/smarty/smarty/distribution/libs/plugins');
    $this->setTemplateDir(PATH_STANDARD . '/vendor/timelinetool/views');

    # See http://www.smarty.net/docs/en/variable.merge.compiled.includes.tpl
    $this->merge_compiled_includes = true;

    # Use a readable structure
    $this->use_sub_dirs = true;

    $aPaths = array(
          'root' => PATH_STANDARD,
          'css' => $aSession['config']['page']['url'] . '/' . $aSession['config']['paths']['public'] . '/css',
          'js' => $aSession['config']['page']['url'] . '/' . $aSession['config']['paths']['public'] . '/js',
          'img' => $aSession['config']['page']['url'] . '/' . $aSession['config']['paths']['public'] . '/img',
          'bootstrap' => $aSession['config']['page']['url'] . '/vendor/twitter/bootstrap/');
    $this->assign('path', $aPaths);
    $this->assign('path_json', json_encode($aPaths));

    $this->assign('meta', $aSession['config']['page']);
    $this->assign('meta_json', json_encode($aSession['config']['page']));

    $this->assign('use_permission', $aSession['config']['permissions']['use_permissions']);

    $oUser = Session::getUserSession();
    $aUser = array(
      'authenticated' => $oUser->isLoggedIn(),
      'name' => $oUser->getName(),
      'username' => $oUser->getUsername(),
      'editable_timelines' => $oUser->getEditableTimelineHashes(),
      'has_admin_right' => $oUser->isAdmin()
    );
    $this->assign('user', $aUser);
  }

  /**
   * Assign the language array to smartys templates
   *
   * @access public
   * @param array $aLang the language array
   *
   */
  public function setDefaultLanguage(&$aLang) {
    $this->assign('lang', $aLang);
  }

  /**
   * Delete this variable from memory...
   *
   * @access public
   *
   */
  public function __destruct() {
    parent::__destruct();

    self::$_oInstance = null;
  }

  public function addTplDir($sController) {
    $this->addTemplateDir(PATH_STANDARD . '/vendor/timelinetool/views/' . $sController);
  }
}
