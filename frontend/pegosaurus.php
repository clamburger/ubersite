<?php
  include_once("../includes/start.php");

  $title = 'Pegosaurus';
  $tpl->set('title', $title);

  if (!empty($_POST)) {
    $leader = userInput($_POST['leader']);

    if (isset($people[$leader])) {
      if (!ctype_digit($_POST['pegs'])) {
        $tpl->set('error', "The number of pegs must be an integer.");
      } else {
        $query = "INSERT INTO `pegosaurus` (`Leader`, `Pegs`, `Date`) ";
        $query .= "VALUES ('$leader', '{$_POST['pegs']}', NOW())";
        do_query($query);
        action("new", $leader);
        storeMessage('success', "Pegosaurus record successfully added.");
        refresh();
      }
    } else {
      $tpl->set('error', "You must select a valid leader.");
    }
  }

  $query = "SELECT * FROM `pegosaurus` ORDER BY `Pegs` DESC, `Date` ASC";
  $result = do_query($query);

  $pegosaurus = false;
  $rankings = array();

  while ($row = fetch_row($result)) {

    $leader = userpage($row['Leader'], true);

    //$rank = count($rankings);

    //if (count($rankings) && $row['Pegs'] == $rankings[count($rankings)-1]['pegs']) {
    //  $rank = intval($rankings[count($rankings)-1]['rank']);
    //} else {
      $rank = count($rankings) + 1;
    //}

    if ($rank == 1) {
      $pegosaurus = $people[$row['Leader']];
    }

    if (($rank >= 10) and ($rank <= 19)) {
      $rank .= "th";
    } else if (($rank - 1 + 10) % 10 == 0) {
      $rank .= "st";
    } else if (($rank - 2 + 10) % 10 == 0) {
      $rank .= "nd";
    } else if (($rank - 3 + 10) % 10 == 0) {
      $rank .= "rd";
    } else {
      $rank .= "th";
    }

    $rankings[] = array("leader" => $leader, "rank" => $rank, "pegs" => $row['Pegs']);
  }

  # Generate the list of leaders
  $query = "SELECT `UserID`, `Name` FROM `people` WHERE `Category` = 'leader' OR `Category` = 'director' ORDER BY `Name` ASC";
  $result = do_query($query);
  $leaderDropdown = "<option value='none'>---</option>\n";
  while ($row = fetch_row($result)) {
    $leaderDropdown .= "<option value='{$row['UserID']}'>{$row['Name']}</option>\n";
  }

  $tpl->set('pegosaurus', $pegosaurus);
  $tpl->set('rankings', $rankings);
  $tpl->set('showTable', $pegosaurus or $leader);

  $tpl->set('leaderDropdown', $leaderDropdown);

  fetch();
?>
