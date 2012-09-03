<h2>Timetable:</h2>
<if:timetable>
Here is the planned timetable for the camp. Actual schedule may vary!<br/>
<br/>
<table class="timetable">
  <tr>
    <th width="5%">Time</th>
    <loop:days>
    <th width="16%"><tag:days[] /></th>
    </loop:days>
  </tr>
  <loop:timetable>
  <tr>
    <tag:timetable[].0 />
    <tag:timetable[].1 />
    <tag:timetable[].2 />
    <tag:timetable[].3 />
    <tag:timetable[].4 />
    <tag:timetable[].5 />
    <tag:timetable[].6 />
  </tr>
  </loop:timetable>
</table>
<else:timetable>
No timetable data currently exists.
</if:timetable>
