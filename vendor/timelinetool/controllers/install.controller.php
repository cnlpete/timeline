<?php

namespace Timelinetool\Controllers;

use \Timelinetool\Helpers\Helper;
use \Timelinetool\Helpers\MySmarty;

class Install extends Main {

  public function executeAction() {
    switch ($this->_sAction) {
      case 'migrate':
        if (isset($this->_aRequest['file']) && $this->_oModel->hasMigration($this->_aRequest['file'])) {
          //run the migration, output is never html ...
          return $this->_oModel->doMigration($this->_aRequest['file']);
        }
        else {
          // if html, load smarty and fetch template
          if ($this->_sFormat == 'html') {
            $oSmarty = MySmarty::getInstance();
            $oSmarty->addTplDir($this->_sController);
            //TODO cache
            $oSmarty->assign('files', $this->_oModel->getMigrations());
            return $oSmarty->fetch('migrate.tpl');
          }
          else
            return $this->_oModel->getMigrations();
        }
        break;
      default:
      case 'install':
        return 'install :)';
        break;
    }
  }

}

