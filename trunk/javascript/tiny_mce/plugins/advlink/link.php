<?
// Include common functions and declarations
require_once "../../../../include/common.php";

// Check if user
if(!$login->isUser()) {
	$login->printLoginForm();
	exit();
}

// Initialize variables
$file = "";
$fileText = "";

// Upload file
if(!empty($_FILES["file"]["name"])) {
	$extension = convertToLowercase(substr(strrchr($_FILES["file"]["name"],"."), 1));

	// Insert into file database
	$dbi->query("INSERT INTO ".fileTableName."(folderId,name,type,size) VALUES('".pageUploadFolder."',".$dbi->quote($_FILES["file"]["name"]).",".$dbi->quote($_FILES["file"]["type"]).",".$dbi->quote($_FILES["file"]["size"]).")");
	$id = $dbi->getInsertId();

	if (!empty($id)) {
		// Move uploaded file
		move_uploaded_file($_FILES["file"]["tmp_name"], filePath."/".$id.".".$extension);

		// Save file name
		$file = scriptUrl."/".folderFiles."/".fileFilesGetFile."?fileId=$id";
		$fileText = !empty($_POST["uploadFileDescription"])?$_POST["uploadFileDescription"]:$_FILES["file"]["name"];
	}
}
else if (!empty($_POST["fileUrl"])) {
	$file = $_POST["fileUrl"];
	$fileText = !empty($_POST["uploadFileDescription"])?$_POST["uploadFIleDescription"]:(!empty($_POST["fileName"])?$_POST["fileName"]:"");		
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>{#advlink_dlg.title}</title>
	<script type="text/javascript" src="../../tiny_mce_popup.js"></script>
	<script type="text/javascript" src="../../utils/mctabs.js"></script>
	<script type="text/javascript" src="../../utils/form_utils.js"></script>
	<script type="text/javascript" src="../../utils/validate.js"></script>
	<script type="text/javascript" src="js/advlink.js"></script>
	<link href="css/advlink.css" rel="stylesheet" type="text/css" />
</head>
<body id="advlink" style="display: none">
    <form action="#" method="post" enctype="multipart/form-data" onsubmit="if (document.getElementById('mceUploadPanel').style.display=='none') { insertAction(); return false; } else { return true; }">
		<div class="tabs">
			<ul>
				<li id="general_tab" class="current"><span><a href="javascript:mcTabs.displayTab('general_tab','general_panel');" onmousedown="document.getElementById('mceUploadPanel').style.display='none';document.getElementById('mceActionPanel').style.display='';return false;">{#advlink_dlg.general_tab}</a></span></li>
				<li id="popup_tab"><span><a href="javascript:mcTabs.displayTab('popup_tab','popup_panel');" onmousedown="document.getElementById('mceUploadPanel').style.display='none';document.getElementById('mceActionPanel').style.display='';return false;">{#advlink_dlg.popup_tab}</a></span></li>
				<li id="events_tab"><span><a href="javascript:mcTabs.displayTab('events_tab','events_panel');" onmousedown="document.getElementById('mceUploadPanel').style.display='none';document.getElementById('mceActionPanel').style.display='';return false;">{#advlink_dlg.events_tab}</a></span></li>
				<li id="advanced_tab"><span><a href="javascript:mcTabs.displayTab('advanced_tab','advanced_panel');" onmousedown="document.getElementById('mceUploadPanel').style.display='none';document.getElementById('mceActionPanel').style.display='';return false;">{#advlink_dlg.advanced_tab}</a></span></li>
				<li id="upload_tab"><span><a href="javascript:mcTabs.displayTab('upload_tab','upload_panel');" onmousedown="document.getElementById('mceUploadPanel').style.display='';document.getElementById('mceActionPanel').style.display='none';return false;">{#advlink_dlg.upload_tab}</a></span></li>
			</ul>
		</div>

		<div class="panel_wrapper">
			<div id="general_panel" class="panel current">
				<fieldset>
					<legend>{#advlink_dlg.general_props}</legend>

					<table border="0" cellpadding="4" cellspacing="0">
						<tr>
						  <td class="nowrap"><label id="hreflabel" for="href">{#advlink_dlg.url}</label></td>
						  <td><table border="0" cellspacing="0" cellpadding="0">
								<tr>
								  <td><input id="href" name="href" type="text" class="mceFocus" value="<?= !empty($file)?$file:"" ?>" onchange="selectByValue(this.form,'linklisthref',this.value);" /></td>
								  <td id="hrefbrowsercontainer">&nbsp;</td>
								</tr>
							  </table></td>
						</tr>
						<tr id="linklisthrefrow">
							<td class="column1"><label for="linklisthref">{#advlink_dlg.list}</label></td>
							<td colspan="2" id="linklisthrefcontainer"><select id="linklisthref"><option value=""></option></select></td>
						</tr>
						<tr>
							<td class="column1"><label for="sectionlist">{#advlink_dlg.pages}</label></td>
							<td colspan="2" id="sectionlistcontainer">
							<select name="section" id="sectionlist" class="mceList" onchange="document.getElementById('href').value=this.options[this.selectedIndex].value">
							<option value="">---</option>
							<?
							function printNavigationOption($id=0,$dashes=0) {
								global $dbi;

								$result = $dbi->query("SELECT id FROM ".pageTableName." WHERE parentId=".$dbi->quote($id)." AND id!=0 ORDER BY position");
								for($i=0;(list($id2)=$result->fetchrow_array());$i++) {
									$page = new Page($id2);
									echo "<option value=\"".$page->getPageLink()."\">";				
									for($j=0;$j<$dashes;$j++) {
										echo "- ";
									}
									echo $page->title;
									echo "</option>";

									if(!empty($id2)) printNavigationOption($id2,$dashes+1);
									$j = 0;	
								}
							}

							printNavigationOption();
							?>
							</select>
							</td>
						</tr>						
						<tr>
							<td class="column1"><label for="anchorlist">{#advlink_dlg.anchor_names}</label></td>
							<td colspan="2" id="anchorlistcontainer"><select id="anchorlist"><option value=""></option></select></td>
						</tr>
						<tr>
							<td><label id="targetlistlabel" for="targetlist">{#advlink_dlg.target}</label></td>
							<td id="targetlistcontainer"><select id="targetlist"><option value=""></option></select></td>
						</tr>
						<tr>
							<td class="nowrap"><label id="titlelabel" for="title">{#advlink_dlg.titlefield}</label></td>
							<td><input id="title" name="title" type="text" value="" /></td>
						</tr>
						<tr>
							<td><label id="classlabel" for="classlist">{#class_name}</label></td>
							<td>
								 <select id="classlist" name="classlist" onchange="changeClass();">
									<option value="" selected="selected">{#not_set}</option>
								 </select>
							</td>
						</tr>
					</table>
				</fieldset>
			</div>

			<div id="popup_panel" class="panel">
				<fieldset>
					<legend>{#advlink_dlg.popup_props}</legend>

					<input type="checkbox" id="ispopup" name="ispopup" class="radio" onclick="setPopupControlsDisabled(!this.checked);buildOnClick();" />
					<label id="ispopuplabel" for="ispopup">{#advlink_dlg.popup}</label>

					<table border="0" cellpadding="0" cellspacing="4">
						<tr>
							<td class="nowrap"><label for="popupurl">{#advlink_dlg.popup_url}</label>&nbsp;</td>
							<td>
								<table border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td><input type="text" name="popupurl" id="popupurl" value="" onchange="buildOnClick();" /></td>
										<td id="popupurlbrowsercontainer">&nbsp;</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td class="nowrap"><label for="popupname">{#advlink_dlg.popup_name}</label>&nbsp;</td>
							<td><input type="text" name="popupname" id="popupname" value="" onchange="buildOnClick();" /></td>
						</tr>
						<tr>
							<td class="nowrap"><label>{#advlink_dlg.popup_size}</label>&nbsp;</td>
							<td class="nowrap">
								<input type="text" id="popupwidth" name="popupwidth" value="" onchange="buildOnClick();" /> x
								<input type="text" id="popupheight" name="popupheight" value="" onchange="buildOnClick();" /> px
							</td>
						</tr>
						<tr>
							<td class="nowrap" id="labelleft"><label>{#advlink_dlg.popup_position}</label>&nbsp;</td>
							<td class="nowrap">
								<input type="text" id="popupleft" name="popupleft" value="" onchange="buildOnClick();" /> /                                
								<input type="text" id="popuptop" name="popuptop" value="" onchange="buildOnClick();" /> (c /c = center)
							</td>
						</tr>
					</table>

					<fieldset>
						<legend>{#advlink_dlg.popup_opts}</legend>

						<table border="0" cellpadding="0" cellspacing="4">
							<tr>
								<td><input type="checkbox" id="popuplocation" name="popuplocation" class="checkbox" onchange="buildOnClick();" /></td>
								<td class="nowrap"><label id="popuplocationlabel" for="popuplocation">{#advlink_dlg.popup_location}</label></td>
								<td><input type="checkbox" id="popupscrollbars" name="popupscrollbars" class="checkbox" onchange="buildOnClick();" /></td>
								<td class="nowrap"><label id="popupscrollbarslabel" for="popupscrollbars">{#advlink_dlg.popup_scrollbars}</label></td>
							</tr>
							<tr>
								<td><input type="checkbox" id="popupmenubar" name="popupmenubar" class="checkbox" onchange="buildOnClick();" /></td>
								<td class="nowrap"><label id="popupmenubarlabel" for="popupmenubar">{#advlink_dlg.popup_menubar}</label></td>
								<td><input type="checkbox" id="popupresizable" name="popupresizable" class="checkbox" onchange="buildOnClick();" /></td>
								<td class="nowrap"><label id="popupresizablelabel" for="popupresizable">{#advlink_dlg.popup_resizable}</label></td>
							</tr>
							<tr>
								<td><input type="checkbox" id="popuptoolbar" name="popuptoolbar" class="checkbox" onchange="buildOnClick();" /></td>
								<td class="nowrap"><label id="popuptoolbarlabel" for="popuptoolbar">{#advlink_dlg.popup_toolbar}</label></td>
								<td><input type="checkbox" id="popupdependent" name="popupdependent" class="checkbox" onchange="buildOnClick();" /></td>
								<td class="nowrap"><label id="popupdependentlabel" for="popupdependent">{#advlink_dlg.popup_dependent}</label></td>
							</tr>
							<tr>
								<td><input type="checkbox" id="popupstatus" name="popupstatus" class="checkbox" onchange="buildOnClick();" /></td>
								<td class="nowrap"><label id="popupstatuslabel" for="popupstatus">{#advlink_dlg.popup_statusbar}</label></td>
								<td><input type="checkbox" id="popupreturn" name="popupreturn" class="checkbox" onchange="buildOnClick();" checked="checked" /></td>
								<td class="nowrap"><label id="popupreturnlabel" for="popupreturn">{#advlink_dlg.popup_return}</label></td>
							</tr>
						</table>
					</fieldset>
				</fieldset>
			</div>

			<div id="advanced_panel" class="panel">
			<fieldset>
					<legend>{#advlink_dlg.advanced_props}</legend>

					<table border="0" cellpadding="0" cellspacing="4">
						<tr>
							<td class="column1"><label id="idlabel" for="id">{#advlink_dlg.id}</label></td> 
							<td><input id="id" name="id" type="text" value="" /></td> 
						</tr>

						<tr>
							<td><label id="stylelabel" for="style">{#advlink_dlg.style}</label></td>
							<td><input type="text" id="style" name="style" value="" /></td>
						</tr>

						<tr>
							<td><label id="classeslabel" for="classes">{#advlink_dlg.classes}</label></td>
							<td><input type="text" id="classes" name="classes" value="" onchange="selectByValue(this.form,'classlist',this.value,true);" /></td>
						</tr>

						<tr>
							<td><label id="targetlabel" for="target">{#advlink_dlg.target_name}</label></td>
							<td><input type="text" id="target" name="target" value="" onchange="selectByValue(this.form,'targetlist',this.value,true);" /></td>
						</tr>

						<tr>
							<td class="column1"><label id="dirlabel" for="dir">{#advlink_dlg.langdir}</label></td> 
							<td>
								<select id="dir" name="dir"> 
										<option value="">{#not_set}</option> 
										<option value="ltr">{#advlink_dlg.ltr}</option> 
										<option value="rtl">{#advlink_dlg.rtl}</option> 
								</select>
							</td> 
						</tr>

						<tr>
							<td><label id="hreflanglabel" for="hreflang">{#advlink_dlg.target_langcode}</label></td>
							<td><input type="text" id="hreflang" name="hreflang" value="" /></td>
						</tr>

						<tr>
							<td class="column1"><label id="langlabel" for="lang">{#advlink_dlg.langcode}</label></td> 
							<td>
								<input id="lang" name="lang" type="text" value="" />
							</td> 
						</tr>

						<tr>
							<td><label id="charsetlabel" for="charset">{#advlink_dlg.encoding}</label></td>
							<td><input type="text" id="charset" name="charset" value="" /></td>
						</tr>

						<tr>
							<td><label id="typelabel" for="type">{#advlink_dlg.mime}</label></td>
							<td><input type="text" id="type" name="type" value="" /></td>
						</tr>

						<tr>
							<td><label id="rellabel" for="rel">{#advlink_dlg.rel}</label></td>
							<td><select id="rel" name="rel"> 
									<option value="">{#not_set}</option> 
									<option value="lightbox">Lightbox</option> 
									<option value="alternate">Alternate</option> 
									<option value="designates">Designates</option> 
									<option value="stylesheet">Stylesheet</option> 
									<option value="start">Start</option> 
									<option value="next">Next</option> 
									<option value="prev">Prev</option> 
									<option value="contents">Contents</option> 
									<option value="index">Index</option> 
									<option value="glossary">Glossary</option> 
									<option value="copyright">Copyright</option> 
									<option value="chapter">Chapter</option> 
									<option value="subsection">Subsection</option> 
									<option value="appendix">Appendix</option> 
									<option value="help">Help</option> 
									<option value="bookmark">Bookmark</option>
									<option value="nofollow">No Follow</option>
									<option value="tag">Tag</option>
								</select> 
							</td>
						</tr>

						<tr>
							<td><label id="revlabel" for="rev">{#advlink_dlg.rev}</label></td>
							<td><select id="rev" name="rev"> 
									<option value="">{#not_set}</option> 
									<option value="alternate">Alternate</option> 
									<option value="designates">Designates</option> 
									<option value="stylesheet">Stylesheet</option> 
									<option value="start">Start</option> 
									<option value="next">Next</option> 
									<option value="prev">Prev</option> 
									<option value="contents">Contents</option> 
									<option value="index">Index</option> 
									<option value="glossary">Glossary</option> 
									<option value="copyright">Copyright</option> 
									<option value="chapter">Chapter</option> 
									<option value="subsection">Subsection</option> 
									<option value="appendix">Appendix</option> 
									<option value="help">Help</option> 
									<option value="bookmark">Bookmark</option> 
								</select> 
							</td>
						</tr>

						<tr>
							<td><label id="tabindexlabel" for="tabindex">{#advlink_dlg.tabindex}</label></td>
							<td><input type="text" id="tabindex" name="tabindex" value="" /></td>
						</tr>

						<tr>
							<td><label id="accesskeylabel" for="accesskey">{#advlink_dlg.accesskey}</label></td>
							<td><input type="text" id="accesskey" name="accesskey" value="" /></td>
						</tr>
					</table>
				</fieldset>
			</div>

			<div id="events_panel" class="panel">
			<fieldset>
					<legend>{#advlink_dlg.event_props}</legend>

					<table border="0" cellpadding="0" cellspacing="4">
						<tr>
							<td class="column1"><label for="onfocus">onfocus</label></td> 
							<td><input id="onfocus" name="onfocus" type="text" value="" /></td> 
						</tr>

						<tr>
							<td class="column1"><label for="onblur">onblur</label></td> 
							<td><input id="onblur" name="onblur" type="text" value="" /></td> 
						</tr>

						<tr>
							<td class="column1"><label for="onclick">onclick</label></td> 
							<td><input id="onclick" name="onclick" type="text" value="" /></td> 
						</tr>

						<tr>
							<td class="column1"><label for="ondblclick">ondblclick</label></td> 
							<td><input id="ondblclick" name="ondblclick" type="text" value="" /></td> 
						</tr>

						<tr>
							<td class="column1"><label for="onmousedown">onmousedown</label></td> 
							<td><input id="onmousedown" name="onmousedown" type="text" value="" /></td> 
						</tr>

						<tr>
							<td class="column1"><label for="onmouseup">onmouseup</label></td> 
							<td><input id="onmouseup" name="onmouseup" type="text" value="" /></td> 
						</tr>

						<tr>
							<td class="column1"><label for="onmouseover">onmouseover</label></td> 
							<td><input id="onmouseover" name="onmouseover" type="text" value="" /></td> 
						</tr>

						<tr>
							<td class="column1"><label for="onmousemove">onmousemove</label></td> 
							<td><input id="onmousemove" name="onmousemove" type="text" value="" /></td> 
						</tr>

						<tr>
							<td class="column1"><label for="onmouseout">onmouseout</label></td> 
							<td><input id="onmouseout" name="onmouseout" type="text" value="" /></td> 
						</tr>

						<tr>
							<td class="column1"><label for="onkeypress">onkeypress</label></td> 
							<td><input id="onkeypress" name="onkeypress" type="text" value="" /></td> 
						</tr>

						<tr>
							<td class="column1"><label for="onkeydown">onkeydown</label></td> 
							<td><input id="onkeydown" name="onkeydown" type="text" value="" /></td> 
						</tr>

						<tr>
							<td class="column1"><label for="onkeyup">onkeyup</label></td> 
							<td><input id="onkeyup" name="onkeyup" type="text" value="" /></td> 
						</tr>
					</table>
				</fieldset>
			</div>
			
			<div id="upload_panel" class="panel">
				<fieldset>
					<legend>{#advlink_dlg.new_file}</legend>

					<table class="properties">
						<tr>
						  <td class="column1">{#advlink_dlg.choose_file}</td>
						  <td>
							  <input type="hidden" name="type" value="1" /> 
							  <input name="file" type="file" id="upload" value="" />
						  </td>
						  </tr>
					  
							<tr>
								<td class="column1">{#advlink_dlg.description}</td>
								<td><input id="description" class="mceList" type="text" name="uploadFileDescription" value="" /></td>
							</tr>
					  
						  <tr>
						  <td class="column1">{#advlink_dlg.target_folder}</td>							  
						  <td>
							  <select name="uploadTargetFolder" class="mceList" id="destination">
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
					</table>
				</fieldset>
			
				<fieldset>
					<legend>{#advlink_dlg.use_existing}</legend>
					<input id="fileName" type="hidden" name="fileName" value="" />
					<input id="fileUrl" type="hidden" name="fileUrl" value="" />
					<iframe src="<?= scriptUrl."/".folderFiles."/".fileFilesBrowse ?>" frameborder="0" marginheight="0" marginwidth="0" width="100%" height="185" style="border:1px #000000 solid"></iframe>
				</fieldset>			
			</div>			
		</div>

		<div id="mceUploadPanel" class="mceActionPanel" style="display:none">
			<input type="submit" id="insert" name="upload" value="{#advlink_dlg.upload}" />
			<input type="button" id="cancel" name="cancel" value="{#cancel}" onclick="tinyMCEPopup.close();" />
		</div>

		<div id="mceActionPanel" class="mceActionPanel">
			<input type="submit" id="insert" name="insert" value="{#insert}" />
			<input type="button" id="cancel" name="cancel" value="{#cancel}" onclick="tinyMCEPopup.close();" />
		</div>
    </form>
</body>
</html>
