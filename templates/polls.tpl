<if:create>
  This is where people can create new polls. Be creative! Previous poll questions include:
  <ul>
  <li>It's dangerous to go alone. What do you take?</li>
  <li>Who has the best beard on camp?</li>
  <li>Which [Starcraft] race is superior?</li>
  </ul>
  There's no rule saying you can't use a poll question from a previous
  camp but it's nice if most of the polls are different. Be careful of
  your spelling: once your poll has been created you won't be able to
  change the question or responses without asking a tech leader.<br />
  Poll questions and responses are limited to 50 characters, although in
  practice you should keep the responses a lot shorter. There is no limit
  to the number of responses that a poll can have, but try not to be
  ridiculous.<br /><br />
  <if:camper>
  <strong>
    A leader will have to approve your poll before people can vote on it.
  </strong> This will usually be done within a few minutes.<br /><br />
  </if:camper>
  <form name="create" action="polls.php?create" method="POST">
  <h3>Poll Question:</h3>
  <input type="text" name="question" size="50" maxlength="50" style='font-size: 140%;' value='<tag:new-question />'/>
  <h3>Responses: <small>(one per line)</small></h3>
  <textarea name="responses" style='width: 300px; height: 100px;'><tag:new-responses /></textarea>
  <h3><label for="multiple">Multiple choice: </label><input type="checkbox" id="multiple" name="multiple" /></h3>
  <h3><label for="hideResults">Hide results: </label><input type="checkbox" id="hideResults" name="hideResults" /></h3>
  <input type="hidden" name="creator" value="<tag:new-creator />" />
  <input type="submit" value="Create Poll" />
  </form>

