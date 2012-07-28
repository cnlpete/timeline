<?php

namespace Timelinetool\Controllers;

use \Timelinetool\Helpers\Helper;
use \Timelinetool\Helpers\I18n;
use \Timelinetool\Helpers\MySmarty;
use \Timelinetool\Helpers\Session;

class Admin extends Main {

  private $_oTimelineModel;

  protected function __init() {
    // also load the timeline model
    $this->_oTimelineModel = $this->_loadModel('timeline');
  }

  public function executeAction() {
    if (!(Session::getUserSession()->isLoggedIn()))
      Helper::errorMessage(I18n::get('admin.error.missing_rights'), '/');

    switch ($this->_sAction) {
      // api
      case 'create_timeline':
        return $this->createTimeline();
        break;
      case 'show_timeline':
        return $this->showTimeline(trim($this->_aRequest['hash']));
        break;
      case 'update_timeline':
        return $this->updateTimeline(trim($this->_aRequest['hash']));
        break;
      case 'destroy_timeline':
        return $this->destroyTimeline(trim($this->_aRequest['hash']));
        break;
      case 'create_asset':
        return $this->createAsset(trim($this->_aRequest['hash']));
        break;
      case 'show_asset':
        return $this->showAsset(trim($this->_aRequest['timelinehash']), trim($this->_aRequest['hash']));
        break;
      case 'update_asset':
        return $this->updateAsset(trim($this->_aRequest['timelinehash']), trim($this->_aRequest['hash']));
        break;
      case 'destroy_asset':
        return $this->destroyAsset(trim($this->_aRequest['timelinehash']), trim($this->_aRequest['hash']));
        break;

      // views
      case 'show_timeline':
        return $this->showTimeline(trim($this->_aRequest['hash']));
        break;
      default:
      case 'show':
        return $this->showOverview();
        break;
    }
  }

  /**
   * show all available timelines
   **/
  protected function showOverview() {
    //check rights
    if (!(Session::getUserSession()->hasPermission('admin') || Session::getUserSession()->hasPermission('edit_timeline')))
      Helper::errorMessage(I18n::get('admin.error.missing_rights'), '/');
    return $this->_showOverview();
  }

  protected function _showOverview() {
    // api call
    if ($this->_sFormat != 'html')
      return array('timelines' => $this->_oModel->getAllEditableTimelines());

    $oSmarty = MySmarty::getInstance();
    $oSmarty->addTplDir($this->_sController);
    //TODO cache

    // assign nav-links
    $aNavList = array();
    $aNavList['update'] = array('icon' => 'refresh');
    $oSmarty->assign('navlist', $aNavList);

    $oSmarty->assign('timelines_json', json_encode($this->_oModel->getAllEditableTimelines()));

    return $oSmarty->fetch('overview.tpl');
  }

  /**
   * show one specific timeline to edit
   **/
  protected function createTimeline() {
    if (!(Session::getUserSession()->hasPermission('admin')))
      Helper::errorMessage(I18n::get('admin.error.missing_rights'), '/');
    return $this->_createTimeline();
  }

  protected function _createTimeline() {
    // for now, we only support a json api
    if ($this->_sFormat != 'json')
      return null;

    // TODO check data...
    $aReturn = array('result' => $this->_oTimelineModel->createTimeline());
    if ($aReturn['result'])
      $aReturn['hash'] = $this->_oTimelineModel->lastHash();
    return $aReturn;
  }

  protected function showTimeline($sHash) {
    if (!(Session::getUserSession()->canEditTimeline($sHash)))
      Helper::errorMessage(I18n::get('admin.error.missing_rights'), '/');
    return $this->_showTimeline($sHash);
  }

