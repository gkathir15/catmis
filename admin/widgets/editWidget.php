<?
include "../include/common.php";

if (!$login->isAdmin()) {
	$login->printLoginForm();
	exit();
}

$id = !empty($_GET["id"])?$_GET["id"]:0;

if (!empty($_POST["deleteBox"])) {
	if (!empty($id)) {		
		$dbi->query("DELETE FROM ".frontpageTableName." WHERE id=".$dbi->quote($id));
		redirect(scriptUrl);
	}
}
if (!empty($_GET["save"])) {
	$title = getValue("title");
	$text = getValue("text");
	$pos = strpos($text,"<p>");
	$text = substr_replace($text, "", $pos, 3);
	$pos = strpos($text,"</p>");
	$text = substr_replace($text, "", $pos, 4);
	$text = str_replace("<p>","<p class=\"smallMargin\">",$text);
	$text = trim($text);
	$typeId = getValue("typeId");
	$visible = getValue("visible");
	
	if (!empty($id)) {
		$dbi->query("UPDATE ".frontpageTableName." SET Title=".$dbi->quote($title).",Text=".$dbi->quote($text).",TypeId=".$dbi->quote($typeId).",Visible=".$dbi->quote($visible)." WHERE Id=".$dbi->quote($id));
	}
	else {
		$position = 1;
		$result = $dbi->query("SELECT MAX(Position) FROM ".frontpageTableName);
		if ($result->rows()) {
			list($position) = $result->fetchrow_array();	
			$position++;
		}
		$dbi->query("INSERT INTO ".frontpageTableName."(Title,Text,TypeId,Visible,Position) VALUES(".$dbi->quote($title).",".$dbi->quote($text).",".$dbi->quote($typeId).",".$dbi->quote($visible).",".$dbi->quote($position).")");	
	}
	redirect(scriptUrl);
}
else if (!empty($id)) {	
	$result = $dbi->query("SELECT Title,Text,TypeId,Visible FROM ".frontpageTableName." WHERE Id=".$dbi->quote($id));
	if ($result->rows()) {
		list($title,$text,$typeId,$visible) = $result->fetchrow_array();
	}
}
else {
	$title = "";
	$text = "";
	$typeId = 0;
	$visible = 1;
}

printHeader(!empty($id)?"Rediger boks":"Opret boks", "", array(), true);
?>

<script language="JavaScript" type="text/javascript">
<!--
function validate(form){	
	if(form.deleteBox.checked) {		
		var agree=confirm('Er du sikker på du vil slette denne boks?');		
		if(agree) return true ;		
		else return false ;	
	}
	else {
		return true;
	}
}
-->
</script>


<form name="frontpage" action="<?= scriptUrl ?>/forside/editBox.php?<?= !empty($id)?"id=".$id."&amp;":"" ?>save=1" method="post" onsubmit="return validate(this)">
Titel<br />
<input type="text" name="title" value="<?= $title ?>" class="shortInput" />

<p>Type<br />
<select name="typeId" class="shortInput">
<option value="0">Fritekst</option>
<option value="0">-</option>
<?
$result = $dbi->query("SELECT Id,Name FROM ".frontpageTypeTableName." ORDER BY Name");
if ($result->rows()) {
	for ($i=0; list($typeId2,$typeName)=$result->fetchrow_array(); $i++) {
		echo '<option value="'.$typeId2.'"'.($typeId==$typeId2?' selected="selected"':'').'>'.$typeName.'</option>';	
	}
}
?>
</select></p>

<p>Tekst<br />
<? printRichTextArea("document.frontpage","text",$text,10,40,0,3) ?></p>

<p><input type="checkbox" name="visible" value="1"<?= $visible?' checked="checked"':'' ?> /> Vis på forsiden</p>

<? if (!empty($id)) { ?><p><input type="checkbox" name="deleteBox" value="1" onchange="if(this.checked==1) { saveBox.value='Slet boks' } else { saveBox.value='Gem boks' }" /> Slet boks</p><? } ?>

<p><input name="saveBox" type="submit" value="Gem boks" /></p>
</form>

<?
printFooter();
?>