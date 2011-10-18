<?
class MySQLResult {
	var $query;
	var $res;
	var $debug;
	var $row;
	var $session;
	var $error;

	function MySQLResult ($session, $query, $debug) {
		global $login, $SERVER_NAME;
		$this->query = $query;
		$this->debug = $debug;
		$this->session = $session;

		if ($this->debug) { // Log the query
			$fd = fopen("/tmp/dbi.$SERVER_NAME.log", "a") or die ("Couldn't append to file");
			fputs($fd, $this->query."\n==================\n");
			fclose($fd);
		}

		if (!($this->res = mysql_query($this->query, $this->session))) {
			if (defined("installing")) {
				print "<b>A database error has occoured executing the following query: \"".$this->query."\".</b>. ".mysql_error().".";
			}
			else if (!empty($login)  || debug) {
				if ($login->isWebmaster() || debug) {
					print "<b>A database error has occoured executing the following query: \"".$this->query."\".</b>. ".mysql_error().".";
				}
			}
			else {
				print "<b>A database error has occoured.".(defined('pageAdminMail')?" Please contact the ".protectMail(pageAdminMail, "webmaster")." of this site to report the problem.":"")."</b>";
			}
			$this->error = 1;
			exit();
		}

		$this->row = 0;
	}

	function errors() {
		return $this->error;
	}

	function fetchrow_array () {
		if ($this->error) return array();
		return @mysql_fetch_row($this->res);
	}

	function fetchrow_hash () {
		return @mysql_fetch_array($this->res);
	}

	function fetchrow_assoc () {
		if ($this->error) return array();
		return @mysql_fetch_array($this->res, MYSQL_ASSOC);
	}
	
    function fetchrow_object($class=null, $params=null){
        return mysql_fetch_object($this->res, $class, $params);
    }	

	function rows () {
		if ($this->error) return 0;
		return mysql_num_rows($this->res);
	}

	function fields () {
		if ($this->error) return 0;
		return mysql_num_fields($this->res);
	}

	function finish () {
		if (!$this->error) mysql_free_result($this->res);
	}
}
?>