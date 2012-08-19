<h2>Suggestion Box:</h2>
This page is where you can give us suggestions for various different things: jump to a section that interests you and post away! Try to make one post for each individual idea, make sure posts go in the right category and check the list of already-existing ideas to make sure your idea hasn't already been submitted for something else. Have an idea for something camp-related? Hold that thought because you'll have a chance to tell us everything in the questionnaire at the end of camp.
<br /><br />
<ul class="tabs" data-tabs="tabs">
	<loop:tabs>
		<li <tag:tabs[].first />><a href="#<tag:tabs[].id />"><tag:tabs[].name /></a></li>
	</loop:tabs>
	<if:leader>
		<if:debug>
			<li><a href="?" style="font-weight: normal; color: maroon;">Hide deleted suggestions</a></li>
		<else:debug>
			<li><a href="?debug" style="font-weight: normal; color: maroon;">Show deleted suggestions</a></li>
		</if:debug>
	</if:leader>
</ul>

<div class="tab-content">
<loop:categories>
<div id="<tag:categories[].id />" class="<tag:categories[].class />">
	<h1><tag:categories[].name /></h1>
	<tag:categories[].description />
	<br /><br />
	<!if:wget>
	<form name="<tag:categories[].id />Form" method="POST" action="?">
	<span style='font-size: medium;'>Idea:</span> &nbsp;<input type="text" size="100" name="idea" />
	<input type="hidden" name="category" value="<tag:categories[].id />" />
	<if:categories[].bugBox>
	<input type="checkbox" name="bug" id="bug" value="1" /> <label for="bug">Bug Report</label>
	</if:categories[].bugBox>
	<input type="submit" value="Post Idea" /></td>
	</form>
	<br />
	</!if:wget>
	<table class="ladder" width='98%'>
	<tr>
	<th width='90%'>Suggestion</th>
	<th>Submitter</th>
	<if:categories[].deleteH>
	<th>Delete</th>
	</if:categories[].deleteH>
	</tr>
	<loop:categories[].ideas>
	<tr style='<tag:categories[].ideas[].style />'>
	<td><tag:categories[].ideas[].idea /></td>
	<td><tag:categories[].ideas[].submitter /></td>
	<tag:categories[].ideas[].delete />
	</tr>
	</loop:categories[].ideas>
	</table>
</div>
</loop:categories>
</div>