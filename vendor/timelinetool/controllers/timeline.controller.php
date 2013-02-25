<?php

namespace Timelinetool\Controllers;

use \Timelinetool\Helpers\Helper;
use \Timelinetool\Helpers\I18n;
use \Timelinetool\Helpers\MySmarty;
use \Timelinetool\Helpers\Session;

class Timeline extends Main {

  public function executeAction() {
    switch ($this->_sAction) {
      default:
      case 'show':
        if ($this->_sFormat == 'html') {
          $oSmarty = MySmarty::getInstance();
          $oSmarty->addTplDir($this->_sController);
          //TODO cache

          $aNavList = array();

          if (isset($this->_aRequest['hash'])) {
            $sHash = trim($this->_aRequest['hash']);

            if ($this->_oModel->isValidHash($sHash)) {
              $aTimelinedata = (array)$this->_oModel->getTimelineForHash($sHash);
              $aAssetData = (array)$this->_oModel->getSortedTimelineAssetsForHash($sHash);
              $oColorclassModel = $this->_loadModel('colorclass');
              $aColorclasses = (array)$oColorclassModel->getPublicColorclasses();

              if (!isset($aTimelinedata['types']))
                $aTimelinedata['types'] = array();
              Helper::array_sort_with_target($aTimelinedata['types'], $aAssetData['types']);

              // assign start and end year, depending on max/min of timelinedata and assetdata
              $iStartYear = $aAssetData['min'] != null ? $aAssetData['min'] - 1 : $aTimelinedata['startDate'];
              $iEndYear   = $aAssetData['max'] != null ? $aAssetData['max'] + 1 : $aTimelinedata['endDate'];
              if ($aTimelinedata['startDate'] != 0 && $aTimelinedata['startDate'] < $aAssetData['min'])
                $iStartYear = $aTimelinedata['startDate'];
              if ($aTimelinedata['endDate'] != 0 && $aTimelinedata['endDate'] > $aAssetData['max'])
                $iEndYear = $aTimelinedata['endDate'];

              $oSmarty->assign('range', array('start' => $iStartYear, 'end' => $iEndYear));
              $oSmarty->assign('timeline', $aTimelinedata);
              $oSmarty->assign('assets', $aAssetData['data']);
              $oSmarty->assign('colorclasses', $aColorclasses);

              $oSmarty->assign('canEditCurrentTimeline', Session::getUserSession()->canEditTimeline($sHash));

              $aNavList['fullscreen'] = array('icon' => 'fullscreen',
                'alt' => I18n::get('navigation.fullscreen.alt'));
              $oSmarty->assign('navlist', $aNavList);

              // set the title
              if ($aTimelinedata['title'])
                $oSmarty->assign('title', $aTimelinedata['title']);

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
            $aNavList['home'] = array('icon' => 'home', 
              'label' => I18n::get('navigation.home.label'),
              'alt' => I18n::get('navigation.home.alt'));
            $aNavList['about']   = array('icon' => 'question-sign', 
              'label' => I18n::get('navigation.about.label'), 
              'alt' => I18n::get('navigation.about.alt'));
            // if there is a howto timeline, add a link to it
            if ($this->_oModel->isValidHash('howto')) {
              $aTimelinedata = (array)$this->_oModel->getTimelineForHash('howto');
              $aNavList['howto'] = array('icon' => 'film', 
                'label' => $aTimelinedata['title'],
                'url' => $this->_aSession['config']['page']['url'] . '/howto.html');
            }
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

