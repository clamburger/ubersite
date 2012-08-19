<?php
  include_once("../includes/start.php");
  error_reporting(E_ALL ^ E_NOTICE);
  $title = 'Camper Feedback';
  $tpl->set('title', $title);

  $numbers = array();
  $bible = array();
  $power = array();

  $items = array("Most", "Least", "TimeOver", "OtherComment", "NotDoComments",
                 "ThemeComments", "GeneralComments", "Beards",
                 "BibleComments", "PowerComments", "GameComments",
                 "OutdoorComments", "WebsiteComments", "ShowNightComments");

  $electives = array();
  $query = "SELECT * FROM `questionnaire_electives` ORDER BY `Type` = 'morning' DESC, `Type` = 'evening' DESC, `Order`";
  $result = do_query($query);

  while ($row = fetch_row($result)) {
    $electives[$row['ShortName']] = $row['LongName'];
  }

  $data = array();
  $electiveResponses = array();

  foreach ($electives as $ID => $longName) {
    $electiveResponses[$longName] = array("numbers" => array(),
                                          "comments" => array());
  }

  foreach ($items as $item) {
    $data[$item] = array();
  }

  # Response lookup arrays begin here
  $timeOnCamp = array(
      "--",
      "5 - <span style='color: green;'>&Uuml;ber!</span>",
      "4 - <span style='color: #999900;'>Awesome</span>",
      "3 - <span style='color: #CC9900;'>Great</span>",
      "2 - <span style='color: #FF6600;'>Average</span>",
      "1 - <span style='color: red;'>Lousy</span>",
      "0 - <span style='color: red;'>Terrible</span>");

  $leaderQuality = array(
      "--",
      "4 - <span style='color: green;'>Always</span>",
      "3 - <span style='color: #999900;'>Usually</span>",
      "2 - <span style='color: #CC9900;'>Sometimes</span>",
      "1 - <span style='color: #FF6600;'>Rarely</span>",
      "0 - <span style='color: red;'>Never</span>",
    );

    $hearingAboutCamp = array(
      "--", "Flyer / Poster", "SU Qld", "&Uuml;berTweak website",
      "School Chaplain", "Church / Youth Group", "Friend", "Been before",
      "Other");

  $posters = array("--", "Yes", "<span style='color: silver;'>No</span>");

  $sendInfo = array("<span style='color: silver;'>No</span>", "Yes");

  $christ = array(
      "--",
      "<strong>No; don't want to learn more</strong>",
      "<strong>No; want to learn more</strong>",
      "<strong>Have been slack, turned back at this camp</strong>",
      "<strong style='color: green;'>Became a Christian at this camp</strong>",
      "Following Christ already",
      "Other");

  $length = array("--", "Too short", "Little short", "Just right",
                  "Little long", "Too long");

  $variety = array("--", "No", "Kind of", "Yes");

  $num = array("--", "<span style='color: red;'>1</span>", "2", "3", "4",
               "<strong>5</strong>");

  $smallgroup = true;

  # Response lookup arrays end here
  if (isset($_GET['camper']) && isset($people[$_GET['camper']])) {
    $extra = " `Q`.`UserID` = '{$_GET['camper']}'";
  } else if (isset($_GET['leaders'])) {
    $extra = " `Category` != 'camper'";
  } else if (isset($_GET['smallgroup'])) {
    $extra = " `DutyTeam` = (SELECT `DutyTeam` FROM `people` WHERE `UserID` = '$username') AND `Category` = 'camper'";
    $smallgroup = true;
  } else if (isset($_GET['group'])) {
    $extra = " `DutyTeam` = '{$_GET['group']}' AND `Category` = 'camper'";
  } else {
    $extra = " `Category` = 'camper'";
    $smallgroup = false;
  }

  $tpl->set('smallgroup', $smallgroup);

  $query = "SELECT * FROM `questionnaire` AS `Q`, `people` AS `P` WHERE `P`.`UserID` = `Q`.`UserID` AND $extra ORDER BY `Name` ASC";
  $res = do_query($query);

  while ($row = fetch_row($res)) {
    # First main table
    if (isset($row['GodComment'])) {
      $christStr = "{$christ[$row['God']]}<br><small>{$row['GodComment']}</small>";
      //$christStr = "<span title='{$row['GodComment']}' style='color: blue;'>{$christ[$row['God']]}</span>";
    } else {
      $christStr = $christ[$row['God']];
    }

    if (isset($row['HearComment'])) {
      $hearStr = "{$hearingAboutCamp[$row['Hear']]}<br><small>{$row['HearComment']}</small>";
    } else {
      $hearStr = $hearingAboutCamp[$row['Hear']];
    }

    $temp = array(
        "name" => "<span style='white-space: nowrap;'>{$row['Name']}</span>",
        "timeOnCamp" => $timeOnCamp[$row['timeOnCamp']],
        "leaderQuality" => $leaderQuality[$row['leaderQuality']],
        "hearingAboutCamp" => $hearStr,
        "posters" => $posters[$row['Posters']],
        "christ" => $christStr,
        "sendInfo" => $sendInfo[$row['PostersYes']]);

    if (strlen($row['FavouriteLeader']) > 1) {
      $temp['favouriteLeader'] = $row['FavouriteLeader'];
    } else {
      $temp['favouriteLeader'] = "--";
    }

    $numbers[] = $temp;

    # Activity responses
    if ($row['Bible1'] + $row['Bible2'] + $row['Bible3'] + $row['Bible4'] > 0) {
      $temp = array(
          "name" => $row['Name'],
          "1" => $num[$row['Bible1']],
          "2" => $num[$row['Bible2']],
          "3" => $num[$row['Bible3']],
          "4" => $length[$row['Bible4']],
          "5" => $num[$row['Bible5']]);
      $bible[] = $temp;
    }

    if ($row['Power1'] + $row['Power2'] + $row['Power3'] > 0) {
      $temp = array(
          "name" => $row['Name'],
          "1" => $num[$row['Power1']],
          "2" => $num[$row['Power2']],
          "3" => $num[$row['Power3']]);
      $power[] = $temp;
    }

    if ($row['Game1'] + $row['Game2'] + $row['Game3'] + $row['Game4'] > 0) {
      $temp = array(
          "name" => $row['Name'],
          "1" => $num[$row['Game1']],
          "2" => $num[$row['Game2']],
          "3" => $length[$row['Game3']],
          "4" => $num[$row['Game4']]);
      $game[] = $temp;
    }

    if ($row['Outdoor1'] + $row['Outdoor2'] + $row['Outdoor3'] > 0) {
      $temp = array(
          "name" => $row['Name'],
          "1" => $num[$row['Outdoor1']],
          "2" => $num[$row['Outdoor2']],
          "3" => $length[$row['Outdoor3']]);
      $outdoor[] = $temp;
    }

    if ($row['Website1'] + $row['Website2'] + $row['Website3'] > 0) {
      $temp = array(
          "name" => $row['Name'],
          "1" => $num[$row['Website1']],
          "2" => $num[$row['Website2']],
          "3" => $num[$row['Website3']]);
      $website[] = $temp;
    }

    if ($row['ShowNight1'] + $row['ShowNight2'] > 0) {
      $temp = array(
          "name" => $row['Name'],
          "1" => $num[$row['ShowNight1']],
          "2" => $length[$row['ShowNight2']]);
      $showNight[] = $temp;
    }

    if ($row['ElectivesGeneral1'] + $row['ElectivesGeneral2'] + $row['ElectivesGeneral3'] > 0) {
      $temp = array(
          "name" => $row['Name'],
          "1" => $num[$row['ElectivesGeneral1']],
          "2" => $num[$row['ElectivesGeneral2']],
          "3" => $length[$row['ElectivesGeneral3']]);
      $electivesGeneral[] = $temp;
    }

    # Elective responses
    foreach ($electives as $ID => $longName) {
      if ($row[$ID."1"] + $row[$ID."2"] > 0) {
        $temp = array(
            "name" => $row['Name'],
            "1" => $num[$row[$ID."1"]],
            "2" => $num[$row[$ID."2"]]);
        $electiveResponses[$longName]['numbers'][] = $temp;
        $comment = trim($row[$ID."Comments"]);
        if (!empty($comment)) {
          $electiveResponses[$longName]['comments'][] = "$comment - <em>{$row['Name']}</em>";
        }

      }
    }

    # General responses
    foreach ($items as $item) {
      $row[$item] = trim($row[$item]);
      if (!empty($row[$item])) {
        $data[$item][] = "{$row[$item]} - <em>{$row['Name']}</em>";
      }
    }

  }

    $temp = $electiveResponses;
    $electiveResponses = array();

    foreach ($temp as $electiveName => $electiveData) {
      $electiveResponses[] = array(
          "name" => $electiveName,
          "data" => $electiveData['numbers'],
          "comments" => $electiveData['comments']);
  }

    foreach ($items as $item) {
      $tpl->set($item, $data[$item]);
    }

    $tpl->set('numbers', $numbers);
    $tpl->set('bible', $bible);
    $tpl->set('power', $power);
    $tpl->set('game', $game);
    $tpl->set('outdoor', $outdoor);
    $tpl->set('website', $website);
    $tpl->set('showNight', $showNight);
    $tpl->set('electivesGeneral', $electivesGeneral);
    $tpl->set('electives', $electiveResponses);

    fetch();
?>
