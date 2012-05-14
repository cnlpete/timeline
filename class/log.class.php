<?php
/* 
 *
 * @link http://github.com/cnlpete/timeline
 * @author Hauke Schade <http://hauke-schade.de>
 *
 */

namespace Timeline;

class Log {
	private $debug_msg = Array();
	private static $oInstance = null;

	static function getInstance() {
		if( !Log::$oInstance ) {
			Log::$oInstance = new Log();
		}
		if( !Log::$oInstance ) {
			Log::critical('Could not create Log singleton.');
		}			
		
		return Log::$oInstance;
		
	}
	
	function Log() {
	}

	public static function error($sMessage) {
		Log::showMessage("[E] ".$sMessage, 'error');
	}

	public static function warn($sMessage) {
		Log::showMessage("[W] ".$sMessage, 'warning');
	}

	public static function critical($sMessage, $bDie = true) {
		Log::showMessage("[C] ".$sMessage, 'critical');
		if ($bDie === true)
		  die('');
	}

	public static function debug($sMessage) {
		Log::getInstance()->debug_msg[] = $sMessage;
	}
	
	public static function getDebugMsg() {
		return Log::getInstance()->debug_msg;
	}

	public static function output() {
		foreach(Log::getInstance()->debug_msg as $msg) { 
			Log::showMessage($msg, "alert");
		}
	}

	private static function showMessage($msg, $type = "error") {
		echo '<div class="message hidden" data-type="'.$type.'">'
			."<h3>".$type."!</h3><p>".$msg."</p></div>\n";
	}
}
?>
