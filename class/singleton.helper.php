<?php
/** 
 * This class is meant to give some other classes singleton capabilities by 
 * extending this class.
 *
 * @link http://github.com/cnlpete/timeline
 * @author Hauke Schade <http://hauke-schade.de>
 *
 */

namespace Timeline;

class Singleton {

	private static $oInstance = null;

	static function getInstance() {
		if( !self::$oInstance ) {
			self::$oInstance = new Log();
		}
		if( !self::$oInstance ) {
			self::critical('Could not create singleton.');
		}
		
		return self::$oInstance;
	}
}

