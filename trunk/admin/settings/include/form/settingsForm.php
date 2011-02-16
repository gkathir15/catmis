<script language="JavaScript" type="text/javascript"> 
<!-- 
function validate(form){
	if(form.title.value=="") { 
		alert('<?= $lSettings["MissingTitle"] ?>');
 		return false;
 	}
	else if(form.adminMail.value=="") { 
		alert('<?= $lSettings["MissingAdminMail"] ?>');
 		return false;
 	}
 	else {
 		return true;
 	}
}
--> 
</script>

<form name="settingsForm" action="<?= scriptUrl."/".folderSettings."/".fileSettingsEdit ?>?save=1" method="post" onsubmit="return validate(this)">
<table width="100%">
<tr>
<td width="50%" valign="top">
<?= $lSettings["Title"] ?><br />
<input type="text" name="title" value="<?= $settings->title ?>" onkeyup="validateField(this, 'longInput', 'error', warning_title)" class="longInput<?= $errors->hasError("title")?" error":"" ?>" /> <? $errors->printWarningIcon("title",(!$errors->hasError("title")?$lSettings["MissingTitle"]:""),(empty($settings->title)?1:0)) ?>

<p><?= $lSettings["AdminEmail"] ?><br />
<input type="text" name="adminMail" value="<?= $settings->adminMail ?>" onkeyup="validateField(this, 'longInput', 'error', warning_adminMail)" class="longInput<?= $errors->hasError("adminMail")?" error":"" ?>" /> <? $errors->printWarningIcon("adminMail",(!$errors->hasError("adminMail")?$lSettings["MissingAdminMail"]:""),(empty($settings->adminMail)?1:0)) ?></p>

<p><?= $lSettings["Keywords"] ?><br />
<input type="text" name="keywords" value="<?= $settings->keywords ?>" class="longInput" /></p>

<p><?= $lSettings["Description"] ?><br />
<input type="text" name="description" value="<?= $settings->description ?>" class="longInput" /></p>
</td>
<td width="50%" valign="top">
<?= $lSettings["PageLanguage"] ?><br />
<select name="language" class="longInput">
<?
// Look for languages
if ($handle = opendir(scriptPath."/include/language")) {
	for ($i=0;false!==($file = readdir($handle));$i++) {
		if (file_exists(scriptPath."/include/language/$file/about.php")) {
			include scriptPath."/include/language/$file/about.php";
			echo "<option value=\"$file\"".(pageLanguage==$file?" selected=\"selected\"":"").">$language</option>";
		}
	}
}?>
</select>

<p><?= $lSettings["DefaultPage"] ?><br />
<select name="defaultPage" class="longInput">
<?
$result = $dbi->query("SELECT id,title FROM ".pageTableName." WHERE showInMenu=1 AND parentId=0 ORDER BY position");
for($i=0;(list($pageId,$pageTitle)=$result->fetchrow_array());$i++) {
	echo "<option value=\"$pageId\"".($pageId==$settings->defaultPage?"selected=\"selected\"":"")."\">".parseString($pageTitle)."</option>";
}
?>
</select></p>

<p><?= $lSettings["DefaultUploadFolder"] ?><br />
<select name="defaultUploadFolder" class="longInput">
<option value="0"<?= pageUploadFolder==0?" selected=\"selected\"":"" ?>>/</option>
<?
function printFolderOptions($id=0,$level=1) {
	global $dbi;
	$result = $dbi->query("SELECT id,name FROM ".folderTableName." WHERE parentId=$id ORDER BY name");
	if($result->rows()) {
		for($i=0;(list($id,$name) = $result->fetchrow_array());$i++) {
			echo "<option value=\"$id\"".(pageUploadFolder==$id?" selected=\"selected\"":"").">";
			for($j=0;$j<$level;$j++) {
				echo "-";	
			}
			echo " $name</option>";
			printFolderOptions($id,$level+1);
		}
	}
}
printFolderOptions();
?>
</select></p>
</td>
</tr>
</table>


<? printSubsectionHeader($lSettings["Themes"],"<img src=\"".iconUrl."/themes.gif\" width=\"16\" height=\"16\" />",1,0,"theme") ?>

<p><?= $lSettings["ThemesText"] ?></p>

<div id="theme" class="formIndent" style="display:none">
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
<td width="100%" valign="top">
<?= $lSettings["Theme"] ?><br />
<select name="theme" class="longInput">
<?
// Save reference to selected theme
$selectedTheme = new Theme();
$selectedSubtheme = "";

