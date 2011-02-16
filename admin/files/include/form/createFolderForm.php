<form action="createFolder.php?mode=create" method="post">
<input type="hidden" name="folderId" value="<?= $folderId ?>" />

<p><input type="text" name="folderName" value="<?= $name ?>" class="normalInput" /></p>

<p><input type="submit" value="<?= $lFileCreateFolder["SaveFolder"] ?>" class="button" /></p>
</form>