<h2>Who's Who at &Uuml;berTweak:</h2>
Below is a list of all those here at &Uuml;berTweak along with some information about them.<br />
<br />
<div style='float: left; width: 30%;'>
<h3>Jump to:</h3>
<ul>
	<loop:categories>
		<if:categories[].display>
			<li><a href="#<tag:categories[].lower />"><tag:categories[].upper /></a></li>
		</if:categories[].display>
	</loop:categories>
</ul>
</div>
<div style='float: left; width: 500px;'>
<h3>Show Study Group:</h3>
<form name='dutyTeam' class='dutyTeam' method="GET" action="">
<u<tag:wgetUL />>
<loop:colours>
<if:wget>
<li><a href="?colour=<tag:colours[].html />" style='background-color: <tag:colours[].hex />;' class='textButton'><tag:colours[].name /></a></li>
<else:wget>
<input name='colour' type='submit' value="<tag:colours[].name />" style='background-color: <tag:colours[].hex />; color: <tag:colours[].fonthex />' />
</if:wget>
</loop:colours>
<if:wget>
<li><a href="?" class='textButton' style='background-color: black; color: white;'>Show All Activity Groups</a></li>
<else:wget>
<br />
<input type='submit' value='Show All Activity Groups' style='width: 488px; background-color: black; color: white;'/>
</if:wget>
</u<tag:wgetUL />>
</form>
<if:admin><if:contactDetails>
<h3><a href='contacts.php' style='color: maroon;'>View end-of-camp contact list</a></h3>
</if:contactDetails></if:admin>
</div>
<br clear='all'/>
<if:warcry>
<h3>Team War Cry:</h3>
<blockquote>
<tag:warcryText />
</blockquote>
</if:warcry>
<loop:everybody>
<if:everybody[].display>
<br clear="both" />
<h3 id="<tag:everybody[].lower />"><tag:everybody[].upper />:</h3>
<loop:everybody[].people>
    <div class="person" style='background-color: <tag:everybody[].people[].colour />'>
        <div class="personLeft">
            <img src="<tag:everybody[].people[].src />-thumb.jpg" style='height: 96px; width: 96px; <tag:everybody[].people[].bordercolour />' />
        </div>
        <div class="personRight" style='background-color: <tag:everybody[].people[].colour />; color: <tag:everybody[].people[].fontcolour />;'>
            <a href="person.php?id=<tag:everybody[].people[].id />"
			   style="<tag:everybody[].people[].linkcolour />"><tag:everybody[].people[].name /></a> - <tag:everybody[].people[].greek /><br/>
           <tag:everybody[].people[].desc />
        </div>
    </div>
</loop:everybody[].people>
</if:everybody[].display>
</loop:everybody>