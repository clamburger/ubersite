<?php
  include_once("includes/start.php");
  $title = "Who's Who";
  $tpl->set('title', $title);
  $tpl->set('contenttitle', $title . ' at ' . $CAMP_NAME);

  # Get the duty team information
  $query = "SELECT * FROM `dutyteams` ORDER BY `ID` = 0 ASC, `ID` ASC";
  $res = do_query($query);
  $coloursPage = array();
  $colours = array("#ffffff");
  $fontColours = array();
  $teams = array();
  $warCries = array();
  while ($row = fetch_row($res)) {
    $teams[strtolower($row["Name"])] = $row["ID"];
    $warCries[$row["Name"]] = $row["WarCry"];
    $colours[$row["ID"]] = "#".$row["Colour"];
    $fontColours[$row["ID"]] = "#".$row['FontColour'];
    $coloursPage[] = array("name"=>$row["Name"],
                           "hex" =>"#".$row["Colour"],
                           "fonthex" => "#".$row['FontColour'],
                           "id"  =>$row["ID"],
                           "html"=>urlencode($row["Name"]));
  }

  $reverseTeams = array_flip($teams);

  $tpl->set('colours', $coloursPage);

  # Make sure the provided team name is valid
  $selectedTeam = $SEGMENTS[1];
  if ($selectedTeam) {
    if (!isset($teams[$selectedTeam])) {
        echo "LE NO";
        print_r($teams);
        die();
      header("Location: /profiles");
    } else {
      $queryExtra = "AND `dutyteam` = '{$teams[$selectedTeam]}'";
    }
  } else {
    $queryExtra = "";
  }

  # Creates the table of boxes for either leaders or campers.
  function createBoxes($result, $type) {
    global $tpl, $colours, $fontColours, $SYMBOLS, $reverseTeams;
    $people = array();

    while ($row = fetch_row($result)) {
      # Put their picture there if it exists
      if (!file_exists("camp-data/profiles/{$row["UserID"]}.jpg")) {
        $src = "/resources/img/no-pic";
      } else {
        $src = "/camp-data/profiles/".$row["UserID"];
      }

      if ($row['InfoFilled'] === "0") {
        $desc = "<em>This $type has not entered any information!</em>";
      } else {
        # Add each item
        $descItems = array();
        if (!empty($row['Nickname'])) {
          $descItems[] = "aka: {$row['Nickname']}";
        }

        # Display a random fact
        if (!empty($row['Facts'])) {
          $facts = explode("\n", $row['Facts']);
          $key = array_rand($facts);
          $descItems[] = $facts[$key];
        }

        if (!empty($row['About'])) {
          $about = str_replace("\n", "<br />", $row['About']);
          $descItems[] = "<em>$about</em>";
        }
        $desc = implode("<br />", $descItems);

      }

      //$greek = "<span style='font-family: arial;'>{$SYMBOLS[$row["StudyGroup"]]}</span> ({$row['StudyGroup']})";
      //$greek = $reverseTeams[$row["DutyTeam"]];
      $greek = "{$row['StudyGroup']}";

      $linkColour = "";
      $borderColour = "";
      if ($fontColours[intval($row["DutyTeam"])] != "#000000") {
        $linkColour = "color: #FFFFFF;";
        $borderColour = "border-$linkColour";
      }

      # Add the box to the list
      $people[] = array("src" => $src,
                        "name" => $row["Name"],
                        "id" => $row["UserID"],
                        "colour" => $colours[intval($row["DutyTeam"])],
                        "fontcolour" => $fontColours[intval($row["DutyTeam"])],
                        "linkcolour" => $linkColour,
                        "bordercolour" => $borderColour,
                        "desc" => $desc,
                        "greek" => $greek,
                        "uber" => uberButton(false,
                                             "/person.php?id=" . $row["UserID"]));
    }
    return $people;
  }

  $categories = array("director", "leader", "camper", "cook", "visitor");

  # Create boxes for each category of people
  foreach ($categories as $category) {
    $query = "SELECT * FROM `people` WHERE `Category` = '$category' $queryExtra ORDER BY `Name` ASC;";
    $result = do_query($query);
    $temp = array("upper" => ucfirst($category."s"),
                  "lower" => $category."s");

    if (num_rows($result) === 0) {
      $temp["display"] = false;
    } else {
      $temp["display"] = true;
    }

    $everybody[] = array_merge($temp, array("people" => createBoxes($result, "$category")));
    $categoriesTpl[] = $temp;
  }

  $tpl->set('everybody', $everybody, true);
  $tpl->set('categories', $categoriesTpl, true);

  if (!$selectedTeam or (empty($warCries[$selectedTeam]))) {
    $tpl->set('warcry', false, true);
  } else {
    $tpl->set('warcry', true, true);
    $tpl->set('warcryText', $warCries[$selectedTeam]);
  }

  if ($wget) {
    $tpl->set('wgetUL', "l");
  } else {
    $tpl->set('wgetUL', "dentalplan");
  }

  $tpl->set('contactDetails', $CONTACT_DETAILS);
  fetch();
?>
