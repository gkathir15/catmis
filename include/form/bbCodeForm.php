<select name="<?= $name ?>_addbbcode20" onchange="bbfontstyle(<?= $path ?>,'[h'+<?= $form ?>.<?= $name ?>_addbbcode20.options[<?= $form ?>.<?= $name ?>_addbbcode20.selectedIndex].value + ']', '[/h'+<?= $form ?>.<?= $name ?>_addbbcode20.options[<?= $form ?>.<?= $name ?>_addbbcode20.selectedIndex].value + ']')" class="button" tabindex="99">
<option value="0" class="genmed" selected="selected"><?= $lFormatBar["Normal"] ?></option>
<option value="1" class="genmed"><?= $lFormatBar["Heading1"] ?></option>
<option value="2" class="genmed"><?= $lFormatBar["Heading2"] ?></option></select> 
<input type="button" class="button" accesskey="b" name="addbbcode0" value=" <?= $lFormatBar["B"] ?> " style="font-weight:bold; width: 30px" onClick="bbstyle(<?= $path ?>,0)" tabindex="100" title="<?= $lFormatBar["BoldText"] ?>" />
<input type="button" class="button" accesskey="i" name="addbbcode2" value=" <?= $lFormatBar["i"] ?> " style="font-style:italic; width: 30px" onClick="bbstyle(<?= $path ?>,2)" tabindex="101" title="<?= $lFormatBar["ItalicText"] ?>" />
<input type="button" class="button" accesskey="u" name="addbbcode4" value=" <?= $lFormatBar["u"] ?> " style="text-decoration: underline; width: 30px" onClick="bbstyle(<?= $path ?>,4)" tabindex="102" title="<?= $lFormatBar["UnderlineText"] ?>" />
<input type="button" class="button" accesskey="l" name="addbbcode10" value="<?= $lFormatBar["List"] ?>" style="width: 40px" onClick="bbstyle(<?= $path ?>,10)" tabindex="104" title="<?= $lFormatBar["ListText"] ?>" />
<input type="button" class="button" accesskey="o" name="addbbcode12" value="<?= $lFormatBar["List="] ?>" style="width: 40px" onClick="bbstyle(<?= $path ?>,12)" tabindex="105" title="<?= $lFormatBar["List2Text"] ?>" />
<input type="button" class="button" accesskey="p" name="addbbcode14" value="<?= $lFormatBar["Img"] ?>" style="width: 40px" onclick="popup('<?= scriptUrl ?>/files/insertImage.php?form=<?= $form.".".$name ?>&amp;popup=1', 'popup', '400', '350', 'resizable,scrollbars')" tabindex="106" title="<?= $lFormatBar["ImgText"] ?>" />
<input type="button" class="button" accesskey="w" name="addbbcode16" value="<?= $lFormatBar["Link"] ?>" style="text-decoration: underline; width: 40px" onclick="popup('<?= scriptUrl ?>/files/insertLink.php?form=<?= $form.".".$name ?>&amp;popup=1', 'popup', '400', '350', 'resizable,scrollbars')" tabindex="107" title="<?= $lFormatBar["LinkText"] ?>" />

