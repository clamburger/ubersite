<if:wget>
	<h2>Welcome:</h2>
<else:wget>
	<h2>Welcome <tag:currentName />:</h2>
</if:wget>

<if:wget>
<h3>Welcome to the Camp Website - now on DVD</h3>
This is the final version of the Camp Website from <tag:campname /> <tag:campyear />: it's almost exactly the same except you won't be able to add new captions, quotes or do other things that require you to fill in forms. The good news is that you're still able to enjoy all of the content that was there before, and you don't even need to log in! In order to maintain a complete archive of the site, all of the old announcements are below, even though they obviously aren't relevant any more. Enjoy the website!
<hr />
</if:wget>
<loop:announcements>
<if:wget>
<h3><tag:announcements[].Title /></h3>
<else:wget>
<h3><tag:announcements[].Title /> <small> - <tag:announcements[].DateStr /></small></h3>
</if:wget>
<tag:announcements[].Announcement />
<hr />
</loop:announcements>
