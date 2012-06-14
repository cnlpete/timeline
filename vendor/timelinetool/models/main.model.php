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

}