  protected function _showTimeline($sHash) {
    // if not html, directly return the data
    if ($this->_sFormat != 'html')
      return array('timeline' => $this->_oTimelineModel->getTimelineForHash($sHash),
              'assets' => $this->_oTimelineModel->getTimelineAssetsForHash($sHash));

    if (!$this->_oTimelineModel->isValidHash($sHash))
      return Helper::errorMessage(I18n::get('timeline.error.hash_not_found', $sHash), '/admin.html');

    $oSmarty = MySmarty::getInstance();
    $oSmarty->addTplDir($this->_sController);
    //TODO cache

    $aTimelinedata = (array)$this->_oTimelineModel->getTimelineForHash($sHash);
    $oSmarty->assign('hash', $sHash);
    $oSmarty->assign('timeline', $aTimelinedata);
    $oSmarty->assign('assets_json', json_encode($this->_oTimelineModel->getTimelineAssetsForHash($sHash)));

    // assign nav-links
    $aNavList = array();
    $aNavList['update'] = array('icon' => 'refresh');
    $aNavList['edit']   = array('icon' => 'wrench');
    $aNavList['delete'] = array('icon' => 'trash');
    $oSmarty->assign('navlist', $aNavList);

    // set the title
    if ($aTimelinedata['title'])
      $oSmarty->assign('title', $aTimelinedata['title']);

    // maybe set the user specified language?
    if (isset($aTimelinedata['language']) && !empty($aTimelinedata['language']))
      I18n::load($aTimelinedata['language']);

    return $oSmarty->fetch('timeline.tpl');
  }

  protected function updateTimeline($sTimelineHash) {
    if (!(Session::getUserSession()->canEditTimeline($sHash)))
      Helper::errorMessage(I18n::get('admin.error.missing_rights'), '/');
    return $this->_updateTimeline($sTimelineHash);
  }

  protected function _updateTimeline($sTimelineHash) {
    // for now, we only support a json api
    if ($this->_sFormat != 'json')
      return null;

    // TODO check data..., maybe load old data and do a merge?
    return array('result' => $this->_oTimelineModel->updateTimeline($sTimelineHash));
  }

  protected function destroyTimeline($sTimelineHash) {
    if (!(Session::getUserSession()->canEditTimeline($sHash)))
      Helper::errorMessage(I18n::get('admin.error.missing_rights'), '/');
    return $this->_destroyTimeline($sTimelineHash);
  }

  protected function _destroyTimeline($sTimelineHash) {
    // for now, we only support a json api
    if ($this->_sFormat != 'json')
      return null;

    return array('result' => $this->_oTimelineModel->destroyTimeline($sTimelineHash));
  }

  protected function createAsset($sTimelineHash) {
    if (!(Session::getUserSession()->canEditTimeline($sTimelineHash)))
      Helper::errorMessage(I18n::get('admin.error.missing_rights'), '/');
    return $this->_createAsset($sTimelineHash);
  }

  protected function _createAsset($sTimelineHash) {
    // for now, we only support a json api
    if ($this->_sFormat != 'json')
      return null;

    // TODO check for proper format...
    $aReturn = array('result' => $this->_oTimelineModel->createAsset($sTimelineHash));
    if ($aReturn['result'])
      $aReturn['hash'] = $this->_oTimelineModel->lastHash();
    return $aReturn;
  }

  protected function showAsset($sTimelineHash, $sAssetHash) {
    if (!(Session::getUserSession()->canEditTimeline($sTimelineHash)))
      Helper::errorMessage(I18n::get('admin.error.missing_rights'), '/');
    return $this->_showAsset($sTimelineHash, $sAssetHash);
  }

  protected function _showAsset($sTimelineHash, $sAssetHash) {
    // if not json, directly return the data
    if ($this->_sFormat != 'json')
      return false;

    return $this->_oTimelineModel->showAsset($sTimelineHash, $sAssetHash);
  }

  protected function updateAsset($sTimelineHash, $sAssetHash) {
    if (!(Session::getUserSession()->canEditTimeline($sTimelineHash)))
      Helper::errorMessage(I18n::get('admin.error.missing_rights'), '/');
    return $this->_updateAsset($sTimelineHash, $sAssetHash);
  }

  protected function _updateAsset($sTimelineHash, $sAssetHash) {
    // for now, we only support a json api
    if ($this->_sFormat != 'json')
      return null;

    // TODO check data..., maybe load old data and do a merge?
    return array('result' => $this->_oTimelineModel->updateAsset($sTimelineHash, $sAssetHash));
  }

  protected function destroyAsset($sTimelineHash, $sAssetHash) {
    if (!(Session::getUserSession()->canEditTimeline($sTimelineHash)))
      Helper::errorMessage(I18n::get('admin.error.missing_rights'), '/');
    return $this->_destroyAsset($sTimelineHash, $sAssetHash);
  }

  protected function _destroyAsset($sTimelineHash, $sAssetHash) {
    // for now, we only support a json api
    if ($this->_sFormat != 'json')
      return null;

    return array('result' => $this->_oTimelineModel->destroyAsset($sTimelineHash, $sAssetHash));
  }
}
