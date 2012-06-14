<?php

namespace Timelinetool\Controllers;

use \Timelinetool\Helpers\Helper;

class Main {

  protected $_aRequest;

  protected $_aSession;

  protected $_aCookie;

  protected $_sAction;
  protected $_sController;
  protected $_sFormat;

  protected $_oModel;

  function __construct(&$aSession, &$aRequest, &$aCookie = '') {
    $this->_aSession  = $aSession;
    $this->_aRequest  = $aRequest;
    $this->_aCookie   = $aCookie;

    $this->_sAction     = $aRequest['action'];
    $this->_sController = $aRequest['controller'];
    $this->_sFormat     = $aRequest['format'];

    if (file_exists(PATH_STANDARD . '/vendor/timelinetool/models/' . $this->_sController . '.model.php')) {
      require_once PATH_STANDARD . '/vendor/timelinetool/models/main.model.php';
      require_once PATH_STANDARD . '/vendor/timelinetool/models/' . $this->_sController . '.model.php';

      $sClass = '\Timelinetool\Models\\' . ucfirst($this->_sController);
      $this->oModel = new $sClass($aSession, $aRequest, $aCookie);
    }

    $this->__init();
  }

  protected function __init() {
    //overwrite to customize
  }

}

