<script language="JavaScript" type="text/javascript">
<!--
function validate(form){	
	if(form.folderName.value=="") {		
		alert('<?= $lFileEditFolder["MissingFoldername"] ?>');
		return false;
	}
	else {
		return true;	
	}
}
-->
</script>

<form action="<?= scriptUrl."/".folderFilesAdmin."/".fileFilesEditFolder.(!empty($folder->id)?"?folderId=".$folder->id:"") ?>&amp;mode=update" method="post" onsubmit="return validate(this)">
<p><?= $lFileEditFolder["Name"] ?><br />
<input type="text" name="folderName" value="<?= $folder->name ?>" class="shortInput" /></p>

<p><?= $lFileEditFolder["Folder"] ?><br />
<select name="folderId" class="shortInput">
<? $folder->parent->printFolderOptions() ?>
</select></p>

<p><input type="submit" value="<?= $lFileEditFolder["SaveFolder"] ?>" class="button" /></p>
</form>