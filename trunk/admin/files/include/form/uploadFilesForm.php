<? if(empty($numberOfFiles) || ($errors->hasErrors() && $mode!="upload")) { ?>
<form action="uploadFiles.php?folderId=<?= $folder->id ?><?= showPopup?"&amp;popup=1":"" ?>&amp;mode=send" method="post">
<p><input type="text" size="2" name="numberOfFiles" value="1" class="normalInput" /> <input type="submit" value="Send" class="button" /></p>
</form>
<? } else { ?>
<form action="uploadFiles.php?folderId=<?= $folder->id ?><?= showPopup?"&amp;popup=1":"" ?>&amp;mode=upload" method="post" enctype="multipart/form-data">
<input type="hidden" name="numberOfFiles" value="<?= $numberOfFiles ?>" />

<? for($i=1;$i<$numberOfFiles+1;$i++) { ?>
<p><?= $lFileUploadFiles["File"] ?> <?= $i ?><br />
<input type="file" name="file<?= $i ?>" value="" class="longInput" /></p>
<? } ?>

<p><input type="submit" value="<?= $lFileUploadFiles["UploadFiles"] ?>" class="button" /></p>
</form>
<? } ?>