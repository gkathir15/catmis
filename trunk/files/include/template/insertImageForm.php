<form name="upload" action="<?= scriptUrl ?>/<?= folderFiles ?>/<?= fileFilesInsertImage ?>?form=<?= $_GET[form] ?>&amp;popup=1" enctype="multipart/form-data" method="post">
<table>
<tr>
<td>
<input name="type" type="radio" value="1" onclick="submit()"<?= $_POST[type]==1 || empty($_POST[type])?" checked=\"checked\"":"" ?> />
</td>

<td>
<?= $lFileInsertImage[LocalFile] ?>
</td>
</tr>

<? if($_POST[type]==1 || empty($_POST[type])) { ?>
<tr>
<td>
&nbsp;
</td>

<td>
<input type="file" name="file" value="" class="normalInput" />
</td>
</tr>
<? } ?>

<tr>
<td>
<input name="type" type="radio" value="2" onclick="popup('<?= scriptUrl ?>/<?= folderFiles ?>/browseFiles.php?popup=1','insertlibrary',450,300,'scrollbars,resizable');<?= $_POST[type]!=2?"submit();":"" ?>"<?= $_POST[type]==2?" checked=\"checked\"":"" ?> />
</td>

<td>
<?= $lFileInsertImage[LibraryFile] ?>
</td>
</tr>

<? if($_POST[type]==2) { ?>
<tr>
<td>
&nbsp;
</td>

<td>
<input type="hidden" name="pathId" value="" />
<input type="text" name="path" value="" class="normalInput" />
</td>
</tr>
<? } ?>

<tr>
<td>
<input name="type" type="radio" value="3" onclick="submit()"<?= $_POST[type]==3?" checked=\"checked\"":"" ?> />
</td>

<td>
<?= $lFileInsertImage[RemoteFile] ?>
</td>
</tr>

<? if($_POST[type]==3) { ?>
<tr>
<td>
&nbsp;
</td>

<td>
<input type="text" name="url" value="http://" class="normalInput" />
</td>
</tr>
<? } ?>
</table>

<p><?= $lFileInsertImage[ImageText] ?>
</p>

<p><input type="text" name="text" value="" class="normalInput" /></p>

<p><input type="submit" value="<?= $lFileInsertImage[InsertImage] ?>" class="button" /></p>	
</form>