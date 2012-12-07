<if:statistics>
<if:wget>
To see the achievements for a certain person, simply click on their name.
<br clear="all" />
</if:wget>
<div style='float: left; margin-right: 50px;'>
<h3>Top Achievers:</h3>
<table>
<tr>
	<th>#</th>
	<th>Player</th>
	<th>Achievements</th>
	<th>Rarest Achievement</th>
</tr>
<loop:leaderboard>
<tr>
	<th><tag:leaderboard[].Rank /></th>
	<td><a href='/trosnoth/<tag:leaderboard[].UserID />'><tag:leaderboard[].Name /></a></td>
	<td><tag:leaderboard[].Count /></td>
	<td><tag:leaderboard[].Rarest /></td>
</tr>
</loop:leaderboard>
</table>
</div>

<div style='float: left; margin-right: 50px;'>
<h3>Rarest Achievements:</h3>

<table>
<tr>
	<th>#</th>
	<th>Achievement Name</th>
	<th>Achievers</th>
</tr>
<loop:top>
<tr>
	<th><tag:top[].Rank /></th>
	<td><tag:top[].Name /></td>
	<td><tag:top[].Count /></td>
</tr>
</loop:top>
</table>
</div>

<else:statistics>
<if:achievements>
<div style='text-align: center; font-size: 200%; margin: 20px;'><tag:name />: <tag:unlocked /> out of <tag:total /> achievements unlocked (<tag:percent />%)<!if:wget><br />
<span style='font-size: 50%;'>(Although you could certainly get all the achievements without earning them properly, you will be unable to receive any Trosnoth awards at Show Night if you do! You have been warned.)</span></!if:wget></div>

<loop:achievements>
<div class="person" style='<tag:achievements[].Style /> margin-right: 10px; width: 440px;'>
	<div class="personLeft">
		<img src="/resources/achievements/<tag:achievements[].Image />.png" width="96" height="96" />
	</div> 
	<div class="personRight" style="width: 325px;">
		<span style="font-size: 150%;"><tag:achievements[].Name /></span><br /><br />
		<tag:achievements[].Description /><br /><br />
		<tag:achievements[].Status />
	</div>
</div>
</loop:achievements>
<else:achievements>
<div style='text-align: center; font-size: 200%; margin: 20px;'>You can't start earning achievements until you play Trosnoth at least once!<br />
<span style='font-size: 50%;'>(This page updates every night so this page will not be updated immediately)</span></div>

</if:achievements>
</if:statistics>
