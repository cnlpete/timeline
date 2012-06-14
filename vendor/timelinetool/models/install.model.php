<?php

namespace Timelinetool\Models;

class Install extends Main {

  /**
   * holds all available migrations
   **/
  private $_aMigrations;

  public function __init() {
    //TODO database

    //load all migrations from dir
    $aExecutedMigrations = $this->_getExecutedMigrations();

    $this->_aMigrations = array();
    $sPattern = '/^(\d{4})-(\d{2})-(\d{2})\s([\w\s-_]+)[.](php|sql)$/';

    //search for all migrations
    if ($oDirHandle = opendir($this->_aSession['config']['paths']['migrations'])) {
      while (false !== ($sFile = readdir($oDirHandle))) {
        if(preg_match($sPattern, $sFile, $aTreffer)) {
          $sFilename = md5($sFile);
          $this->_aMigrations[$sFilename] = array(
                  'date'  => mktime(0, 0, 0, $aTreffer[2], $aTreffer[3], $aTreffer[1]),
                  'ext'   => $aTreffer[5],
                  'desc'  => $aTreffer[4],
                  'file'  => $sFile,
                  'executed' => isset($aExecutedMigrations[$sFilename]) ? $aExecutedMigrations[$sFilename] : false);
        }
      }
      closedir($oDirHandle);
    }
  }

  private function _getExecutedMigrations() {
    $sSql = <<<EOD
SELECT 
  file, UNIX_TIMESTAMP(executed) AS executed 
FROM `migrations`
ORDER BY file ASC
EOD;
    $aMigrations = array();
//    foreach (DB::queryAssoc($sSql) as $r)
//      $aMigrations[md5($r['file'])] = $r['executed'];
    return $aMigrations;
  }

  private function _addToMigrationDB($sFileName) {
    $sSql = <<<EOD
INSERT INTO `migrations` (`file`) 
VALUES ('{$sFileName}');
EOD;
//    return DB::execute($sSql);
  }

  private function _doSQLMigration($sFileName) {
    // open the file and read its contents
    $sContent = '';
    
    $oFileHandle = fopen($this->_sPath . '/' . $sFileName,'r');
    while(!feof($oFileHandle)) {
      $sContent .= fread($oFileHandle, 1024);
      flush();
    }
    fclose($oFileHandle);

    if (DB::execute($sContent)) {
      //make log entry
      $this->_addToMigrationDB($sFileName);
      return true;
    }
    else
      return false;
  }

  private function _doPHPMigration($sFileName) {
    require_once $this->_sPath . '/' . $sFileName;

    if (\Timeline\Migration\Script::execute($this->_oDB)) {
      //make log entry
      $this->_addToMigrationDB($sFileName);
      return true;
    }
    else
      return false;
  }

  public function doMigration($fileHash) {
    if (isset($this->_aMigrations[$fileHash]) && $this->_aMigrations[$fileHash]['executed'] == false) {
      // execute migration, depending on file extension
      switch ($this->_aMigrations[$fileHash]['ext']) {

        case 'sql':
          return $this->_doSQLMigration($this->_aMigrations[$fileHash]['file']);
          break;

        case 'php':
          return $this->_doPHPMigration($this->_aMigrations[$fileHash]['file']);
          break;
      }
    }
    else
      return false;
  }
}

