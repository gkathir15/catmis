</table>

<table width="100%">
<tr>
<td>
<input type="submit" name="deletePages" value="<?= $lButtons["Delete"] ?>" class="button" onclick="var agree=confirm('<?= $lPageIndex["ConfirmDelete"] ?>');if(agree) {return true;}else {return false;}" /> <input type="submit" name="showPages" value="<?= $lButtons["Show"] ?>" class="button" onclick="var agree=confirm('<?= $lPageIndex["ConfirmShow"] ?>');if(agree) {return true;}else {return false;}" /> <input type="submit" name="hidePages" value="<?= $lButtons["Hide"] ?>" class="button" onclick="var agree=confirm('<?= $lPageIndex["ConfirmHide"] ?>');if(agree) {return true;}else {return false;}" />
</td>

<td align="right">
<input type="button" name="selectNoneButton" value="<?= $lButtons["SelectNone"] ?>" class="button" onclick="selectAll(document.pagesForm, this, false)" /> <input type="button" name="selectAllButton" value="<?= $lButtons["SelectAll"] ?>" class="button" onclick="selectAll(document.pagesForm, this, true)" />
</td>
</tr>
</table>
</form>
<br />
