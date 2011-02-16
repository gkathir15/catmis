<?
// Include common functions and declarations
require_once "../../include/common.php";

// Check if user is webmaster
if(!$login->isWebmaster()) {
	$login->printLoginForm();
	exit();
}

// Delete entries
$deleteEntries = getValue("deleteEntries");
if(!empty($deleteEntries)) {
	$logs = getValue("logs");
	for($i=0; $i<sizeof($logs); $i++) {
		$dbi->query("DELETE FROM `".logTableName."` WHERE id=".$dbi->quote($logs[$i]));
	}

	// Redirect to log index 
	redirect(!empty($_SERVER["HTTP_REFERER"])?$_SERVER["HTTP_REFERER"]:scriptUrl."/".folderLog."/".fileLogIndex);
}

// Validate page
$pageNumber = getGetValue("page");
if (!empty($pageNumber)) $pageNumber = $pageNumber-1;

// Get type id
$typeId = getGetValue("typeId");

// Add navigation links
$site->addNavigationLink(scriptUrl."/".folderAdmin, $lAdminIndex["Header"]);
$site->addNavigationLink(scriptUrl."/".folderLog, $lLogIndex["Header"]);

// Print common header
$site->printHeader();

// Print page description
echo "<p>".$lLogIndex["HeaderText"]."</p>";
?>

<center>
<form action="index.php" method="get">
<select name="typeId" class="normalInput" style="width:25%">
<option value="0"><?= $lLogIndex["AllTypes"] ?></option>
<?
$types = array();
$result2 = $dbi->query("SELECT id FROM ".moduleContentTypeTableName);
if ($result2->rows()) {
	for ($i=0; list($id)=$result2->fetchrow_array(); $i++) {
		$moduleContentTypeObject = $module->getModuleContentTypeObject($id);
		if (!empty($moduleContentTypeObject)) {
			if (method_exists($moduleContentTypeObject, "getName")) {
				$name = $moduleContentTypeObject->getName();
				if (!empty($name)) {
					$types[$i][0] = $name;
					$types[$i][1] = $id;
				}
			}
		}
	}
	
	sort($types);
	
	for ($i=0; $i<sizeof($types); $i++) {
?>
<option value="<?= $types[$i][1] ?>"<?= $typeId==$types[$i][1]?" selected=\"selected\"":"" ?>><?= $types[$i][0] ?></option>
<?
	}
	$result2->finish();
}
?>
</select> <input type="submit" value="<?= $lSearch["Header"] ?>" class="button" />
</form>
</center>

<?
	// Select transactions from log
	$result = $dbi->query("SELECT id,moduleContentTypeId,moduleContentId,uploadedBy,lastUpdatedBy,UNIX_TIMESTAMP(uploaded),UNIX_TIMESTAMP(lastUpdated) FROM ".logTableName.(!empty($typeId)?" WHERE moduleContentTypeId=".$dbi->quote($typeId):"")." ORDER BY lastUpdated DESC LIMIT ".($pageNumber*100).",100");
	if ($result->rows()) {
?>

<br />
<form name="logForm" action="<?= scriptUrl."/".folderLog."/".fileLogIndex ?>" method="post">
<table width="100%" cellspacing="0" cellpadding="2" border="0" summary="" class="index">
<tr>
<td class="indexHeader">
&nbsp;
</td>

<td class="indexHeader">
<?= $lLogIndex["LastModified"] ?>
</td>

<td class="indexHeader">
<?= $lLogIndex["Resource"] ?>
</td>

<td class="indexHeader">
<?= $lLogIndex["Type"] ?>
</td>

<td class="indexHeader">
<?= $lLogIndex["LastModifiedBy"] ?>
</td>
</tr>
<?
	// Print lines
	for($i=0;(list($id,$moduleContentTypeId,$moduleContentId,$uploadedBy,$lastUpdatedBy,$uploaded,$lastUpdated)=$result->fetchrow_array());$i++) {
		$uploader = new User($uploadedBy);
		$modifier = new User($lastUpdatedBy);
?>
<tr>
<td height="30" class="item<?= $i%2==0?"Alt":"" ?>">
<input type="checkbox" name="logs[]" value="<?= $id ?>" />
</td>

<td width="20%" class="small1 item<?= $i%2==0?"Alt":"" ?>">
<?= date("d/m/y H:i", ($uploaded!=$lastUpdated?$lastUpdated:$uploaded)) ?>
</td>

<td width="40%" class="small1 item<?= $i%2==0?"Alt":"" ?>">
<?
$moduleContentType = $module->getModuleContentTypeObject($moduleContentTypeId);
if ($moduleContentType!=null) {
	if (method_exists($moduleContentType,"getName")) {
		$name = trim($moduleContentType->getName($moduleContentId));
		$link = method_exists($moduleContentType,"getLink")?$moduleContentType->getLink($moduleContentId):"";

		if (!empty($name) && !empty($link))	{		
			echo '<a href="'.$link.'">'.validateTextLength($name, 30).'</a>';
		}
		else if (!empty($name)) {
			echo validateTextLength($name, 30);
		}
		else {
			echo "-";
		}
	}
	else {
		echo "-";
	}
}
else {
	echo "-";
}
?>
</td>

<td width="20%" class="small1 item<?= $i%2==0?"Alt":"" ?>">
<?
if ($moduleContentType!=null) {
	if (method_exists($moduleContentType, "getName")) {
		echo $moduleContentType->getName();
	}
}
else {
	echo "Unknown";
}
?>
</td>

<td width="20%" class="small1 item<?= $i%2==0?"Alt":"" ?>">
<? $site->printPopupLink($modifier->getProfileLink()."&amp;popup=1", $modifier->username) ?>
</td>
</tr>
<?
	}
?>
</table>

<table width="100%">
<tr>
<td>
<input type="submit" name="deleteEntries" value="<?= $lButtons["Delete"] ?>" class="button" onclick="var agree=confirm('<?= $lLogIndex["ConfirmDelete"] ?>');if(agree) {return true;}else {return false;}" />
</td>

<td align="right">
<input type="button" name="selectNoneButton" value="<?= $lButtons["SelectNone"] ?>" class="button" onclick="selectAll(document.usersForm, this, false)" /> <input type="button" name="selectAllButton" value="<?= $lButtons["SelectAll"] ?>" class="button" onclick="selectAll(document.usersForm, this, true)" />
</td>
</tr>
</table>
</form>

<?
	// Generate page links
	$result = $dbi->query("SELECT COUNT(*) FROM ".logTableName.(!empty($typeId)?" WHERE moduleContentTypeId=".$dbi->quote($typeId):""));
	if($result->rows()) {
		list($count) = $result->fetchrow_array();
		echo "<p align=\"center\">";
		$site->printPageLinks(folderLog."/".fileLogIndex."?",$pageNumber,$count,100);
		echo "</p>";
	}
}
else {		
	echo "<p><i>".$lLogIndex["NoLogEntries"]."</i></p>";
}

// Print common footer
$site->printFooter();
?>