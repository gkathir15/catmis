<form name="send" action="<?= scriptUrl ?>/<?= fileSendMail ?>?mode=send" method="post">
<p><?= $lSendMail["Name"] ?><br />
<input name="name" type="text" value="<?= !empty($_POST["name"])?$_POST["name"]:$login->name ?>" class="shortInput" /></p>

<p><?= $lSendMail["Mail"] ?><br />
<input name="email" type="text" value="<?= !empty($_POST["email"])?$_POST["email"]:$login->email ?>" class="shortInput" /></p>

<p><?= $lSendMail["Subject"] ?><br />
<input name="subject" type="text" value="<?= $subject ?>" class="shortInput" /></p>

<p><?= $lSendMail["Message"] ?><br />
<textarea name="message" rows="10"><?= $message ?></textarea></p>

<p><input type="submit" value="<?= $lSendMail["SendMail"] ?>" class="normalInput" /></p>
</form>

<script language="JavaScript" type="text/javascript">document.send.friendEmail.focus()</script>