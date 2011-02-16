<?
// Include common functions and declarations
require_once "../../include/common.php";

// Get content type and content identifier
$moduleContentTypeId = getGetValue("moduleContentTypeId");
$moduleContentId = getGetValue("moduleContentId");

$revisionNumber = getValue("revisionNumber");
$textfieldIndex = getGetValue("textfieldIndex");

if (!empty($moduleContentTypeId) && !empty($moduleContentId)) {
	if(!$login->hasEditPermission($moduleContentTypeId, $moduleContentId)) {
		$login->printLoginForm();
		exit();
	}
	
	// Get info on module type
	$moduleId = 0;
	$title = "";
	$result = $dbi->query("SELECT moduleId, title FROM ".moduleContentTypeTableName." WHERE id=".$dbi->quote($moduleContentTypeId));
	if ($result->rows()) {
		list($moduleId, $title) = $result->fetchrow_array();
	}

	// Get module content type object
	$moduleContentTypeObject = $module->getModuleContentTypeObject($moduleContentTypeId);
	
	// Revert to previous revision
	if (!empty($_POST["revert"])) {
		if (method_exists($moduleContentTypeObject,"restoreRevision")) {
			$moduleContentTypeObject->restoreRevision($moduleContentId, $revisionNumber);
		}
		else if (method_exists($moduleContentTypeObject,"setText")) {
			$text = method_exists($moduleContentTypeObject,"getText") ? $moduleContentTypeObject->getText($moduleContentId, $textfieldIndex) : "";
			if (!empty($revisionNumber)) $text = $revision->getTextRevision(pageModuleId, pageContentTypeId, $moduleContentId, $text, 0, $revisionNumber);			
			$moduleContentTypeObject->setText($moduleContentId, $text, $textfieldIndex);
			$revisionNumber = 0;
		}
	}
	
	// Add navigation links
	$site->addNavigationLink(scriptUrl."/".folderAdmin, $lAdminIndex["Header"]);
	$site->addNavigationLink(scriptUrl."/".folderRevision."/index.php?moduleContentTypeId=".$moduleContentTypeId."&amp;moduleContentId=".$moduleContentId, $lRevisions["Header"]);
	
	// Print common header
	$site->printHeader();

	// Print page description
	printf("<p>".$lRevisions["HeaderText"]."</p>", method_exists($moduleContentTypeObject,"getName")?$moduleContentTypeObject->getName($moduleContentId):"-");
?>
<form action="index.php?moduleContentTypeId=<?= $moduleContentTypeId ?>&amp;moduleContentId=<?= $moduleContentId ?>" method="post">
<?
echo '<center>';
echo '<select name="revisionNumber">';
$result = $dbi->query("SELECT revision,UNIX_TIMESTAMP(timestamp) FROM ".revisionTableName." WHERE moduleId=".$dbi->quote($moduleId)." AND moduleContentTypeId=".$dbi->quote($moduleContentTypeId)." AND moduleContentId=".$dbi->quote($moduleContentId)." GROUP BY revision DESC");
echo '<option value="0">'.($result->rows()+1).'</option>';
if ($result->rows()) {
	for ($i=0; list($revisionId,$timestamp) = $result->fetchrow_array(); $i++) {
		echo '<option value="'.$revisionId.'"'.($revisionId==$revisionNumber ? ' selected="selected"' : '').'>'.$revisionId.' ('.$site->generateTimestamp($timestamp).')</option>';
	}
}
echo '</select> <input type="submit" value="Show revision" /> <input name="revert" type="submit" value="Revert to revision" />';
echo '</center>';
echo '<br />';

if (empty($revisionNumber)) $revisionNumber = $result->rows()+1;

$text = method_exists($moduleContentTypeObject,"getText") ? $moduleContentTypeObject->getText($moduleContentId, $textfieldIndex) : "";
if (method_exists($moduleContentTypeObject,"printRevision")) {
	$moduleContentTypeObject->printRevision($moduleContentId, $revisionNumber);
}
else {
	if (!empty($revisionNumber)) $text = $revision->getTextRevision(pageModuleId, pageContentTypeId, $moduleContentId, $text, 0, $revisionNumber);
	echo $text;
}

/*echo '<h2>Changes</h2>';
$result = $revision->getTextRevisionChanges(pageModuleId, pageContentTypeId, $moduleContentId, $text, $revisionNumber);
print_r($result);*/
?>
</form>

<?	
	// Print common footer
	$site->printFooter();
}
?>