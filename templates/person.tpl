<div class="newprofileCompleteContainer">

<div class="newprofilePhotoSuperContainer">
<div class="newprofilePhotoContainer">
	<div class="newprofilePhoto" style='border-color: #<tag:person.colour />; background-color: #<tag:person.colour />; color: #<tag:person.font />;'>
		<img src="<tag:picture />" style='width: 280px; height: 500px;'><br />
		<!-- <strong>Duty Team:</strong> <tag:person.greek /><br />
		<strong>Activity Group:</strong> <tag:person.team /> -->
		<strong>Duty Team:</strong> <tag:person.team />
		<if:allowedToEdit><br />
		<a href="?id=<tag:person.id />&edit" class="button">Edit profile</a> 
		<if:contactDetails>
		<a href="?id=<tag:person.id />&contact" class="button">Edit contact details</a>
		</if:contactDetails>
		<else:allowedToEdit>
		<if:cancelButton><br />
		<a href="?id=<tag:person.id />" class="button">Return to profile without saving</a>
		</if:cancelButton>
		</if:allowedToEdit>
	</div>
</div>
</div>

<div class="newprofileInfo">

<if:contactMode>
<h2 style='padding-bottom: 5px;'>Contact Details:</h2>
</if:contactMode>

	<if:contactMode>
	
	These details will emailed out to everyone who supplies their details at the end of camp, allowing you to stay in contact with leaders and campers that you enjoyed talking to during the week.<br /><br />All fields are completely optional, although if you don't provide an email address we won't be able to send you the list! Feel free to include as much or as little information as you are comfortable with. 
	
	<form action="?id=<tag:person.id />" method="POST">
	<table class='newprofileContacts'>
		<tr><td><b>Email:</b></td><td><input type="text" size="40" maxlength="75" name="email" value="<tag:contact.email />" /></td></tr>
		<tr><td><b>MSN:</b></td><td><input type="text" size="40" maxlength="75" name="msn" value="<tag:contact.msn />" /></td></tr>	
		<tr><td><b>Google Chat:</b></td><td><input type="text" maxlength="75" size="40" name="google" value="<tag:contact.google />" /></td></tr>
		<tr><td><b>Phone:</b></td><td><input type="text" size="40" maxlength="10" name="phone" value="<tag:contact.phone />" /></td></tr>
		<tr><td><b>Mobile:</b></td><td><input type="text" size="40" maxlength="10" name="mobile" value="<tag:contact.mobile />" /></td></tr>
		<tr><td><b>Facebook URL:</b></td><td>http://facebook.com/ <input type="text" size="19" maxlength="200" name="facebook" value="<tag:contact.facebook />" /><br />
		<small>(Don't know your URL? Just enter your name.)</small></td></tr>
		<tr><td>&nbsp;</td><td><input type="submit" value="Submit" name="contactSubmitted" /></td></tr>
	</table>
	</form> 
	
	<else:contactMode>
	
	<if:editMode>
		<form action="?id=<tag:person.id />" method="POST">
		Your nickname / Internet name:<br />
		<em style='color: grey; margin-left: 10px; line-height: 2;'>Also known as &nbsp;
		<strong><input type="text" value="<tag:person.nickname />" name="nickname" size="40" maxlength="30" /></strong></em><br /><br />
	<else:editMode>
		<if:name>
			<em style='color: grey;'>Also known as <strong><tag:person.nickname /></strong></em><br />
		</if:name>
	</if:editMode>
	
	<if:editMode>
		Miscellaneous facts about yourself:<br />
		<if:facts>
			<textarea style='height: 110px; width: 600px; margin-left: 10px; margin-top: 10px;' name="facts" id="facts"><tag:facts /></textarea><br />
		<else:facts>
			<textarea style='height: 110px; width: 600px; margin-left: 10px; margin-top: 10px; color: grey; font-family: Tahoma; Verdana;'
			name="facts" id="facts" onfocus="profile_clear();");">Use this box to provide brief information about yourself, such as:
Favourite Game: Trosnoth
ÜberTweak Count: 3
Nerf Guns Owned: 17
Check leader profiles for other examples!</textarea><br />
		</if:facts>
	<else:editMode>
		<if:facts><tag:factBreak />
			<loop:facts>
				<tag:facts[] /><br />
			</loop:facts>
		</if:facts>
	</if:editMode>
	
	<if:editMode>
		A short passage about yourself:<br />
		<textarea style='height: 150px; width: 600px; margin-left: 10px; margin-top: 10px;' name="about"><tag:person.about /></textarea>
	<else:editMode>
		<if:about>
			<div class="newprofileAbout">
				<tag:person.about />
			</div>
		</if:about>
	</if:editMode>
	
	<if:noInfo>
		<if:allowedToEdit>
			You have not yet entered any information!<br />
			Although you are not required to create a profile for yourself, it is strongly recommended.
			<if:leader><br />Especially for leaders!</if:leader>
		<else:allowedToEdit>
			This <tag:person.category /> has not entered any information!<br />
			Perhaps you should ask them nicely to do so.<br /><br />
		</if:allowedToEdit>
	</if:noInfo>
	
	<if:editMode>
		<br /><input type="submit" value="Save Profile" style='font-size: large;' class="button" />
		</form>
	<else:editMode>
		<if:photos>
		<tag:photoBreak /><strong>Photos:</strong>
		<a href="photos.php?username=<tag:person.id />" style='font-size: small;' class='pollLink'>
		<if:excessPhotos>
			+ <tag:excessPhotos /> more in photo gallery
		<else:excessPhotos>
			view in photo gallery
		</if:excessPhotos></a><br />
			<loop:photos>
			<div class="newprofileSmallPhoto">
				<a href='/photo/<tag:photos[].filename />'>
					<img src="/photos/cache/<tag:photos[].thumbnail />" />
				</a>
				<div class="desc"><tag:photos[].filename /></div>
			</div>
			</loop:photos>
		</if:photos>
	</if:editMode>
	
	</if:contactMode>
	
</div>

</div>
