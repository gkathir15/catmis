<?
/**
 * The class Log contains methods for handling log entries in the 
 * database.
 * @author	Kaspar Rosengreen Nielsen.
 */
class Log {
	/** 
	 * Delete a transaction in database.
	 * @param 	$moduleContentTypeId	Type of content.
	 * @param 	$moduleContentId		Identifier of content.
	 */	
	function deleteTransaction($moduleContentTypeId, $moduleContentId) {
		if (!empty($moduleContentTypeId) && !empty($moduleContentId)) {
			global $dbi;
			$dbi->query("DELETE FROM `".logTableName."` WHERE moduleContentTypeId=".$dbi->quote($moduleContentTypeId)." AND moduleContentId=".$dbi->quote($moduleContentId));
		}
	}
	
	/**
	 * Get the full name of user with a given user identifier.
	 * @param	$userId	Identifier of user to get name for.
	 */
	function getFullname($userId) {
		if (!empty($userId)) {
			$user = new User($userId);
			if (!empty($user->name)) return $user->name;
		}
		return "";
	}

	/**
	 * Get time a given resource was last updated.
	 * @param	$moduleContentTypeId	Identifier of module content type.
	 * @param	$moduleContentId		Identifier of module content.
	 * @return	Time the given resource was last updated.
	 */	
	function getLastUpdated($moduleContentTypeId, $moduleContentId) {
		global $dbi;
		$result = $dbi->query("SELECT UNIX_TIMESTAMP(lastUpdated) FROM `".logTableName."` WHERE moduleContentTypeId=".$dbi->quote($moduleContentTypeId)." AND moduleContentId=".$dbi->quote($moduleContentId));
		if ($result->rows()) {
			list($lastUpdated) = $result->fetchrow_array();
			return $lastUpdated;
		}
		return 0;		
	}
	
	function getNumberOfReads($moduleContentTypeId,$moduleContentId) {
		$totalReads = 0;
		if (!empty($moduleContentTypeId) && !empty($moduleContentId)) {
			global $dbi, $login;
			
			// Get total number of reads
			$result = $dbi->query("SELECT totalReads FROM `".logReadsTableName."` WHERE moduleContentTypeId=".$dbi->quote($moduleContentTypeId)." AND moduleContentId=".$dbi->quote($moduleContentId));
			if ($result->rows()) {
				list($totalReads) = $result->fetchrow_array();
			}
		}
		return $totalReads;
	}
	
	/** 
	 * Function that returns a new number of reads of a given resource.
	 * @param 	$reads 					Current number of reads.
	 * @param 	$moduleContentTypeId 	Type of resource.
	 * @param 	$moduleContentId		Identifier og resource.
	 */
	function incrementNumberOfReads($moduleContentTypeId,$moduleContentId) {
		$totalReads = 0;
		if (!empty($moduleContentTypeId) && !empty($moduleContentId)) {
			global $dbi, $login;
	
			// Generate time interval (default 1 day)
			$time = getdate();
			$starttime = mktime(0,0,0,$time["mon"],$time["mday"]-7,$time["year"]);
	
			// Delete all entries older than 14 days
			$dbi->query("DELETE FROM ".logReadsIpTableName." WHERE timestamp<FROM_UNIXTIME($starttime)");

			// Get total number of reads
			$entryExists = false;
			$result = $dbi->query("SELECT totalReads FROM `".logReadsTableName."` WHERE moduleContentTypeId=".$dbi->quote($moduleContentTypeId)." AND moduleContentId=".$dbi->quote($moduleContentId));
			if ($result->rows()) {
				list($totalReads) = $result->fetchrow_array();
				$entryExists = true;
			}

			// Administrators doesn't generate hits
			if(!$login->isWebmaster() && !$login->isAdmin()) {
				// Get user ip
				$ip = getenv("REMOTE_ADDR");

				// Determine if the user has visited this resource within the last day
				$starttime = mktime(0,0,0,$time["mon"],$time["mday"],$time["year"]);
				$result = $dbi->query("SELECT id FROM ".logReadsIpTableName." WHERE ModuleContentTypeId=".$dbi->quote($moduleContentTypeId)." AND ModuleContentId=".$dbi->quote($moduleContentId)." AND timestamp>FROM_UNIXTIME(".$starttime.") AND ip=".$dbi->quote($ip));
				if(!$result->rows()) {
					$dbi->query("INSERT INTO `".logReadsIpTableName."`(ModuleContentTypeId,ModuleContentId,Ip,Timestamp) VALUES(".$dbi->quote($moduleContentTypeId).",".$dbi->quote($moduleContentId).",".$dbi->quote($ip).",NOW())");
					$totalReads++;
				}

				// Update number of reads
				if ($entryExists) {
					$dbi->query("UPDATE `".logReadsTableName."` SET TotalReads=".$dbi->quote($totalReads)." WHERE ModuleContentTypeId=".$dbi->quote($moduleContentTypeId)." AND ModuleContentId=".$dbi->quote($moduleContentId));
				}
				else {
					$dbi->query("INSERT INTO `".logReadsTableName."`(ModuleContentTypeId,ModuleContentId,TotalReads) VALUES(".$dbi->quote($moduleContentTypeId).",".$dbi->quote($moduleContentId).",".$dbi->quote($totalReads).")");	
				}
			}
		}
		return $totalReads;
	}
	
