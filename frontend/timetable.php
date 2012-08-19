<?php
  include_once("../includes/start.php");

  $title = 'Timetable';
  $tpl->set('title', $title);

  # Get the timetable information from the database.
  $query = "SELECT * FROM `timetable` WHERE `Visible` = 1 ORDER BY `Start` ASC";
  $res = do_query($query);

  if (mysql_num_rows($res) == 0) {
    $tpl->set('timetable', false);
  } else {

    # Create the timetable table with the times along the left.
    $days = array();
    foreach ($CAMP_DAYS as $day) {
      $days[$day] = count($days)+1;
    }
    $tpl->set('days', $CAMP_DAYS);

    $SLEEP_TIME = 23;
    $AWAKE_TIME = 6;

    $count = 0;
    $old = 0;
    $oldstart = "";
    $timetable = array_fill(0,($SLEEP_TIME-$AWAKE_TIME+0.5)*12,Array());
    for ($i = 0; $i < count($timetable); $i++) {
      if (($i%6)==0) {
        $timetable[$i][] = "<th rowspan='6'>".intval(6+($i/12)).":".sprintf("%02d",($i%12)*5)."</th>";
      }
    }

    # Black magic that figures out the duration of each event and gives each cell the correct contents and height.
    $hour = date("H");
    $time = date(":i");
    $time = (intval($hour)) . $time;
    while ($row = fetch_row($res)) {
      $duration = (intval(substr($row['End'],0,2))-intval(substr($row['Start'],0,2)))*12 + ((intval(substr($row['End'],3,2))-intval(substr($row['Start'],3,2)))/5);
      $duration = intval($duration);
      # If the day matches, the time is after the start time and the time is before the end time the event is active.
      $class = "";
      if ($row["Day"] == date("l") && strtotime($time) >= strtotime($row["Start"]) && strtotime($time) < strtotime($row["End"]) && !$wget) {
        $class = " class='active'";
      }

      $arrayKey = intval(substr($row['Start'],0,2)-6)*12 + intval(substr($row['Start'],3,2))/5;
      $timetable[$arrayKey][$days[$row["Day"]]] = "<td rowspan=$duration$class>${row['Activity']}</td>";

      $old = $row['Day'];
      $oldstart = $row['Start'];
    }
    $tpl->set('timetable', $timetable);
  }
  fetch();
?>
