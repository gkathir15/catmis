<? 
global $tinyLiteIncluded, $tinyIncluded, $tinyUserIncluded;
if (!$tinyLiteIncluded && !$tinyUserIncluded && !$tinyIncluded) { 
?>
<script type="text/javascript" src="<?= scriptUrl ?>/javascript/tiny_mce/tiny_mce_gzip.js"></script>
<script type="text/javascript">
tinyMCE_GZ.init({
	plugins : 'style,layer,table,save,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,contextmenu,paste',
	themes : 'simple,advanced',
	languages : '<?= pageLanguage ?>',
	disk_cache : true,
	debug : false
});
</script>
<? } ?>
<? if (!$tinyIncluded) { ?>
<script language="javascript" type="text/javascript">
tinyMCE.init({
	auto_reset_designmode : true,
	mode : "textareas",

	content_css : "<?= layoutUrl ?>/css/format.css.php",
	editor_selector : "mceEditor",
	language : "<?= pageLanguage ?>",
	plugins : "spellchecker,style,table,advimage,advlink,contextmenu,paste,media,inlinepopups",
	convert_urls : false,
	document_base_url : "<?= scriptUrl ?>/",
	relative_urls : false,
	remove_script_host : false,
	theme : "advanced",

	theme_advanced_buttons1 : "formatselect,bold,italic,underline,separator,bullist,numlist,separator,undo,redo,separator,pastetext,pasteword,separator,anchor,link,unlink,separator,image,media,separator,spellchecker,separator,code,autosave",
	theme_advanced_buttons2: "",
	theme_advanced_buttons3: "",
	theme_advanced_path_location : "bottom",
	theme_advanced_resize_horizontal : false,
	theme_advanced_resizing : true,
	theme_advanced_toolbar_align : "left",
	theme_advanced_toolbar_location : "top",

	extended_valid_elements : "-p",
	extended_valid_elements : "pre[name|class],textarea[name|class|cols|rows],a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name|style],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style],form[action|method|onsubmit],input[type|value|name|style|class],select[name|class|style],option[value|selected],object[width|height|classid|codebase|data|type],param[name|value],embed[src|type|width|height|allowscriptaccess|allowfullscreen],iframe[src|width|height|frameborder|name|align|title|class|type]",
    remove_linebreaks : false,

	paste_auto_cleanup_on_paste : true,
	paste_use_dialog : false,
	paste_create_paragraphs : true,
	paste_create_linebreaks : true,
	paste_convert_middot_lists : true,
	paste_unindented_list_class : "unindentedList",
	paste_convert_headers_to_strong : true,
	paste_strip_class_attributes : "all",
	paste_remove_spans : true,
	paste_remove_styles : true,
	paste_insert_word_content_callback : "removeParagraphs",

	spellchecker_languages : "+Danish=da,English=en"
});

function removeParagraphs(type, content) {
	if (type == "before"){
		//content = content.replace(/(<SPAN style="mso-tab-count)/gi,'@tab@$1');
	}
	if (type="after") {
  		content = content.replace(/&nbsp;/gi,'');
	}
    return content;
}
</script>
<? } ?>
<textarea name="<?= $name ?>" rows="<?= $rows ?>" cols="<?= $cols ?>" tabindex="<?= $tabIndex ?>" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);" style="width:100%;" class="mceEditor"><?= $value ?></textarea>
