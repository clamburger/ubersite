<?php
  include_once("includes/start.php");
  $title = 'Trosnoth';
  $tpl->set('title', $title);

  if ($SEGMENTS[1] == "stats" || ($wget && !$SEGMENTS[1])) {
    $result = do_query("SELECT `Name` FROM `achievement_list` WHERE `Disabled` = 0");
    $achieveCount = num_rows($result);

    $achieveList = $leaderboard = $top = $ranks = array();

    while ($row = fetch_row($result)) {
      $achieveList[$row['Name']] = 0;
    }

    $query = "SELECT `UserID`, COUNT(*) AS `Count` FROM `achievement_list` INNER JOIN `achievement_people`";
    $query .= " USING(`ID`) WHERE `Unlocked` = 1 AND `Disabled` = 0 GROUP BY `UserID` ORDER BY `Count` DESC";
    $resultA = do_query($query);

    $campers = num_rows($resultA);

    $query = "SELECT `Name`, COUNT(*) AS `Count` FROM `achievement_list` INNER JOIN `achievement_people`";
    $query .= " USING(`ID`) WHERE `Unlocked` = 1 AND `Disabled` = 0 GROUP BY `ID` ORDER BY `Count` ASC";
    $resultB = do_query($query);

    $ranks[] = 0;
    while ($row = fetch_row($resultB)) {
      $achieveList[$row['Name']] = $row['Count'];
      $ranks[] = $row['Count'];
    }

    asort($achieveList);
    sort($ranks);

    # ACHIEVEMENT LIST
    foreach ($achieveList as $name => $count) {
      if ($count == 0) {              # 0%
        $color = "grey";
      } else if ($count >= $campers / 5 * 4) {  # 80% - 100%
        $color = "blue";
      } else if ($count >= $campers / 2) {    # 50% - 80%
        $color = "green";
      } else if ($count <= $campers / 10) {    # 1% - 10%
        $color = "red";
      } else if ($count <= $campers / 5) {    # 10% - 20%
        $color = "darkorange";
      } else {          # 20% - 50%
        $color = "black";
      }

      $rank = array_search($count, $ranks);
      $percent = round($count / $campers * 100, 2);
      $count = "<span style='color: $color;'>$count / $campers</span> ($percent%)";

      if ($rank === 0) {
        $rank = "--";
      }

      $top[] = array("Name" => $name, "Count" => $count, "Rank" => $rank);
    }

    $ranks = array();
    while ($row = fetch_row($resultA)) {
      $ranks[] = $row['Count'];
    }

    rsort($ranks);

    mysql_data_seek($resultA, 0);

    # USER LIST
    while ($row = fetch_row($resultA)) {
      $percent = round($row['Count'] / $achieveCount * 100, 2);
      if ($row['Count'] == $achieveCount) {        # 100%
        $color = "purple";
      } else if ($row['Count'] >= $achieveCount / 5 * 4) {    # 80% - 100%
        $color = "blue";
      } else if ($row['Count'] >= $achieveCount / 2) {  # 50% - 80%
        $color = "green";
      } else if ($row['Count'] <= $achieveCount / 10) {  # 0% - 10%
        $color = "red";
      } else if ($row['Count'] <= $achieveCount / 5) {  # 10% - 20%
        $color = "darkorange";
      } else {                      # 20% - 50%
        $color = "black";
      }

      $rank = array_search($row['Count'], $ranks) + 1;

      $query = "SELECT `ID`, `Name`, COUNT(*) AS `Count` FROM `achievement_list` INNER JOIN `achievement_people`";
      $query .= " USING(`ID`) WHERE `Unlocked` = 1 AND `Disabled` = 0 AND `ID` IN (SELECT `ID` FROM `achievement_people`";
      $query .= " WHERE `UserID` = '{$row['UserID']}' AND `Unlocked` = 1) GROUP BY `ID` ORDER BY `Count` ASC";
      $result = do_query($query);
      $row2 = fetch_row($result);

      $percent2 = round($row2['Count'] / $campers * 100, 2);
      $rarest = "{$row2['Name']} ($percent2%)";

      $count = "<span style='color: $color;'>{$row['Count']} / $achieveCount</span> ($percent%)";
      $leaderboard[] = array(
          "UserID" => $row['UserID'],
          "Name" => $people[$row['UserID']],
          "Count" => $count,
          "Rank" => $rank,
          "Rarest" => $rarest);
    }

    $tpl->set("leaderboard", $leaderboard);
    $tpl->set("top", $top);

    $stats = true;
  } else {
    $stats = false;
  }

  if (!$stats && $SEGMENTS[1]) {
    $user = userInput($SEGMENTS[1]);
  } else {
    $user = $username;
  }

  $rows = array();

  # Get the achievements that the user has
  $query = "SELECT * FROM `achievement_people` INNER JOIN `achievement_list` USING(`ID`) ";
  $query .= " WHERE `UserID` = '$user' AND `Disabled` = 0 ORDER BY `ID` DESC";
  $result = do_query($query);
  while ($row = fetch_row($result)) {
    $rows[$row['ID']] = $row;
  }

  # Get the achievements that the user doesn't have
  $query = "SELECT * FROM `achievement_list` WHERE `Disabled` = 0 ORDER BY `ID` DESC";
  $result = do_query($query);
  while ($row = fetch_row($result)) {
    if (!isset($rows[$row['ID']])) {
      $rows[$row['ID']] = $row;
      $rows[$row['ID']]['Unlocked'] = 0;
    }
  }

  krsort($rows);

  $achievements = array();

  $unlocked = $total = 0;

  foreach ($rows as $row) {
    if (file_exists("resources/achievements/{$row['ID']}.png")) {
      $image = $row['ID'];
    } else {
      $image = "default";
    }

    if ($row['Unlocked']) {
      $style = "";
      $status = "<span style='color: green; font-weight: bold;'>";
      if ($row['KeepProgress']) {
        if ($row['Type'] == "incremental") {
          $status .= "(Achievement Unlocked - {$row['Progress']}/{$row['Requirements']})";
        } else {
          $reqCurrent = count(explode("|", $row['Progress']));
          $reqNeeded = count(explode("|", $row['Requirements']));
          $status .= "(Achievement Unlocked - $reqCurrent/$reqNeeded items)";
        }
      } else {
        $status .= "(Achievement Unlocked)";
      }
      $description = $row['Description'];
      $status .= "</span>";
      $unlocked++;
    } else {
      $image = "locked/$image";
      $style = "background-color: silver; border-color: gray; color: #555555;";
      $status = "<span style='color: gray;'>";
      if ($row['KeepProgress']) {
        if ($row['Type'] == "incremental") {
          if (!isset($row['Progress'])) {
            $row['Progress'] = 0;
          }
          $status .= "(Achievement Locked - {$row['Progress']}/{$row['Requirements']})";
        } else {
          if (!isset($row['Progress'])) {
            $row['Progress'] = "";
          }
          if (empty($row['Progress'])) {
            $reqCurrent = 0;
          } else {
            $reqCurrent = count(explode("|", $row['Progress']));
          }
          $reqNeeded = count(explode("|", $row['Requirements']));
          $status .= "(Achievement Locked - $reqCurrent/$reqNeeded items)";
        }
      } else {
        $status .= "<span style='color: gray;'>(Achievement Locked)";
      }
      $description = "???";
      $status .= "</span>";
    }

    $total++;

    $achievements[] = array(
        "Name" => $row['Name'], "Description" => $description,
        "Style" => $style, "Image" => $image, "Status" => $status);
  }

  if (!num_rows($result)) {
    $achievements = false;
  } else {
    $tpl->set('unlocked', $unlocked);
    $tpl->set('total', $total);
    $tpl->set('percent', round($unlocked / $total * 100, 2));
  }

  $tpl->set('contenttitle', "Trosnoth " . (
      $stats ? "Achievements Statistics" : "Achievements"));
  $tpl->set('name', $people[$user]);
  $tpl->set('statistics', $stats, true);
  $tpl->set('achievements', $achievements, true);

  fetch();
?>
