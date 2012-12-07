<?php
  include_once("includes/start.php");
  $title = "Account Management";
  $shortTitle = "Account Mgmt.";
  $tpl->set('title', $title);
  $tpl->set('shortTitle', $shortTitle);

  $tpl->set('editing', false, true);
  $tpl->set('edit-ID', false);
  $tpl->set('edit-name', false);
  $tpl->set('edit-disabled', false);
  $tpl->set('edit-admin', false);
  $tpl->set('edit-greek', false);
  $tpl->set('submit', "Create User");

  $groups = array("leader" => "blue", "director" => "red", "camper" => "green", "cook" => "gray", "visitor" => "gray");

  # Check how many people there are in the database right now
  $query = "SELECT COUNT(*) FROM `people`";
  $result = do_query($query);
  $row = fetch_row($result);
  $userCount = $row[0];
  $USER_LIMIT = 1;

  # Get a list of duty teams
  //$dutyTeams = array(0 => array("name" => "No Activity Group", "colour" => "FFFFFF"));
  $dutyTeams = array(0 => array("name" => "No Duty Team", "colour" => "FFFFFF"));
  $query = "SELECT * FROM `dutyteams`";
  $result = do_query($query);
  while ($row = fetch_row($result)) {
    $dutyTeams[$row['ID']] = array("name" => $row['Name'], "colour" => $row['Colour'], "fontcolour" => $row['FontColour']);
  }
  ksort($dutyTeams);

  $selectNone = false;

  if (isset($_POST['action']) && $leader) {
    $tpl->set('edit-ID', $_POST['userID']);
    $tpl->set('edit-name', str_replace("'", "&#39;", $_POST['name']));
    if (isset($_POST['admin'])) {
      $tpl->set('edit-admin', " checked");
    }
    $tpl->set('edit-greek', $_POST['greek']);

    # New account submission
    if ($_POST['action'] == "new") {
      $tpl->set('edit-ID', $_POST['userIDinput']);
      $ID = userInput($_POST['userIDinput'], false);
      if (isset($people[$ID])) {
        $messages->addMessage(new Message("error", "That ID already exists!"));
      } else {
        $name = trim($_POST['name']);
        if (empty($name)) {
          $messages->addMessage(new Message("error", "Name cannot be blank!"));
        } else {
          $name = userInput($name);
          $admin = 0;
          if (isset($_POST['admin'])) {
            $admin = 1;
          }
          $greek = userInput(trim($_POST['greek']));
          $password = password_hash($ID, PASSWORD_DEFAULT);
          if (!$password) {
            $messages->addMessage(new Message("error",
              "An error occurred while generated the password. Please try again."));
          } else {

            # Here this query is outside the wrapper because we are assuming that the LDAP server only contains information
            # such as username and password. This means we need to insert a row for the rest of the data.

            $query = "INSERT INTO `people` (`UserID`, `Name`, `Category`, `DutyTeam`, `StudyGroup`, `Admin`)";
            $query .= " VALUES('$ID', '$name', '{$_POST['category']}', {$_POST['dutyteam']}, '$greek', $admin)";
            do_query($query);
            action("new", $ID);
            storeMessage('success', "Account successfully created!");

            newAccount($ID);

            refresh();

            $selectNone = true;

            $tpl->set('edit-ID', false);
            $tpl->set('edit-name', false);
            $tpl->set('edit-admin', false);
            $tpl->set('edit-greek', false);
          }
        }
      }

    # Edit account submission
    } else {
      $tpl->set('editing', true, true);
      $tpl->set('edit-disabled', 'disabled="disabled"');

      $ID = $_POST['userID'];
      $name = trim($_POST['name']);
      $greek = trim($_POST['greek']);
      if (empty($name)) {
        $messages->addMessage(new Message("error", "Name cannot be blank!"));
      } else {
        $name = userInput($name);
        $admin = 0;
        if (isset($_POST['admin'])) {
          $admin = 1;
        }
        $query = "UPDATE `people` SET `Name` = '$name', `Category` = '{$_POST['category']}', `DutyTeam` = {$_POST['dutyteam']},";
        $query .= " `Admin` = $admin, `StudyGroup` = '$greek' WHERE `UserID` = '$ID'";
        do_query($query);
        action("edit", $ID);
        storeMessage('success', "Account successfully modified.");
        refresh();
        $selectNone = true;

        $tpl->set('edit-ID', false);
        $tpl->set('edit-name', false);
        $tpl->set('edit-admin', false);
        $tpl->set('edit-disabled', false);
        $tpl->set('edit-greek', false);
      }
    }

  }

  # Edit link clicked
  if ($SEGMENTS[1] == "edit") {
    $ID = userInput($SEGMENTS[2]);
    $tpl->set('editing', true, true);
    $query = "SELECT * FROM `people` WHERE `UserID` = '$ID'";
    $result = do_query($query);
    if (!num_rows($result)) {
      header("Location: /accounts");
    }
    $row = fetch_row($result);

    $tpl->set('edit-disabled', 'disabled="disabled"');
    $tpl->set('edit-ID', $row['UserID']);
    $tpl->set('edit-name', str_replace("'", "&#39;", $row['Name']));
    $tpl->set('edit-greek', $row['StudyGroup']);

    if ($row['Admin']) {
      $tpl->set('edit-admin', " checked");
    }

    $tpl->set('submit', "Modify User");

  }

  # Delete link clicked
  if ($SEGMENTS[1] == "delete") {
    $userToDelete = $SEGMENTS[2];
    if (!isset($people[$userToDelete])) {
      header("Location: /accounts");
    } else {
      if ($SEGMENTS[3] == "confirm") {
        if (!isset($_SESSION['deleteID'])) {
          $messages->addMessage(new Message("error",
            "Cannot find original deletion request. You will need to press \"delete\" again."));
        } else if (time() - $_SESSION['deleteTime'] > 30) {
          $messages->addMessage(new Message("error",
            "You took too long to confirm. You will need to press \"delete\" again."));
        } else if ($_SESSION['deleteID'] != $userToDelete) {
          $messages->addMessage(new Message("error",
            "You have confirmed the wrong ID. You will need to press \"delete\" again."));
        } else {
          $query = "DELETE FROM `people` WHERE `UserID` = '$userToDelete'";
          do_query($query);
          deleteAccount($userToDelete);
          action("delete", $userToDelete);
          $messages->addMessage(new Message("success",
            "You have successfully deleted {$people[$userToDelete]}'s account."));
        }
        unset($_SESSION['deleteID']);
        unset($_SESSION['deleteTime']);
      } else {
        if ($userToDelete == $username) {
            $messages->addMessage(new Message("error", "You cannot delete your own account!"));
        } else {
            $_SESSION['deleteID'] = $userToDelete;
            $_SESSION['deleteTime'] = time();
            $tpl->set('warning', "Are you absolutely positive that you want to delete {$people[$userToDelete]}'s account?" .
                " | <a href='/accounts/delete/$userToDelete/confirm'>Confirm deletion</a>.");
        }
      }
    }
  }

  # Populate the "category" dropdown list
  $categories = "";
  foreach ($groups as $id => $colour) {
    $selected = "";
    if ((isset($_GET['edit']) && $id == $row['Category']) or
       (isset($_POST['action']) && $id == $_POST['category']) and (!$selectNone)) {
      $selected = " selected";
    }
    $categories .= "<option value='$id'$selected>".ucfirst($id)."</option>\n";
  }
  $tpl->set('categories', $categories);

  # Populate the "duty team" dropdown list
  $dutyTeamsTpl = "";
  foreach ($dutyTeams as $id => $info) {
    $selected = "";
    if ((isset($_GET['edit']) && $id === (int)$row['DutyTeam']) or
       (isset($_POST['action']) && $id === (int)$_POST['dutyteam']) and (!$selectNone)) {
      $selected = " selected";
    }
    $dutyTeamsTpl .= "<option value='$id' style='background-color: #{$info['colour']}; color: #{$info['fontcolour']};'$selected>$id - {$info['name']}</option>\n";
  }
  $tpl->set('dutyteams', $dutyTeamsTpl);

  # Grab the complete unabridged list of people
  $query = "SELECT `people`.*, MAX(`Timestamp`) as `LastActive` FROM `people` LEFT JOIN `access` USING (`UserID`) GROUP BY `UserID` ";
  $query .= " ORDER BY (`Category` = 'director' OR `Category` = 'leader') DESC, `Category` = 'camper' DESC, `people`.`UserID` ASC";
  $result = do_query($query);
  $peoplee = array();

  while ($row = fetch_row($result)) {
    $userID = $row['UserID'];
    $category = "<span style='color: {$groups[$row['Category']]};'>".ucfirst($row['Category'])."</span>";
    $name = $row['Name'];
    if ($row['Admin']) {
      $name .= " <strong>(admin)</strong>";
    }
    $dutyTeam = $row['DutyTeam'] . " - " . $dutyTeams[$row['DutyTeam']]['name'];
    $colour = $dutyTeams[$row['DutyTeam']]['colour'];
    $fontColour = $dutyTeams[$row['DutyTeam']]['fontcolour'];
    if ($AUTH_TYPE != "mysql") {
      $password = "<span style='color: grey;'>N/A</span>";
    } else if ($row['PasswordChanged']) {
      $password = "<strong style='color: green;'>Yes</strong>";
    } else {
      $password = "<span style='color: red;'>No</span>";
    }
    if ($row['InfoFilled']) {
      $profile = "<strong style='color: green;'>Yes</strong>";
    } else {
      $profile = "<span style='color: red;'>No</span>";
    }
    if (isset($row['LastActive'])) {
      $lastActive = howlong($row['LastActive']);
    } else {
      $lastActive = "<span style='color: grey;'>Never logged on</span>";
    }

    if (time() - strtotime($row['LastActive']) < 60*15) {
      $lastActive = "<span style='color: green; font-weight: bold;'>$lastActive</span>";
    } else if (time() - strtotime($row['LastActive']) > 60*60*24) {
      $lastActive = "<span style='color: grey;'>$lastActive</span>";
    }

    if ($userID != $username) {
      $delete = "| <a href='/accounts/delete/$userID'>Delete</a>";
    } else {
      $delete = "";
    }

    //$greek = "<span style='font-family: arial;'>{$SYMBOLS[$row["StudyGroup"]]}</span> ({$row['StudyGroup']})";
    $greek = "";
    $peoplee[] = array("UserID" => $userID, "Name" => $name, "Category" => $category,
               "Admin" => $row['Admin'], "DutyTeam" => $dutyTeam, "InfoFilled" => $profile,
               "PasswordChanged" => $password, "Colour" => $colour, "Greek" => $greek,
               "LastActive" => $lastActive, "Delete" => $delete, "FontColour" => $fontColour);
  }

  $tpl->set('people', $peoplee);
  $tpl->set('dutyteams', $dutyTeamsTpl);

  fetch();
?>
