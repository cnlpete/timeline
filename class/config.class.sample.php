<?php
/* 
 *
 * @link http://github.com/cnlpete/timeline
 * @author Hauke Schade <http://hauke-schade.de>
 *
 */

namespace Timeline;

class Config {
	private static $oInstance = null;

	public $db_host     = "localhost";
	public $db_name     = "";
	public $db_user     = "";
	public $db_passwd   = "";
	public $db_port     = 3306;

	public $mail_sender       = "noreply@example.de";
	public $mail_sender_name  = "Timeline Tool";
	public $mail_replyto      = "support@example.de";
	public $mail_replyto_name = "Timeline Tool Support";

	public $page_ref = "http://timeline.cnlpete.de/";

	public $tl_column_width     = 100;
	public $tl_event_padding_x  = 26;
	public $tl_event_padding_y  = 40;

	static function getInstance() {
		if( !self::$oInstance ) {
			self::$oInstance = new Config();
		}
		if( !self::$oInstance ) {
			Log::critical('Could not create Config singleton.');
		}

		return self::$oInstance;
	}
}
?>
