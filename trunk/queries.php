
<!--
<? 
if (debug && !empty($_GET["queries"])) {
?>
		<table cellspacing="0" cellpadding="2" class="index" style="width:70%">
			<tr>
				<td class="indexHeader">#</td>
				<td class="indexHeader">Query</td>
			</tr>
<?
	for ($i=0; $i<sizeof($dbi->queries); $i++) {
		echo "<tr><td class=\"item".($i%2==0?"Alt":"")."\" valign=\"top\">".$i."</td>";
		echo "<td class=\"item".($i%2==0?"Alt":"")."\" valign=\"top\">";
		echo wordwrap($dbi->queries[$i], 70, "<br />", true);
		echo "</td></tr>";
	}
?>
			</table>
<?
}
?>
-->