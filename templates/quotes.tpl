Here we have some of the funny and potentially very embarrassing things campers and leaders have said during camp. Have you heard someone say something really silly? If so, we recommend you submit it immediately.
<if:leader>
<ul>
<if:debug>
<li><a href='?' style='color: maroon;'>Back to standard mode</a></li>
<else:debug>
<li><a href='?debug' style='color: maroon;'>Debug mode</a> - shows declined quotes and lets you revert any quote back to "unapproved" status</li>
</if:debug>
</ul>
<else:leader>
<br /><br />
</if:leader>
<table class="ladder" width="100%">
<if:quotes>
<tr>
<th width='10%'>Who Said It</th>
<th>What They Said</th>
<if:controls><th>Controls</th></if:controls>
</tr>
<loop:quotes>
	<tag:quotes[].rowTag />
    <tag:quotes[].people />
    <tag:quotes[].text />
	<tag:quotes[].controls />
    </tr>
</loop:quotes>
<else:quotes>
<tr><td>There are currently no quotes available. Check back soon!</td></tr>
</if:quotes>
</table>
<!if:wget>
<br/>
<form method="POST">
<center>
    <table class="ladder" style='width: 560px;'>
		<tr>
			<th colspan="2">New Quote</th>
		</tr>
        <tr>
            <td>Are you quoting one person or multiple people?</td>
            <td style='width: 152px;'><input type="radio" value="single" name="people" id="singleRadio" <tag:singleCheck /> onClick='quotes_single();' /> <label for="singleRadio">Single Person</label><br />
				<input type="radio" value="multiple" name="people" id="multipleRadio" <tag:multipleCheck /> onClick='quotes_multiple()' /> <label for="multipleRadio">Multiple People</label></td>
        </tr>
        <tr id="selectionRow" style="<tag:selectionStyle />">
			<td>Who are you quoting?</td>
			<td><select name="name" id="name"><tag:dropdown /></select></td>
        <tr>
            <th colspan="2">What was the context?</th>
        </tr>
        <tr>
            <td colspan="2"><textarea style="width:100%;" name="context" placeholder="When/where did this quote occur? What were they talking about? You only need to fill in this box if the quote doesn't make sense to somebody who wasn't there."><tag:context /></textarea></td>
        </tr>
        <tr>
            <th colspan="2">What did they say?</i></th>
        </tr>
        <tr>
            <td colspan="2"><textarea style="width:100%;" name="quote" placeholder="Enter the actual quote here."><tag:quote /></textarea></td>
        </tr>
        <tr>
            <th colspan="2" style="text-align:center"><input type="submit" value="Submit Quote" /></td>
        </tr>
    </table>
</center>
</form>
</!if:wget>