	/** 
	 * Function that logs a transaction in database
	 * @param 	$moduleContentTypeId	Type of content.
	 * @param 	$moduleContentId		Identifier of content.
	 */
	function logTransaction($moduleContentTypeId,$moduleContentId) {
		if (!empty($moduleContentTypeId) && !empty($moduleContentId)) {
			global $dbi,$login;
			
			// Log transaction	
			$result = $dbi->query("SELECT id FROM `".logTableName."` WHERE moduleContentTypeId=".$dbi->quote($moduleContentTypeId)." AND moduleContentId=".$dbi->quote($moduleContentId));	
			if($result->rows()) {	
				list($id) = $result->fetchrow_array();
				$dbi->query("UPDATE `".logTableName."` SET uploaded=uploaded,lastUpdated=NOW(),lastUpdatedBy='".$login->id."' WHERE id=".$id);	
			}	
			else {		
				$dbi->query("INSERT INTO `".logTableName."`(moduleContentTypeId,moduleContentId,uploaded,uploadedBy,lastUpdated,lastUpdatedBy) VALUES(".$dbi->quote($moduleContentTypeId).",".$dbi->quote($moduleContentId).",NOW(),'".$login->id."',NOW(),'".$login->id."')");	
			}
		}
	}
	
	/** 
	 * Function that prints a transaction from database
	 * @param 	$moduleContentTypeId	Type of content.
	 * @param 	$moduleContentId		Identifier of content.
	 */
	function printTransactions($moduleContentTypeId,$moduleContentId) {	
		if (!empty($moduleContentTypeId) && !empty($moduleContentId)) {
			global $dbi, $login, $site;
			
			// Include language
			include scriptPath."/include/language/".pageLanguage."/general.php";
			
			// Print transaction
			$result = $dbi->query("SELECT UNIX_TIMESTAMP(uploaded),uploadedBy,UNIX_TIMESTAMP(lastUpdated),lastUpdatedBy FROM `".logTableName."` WHERE moduleContentTypeId=".$dbi->quote($moduleContentTypeId)." AND moduleContentId=".$dbi->quote($moduleContentId));	
			if($result->rows()) {
				list($uploaded,$uploadedBy,$lastUpdated,$lastUpdatedBy) = $result->fetchrow_array();
				if (!empty($uploadedBy) || !empty($lastUpdatedBy)) {
					echo "<p class=\"small1\">";
					$uploadName = $this->getFullname($uploadedBy);
					$lastUpdatedName = $this->getFullname($lastUpdatedBy);
					
					if(!empty($uploadedBy)) echo $lLog["CreatedBy"]." ".(!empty($uploadName)?$site->generatePopupLink(scriptUrl."/".fileUserProfile."?profileId=".$uploadedBy."&amp;popup=1",$uploadName):"<i>".$lLog["UnknownUser"]."</i>")." ".$site->generateTimestamp($uploaded)."<br />";
					if(!empty($lastUpdatedBy)) echo $lLog["LastUpdatedBy"]." ".(!empty($lastUpdatedName)?$site->generatePopupLink(scriptUrl."/".fileUserProfile."?profileId=".$lastUpdatedBy."&amp;popup=1",$lastUpdatedName):"<i>".$lLog["UnknownUser"]."</i>")." ".$site->generateTimestamp($lastUpdated);
					echo "</p>";	
				}
			}
		}
	}
}
?>