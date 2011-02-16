<script language="JavaScript" type="text/javascript">
<!--
function validate(form){	
	if(form.deleteGroup.checked) {
		var agree=confirm('<?= $lEditGroup["ConfirmDelete"] ?>');
		if(agree) return true;		
		else return false;	
	}
	return true;	
}
-->
</script>

<form name="group" action="<?= fileGroupEdit ?>?<?= !empty($group->id)?"groupId=".$group->id."&amp;":"" ?>save=1" enctype="multipart/form-data" method="post" onsubmit="return validate(this)">
<p><?= $lEditGroup["Name"] ?><br />
<input type="text" name="groupName" value="<?= $group->name ?>" tabindex="1" onkeyup="validateField(this, 'shortInput', 'error', warning_name)" class="shortInput<?= empty($group->name) || $errors->hasError("name")?" error":"" ?>" /> <? $errors->printWarningIcon("name",(!$errors->hasError("name")?$lEditGroup["MissingName"]:""),(empty($group->id) && empty($group->name)?1:0)) ?></p>

<p><?= $lEditGroup["Description"] ?><br />
<textarea rows="5" cols="40" name="groupDescription" tabindex="2"><?= $group->description ?></textarea></p>


<? if ($group->hasAdministerPermission()) { ?>
<? $site->printSubsectionHeader($lEditGroup["Permissions"],"",1,0,"permissions") ?>

<?= $lEditGroup["PermissionsText"] ?><br /><br />

<div id="permissions" style="display:none" class="formIndent">
<?
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
		if (!empty($group->id)) {
			$permissionLevel = $login->getModulePermissionLevel($moduleId, "Group", $group->id);
		}
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
	$modules .= '</table><br />';
	echo $modules;
}
?>
</div>
<? } ?>

<? if (!empty($group->id)) { ?>
<? if ($group->hasDeletePermission()) { ?>
<? $site->printSubsectionHeader($lEditGroup["Delete"],"",1,0,"delete") ?>

<?= $lEditGroup["DeleteText"] ?><br /><br />

<div id="delete" style="display:none" class="formIndent">
<input type="checkbox" name="deleteGroup" tabindex="3" value="1" onchange="if(this.checked==1) { saveGroup.value='<?= $lEditGroup["Delete"] ?>' } else { saveGroup.value='<?= $lEditGroup["Save"] ?>' }"<?= empty($group->id) || !$group->hasDeletePermission()?" disabled=\"disabled\"":"" ?> /> <?= $lEditGroup["Delete"] ?><br /><br />
</div>
<? } ?>
<? } ?>

<p><input name="saveGroup" tabindex="4" type="submit" value="<?= $lEditGroup["Save"] ?>" class="button" /></p>
</form>