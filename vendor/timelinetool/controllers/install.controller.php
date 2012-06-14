<?php

namespace Timelinetool\Controllers;

use \Timelinetool\Helpers\Helper;

class Install extends Main {

  public function executeAction() {
    switch ($this->_sAction) {
      case 'migrate':
        if (isset($this->_aRequest['file']) && 
            isset($this->_aMigrations[$this->_aRequest['file']]) && 
            $this->_aMigrations[$this->_aRequest['file']]['executed'] !== false) {
          //run the migration, output is never html ...
          return $m->doMigration($this->_aRequest['file']);
        }
        else {
          // if html, load smarty and fetch template
          if ($this->_sFormat == 'html') {
            //TODO
            return 'hello';
          }
          else
            return $this->_aMigrations;
        }
        break;
      default:
      case 'install':
        return 'install :)';
        break;
    }
  }

}

