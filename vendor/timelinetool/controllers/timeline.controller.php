<?php

namespace Timelinetool\Controllers;

use \Timelinetool\Helpers\Helper;
use \Timelinetool\Helpers\MySmarty;

class Timeline extends Main {

  public function executeAction() {
    switch ($this->_sAction) {
      default:
      case 'show':
        if ($this->_sFormat == 'html') {
          $oSmarty = MySmarty::getInstance();
          $oSmarty->addTplDir($this->_sController);
          //TODO cache
          //TODO valid hash?
          if ($this->_aRequest['hash']) {
            $oSmarty->assign('timelinedata', array());
            return $oSmarty->fetch('timeline.tpl');
          }
          else {
            // show some index instead
            return $oSmarty->fetch('index.tpl');
          }
        }
        else
          return $this->_oModel->getMigrations();
        break;
    }
  }

}

