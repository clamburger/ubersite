<h2><tag:header /></h2>
<table style='float: right;' class='timetable'>
<tr><th colspan='2'>Questionnaire Progress:</th></tr>
<tr><td>1. General Feedback</td><tag:stage1Progress /></tr>
<tr><td>2. Activity Feedback</td><tag:stage2Progress /></tr>
<tr><td>3. Elective Feedback</td><tag:stage3Progress /></tr>
<tr><td>4. Final Comments</td><tag:stage4Progress /></tr>
<if:deleteButton>
<tr><td colspan='2'><a href='questionnaire.php?delete'>Delete current progress</a></td></tr>
</if:deleteButton>
<if:leader>
<tr><td colspan='2'><a href='questionnaire-check.php' style='color: maroon;'>Check camper progress</a></td></tr>
</if:leader>
</table>
<if:stage0>
At the end of each &Uuml;berTweak we get all campers to fill out a questionnaire about camp.
Your feedback is extremely useful and all of the leaders spend time after camp reviewing it to ensure that the next &Uuml;berTweak is better than ever!<br /><br />
We encourage you to be completely honest: if you had a terrible time and hated all the leaders, make sure you tell us that. We will not hold any of this feedback against you.<br /><br />
The questionnairre is broken up into a number of sections. Take as much time as you need for each section and please be completely honest! Keep in mind that once you complete a section you <strong>cannot go back to that section</strong>.<br /><br />
<if:friday>
Your specimen has been processed and we are now ready to begin the test proper.<br /><br />
<h3><a href='questionnaire.php?begin'>Begin the Questionnaire</a></h3>
<if:admin>
<h3><a href='questionnaire-update.php' style='color: maroon;'>Synchronise Questionnaire Tables</a></h3>
</if:admin>
<else:friday>
<strong>The questionnaire isn't enabled yet!</strong> Come back on Friday.
</if:friday>
</if:stage0>
<if:stage1>
<form action="questionnaire.php" method="post">
<input type="hidden" name="stage" value="1" />
<h3>Camp:</h3>
What sort of time did you have on camp?
<ul class="question">
	<li><input type="radio" name="timeOnCamp" value="1" />&Uuml;ber!</li>
	<li><input type="radio" name="timeOnCamp" value="2" />Awesome</li>
	<li><input type="radio" name="timeOnCamp" value="3" />Great</li>
	<li><input type="radio" name="timeOnCamp" value="4" />Average</li>
	<li><input type="radio" name="timeOnCamp" value="5" />Lousy</li>
	<li><input type="radio" name="timeOnCamp" value="6" />Terrible</li>
</ul>
	
What did you like the most about camp? <br/>
<textarea name="Most"><tag:submittedvalues.Most /></textarea>

What did you like the least about camp? <br/>
<textarea name="Least" /><tag:submittedvalues.Least /></textarea>

If we could have the time over, what should we change? <br/>
<textarea name="TimeOver" /><tag:submittedvalues.TimeOver /></textarea>

Were the leaders supportive and did they listen to your needs?
<ul class="question">
	<li><input type="radio" name="leaderQuality" value="1" />Always</li>
	<li><input type="radio" name="leaderQuality" value="2" />Usually</li>
	<li><input type="radio" name="leaderQuality" value="3" />Sometimes</li>
	<li><input type="radio" name="leaderQuality" value="4" />Rarely</li>
	<li><input type="radio" name="leaderQuality" value="5" />Never</li>
</ul>

<!-- Who was your favourite leader?
<ul class="question">
	<li><input type="text" name="FavouriteLeader" value="<tag:submittedvalues.FavouriteLeader />" /></li>
</ul> -->

Indicate your position in relation to Christianity:
<ul class="question">
	<li><input type="radio" name="god" value="1" />I am not a Christian and have no interest in learning about Christianity</li>
	<li><input type="radio" name="god" value="2" />I am not a Christian, but I am interested in learning more</li>
	<li><input type="radio" name="god" value="3" />I've been slack trusting Christ, but at this camp I've turned back to Christ as my Lord</li>
	<li><input type="radio" name="god" value="4" />I've become a Christian at this camp and Jesus is my Lord and Saviour</li>
	<li><input type="radio" name="god" value="5" />I'm already trusting and following the Lord Jesus Christ</li>
	<li><input type="radio" name="god" value="6" />None of the above accurately describe me (enter details below)
		<ul class="question"><li><li><textarea name="godcomment"><tag:submittedvalues.GodComment /></textarea></li></li></ul>
	</li>
</ul>

Where did you first hear about camp this year?<br/>
<ul class="question">
 	<li><input type="radio" name="hear" value="1" />Flyer / Poster</li>
 	<li><input type="radio" name="hear" value="2" />SU Qld Website / Camp Brochure</li>
 	<li><input type="radio" name="hear" value="3" />UberTweak Website</li>
 	<li><input type="radio" name="hear" value="4" />School Chaplain</li>
 	<li><input type="radio" name="hear" value="5" />Church / Youth Group</li>
 	<li><input type="radio" name="hear" value="6" />Friend</li>
 	<li><input type="radio" name="hear" value="7" />Been on &Uuml;berTweak before</li>
 	<li><input type="radio" name="hear" value="8" />Other. Please specify: 
 		<ul class="question"><li><li><input name="hearcomment" style="width:25%" value="<tag:submittedvalues.HearComment />" /></li></li></ul>
	</li>
</ul>

Did you see a Flyer or Poster?
<ul class="question">
 	<li><input type="radio" name="posters" value="1" />Yes</li>
 	<li><input type="radio" name="posters" value="2" />No</li>
