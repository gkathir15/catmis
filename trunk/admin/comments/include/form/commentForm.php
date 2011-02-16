<script language="JavaScript" type="text/javascript"> 
<!-- 
function validate(form){
 	if(form.deleteComment.checked) {
 		var agree=confirm('<?= addslashes($lEditComment["ConfirmDelete"]) ?>');
 		if(agree) return true ;
 		else return false ;
 	}
 	else {
 		return true;
 	}
}
--> 
</script>

<form name="comment" action="<?= scriptUrl."/".folderComment."/".fileCommentEdit ?><?= !empty($comment->id)?"?commentId=".$comment->id."&amp;":"?" ?><?= showPopup?"popup=1&amp;":"" ?>save=1" enctype="multipart/form-data" method="post" onsubmit="return validate(this)">
<input type="hidden" name="referer" value="<?= !empty($_GET["return"])?$_SERVER["HTTP_REFERER"]:"" ?>" />
<input type="hidden" name="moduleId" value="<?= $comment->moduleId ?>" />
<input type="hidden" name="moduleContentTypeId" value="<?= $comment->moduleContentTypeId ?>" />
<input type="hidden" name="moduleContentId" value="<?= $comment->moduleContentId ?>" />

<? if (empty($comment->userId)) { ?>
<p><?= $lEditComment["Name"] ?><br />
<input type="text" name="name" value="<?= $comment->name ?>" onkeyup="validateField(this, 'shortInput', 'error', warning_name)" class="shortInput<?= empty($comment->name) || $errors->hasError("name")?" error":"" ?>" /> <? $errors->printWarningIcon("name",(!$errors->hasError("name")?$lEditComment["MissingName"]:""),(empty($comment->id)?1:0)) ?></p></p>

<p><?= $lEditComment["Mail"] ?><br />
<input type="text" name="mail" value="<?= $comment->mail ?>" class="shortInput" /></p>
<? } else {?>
<input type="hidden" name="userId" value="<?= $comment->userId ?>" />
<? } ?>

<p><?= $lEditComment["Link"] ?><br />
<input type="text" name="link" value="<?= $comment->link ?>" class="shortInput" /></p>

<p><?= $lEditComment["Subject"] ?><br />
<input type="text" name="subject" value="<?= $comment->subject ?>" onkeyup="validateField(this, 'shortInput', 'error', warning_subject)" class="shortInput<?=  empty($comment->subject) || $errors->hasError("subject")?" error":"" ?>" /> <? $errors->printWarningIcon("subject") ?> <? $errors->printWarningIcon("subject",(!$errors->hasError("subject")?$lEditComment["MissingSubject"]:""),(empty($comment->id)?1:0)) ?></p>

<p><?= $lEditComment["BodyText"] ?> <? $errors->printWarningIcon("message",(!$errors->hasError("message")?$lEditComment["MissingText"]:""),(empty($comment->id)?1:0)) ?><br />
<textarea name="message" rows="10" cols="40" onkeyup="validateField(this, '', 'error', warning_message)"<?=  empty($comment->message) || $errors->hasError("message")?" class=\"error\"":"" ?>><?= $comment->message ?></textarea></p>

<p><input type="checkbox" name="spam" value="1"<?= $comment->spam?" checked=\"checked\"":"" ?><?= empty($comment->id)?" disabled=\"disabled\"":"" ?> /> <?= $lEditComment["MarkAsSpam"] ?><br />
<input type="checkbox" name="deleteComment" value="1" onChange="if(this.checked==1) { saveComment.value='<?= $lEditComment["DeleteComment"] ?>' } else { saveComment.value='<?= $lEditComment["SaveComment"] ?>' }" <?= empty($comment->id) || !$comment->hasDeletePermission()?" disabled=\"disabled\"":"" ?> /> <?= $lEditComment["DeleteComment"] ?></p>

<p><input name="saveComment" type="submit" value="<?= $lEditComment["SaveComment"] ?>" class="button" /></p>
</form>