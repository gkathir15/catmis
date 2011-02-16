<div class="formIndent">
<form name="user" action="<?= scriptUrl.'/'.folderUsers.'/'.fileUserChangePassword ?>?save=1<?= !empty($user->id)?"&amp;userId=$user->id":"" ?><?= showPopup ? "&amp;popup=1" : "" ?>" enctype="multipart/form-data" method="post">
<? if ($forgotPassword) { ?>
<input type="hidden" name="forgotPassword" value="1" />
<input type="hidden" name="key" value="<?= $key ?>" />
<? } else if ($login->id != $user->id) { ?>
<input type="hidden" name="changePasswordAdmin" value="1" />
<? } else { ?>
<p><?= empty($user->id)?"*":"" ?><?= $lEditUser["ExistingPassword"] ?><br />
<input type="password" name="oldPassword" value="" onkeyup="validateField(this, 'shortInput', 'error', warning_oldPassword)" class="shortInput<?= $errors->hasError("oldPassword")?" error":"" ?>" /> <? $errors->printWarningIcon("oldPassword",(!$errors->hasError("oldPassword")?$lEditUser["MissingOldPassword"]:""),0) ?></p>
<? } ?>

<p><?= empty($user->id)?"*":"" ?><?= $lEditUser["NewPassword"] ?><br />
<input type="password" name="password" value="" onkeyup="validateField(this, 'shortInput', 'error', warning_password)" class="shortInput<?= $errors->hasError("password")?" error":"" ?>" /> <? $errors->printWarningIcon("password",(!$errors->hasError("password")?$lEditUser["MissingPassword"]:""),0) ?></p>

<p><?= empty($user->id)?"*":"" ?><?= $lEditUser["RepeatNewPassword"] ?><br />
<input type="password" name="repeatedPassword" value="" onkeyup="validateField(this, 'shortInput', 'error', warning_repeatedPassword)" class="shortInput<?= $errors->hasError("repeatedPassword")?" error":"" ?>" /> <? $errors->printWarningIcon("repeatedPassword",(!$errors->hasError("repeatedPassword")?$lEditUser["MissingRepeatedPassword"]:""),0) ?></p>

<p><input type="submit" value="Send" class="button" /></p>
</form>
</div>