<?php

namespace Timelinetool\Controllers;

use \Timelinetool\Helpers\Helper;
use \Timelinetool\Helpers\I18n;
use \Timelinetool\Helpers\MySmarty;
use \Timelinetool\Helpers\Session as SessionHelper;

class Session extends Main {

  public function executeAction() {
    switch ($this->_sAction) {
      case 'logout':
        return $this->doLogout();
        break;
      default:
      case 'login':
        if ($this->_sFormat == 'html')
          return $this->showLogin();
        else
          return $this->doLogin();
        break;
    }
  }

  protected function showLogin() {
    return $this->_showLogin();
  }

  protected function _showLogin() {
    $oSmarty = MySmarty::getInstance();
    $oSmarty->addTplDir($this->_sController);
    //TODO cache

    return $oSmarty->fetch('login.tpl');
  }

  protected function doLogin() {
    return $this->_doLogin();
  }

  protected function _doLogin() {
    $oSession = SessionHelper::getUserSession();
    return $oSession->login($this->_aRequest['user'], $this->_aRequest['password']);
  }

  protected function doLogout() {
    return $this->_doLogout();
  }

  protected function _doLogout() {
    $oSession = SessionHelper::getUserSession();
    return $oSession->logout();
  }

}

