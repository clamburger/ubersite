<if:leader>
This page will update the columns in the <tt>`questionnaire`</tt> table with information from the <tt>`questionnaire_electives`</tt> table. This needs to be done every time the <tt>`questionnaire_electives`</tt> table is changed, otherwise campers will get errors when they try and submit their questionnaire. This is not a perfect solution, but is sufficient for now.<br />
<br />
There are two options: either add missing electives or remove electives that are no longer needed. The latter option is disabled if there are any rows in the <tt>`questionnaire`</tt> table, since any data in the removed columns will be lost.

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
