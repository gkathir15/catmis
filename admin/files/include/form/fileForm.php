<script language="JavaScript" type="text/javascript">
<!--
function validate(form){	
	if(form.filename.value=="") {		
		alert('<?= $lFileEditFile["MissingFilename"] ?>');
		return false;	
	}
	else {
		return true;	
	}
}
-->
</script>

<form action="<?= scriptUrl."/".folderFilesAdmin."/".fileFilesEditFile.(!empty($file->id)?"?fileId=".$file->id:"") ?>&amp;mode=update" method="post" onsubmit="return validate(this)">
<p><?= $lFileEditFile["Name"] ?><br />
<input type="text" name="filename" value="<?= $file->name ?>" class="shortInput" /></p>

<p><?= $lFileEditFile["Folder"] ?><br />
<select name="folderId" class="shortInput">
<? $file->parent->printFolderOptions() ?>
</select></p>

<p><input type="submit" value="<?= $lFileEditFile["SaveFile"] ?>" class="button" /></p>
</form>