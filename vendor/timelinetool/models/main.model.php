<?php

namespace Timelinetool\Models;

class Main {

  protected $_aRequest;

  protected $_aSession;

  protected $_aCookie;

  protected $_sAction;
  protected $_sController;
  protected $_sFormat;

  function __construct(&$aSession, &$aRequest, &$aCookie = '') {
    $this->_aSession  = $aSession;
    $this->_aRequest  = $aRequest;
    $this->_aCookie   = $aCookie;

    $this->_sAction     = $aRequest['action'];
    $this->_sController = $aRequest['controller'];
    $this->_sFormat     = $aRequest['format'];

    $this->__init();
  }
  
  protected function __init() {
    //overwrite to customize
  }

  protected function _loadModel($sModel) {
    if (file_exists(PATH_STANDARD . '/vendor/timelinetool/models/' . $sModel . '.model.php')) {
      require_once PATH_STANDARD . '/vendor/timelinetool/models/main.model.php';
      require_once PATH_STANDARD . '/vendor/timelinetool/models/' . $sModel . '.model.php';

      $sClass = '\Timelinetool\Models\\' . ucfirst($sModel);
      return new $sClass($this->_aSession, $this->_aRequest, $this->_aCookie);
    }
    else
      return null;
  }
}
