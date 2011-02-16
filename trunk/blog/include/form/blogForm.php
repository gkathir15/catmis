<script language="JavaScript" type="text/javascript"> 
<!-- 
function validate(form){
 	if(form.deleteBlog.checked) {
 		var agree=confirm('<?= $lBlogEdit["ConfirmDelete"] ?>');
 		if(agree) return true ;
 		else return false ;
 	} 
 	else {
 		return true;
 	}
}
--> 
</script>

<form name="blog" action="<?= scriptUrl."/".folderBlog."/".fileBlogEdit ?>?<?= !empty($blog->id)?"blogId=".$blog->id."&amp;":"" ?>save=1" enctype="multipart/form-data" method="post" onsubmit="return validate(this)">
<input type="hidden" name="referer" value="<?= !empty($_GET["return"])?$_SERVER["HTTP_REFERER"]:"" ?>" />

<p><?= $lBlogEdit["BlogTitle"] ?><br />
<input type="text" name="title" value="<?= stripslashes($blog->title) ?>" onkeyup="validateField(this, 'shortInput', 'error', warning_title)" class="shortInput<?= empty($blog->title) || $errors->hasError("title")?" error":"" ?>" /> <? $errors->printWarningIcon("title",(!$errors->hasError("title")?$lBlogEdit["MissingTitle"]:""),(empty($blog->id) && empty($blog->title)?1:0)) ?></p>

<p><?= $lBlogEdit["Category"] ?><br />
<input type="text" name="category" value="<?= stripslashes($blog->category) ?>" class="shortInput" /></p>

<p><?= $lBlogEdit["Description"] ?><br />
<textarea rows="5" cols="40" name="description"><?= $blog->description ?></textarea></p>


<? printSubsectionHeader($lBlogEdit["Image"],"",1,0,"image") ?>

<p><?= $lBlogEdit["ImageDescription"] ?></p>

<div id="image" class="formIndent" style="display:none">
<table width="100%" cellspacing="0" cellpadding="2" border="0" summary="Images"> 
<tr> 
<td width="35">
<img name="img0_preview" src="<?= file_exists(scriptPath."/".folderUploadedFiles."/blog_".$blog->id.".jpg")?scriptUrl."/".folderUploadedFiles."/blog_".$blog->id.".jpg":iconUrl."/picture5050.gif" ?>" width="50" height="50" border="0" alt="" title="" class="border" />
</td> 

<td width="100%"> 
<input type="file" name="img_0" class="normalInput" onBlur="imagePreview(document.img0_preview,this.value)"  /> <? $errors->printWarningIcon("upload",(!$errors->hasError("upload")?$lErrors["ReUploadImages"]:""),$errors->hasError("upload")?1:0) ?>
</td>  
</tr>
</table>
</div>


<? printSubsectionHeader($lBlogEdit["Options"],"",1,0,"options") ?>

<p><?= $lBlogEdit["OptionsText"] ?></p>

<div id="options" class="formIndent" style="display:none">
<?= $lBlogEdit["Language"] ?><br />
<select name="language" class="shortInput">
<?
// Look for languages
if ($handle = opendir(scriptPath."/".folderBlog."/include/language")) {
	for ($i=0;false!==($file = readdir($handle));$i++) {
		if (file_exists(scriptPath."/".folderBlog."/include/language/$file/about.php")) {
			include scriptPath."/".folderBlog."/include/language/$file/about.php";
			echo "<option value=\"$file\"".($blog->language==$file || pageLanguage==$file?" selected=\"selected\"":"").">$language</option>";
		}
	}
}
?>
</select>

<p><?= $lBlogEdit["PostPrPage"] ?><br />
<select name="postLimit" class="shortInput">
<option value="5"<?= $blog->postLimit==5?" selected=\"selected\"":"" ?>>5</option>
<option value="10"<?= $blog->postLimit==10 || empty($blogId)?" selected=\"selected\"":"" ?>>10</option>
<option value="15"<?= $blog->postLimit==15?" selected=\"selected\"":"" ?>>15</option>
<option value="20"<?= $blog->postLimit==20?" selected=\"selected\"":"" ?>>20</option>
<option value="25"<?= $blog->postLimit==25?" selected=\"selected\"":"" ?>>25</option>
<option value="30"<?= $blog->postLimit==30?" selected=\"selected\"":"" ?>>30</option>
</select></p>

<p><?= $lBlogEdit["Subscribers"] ?><br />
<textarea name="subscribers" rows="5" cols="40"><?= stripslashes($blog->subscribers) ?></textarea></p>
</div>


<? if (!empty($blog->id)) { ?>
<? printSubsectionHeader($lBlogEdit["DeleteBlog"],"",1,0,"delete") ?>

<p><?= $lBlogEdit["DeleteBlogText"] ?></p>

<div id="delete" class="formIndent" style="display:none">
<input type="checkbox" name="deleteBlog" value="1" onChange="if(this.checked==1) { saveBlog.value='<?= $lBlogEdit["DeleteBlog"] ?>' } else { saveBlog.value='<?= $lBlogEdit["SaveBlog"] ?>' }" <?= empty($blog->id)?" disabled=\"disabled\"":"" ?> /> <?= $lBlogEdit["DeleteBlog"] ?><br /><br />
</div>
<? } ?>

<p><input name="saveBlog" type="submit" value="<?= $lBlogEdit["SaveBlog"] ?>" class="button" /></p>
</form>