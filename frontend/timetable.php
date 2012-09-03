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
    $SLEEP_TIME = 23;
    $AWAKE_TIME = 6;
    $timetable = array_fill(0, ($SLEEP_TIME-$AWAKE_TIME+0.5)*2, Array());
    foreach ($CAMP_DAYS as $day) {
      $days[$day] = count($days)+1;
      $timetable[0][$days[$day]] = "<td rowspan='35'>";
    }
    $tpl->set('days', $CAMP_DAYS);


    $count = 0;
    $old = 0;
    $oldstart = "";
    for ($i = 0; $i < count($timetable); $i++) {
      $timetable[$i][0] = "<th>".intval(6+($i/2)).":".sprintf("%02d",($i%2)*30)."</th>";
    }

    # Black magic that figures out the duration of each event and gives each
    # cell the correct contents and height.
    $hour = date("H");
    $time = date(":i");
    $time = (intval($hour)) . $time;
    while ($row = fetch_row($res)) {
      $last = false;
      $startMin = intval(substr($row['Start'],0,2)) * 60 + intval(
          substr($row['Start'],3,2));
      if ($startMin < $AWAKE_TIME*60) {
        $startMin = $AWAKE_TIME*60;
      }
      $endMin = intval(substr($row['End'],0,2)) * 60 + intval(
          substr($row['End'],3,2));
      if ($endMin > $SLEEP_TIME*60) {
        $endMin = $SLEEP_TIME*60 + 29;
        $last = true;
      }
      $duration = ($endMin - $startMin);
      # If the day matches, the time is after the start time and the time is
      # before the end time the event is active.
      $class = "";
      if ($row["Day"] == date("l") &&
          strtotime($time) >= strtotime($row["Start"]) &&
          strtotime($time) < strtotime($row["End"]) && !$wget) {
        $class = " class='active'";
      }

      $arrayKey = ($startMin - 6*60) / 5;
      $always = 2;
      #$always -= ($duration / 30) * 2;
      $height = $duration - $always;
      $half = intval(($height - 18) / 2);
      $height -= $half;

      $timetable[0][$days[$row["Day"]]] .=
          "<div style=\"padding:${half}px 0 0 0;height:${height}px;" .
          ($last ? "border-bottom:0;" : "") .
          "\"$class>${row['Activity']}</div>";

      $old = $row['Day'];
      $oldstart = $row['Start'];
    }
    foreach ($days as $day => $v) {
      $timetable[0][$v] .= "</td>";
    }
    $tpl->set('timetable', $timetable);
  }
  fetch();
?>
