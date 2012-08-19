<?php
  include_once("../includes/start.php");
  $title = 'Page View Analysis';
  $tpl->set('title', $title);

  $colours = array("photos" => "color: green; font-weight: bold;",
                   "view-photo" => "color: green;",
                   "polls" => "color: green; font-weight: bold;",
                   "quotes" => "color: green; font-weight: bold;",
                   "suggestions" => "color: green; font-weight: bold;",
                   "trosnoth" => "color: green; font-weight: bold;",
                   "login" => "text-decoration: line-through; font-size: smaller;",
                   "questionnaire-feedback" => "color: red;",
                   "questionnaire-check" => "color: red;",
                   "accounts" => "color: red;",
                   "questionnaire" => "color: blue;",
                   "profiles" => "color: purple; font-weight: bold;",
                   "recent-changes" => "color: purple; font-weight: bold;",
                   "person" => "color: purple;",
                   "change-password" => "text-decoration: line-through; font-size: smaller;",

                   "camper" => "color: green;",
                   "leader" => "color: blue;",
                   "director" => "color: red;");

  $order = array("everybody1", "everybody2",
                 "leaders1", "leaders2", "leaders3",
                 "campers1", "campers2", "campers3");

  $conditional = "`UserID` NOT IN (SELECT `UserID` FROM `people_groups` WHERE `Group` = 'stats-exclude')";

  # Everybody except for me and the testing accounts.
  $query = "SELECT `Page`, COUNT(*) as `Count` FROM `access` WHERE $conditional GROUP BY `Page` ORDER BY `Count` DESC";
  $queries[] = $query;

  $query = "SELECT `Page`, COUNT(DISTINCT `UserID`) as `Count` FROM `access` WHERE $conditional GROUP BY `Page` ORDER BY `Count` DESC";
  $queries[] = $query;

  /*$query = "SELECT `Name`, `Category`, COUNT(*) as `Count` FROM `access` INNER JOIN `people` USING (`UserID`) ";
  $query .= " WHERE `UserID` != 'samuelh' AND `UserID` != 'leader' AND `UserID` != 'camper' ";
  $query .= "GROUP BY `UserID` ORDER BY `Count` DESC";
  $queries[] = $query;*/

  # Just the leaders (same exclusions apply).
  $query = "SELECT `Page`, COUNT(*) as `Count` FROM `access` INNER JOIN `people` USING (`UserID`) ";
  $query .= "WHERE $conditional AND `Category` != 'camper' ";
  $query .= "GROUP BY `Page` ORDER BY `Count` DESC";
  $queries[] = $query;

  $query = "SELECT `Page`, COUNT(DISTINCT `UserID`) as `Count` FROM `access` INNER JOIN `people` USING (`UserID`)";
  $query .= "WHERE $conditional AND `Category` != 'camper' ";
  $query .= "GROUP BY `Page` ORDER BY `Count` DESC";
  $queries[] = $query;

  $query = "SELECT `Name`, `Category`, COUNT(*) as `Count` FROM `access` INNER JOIN `people` USING (`UserID`) ";
  $query .= "WHERE $conditional AND `Category` != 'camper' ";
  $query .= "GROUP BY `UserID` ORDER BY `Count` DESC";
  $queries[] = $query;

  # Just the campers.
  $query = "SELECT `Page`, COUNT(*) as `Count` FROM `access` INNER JOIN `people` USING (`UserID`) ";
  $query .= "WHERE $conditional AND `Category` = 'camper' ";
  $query .= "GROUP BY `Page` ORDER BY `Count` DESC";
  $queries[] = $query;

  $query = "SELECT `Page`, COUNT(DISTINCT `UserID`) as `Count` FROM `access` INNER JOIN `people` USING (`UserID`)";
  $query .= "WHERE $conditional AND `Category` = 'camper' ";
  $query .= "GROUP BY `Page` ORDER BY `Count` DESC";
  $queries[] = $query;

  $query = "SELECT `Name`, `Category`, COUNT(*) as `Count` FROM `access` INNER JOIN `people` USING (`UserID`) ";
  $query .= "WHERE $conditional AND `Category` = 'camper' ";
  $query .= "GROUP BY `UserID` ORDER BY `Count` DESC";
  $queries[] = $query;

  foreach ($queries as $key => $query) {
    $result = do_query($query);
    $results = array();
    while ($row = fetch_row($result)) {
      if (isset($row['Page'])) {
        if (isset($colours[$row['Page']])) {
          $style = $colours[$row['Page']];
        } else {
          $style = "color: grey;";
        }
        $row['Page'] = "<span style='$style'>{$row['Page']}</span>";
      } else {
        if (isset($colours[$row['Category']])) {
          $style = $colours[$row['Category']];
        } else {
          $style = "";
        }
        $row['Name'] = "<span style='$style'>{$row['Name']}</span>";
      }
      $results[] = $row;
    }
    $tpl->set($order[$key], $results);
  }

  $query = "SELECT `UserID`, `Page`, COUNT(*) as `Count`, `Category`, `Name` FROM `access` INNER JOIN `people` USING (`UserID`) ";
  $query .= "WHERE $conditional GROUP BY `UserID`, `Page` ORDER BY `UserID`, `Count` DESC";
  $result = do_query($query);
  $results = array();
  while ($row = fetch_row($result)) {
    $results[] = $row;
  }

  $usersDone = array();
  $details = array();

  foreach ($results as $data) {
    if (!isset($usersDone[$data['UserID']])) {
      if (isset($colours[$data['Page']])) {
        $style = $colours[$data['Page']];
      } else {
        $style = "color: grey;";
      }
      $data['Page'] = "<span style='$style'>{$data['Page']}</span>";
      if ($data['Category'] == "leader") {
        $data['Name'] = "<em>{$data['Name']}</em>";
      }
      $details[] = array("Name" => $data['Name'],
                "Page" => $data['Page'],
                "Count" => $data['Count']);
      $usersDone[$data['UserID']] = true;
    }
  }

  $tpl->set('everybody3', $details);
  fetch();
?>
