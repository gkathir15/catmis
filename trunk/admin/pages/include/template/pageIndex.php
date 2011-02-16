<tr>
<td width="100%" nowrap="nowrap" class="<?= $i%2!=0?"item":"itemAlt" ?>" style="padding-left:<?= $level*20 ?>px">
<table width="100%" cellspacing="0" cellpadding"0">
<tr>
<td width="16">
<?
if($page->getNumberOfSubpages()!=0) {
	if (!empty($_GET["parentId"])) {
		if($page->isParent($_GET["parentId"]) || $page->id==$_GET["parentId"]) {
?>
<a href="<?= scriptUrl ?>/<?= folderPage ?>/<?= filePageIndex ?>?parentId=<?= $page->parent->id ?>"><img src="<?= iconUrl ?>/collapse.gif" height="16" width="16" border="0" alt="<?= $lPageIndex["CollapsePage"] ?>" title="<?= $lPageIndex["CollapsePage"] ?>" /></a> 
<? 
		}
		else { 
?>
<a href="<?= scriptUrl ?>/<?= folderPage ?>/<?= filePageIndex ?>?parentId=<?= $page->id ?>"><img src="<?= iconUrl ?>/<?= "expand".($page->getNumberOfSubpages()==0?"_disabled":"") ?>.gif" height="16" width="16" border="0" alt="<?= $lPageIndex["ExpandPage"] ?>" title="<?= $lPageIndex["ExpandPage"] ?>" /></a> 
<?
		}
	}
	else { 
?>
<a href="<?= scriptUrl ?>/<?= folderPage ?>/<?= filePageIndex ?>?parentId=<?= $page->id ?>"><img src="<?= iconUrl ?>/<?= "expand".($page->getNumberOfSubpages()==0?"_disabled":"") ?>.gif" height="16" width="16" border="0" alt="<?= $lPageIndex["ExpandPage"] ?>" title="<?= $lPageIndex["ExpandPage"] ?>" /></a> 
<?
	}
} 
else { 
?>
<img src="<?= iconUrl ?>/spacer_1616.gif" height="16" width="16" border="0" alt="" title="" />
<? } ?>
</td>

<td class="item" style="border:0px">
<input type="checkbox" name="pages[]" value="<?= $page->id ?>" />
</td>

<td width="16" nowrap="nowrap" class="item" style="border:0px">
<a href="<?= scriptUrl ?>/<?= folderPage ?>/<?= filePageEdit ?>?pageId=<?= $page->id ?>&amp;return=1"><img src="<?= iconUrl ?>/edit.png" height="16" width="16" border="0" alt="<?= $lPageIndex["EditPage"] ?>" title="<?= $lPageIndex["EditPage"] ?>" /></a>
</td>

<td width="100%" class="item" style="border:0px">
<a href="<?= $page->getPageLink() ?>" title="<?= $page->title ?>"><?= substr($page->title,0,15).(strlen($page->title)>15?"...":"") ?></a>
</td>
</tr>
</table>
</td>

<td width="16" align="center" class="<?= $i%2!=0?"item":"itemAlt" ?>" style="padding-right:8px">
<a href="<?= scriptUrl ?>/<?= folderPage ?>/<?= filePageIndex ?>?pageId=<?= $page->id ?>&amp;visible=<?= $page->showInMenu?0:1 ?>"><img src="<?= iconUrl ?>/<?= $page->showInMenu?"visible.gif":"hidden.gif" ?>" height="16" width="16" border="0" alt="<?= $lPageIndex["VisibleText"] ?>" title="<?= $lPageIndex["VisibleText"] ?>" /></a>
</td>

<td align="center" width="16" class="<?= $i%2!=0?"item":"itemAlt" ?>" style="padding-right:8px">
<? if($i!=0) { ?>
<a href="<?= scriptUrl ?>/<?= folderPage ?>/<?= filePageIndex ?>?pageId=<?= $page->id ?>&amp;up=1"><img src="<?= iconUrl ?>/go-up.png" height="16" width="16" border="0" alt="<?= $lPageIndex["MoveUpText"] ?>" title="<?= $lPageIndex["MoveUpText"] ?>" /></a>
<? } ?>
</td>

<td align="center" width="16" class="<?= $i%2!=0?"item":"itemAlt" ?>" style="padding-right:8px">
<? if(($i+1)<$result->rows()) { ?>
<a href="<?= scriptUrl ?>/<?= folderPage ?>/<?= filePageIndex ?>?pageId=<?= $page->id ?>&amp;down=1"><img src="<?= iconUrl ?>/go-down.png" height="16" width="16" border="0" alt="<?= $lPageIndex["MoveDownText"] ?>" title="<?= $lPageIndex["MoveDownText"] ?>" /></a>
<? } ?>
</td>
</tr>