<else:create>
  <if:moderate>
    <h3>Moderation Guidelines:</h3>
    <ul>
      <li>One guideline above all others: <strong>use common sense</strong>.</li>
      <li>Do not decline polls due to incredibly poor spelling and/or grammar. It can always be changed later by a tech leader.</li>
      <li>You should only use <strong>Delete</strong> if the poll is nothing but random characters or something stupid like that (or if it's a duplicate of another poll).<br />Use <strong>Decline</strong> in all other cases (apart from approving the poll, of course.)</li>
    </ul>
    
    <h3>The Poll:</h3>
    
    <table class="ladder">
    <tr>
      <th><tag:question /></th>
    </tr>
    <tr>
      <td style="text-align:left; border-bottom: none;">
        <loop:options>
          <input type="radio" value="<tag:options[].id />" id="<tag:options[].id />" disabled name="response">
          <label for="<tag:options[].id />"><tag:options[].text /></label><br />
        </loop:options>
      </td>
    </tr>
    <tr>
      <td style='border-top: none;'>
        <input type="button" value="Approve" style="margin: 7px; background-color: #b7ffb7; border-color: #008000"
          onClick="location.href='?approve=<tag:pollID />'"/>
        <input type="button" value="Decline" style="margin: 7px; background-color: #ffdbb7; border-color: #D09900"
          onClick="location.href='?decline=<tag:pollID />'"/>
        <input type="button" value="Delete" style="margin: 7px; background-color: #ffb7b7; border-color: #D00000"
          onClick="location.href='?delete=<tag:pollID />'"/>
<br />
        Poll Creator: <tag:creator />
      </td>
    </tr>
    </table>
    
    <h3>Go Back:</h3>
    
    <ul>
      <li><a href="?">Back to the other polls</a></li>
    </ul>

  <else:moderate>

    <if:poll>
    
      <!if:hidden>
    
      <script type="text/javascript">

      var chart;
      
      $(document).ready(function() {
        chart = new Highcharts.Chart({
          credits: {
            enabled: false,
          },
          chart: {
            animation: false,
            renderTo: 'graph',
            plotBorderWidth: 0,
            reflow: false,
            height: 296,
            spacingBottom: 0,
            spacingTop: 4,
            spacingLeft: 4,
            spacingRight: 4
          },
          title: null,
          tooltip: false,
          plotOptions: {
            pie: {
              allowPointSelect: false,
              dataLabels: {
                enabled: true,
                color: '#000000',
                connectorColor: '#000000',
                formatter: function() {
                  if (this.y == 0) {
                    return '';
                  } else {
                    return '<b>'+ this.point.name +'</b>: '+ this.y + '<br />(' + Highcharts.numberFormat(this.percentage, 2) + '%)';
                  }
                }
              }
            },
            series: {
              states: {
                hover: {
                  enabled: false
                }
              }
            }
          },
          series: [{
            type: 'pie',
            name: null,
            data: [
              <tag:graphData />
            ]
          }]
        });
        
        console.log(chart);
        
        var len = chart.series[0].data.length - 1;
        for (var i = len; i >= 0; i--) {
          if (chart.series[0].data[i].y == 0) {
            chart.series[0].data[i].remove();
          }
        }
        
      });
   
      </script>
      
      </!if:hidden>

      <h2>Poll: <tag:question /></h2>
      <table class="ladder" style='width: 98%;'>
      <if:voted>
        <tr>
          <th width='50%'>Result</th>
          <th width='50%'>People who Voted</th>
        </tr>
        <tr>
          <td id="graph" style="height: 300px; padding: 0px;">
          <if:hidden>
          The results of this poll are hidden!
          </if:hidden>
          </td>
          <td rowspan="2">
            <table class="ladder" style='width: 100%; margin: auto;'>
            <loop:options>
            <tr>
              <th width='120px'><tag:options[].text /> (<tag:options[].count />)</th>
              <td><tag:options[].people /></td>
            </tr>
            </loop:options>
            <!if:wget>
              <tr>
              <if:preview>
                <td colspan="2">You have not yet voted on this poll. <a href='?id=<tag:pollid />'>Back to voting page.</a></td>
              <else:preview>
                <td colspan="2"><a href='?id=<tag:pollid />&reset'>Change my vote.</a>
                (Clicking this link will delete your current vote.)</td>
              </if:preview>
              </tr>
            </!if:wget>
            </table>
          </td>
        </tr>
        <tr style='height: 30px;'>
          <td>This poll was created by <tag:creator />!</td>
        </tr>
      <else:voted>
        <tr>
          <th><tag:question /></th>
        </tr>
        <tr>
          <td style="text-align:left;">
            <form name="<tag:pollid />" action="polls.php?id=<tag:pollid />" method="POST">
              <loop:options>
                <if:multiple>
                <input type="checkbox" value="<tag:options[].id />" id="<tag:options[].id />" name="response[]">
                <else:multiple>
                <input type="radio" value="<tag:options[].id />" id="<tag:options[].id />" name="response">
                </if:multiple>
                <label for="<tag:options[].id />"><tag:options[].text /></label><br />
              </loop:options>
              <input type="submit" value="Vote" style="margin-top: 7px;" />
              <!if:hidden>
              <input type="button" value="View Results" onClick="location.href='?id=<tag:pollid />&preview'" />
              </!if:hidden>
            </form>
          </td>
        </tr>
      </if:voted>
      </table>
      <if:previous>
      <h3>Other Polls</h3>
      <ul>
        <loop:previous>
          <li><a href="polls.php?id=<tag:previous[].id />"><tag:previous[].question /></a></li>
        </loop:previous>
      </ul>
      </if:previous>
    <else:poll>
      There are not currently any polls to vote on! Check back later.
    </if:poll>
    <if:moderation>
    <h3>Polls that need approval</h3>
    <ul>
      <loop:moderation>
        <li><a href="polls.php?moderate=<tag:moderation[].id />" style='color: maroon;'>
        <tag:moderation[].question /></a> (by <tag:moderation[].creator />)</li>
      </loop:moderation>
    </ul>
    </if:moderation>
      
    <if:createLink>
      <h3>New Poll</h3>
      <ul>
        <li><a href="polls.php?create" style='color: green;'>Create a poll</a></li>
      </ul>
    </if:createLink>
  
  </if:moderate>
  
</if:create>
