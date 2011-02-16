<script language="JavaScript" type="text/javascript"> 
<!-- 
function validate(form){
 	if(form.deletePost.checked) {
 		var agree=confirm('<?= $lBlogEditPost["ConfirmDelete"] ?>');
 		if(agree) return true ;
 		else return false ;
 	} 
	<? if ($errors->hasError("postModified")) { ?>
	else {
		var agree=confirm('<?= addslashes($lBlogEditPost["PostModifiedWarning"]) ?>');
		if(agree) return true;
		else return false;
	}
	<? } else { ?>
	else {
		return true;
	}
	<? } ?>
}
--> 
</script>

<form name="post" action="<?= scriptUrl."/".folderBlog."/".fileBlogPostEdit ?>?<?= !empty($post->id)?"postId=".$post->id."&amp;":"" ?>save=1" enctype="multipart/form-data" method="post" onsubmit="return validate(this)">
<input type="hidden" name="referer" value="<?= !empty($referer)?$referer:$_SERVER["HTTP_REFERER"] ?>" />
<input type="hidden" name="refresh" value="0" />
<input type="hidden" name="blogId" value="<?= !empty($_GET["blogId"])?$_GET["blogId"]:(!empty($post->blog->id)?$post->blog->id:0) ?>" />
<input type="hidden" name="keyword" value="cmis_block" />
<input type="hidden" name="lastUpdated" value="<?= $post->getLastUpdated() ?>" />

<p><?= $lBlogEditPost["Subject"] ?><br />
<input type="text" name="subject" value="<?= $post->subject ?>" onkeyup="validateField(this, 'shortInput', 'error', warning_subject)" class="shortInput<?= empty($post->subject) || $errors->hasError("subject")?" error":"" ?>" /> <? $errors->printWarningIcon("subject",(!$errors->hasError("subject")?$lBlogEditPost["MissingSubject"]:""),(empty($post->id) && empty($post->subject)?1:0)) ?></p>

<p><?= $lBlogEditPost["Summary"] ?><br />
<? printRichTextArea("document.post","summary",$post->summary,7,40,1,0,0,"blog") ?></p>

<p><?= $lBlogEditPost["BodyText"] ?><br />
<? printRichTextArea("document.post","text",$post->text,25,40,1,0,0,"../") ?></p>


<? printSubsectionHeader($lBlogEditPost["Categories"],"",1,1,"categories") ?>

<p><?= $lBlogEditPost["CategoriesText"] ?></p>

<div id="categories" class="formIndent">
<? 
$categories = "";
if (!empty($post->categories)) {
	for ($i=0; $i<sizeof($post->categories); $i++) {
		if (!empty($post->categories[$i][1])) {
			$categories .= ($i!=0?", ":"")."".$post->categories[$i][1];
		}
	}
}
include_once(scriptPath."/include/form/categoryInput.php"); 
?>
</div>


<? printSubsectionHeader($lBlogEditPost["PublishingInformation"], "", 1, 0, "publish") ?>

<p><?= $lBlogEditPost["PublishingInformationText"] ?></p>

<div id="publish" class="formIndent" style="display:none">
<?= $lBlogEditPost["PublishingTime"] ?><br />
<input name="day" size="2" maxlength="2" type="text" value="<?= date("j",$post->posted) ?>" onchange="validateDate(document.news.day,document.news.month,document.news.year)" class="normalInput" /> / <input name="month" type="text" size="2" maxlength="2" value="<?= date("n",$post->posted) ?>" onChange="validateDate(document.news.day,document.news.month,document.news.year)" class="normalInput" /> / <input name="year" type="text" size="4" maxlength="4" value="<?= date("Y",$post->posted) ?>" onChange="validateDate(document.news.day,document.news.month,document.news.year)" class="normalInput" /> - <input name="hour" type="text" size="2" maxlength="2" value="<?= date("H",$post->posted) ?>" class="normalInput" />:<input name="minute" type="text" maxlength="2" size="2" value="<?= date("i",$post->posted) ?>" class="normalInput" />

<p><?= $lBlogEditPost["Author"] ?><br />
<select name="userId" class="shortInput">
<?
$result = $dbi->query("SELECT id,name FROM ".userDataTableName." ORDER BY name");
if ($result->rows()) {
	for ($i=0; list($userId,$username)=$result->fetchrow_array(); $i++) {
?>
<option value="<?= $userId ?>"<?= $userId==$post->user->id?" selected=\"selected\"":"" ?>><?= $username ?></option>
<?	
	}
}
?>
</select></p>

<p><input type="checkbox" name="draft" value="1"<?= $post->draft || (!$post->hasPublishPermission() && ($post->draft || empty($post->id)))?" checked=\"checked\"":"" ?><?= !$post->hasPublishPermission()?' disabled="disabled"':'' ?> onChange="if (deletePost.checked!=1) { if(this.checked==1) { savePost.value='<?= $lBlogEditPost["SaveAsDraft"] ?>' } else { savePost.value='<?= $lBlogEditPost["Publish"] ?>' } }" /> <?= $lBlogEditPost["SaveAsDraft"] ?></p>
<? if (!$post->hasPublishPermission() && ($post->draft || empty($post->id))) { ?><input type="hidden" name="draft" value="1" /><? } ?>
</div>


<? printSubsectionHeader($lBlogEditPost["Options"], "", 1, 0, "blogOptions") ?>

<p><?= $lBlogEditPost["OptionsText"] ?></p>

<div id="blogOptions" class="formIndent" style="display:none">
<input type="checkbox" name="showComments" value="1"<?= $post->showComments?" checked=\"checked\"":"" ?> onclick="if (this.checked) { disableComments.disabled=false } else { disableComments.disabled=true }" /> <?= $lBlogEditPost["ShowComments"] ?><br />
<input type="checkbox" name="disableComments" value="1"<?= $post->disableComments?" checked=\"checked\"":"" ?> /> <?= $lBlogEditPost["DisableComments"] ?>
</div>

<? printSubsectionHeader($lBlogEditPost["DeletePost"], "", 1, 0, "delete") ?>

<p><?= $lBlogEditPost["DeletePostText"] ?></p>

<div id="delete" class="formIndent" style="display:none">
<input type="checkbox" name="deletePost" value="1" onChange="if(this.checked==1) { savePost.value='<?= $lBlogEditPost["DeletePost"] ?>' } else { if (draft.checked==1) { savePost.value='<?= $lBlogEditPost["SaveAsDraft"] ?>'; } else {savePost.value='<?= $lBlogEditPost["Publish"] ?>' } }" <?= empty($post->id) || !$post->hasDeletePermission()?" disabled=\"disabled\"":"" ?> /> <?= $lBlogEditPost["DeletePost"] ?><br /><br />
</div>

<p><input name="savePost" type="submit" value="<?= $post->draft || (!$post->hasPublishPermission() && ($post->draft || empty($post->id)))?$lBlogEditPost["SaveAsDraft"]:$lBlogEditPost["Publish"] ?>" class="button" /></p>
</form>