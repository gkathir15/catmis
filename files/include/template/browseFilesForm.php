<table width="100%" cellspacing="0" cellpadding="1" border="0" summary="" style="padding:1px">
<? if($folder->id!=0) { ?>
<tr>
<td style="background:#ebebeb">
&nbsp;
</td>

<td height="25" width="16" style="background:#ebebeb">
<a href="<?= scriptUrl."/".folderFiles."/browseFiles.php?folderId=".(!empty($folder->parent)?$folder->parent->id:0) ?><?= showPopup?"&amp;popup=1":"" ?><?= !empty($fileTypes)?"&amp;fileTypes=".$fileTypes:"" ?>" style="text-decoration:none"><img src="<?= iconUrl ?>/folder.png" border="0" /></a>
</td>

<td width="50%" style="background:#ebebeb">
<a href="<?= scriptUrl."/".folderFiles."/browseFiles.php?folderId=".(!empty($folder->parent)?$folder->parent->id:0) ?><?= showPopup?"&amp;popup=1":"" ?><?= !empty($fileTypes)?"&amp;fileTypes=".$fileTypes:"" ?>" style="text-decoration:none"><b>..</b></a>
</td>

<td width="50%" class="small1" style="background:#ebebeb">
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
?>
<tr>
<td height="25" width="16"<?= $i%2==0?" style=\"background:#ebebeb\"":"" ?>>
&nbsp;
</td>

<td height="25" width="16"<?= $i%2==0?" style=\"background:#ebebeb\"":"" ?>>
<a href="<?= scriptUrl."/".folderFiles."/browseFiles.php?folderId=".$subFolder->id ?><?= showPopup?"&amp;popup=1":"" ?><?= !empty($fileTypes)?"&amp;fileTypes=".$fileTypes:"" ?>"><img src="<?= iconUrl ?>/folder.png" border="0" /></a>
</td>

<td width="50%"<?= $i%2==0?" style=\"background:#ebebeb\"":"" ?>>
<a href="<?= scriptUrl."/".folderFiles."/browseFiles.php?folderId=".$subFolder->id ?><?= showPopup?"&amp;popup=1":"" ?><?= !empty($fileTypes)?"&amp;fileTypes=".$fileTypes:"" ?>"><?= $subFolder->name ?></a>
</td>

<td width="50%" align="right" <?= $i%2==0?" style=\"background:#ebebeb\"":"" ?>>
<?= getFormattedSize($subFolder->getSize()) ?>
</td>
</tr>
<?		
			$old = $i;
		}
	}
	
	if($files->rows()) {
		for($i=$old+1;(list($id)=$files->fetchrow_array());$i++) {
			$file = new File($id);
?>
<tr>
<td height="25" width="16"<?= $i%2==0?" style=\"background:#ebebeb\"":"" ?>>
<input type="radio" name="files[]" value="<?= $file->id ?>" onclick="window.parent.document.getElementById('fileUrl').value='<?= scriptUrl."/".folderFiles."/".fileFilesGetFile."?fileId=".$file->id ?>';window.parent.document.getElementById('fileName').value='<?= $file->name ?>'" />
</td>

<td height="25" width="16"<?= $i%2==0?" style=\"background:#ebebeb\"":"" ?>>
<a href="<?= scriptUrl."/".folderFiles."/".fileFilesGetFile ?>?fileId=<?= $file->id ?>" target="_blank"><img src="<?= iconUrl ?>/page.gif" border="0" /></a>
</td>

<td width="50%"<?= $i%2==0?" style=\"background:#ebebeb\"":"" ?>>
<a href="<?= scriptUrl."/".folderFiles."/".fileFilesGetFile ?>?fileId=<?= $file->id ?>" target="_blank"><?= $file->name ?></a>
</td>

<td width="50%" align="right"<?= $i%2==0?" style=\"background:#ebebeb\"":"" ?>>
<?= getFormattedSize($file->getSize()) ?>
</td>
</tr>
<?
			}
		}
	}
?>
</table>