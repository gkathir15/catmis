<?
// Include common functions and declarations
require_once "../../include/common.php";

// Get content type and content identifier
$moduleContentTypeId = getGetValue("moduleContentTypeId");
$moduleContentId = getGetValue("moduleContentId");

if (!empty($moduleContentTypeId) && !empty($moduleContentId)) {
	if(!$login->hasAdministerPermission($moduleContentTypeId, $moduleContentId)) {
		$login->printLoginForm();
		exit();
	}
	
	if (!empty($_GET["save"])) {
		// Reset permissions
		$dbi->query("DELETE FROM ".permissionTableName." WHERE moduleContentId=".$dbi->quote($moduleContentId)." AND moduleContentTypeId=".$dbi->quote($moduleContentTypeId));		

		// Get permission from post
		$permissions = getPostValue("permissions");

		/**
		 * Set permissions for a given type and identifier.
		 * @param	$type	Type to set permissions for.
		 * @param	$typeId	Identifier of type to set permissions for.
		 */
		function setPermissions($type, $typeId=0) {
			global $moduleContentTypeId,$moduleContentId;
			global $login, $module;
			global $permissions;
			
			// Get module identifier for this content type
			$moduleId = $module->getModuleIdFromContentType($moduleContentTypeId);					
			
			// Get permission level for the current type
			$modulePermissionLevel = $login->getModulePermissionLevel($moduleId, $type, $typeId);
			$moduleContentTypePermissionLevel = $login->getModuleContentTypePermissionLevel($moduleContentTypeId, $moduleContentId, $type, $typeId);
	
			// Determine the greatest permission level available
			$permissionLevel = $moduleContentTypePermissionLevel;
			if ($modulePermissionLevel>$moduleContentTypePermissionLevel) $permissionLevel = $modulePermissionLevel;
			
			// If permissions given are greater than general permission set permissions
			if ($permissionLevel<$permissions[$type.(!empty($typeId)?"_".$typeId:"")]) {								
				// Initialize values
				$administrator = 0;
				$comment = 0;
				$create = 0;
				$delete = 0;
				$edit = 0;
				$publish = 0;
				$read = 0;
				
				// Get permission type
				switch($permissions[$type.(!empty($typeId)?"_".$typeId:"")]) {
					case 1:
						$read = 1;
						break;	
					case 2:
						$read = 1;
						$comment = 1;
						break;	
					case 3:
						$read = 1;
						$comment = 1;
						$create = 1;
						$edit = 1;
						break;	
					case 4:
						$read = 1;
						$comment = 1;
						$create = 1;
						$edit = 1;
						$publish = 1;
						break;	
					case 5:
						$read = 1;
						$comment = 1;
						$create = 1;
						$edit = 1;
						$publish = 1;	
						$delete = 1;					
						break;	
					case 6:
						$read = 1;
						$comment = 1;
						$create = 1;
						$edit = 2;
						$publish = 1;	
						$delete = 2;
						break;	
					case 7:
						$administrator = 1;
						break;	
				}
				
				// Check if any permissions have been set
				if ($administrator || $comment!=0 || $create!=0 || $delete!=0 || $edit!=0 || $publish!=0 || $read!=0) {
					// Set permissions for module content
					$login->setModuleContentPermissions($moduleContentTypeId, 
													 	 $moduleContentId, 
														 $type, 
														 $typeId, 
														 $administrator,
														 $comment, 
														 $create, 
														 $delete, 
														 $edit, 
														 $publish, 
														 $read
														 );											
				}
			}
		}

		// Set permissions for visitors to the page
		setPermissions("Visitors");

		// Set permissions for users visiting the page
		setPermissions("Users");

		// Set permissions for groups on the page
		$result = $dbi->query("SELECT id FROM `".groupTableName."` ORDER BY name");
		if ($result->rows()) {
			for($i=0;(list($id)=$result->fetchrow_array());$i++) {
				setPermissions("Group", $id);
			}
		}

		// Set permissions for users on the page			
		$result = $dbi->query("SELECT id FROM ".userTableName." ORDER BY username");
		if ($result->rows()) {
			for($i=0;(list($id)=$result->fetchrow_array());$i++) {
				setPermissions("User", $id);
			}
		}

		// Get module content type object
		$moduleContentTypeObject = $module->getModuleContentTypeObject($moduleContentTypeId);
		if ($moduleContentTypeObject!=null) {
			if (method_exists($moduleContentTypeObject, "getLink")) {
				redirect($moduleContentTypeObject->getLink($moduleContentId));
			}
			else {
				redirect(scriptUrl);
			}				
		}
		
		// Redirect to scriptUrl
		redirect(scriptUrl);
	}
	
	// Add navigation links
	$site->addNavigationLink(scriptUrl."/".folderAdmin, $lAdminIndex["Header"]);
	$site->addNavigationLink(scriptUrl."/".folderUsers, $lAdminIndex["Users"]);
	$site->addNavigationLink(scriptUrl."/".folderUsers."/".fileEditPermissions."?moduleContentTypeId=".$moduleContentTypeId."&amp;moduleContentId=".$moduleContentId, $lEditPermissions["Header"]);
	
	// Print common header
	$site->printHeader();
	
	// Get search string
	$searchString = getPostValue("searchString");
	$searchType = getPostValue("searchType");
	
	// Get info on module type
	$moduleId = 0;
	$title = "";
	$result = $dbi->query("SELECT moduleId, title FROM ".moduleContentTypeTableName." WHERE id=".$dbi->quote($moduleContentTypeId));
	if ($result->rows()) {
		list($moduleId, $title) = $result->fetchrow_array();
	}

	// Get module content type object
	$moduleContentTypeObject = $module->getModuleContentTypeObject($moduleContentTypeId);

	// Print page description
	printf("<p>".$lEditPermissions["HeaderText"]."</p>", method_exists($moduleContentTypeObject,"getName")?$moduleContentTypeObject->getName($moduleContentId):"-");

	// Make list of search types
	$searchTypes = array();
	$searchTypes[0][0] = $lEditPermissions["AllTypes"];
	$searchTypes[0][1] = 0; 
	$searchTypes[1][0] = $lEditPermissions["General"];
	$searchTypes[1][1] = 1; 
	$searchTypes[2][0] = $lEditPermissions["Groups"];
	$searchTypes[2][1] = 2; 
	$searchTypes[3][0] = $lEditPermissions["Users"];
	$searchTypes[3][1] = 3;
	
	// Sort search types
	sort($searchTypes);
?>
<center>
<form action="editPermissions.php?moduleContentTypeId=<?= $moduleContentTypeId ?>&amp;moduleContentId=<?= $moduleContentId ?>" method="post">
<input type="text" name="searchString" value="<?= !empty($_POST["searchString"])?$_POST["searchString"]:"" ?>" class="shortInput" style="width:150px" /> <select name="searchType" class="shortInput" style="width:100px">
<? for ($i=0; $i<sizeof($searchTypes); $i++) { ?>
<option value="<?= $searchTypes[$i][1] ?>"<?= $searchType==$searchTypes[$i][1]?" selected=\"selected\"":"" ?>><?= $searchTypes[$i][0] ?></option>
<? } ?>
</select> <input type="submit" value="<?= $lGeneral["Search"] ?>" class="button" />
</form>
</center>

<form action="editPermissions.php?save=1&amp;moduleContentTypeId=<?= $moduleContentTypeId ?>&amp;moduleContentId=<?= $moduleContentId ?>" method="post">

<?
		function printPermissionHeader($title) {
			global $lEditPermissions;
?>
<h2><?= $title ?></h2>

<table width="100%" cellspacing="0" cellpadding="2" border="0" summary="" class="index">
<tr>
<td width="60%" class="indexHeader"><b><?= $lEditPermissions["Name"] ?></b></td>
<td width="40%" class="indexHeader"><b><?= $lEditPermissions["Permissions"] ?></b></td>
</tr>
<?	
	}

	function printPermissionRow($title, $type, $typeId="") {
		global $dbi, $login, $module;
		global $moduleContentTypeId,$moduleContentId;
		global $lEditPermissions;
		
		// Set identifier
		$typeName = $type.(!empty($typeId)?"_".$typeId:"");
?>
<tr>
<td class="itemAlt" valign="top">
<?
		$administrator = 0;
		$comment = 0;
		$create = 0;
		$delete = 0;
		$edit = 0;
		$grant = 0;
		$publish = 0;
		$read = 0;

		// Get module identifier for this content type
		$moduleId = $module->getModuleIdFromContentType($moduleContentTypeId);					
		
		// Get permission level for the current type
		$modulePermissionLevel = $login->getModulePermissionLevel($moduleId, $type, $typeId);
		$moduleContentTypePermissionLevel = $login->getModuleContentTypePermissionLevel($moduleContentTypeId, $moduleContentId, $type, $typeId);

		// Determine the greatest permission level available
		$permissionLevel = $moduleContentTypePermissionLevel;
		if ($modulePermissionLevel>$moduleContentTypePermissionLevel) $permissionLevel = $modulePermissionLevel;
?>
<?= ($permissionLevel==0?'<span style="color:#666666">':'').$title.($permissionLevel==0?'</span>':'') ?>
</td>
<td class="itemAlt" align="right">
<select name="permissions[<?= $typeName ?>]" style="width:100%">
<option value="0"<?= $permissionLevel==0?' selected="selected"':'' ?><?= $modulePermissionLevel>0?' disabled="disabled"':'' ?>><?= $lEditPermissions["Level0"] ?></option>
<option value="0">-</option>
<option value="1"<?= $permissionLevel==1?' selected="selected"':'' ?><?= $modulePermissionLevel>1?' disabled="disabled"':'' ?>><?= $lEditPermissions["Level1"] ?></option>
<option value="2"<?= $permissionLevel==2?' selected="selected"':'' ?><?= $modulePermissionLevel>2?' disabled="disabled"':'' ?>><?= $lEditPermissions["Level2"] ?></option>
<option value="3"<?= $permissionLevel==3?' selected="selected"':'' ?><?= $modulePermissionLevel>3?' disabled="disabled"':'' ?>><?= $lEditPermissions["Level3"] ?></option>
<option value="4"<?= $permissionLevel==4?' selected="selected"':'' ?><?= $modulePermissionLevel>4?' disabled="disabled"':'' ?>><?= $lEditPermissions["Level4"] ?></option>
<option value="5"<?= $permissionLevel==5?' selected="selected"':'' ?><?= $modulePermissionLevel>5?' disabled="disabled"':'' ?>><?= $lEditPermissions["Level5"] ?></option>
<option value="6"<?= $permissionLevel==6?' selected="selected"':'' ?><?= $modulePermissionLevel>6?' disabled="disabled"':'' ?>><?= $lEditPermissions["Level6"] ?></option>
<option value="0">-</option>
<option value="7"<?= $permissionLevel==7?' selected="selected"':'' ?>><?= $lEditPermissions["Level7"] ?></option>
</select>
</td>
</tr>
<?
	}
	
	function printPermissionFooter() {
		global $lEditPermissions;
		echo "</table><br />";
		echo "<div align=\"right\"><input type=\"submit\" value=\"".$lEditPermissions["SavePermissions"]."\" /></div>";
	}

	if (empty($searchString) && (empty($searchType) || $searchType==1)) {
		printPermissionHeader($lEditPermissions["General"]);
		printPermissionRow($lEditPermissions["Visitors"], "Visitors");
		printPermissionRow($lEditPermissions["RegisteredUsers"], "Users");
		printPermissionFooter();
	}

	if (empty($searchType) || $searchType==2) {
		// Print permissions for groups
		$result2 = $dbi->query("SELECT id FROM `".groupTableName."`".(!empty($searchString)?" WHERE name LIKE ".$dbi->quote("%".$searchString."%"):"")." ORDER BY name");
		if ($result2->rows()) {	
			printPermissionHeader($lEditPermissions["Groups"]);
			for($i=0;(list($id)=$result2->fetchrow_array());$i++) { 
				$group = new Group($id);
				printPermissionRow($group->name, "Group", $group->id);
			}
			printPermissionFooter();
		}
	}
		
	if (empty($searchType) || $searchType==3) {
		// Print permissions for users
		$result2 = $dbi->query("SELECT id FROM ".userTableName.(!empty($searchString)?" WHERE username LIKE ".$dbi->quote("%".$searchString."%"):"")." ORDER BY username");
		if ($result2->rows()) {
			printPermissionHeader($lEditPermissions["Users"]);
			for($i=0;(list($id)=$result2->fetchrow_array());$i++) { 
				$user = new User($id);
				printPermissionRow($user->username, "User", $user->id);
			}
			printPermissionFooter();
		}
	}
	
	// Print common footer
	$site->printFooter();
}
?>