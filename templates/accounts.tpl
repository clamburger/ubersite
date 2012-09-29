<if:leader>
<table>
<tr>
	<th>Category</th>
	<th>User ID</th>
	<th>Name</th>
	<!-- <th>Activity Group</th> -->
	<th>Duty Team</th>
	<th>Password<br />Changed?</th>
	<th>Profile?</th>
	<th>Last Active</th>
	<th>Manage</th>
</tr>
<loop:people>
<tr>
	<td><tag:people[].Category /></td>
	<td><a href='person.php?id=<tag:people[].UserID />'><tag:people[].UserID /></td>
	<td><tag:people[].Name /></td>	
	<td style='background-color: #<tag:people[].Colour />; color: #<tag:people[].FontColour />;'><tag:people[].DutyTeam /></td>
	<!-- <td><tag:people[].Greek /></td> -->
	<td style='text-align: center;'><tag:people[].PasswordChanged /></td>
	<td style='text-align: center;'><tag:people[].InfoFilled /></td>
	<td><tag:people[].LastActive /></td>
	<td><a href='accounts.php?edit=<tag:people[].UserID />#new'>Edit</a> <tag:people[].Delete /></td>
</tr>
</loop:people>
</table>

<form method="POST" action="accounts.php">
<if:editing>
<h2 id="new">Editing User:</h2>
<input type="hidden" name='action' value='edit' />
<else:editing>
<h2 id="new">New User:</h2>
<input type="hidden" name='action' value='new' />
</if:editing>
<input type="hidden" name='userID' value='<tag:edit-ID />' />
<table class='formTable'>
	<tr>
		<th>User ID:</th>
		<td><input type='text' name='userIDinput' maxlength='6' size='6' value='<tag:edit-ID />'<tag:edit-disabled /> /></td>
	</tr>
	<tr>
		<th>Name:</th>
		<td><input type='text' name='name' value='<tag:edit-name />' /></td>
	</tr>
	<tr>
		<th>Category:</th>
		<td><select name='category'>
			<tag:categories />
		</select></td>
	</tr>
	<tr>
		<!-- <th>Activity Group:</th> -->
		<th>Duty Team:</th>
		<td><select name='dutyteam'>
			<tag:dutyteams />
		</select>
		</td>
	</tr>
	<!-- <tr>
		<th>Duty Team:</th>
		<td><input type='text' name='greek' value='<tag:edit-greek />' /.</td>
	</tr>-->
	<tr>
		<th>Admin:</th>
		<td><input type="checkbox" name="admin"<tag:edit-admin />/></td>
	</tr>
	<tr>
		<th colspan="2" class="submitRow">
		<input type='button' value="Cancel" onclick="document.location = 'accounts.php'" />
		<input type='submit' value="<tag:submit />" />
		</th>
	</tr>
</table>
</form>
<else:leader>
You must be a leader to view this page.
</if:leader>
