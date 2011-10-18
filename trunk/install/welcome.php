<?
// Include default header
include "include/header.php";
?>

<table cellspacing="0" cellpadding="0" style="width:50%;border:1px #666666 solid;padding:0px">
<tr>
<td>
<table cellspacing="0" cellpadding="0" style="width:100%;background-color:#ffffff">
<tr>
<td height="400" style="padding:20px;padding-top:5px;border-right:1px #cccccc solid;" align="left" valign="top">
<form action="index.php" method="post">	
<h1>Welcome to Catmis</h1>

<p>Please choose the language you want to use throughout the site.</p>

<p><input type="radio" name="language" value="da" /> Danish<br />
<input type="radio" name="language" value="en" checked="checked" /> English</p><br />

<p><input type="submit" value="Install Catmis" style="font-size:120%" /></p>
</form>
</td>
</tr>
</table>
</td>
</tr>
</table>

<?
// Include default footer
include "include/footer.php";
?>
