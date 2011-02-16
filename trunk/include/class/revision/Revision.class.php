<?
require_once dirname(__FILE__)."/Diff.class.php";

class Revision {
	/**
	 * Get text as it looked at a given revision.
	 * @param	$revision	Revision number.
	 * @return 	text at the given revision number.
	 */
	public function getTextRevision($moduleId, $moduleContentTypeId, $moduleContentId, $newText, $textfieldIndex, $revision) {
		global $dbi;
		$result = $dbi->query("SELECT diff FROM ".revisionTableName." WHERE moduleId=".$dbi->quote($moduleId)." AND moduleContentTypeId=".$dbi->quote($moduleContentTypeId)." AND moduleContentId=".$moduleContentId." AND revision>=".$dbi->quote($revision)." AND textfieldIndex=".$dbi->quote($textfieldIndex)." ORDER BY revision DESC");
		if ($result->rows()) {
			$diff = new Diff();
			$text = parseString($newText);
			for ($i=0; list($diffText) = $result->fetchrow_array(); $i++) {
				$changes = unserialize(parseString($diffText));
				$text = $diff->renderChanges($changes, $text);
			}
			return $text;
		}
		return $newText;
	}
	
	public function getTextRevisionChanges($moduleId, $moduleContentTypeId, $moduleContentId, $newText, $revision) {
		global $dbi;
		$result = $dbi->query("SELECT diff FROM ".revisionTableName." WHERE moduleId=".$dbi->quote($moduleId)." AND moduleContentTypeId=".$dbi->quote($moduleContentTypeId)." AND moduleContentId=".$moduleContentId." AND revision>=".$dbi->quote($revision)." ORDER BY revision DESC");
		if ($result->rows()) {
			$diff = new Diff();
			$text = parseString($newText);
			for ($i=0; list($diffText) = $result->fetchrow_array(); $i++) {
				$changes = unserialize(parseString($diffText));
				$text = $diff->renderChanges($changes, $text);
				
				$result = $diff->stringDiff($text, $newText);
				print_r($result);
				$diffString = $diff->renderDiff($result);
				return $diffString;
			}
		}
		return "";
	}	
	
	public function saveTextRevision($moduleId, $moduleContentTypeId, $moduleContentId, $oldText, $newText, $textfieldIndex=0, $revision=0) {
		global $dbi,$login,$settings;
		
		if (!$settings->enableRevisioning) return;
		
		// Get latest revision number and increment
		$maxRevision = 0;
		if (empty($revision)) {
			$result = $dbi->query("SELECT MAX(revision) FROM ".revisionTableName." WHERE moduleId=".$dbi->quote($moduleId)." AND moduleContentTypeId=".$dbi->quote($moduleContentTypeId)." AND moduleContentId=".$dbi->quote($moduleContentId));
			if ($result->rows()) {
				list($maxRevision) = $result->fetchrow_array();
				$maxRevision++;
			}
		}
		else {
			$maxRevision = $revision;
		}

		// Parse text strings
		$newText = parseString($newText);
		$oldText = parseString($oldText);
		
		// Calculate diff
		$diff = new Diff();
		$result = $diff->stringDiff($newText, $oldText, " ");
		$changes = $diff->sequentialChanges($result);
		if (sizeof($changes) > 0) {
			$serializedChanges = serialize($changes);

			// Insert diff
			$dbi->query("INSERT INTO ".revisionTableName."(moduleId,moduleContentTypeId,moduleContentId,textfieldIndex,diff,revision,userId,timestamp) VALUES(".$dbi->quote($moduleId).",".$dbi->quote($moduleContentTypeId).",".$dbi->quote($moduleContentId).",".$dbi->quote($textfieldIndex).",".$dbi->quote($serializedChanges).",".$dbi->quote($maxRevision).",".$dbi->quote($login->id).",NOW())");		
		}
		return $maxRevision;
	}
}
?>