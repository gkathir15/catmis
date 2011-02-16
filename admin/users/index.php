<?
// Include common functions and declarations
require_once "../../include/common.php";

// Check if user is webmaster
$user = new User();
if (!$user->hasEditPermission()) {
	$login->printLoginForm();
	exit();
}

// Delete users
if(!empty($_POST["deleteUsers"])) {
	$users = getPostValue("users");
	for($i=0; $i<sizeof($users); $i++) {
		if (!empty($users[$i])) {
			$user = new User($users[$i]);
			$user->deleteUser();
		}
	}

	// Redirect to user index 
	redirect(scriptUrl."/".folderUsers."/".fileUserIndex);
}

// Set number of users to display per page 
$userLimit = 30;

// Get page number
$pageNumber = getGetValue("page");
if (!empty($pageNumber)) $pageNumber = $pageNumber-1;

// Get search parameters
$administrator = 0;	
$groupObject = null;
$guest = 0;
$webmaster = 0;

$search_string = getValue("search_string");
$sortby = getValue("sortby");
if (!($sortby=="u1.username" || $sortby=="u1.username desc" || $sortby=="u2.name" || $sortby=="u2.name desc" || $sortby=="u1.registered" || $sortby=="u1.registered desc")) {
	$sortby = "u1.registered desc";
}

$group = getValue("group");
if (!empty($group)) {
	switch ($group) {
		case "webmaster":
			$webmaster = 1;
			break;
		case "administrator":
			$administrator = 1;
			break;
		case "guest":
			$guest = 1;
			break;
		default:
			$groupObject = new Group($group);
			break;		
	}
}		

// Add navigation link
$site->addNavigationLink(scriptUrl."/".folderAdmin, $lAdminIndex["Header"]);
$site->addNavigationLink(scriptUrl."/".folderUsers, $lUserIndex["Header"]);
if (!empty($group)) {		
	$site->addNavigationLink(scriptUrl."/".fileUserIndex."/?sortby=".$sortby.(!empty($group)?"&amp;group=".$group:"").(!empty($search_string)?"&amp;search_string=".$search_string:""), $webmaster?$lUser["Webmaster"]:($administrator?$lUser["Administrator"]:($guest?$lUser["Guest"]:(!empty($groupObject)?$groupObject->name:""))));	
}

// Print common header
$site->printHeader();

// Print header text
echo "<p>".$lUserIndex["HeaderText"]."</p>";

// Fetch users	
if ($webmaster || $administrator || $guest || empty($group)) {
	$result = $dbi->query("SELECT u1.id FROM ".userTableName." as u1,".userDataTableName." as u2 WHERE u1.id=u2.id".(!empty($search_string)?" AND (u2.name LIKE ".$dbi->quote("%".$search_string."%")." OR u1.username LIKE ".$dbi->quote($search_string."%").")":"").($webmaster?" AND u1.webmaster=1":"").($administrator?" AND u1.administrator=1":"").($guest?" AND u1.administrator=0 AND u1.webmaster=0":"")." ORDER BY ".(!empty($sortby)?$sortby:"u1.registered DESC")." LIMIT ".($pageNumber*$userLimit).",".$userLimit);
}
else {
	$result = $dbi->query("SELECT u1.id FROM ".userTableName." as u1,".userDataTableName." as u2,`".userGroupRefTableName."` as g1 WHERE u1.id=u2.id AND g1.userId=u1.id AND g1.groupId=".$dbi->quote($group).(!empty($search_string)?" AND (u2.name LIKE ".$dbi->quote("%".$search_string."%")." OR u1.username LIKE ".$dbi->quote($search_string."%").")":"").($webmaster?" AND u1.webmaster=1":"").($administrator?" AND u1.administrator=1":"").($guest?" AND u1.administrator=0 AND u1.webmaster=0":"")." ORDER BY ".(!empty($sortby)?$sortby:"u1.registered DESC")." LIMIT ".($pageNumber*$userLimit).",".$userLimit);
}
?>

<center>
<form action="index.php" method="get">
<input type="text" name="search_string" value="<?= $search_string ?>" class="normalInput" style="width:150px" /> <select name="group" class="normalInput" style="width:150px">
<option value=""<?= empty($group) && !$guest && !$administrator && !$webmaster?" selected=\"selected\"":"" ?>><?= $lUser["AllUsers"] ?></option>
<option value="" style="font-weight:bold"><?= $lUserIndex["UserLevels"] ?></option>
<option value="guest"<?= $guest?" selected=\"selected\"":"" ?>>- <?= $lUser["Guest"] ?></option>
<option value="administrator"<?= $administrator?" selected=\"selected\"":"" ?>>- <?= $lUser["Administrator"] ?></option>
<option value="webmaster"<?= $webmaster?" selected=\"selected\"":"" ?>>- <?= $lUser["Webmaster"] ?></option>
<?
$result2 = $dbi->query("SELECT id,name FROM `".groupTableName."` ORDER BY name");
if ($result2->rows()) {
?>
<option value="" style="font-weight:bold"><?= $lUserIndex["Groups"] ?></option>
<?
	for ($i=0; list($id,$name)=$result2->fetchrow_array(); $i++) {
?>
<option value="<?= $id ?>"<?= $group==$id?" selected=\"selected\"":"" ?>>- <?= $name ?></option>
<?
	}
	$result2->finish();
}
?>
</select> <input type="submit" value="<?= $lSearch["Header"] ?>" class="button" />
</form>
</center>