</ul>

Where do you think it would be best to advertise camp next year?
<ul class="question">
	<li><input name="othercomment" style="width:25%" value="<tag:submittedvalues.OtherComment />" /></li>
</ul>
	
<input type="submit" value="Submit your responses and move onto the next section" style="font-size: 150%;" />
</if:stage1>

<if:stage2>
<form action="questionnaire.php" method="post">
<input type="hidden" name="stage" value="2" />
<strong style='font-size: 125%;'>In the following section, 5 is the highest rating and 1 is the lowest rating!</strong><br /><br />

<loop:stage2Questions>
<h3><tag:stage2Questions[].name /></h3>
<table class='questionTable'>
<loop:stage2Questions[].questions>
<tr>
	<td><tag:stage2Questions[].questions[].id />. &nbsp;<tag:stage2Questions[].questions[].name /></td>
	<td><select name="<tag:stage2Questions[].id /><tag:stage2Questions[].questions[].id />">
		<tag:stage2Questions[].questions[].answers />
		</select></td>
</tr>
</loop:stage2Questions[].questions>
</table>

Comments:
<textarea name="<tag:stage2Questions[].id />Comments" style='margin-bottom: 20px;' /></textarea>

</loop:stage2Questions>

<input type="submit" value="Submit these responses and move onto the next section" style="font-size: 150%;" />
</form>
</if:stage2>

<if:stage3>
<form action="questionnaire.php" method="post">
<input type="hidden" name="stage" value="3" />
<strong style='font-size: 125%;'>In the following section, 5 is the highest rating and 1 is the lowest rating!</strong><br /><br />
You are not required to provide feedback for <em>all</em> of the electives: just provide feedback for the ones you participated in.<br /><br />

<loop:stage3Questions>
<h3><tag:stage3Questions[].name /></h3>
<div class="optquest" id="<tag:stage3Questions[].id />">
<a href="javascript:{}" onclick="questionnaire_toggle(this, '<tag:stage3Questions[].type />')">Did this <tag:stage3Questions[].type /> elective, click to expand:</a>
<table class='questionTable' style='margin-top: 8px;'>
<tr>
	<td>1. &nbsp;How much did you enjoy the <tag:stage3Questions[].name /> sessions?</td>
	<td><select name="<tag:stage3Questions[].id />1" style="margin-left:25px;display:inline;clear:left;">
			<tag:five />
		</select></td>
</tr>
<tr>
	<td>2. &nbsp;How much did you learn from the sessions?</td>
	<td><select name="<tag:stage3Questions[].id />2" style="margin-left:25px;display:inline;clear:left;">
			<tag:five />
		</select></td>
</tr>
</table>
Comments:
<textarea name="<tag:stage3Questions[].id />Comments" /></textarea>   
</div> 
</loop:stage3Questions>

<h3>Electives in General</h3>
<table class='questionTable'>
<tr>
	<td>1. &nbsp;In general, how much did you enjoy the elective sessions?</td>
	<td><select name="ElectivesGeneral1" value="<tag:submittedvalues.ElectivesGeneral1 />">
		<tag:five />
	</select></td>
</tr>
<tr>
	<td>2. &nbsp;Do you think there was enough variety in the electives available?</td>
	<td><select name="ElectivesGeneral2" value="<tag:submittedvalues.ElectivesGeneral2 />">
		<option value="0">--</option>
		<option value="3" style='background-color: #63BE7B;'>Yes</option>
		<option value="2" style='background-color: #FFEB84;'>Kind of</option>
		<option value="1" style='background-color: #F8696B;'>No</option>
	</select></td>
</tr>
<tr>
	<td>3. &nbsp;Did you think the elective sessions were of appropriate length?</td>
	<td><select name="ElectivesGeneral3" value="<tag:submittedvalues.ElectivesGeneral3/>">
		<tag:five />
	</select></td>
</tr>
</table>
<input type="submit" value="Submit these responses and move onto the final section" style="font-size: 150%;" />
</form>
</if:stage3>

<if:stage4>
<form action="questionnaire.php" method="post">
<input type="hidden" name="stage" value="4" />
Are there any activities that we should not do next year?
<textarea name="notDoComments" /><tag:submittedvalues.NotDoComments /></textarea> 

Do you have any suggestions on what Theme / Give-Away / New Elective Workshops we have can next year? 
<textarea name="themecomments" /><tag:submittedvalues.ThemeComments /></textarea> 

Do you have any general comments or suggestions for how we can make &Uuml;berTweak better again? 
<textarea name="generalcomments" /><tag:submittedvalues.GeneralComments /></textarea> 

Would you like us to send you Flyers for upcoming &Uuml;berTweaks?
<ul class="question">
 	<li><input type="radio" name="postersYes" value="1" />Yes</li>
 	<li><input type="radio" name="postersYes" value="0" />No</li>
</ul>

<!-- Please use this textbox to write at least 100 words of prose about Jake, Alan and Thomas's beards. This question is required.<br /> -->
Are you interested in hearing about other SU camps?<br />
<textarea name="beards" /><tag:submittedvalues.Beards /></textarea> 

<input type="submit" value="Submit these responses and finish the questionnaire!" style="font-size: 150%;" />
</form>
</if:stage4>

<if:stage5>
<h3>Your questionnaire has been submitted!</h3>
<h3>Thank you for all your responses! <br />
&nbsp;&nbsp;&nbsp;<tag:directors /> and <br />
&nbsp;&nbsp;&nbsp;The <tag:campname /> Team</h3>
If you made a mistake that you would like to correct, please contact the nearest leader for assistance.
</if:stage5>
