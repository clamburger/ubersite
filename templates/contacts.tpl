<if:leader>
<if:standalone>(<a href='?standalone'>View as standalone page</a>)</if:standalone>
<table class="contacts">
	<tr>
		<th>Name</th>
		<th>Email</th>
		<th>MSN</th>
		<th>Google Talk</th>
		<th>Phone</th>
		<th>Mobile</th>
		<th>Facebook URL</th>
	</tr>
<loop:output>
     <tr>
		<td><strong><tag:output[].Name /></strong></td>
        <td><tag:output[].Email /></td>
        <td><tag:output[].MSN /></td>
        <td><tag:output[].Google /></td>
        <td><tag:output[].Phone /></td>
        <td><tag:output[].Mobile /></td>
        <td><tag:output[].Facebook /></td>
	</tr>
</loop:output>
</table>
<else:leader>
You must be a leader to view this page.
</if:leader>
