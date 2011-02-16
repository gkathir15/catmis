<? 
global $tinyLiteIncluded, $tinyUserIncluded, $tinyIncluded;
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
<? if (!$tinyUserIncluded) { ?>
<script language="javascript" type="text/javascript">
tinyMCE.init({
	auto_reset_designmode : true,
	mode : "textareas",

	content_css : "<?= layoutUrl ?>/css/format.css.php",
	editor_selector : "mceLiteEditor",
	language : "<?= pageLanguage ?>",
	plugins : "advimage,spellchecker,preview,paste,noneditable,inlinepopups,emotions,media",
	convert_urls : false,
	document_base_url : "<?= scriptUrl ?>/",
	relative_urls : false,
	remove_script_host : false,
	theme : "advanced",

	theme_advanced_buttons1 : "bold,italic,underline,separator,undo,redo,separator,bullist,numlist,separator,link,unlink,autosave,separator,image,media,separator,emotions,separator,spellchecker,separator,code",
	theme_advanced_buttons2: "",
	theme_advanced_buttons3: "",
	theme_advanced_toolbar_align : "left",
	theme_advanced_toolbar_location : "top",
	theme_advanced_resizing : true,

	paste_auto_cleanup_on_paste : true,
	paste_convert_headers_to_strong : true,
	paste_create_paragraphs : true,
	paste_create_linebreaks : true,
	paste_convert_middot_lists : true,
	paste_insert_word_content_callback : "removeParagraphs",
	paste_remove_spans : true,
	paste_remove_styles : true,
	paste_strip_class_attributes : "all",   
	paste_unindented_list_class : "unindentedList",       
	paste_use_dialog : false,

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
<textarea name="<?= $name ?>" rows="<?= $rows ?>" cols="<?= $cols ?>" tabindex="<?= $tabIndex ?>" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);" class="mceLiteEditor" style="width:100%"><?= $value ?></textarea>
