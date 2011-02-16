<form action="index.php?folderId=<?= $folder->id ?>" method="post" onsubmit="return validate(this)">
<table width="100%" cellspacing="0" cellpadding="2" border="0" summary="" class="index">
<tr>
<td width="16" class="indexHeader" colspan="2">
&nbsp;
</td>

<td nowrap="nowrap" width="100%" class="indexHeader">
<b><?= $lFileIndex["Name"] ?></b>&nbsp;
</td>

<td nowrap="nowrap" class="indexHeader" align="right">
<b><?= $lFileIndex["Size"] ?></b>&nbsp;&nbsp;
</td>

<td nowrap="nowrap" class="indexHeader">
<b><?= $lFileIndex["LastUpdated"] ?></b>&nbsp;&nbsp;
</td>

<td width="16" class="indexHeader">
&nbsp;
</td>
</tr>

<? if(!empty($folder->id)) { ?>
<tr>
<td height="30" width="16" class="itemAlt">
&nbsp;
</td>

<td class="itemAlt">
<a href="index.php?folderId=<?= $folder->parent->id ?>" style="text-decoration:none"><img src="<?= iconUrl ?>/folder.png" border="0" /></a>
</td>

<td class="itemAlt">
<a href="index.php?folderId=<?= $folder->parent->id ?>" style="text-decoration:none"><b>..</b></a>
</td>

<td colspan="3" class="itemAlt">
&nbsp;
</td>
</tr>

<?
}

$old = "";
if($subfolders->rows() || $files->rows()) {
	if($subfolders->rows()) {
		for($i=($folder->id==0?0:1);(list($id)=$subfolders->fetchrow_array());$i++) {
			$subFolder = new Folder($id);
			$class = "item".($i%2==0?"Alt":"");
?>
<tr>
<td height="30" class="<?= $class ?>">
<input type="checkbox" name="folders[]" value="<?= $subFolder->id ?>" />
</td>

<td class="<?= $class ?>">
<a href="index.php?folderId=<?= $subFolder->id ?>"><img src="<?= iconUrl ?>/folder.png" border="0" /></a>
</td>

<td class="<?= $class ?>">
<a href="index.php?folderId=<?= $subFolder->id ?>"><?= validateTextLength($subFolder->name,30) ?></a>
</td>

<td nowrap="nowrap" align="right" class="small1 <?= $class ?>">
<? $site->printFormattedSize($subFolder->getSize()) ?>
</td>

<td nowrap="nowrap" class="small1 <?= $class ?>">
<? 
$lastModified = $subFolder->getLastModified();
if ($lastModified!=0) $site->printTimestamp($lastModified);
else echo "-";
?>
</td>

<td height="25" class="<?= $class ?>">
<?= $login->isAdmin()?"<a href=\"".scriptUrl."/".folderAdmin."/".folderFiles."/".fileFilesEditFolder."?folderId=".$subFolder->id."\"><img src=\"".iconUrl."/edit.png\" border=\"0\" /></a>":"" ?>
</td>
</tr>
<?
			$old = $i;
		}
	}
	
	if($files->rows()) {
		for($i=$old+1;(list($id)=$files->fetchrow_array());$i++) {
			$file = new File($id);
			$class = "item".($i%2==0?"Alt":"");
?>
<tr>
<td height="25" class="<?= $class ?>">
<input type="checkbox" name="files[]" value="<?= $id ?>" />
</td>

<td height="25" class="<?= $class ?>">
<a href="<?= scriptUrl."/".folderFiles."/".fileFilesGetFile ?>?fileId=<?= $file->id ?>" target="_blank"><img src="<?= $file->getIconUrl () ?>" border="0" /></a>
</td>

<td class="<?= $class ?>">
<a href="<?= scriptUrl."/".folderFiles."/".fileFilesGetFile ?>?fileId=<?= $file->id ?>" target="_blank"><?= validateTextLength($file->name, 30) ?></a>
</td>

<td nowrap="nowrap" align="right" class="small1 <?= $class ?>">
<? $site->printFormattedSize($file->getSize()) ?>
</td>

<td nowrap="nowrap" class="small1 <?= $class ?>">
<?
$lastModified = $file->getLastModified();
if ($lastModified!=0) $site->printTimestamp($lastModified);
else echo "-";
?>
</td>

<td height="25" class="item<?= $i%2==0?"Alt":"" ?>">
<?= $login->isAdmin()?"<a href=\"".scriptUrl."/".folderAdmin."/".folderFiles."/".fileFilesEditFile."?fileId=".$file->id."\"><img src=\"".iconUrl."/edit.png\" border=\"0\" /></a>":"" ?>
</td>
</tr>
<?
			}
		}
	}
?>
</table>
<br />

<select name="moveFolderId" class="normalInput" style="width:20%">
<? $folder->printFolderOptions() ?>
</select> <input type="submit" name="move" value="<?= $lFileIndex["Move"] ?>" class="button"/>&nbsp;&nbsp;<input name="delete" type="submit" value="<?= $lFileIndex["Delete"] ?>" class="button" onclick="var agree=confirm('<?= $lFileIndex["ConfirmDelete"] ?>');if(agree) {return true;}else {return false;}"/>
</form>