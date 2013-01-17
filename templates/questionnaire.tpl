<table style='float: right;' class='progress'>
<tr><th colspan='2'>Questionnaire Progress:</th></tr>
<loop:progress><tr><tag:progress[] /></tr>
</loop:progress>
<if:deleteButton>
<tr><td colspan='2'><a href='/questionnaire/<tag:ID />/delete'>Delete current progress</a></td></tr>
</if:deleteButton>
<if:leader>
<tr><td colspan='2'><a href='/questionnaire-check/<tag:ID />' style='color: maroon;'>Check camper progress</a></td></tr>
</if:leader>
</table>

<if:start>
<tag:intro />
<br>
<br>
<h3><a href='/questionnaire/<tag:ID />/begin'>Begin the Questionnaire</a></h3>
<if:admin>
<h3><a href='/questionnaire-update' style='color: maroon;'>Synchronise Questionnaire Tables</a></h3>
</if:admin>
</if:start>

<if:questions>
<form action="/questionnaire/<tag:ID />" method="POST">
<input type="hidden" name="stage" value="<tag:stage />" />
<tag:questions />
<input type="submit"
    value="Submit your responses and move onto the next section"
    style="font-size:150%;" />
</form>
</if:questions>

<if:end>
<tag:outro />
</if:end>

<script type="text/javascript">
  $(".optquest legend").click(function (event) {
    event.preventDefault();
    $(this).next().toggle();
    if ($(this).next().is(":visible")) {
      $(this).find(".help").text("click to hide questions");
    } else {
      $(this).find(".help").text("click to view questions");
    }
  });
</script>
