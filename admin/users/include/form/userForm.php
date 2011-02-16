<? 
if (!isset($profile)) $profile = true; 
if (!isset($register)) $register = false;
$actionUrl = $register ? scriptUrl."/".fileRegister."?save=1".(showPopup?"&amp;popup=1":"") : scriptUrl."/".folderUsers."/".($profile?fileUserProfileEdit:fileUserEdit)."?".(!empty($user->id)?"userId=".$user->id."&amp;":"").($profile?"profile=1&amp;":"")."save=1".(showPopup?"&amp;popup=1":"");

function printEditUserSection($index) {
	global $site;
	for ($i=0; $i<sizeof($site->editUserSections); $i++) {
		$section = $site->editUserSections[$i];
		if (!empty($section["index"])) {
			if ($section["index"] == $index) {
				printSubsectionHeader($section["title"],"",1,1,$section["title"]);
				echo $section["description"].'<br /><br />';
				echo '<div id="'.$section["title"].'" class="formIndent">';
				echo $section["fields"];
				echo '</div>';
			}
		}
	}
}
?>
<script src="<?= scriptUrl ?>/javascript/select.js" language="JavaScript"></script>
<script language="JavaScript" type="text/javascript">
<!--
function validate(form){	
	<? if ($user->hasDeletePermission() && !$profile) { ?>
	if(form.deleteUser.checked) {		
		var agree=confirm('<?= $lEditUser["ConfirmDelete"] ?>');		
		if(agree) return true ;		
		else return false ;	
	}
	<? } ?>
}
function validateField(field, normalClass, errorClass, warningIcon) {
	if ((field.name!='repeated_password' && field.value!='') || (field.name=='repeated_password' && document.user.password.value==field.value)) { 
		field.className = normalClass; 
		warningIcon.style.display='none'; 
	} 
	else { 
		field.className = normalClass+' '+errorClass; 
		warningIcon.style.display=''; 
	}
}
-->
</script>

<form name="user" action="<?= $actionUrl ?>" enctype="multipart/form-data" method="post" onsubmit="return validate(this)">
<table width="100%">
<tr>
<td width="50%" valign="top">
<? if (!$profile || $register) { ?>
<?= $lEditUser["Username"] ?><br />
<input type="text" name="u_username" value="<?= $user->username ?>" onkeyup="validateField(this, 'longInput', 'error', warning_username)" class="longInput<?= empty($user->username) || $errors->hasError("username")?" error":"" ?>" /> <? $errors->printWarningIcon("username",(!$errors->hasError("username")?$lEditUser["MissingUsername"]:""),(empty($user->id) && empty($user->username)?1:0)) ?>
<? } ?>

<? if (!$profile || $register) { ?><p><? } ?>
<?= $lEditUser["FullName"] ?><br />
<input type="text" name="u_name" value="<?= $user->name ?>" onkeyup="validateField(this, 'longInput', 'error', warning_name)" class="longInput<?= empty($user->name) || $errors->hasError("name")?" error":"" ?>" /> <? $errors->printWarningIcon("name",(!$errors->hasError("name")?$lEditUser["MissingFullName"]:""),(empty($user->id) && empty($user->name)?1:0)) ?>
<? if (!$profile || $register) { ?></p><? } ?>

<p><?= $lEditUser["Email"] ?><br />
<input type="text" name="u_email" value="<?= $user->email ?>" onkeyup="validateField(this, 'longInput', 'error', warning_email)" class="longInput<?= (empty($user->email) && !$login->isWebmaster()) || $errors->hasError("email")?" error":"" ?>" /> <? $errors->printWarningIcon("email",(!$errors->hasError("email")?$lEditUser["MissingEmail"]:""),((empty($user->id) && !$login->isWebmaster()) && empty($user->email)?1:0)) ?></p>

<?= $lEditUser["Telephone"] ?><br />
<input type="text" name="u_phone" value="<?= $user->phone ?>" class="longInput" />

<p><?= $lEditUser["Mobile"] ?><br />
<input type="text" name="u_mobile" value="<?= $user->mobile ?>" class="longInput" /></p>
</td>

<td width="50%" valign="top">
<?= $lEditUser["Location"] ?><br />
<input type="text" name="u_location" value="<?= $user->location ?>" class="longInput" />

<p><?= $lEditUser["Occupation"] ?><br />
<input type="text" name="u_position" value="<?= $user->position ?>" class="longInput" /></p>

<p><?= $lEditUser["Department"] ?><br />
<input type="text" name="u_department" value="<?= $user->department ?>" class="longInput" /></p>
</td>
</tr>
</table>

