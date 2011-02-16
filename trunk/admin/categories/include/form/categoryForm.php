<script language="JavaScript" type="text/javascript"> 
<!-- 
function validate(form){
 	if(form.deleteCategory.checked) {
 		var agree=confirm('<?= $lCategoryEdit["ConfirmDelete"] ?>');
 		if(agree) return true ;
 		else return false ;
 	}
 	else {
 		return true;
 	}
}
-->
</script>

<form name="post" action="<?= scriptUrl."/".folderCategory."/".fileCategoryEdit ?><?= !empty($category->id)?"?categoryId=".$category->id."&amp;":"?" ?>save=1" enctype="multipart/form-data" method="post" onsubmit="return validate(this)">
<input type="hidden" name="referer" value="<?= !empty($_GET["return"])?$_SERVER["HTTP_REFERER"]:"" ?>" />

<p>*<?= $lCategoryEdit["CategoryTitle"] ?><br />
<input type="text" name="title" value="<?= $category->title ?>" onkeyup="validateField(this, 'shortInput', 'error', warning_title)" class="shortInput<?= empty($category->title) || $errors->hasError("title")?" error":"" ?>" /> <? $errors->printWarningIcon("title",(!$errors->hasError("title")?$lCategoryEdit["MissingTitle"]:""),(empty($category->id)?1:0)) ?></p>

<p><?= $lCategoryEdit["Description"] ?><br />
<textarea name="description" rows="4"><?= $category->description ?></textarea></p>

<? if (!empty($category->id)) { ?><p><input type="checkbox" name="deleteCategory" value="1" onChange="if(this.checked==1) { saveCategory.value='<?= $lCategoryEdit["DeleteCategory"] ?>' } else { saveCategory.value='<?= $lCategoryEdit["SaveCategory"] ?>' }" <?= empty($category->id) || !$category->hasDeletePermission()?" disabled=\"disabled\"":"" ?> /> <?= $lCategoryEdit["DeleteCategory"] ?></p><? } ?>

<p><input name="saveCategory" type="submit" value="<?= $lCategoryEdit["SaveCategory"] ?>" class="button" /></p>
</form>