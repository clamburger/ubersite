<h2>Photo Processing Lab: <sup style='color: green;'>Alpha</sup></h2>

<if:leader>

<if:filename>
<h3><a href='?'>Go back to welcome message</a></h3>
<else:filename>
<h3>The Photo Processing Lab is currently in alpha and not intended for general use. If you have somehow found this page, congratulations! You are allowed to use it, however you should expect things to be quite broken, confusing and unfinished. <if:small><strong>This page is not designed for 1024x768!</strong></if:small></h3>
</if:filename>

<table class="ladder captioning" style='width: 100%;'>

<tr>
	<td rowspan="4" class="image" style="width: 525px; height: 525px; padding: 0px;" valign="middle">
		<if:filename>
			<a href="includes/uploads/<tag:filename />" style="border: none;" title="Click to view full resolution">
			<img src="<tag:imageURL />" style="margin: 0px;" /></a><br />
		<else:filename>
			<div style='height: 510px; width: 515px; padding: 5px;'>
			<h3><strong>Welcome to the Photo Processing Lab.</strong></h3>
			Please select an image from the photo reel on the right to get started.
			The colour of the border will change depending on the status of the photo in question.
			<h3>Red = this image needs processing<br />
			Green = you are currently processing this image<br />
			Blue = this image has been processed</h3>
			Make sure you click the Save button at the bottom of the table for your changes to take effect.
			If you move to another photo without clicking Save, you will lose all your changes!
			<br /><br /><br /><h3><strong>Do I have to process every single photo?</strong></h3>
			For any average-looking photos that are unlikely to be useful for the website or DVD, don't worry about tagging them:
			just hit Save and move onto the next photo.
			<br /><br /><br /><h3><strong>Current Photo Reel settings:</strong></h3>
			Show all images, unprocessed first, oldest to newest
			</div>
		</if:filename>
	</td>
	
	<td style="padding: 0px; height: 145px; overflow: scroll; min-width: 400px;" colspan="2">
		<div style="overflow-y: auto; width: 100%; height: 100%;">
			<if:navigation>
			<loop:navigation>
				<a href='?filename=<tag:navigation[].filename />'>
				<img src="<tag:navigation[].thumbnail />" style="float: left; margin: 3px; border-color: <tag:navigation[].colour />;"
					 title="<tag:navigation[].filename />" />
				</a>
			</loop:navigation>
			<else:navigation>
			<br /><br />There are no photos currently available for processing.<br /><br />
			<a href='photo_upload.php'>Upload Photos</a>
			</if:navigation>
		</div>
	</td>

</tr>

<tr>
	<td height="50px" style='font-weight: normal;' colspan="2" class="image">
	<if:filename>
	<a href='?filename=<tag:prevFile />' class='pollLink'><< <tag:prevFile /></a> | Currently processing <strong><tag:filename /></strong> | <a href='?filename=<tag:nextFile />' class='pollLink'><tag:nextFile /> >></a><br />
	Processing Status: <tag:status />
	<else:filename>
	No image currently selected for processing.
	</if:filename>
	</td>
</tr>

<form action="?" method="POST">

<tr>
	<input type="hidden" name="filename" value="<tag:filename />" />
	
	<td style='vertical-align: top; text-align: left;'>
	<if:filename>
	<h3 style='margin-bottom: 0px;'>Image Information:</h3>
	<ul style='margin-top: 0px;'>
		<li><a href="includes/uploads/<tag:filename />"><tag:filename /></a> - <tag:resolution /></li>
		<li>Uploaded by <tag:uploader /></li>
	</ul>
	<h3>Tags:</h3>
	<ul>
		<li><input type="text" placeholder="What is this image about?" size="40" name="tags" maxlength="255" value="<tag:form.tags />" /><br />
		<span style='color: grey; font-size: small;'>eg. robotics, dinner, wide game<br />
		You can add more than one value</span></li>
	</ul>
	</if:filename>
	</td>
	
	<td style='vertical-align: top; text-align: left;' width="300px">
	<if:filename>
	<h3 style='margin-bottom: 0px;'>Quality Control:</h3>
	<ul style='margin-top: 0px; list-style-type: none; padding-left: 10px;'>
		<li><input type="radio" id="qualityDupe" name="quality" value="0" <tag:form.qualityDupe /> />
			<label for="qualityDupe">Duplicate / near duplicate</label></li>
		<li><input type="radio" id="qualityLow" name="quality" value="1" <tag:form.qualityLow /> />
			<label for="qualityLow">Nothing special<br />
			<span style='color: grey; font-size: small; margin-left: 26px;'>Bad photo or uninteresting subject</span></label></li>
		<li><input type="radio" id="qualityMed" name="quality" value="2" <tag:form.qualityMed /> />
			<label for="qualityMed">Decent enough<br />
			<span style='color: grey; font-size: small; margin-left: 26px;'>Interesting subject and/or decent photo</span></label></li>
		<li><input type="radio" id="qualityHigh" name="quality" value="3" <tag:form.qualityHigh /> />
			<label for="qualityHigh">Absolutely brilliant<br />
			<span style='color: grey; font-size: small; margin-left: 26px'>Excellent and unique photo of subject</span></label></li>
		<li><input type="checkbox" id="website" name="website" <tag:form.website /> />
			<label for="website">Publish to camp website?</label></li>
		<li><input type="checkbox" id="nobody" name="nobody" <tag:form.nobody /> />
			<label for="nobody">There are no people in this image</label></li>
	</ul>
	</if:filename>
	</td>
	
</tr>

<tr>
	<th colspan="2" style="height: 30px;">
		<if:filename>
			<input type="submit" value="<tag:form.submit />" />
		<else:filename>
			&nbsp;
		</if:filename>
	</th>
</tr>

</form>
</table>

<else:leader>
You must be a leader to view this page.
</if:leader>