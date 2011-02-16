<?
// Set install language
$language = !empty($_POST["language"])?$_POST["language"]:"en";

// Include default header
include "include/header.php";

// Include language
include "include/language/".$language."/general.php"; 
?>

<table cellspacing="0" cellpadding="0" style="width:50%;border:1px #666666 solid;padding:0px">
<tr>
<td>
<table cellspacing="0" cellpadding="0" style="width:100%;background-color:#ffffff">
<tr>
<td height="400" style="padding:20px;padding-top:5px;border-right:1px #cccccc solid;" align="left" valign="top">	
<h1><?= $lInstall["Header"] ?></h1>

<p><?= $lInstall["HeaderText"] ?></p>

<script language="JavaScript" type="text/javascript">
<!--
function validate(form){
	if(form.title.value=="") {
		alert('<?= $lInstall["TitleMissing"] ?>');
		return false;
	}
	else if(form.name.value=="") {
		alert('<?= $lInstall["NameMissing"] ?>');
		return false;
	}
	else if(form.email.value=="") {
		alert('<?= $lInstall["EmailMissing"] ?>');
		return false;
	}
	else if(form.username.value=="") {
		alert('<?= $lInstall["UsernameMissing"] ?>');
		return false;
	}
	else if(form.password.value=="") {
		alert('<?= $lInstall["PasswordMissing"] ?>');
		return false;
	}
	else if(form.password2.value=="") {
		alert('<?= $lInstall["RepeatedPasswordMissing"] ?>');
		return false;
	}
	else if(form.password.value!=form.password2.value) {
		alert('<?= $lInstall["MismatchingPassword"] ?>');
		return false;
	}
	else if(form.scriptUrl.value=="") {
		alert('<?= $lInstall["ScriptUrlMissing"] ?>');
		return false;
	}
	else if(form.scriptPath.value=="") {
		alert('<?= $lInstall["ScriptPathMissing"] ?>');
		return false;
	}
	else if(form.filePath.value=="") {
		alert('<?= $lInstall["FilePathMissing"] ?>');
		return false;
	}
	else if(form.dbHost.value=="") {
		alert('<?= $lInstall["DBHostMissing"] ?>');
		return false;
	}
	else if(form.dbName.value=="") {
		alert('<?= $lInstall["DBNameMissing"] ?>');
		return false;
	}
	else if(form.dbUsername.value=="") {
		alert('<?= $lInstall["DBUsernameMissing"] ?>');
		return false;
	}
	else if(form.dbPassword.value=="") {
		alert('<?= $lInstall["DBPasswordMissing"] ?>');
		return false;
	}
	else {
		return true;
	}
}
-->
</script>

<form action="complete.php" method="post" onsubmit="return validate(this);">
<input type="hidden" name="language" value="<?= $language ?>" />

<h2><?= $lInstall["WebsiteInformation"] ?></h2>

<p><?= $lInstall["Title"] ?><br />
<input type="text" name="title" value="" class="shortInput" /></p>

<p><?= $lInstall["Subtitle"] ?><br />
<input type="text" name="description" value="" class="shortInput" /></p>


<h2><?= $lInstall["Webmaster"] ?></h2>

<p><?= $lInstall["Name"] ?><br />
<input type="text" name="name" value="" class="shortInput" /></p>

<p><?= $lInstall["Mail"] ?><br />
<input type="text" name="email" value="" class="shortInput" /></p>

<p><?= $lInstall["Username"] ?><br />
<input type="text" name="username" value="" class="shortInput" /></p>

<p><?= $lInstall["Password"] ?><br />
<input type="password" name="password" value="" class="shortInput" /></p>

<p><?= $lInstall["RepeatPassword"] ?><br />
<input type="password" name="password2" value="" class="shortInput" /></p>


<h2><?= $lInstall["Paths"] ?></h2>

<?= $lInstall["BaseScriptUrl"] ?><br />

<?
$me = $_SERVER['PHP_SELF'];
$Apathweb = explode("/", $me);
$myFileName = array_pop($Apathweb);
$pathweb = implode("/", $Apathweb);
$url = str_replace("/install/index.php","","http://".$_SERVER['HTTP_HOST'].$pathweb."/".$myFileName);
?>
<input type="text" name="scriptUrl" value="<?= $url ?>" class="shortInput" />

<p><?= $lInstall["BaseScriptPath"] ?><br />
<?
$strPathSeparator = strstr(PHP_OS, "WIN") ?"\\":"/";
$pathfile = getcwd ();
$path = str_replace($strPathSeparator."install".$strPathSeparator."index.php","",$pathfile.$strPathSeparator.$myFileName);
?>
<input type="text" name="scriptPath" value="<?= $path ?>" class="shortInput" /></p>

<p><?= $lInstall["BaseFileUploadPath"] ?><br />
<input type="text" name="filePath" value="<?= $path.$strPathSeparator."data/uploads" ?>" class="shortInput" /></p>


<h2><?= $lInstall["Database"] ?></h2>

<p><?= $lInstall["DatabasePrefix"] ?><br />
<input type="text" name="dbPrefix" value="" class="shortInput" /></p>

<p><?= $lInstall["DatabaseHost"] ?><br />
<input type="text" name="dbHost" value="localhost" class="shortInput" /></p>

<p><?= $lInstall["DatabaseName"] ?><br />
<input type="text" name="dbName" value="" class="shortInput" /></p>

<p><?= $lInstall["DatabaseUsername"] ?><br />
<input type="text" name="dbUsername" value="" class="shortInput" /></p>

<p><?= $lInstall["DatabasePassword"] ?><br />
<input type="password" name="dbPassword" value="" class="shortInput" /></p>

<p><input type="submit" value="Install CMIS" /></p>
</form>
</td>
</tr>
</table>
</td>
</tr>
</table>

<?
// Include default footer
include "include/footer.php";
?>