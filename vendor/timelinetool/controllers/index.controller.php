<?php

namespace Timelinetool\Controllers;

use \Timelinetool\Helpers\Helper;
use \Timelinetool\Helpers\MySmarty;
use \Symfony\Component\Yaml\Yaml;
use \Routes;

class Index {

  protected $_aRequest;
  
  protected $_aSession;
  
  protected $_aCookie;

  function __construct(&$aRequest, &$aSession, &$aCookie = '') {
    require_once PATH_STANDARD . '/vendor/autoload.php';
    require PATH_STANDARD . '/vendor/timelinetool/helpers/helper.helper.php';
    require PATH_STANDARD . '/vendor/timelinetool/helpers/smarty.helper.php';

    $this->_aSession  = &$aSession;
    $this->_aRequest  = &$aRequest;
    $this->_aCookie   = &$aCookie;

    $this->getConfig();

    MySmarty::getInstance($aRequest, $aSession);
  }

  public function getConfig() {
    # Cache config for performance reasons
    if(!isset($this->_aSession['config']) || true) {
      $this->_aSession['config'] = Yaml::parse(file_get_contents(PATH_STANDARD . '/app/config/default.config.yml'));

      $aUserConfig = Yaml::parse(file_get_contents(PATH_STANDARD . '/app/config/config.yml'));

      # replace defaults with user defined values
      Helper::recursive_array_replace($this->_aSession['config'], $aUserConfig);
    }
  }

  public function getRoutes() {
    require_once PATH_STANDARD . '/vendor/simonhamp/routes/routes.php';

    # Cache routes for performance reasons
    if(!isset($this->_aSession['routes']) || true)
      $this->_aSession['routes'] = Yaml::parse(file_get_contents(PATH_STANDARD . '/app/config/routes.yml'));

    Routes::add($this->_aSession['routes']);

    $sURI = isset($_SERVER['REQUEST_URI']) ? Helper::removeSlash($_SERVER['REQUEST_URI']) : '';

    if ( strpos( $sURI, '?' ) !== false ) {
      # Break the query string off and attach later
      $sAdditionalParams = parse_url( $sURI, PHP_URL_QUERY );
      $sURI = str_replace( '?' . $sAdditionalParams, '', $sURI );
    }

    $aRouteParts = explode('&', Routes::route($sURI));

    if (strlen($sAdditionalParams) > 0)
      $aRouteParts = array_merge($aRouteParts, explode('&', $sAdditionalParams));

    foreach ($aRouteParts as $sRoutes) {
      $aRoute = explode('=', $sRoutes);

      if(!empty($aRoute[0]) && !isset($this->_aRequest[$aRoute[0]]))
        $this->_aRequest[$aRoute[0]] = $aRoute[1];
    }

    # set defaults
    if (!isset($this->_aRequest['controller']))
      $this->_aRequest['controller'] = Routes::route('/');
    if (!isset($this->_aRequest['action']))
      $this->_aRequest['action'] = 'show';
    if (!isset($this->_aRequest['format']))
      $this->_aRequest['format'] = 'html';

    return $this->_aRequest;
  }

  public function show() {
    // dispatch, include requested controller and execute its action
    $sController = Helper::getController($this->_aRequest['controller']);
    if ($sController == false)
      return 'Controller "'.$this->_aRequest['controller'].'" not found.';

    $oController = new $sController($this->_aSession, $this->_aRequest, $this->_aCookie);

    // executeAction shall return html code if format is html, otherwise raw data, that is safe to send to client
    $mOutput = $oController->executeAction();

    switch ($this->_aRequest['format']) {
      default:
      case 'html':
        // build header and footer and attach accordingly
        $oSmarty = MySmarty::getInstance();

        $oSmarty->assign('_FLASH', $this->_getFlashMessage());
        //TODO assigns, cache
        return $oSmarty->fetch('_header.tpl') . $mOutput . $oSmarty->fetch('_footer.tpl');
        break;
      case 'xml':
        // TODO needed?
        // @see http://de2.php.net/manual/en/book.simplexml.php
        break;
      case 'json':
        return json_encode($mOutput);
        break;
    }
  }

  /**
   * Store and show flash status messages in the application.
   *
   * @access protected
   * @see app/config/Candy.inc.php
   * @return array $aFlashMessage The message, its type and the headline of the message.
   *
   */
  protected function _getFlashMessage() {
    $aFlashMessage = isset($this->_aSession['flash_message']) ? $this->_aSession['flash_message'] : '';

    unset($this->_aSession['flash_message']);
    return $aFlashMessage;
  }

}

