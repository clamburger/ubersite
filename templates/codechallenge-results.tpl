<table>
	<tr>
		<th>Name</th>
		<th>Score</th>
		<th>Average Time</th>
		<th>Time 1</th>
		<th>Time 2</th>
		<th>Time 3</th>
	</tr>
<loop:output>
     <tr>
		<td<if:output[].Valid> style="background-color: green; color: white;"</if:output[].Valid>><strong><tag:output[].Name /></strong></td>
        <td<if:output[].Valid> style="background-color: green; color: white;"</if:output[].Valid>><tag:output[].Score /></td>
        <td<if:output[].Valid> style="background-color: green; color: white;"</if:output[].Valid>><tag:output[].TimeMSAverage /></td>
        <td<if:output[].Valid> style="background-color: green; color: white;"</if:output[].Valid>><tag:output[].TimeMS1 /></td>
        <td<if:output[].Valid> style="background-color: green; color: white;"</if:output[].Valid>><tag:output[].TimeMS2 /></td>
        <td<if:output[].Valid> style="background-color: green; color: white;"</if:output[].Valid>><tag:output[].TimeMS3 /></td>
	</tr>
</loop:output>
</table>
