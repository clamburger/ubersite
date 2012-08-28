	<table class="ladder captioning" style='width: 99%;'>

<tr>
	<td rowspan="4" class="image">
		<img src="<tag:imageURL />" /><br />
		<a href="/photos/<tag:filename />">View full resolution</a>
	</td>

	<th height="40px">
		<a href="<tag:prevImage />"><< Previous</a> | <tag:filename /> | <a href="<tag:nextImage />">Next >></a>
	</th>

</tr>

<tr height="100px">
	<td>
		<div id="tagText" <tag:tagTextStyle />>
			<if:nobody>
			There is nobody in this photo.
			<if:leader>
			<br /><br /><a href="?image=<tag:filename />&untag=nobody" class="pollLink" style='font-size: smaller;'>Undo</a>
			</if:leader>
			<else:nobody>
			
			<if:tags>
			In this photo:<br /><br />
			<tag:tags /><!if:wget><br />
			<span style='font-size: smaller;'>
			<a class='pollLink' onClick='photo_tag();'>Tag another person</a></!if:wget>
			<if:leader>
			 | <a class='pollLink' onClick='photo_untag();'>Untag somebody</a>
			</if:leader>
			</span>
			<else:tags>
			Nobody has been tagged in this photo.<br /><small>Fun fact: certain plush animals can be tagged in photos</small><!if:wget><br /><br />
			<a href="#" class="pollLink" onClick='photo_tag();'>Tag somebody</a></!if:wget>
			</if:tags>
			
			</if:nobody>
		</div>
		
		<!if:wget>
		<div id="tagInput" <tag:tagInputStyle />>
			Please only tag people who are actually in the photo.
			If you accidentally tag the wrong person, let a leader know and they will untag it.<br /><br />
			<form method="POST" action="?image=<tag:filename />&tag" id="tagForm">
			<select name="newTag" id="newTag" onKeyPress="return photo_submit(this, event);">
			<tag:dropdown />
			</select>
			<input type="submit" value="Tag!" />
			<input type="button" value="Go Back" onClick='photo_tag();' />
			</form>
		</div>
		
		<div id="untagInput" style='display: none;'>
			Click on a person's name to untag them.<br /><br />
			<tag:untags /><br />
			<a onClick='photo_untag();' style='font-size: smaller;'>Go back</a>
		</div>
		</!if:wget>
		
	</td>
</tr>

<tr>
	<th height="40px">Captions</th>
</tr>

<tr>
	<td style='vertical-align: top; width: 40%;'>
		<tag:caption />
		<br />
		<!if:wget>
		<form method="POST" action="?image=<tag:filename />">
		<input type="text" size="50" name="caption" maxlength="200" placeholder="Add a caption to this photo." />
		<input type="submit" value="Submit" />
		</form>
		<br />
		</!if:wget>
	</td>
</tr>
</table>
