<form name="send" action="<?= !empty($formURL)?$formURL:fileSendToFriend."?mode=send".(showPopup?"&amp;popup=1":"") ?>" method="post">
<input type="hidden" name="url" value="<?= $link ?>" />
<input type="hidden" name="info" value="<?= $info ?>" />
<input type="hidden" name="title" value="<?= $title ?>" />
<input type="hidden" name="subject" value="<?= $subject ?>" />
<input type="hidden" name="summary" value="<?= $summary ?>" />
<input type="hidden" name="windowTitle" value="<?= $windowTitle ?>" />
<input type="hidden" name="showInformation" value="<?= $showInformation ?>" />

<p><?= $lSendToFriend["YourName"] ?><br />
<input name="name" type="text" value="<?= !empty($_4POST["name"])?$_POST["name"]:$login->name ?>" class="longInput"<?= $errors->hasError("name")?" error":"" ?> /> <? $errors->printWarningIcon("name") ?></p>

<p><?= $lSendToFriend["YourMail"] ?><br />
<input name="email" type="text" value="<?= !empty($_POST["email"])?$_POST["email"]:$login->email ?>" class="longInput"<?= $errors->hasError("email")?" error":"" ?> /> <? $errors->printWarningIcon("email") ?></p>

<p><?= $lSendToFriend["FriendMail"] ?><br />
<input name="friendEmail" type="text" value="<?= !empty($_POST["friendEmail"])?$_POST["friendEmail"]:"" ?>" class="longInput<?= $errors->hasError("friendEmail")?" error":"" ?>" /> <? $errors->printWarningIcon("friendEmail") ?></p>

<p><?= $lSendToFriend["Message"] ?><br />
<textarea name="message" rows="10"><?= $message ?></textarea></p>

<? 
if (!empty($info)) { 
	$infoText = str_replace("\n","<br />",$info);
?>
<p><?= $infoText ?><br />
<a href="<?= $link ?>"><?= $link ?></a></p>
<? } ?>

<? if ($settings->commentsRequireValidation) { ?>
<p><?= $lComment["ValidationText"] ?></p>

<p><img width="120" height="30" src="<?= scriptUrl ?>/include/form/auditButton.php" border="1" /></p>

<p><input maxlength="5" size="5" name="userdigit" type="text" value="" class="shortInput<?= $errors->hasError("validation")?" error":"" ?>" /> <? $errors->printWarningIcon("validation") ?></p>
<? } ?>

<p><input type="submit" value="<?= $lSendToFriend["SendLink"] ?>" class="button" /></p>
</form>

<script language="JavaScript" type="text/javascript">document.send.friendEmail.focus()</script>