<input type="checkbox" name="u_notifyAboutChanges" value="1"<?= $user->notifyAboutChanges?" checked=\"checked\"":"" ?> /> <?= !$profile && !$register ? $lEditUser["NotifyAboutChanges"] : $lEditProfile["NotifyAboutChanges"] ?><br /><br />


<? printSubsectionHeader($lEditUser["Links"],"",1,1,"links") ?>

<p><?= !$profile && !$register ?$lEditUser["LinksText"]:$lEditProfile["LinksText"] ?></p>

<div id="links" class="formIndent">
	<table width="100%">
	<tr>
	<td width="50%" valign="top">
	<?= $lEditUser["Link"] ?><br />
	<input type="text" name="u_linkurl" value="<?= $user->linkurl ?>" class="longInput" />

	<p><?= $lEditUser["Linkname"] ?><br />
	<input type="text" name="u_linkname" value="<?= $user->linkname ?>" class="longInput" /></p>
	</td>
	<td width="50%" valign="top">
	Facebook<br />
	<input type="text" name="u_facebook" value="<?= $user->facebook ?>" class="longInput" />
	
	<p>Twitter<br />
	<input type="text" name="u_twitter" value="<?= $user->twitter ?>" class="longInput" /></p>	
	</td>
	</tr>
	</table>
</div>


<? printEditUserSection(1); ?>

<? printSubsectionHeader($lEditUser["Profile"],"",1,1,"profile") ?>

<p><?= !$profile && !$register ?$lEditUser["ProfileText"]:$lEditProfile["ProfileText"] ?></p>

<div id="profile" class="formIndent">
<table width="95%" cellspacing="0" cellpadding="2" border="0" summary="Pictures">
<tr>
<td width="35">
<? 
$imageUrl = $user->getUserImage(50);
$dimensions = getImageDimensions($imageUrl);
?>
<img name="img_0_preview" src="<?= $imageUrl ?>" width="<?= $dimensions[0] ?>" height="<?= $dimensions[1] ?>" border="0" alt="" title="" class="border" />
</td>

<td width="100%">
<?= $lEditUser["UploadPortraitText"] ?><br />
<input type="file" name="img_0" class="normalInput" onchange="imagePreview(document.img_0_preview,this.value)"  /> <? $errors->printWarningIcon("image","",0) ?> <? $errors->printWarningIcon("upload",(!$errors->hasError("upload")?$lErrors["ReUploadImages"]:""),$errors->hasError("upload")?1:0) ?>
</td>
</tr>
</table>

<p><?= $lEditUser["ProfileBody"] ?><br />
<? printRichTextArea("document.user","u_profileText",$user->profileText,15,40,0,3,2) ?></p>

<p><?= $lEditUser["Signature"] ?><br />
<? printRichTextArea("document.user","u_signature",$user->signature,5,40,0,4,2) ?></p>
</div>


<? if($user->hasAdministerPermission() && !$profile) { ?>
<? printSubsectionHeader($lEditUser["Groups"],"",1,0,"groups") ?>

<p><?= $lEditUser["GroupsText"] ?></p>

<div id="groups" style="display:none" class="formIndent">
<?
$columnCount = 0;
$result = $dbi->query("SELECT id,name FROM `".groupTableName."` ORDER BY name");
if ($result->rows()) {
?>
<table width="100%" cellspacing="0" cellpadding="0">
<?
	$colomnCount = 0;
	for($i=0;(list($groupId,$groupTitle)=$result->fetchrow_array());$i++) {
		if ($i%3==0) echo ($i!=0?"</tr>":"")."<tr>";
?>
<td valign="top">
<input name="u_groups[]" type="checkbox" value="<?= $groupId ?>"<?= $group->isMember($groupId, $user->id)?" checked=\"checked\"":"" ?> />
</td>
<td width="33%" valign="top">
<?= $groupTitle ?>
</td>
<? 
		$columnCount++;
	} 
	while ($columnCount%3!=0) {
		echo "<td>&nbsp;</td><td width=\"33%\">&nbsp;</td>";
		$columnCount++;
	}
?>
</tr>
</table>
<br />
<? } else { ?>
<i><?= $lEditUser["NoGroups"] ?></i><br /><br />
<? } ?>
</div>
<? } ?>

