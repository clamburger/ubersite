<if:leader>
This page will update the Elective Feedback page in the questionnaire with information from the <code>`questionnaire_electives`</code> table.
<br />
There are two options: either add missing electives or remove electives that are no longer needed. The latter option is disabled if there are any rows in the <code>`questionnaire`</code> table, since any data in the removed columns will be lost.

<h2 style='margin-top: 30px;'>Current Status:</h2>
<table>
<tr>
	<th>ID</th>
	<th>Name</th>
	<th>Status</th>
</tr>
<loop:columns>
<tr>
	<tag:columns[] />
</tr>
</loop:columns>
</table>
<form action="" method="POST">
<tag:actions />
</form>
<else:leader>
You must be a leader to view this page.
</if:leader>
