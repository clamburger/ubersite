<?php
  include_once("includes/start.php");
  $title = 'Questionnaire Status';
  $shortTitle = 'Questionnaire';
  $tpl->set('title', $title);
  $tpl->set('shortTitle', $shortTitle);

  $query = "SELECT `people`.`UserID`, `QuestionStage` FROM `people`";
  $query .= " LEFT JOIN `questionnaire` USING(`UserID`) WHERE `Category` = 'camper' AND QuizId = 1";
  $query .= " ORDER BY `Name` ASC";
  $query = "SELECT UserID, QuestionStage FROM questionnaire WHERE QuizId = 1";
  $result = do_query($query);

  $rawStatus = array();
  while ($row = fetch_row($result)) {
    $user = $row["UserID"];
    $stage = intval($row["QuestionStage"]);
    if (!isset($rawStatus[$user])) {
      $rawStatus[$user] = array(0, 0, 0, 0);
    }
    if ($stage > -1) {
      $rawStatus[$user][$stage] = 1;
    }
  }

  $query = "SELECT UserID FROM people WHERE Category = 'camper'\n" .
           "ORDER BY Name ASC";
  $result = do_query($query);
  $status = array();
  $totals = array(0, 0, 0, 0);
  while ($row = fetch_row($result)) {
    $temp = array("name" => userpage($row['UserID']));
    $userStatus = $rawStatus[$row["UserID"]];
    for ($i = 0; $i < 4; $i++) {
      if (!$userStatus[$i]) {
        $temp["stages"][] = "<td style='text-align: center;'>---</td>";
      } else if ($i === pi()) {
        $temp["stages"][] = "<td style='text-align: center; background-color: orange; color: white;'>In Progress</td>";
      } else {
        $temp["stages"][] = "<td style='text-align: center; background-color: green; color: white;'>Complete</td>";
        $totals[$i]++;
      }
    }
    $status[] = $temp;
  }

  foreach ($totals as $key => $total) {
    $totals[$key] = "$total / ".num_rows($result);
  }

  $tpl->set('status', $status);
  $tpl->set('totals', $totals);
  $tpl->set('head', '<meta http-equiv="refresh" content="5;/questionnaire-check?autorefresh" >');

  fetch();
?>