<? if (empty($user->id) && !$profile || $register) { ?>
<? printSubsectionHeader($lEditUser["Password"],"",1,empty($user->id) || $errors->hasError("password") && $errors->hasError("repeatedPassword"),"password") ?>

<p><?= !$profile && !$register ?$lEditUser["PasswordText"]:$lEditProfile["PasswordText"] ?></p>

<div id="password"<?= !empty($user->id) && !$errors->hasError("password") && !$errors->hasError("repeatedPassword")?" style=\"display:none\"":"" ?> class="formIndent">
<span id="passwordSpan">
<?= $lEditUser["Password"] ?><br />
<input type="password" name="u_passwd" value="" onkeyup="validateField(this, 'shortInput', 'error', warning_password)" class="shortInput<?= empty($user->id) || $errors->hasError("password") || $errors->hasError("repeatedPassword")?" error":"" ?>" /> <? $errors->printWarningIcon("password",(!$errors->hasError("password")?$lEditUser["MissingPassword"]:""),(empty($user->id)?1:0)) ?><br /><br />

<?= $lEditUser["RepeatPassword"] ?><br />
<input type="password" name="u_repeated_passwd" onkeyup="validateField(this, 'shortInput', 'error', warning_repeatedPassword)" value="" class="shortInput<?= empty($user->id) || $errors->hasError("repeatedPassword")?" error":"" ?>" /> <? $errors->printWarningIcon("repeatedPassword",(!$errors->hasError("repeatedPassword")?$lEditUser["DifferentPasswords"]:""),(empty($user->id)?1:0)) ?>
</span>
<br /><br />
</div>
<? } ?>

<? if ($user->hasAdministerPermission() && !$profile) { ?>
<? printSubsectionHeader($lEditUser["Permissions"],"",1,0,"permissions") ?>

<p><?= $lEditUser["PermissionsText"] ?></p>

<div id="permissions" style="display:none" class="formIndent">
<input type="radio" name="userType" value="1"<?= $user->webmaster?" checked=\"checked\"":"" ?> onclick="document.getElementById('modulesList').style.display='none';" /> <?= $lUser["Webmaster"] ?><br />
<input type="radio" name="userType" value="2"<?= $user->administrator && !$user->webmaster?" checked=\"checked\"":"" ?> onclick="document.getElementById('modulesList').style.display='none';" /> <?= $lUser["Administrator"] ?><br />
<?
$moduleAdmin = false;
$modules = "";
$result = $dbi->query("SELECT id,title FROM ".moduleTableName." WHERE Visible=1 ORDER BY Title");
if ($result->rows()) {
	$modules = '<table width="100%" cellspacing="0" cellpading="0" class="index"><tr><td width="60%" class="indexHeader">'.
				$lEditPermissions["Name"].
				'</td>
				<td width="40%" class="indexHeader">'.
				$lEditPermissions["Permissions"].
				'</td></tr>';
	
	for ($i=0;list($moduleId,$moduleTitle)=$result->fetchrow_array();$i++) {	
		$permissionLevel = 0;
		if (!empty($user->id)) {
			$permissionLevel = $login->getModulePermissionLevel($moduleId, "User", $user->id);
		}
		if ($permissionLevel>0) $moduleAdmin = true;
		$modules .=  '<tr>
					  <td class="itemAlt">'.
					  $moduleTitle.
					  '</td>'.
					  '<td class="itemAlt" align="right">
					  <select name="permissions['.$moduleId.']" style="width:100%">
					  <option value="0"'.($permissionLevel==0?' selected="selected"':'').'>'.$lEditPermissions["Level0"].'</option>
					  <option value="0">-</option>
					  <option value="1"'.($permissionLevel==1?' selected="selected"':'').'>'.$lEditPermissions["Level1"].'</option>
					  <option value="2"'.($permissionLevel==2?' selected="selected"':'').'>'.$lEditPermissions["Level2"].'</option>
					  <option value="3"'.($permissionLevel==3?' selected="selected"':'').'>'.$lEditPermissions["Level3"].'</option>
					  <option value="4"'.($permissionLevel==4?' selected="selected"':'').'>'.$lEditPermissions["Level4"].'</option>
					  <option value="5"'.($permissionLevel==5?' selected="selected"':'').'>'.$lEditPermissions["Level5"].'</option>
					  <option value="6"'.($permissionLevel==6?' selected="selected"':'').'>'.$lEditPermissions["Level6"].'</option>
					  <option value="0">-</option>
					  <option value="7"'.($permissionLevel==7?' selected="selected"':'').'>'.$lEditPermissions["Level7"].'</option>
					  </select>
					  </td></tr>';
	}
	$modules .= '</table>';
}
?>
<input type="radio" name="userType" value="3"<?= $moduleAdmin && !$user->webmaster && !$user->administrator?" checked=\"checked\"":"" ?>  onclick="document.getElementById('modulesList').style.display='';" /> <?= $lUser["ModuleAdministrator"] ?><br />
<span id="modulesList"<?= !$moduleAdmin || ($user->webmaster || $user->administrator)?" style=\"display:none\"":"" ?>>
<table id="modules">
<tr>
<td>
&nbsp;&nbsp;&nbsp;&nbsp;
</td>
<td width="100%">
<?= $modules ?>
</td>
</tr>
</table>
</span>
<input type="radio" name="userType" value="4"<?= !$user->administrator && !$user->webmaster && !$moduleAdmin?" checked=\"checked\"":"" ?> onclick="document.getElementById('modulesList').style.display='none';" /> <?= $lUser["Guest"] ?><br />
<br />
</div>
<? } ?>


