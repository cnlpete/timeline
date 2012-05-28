<?php
/* 
 *
 * @link http://github.com/cnlpete/timeline
 * @author Hauke Schade <http://hauke-schade.de>
 *
 */

namespace Timeline;

require_once('config.class.php');
require_once('log.class.php');
require_once 'singleton.helper.php';

class DB extends Singleton {
	private $db_connect_id = null;
	private $query_result;

	private function __construct() {
		if ( !$this->db_connect_id ) {
		  $oConfig = Config::getInstance();
			if ( !$this->db_connect_id = mysql_connect($oConfig->db_host . ":" . $oConfig->db_port,
				$oConfig->db_user,
				$oConfig->db_passwd) ) {
				Log::critical("could not login using ".$oConfig->db_user."@".$oConfig->db_host.":".$oConfig->db_port);
				return false;
			}

			if ( !mysql_select_db($oConfig->db_name, $this->db_connect_id) ) {
				$this->sql_close();
				Log::critical("could not select database: ".$oConfig->db_name);
				return false;
			}
			$this->sql_query("SET NAMES 'utf8'");
		}
	}

	public static function queryAssocAtom($sSql) {
		$db = DB::getInstance();

		$oResult = $db->sql_query($sSql);

		if( $oResult == null)
			Log::critical("Cannot execute query! ($sSql)");
		$aRow = $db->sql_fetchrow($oResult);

		return $aRow;
	}

	public static function queryAssocAtomFirst($sSql) {
		$aResult = DB::queryAssocAtom($sSql);

		return $aResult[0];
	}

	public static function queryAssoc($sSql) {
		$db = DB::getInstance();

		$oResult = $db->sql_query($sSql);
		if( $oResult == null) {
			Log::debug("Cannot execute query! ($sSql)");
			return false;
		}

		$aRows = array();
		while($aRow = $db->sql_fetchrow($oResult)) {
			if( $oResult === false)
				break;
			$aRows[] = $aRow;
		}

		return $aRows;
	}

	public static function execute($sSql) {
		$db = self::getInstance();

		$oResult = $db->sql_query($sSql);

		if( $oResult == null) {
			Log::debug("Cannot execute query! ($sSql)");
			return false;
		}
		else
			return true;
	}

	static function escape($string) {
		$db = DB::getInstance();

		if (get_magic_quotes_gpc()) { // magic quotes aktiviert?
			$string = stripslashes($string);
		}
		return mysql_real_escape_string($string, $db->db_connect_id);
	}

	function sql_close() {
		if ( $this->db_connect_id )
			return mysql_close($this->db_connect_id);

		return false;
	}

	function sql_insert_id() {
		return mysql_insert_id($this->db_connect_id);
	}

	function sql_query($query) {
		Log::debug($query);
		return $this->query_result = mysql_query($query, $this->db_connect_id);
	}

	function sql_fetchrow($query_result = NULL) {
		if ( !$query_result )
			$query_result = $this->query_result;

		return  mysql_fetch_assoc($query_result);
	}

	function sql_affectedrows() {
		return mysql_affected_rows($this->db_connect_id);
	}

	function sql_error(){
		return mysql_errno($this->db_connect_id) . ": " . mysql_error($this->db_connect_id);
	}

	function begin() {
		$sSql = "START TRANSACTION;";
		$oResult = $this->sql_query($sSql);
		if( $oResult == null)
			Log::critical("Cannot execute query!");

		return true;
	}

	function commit() {
		$sSql = "COMMIT;";
		$oResult = $this->sql_query($sSql);
		if( $oResult == null)
			Log::critical("Cannot execute query!");

		return true;
	}

	function rollback() {
		$sSql = "ROLLBACK;";
		$oResult = $this->sql_query($sSql);
		if( $oResult == null)
			Log::critical("Cannot execute query!");

		return true;
	}

	function mysql_num_rows($query_result = NULL) {
		if ( !$query_result )
			$query_result = $this->query_result;

		return mysql_num_rows($query_result);
	}

}

?>
