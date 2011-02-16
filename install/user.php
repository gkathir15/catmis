<?
// Include default header
include "../theme/layout/cmis/header.php";


?>

<table cellspacing="0" cellpadding="0" style="width:50%;border:1px #666666 solid;padding:0px">
<tr>
<td>
<table cellspacing="0" cellpadding="0" style="width:100%;background-color:#ffffff">
<tr>
<td height="400" style="padding:20px;padding-top:5px;border-right:1px #cccccc solid;" align="left" valign="top">	
<h1>CMIS Setup</h1>

<p>Below you must type in the information to setup the CMIS system. When submitting tables will be created in the databases as well as the main configuration file of the system. Remember to make include/config.php chmod 777.</p>

<script language="JavaScript" type="text/javascript">
<!--
function validate(form){
	if(form.title.value=="") {
		alert('You have not given your website a title. Please correct this and try again.');
		return false;
	}
	else if(form.name.value=="") {
		alert('You have not filled in a name for the webmaster. Please correct this and try again.');
		return false;
	}
	else if(form.email.value=="") {
		alert('You have not filled in an email for the webmaster. Please correct this and try again.');
		return false;
	}
	else if(form.username.value=="") {
		alert('You have not filled in a username for the webmaster. Please correct this and try again.');
		return false;
	}
	else if(form.name.value=="") {
		alert('You have not filled in a name for the webmaster. Please correct this and try again.');
		return false;
	}
	else if(form.password.value=="") {
		alert('You have not filled in a password for the webmaster. Please correct this and try again.');
		return false;
	}
	else if(form.password2.value=="") {
		alert('You have not confirmed the password for the webmaster. Please correct this and try again.');
		return false;
	}
	else if(form.password.value!=form.password2.value) {
		alert('Your passwords do not match. Please correct this and try again.');
		return false;
	}
	else {
		return true;
	}
}
-->
</script>

<form action="complete.php" method="post" onsubmit="return validate(this);">
<p>Name<br />
<input type="text" name="name" value="" class="shortInput" /></p>

<p>Email<br />
<input type="text" name="email" value="" class="shortInput" /></p>

<p>Username<br />
<input type="text" name="username" value="" class="shortInput" /></p>

<p>Password<br />
<input type="password" name="password" value="" class="shortInput" /></p>

<p>Repeat password<br />
<input type="password" name="password2" value="" class="shortInput" /></p>

<p><input type="submit" value="Install" /></p>
</form>
</td>
</tr>
</table>
</td>
</tr>
</table>

<?
// Include default footer
include "../theme/layout/cmis/footer.php";
?>