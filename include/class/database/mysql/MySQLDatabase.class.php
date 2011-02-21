<?
class MySQLDatabase {
	var $session;
	var $debug = 0;
	var $queries = array();
	var $lastQuery = "";
	var $columnDefinitions = array();

	function MySQLDatabase($dbhost,$db,$user,$pass) {
		$this->session = mysql_connect($dbhost,$user,$pass) or die("Could not connect: ". mysql_error());
		mysql_select_db($db,$this->session) or die("Could not select database: ".mysql_error());
	}
	
	function addColumnDefinition($table, $column, $columnAttributes="varchar(255)", $after="") {
		$index = sizeof($this->columnDefinitions);
		$this->columnDefinitions[$index]["table"] = sprintf($table, dbPrefix);
		$this->columnDefinitions[$index]["column"] = $column;
		$this->columnDefinitions[$index]["columnAttributes"] = $columnAttributes;
		$this->columnDefinitions[$index]["after"] = $after;
	}
	
	function addColumnIfNotExists($table, $column, $column_attr = "VARCHAR(255) NULL",$after=""){
	    $exists = false;
	    $columns = mysql_query("show columns from ".$table);
	    while($c = mysql_fetch_assoc($columns)) {
	        if($c['Field'] == $column) {
	            $exists = true;
	            break;
	        }
	    }
	    if (!$exists){
	        mysql_query("ALTER TABLE `$table` ADD `$column` $column_attr".(!empty($after) ? " AFTER ".$after : ""));
	    }
	}

	function createTables($dbTableDefs) {
		// Begin transaction
		$this->query("BEGIN");
		
	    // Create the tables.
	    foreach ($dbTableDefs as $tableName => $tableDef) {
	        $this->query(sprintf($tableDef, dbPrefix));
	    }
	
		// Alter columns
		foreach ($this->columnDefinitions as $columnDefinition) {
			$this->addColumnIfNotExists($columnDefinition["table"],$columnDefinition["column"],$columnDefinition["columnAttributes"],$columnDefinition["after"]);
		}
		
		// Commit transaction
		$this->query("COMMIT");
	}
	
	function getInsertId() {
		return mysql_insert_id();
	}
	
	function getLastQuery() {
		return $this->lastQuery;
	}
	
	function parse_mysql_dump($path, $ignoreerrors = false) {
		$file_content = file($path);
		$query = "";
		foreach($file_content as $sql_line) {
			$sql_line = trim($sql_line);
			if ($sql_line!="" && substr($sql_line,0,1)!="#" && substr($sql_line,0,2)!="--") {
				$query .= $sql_line;
				if(preg_match("/;\s*$/", $sql_line)) {
					$result = mysql_query($query) or die(mysql_error().". Query: ".$query);
					$query = "";
				}
			}
		}
	}

	function query ($query) {
		$result = new MySQLResult($this->session, $query, $this->debug);
		if (diagnose) {
			global $log;
			$index = sizeof($this->queries);
			$this->queries[$index]["query"] = $query;
			$stacktrace = "";
			ob_start();
			print_r(debug_backtrace());
			$stacktrace = ob_get_contents();
			ob_end_clean();
			$log->logDataToFile("queries.txt", "---\n" . sizeof($this->queries) . ": " . $query . "\n" . $stacktrace . "\n\n");
			unset($stacktrace);
		}
		$this->lastQuery = $query;
		return $result;
	}

	function quote ($str) {
		return "'".addslashes(replaceSiteUrls($str))."'";
	}
}
?>
