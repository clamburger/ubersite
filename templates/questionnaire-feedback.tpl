<if:leader>
<h2>General Feedback:</h2>
<!if:wget>
<if:smallgroup>
<h3><a href="?">View feedback from all campers</a></h3>
<else:smallgroup>
<h3><a href="?smallgroup">View only feedback from your study group</a></h3>
</if:smallgroup>
</!if:wget>
<table>
<tr>
<th>Person</th>
<th style='width: 80px;'>Time on camp</th>
<th style='width: 80px;'>Leaders supportive?</th>
<th>Hearing about camp</th>
<th>Did you see posters?</th>
<th>Status in Christ</th>
<!-- <th>Favourite Leader</th> -->
<th>Send info about next camp?</th>
</tr>
<loop:numbers>
	<tr>
	<td><tag:numbers[].name /></td>
	<td><tag:numbers[].timeOnCamp /></td>
	<td><tag:numbers[].leaderQuality /></td>
	<td><tag:numbers[].hearingAboutCamp /></td>
	<td><tag:numbers[].posters /></td>
	<td><tag:numbers[].christ /></td>
	<!-- <td><tag:numbers[].favouriteLeader /></td> -->
	<td><tag:numbers[].sendInfo /></td>
	</tr>
</loop:numbers>	
</table>

<strong>What did you like the most about camp?</strong>
<ul>
<loop:Most>
    <li><tag:Most[] /></li>
</loop:Most>
</ul>

<strong>What did you like the least about camp?</strong>
<ul>
<loop:Least>
    <li><tag:Least[] /></li>
</loop:Least>
</ul>

<strong>If we could have the time over, what should we change?</strong>
<ul>
<loop:TimeOver>
    <li><tag:TimeOver[] /></li>
</loop:TimeOver>
</ul>
<strong>Where do you think it would be best to advertise camp next year?</strong> 
<ul>
<loop:OtherComment>
    <li><tag:OtherComment[] /></li>
</loop:OtherComment>
</ul>

<strong>Are there any activities that we should not do next year? </strong>
<ul>
<loop:NotDoComments>
    <li><tag:NotDoComments[] /></li>
</loop:NotDoComments>
</ul>

<strong>Do you have any suggestions on what Theme / Give-Away / New Elective Workshops we have can next year? </strong>
<ul>
<loop:ThemeComments>
    <li><tag:ThemeComments[] /></li>
</loop:ThemeComments>
</ul>

<strong>Do you have any general comments or suggestions for how we can make &Uuml;berTweak better again? </strong>
<ul>
<loop:GeneralComments>
    <li><tag:GeneralComments[] /></li>
</loop:GeneralComments>
</ul>

<strong>Are you interested in hearing about other SU camps?</strong>
<ul>
<loop:Beards>
    <li><tag:Beards[] /></li>
</loop:Beards>
</ul>

<h2>Activity Feedback:</h2>

<strong>Focus:</strong>
<table>
<tr>
<th>Camper</th>
<th>Interesting?</th>
<th>Relevant?</th>
<th>Challenging?</th>
<th>Length?</th>
<th>Interesting skits?</th>
</tr>
<loop:bible>
	<tr>
	<td><tag:bible[].name /></td>
	<td><tag:bible[].1 /></td>
	<td><tag:bible[].2 /></td>
	<td><tag:bible[].3 /></td>
	<td><tag:bible[].4 /></td>
	<td><tag:bible[].5 /></td>
	</tr>
</loop:bible>	
</table>

<ul>
<loop:BibleComments>
	<li><tag:BibleComments[] /></li>
</loop:BibleComments>
</ul>

<strong>Power Down:</strong>
<table>
<tr>
<th>Camper</th>
<th>Enjoyable testimonies?</th>
<th>Relevant testimonies?</th>
</tr>
<loop:power>
	<tr>
	<td><tag:power[].name /></td>
	<td><tag:power[].1 /></td>
	<td><tag:power[].2 /></td>
	</tr>
