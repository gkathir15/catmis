</div>

<a name="post"><? printSubsectionHeader($lComment["WriteComment"],"",1,1,"post"); ?></a>

<p><? printf($lComment["PostText"],$replySubject) ?></p>

<div id="post" class="formIndent">
<?
if ($this->errors->hasErrors()) {
	$this->errors->printErrorMessages();
}
else {
	$subject = $lComment["Reply"].": ".$replySubject;
}
?>

<form action="<?= $formURL ?><?= !empty($_GET["page"])?"page=".$_GET["page"]:"" ?>&amp;addComment=1#comments" method="post">
<? if (!$login->isLoggedIn()) { ?>
<?= $lComment["Name"] ?><br />
<input type="text" name="name" value="<?= !empty($name)?$name:"" ?>"<?= $disableForm?" disabled=\"disabled\"":"" ?> class="shortInput<?= $this->errors->hasError("name")?" error":"" ?>" /> <? $this->errors->printWarningIcon("name") ?>

<p><?= $lComment["Mail"] ?><br />
<input type="text" name="mail" value="<?= !empty($mail)?$mail:"" ?>"<?= $disableForm?" disabled=\"disabled\"":"" ?> class="shortInput" /></p>
<? } ?>

<? if (!$login->isLoggedIn()) { ?><p><? } ?><?= $lComment["Subject"] ?><br />
<input type="text" name="subject" class="shortInput<?= $this->errors->hasError("subject")?" error":"" ?>" value="<?= !empty($subject)?$subject:"" ?>"<?= $disableForm?" disabled=\"disabled\"":"" ?> /> <? $this->errors->printWarningIcon("subject") ?><? if (!$login->isLoggedIn()) { ?></p><? } ?>

<p><?= $lComment["Message"] ?> <? $this->errors->printWarningIcon("message") ?><br />
<textarea name="message" rows="5" style="width:100%"<?= $disableForm?" disabled=\"disabled\"":"" ?><?= $this->errors->hasError("message")?" class=\"error\"":"" ?>><?= $disableForm?$lEditComment["InsufficientPermissions"]:(!empty($message)?$message:"") ?></textarea></p>

<? if (!$disableForm && !$login->isLoggedIn()) { ?>
<p><input type="checkbox" name="remember" value="1"<?= !empty($remember)?" checked=\"checked\"":"" ?> /> <?= $lComment["RememberMe"] ?></p>

<? if ($settings->commentsRequireValidation) { ?>
<p><?= $lComment["ValidationText"] ?></p>

<p><img width="120" height="30" src="<?= scriptUrl ?>/include/form/auditButton.php" border="1" /></p>

<p><input maxlength="5" size="5" name="userdigit" type="text" value="" class="shortInput<?= $this->errors->hasError("validation")?" error":"" ?>" /> <? $this->errors->printWarningIcon("validation") ?></p>
<? } ?>
<? } ?>

<p><input type="submit" class="button" value="<?= $lComment["SendComment"] ?>"<?= $disableForm?" disabled=\"disabled\"":"" ?> /></p>
</form>
</div>