<textarea name="<?= $name ?>" rows="<?= $rows ?>" cols="<?= $cols ?>" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);"><?= $value ?></textarea><br />
<? if ($showSmilies) { ?>
<a href="javascript:emoticon(<?= $path ?>,':D')" tabindex="108"><img src="<?= iconUrl ?>/smilies/icon_biggrin.gif" border="0" alt="Very Happy" title="Very Happy" /></a>
<a href="javascript:emoticon(<?= $path ?>,':)')" tabindex="109"><img src="<?= iconUrl ?>/smilies/icon_smile.gif" border="0" alt="Smile" title="Smile" /></a>
<a href="javascript:emoticon(<?= $path ?>,':(')" tabindex="110"><img src="<?= iconUrl ?>/smilies/icon_sad.gif" border="0" alt="Sad" title="Sad" /></a>
<a href="javascript:emoticon(<?= $path ?>,':o')" tabindex="111"><img src="<?= iconUrl ?>/smilies/icon_surprised.gif" border="0" alt="Surprised" title="Surprised" /></a>
<a href="javascript:emoticon(<?= $path ?>,':shock:')" tabindex="112"><img src="<?= iconUrl ?>/smilies/icon_eek.gif" border="0" alt="Shocked" title="Shocked" /></a>
<a href="javascript:emoticon(<?= $path ?>,':?')" tabindex="113"><img src="<?= iconUrl ?>/smilies/icon_confused.gif" border="0" alt="Confused" title="Confused" /></a>
<a href="javascript:emoticon(<?= $path ?>,'8)')" tabindex="114"><img src="<?= iconUrl ?>/smilies/icon_cool.gif" border="0" alt="Cool" title="Cool" /></a>
<a href="javascript:emoticon(<?= $path ?>,':lol:')" tabindex="115"><img src="<?= iconUrl ?>/smilies/icon_lol.gif" border="0" alt="Laughing" title="Laughing" /></a>
<a href="javascript:emoticon(<?= $path ?>,':x')" tabindex="116"><img src="<?= iconUrl ?>/smilies/icon_mad.gif" border="0" alt="Mad" title="Mad" /></a>
<a href="javascript:emoticon(<?= $path ?>,':P')" tabindex="117"><img src="<?= iconUrl ?>/smilies/icon_razz.gif" border="0" alt="Razz" title="Razz" /></a>
<a href="javascript:emoticon(<?= $path ?>,':oops:')" tabindex="118"><img src="<?= iconUrl ?>/smilies/icon_redface.gif" border="0" alt="Embarassed" title="Embarassed" /></a>
<a href="javascript:emoticon(<?= $path ?>,':cry:')" tabindex="119"><img src="<?= iconUrl ?>/smilies/icon_cry.gif" border="0" alt="Crying or Very sad" title="Crying or Very sad" /></a>
<a href="javascript:emoticon(<?= $path ?>,':evil:')" tabindex="120"><img src="<?= iconUrl ?>/smilies/icon_evil.gif" border="0" alt="Evil or Very Mad" title="Evil or Very Mad" /></a>
<a href="javascript:emoticon(<?= $path ?>,':twisted:')" tabindex="121"><img src="<?= iconUrl ?>/smilies/icon_twisted.gif" border="0" alt="Twisted Evil" title="Twisted Evil" /></a>
<a href="javascript:emoticon(<?= $path ?>,':roll:')" tabindex="122"><img src="<?= iconUrl ?>/smilies/icon_rolleyes.gif" border="0" alt="Rolling Eyes" title="Rolling Eyes" /></a>
<a href="javascript:emoticon(<?= $path ?>,':wink:')" tabindex="123"><img src="<?= iconUrl ?>/smilies/icon_wink.gif" border="0" alt="Wink" title="Wink" /></a>
<a href="javascript:emoticon(<?= $path ?>,':!:')" tabindex="124"><img src="<?= iconUrl ?>/smilies/icon_exclaim.gif" border="0" alt="Exclamation" title="Exclamation" /></a>
<a href="javascript:emoticon(<?= $path ?>,':?:')" tabindex="125"><img src="<?= iconUrl ?>/smilies/icon_question.gif" border="0" alt="Question" title="Question" /></a>
<a href="javascript:emoticon(<?= $path ?>,':idea:')" tabindex="126"><img src="<?= iconUrl ?>/smilies/icon_idea.gif" border="0" alt="Idea" title="Idea" /></a>
<a href="javascript:emoticon(<?= $path ?>,':arrow:')" tabindex="127"><img src="<?= iconUrl ?>/smilies/icon_arrow.gif" border="0" alt="Arrow" title="Arrow" /></a>
<? } ?>