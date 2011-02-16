<?
if (!empty($errors)) {
	if ($errors->hasErrors()) {
		$errors->printErrorMessages();
	}
}
else if (sizeof($_POST)!=0) {
	echo "<p><i>".$lLogin["SessionTimedOut"]."</i></p>";
}
if (file_exists(templatePath.'/loginFormHeader.template.php')) {
	include templatePath.'/loginFormHeader.template.php';
}
?>
<form method="post" action="<?= scriptUrl ?>/<?= fileLogin ?>">
<? 
$referer = "";
if($gotoReferer) {
	// Get referer
	$referer = !empty($_SERVER["HTTP_REFERER"])?$_SERVER["HTTP_REFERER"]:(!empty($_POST["referer"])?$_POST["referer"]:scriptUrl);
	if ($referer==scriptUrl."/".fileLogin) {
		$referer = scriptUrl;
	}
}
else {
	$referer = getCurrentURL();

	// Get post values if any in order resend them on succesful login
	foreach ($_POST as $k => $v) {
		$_SESSION["post"][$k] = $v;
	}
}
?>
<input type="hidden" name="referer" value="<?= $referer ?>" />

<p><?= $lLogin["Username"] ?><br />
<input type="text" name="username" maxlength="100" class="shortInput" /></p>

<p><?= $lLogin["Password"] ?><br />
<input type="password" name="password" maxlength="100" class="shortInput" /></p>

<p><input type="checkbox" name="remember" value="1" /> <?= $lLogin["RememberMe"] ?></p>

<p><input type="submit" name="submit" value="<?= $lLogin["Send"] ?>" class="button" /></p>

<p><a href="<?= scriptUrl ?>/<?= fileProfileForgotPassword ?>"><?= $lLogin["ForgotPassword"] ?></a></p>

<?
if (file_exists(templatePath.'/loginFormFooter.template.php')) {
	include templatePath.'/loginFormFooter.template.php';
}
?>