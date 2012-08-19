<h2>First Annual Nanobyte Awards:</h2>

<div style='font-size: 14px;'>Welcome to the First Annual Nanobyte Awards, the only &Uuml;berTweak award ceremony where <em>you</em> decide who wins! During the camp you'll see some people doing awesome things, silly things and funny things: here's your chance to reward those people by nominating them for an award!<br /><br />

There are a number of different awards that people can win. Everybody is allowed to nominate one person for each award, however you are allowed to change your nomination if you change your mind.</div>

<table class="formTable" style='width: 950px;'>
<tr>
<td colspan='2' class="cellBackground" style="font-weight: bold; font-size: 120%;">Select an award:
<select style='font-size: 110%;' id='categorySelector' onchange="awards_selectAward(this.value)">
<option value='none'>---</option>
<tag:categoryDropdown />
</select>
</th>
</tr>
<loop:categories>
<form method="POST">
<input type="hidden" name="category" value="<tag:categories[].ID />" />
<tr id='award<tag:categories[].ID />' <if:categories[].invisible>style='display: none;'</if:categories[].invisible>>
	<td style='padding: 0px; width: 96px; height: 98px; vertical-align: middle;'>
		<img src='camp-data/profiles/<tag:categories[].nominee />-thumb.jpg' id='photo<tag:categories[].ID />' 
		onerror="this.src = 'resources/img/no-pic-thumb.jpg';"/>
	</td>
	<td style='padding-left: 10px;'><strong><tag:categories[].description /></strong><br /><br />		
		<if:categories[].nominee>
		<input type="hidden" name="denominate" value="true" />
		<input type="hidden" name="nominee" value="<tag:categories[].nominee />" />
		You have nominated <tag:categories[].userpage />! <input id="submit" type="submit" value="Remove Nomination" class="declineButton" />
		<else:categories[].nominee>
		You have not yet nominated anybody for this award.<br />
		Select a person to nominate:
		<select name='nominee' onkeyup="awards_selectPerson(<tag:categories[].ID />, this.value)" onchange="awards_selectPerson(<tag:categories[].ID />, this.value)">
			<tag:userDropdown />
		</select><br /><br />
		Why are you nominating this person / what did this person do or say?<br />
		<textarea name="notes"></textarea>
		<input id="submit<tag:categories[].ID />" type="submit" value="Nominate!" class="approveButton" style="display: none; margin: 0px;" />
		</if:categories[].nominee>
	</td>
</tr>
</form>
</loop:categories>
</table>

<if:leader>
<h2>Nominations for <tag:day />:</h2>
<loop:nomCats>
	<h3><tag:nomCats[].name />:</h3>
	<ul>
		<loop:nomCats[].nominees>
		<li><tag:nomCats[].nominees[] /></li>
		</loop:nomCats[].nominees>
	</ul>
</loop:nomCats>
</if:leader>