// Get available themes
$themes = $site->getThemes();
for ($i=0; $i<sizeof($themes); $i++) {
	$theme = $themes[$i];
	echo "<option value=\"".$theme->folder."\"";
	if ($theme->folder==$settings->theme) {
		echo " selected=\"selected\"";
		$selectedTheme = $theme;
	}
	if (sizeof($theme->subthemes)>0) {
		echo " onclick=\"updateSubthemes(new Array(";
		for ($j=0; $j<sizeof($theme->subthemes); $j++) {
			echo ($j!=0?",":"")."new Array('".$theme->subthemes[$j]."','".$theme->getSubthemePreviewURL($theme->subthemes[$j])."')";
		}
		echo "))\"";
	}
	else {
		echo " onclick=\"updateSubthemes(new Array());loadThemePreview('".$theme->getThemePreviewURL()."')\"";
	}
	echo ">".$theme->title."</option>";
}
?>
</select>

<span id="subtheme"<?= sizeof($selectedTheme->subthemes)==0?" style=\"display:none\"":"" ?>>
<p>Subtheme<br />
<select name="subtheme" class="longInput" onchange="loadPreview(this.options[this.selectedIndex].value)">
<? 
if (sizeof($selectedTheme->subthemes)>0) {
	for ($i=0; $i<sizeof($selectedTheme->subthemes); $i++) {
		if ($selectedTheme->subthemes[$i]==$settings->subtheme) $selectedSubtheme = $selectedTheme->subthemes[$i];
		echo '<option value="'.$selectedTheme->subthemes[$i].'"'.($selectedTheme->subthemes[$i]==$settings->subtheme?' selected="selected"':'');
		echo '>'.$selectedTheme->subthemes[$i].'</option>';
	}
}
?>
</select></p>
</span>

<p><?= $lSettings["IconTheme"] ?><br />
<select name="iconTheme" class="longInput">
<?
if ($handle = opendir(scriptPath."/theme/icon")) {
	for ($i=0;false!==($file = readdir($handle));$i++) {
		if (file_exists(scriptPath."/theme/icon/$file/about.php")) {
			include scriptPath."/theme/icon/$file/about.php";
			echo "<option value=\"$file\"".($file==$settings->iconTheme?" selected=\"selected\"":"").">$title</option>";
		}
	}
}
?>
</select></p>

<p><?= $lSettings["ThemeWidth"] ?><br />
<input type="text" name="themeWidth" value="<?= $settings->themeWidth ?>" class="longInput" /></p>

<p><?= $lSettings["ThemeHeaderUrl"] ?><br />
<input type="text" name="themeHeaderUrl" value="<?= $settings->themeHeaderUrl ?>" class="longInput" /></p>

<script language="javascript" type="text/javascript"> 
<!--
<?
if ($selectedTheme->getNumberOfSubthemes()>0) {
	echo 'var subthemes2 = new Array(';
	for ($i=0; $i<sizeof($selectedTheme->subthemes); $i++) {
		echo ($i!=0?',':'').'new Array(\''.$selectedTheme->subthemes[$i].'\',\''.$theme->getSubthemePreviewURL($selectedTheme->subthemes[$i]).'\')';
	}
	echo ');';
}
else {
	echo 'var subthemes2 = new Array();';	
}
?>
function loadPreview(subthemeName) {
	if (subthemeName!="") {
		for (i = 0; i < subthemes2.length; i++) {
			if (subthemes2[i][0]==subthemeName) {
				document['themePreview'].src = subthemes2[i][1];
			}
		}
	}
}

function loadThemePreview(url) {
	document['themePreview'].src = url;
}

function updateSubthemes(subthemes) {
	subthemes2 = subthemes;
	var subthemesList = document.settingsForm.subtheme;

	// Clear subthemes
	for (i = subthemesList.length; i >= 0; i--) {
		subthemesList[i] = null;
	}

	for (i = 0; i < subthemes.length; i++) {
		if (i==0) document['themePreview'].src = subthemes[i][1];
		var option = new Option(subthemes[i][0], subthemes[i][0]);
		subthemesList[subthemesList.length] = option;
	}
	
	if (subthemes.length==0) {
		document.getElementById('subtheme').style.display = 'none';
		document['themePreview'].src = '<?= iconUrl ?>/noPreview.jpg';
	}
	else {
		document.getElementById('subtheme').style.display = '';	
	}
}
-->
</script>
</td>

<td valign="top" align="right">
<img name="themePreview" src="<?= !empty($selectedSubtheme)?$selectedTheme->getSubthemePreviewURL($selectedSubtheme):$selectedTheme->getThemePreviewURL() ?>" border="1" /><br />
</td>
</tr>
</table>
</div>


<? printSubsectionHeader($lSettings["CommentModeration"],"<img src=\"".iconUrl."/spam.gif\" />",1,0,"comment") ?>

<p><?= $lSettings["CommentModerationText"] ?></p>

<div id="comment" class="formIndent" style="display:none">
<?= $lSettings["MaxLinks"] ?><br />
<input type="text" name="maxNoOfLinksInComments" value="<?= $settings->maxNoOfLinksInComments ?>" class="shortInput" />

