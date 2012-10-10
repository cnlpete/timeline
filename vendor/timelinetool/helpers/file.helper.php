<?php

/**
 * Provide many helper methods.
 *
 * @author Hauke Schade <http://hauke-schade.de>
 * @license MIT
 * @since 1.0
 *
 */

namespace Timelinetool\Helpers;

class File {

  public static function _writeData($aData, $sFileName) {
    $file_pointer = fopen($sFileName,'w');
    if (!$file_pointer)
      return false;

    // write the data
    fwrite($file_pointer, json_encode($aData));
    // and close the file
    fclose($file_pointer);
    return true;
  }

  public static function _readData($sFileName) {
    if (file_exists($sFileName))
      return (array)json_decode(file_get_contents($sFileName), true);

    return array();
  }

}
