<?php

namespace Timelinetool\Controllers;

use \Timelinetool\Helpers\Helper;
use \Timelinetool\Helpers\I18n;
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
              $aAssetData = (array)$this->_oModel->getSortedTimelineAssetsForHash($sHash);
              $oColorclassModel = $this->_loadModel('colorclass');
              $aColorclasses = (array)$oColorclassModel->getPublicColorclasses();

              // assign start and end year, depending on max/min of timelinedata and assetdata
              $iStartYear = $aAssetData['min'] - 1;
              $iEndYear   = $aAssetData['max'] + 1;
              if ($aTimelinedata['startDate'] != 0 && $aTimelinedata['startDate'] < $aAssetData['min'])
                $iStartYear = $aTimelinedata['startDate'];
              if ($aTimelinedata['endDate'] != 0 && $aTimelinedata['endDate'] > $aAssetData['max'])
                $iEndYear = $aTimelinedata['endDate'];

              $oSmarty->assign('range', array('start' => $iStartYear, 'end' => $iEndYear));
              $oSmarty->assign('timeline', $aTimelinedata);
              $oSmarty->assign('assets', $aAssetData['data']);
              $oSmarty->assign('colorclasses', $aColorclasses);

              // maybe set the user specified language?
              if (isset($aTimelinedata['language']) && !empty($aTimelinedata['language']))
                I18n::load($aTimelinedata['language']);

              return $oSmarty->fetch('timeline.tpl');
            }
            else {
              Helper::errorMessage(I18n::get('timeline.error.hash_not_found', $sHash), '/');
            }
          }
          else {
            // show some index instead

            // assign nav-links
            $aNavList = array();
            $aNavList['home'] = array('icon' => 'home', 'label' => 'Home');
            $aNavList['about']   = array('icon' => 'question-sign', 'label' => 'Über');
            $oSmarty->assign('navlist', $aNavList);

            return $oSmarty->fetch('index.tpl');
          }
        }
        else
          return array(
            'timeline' => $this->_oModel->getTimelineForHash($this->_aRequest['hash']),
            'assets' => $this->_oModel->getTimelineAssetsForHash($this->_aRequest['hash'])
          );
        break;
    }
  }

}

