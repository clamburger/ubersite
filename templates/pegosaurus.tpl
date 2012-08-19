<br />

<div style='text-align: center; margin: auto;'>
<img src="resources/img/stegosaurus.png" /><br />
<h2 style='border-bottom: none;'>
<if:pegosaurus>
Current Pegosaurus: <tag:pegosaurus />
<else:pegosaurus>
There is no pegosaurus... yet.
</if:pegosaurus>
</h2>
</div>
<br />
<if:showTable>
<table class="ladder" style='text-align: center; margin: auto;'>
    <tr>
        <th>Rank</th>
        <th width="180px">Unfortunate Victim</th>
        <th width="80px">Peg Count</th>
    </tr>
    <loop:rankings>
    <tr>
        <th><tag:rankings[].rank /></th>
        <td><tag:rankings[].leader /></td>
        <td><strong><tag:rankings[].pegs /></strong></td>
    </tr>
    </loop:rankings>
    <if:leader>
    <tr id="link" style='height: 35px;'>
		<td colspan="3"><a onclick="pegosaurus_new();">Add new Pegosaurus record</a></td>
	</tr>
	<form method="POST">
	<tr id="new" style='height: 35px; display: none;'>
		<th style='padding: 0px;'><input type="submit" value="Add" /></th>
		<td style='padding: 0px;'><select name="leader"><tag:leaderDropdown /></select></td>
		<td style='padding: 0px;'><input name="pegs" type="text" size="8" style='margin: 3px;' /></td>
	</tr>
	</form>
	</if:leader>
</table>
</if:showTable>