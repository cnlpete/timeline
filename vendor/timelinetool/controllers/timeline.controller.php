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

          if ($this->_aRequest['hash']) {
            $sHash = trim($this->_aRequest['hash']);

            if ($this->_oModel->isValidHash($sHash)) {
              $aTimelinedata = (array)$this->_oModel->getTimelineForHash($sHash);
              $oSmarty->assign('timeline', $aTimelinedata);
              $oSmarty->assign('assets', $this->_oModel->getTimelineAssetsForHash($sHash));

              return $oSmarty->fetch('timeline.tpl');
            }
            else {
              Helper::errorMessage(I18n::get('timeline.error.hash_not_found', $sHash), '/');
            }
          }
          else {
            // show some index instead
            return $oSmarty->fetch('index.tpl');
          }
        }
        else
          return $this->_oModel->getTimelineForHash($this->_aRequest['hash']);
        break;
    }
  }

}

