<?
// Include common functions and declarations
require_once "../../include/common.php";

// Create Group object
$group = new Group();

// Check user permissions
if (!$group->hasEditPermission()) {
	$login->printLoginForm();
	exit();
}

// Delete groups
$deleteGroups = getValue("deleteGroups");
if(!empty($deleteGroups)) {
	$groups = getValue("groups");
	for($i=0; $i<sizeof($groups); $i++) {
		$group = new Group($groups[$i]);
		$group->deleteGroup();
	}

	// Redirect to group index 
	redirect(!empty($_SERVER["HTTP_REFERER"])?$_SERVER["HTTP_REFERER"]:scriptUrl."/".folderGroups."/".fileGroupIndex);
}

// Set number of groups to display per page
$groupLimit = 30;

// Get page number
$page = getGetValue("page");
if (!empty($page)) $page = $page-1;

// Get search parameters
$search_string = getValue("searchString");

// Add navigation links
$site->addNavigationLink(scriptUrl."/".folderAdmin, $lAdminIndex["Header"]);
$site->addNavigationLink(scriptUrl."/".folderGroups."/".fileGroupIndex, $lGroupIndex["Header"]);
	
// Print common header
$site->printHeader();

// Print page description
echo "<p>".$lGroupIndex["HeaderText"]."</p>";

// Fetch groups
$result = $dbi->query("SELECT id FROM `".groupTableName."`".(!empty($search_string)?" WHERE name LIKE ".$dbi->quote($search_string."%")." OR description LIKE ".$dbi->quote($search_string."%"):"")." ORDER BY name LIMIT ".($page*$groupLimit).",".$groupLimit);

if ($result->rows() || !empty($search_string)) {
?>
<center>
<form action="<?= scriptUrl."/".folderGroups."/".fileGroupIndex ?>" method="get">
<input type="text" name="search_string" value="<?= $search_string ?>" class="normalInput" /> <input type="submit" value="<?= $lSearch["Header"] ?>" class="button" />
</form>
</center>

<?
}

// Print group index
if ($result->rows()) {
?>

<br />
<form name="groupsForm" action="<?= scriptUrl."/".folderGroups."/".fileGroupIndex ?>" method="post">
<table width="100%" cellspacing="0" cellpadding="2" border="0" summary="" class="index">
<tr>
<td colspan="2" class="indexHeader">
&nbsp;
</td>

<td class="indexHeader">
<b><?= $lGroupIndex["Name"] ?></b>
</td>

<td class="indexHeader">
<b><?= $lGroupIndex["Description"] ?></b>
</td>

<td class="indexHeader" align="center">
<b><?= $lGroupIndex["NoOfMembers"] ?></b>
</td>
</tr>
<?
	for ($i = 0;(list ($id) = $result->fetchrow_array()); $i ++) {
		$group = new Group($id);
?>
<tr>
<td height="30" class="item<?= $i%2==0?"Alt":"" ?>">
<input type="checkbox" name="groups[]" value="<?= $group->id ?>" />
</td>

<td height="30" width="16" class="<?= $i%2==0?"itemAlt":"item" ?>">
<a href="<?= scriptUrl."/".folderGroups."/".fileGroupEdit ?>?groupId=<?= $group->id ?>"><img src="<?= iconUrl ?>/edit.gif" height="16" width="16" border="0" alt="<?= $lGroupIndex["EditGroup"] ?>" name="<?= $lGroupIndex["EditGroup"] ?>" /></a>
</td>

<td width="30%" class="<?= $i%2==0?"itemAlt":"item" ?>">
<a href="<?= scriptUrl."/".folderUsers."/".fileUserIndex."?group=".$group->id ?>"><?= $group->name ?></a>
</td>

<td width="40%" class="small1 <?= $i%2==0?"itemAlt":"item" ?>">
<?= !empty($group->description)?$group->description:"-" ?>
</td>

<td width="30%" class="small1 <?= $i%2==0?"itemAlt":"item" ?>" align="center">
<?= $group->getNumberOfUsers() ?>
</td>
</tr>
<?
	}
?>
</table>

<table width="100%">
<tr>
<td>
<input type="submit" name="deleteGroups" value="<?= $lButtons["Delete"] ?>" class="button" onclick="var agree=confirm('<?= $lGroupIndex["ConfirmDelete"] ?>');if(agree) {return true;}else {return false;}" />
</td>

<td align="right">
<input type="button" name="selectNoneButton" value="<?= $lButtons["SelectNone"] ?>" class="button" onclick="selectAll(document.groupsForm, this, false)" /> <input type="button" name="selectAllButton" value="<?= $lButtons["SelectAll"] ?>" class="button" onclick="selectAll(document.groupsForm, this, true)" />
</td>
</tr>
</table>
</form>
<br />
<?
	// Get number of groups
	$count = 0;
	$result = $dbi->query("SELECT COUNT(*) FROM `".groupTableName.(!empty($search_string)?"` WHERE name LIKE ".$dbi->quote($search_string."%"):""));
	if ($result->rows()) {
		list($count) = $result->fetchrow_array();
	}

	// Generate page index
	echo "<br /><center>";
	echo $site->generatePageLinks(folderGroups."/".fileGroupIndex."?".(!empty($search_string)?"search_string=$search_string&amp;":"").(!empty($sortby)?"sortby=$sortby&amp;":""), $page, $count, $groupLimit);
	echo "</center>";
}
else {
	if (!empty($search_string)) {
		printf("<p><i>".$lGroupIndex["NoResults"]."</i></p>", $search_string);
	}
	else {
		echo "<p><i>".$lGroupIndex["NoGroups"]."</i></p>";
	}
}

// Print common footer
$site->printFooter();
?>