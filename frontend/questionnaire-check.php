<?php
  include_once("../includes/start.php");
  $title = 'Questionnaire Status';
  $shortTitle = 'Questionnaire';
  $tpl->set('title', $title);
  $tpl->set('shortTitle', $shortTitle);

  $query = "SELECT `people`.`UserID`, `QuestionStage` FROM `people`";
  $query .= " LEFT JOIN `questionnaire` USING(`UserID`) WHERE `Category` = 'camper'";
  $query .= " ORDER BY `Name` ASC";
  $result = do_query($query);

  $status = array();
  $totals = array("0", "0", "0", "0");
  while ($row = fetch_row($result)) {
    $temp = array("name" => userpage($row['UserID']));
    $stage = $row['QuestionStage'];
    for ($i = 1; $i <= 4; $i++) {
      if ($i > $stage) {
        $temp["stage$i"] = "<td style='text-align: center;'>---</td>";
      } else if ($i == $stage) {
        $temp["stage$i"] = "<td style='text-align: center; background-color: orange; color: white;'>In Progress</td>";
      } else {
        $temp["stage$i"] = "<td style='text-align: center; background-color: green; color: white;'>Complete</td>";
        $totals[$i-1]++;
      }
    }
    $status[] = $temp;
  }

  foreach ($totals as $key => $total) {
    $totals[$key] = "$total / ".num_rows($result);
  }

  $tpl->set('status', $status);
  $tpl->set('totals', $totals);
  $tpl->set('head', '<meta http-equiv="refresh" content="5;questionnaire-check.php?autorefresh" >');

  fetch();
?>
