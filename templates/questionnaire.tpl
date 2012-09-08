<h2><tag:header /></h2>
<table style='float: right;' class='progress'>
<tr><th colspan='2'>Questionnaire Progress:</th></tr>
<loop:progress><tr><tag:progress[] /></tr>
</loop:progress>
<if:deleteButton>
<tr><td colspan='2'><a href='questionnaire.php?delete'>Delete current progress</a></td></tr>
</if:deleteButton>
<if:leader>
<tr><td colspan='2'><a href='questionnaire-check.php' style='color: maroon;'>Check camper progress</a></td></tr>
</if:leader>
</table>

<if:start>
<tag:intro />
<h3><a href='?begin'>Begin the Questionnaire</a></h3>
<if:admin>
<h3><a href='/questionnaire-update.php' style='color: maroon;'>Synchronise Questionnaire Tables</a></h3>
</if:admin>
</if:start>

<if:questions>
<form action="" method="POST">
<input type="hidden" name="stage" value="<tag:stage />" />
<tag:questions />
<input type="submit"
    value="Submit your responses and move onto the next section"
    style="font-size:150%;margin-top:20px;" />
</form>
</if:questions>

<if:end>
<tag:outro />
</if:end>
