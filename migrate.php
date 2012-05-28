<?php
/** 
 *
 * Execute a given migration or display all migrations
 *
 * @link http://github.com/cnlpete/timeline
 * @author Hauke Schade <http://hauke-schade.de>
 *
 **/

namespace Timeline;

class Migrate {

  /**
   * holds all available migrations
   **/
  private $_aMigrations;

  /**
   * holds the path where the migrations are stored
   **/
  private $_sPath;

  /**
   * holds the db-connection
   **/
  private $_oDB;

  public function __construct() {
    require_once 'class/db.class.php';
    require_once 'class/config.class.php';
    $this->_sPath = Config::getInstance()->migrationPath;

    $aExecutedMigrations = $this->_getExecutedMigrations();

    $this->_aMigrations = array();
    $sPattern = '/^(\d{4})-(\d{2})-(\d{2})\s([\w\s-_]+)[.](php|sql)$/';

    //search for all migrations
    if ($oDirHandle = opendir($this->_sPath)) {
      while (false !== ($sFile = readdir($oDirHandle))) {
        if(preg_match($sPattern, $sFile, $aTreffer)) {
          $sFilename = md5($sFile);
          $this->_aMigrations[$sFilename] = array(
                  'date'  => mktime(0 , 0, 0, $aTreffer[2], $aTreffer[3], $aTreffer[1]),
                  'ext'   => $aTreffer[5],
                  'desc'  => $aTreffer[4],
                  'file'  => $sFile,
                  'executed' => isset($aExecutedMigrations[$sFilename]) ? $aExecutedMigrations[$sFilename] : false);
        }
      }
      closedir($oDirHandle);
    }
  }

  public function toHtml() {
    $bHasMigration = false;

    $sStr = '<ul>';
    foreach ($this->_aMigrations as $sMigrationKey => $aMigration) {
      if ($aMigration['executed'] === false) {
        $bHasMigration = true;
        $sStr .= '<li>';
        $sStr .= '<a href="?file=' . $sMigrationKey . '">';
          $sStr .= '<time datetime="' . date('c', $aMigration['date']) . '">';
            $sStr .= date('d.m.Y', $aMigration['date']) . '</time> ';
          $sStr .= $aMigration['desc'];
        $sStr .= '</a></li>';
      }
    }
    $sStr .= '</ul>';
    
    if (!$bHasMigration) {
      $sStr = '<div class="alert alert-success">There are no migrations</div>';
    }

    return $sStr;
  }
  
  private function _getExecutedMigrations() {
    $sSql = <<<EOD
SELECT 
  file, UNIX_TIMESTAMP(executed) AS executed 
FROM `migrations`
ORDER BY file ASC
EOD;
    $aMigrations = Array();
    foreach (DB::queryAssoc($sSql) as $r)
      $aMigrations[md5($r['file'])] = $r['executed'];
    return $aMigrations;
  }
  
  private function _addToMigrationDB($sFileName) {
    $sSql = <<<EOD
INSERT INTO `migrations` (`file`) 
VALUES ('{$sFileName}');
EOD;
    return DB::execute($sSql);
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
      $aReturn = array();
      // execute migration, depending on file extension
      if ($this->_aMigrations[$fileHash]['ext'] == 'sql')
        $aReturn['return'] = $this->_doSQLMigration($this->_aMigrations[$fileHash]['file']);
      else if ($this->_aMigrations[$fileHash]['ext'] == 'php')
        $aReturn['return'] = $this->_doPHPMigration($this->_aMigrations[$fileHash]['file']);
      
      return $aReturn;
    }
    else
      return false;
  }
}

$m = new Migrate();
if (isset($_REQUEST['file'])) {
  //try to run the migration
  $mReturn = $m->doMigration($_REQUEST['file']);

  if ($mReturn === false) {
    echo $m->toHtml();
  }
  else {
    $mReturn['debug'] = Log::getDebugMsg();
    echo json_encode($mReturn);
  }
}
else {
  echo $m->toHtml();
}

?>