<? printSubsectionHeader($lEditUser["Options"],"",1,0,"userOptions") ?>

<p><?= $profile?$lEditProfile["OptionsText"]:$lEditUser["OptionsText"] ?></p>

<div id="userOptions" style="display:none" class="formIndent">
<? if ($user->hasEditPermission() && !$profile) { ?>
<?= $lEditUser["UserCategory"] ?><br />
<select name="categoryId" class="shortInput editable keepSorted">
<option value=""><?= $lEditUser["UserCategoryPick"] ?></option>
<option value="!!!edit!!!">(<?= $lEditUser["UserCategoryCreate"] ?>)</option>
<?
$result = $dbi->query("SELECT id,title FROM ".userCategoryTableName." ORDER BY title");
for($i=0;(list($categoryId,$categoryTitle)=$result->fetchrow_array());$i++) {
?>
<option value="<?= $categoryId ?>"<?= $user->categoryId==$categoryId?" selected=\"selected\"":"" ?>><?= $categoryTitle ?></option>
<? } ?>
</select>
<? } ?>

<? if ($user->hasEditPermission() && !$profile) { ?><p><? } ?>
<? if ($login->isWebmaster() && !$profile) { ?><input type="checkbox" name="u_activated" value="1"<?= !$user->activated?" checked=\"checked\"":"" ?> /> <?= $lEditUser["BlockUser"] ?><br /><? } ?>
<input type="checkbox" name="u_hideEmail" value="1"<?= $user->hideEmail?" checked=\"checked\"":"" ?> /> <?= $lEditUser["HideEmail"] ?><br />
<input type="checkbox" name="u_hideTelephone" value="1"<?= $user->hideTelephone?" checked=\"checked\"":"" ?> /> <?= $lEditUser["HideTelephoneNumber"] ?><br />
<input type="checkbox" name="u_hideInUserlist" value="1"<?= $user->hideInUserlist?" checked=\"checked\"":"" ?> /> <?= $lEditUser["HideInUserlist"] ?><br />
<input type="checkbox" name="u_hideOnlineStatus" value="1"<?= $user->hideOnlineStatus?" checked=\"checked\"":"" ?> /> <?= $lEditUser["HideOnlineStatus"] ?>
<? if ($user->hasEditPermission() && !$profile) { ?></p><? } ?>
</div>


<? if (!empty($user->id) && $user->hasDeletePermission() && !$profile) { ?>
<? printSubsectionHeader($lEditUser["Delete"],"",1,0,"deleteUser") ?>

<p><?= $lEditUser["DeleteText"] ?></p>

<div id="deleteUser" class="formIndent" style="display:none">
<input type="checkbox" name="deleteUser" value="1" onchange="if(this.checked==1) { saveUser.value='<?= $lEditUser["Delete"] ?>' } else { saveUser.value='<?= $lEditUser["Save"] ?>' }"<?= empty($user->id)?" disabled=\"disabled\"":"" ?> /> <?= $lEditUser["Delete"] ?><br /><br />
</div>
<? } ?>

<? if (!$login->isLoggedIn() && $settings->requireValidation) { ?>
<? printSubsectionHeader($lEditUser["Validation"],"",1,1,"validation") ?>

<p><?= $lComment["ValidationText"] ?></p>

<div id="validation" class="formIndent">
<img width="120" height="30" src="<?= scriptUrl ?>/include/form/auditButton.php" border="1" />

<p><input maxlength="5" size="5" name="userdigit" type="text" value="" onkeyup="validateField(this, 'shortInput', 'error', warning_validation)" class="shortInput<?= empty($user->id) || $errors->hasError("validation")?" error":"" ?>" /> <? $errors->printWarningIcon("validation",(!$errors->hasError("validation")?$lEditUser["MissingValidation"]:""),1) ?></p>
</div>
<? } ?>

<p><input name="saveUser" type="submit" value="<?= $profile?$lEditProfile["Save"]:$lEditUser["Save"] ?>" class="button" /></p>
</form>