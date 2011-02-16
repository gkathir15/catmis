<script language="JavaScript" type="text/javascript">
<!--
function validate(form){	
	if(form.deletePage.checked) {		
		var agree=confirm('<?= addslashes($lEditPage["ConfirmDelete"]) ?>');		
		if(agree) return true ;		
		else return false ;	
	}
	<? if ($errors->hasError("pageModified")) { ?>
	else {
		var agree=confirm('<?= addslashes($lEditPage["PageModifiedWarning"]) ?>');
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

<form name="page" action="<?= filePageEdit ?>?<?= !empty($pageObject->id)?"pageId=".$pageObject->id:"" ?>&amp;save=1" method="post" enctype="multipart/form-data" onsubmit="return validate(this)">
<input type="hidden" name="referer" value="<?= !empty($_GET["return"])?(!empty($_SERVER["HTTP_REFERER"])?$_SERVER["HTTP_REFERER"]:""):"" ?>" />
<input type="hidden" name="lastUpdated" value="<?= $pageObject->getLastUpdated() ?>" />

<p><?= $lEditPage["Title"] ?><br />
<input type="text" name="title" value="<?= $pageObject->title ?>" onkeyup="validateField(this, 'shortInput', 'error', warning_title)" maxsize="100" tabindex="1" class="shortInput<?= empty($pageObject->title) || $errors->hasError("title")?" error":"" ?>" /> <? $errors->printWarningIcon("title",(!$errors->hasError("title")?$lEditPage["TitleMissing"]:""),(empty($pageObject->id)?1:0)) ?></p>

<p><?= $lEditPage["SubpageOf"] ?><br />
<select name="parentId" class="shortInput" tabindex="2">
<option value="0"<?= empty($pageObject->parent->id)?" selected=\"selected\"":"" ?>><?= pageTitle ?></option>
<? $pageObject->printNavigationOption() ?>
</select></p>

<p><?= $lEditPage["BodyText"] ?><br />
<? printRichTextArea("document.page","text",$pageObject->text,30,40,0,3) ?></p>


<? printSubsectionHeader($lEditPage["Options"],"",1,0,"pageOptions") ?>

<p><?= $lEditPage["OptionsText"] ?></p>

<div id="pageOptions" class="formIndent" style="display:none">
<?= $lEditPage["Link"] ?><br />
<input type="text" name="link" value="<?= $pageObject->link ?>" maxsize="100" class="shortInput" tabindex="6" />

<p><input type="checkbox" name="showComments" value="1" tabindex="7"<?= $pageObject->showComments?" checked=\"checked\"":"" ?> /> <?= $lEditPage["ShowComments"] ?><br />
<input type="checkbox" name="disableComments" value="1" tabindex="7"<?= $pageObject->disableComments?" checked=\"checked\"":"" ?> /> <?= $lEditPage["DisableComments"] ?></p>

<p><input type="checkbox" name="showInMenu" value="1" tabindex="8"<?= $pageObject->showInMenu?" checked=\"checked\"":"" ?> /> <?= $lEditPage["ShowInNavbar"] ?><br />
<input type="checkbox" name="separator" value="1" tabindex="8"<?= $pageObject->separator?" checked=\"checked\"":"" ?> /> <?= $lEditPage["Separator"] ?></p>

<p><input type="checkbox" name="showLastModified" value="1" tabindex="8"<?= $pageObject->showLastModified?" checked=\"checked\"":"" ?> /> <?= $lEditPage["ShowLastModified"] ?></p>
</div>


<? if (!empty($pageObject->id)) { ?>
<? printSubsectionHeader($lEditPage["Delete"],"",1,0,"delete") ?>

<p><?= $lEditPage["DeleteText"] ?></p>

<div id="delete" class="formIndent" style="display:none">
<input type="checkbox" name="deletePage" value="1" tabindex="9" onchange="if(this.checked==1) { savePage.value='<?= $lEditPage["Delete"] ?>' } else { savePage.value='<?= $lEditPage["Save"] ?>' }" <?= empty($pageObject->id)?" disabled=\"disabled\"":"" ?> /> <?= $lEditPage["Delete"] ?><br /><br />
</div>
<? } ?>

<p><input name="savePage" type="submit" value="<?= $lEditPage["Save"] ?>" tabindex="10" class="button" /></p>
</form>