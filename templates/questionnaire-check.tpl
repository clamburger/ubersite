<h2>Questionnaire Status:</h2>
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
	<tag:status[].stage1 />
	<tag:status[].stage2 />
	<tag:status[].stage3 />
	<tag:status[].stage4 />
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