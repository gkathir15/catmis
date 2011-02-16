<form name="page" action="<?= filePageBarEdit ?>?<?= !empty($page->id)?"pageId=".$page->id:"" ?>&amp;save=1" method="post" enctype="multipart/form-data">
<input type="hidden" name="referer" value="<?= !empty($_GET["return"])?$_SERVER["HTTP_REFERER"]:"" ?>" />

<p><?= $lEditPageBar["LeftBarParent"] ?><br />
<select name="leftTemplate" class="shortInput">
<option value="0">New template</option>
<?
$result = $dbi->query("SELECT id,title FROM ".pageTableName." WHERE leftText!='' ORDER BY title");
if ($result->rows()) {
	for ($i=0;(list($id,$title)=$result->fetchrow_array());$i++) {
		echo "<option value=\"$id\"".($id==$page->leftTemplate?" selected=\"selected\"":"").">$title</option>";
	}	
}
?>
</select></p>

<p><?= $lEditPageBar["LeftText"] ?><br />
<? printRichTextArea("document.page","leftText",$page->leftText,15,40) ?></p>

<p><?= $lEditPageBar["RightBarParent"] ?><br />
<select name="rightTemplate" class="shortInput">
<option value="0">New template</option>
<?
$result = $dbi->query("SELECT id,title FROM ".pageTableName." WHERE rightText!='' ORDER BY title");
if ($result->rows()) {
	for ($i=0;(list($id,$title)=$result->fetchrow_array());$i++) {
		echo "<option value=\"$id\"".($id==$page->rightTemplate?" selected=\"selected\"":"").">$title</option>";
	}	
}
?>
</select></p>
<p><?= $lEditPageBar["RightText"] ?><br />
<? printRichTextArea("document.page","rightText",$page->rightText,15,40) ?></p>

<p><input name="savePage" type="submit" value="<?= $lEditPageBar["Save"] ?>" class="button" /></p>
</form>
