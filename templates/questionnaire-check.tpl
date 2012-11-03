<if:leader>
<table>
<tr>
	<th>Person</th>
	<th width='80px'>General<br />Feedback</th>
	<th width='80px'>Activity<br />Feedback</th>
	<th width='80px'>Elective<br />Feedback</th>
	<th width='80px'>Final<br />Comments</th>
</tr>
<loop:status>
<tr>
	<td><tag:status[].name /></td>
        <loop:status[].stages>
          <tag:status[].stages[] />
        </loop:status[].stages>
</tr>
</loop:status>
<tr>
	<th>Totals:</th>
	<loop:totals>
		<th><tag:totals[] /></th>
	</loop:totals>
</tr>
</table>
<else:leader>
You must be a leader to view this page.
</if:leader>