<?	
// Print user index
if ($result->rows()) {
?>

<br />
<form name="usersForm" action="<?= scriptUrl."/".folderUsers."/".fileUserIndex ?>" method="post">
<table width="100%" cellspacing="0" cellpadding="2" border="0" summary="" class="index">
<tr>
<td colspan="2" class="indexHeader">
&nbsp;
</td>

<td class="indexHeader">
<a href="<?= fileUserIndex ?>?sortby=u1.username<?= $sortby=="u1.username"?" desc":"" ?><?= !empty($group)?"&amp;group=".$group:"" ?><?= !empty($search_string)?"&amp;search_string=".$search_string:"" ?>" class="columnHeader1"><b><?= $lUserIndex["Username"] ?></b></a><?= $sortby=="u1.username" || $sortby=="u1.username desc"?' <img src="'.iconUrl.'/sort'.($sortby=="u1.username desc"?"up":"down").'.gif" />':'' ?>
</td>

<td class="indexHeader">
<a href="<?= fileUserIndex ?>?sortby=u2.name<?= $sortby=="u2.name"?" desc":"" ?><?= !empty($group)?"&amp;group=".$group:"" ?><?= !empty($search_string)?"&amp;search_string=".$search_string:"" ?>" class="columnHeader1"><b><?= $lUserIndex["FullName"] ?></b></a><?= $sortby=="u2.name" || $sortby=="u2.name desc"?' <img src="'.iconUrl.'/sort'.($sortby=="u2.name desc"?"up":"down").'.gif" />':'' ?>
</td>

<td class="indexHeader">
<a href="<?= fileUserIndex ?>?sortby=u1.registered<?= $sortby=="u1.registered desc"?"":" desc" ?><?= !empty($group)?"&amp;group=".$group:"" ?><?= !empty($search_string)?"&amp;search_string=".$search_string:"" ?>" class="columnHeader1"><b><?= $lUserIndex["Registered"] ?></b></a><?= $sortby=="u1.registered" || $sortby=="u1.registered desc"?' <img src="'.iconUrl.'/sort'.($sortby=="u1.registered desc"?"up":"down").'.gif" />':'' ?>
</td>
</tr>
<?
	for ($i = 0;(list ($id) = $result->fetchrow_array()); $i ++) {
		$user = new User($id);
?>
<tr>
<td height="30" class="item<?= $i%2==0?"Alt":"" ?>">
<input type="checkbox" name="users[]" value="<?= $user->id ?>" />
</td>

<td height="30" width="16" class="item<?= $i%2==0?"Alt":"" ?>">
<a href="<?= scriptUrl."/".folderUsers."/".fileUserEdit ?>?userId=<?= $user->id ?>"><img src="<?= iconUrl ?>/edit.gif" height="16" width="16" border="0" alt="<?= $lUserIndex["EditUser"] ?>" title="<?= $lUserIndex["EditUser"] ?>" /></a>
</td>

<td width="40%" class="item<?= $i%2==0?"Alt":"" ?>">
<?= printPopup(scriptUrl."/".fileUserProfile."?profileId=".$user->id."&amp;popup=1",$user->username) ?>
</td>

<td width="40%" class="small1 item<?= $i%2==0?"Alt":"" ?>">
<?= !empty($user->name)?$user->name:"&nbsp;" ?>
</td>

<td width="20%" class="small1 item<?= $i%2==0?"Alt":"" ?>">
<?= printTimestamp($user->registered, true) ?>
</td>
</tr>
<?
	}
?>
</table>

<table width="100%">
<tr>
<td>
<input type="submit" name="deleteUsers" value="<?= $lButtons["Delete"] ?>" class="button" onclick="var agree=confirm('<?= $lUserIndex["ConfirmDelete"] ?>');if(agree) {return true;}else {return false;}" />
</td>

<td align="right">
<input type="button" name="selectNoneButton" value="<?= $lButtons["SelectNone"] ?>" class="button" onclick="selectAll(document.usersForm, this, false)" /> <input type="button" name="selectAllButton" value="<?= $lButtons["SelectAll"] ?>" class="button" onclick="selectAll(document.usersForm, this, true)" />
</td>
</tr>
</table>
</form>
<br />
<?
	// Get number of users
	$count = 0;
	$result = $dbi->query("SELECT COUNT(*) FROM ".userTableName." as u1,".userDataTableName." as u2 WHERE u1.id=u2.id".(!empty($search_string)?" AND (u2.name LIKE ".$dbi->quote("%".$search_string."%")." OR u1.username LIKE ".$dbi->quote($search_string."%").")":"").($webmaster?" AND u1.webmaster=1":"").($administrator?" AND u1.administrator=1":"").($guest?" AND u1.administrator=0 AND u1.webmaster=0":""));
	if ($result->rows()) {
		list($count) = $result->fetchrow_array();
	}

	// Generate page index
	$user = new User();
	echo "<br /><center>";
	$site->printPageLinks(folderUsers."/".fileUserIndex."?".(!empty($search_string)?"search_string=$search_string&amp;":"").(!empty($group)?"group=$group&amp;":"").(!empty($sortby)?"sortby=$sortby&amp;":""), $pageNumber, $count, $userLimit);
	echo "</center>";
	
} 
else {
	printf("<p><i>".$lUserIndex["NoUsersInGroup"]."</i></p>",$webmaster?$lUser["Webmaster"]:($administrator?$lUser["Administrator"]:(!empty($groupObject)?$groupObject->name:"")));
}

// Print common footer
$site->printFooter();
?>