<p><?= $lSettings["SpamWords"] ?><br />
<textarea name="commentBlacklist" rows="10"><?= $settings->commentBlacklist ?></textarea></p>

<p><input type="checkbox" name="commentsRequireValidation" value="1"<?= $settings->commentsRequireValidation?" checked=\"checked\"":"" ?> /> <?= $lSettings["CommentRequireValidation"] ?></p>
</div>


<? printSubsectionHeader($lSettings["Permalinks"],"<img src=\"".iconUrl."/links.gif\" width=\"16\" height=\"16\" />",1,0,"permalinks") ?>

<p><?= $lSettings["PermalinksText"] ?></p>

<div id="permalinks" class="formIndent" style="display:none">
<table width="100%">
<tr>
<td><input type="radio" name="linkType" value="1"<?= $settings->linkType==1?" checked=\"checked\"":"" ?> /></td><td><?= $lSettings["PermalinksByName"] ?></td></tr>
<tr><td>&nbsp;</td><td><u><?= scriptUrl."/index.php?Main" ?></u></td>
</tr>

<tr>
<td><input type="radio" name="linkType" value="2"<?= $settings->linkType==2?" checked=\"checked\"":"" ?> /></td><td><?= $lSettings["PermalinksById"] ?></td></tr>
<tr><td>&nbsp;</td><td width="100%"><u><?= scriptUrl."/index.php?sectionId=1" ?></u></td>
</tr>

<tr>
<td><input type="radio" name="linkType" value="3"<?= $settings->linkType==3?" checked=\"checked\"":"" ?> /></td><td><?= $lSettings["PermalinksByNameAndId"] ?></td></tr>
<tr><td>&nbsp;</td><td><u><?= scriptUrl."/index.php?Main/1" ?></u></td>
</tr>
</table>
</div>


<? printSubsectionHeader($lSettings["Profiles"],"<img src=\"".iconUrl."/users.gif\" width=\"16\" height=\"16\" />",1,0,"profiles") ?>

<p><?= $lSettings["ProfilesText"] ?></p>

<div id="profiles" class="formIndent" style="display:none">
<input type="checkbox" name="allowUserRegistration" value="1"<?= $settings->allowUserRegistration?" checked=\"checked\"":"" ?> /> <?= $lSettings["AllowUserRegistration"] ?><br />
<input type="checkbox" name="requireValidation" value="1"<?= $settings->requireValidation?" checked=\"checked\"":"" ?> /> <?= $lSettings["RequireValidation"] ?><br />
<input type="checkbox" name="activateWithEmail" value="1"<?= $settings->activateWithEmail?" checked=\"checked\"":"" ?> /> <?= $lSettings["ActivateWithEmail"] ?>
</div>


<? printSubsectionHeader($lSettings["Options"],"<img src=\"".iconUrl."/options.gif\" width=\"16\" height=\"16\" />",1,0,"settingsOptions") ?>

<p><?= $lSettings["OptionsText"] ?></p>

<div id="settingsOptions" class="formIndent" style="display:none">
<input type="checkbox" name="enableCaching" value="1" onclick="document.getElementById('cacheSizeSpan').style.display=this.checked==1?'':'none'"<?= $settings->enableCaching?" checked=\"checked\"":"" ?> /> <?= $lSettings["EnableCaching"] ?>
<span id="cacheSizeSpan"<?= !$settings->enableCaching?" style=\"display:none\"":"" ?>>
 <?= $lSettings["CacheSize"] ?> <select name="cacheSize">
<?
$sizes = array(0,1024,2048,5120,10240,20480);
for ($i=0; $i<sizeof($sizes); $i++) {
	echo '<option value="'.$sizes[$i].'"'.($sizes[$i]==$settings->cacheSize?' selected="selected"':'').'>'.$sizes[$i].' KB</option>';
}
?>
</select>
</span>

<p><input type="checkbox" name="enableRevisioning" value="1"<?= $settings->enableRevisioning?" checked=\"checked\"":"" ?> /> <?= $lSettings["EnableRevisioning"] ?></p>
 
<p><input type="checkbox" name="showDirectLink" value="1"<?= $settings->showDirectLink?" checked=\"checked\"":"" ?> /> <?= $lSettings["ShowDirectLink"] ?><br />
<input type="checkbox" name="showPrinterLink" value="1"<?= $settings->showPrinterLink?" checked=\"checked\"":"" ?> /> <?= $lSettings["ShowPrinterLink"] ?><br />
<input type="checkbox" name="showRecommendLink" value="1"<?= $settings->showRecommendLink?" checked=\"checked\"":"" ?> /> <?= $lSettings["ShowRecommendLink"] ?></p>
</div>

<p><input type="submit" value="<?= $lSettings["SaveSettings"] ?>" class="button" /></p>
</form>
