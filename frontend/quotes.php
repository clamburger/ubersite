<?php
  include_once("../includes/start.php");
  $title = 'Camp Quotes';
  $tpl->set('title', $title);

  $formPerson = $formContext = $formQuote = $formSelection = false;

  function validQuote($id, $unapproved) {
    if (!is_numeric($id)) {
      return false;
    }
    $query = "SELECT `ID` FROM `quotes` WHERE `ID` = '$id'";
    if ($unapproved) {
      $query .= " AND `Status` = 0";
    }
    if (!num_rows(do_query($query))) {
      return false;
    }
    return true;
  }

  if ($leader) {
    # Approve the selected quote
    if (isset($_GET["approve"])) {
      if (!validQuote($_GET['approve'], true)) {
        $tpl->set('error', "That is not a valid quote ID.");
      } else {
        do_query("UPDATE `quotes` SET `Status` = 1 WHERE `ID` = '{$_GET['approve']}'");
        action("approve", $_GET['approve']);
        $tpl->set('success', "You have successfully approved a quote.");
      }
    # Decline the selected quote
    } else if (isset($_GET["decline"])) {
      if (!validQuote($_GET['decline'], true)) {
        $tpl->set('error', "That is not a valid quote ID.");
      } else {
        do_query("UPDATE `quotes` SET `Status` = -1 WHERE `ID` = '{$_GET['decline']}'");
        action("decline", $_GET['decline']);
        $tpl->set('success', "You have successfully declined a quote.");
      }
    # Revert the quote back to unapproved status
    } else if (isset($_GET["revert"])) {
      if (!validQuote($_GET['revert'], false)) {
        $tpl->set('error', "That is not a valid quote ID.");
      } else {
        do_query("UPDATE `quotes` SET `Status` = 0 WHERE `ID` = '{$_GET['revert']}'");
        action("revert", $_GET['revert']);
        $tpl->set('success', "The selected quote has been reverted to unapproved status.");
      }
    # Delete the quote permanentely
    } else if (isset($_GET["delete"]) && $admin) {
      if (!validQuote($_GET['delete'], false)) {
        $tpl->set('error', "That is not a valid quote ID.");
      } else {
        do_query("DELETE FROM `quotes` WHERE `ID` = '{$_GET['delete']}'");
        action("delete", $_GET['delete']);
        $tpl->set('success', "You have successfully deleted a quote.");
      }
    }
  }

  # A new quote has been submitted.
  if (isset($_POST['quote'])) {
    $formSelection = userInput($_POST['people']);
    $formPerson = userInput($_POST['name']);
    $formContext = userInput($_POST['context']);
    $formQuote = userInput($_POST['quote']);
    if ($_POST['quote'] == "") {
      # It was empty.
      $tpl->set('error', "You must enter a quote before submitting.");
    } else if ($formPerson == "---" && $formSelection == "single") {
      # The person name was empty.
      $tpl->set('error', "You must select the name of the person who said the quote.");
    } else if ($formSelection == "single" and (!isset($people[$formPerson]) && $formPerson != "other" && $formPerson != "unknown")) {
      # Something weird went wrong.
      $tpl->set('error', "The person you have selected is not valid.");
    } else {
      # Check if the quote exists.
      $result = do_query("SELECT `Status` FROM `quotes` WHERE `Quote` = '$formQuote'");
      if (num_rows($result)) {
        $row = fetch_row($result);
        if ($row['Status'] == 0) {
          $tpl->set('error', "That quote has already been submitted (it's currently waiting to be approved).");
        } else {
          $tpl->set('error', "That quote has already been submitted.");
        }
      } else {
        if ($leader) {
          $initialStatus = 1;
        } else {
          $initialStatus = 0;
        }
        if ($formSelection != "single") {
          $formPerson = "multiple";
        }
        $query = "INSERT INTO `quotes` (`Person`, `Context`, `Quote`, `Submitter`, `Status`) ".
             "VALUES ('$formPerson', '$formContext', '$formQuote', '$username', $initialStatus)";
        do_query($query);
        action("submit", mysql_insert_id());
        if ($leader) {
          storeMessage('success', "Quote successfully submitted. Since you are a leader it has been automatically" .
                " approved and will be visible to campers immediately.");
        } else {
          storeMessage('success', "Quote successfully submitted. It will appear on the website once it has been approved.");
        }
        refresh();
      }
      $formMultiple = $formPerson = $formContext = $formQuote = false;
    }
  }

  $quotes = array();
  $showControls = false;

  # Get the list of quotes.
  if (!$leader) {
    $extra = " `Status` = 1";
  } else if (isset($_GET['debug'])) {
    $extra = " 1";
  } else {
    $extra = " `Status` != -1";
  }

  $query = "SELECT * FROM `quotes` WHERE$extra ORDER BY `Person` = 'multiple', `Person` = 'unknown', ";
  $query .= "`Person` = 'other', `Person` ASC, `ID` ASC";
  $result = do_query($query);
  if (mysql_num_rows($result) > 0) {
    # Figure out how many quotes each person has.
    $howManyTimes = array();
    while ($row = fetch_row($result)) {
      $person = $row['Person'];
      if (!isset($howManyTimes[$person])) {
        $howManyTimes[$person] = 1;
      } else {
        $howManyTimes[$person]++;
      }
    }

    mysql_data_seek($result, 0);
    $currentPerson = "";
    $peopleDone = array();
    foreach ($howManyTimes as $person => $times) {
      $peopleDone[$person] = 0;
    }

    # Start forming the table.
    while ($row = mysql_fetch_assoc($result)) {
      $person = $row['Person'];
      $code = "";
      if ($person != "multiple") {
        if ($peopleDone[$person] === 0) {
          $peopleDone[$person] = 1;
          $code .= "<td rowspan='";
          $code .= $howManyTimes[$person];
          $code .= "'><strong>";
          if (!isset($people[$person])) {
            $code .= ucfirst($person);
          } else {
            $code .= userpage($person);
          }
          $code .= "</strong></td>";
        }
        $colspan = "";
      } else {
        $colspan = ' colspan="2"';
      }

      # Replace linebreaks and remove all tags except for <b> <i> and <u>.
      $quote = str_replace("\n", "<br />", $row['Quote']);
      $quote .= uberButton(false, "/quotes.php?id=" .$row["ID"]);
      $context = "<br /><small>(";
      if (!empty($row['Context'])) {
        $context .= $row['Context'] . " - ";
      }
      $context .= "Submitted by ".userpage($row['Submitter'], true).")</small>";

      if ($row['Status'] == 0) {
        # Waiting for approval
        $text = "<td style='background-color: #B7FFB7' $colspan>$quote$context</td>";
        $rowTag = "<tr style='background-color: #B7FFB7'>";

        $controls = '<td class="controlBox">';
        $controls .= '<a href="?approve='.$row['ID'].'" class="button approveButton">Approve</a>';
        $controls .= '<a href="?decline='.$row['ID'].'" class="button declineButton">Decline</a>';
        if ($admin) {
          $controls .= '<a href="?delete='.$row['ID'].'" class="button deleteButton">Delete</a>';
        }
        $controls .= "</td>";

        $showControls = true;
      } else if ($row['Status'] == -1 && isset($_GET['debug'])) {
        # Declined
        $text = "<td style='background-color: #FFB7B7' $colspan>$quote$context</td>";
        $rowTag = "<tr style='background-color: #FFB7B7'>";

        $controls = '<td class="controlBox">';
        $controls .= '<a href="?revert='.$row['ID'].'&debug" class="button declineButton">Revert&nbsp;Deletion</a>';
        if ($admin) {
          $controls .= '<a href="?delete='.$row['ID'].'&debug" class="button deleteButton">Delete&nbsp;Forever</a>';
        }
        $controls .= "</td>";

        $showControls = true;
      } else {
        # Approved
        $text = "<td $colspan>$quote$context</td>";
        $controls = "";
        if (isset($_GET['debug'])) {
          $controls = '<td class="controlBox">';
          $controls .= '<a href="?revert='.$row['ID'].'&debug" class="button declineButton">Revert&nbsp;Approval</a></td>';
          $showControls = true;
        }
        $rowTag = "<tr>";
      }
      $quotes[] = array(
          "people" => $code, "text" => $text,
          "controls" => $controls, "rowTag" => $rowTag,
          "submitter" => userpage($row['Submitter']));
    }
    $tpl->set('quotes', $quotes, true);
  } else {
    $tpl->set('quotes', false, true);
  }

  $tpl->set('controls', $showControls, true);

  # Generate the dropdown list for new quotes
  $dropdown = "<option id='none'>---</option>\n";
  foreach ($people as $id => $name) {
    if ($id == $formPerson) {
      $dropdown .= "<option value='$id' selected>$name</option>\n";
    } else {
      $dropdown .= "<option value='$id'>$name</option>\n";
    }
  }
  $dropdown .= "<option disabled>---</option>\n";
  $dropdown .= "<option value='other'>Other</option>\n";
  $dropdown .= "<option value='unknown'>Unknown</option>\n";

  $tpl->set('dropdown', $dropdown);

  if ($formSelection == "single" || !$formSelection) {
    $tpl->set('singleCheck', 'checked');
    $tpl->set('multipleCheck', '');
    $tpl->set('selectionStyle', '');
  } else {
    $tpl->set('singleCheck', '');
    $tpl->set('multipleCheck', 'checked');
    $tpl->set('selectionStyle', 'display: none;');
  }

  $debug = false;
  if (isset($_GET['debug'])) {
    $debug = true;
  }

  # Pass everything to the templates.
  $tpl->set('debug', $debug, true);
  $tpl->set('person', $formPerson, true);
  $tpl->set('context', $formContext, true);
  $tpl->set('quote', $formQuote, true);

  fetch();
?>
