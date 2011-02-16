<?
// Include common functions and declarations
require_once "../../../../include/common.php";

// Check if user
if(!$login->isUser()) {
	$login->printLoginForm();
	exit();
}

// Initialize variables
$imageFile = "";
$imageText = "";

// Upload picture
if(!empty($_FILES["file"]["name"])) {
	$extension = substr(strrchr($_FILES["file"]["name"],"."), 1);
	if($extension=="gif" || $extension=="jpg" || $extension=="jpeg" || $extension=="JPEG" || $extension=="GIF" || $extension=="JPG" || $extension=="PNG" || $extension=="png") {
		if ($extension=="jpeg" || $extension=="JPEG") $extension = "jpg";
		$extension = convertToLowercase($extension);
		
		// Resize file if not webmaster
		if (!$login->isWebmaster()) {
			$dimensions = getImageDimensions($_FILES["file"]["tmp_name"]);
			$width = 600;
			$ratio = $width/$dimensions[0];
			$height = $dimensions[1]*$ratio;
			if ($dimensions[0] > $width) resizeToFile($_FILES["file"]["tmp_name"], $width, $height, $_FILES["file"]["tmp_name"], 100);
		}

		// Insert into file database
		$dbi->query("INSERT INTO ".fileTableName."(folderId,name,type,size) VALUES('".(!empty($_POST["imageTargetFolder"])?$_POST["imageTargetFolder"]:pageUploadFolder)."',".$dbi->quote($_FILES["file"]["name"]).",".$dbi->quote($_FILES["file"]["type"]).",".$dbi->quote($_FILES["file"]["size"]).")");
		$id = $dbi->getInsertId();

		if (!empty($id)) {
			// Get thumbnail width
			$thumbnailWidth = getPostValue("uploadImageThumbnailWidth");
			$thumbnailLink = getPostValue("uploadImageThumbnailLink");
			
			// Move uploaded file
			$filename = filePath."/".$id.".".$extension;
			move_uploaded_file($_FILES["file"]["tmp_name"],$filename);
			$imageFile = scriptUrl."/".folderFiles."/".fileFilesGetFile."?fileId=".$id.(!empty($thumbnailWidth) ? "&amp;imageWidth=".$thumbnailWidth : "").(empty($thumbnailLink)?"&amp;noLink=1":"");
			$imageText = !empty($_POST["uploadImageDescription"])?$_POST["uploadImageDescription"]:str_replace(".".getFileExtension($_FILES["file"]["name"]),"",$_FILES["file"]["name"]);
		}
	}
}
else if (!empty($_POST["fileUrl"])) {
	$imageFile = $_POST["fileUrl"];
	$imageText = !empty($_POST["uploadImageDescription"])?$_POST["uploadImageDescription"]:(!empty($_POST["fileName"])?$_POST["fileName"]:"");
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>{#advimage_dlg.dialog_title}</title>
	<script type="text/javascript" src="../../tiny_mce_popup.js"></script>
	<script type="text/javascript" src="../../utils/mctabs.js"></script>
	<script type="text/javascript" src="../../utils/form_utils.js"></script>
	<script type="text/javascript" src="../../utils/validate.js"></script>
	<script type="text/javascript" src="../../utils/editable_selects.js"></script>
	<script type="text/javascript" src="js/image.js"></script>
	<link href="css/advimage.css" rel="stylesheet" type="text/css" />
</head>
<body id="imageCMIS" style="display: none">
    <form enctype="multipart/form-data" method="post" onsubmit="if (document.getElementById('mceUploadPanel').style.display=='none') { ImageDialog.insert();return false; } else { return true; }" action="#">
		<div class="tabs">
			<ul>
				<li id="general_tab" class="current"><span><a href="javascript:mcTabs.displayTab('general_tab','general_panel');" onmousedown="document.getElementById('mceUploadPanel').style.display='none';document.getElementById('mceActionPanel').style.display='';return false;">{#advimage_dlg.tab_general}</a></span></li>
				<li id="appearance_tab"><span><a href="javascript:mcTabs.displayTab('appearance_tab','appearance_panel');" onmousedown="document.getElementById('mceUploadPanel').style.display='none';document.getElementById('mceActionPanel').style.display='';return false;">{#advimage_dlg.tab_appearance}</a></span></li>
				<li id="advanced_tab"><span><a href="javascript:mcTabs.displayTab('advanced_tab','advanced_panel');" onmousedown="document.getElementById('mceUploadPanel').style.display='none';document.getElementById('mceActionPanel').style.display='';return false;">{#advimage_dlg.tab_advanced}</a></span></li>
				<li id="upload_tab"><span><a href="javascript:mcTabs.displayTab('upload_tab','upload_panel');" onmousedown="document.getElementById('mceUploadPanel').style.display='';document.getElementById('mceActionPanel').style.display='none';return false;">{#advimage_dlg.tab_upload}</a></span></li>
			</ul>
		</div>

		<div class="panel_wrapper">
			<div id="general_panel" class="panel current">
				<fieldset>
						<legend>{#advimage_dlg.general}</legend>

						<table class="properties">
							<tr>
								<td class="column1"><label id="srclabel" for="src">{#advimage_dlg.src}</label></td>
								<td colspan="2"><table border="0" cellspacing="0" cellpadding="0">
									<tr>
									  <td><input name="src" type="text" id="src" class="mceFocus" onchange="ImageDialog.showPreviewImage(this.value);" value="<?= !empty($imageFile)?$imageFile:"" ?>" /></td>
									  <td id="srcbrowsercontainer">&nbsp;</td>
									</tr>
								  </table></td>
							</tr>
							<tr>
								<td><label for="src_list">{#advimage_dlg.image_list}</label></td>
								<td><select id="src_list" name="src_list" onchange="document.getElementById('src').value=this.options[this.selectedIndex].value;document.getElementById('alt').value=this.options[this.selectedIndex].text;document.getElementById('title').value=this.options[this.selectedIndex].text;ImageDialog.showPreviewImage(this.options[this.selectedIndex].value);"><option value=""></option></select></td>
							</tr>
							<tr>
								<td class="column1"><label id="altlabel" for="alt">{#advimage_dlg.alt}</label></td>
								<td colspan="2"><input id="alt" name="alt" type="text" value="<?= !empty($imageText)?$imageText:"" ?>" /></td>
							</tr>
							<tr>
								<td class="column1"><label id="titlelabel" for="title">{#advimage_dlg.title}</label></td>
								<td colspan="2"><input id="title" name="title" type="text" value="<?= !empty($imageText)?$imageText:"" ?>" /></td>
							</tr>
						</table>
				</fieldset>

				<fieldset>
					<legend>{#advimage_dlg.preview}</legend>
					<div id="prev"></div>
				</fieldset>
			</div>

			<div id="appearance_panel" class="panel">
				<fieldset>
					<legend>{#advimage_dlg.tab_appearance}</legend>

					<table border="0" cellpadding="4" cellspacing="0">
						<tr>
							<td class="column1"><label id="alignlabel" for="align">{#advimage_dlg.align}</label></td>
							<td><select id="align" name="align" onchange="ImageDialog.updateStyle('align');ImageDialog.changeAppearance();">
									<option value="">{#not_set}</option>
									<option value="baseline">{#advimage_dlg.align_baseline}</option>
									<option value="top">{#advimage_dlg.align_top}</option>
									<option value="middle">{#advimage_dlg.align_middle}</option>
									<option value="bottom">{#advimage_dlg.align_bottom}</option>
									<option value="text-top">{#advimage_dlg.align_texttop}</option>
									<option value="text-bottom">{#advimage_dlg.align_textbottom}</option>
									<option value="left">{#advimage_dlg.align_left}</option>
									<option value="right">{#advimage_dlg.align_right}</option>
								</select>
							</td>
							<td rowspan="6" valign="top">
								<div class="alignPreview">
									<img id="alignSampleImg" src="img/sample.gif" alt="{#advimage_dlg.example_img}" />
									Lorem ipsum, Dolor sit amet, consectetuer adipiscing loreum ipsum edipiscing elit, sed diam
									nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.Loreum ipsum
									edipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam
									erat volutpat.
								</div>
							</td>
						</tr>

						<tr>
							<td class="column1"><label id="widthlabel" for="width">{#advimage_dlg.dimensions}</label></td>
							<td class="nowrap">
								<input name="width" type="text" id="width" value="" size="5" maxlength="5" class="size" onchange="ImageDialog.changeHeight();" /> x
								<input name="height" type="text" id="height" value="" size="5" maxlength="5" class="size" onchange="ImageDialog.changeWidth();" /> px
							</td>
						</tr>

						<tr>
							<td>&nbsp;</td>
							<td><table border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td><input id="constrain" type="checkbox" name="constrain" class="checkbox" /></td>
										<td><label id="constrainlabel" for="constrain">{#advimage_dlg.constrain_proportions}</label></td>
									</tr>
								</table></td>
						</tr>

						<tr>
							<td class="column1"><label id="vspacelabel" for="vspace">{#advimage_dlg.vspace}</label></td>
							<td><input name="vspace" type="text" id="vspace" value="" size="3" maxlength="3" class="number" onchange="ImageDialog.updateStyle('vspace');ImageDialog.changeAppearance();" onblur="ImageDialog.updateStyle('vspace');ImageDialog.changeAppearance();" />
							</td>
						</tr>

						<tr>
							<td class="column1"><label id="hspacelabel" for="hspace">{#advimage_dlg.hspace}</label></td>
							<td><input name="hspace" type="text" id="hspace" value="" size="3" maxlength="3" class="number" onchange="ImageDialog.updateStyle('hspace');ImageDialog.changeAppearance();" onblur="ImageDialog.updateStyle('hspace');ImageDialog.changeAppearance();" /></td>
						</tr>

						<tr>
							<td class="column1"><label id="borderlabel" for="border">{#advimage_dlg.border}</label></td>
							<td><input id="border" name="border" type="text" value="" size="3" maxlength="3" class="number" onchange="ImageDialog.updateStyle('border');ImageDialog.changeAppearance();" onblur="ImageDialog.updateStyle('border');ImageDialog.changeAppearance();" /></td>
						</tr>

						<tr>
							<td><label for="class_list">{#class_name}</label></td>
							<td colspan="2"><select id="class_list" name="class_list" class="mceEditableSelect"><option value=""></option></select></td>
						</tr>

						<tr>
							<td class="column1"><label id="stylelabel" for="style">{#advimage_dlg.style}</label></td>
							<td colspan="2"><input id="style" name="style" type="text" value="" onchange="ImageDialog.changeAppearance();" /></td>
						</tr>

						<!-- <tr>
							<td class="column1"><label id="classeslabel" for="classes">{#advimage_dlg.classes}</label></td>
							<td colspan="2"><input id="classes" name="classes" type="text" value="" onchange="selectByValue(this.form,'classlist',this.value,true);" /></td>
						</tr> -->
					</table>
				</fieldset>
			</div>

			<div id="advanced_panel" class="panel">
				<fieldset>
					<legend>{#advimage_dlg.swap_image}</legend>

					<input type="checkbox" id="onmousemovecheck" name="onmousemovecheck" class="checkbox" onclick="ImageDialog.setSwapImage(this.checked);" />
					<label id="onmousemovechecklabel" for="onmousemovecheck">{#advimage_dlg.alt_image}</label>

					<table border="0" cellpadding="4" cellspacing="0" width="100%">
							<tr>
								<td class="column1"><label id="onmouseoversrclabel" for="onmouseoversrc">{#advimage_dlg.mouseover}</label></td>
								<td><table border="0" cellspacing="0" cellpadding="0">
									<tr>
									  <td><input id="onmouseoversrc" name="onmouseoversrc" type="text" value="" /></td>
									  <td id="onmouseoversrccontainer">&nbsp;</td>
									</tr>
								  </table></td>
							</tr>
							<tr>
								<td><label for="over_list">{#advimage_dlg.image_list}</label></td>
								<td><select id="over_list" name="over_list" onchange="document.getElementById('onmouseoversrc').value=this.options[this.selectedIndex].value;"><option value=""></option></select></td>
							</tr>
							<tr>
								<td class="column1"><label id="onmouseoutsrclabel" for="onmouseoutsrc">{#advimage_dlg.mouseout}</label></td>
								<td class="column2"><table border="0" cellspacing="0" cellpadding="0">
									<tr>
									  <td><input id="onmouseoutsrc" name="onmouseoutsrc" type="text" value="" /></td>
									  <td id="onmouseoutsrccontainer">&nbsp;</td>
									</tr>
								  </table></td>
							</tr>
							<tr>
								<td><label for="out_list">{#advimage_dlg.image_list}</label></td>
								<td><select id="out_list" name="out_list" onchange="document.getElementById('onmouseoutsrc').value=this.options[this.selectedIndex].value;"><option value=""></option></select></td>
							</tr>
					</table>
				</fieldset>

				<fieldset>
					<legend>{#advimage_dlg.misc}</legend>

					<table border="0" cellpadding="4" cellspacing="0">
						<tr>
							<td class="column1"><label id="idlabel" for="id">{#advimage_dlg.id}</label></td>
							<td><input id="id" name="id" type="text" value="" /></td>
						</tr>

						<tr>
							<td class="column1"><label id="dirlabel" for="dir">{#advimage_dlg.langdir}</label></td>
							<td>
								<select id="dir" name="dir" onchange="ImageDialog.changeAppearance();">
										<option value="">{#not_set}</option>
										<option value="ltr">{#advimage_dlg.ltr}</option>
										<option value="rtl">{#advimage_dlg.rtl}</option>
								</select>
							</td>
						</tr>

						<tr>
							<td class="column1"><label id="langlabel" for="lang">{#advimage_dlg.langcode}</label></td>
							<td>
								<input id="lang" name="lang" type="text" value="" />
							</td>
						</tr>

						<tr>
							<td class="column1"><label id="usemaplabel" for="usemap">{#advimage_dlg.map}</label></td>
							<td>
								<input id="usemap" name="usemap" type="text" value="" />
							</td>
						</tr>

						<tr>
							<td class="column1"><label id="longdesclabel" for="longdesc">{#advimage_dlg.long_desc}</label></td>
							<td><table border="0" cellspacing="0" cellpadding="0">
									<tr>
									  <td><input id="longdesc" name="longdesc" type="text" value="" /></td>
									  <td id="longdesccontainer">&nbsp;</td>
									</tr>
								</table></td>
						</tr>
					</table>
				</fieldset>
			</div>

			<div id="upload_panel" class="panel">
				<fieldset>
					<legend>{#advimage_dlg.new_image}</legend>

					<table class="properties">
						<tr>
						  	<td class="column1">{#advimage_dlg.choose_file}</td>
						  	<td>
							  <input type="hidden" name="type" value="1" />
							  <input name="file" type="file" id="upload" value="" />
						  	</td>
						</tr>

						<tr>
							<td class="column1">{#advimage_dlg.image_description}</td>
							<td><input type="text" name="uploadImageDescription" value="" /></td>
						</tr>

						<?
						global $login;
						if ($login->isWebmaster()) {
						?>
						<tr>
							<td class="column1">{#advimage_dlg.image_thumbnail}</td>
							<td><input type="text" name="uploadImageThumbnailWidth" value="" /></td>
						</tr>

						<tr>
							<td class="column1">{#advimage_dlg.image_thumbnail_link}</td>
							<td><input type="checkbox" name="uploadImageThumbnailLink" value="1" checked="checked" /></td>
						</tr>

						<tr>
							<td class="column1">{#advimage_dlg.target_folder}</td>
						  	<td>
							  <select name="uploadTargetFolder" id="folder">
								<option value="0"<?= pageUploadFolder==0?" selected=\"selected\"":"" ?>>/</option>
								<?
								function printFolderOptions($id=0,$level=1) {
									global $dbi;
									$result = $dbi->query("SELECT id,name FROM ".folderTableName." WHERE parentId=$id ORDER BY name");
									if($result->rows()) {
										for($i=0;(list($id,$name) = $result->fetchrow_array());$i++) {
											echo "<option value=\"$id\"".(pageUploadFolder==$id?" selected=\"selected\"":"").">";
											for($j=0;$j<$level;$j++) {
												echo "-";
											}
											echo " $name</option>";
											printFolderOptions($id,$level+1);
										}
									}
								}
								printFolderOptions();
								?>
							</select>
						  </td>
						</tr>
						<? } ?>
					</table>
				</fieldset>

				<? if ($login->isWebmaster()) { ?>
				<fieldset>
					<legend>{#advimage_dlg.use_existing}</legend>
					<input id="fileName" type="hidden" name="fileName" value="" />
					<input id="fileUrl" type="hidden" name="fileUrl" value="" />
					<iframe src="<?= scriptUrl."/".folderFiles."/".fileFilesBrowse."?fileTypes=images" ?>" frameborder="0" marginheight="0" marginwidth="0" width="100%" height="165" style="border:1px #000000 solid"></iframe>
				</fieldset>
				<? } ?>
			</div>
		</div>

		<div id="mceUploadPanel" class="mceActionPanel" style="display:none">
			<input type="submit" id="insert" name="upload" value="{#advimage_dlg.upload}" />
			<input type="button" id="cancel" name="cancel" value="{#cancel}" onclick="tinyMCEPopup.close();" />
		</div>

		<div id="mceActionPanel" class="mceActionPanel">
			<input type="submit" id="insert" name="insert" value="{#insert}" />
			<input type="button" id="cancel" name="cancel" value="{#cancel}" onclick="tinyMCEPopup.close();" />
		</div>
    </form>
</body>
</html>