</loop:power>	
</table>

<ul>
<loop:PowerComments>
	<li><tag:PowerComments[] /></li>
</loop:PowerComments>
</ul>

<strong>Game Strategy:</strong>
<table>
<tr>
<th>Camper</th>
<th>Enjoyable?</th>
<th>Choice of games?</th>
<th>Length?</th>
<th>Helpful?</th>
</tr>
<loop:game>
	<tr>
	<td><tag:game[].name /></td>
	<td><tag:game[].1 /></td>
	<td><tag:game[].2 /></td>
	<td><tag:game[].3 /></td>
	<td><tag:game[].4 /></td>
	</tr>
</loop:game>	
</table>

<ul>
<loop:GameComments>
	<li><tag:GameComments[] /></li>
</loop:GameComments>
</ul>

<strong>Outdoor Games:</strong>
<table>
<tr>
<th>Camper</th>
<th>Enjoyable?</th>
<th>Clear rules?</th>
<th>Enough time devoted?</th>
</tr>
<loop:outdoor>
	<tr>
	<td><tag:outdoor[].name /></td>
	<td><tag:outdoor[].1 /></td>
	<td><tag:outdoor[].2 /></td>
	<td><tag:outdoor[].3 /></td>
	</tr>
</loop:outdoor>	
</table>

<ul>
<loop:OutdoorComments>
	<li><tag:OutdoorComments[] /></li>
</loop:OutdoorComments>
</ul>

<strong>Camp Website:</strong>
<table>
<tr>
<th>Camper</th>
<th>Did you like it?</th>
<th>Ease of use?</th>
<th>How much did you use it?</th>
</tr>
<loop:website>
	<tr>
	<td><tag:website[].name /></td>
	<td><tag:website[].1 /></td>
	<td><tag:website[].2 /></td>
	<td><tag:website[].3 /></td>
	</tr>
</loop:website>	
</table>

<ul>
<loop:WebsiteComments>
	<li><tag:WebsiteComments[] /></li>
</loop:WebsiteComments>
</ul>

<strong>Show Night:</strong>
<table>
<tr>
<th>Camper</th>
<th>Did you like it?</th>
<th>Length?</th>
</tr>
<loop:showNight>
	<tr>
	<td><tag:showNight[].name /></td>
	<td><tag:showNight[].1 /></td>
	<td><tag:showNight[].2 /></td>
	</tr>
</loop:showNight>	
</table>

<ul>
<loop:ShowNightComments>
	<li><tag:ShowNightComments[] /></li>
</loop:ShowNightComments>
</ul>


<strong>Electives in General:</strong>
<table>
<tr>
<th>Camper</th>
<th>Enjoyable?</th>
<th>Variety?</th>
<th>Length?</th>
</tr>
<loop:electivesGeneral>
	<tr>
	<td><tag:electivesGeneral[].name /></td>
	<td><tag:electivesGeneral[].1 /></td>
	<td><tag:electivesGeneral[].2 /></td>
	<td><tag:electivesGeneral[].3 /></td>
	</tr>
</loop:electivesGeneral>	
</table>

<h2>Elective Feedback:</h2>

<loop:electives>

<strong><tag:electives[].name /></strong>
<table>
<tr>
<th>Camper</th>
<th>Enjoyable?</th>
<th>Learned things?</th>
</tr>
<loop:electives[].data>
	<tr>
	<td><tag:electives[].data[].name /></td>
	<td><tag:electives[].data[].1 /></td>
	<td><tag:electives[].data[].2 /></td>
	</tr>
</loop:electives[].data>
</table>

<ul>
<loop:electives[].comments>
	<li><tag:electives[].comments[] /></li>
</loop:electives[].comments>
</ul>

</loop:electives>
<else:leader>
<h2>Questionairre Feedback:</h2>
You must be a leader to see this page.
</if:leader>