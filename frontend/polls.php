<?php
  include_once("includes/start.php");
  $title = 'Polls';
  $tpl->set('title', $title);

  $valid = false;

  # Get the complete list of polls: it is a useful resource to have
  $query = "SELECT * FROM `poll_questions` ORDER BY `ID` DESC";
  $result = do_query($query);
  $polls = $pollsMod = array();
  while ($row = fetch_row($result)) {
    if ($row['Status'] == 1) {
      $polls[$row['ID']] = $row;
    } else if ($row['Status'] == 0) {
      $pollsMod[$row['ID']] = $row;
    }
  }

  if ($user->isLeader()) {
    # Declining a poll
    $toModerate = $SEGMENTS[2];
    if ($SEGMENTS[1] == "decline" && isset($pollsMod[$toModerate])) {
      $query = "UPDATE `poll_questions` SET `Status` = -1 WHERE `ID` = $toModerate";
      do_query($query);
      $messages->addMessage(new Message("success", "Poll successfully declined."));
      unset($pollsMod[$toModerate]);
      action("decline", $toModerate);

    # Deleting a poll
    } else if ($SEGMENTS[1] == "delete" && isset($pollsMod[$toModerate])) {
      $query = "DELETE FROM `poll_questions` WHERE `ID` = $toModerate";
      do_query($query);
      $query = "DELETE FROM `poll_options` WHERE `PollID` = $toModerate";
      do_query($query);
      $messages->addMessage(new Message("success", "Poll successfully deleted."));
      unset($pollsMod[$toModerate]);
      action("delete", $toModerate);

    # Approving a poll
    } else if ($SEGMENTS[1] == "approve" && isset($pollsMod[$toModerate])) {
      $query = "UPDATE `poll_questions` SET `Status` = 1 WHERE `ID` = $toModerate";
      do_query($query);
      $messages->addMessage(new Message("success", "Poll successfully approved."));
      $polls[$toModerate] = $pollsMod[$toModerate];
      unset($pollsMod[$toModerate]);
      action("approve", $toModerate);
    }
  }

    if (!empty($polls)) {
    $lastIndex = max(array_keys($polls));
    $pollIndex = $lastIndex;
  }

    $tpl->set('create', false, true);
    $tpl->set('moderate', false, true);

    if ($SEGMENTS[1] == "create") {

    $tpl->set('create', true, true);
    $tpl->set('contenttitle', 'Create a Poll');
    $tpl->set('new-question', false);
    $tpl->set('new-responses', false);
    $tpl->set('new-creator', $username);

    # Creating a new poll
    if (isset($_POST['question'])) {

      $tpl->set('new-question', $_POST['question']);
      $tpl->set('new-responses', $_POST['responses']);

      $question = userInput($_POST['question']);
      $creator = userInput($_POST['creator']);
      $multiple = intval(isset($_POST['multiple']));
      $hideResults = intval(isset($_POST['hideResults']));

      if (empty($question)) {
        $messages->addMessage(new Message("error", "You must enter in a question for the poll!"));
      } else {
        $result = do_query("SELECT `Question` FROM `poll_questions` WHERE `Question` = '$question'");
        if (num_rows($result)) {
          $messages->addMessage(new Message("error",
            "A poll with that question already exists. " .
            "Did you refresh the page after submitting?"));
        } else {
          $responses = array_values(array_filter(array_map("trim", explode("\n", $_POST['responses']))));
          if (count($responses) < 2) {
            $messages->addMessage(new Message("error", "You must enter at least two responses."));
          } else {
            if ($user->isLeader()) {
              $initialStatus = 1;
            } else {
              $initialStatus = 0;
            }
            $query = "INSERT INTO `poll_questions` (`Question`, `Creator`, `Status`, `Multiple`, `Hidden`) VALUES ('$question', '$creator', $initialStatus, $multiple, $hideResults)";
            do_query($query);

            $pollID = mysql_insert_id();
            action("new", $pollID);
            $responses = array_map("userInput", $responses);
            foreach ($responses as $response) {
              do_query("INSERT INTO `poll_options` (`PollID`, `Response`) VALUES ($pollID, '$response')");
            }
            if ($user->isLeader()) {
              storeMessage('success', "Poll successfully created! Go <a href='/polls/$pollID'>check it out</a>.");
            } else {
              storeMessage('success', "Poll successfully created! It will appear on the <a href='/polls'>Polls</a> page" .
                         " once approved by a leader.<br />".
                         "(There is no need to fill out the form again unless you are creating a different poll.)");
            }
            refresh();

            $tpl->set('new-question', false);
            $tpl->set('new-responses', false);
          }
        }
      }
    }

  } else if ($SEGMENTS[1] == "moderate" && $user->isLeader() && isset($pollsMod[$SEGMENTS[2]])) {
    $tpl->set('contenttitle', 'Poll Moderation');

    $toModerate = $SEGMENTS[2];

    $tpl->set('moderate', true, true);
    $question = $pollsMod[$toModerate]['Question'];
    $tpl->set('question', $question);
    $tpl->set('creator', userpage($pollsMod[$toModerate]['Creator']));

    # Get the list of options for this poll
    $query = fetch_row(do_query("SELECT `Question` FROM `poll_questions` WHERE `ID` = $toModerate"));
    $tpl->set('question', $query[0]);
    $res = do_query("SELECT * FROM `poll_options` WHERE `PollID` = $toModerate ORDER BY `OptionID`");
    $options = array();
    while ($row = fetch_row($res)) {
      $options[] = array("id" => $row["OptionID"], "text"=>$row["Response"]);
    }
    $tpl->set('options', $options);
    $tpl->set('pollID', $toModerate);

  } else if (!isset($pollIndex)) {
       # Check if there are no polls yet
    $tpl->set('poll', false, true);

  } else {

    if (isset($_GET['id'])) {
      if (is_numeric($_GET['id'])) {
        $pollIndex = $_GET['id'];
      }
    }

    # Find out if the provided ID is valid
    if (!isset($polls[$pollIndex])) {
      $pollIndex = $lastIndex;
    } else {
      $valid = true;
    }

    # Find out if the user has voted on this poll yet
    $query = "SELECT * FROM `poll_votes` WHERE `UserID` = '$username' AND `PollID` = $pollIndex";
    $voted = num_rows(do_query($query));

    # They can't reset the vote if they never voted to start with
    if (!$voted) {
      $valid = false;
    }

    # Reset the user's vote.
    if (isset($_GET['reset']) && $valid) {
      $query = "DELETE FROM `poll_votes` WHERE `UserID` = '$username' AND `PollID` = $pollIndex";
      do_query($query);
      action("reset", $pollIndex);
      $messages->addMessage(new Message("success",
        "Your vote has been removed. Don't forget to vote again!"));
      $voted = 0;
    }

    # Submit the vote.
    if (isset($_POST['response']) && !$voted) {
      $query = "SELECT * FROM `poll_options` WHERE `PollID` = $pollIndex";
      if (!num_rows(do_query($query))) {
        $messages->addMessage(new Message("error",
          "Invalid option: perhaps the poll was deleted while you were voting?"));
      } else {
        if (is_array($_POST['response'])) {
          foreach ($_POST['response'] as $response) {
            $query = "INSERT INTO `poll_votes` VALUES ($pollIndex, $response, '$username')";
            do_query($query);
          }
        } else {
          $query = "INSERT INTO `poll_votes` VALUES ($pollIndex, ${_POST["response"]}, '$username')";
          do_query($query);
        }
        action("voted", $pollIndex, $_POST['response']);
        $voted = 1;
        $messages->addMessage(new Message("success", "You have successfully voted on this poll."));
      }
    }

    # Find the question (to life, the universe and everything)
    $question = $polls[$pollIndex]['Question'];
    $hidden = $polls[$pollIndex]['Hidden'];

    if (($user->isLeader() && isset($_GET['reveal'])) || $wget) {
      $hidden = false;
    }

    $tpl->set('question', $question);
    $tpl->set('creator', userpage($polls[$pollIndex]['Creator']));
    $tpl->set('hidden', $hidden);
    $tpl->set('multiple', $polls[$pollIndex]['Multiple']);

    $preview = false;

    # Check if the user is just viewing results
    if ($SEGMENTS[2] == "preview" && !$voted) {
      $voted = true;
      $preview = true;
    }

    if ($wget) {
      $voted = true;
      $preview = false;
    }

    # If the user has voted...
    if ($voted) {

      # Choose a random graph type.
      $graphs = array("Column2D.swf", "Doughnut2D.swf", "Pie2D.swf",
              "Column3D.swf", "Doughnut3D.swf", "Pie3D.swf");
      $graph = $graphs[rand(0,count($graphs)-1)];

      # Look up the avaliable options for this poll.
      $query = "SELECT * FROM `poll_options` WHERE `PollID` = $pollIndex";
      $res = do_query($query);
      $options = array();
      while($row = fetch_row($res)){
        $options[$row["OptionID"]] = array("text"=>$row["Response"], "value"=>0);
      }

      # Figure out how many times each option has been voted for
      $total = fetch_row(do_query("SELECT COUNT(*) FROM `poll_votes` WHERE `PollID` = $pollIndex"));
      $total = intval($total[0]);

      $query = "SELECT COUNT(*), `OptionID` FROM `poll_votes` WHERE `PollID` = $pollIndex GROUP BY `OptionID`";
      $res = do_query($query);
      while($row = fetch_row($res)){
        $options[$row["OptionID"]]["count"] = $row[0];
        $options[$row["OptionID"]]["value"] = (intval($row[0]) / $total) * 100;
      }

      foreach ($options as $key => $value) {
        if (!isset($value['count'])) {
          $options[$key]['count'] = 0;
        }
        if ($hidden) {
          $options[$key]['count'] = "?";
        }
      }

      # Get the complete list of votes for this poll.
      $votes = array();
      $query = "SELECT `UserID`, `OptionID` FROM `poll_votes` WHERE `PollID` = $pollIndex ORDER BY `OptionID` ASC, `UserID` ASC";
      $result = do_query($query);

      while ($row = mysql_fetch_assoc($result)) {
        if ($hidden) {
          if ($username == strtolower($row['UserID'])) {
            $votes[$row['OptionID']][] = "You voted for this option.";
          }
        } else {
          $tempUserID = strtolower($row['UserID']);
          $votes[$row['OptionID']][] = "<a href='/person/$tempUserID' class='pollLink'>{$people[$tempUserID]}</a>";
        }
      }

      $jsData = array();

      foreach ($options as $data) {
        $jsData[] = "{name: \"{$data['text']}\", y: {$data['count']}}";
      }

      $tpl->set("graphData", implode(",\n", $jsData));

      $votesStr = array();

      # Format everything so it can be displayed in the template correctly.
      foreach ($options as $ID => $data) {
        if (!isset($votes[$ID])) {
          $votes[$ID] = array();
        }
      }
      ksort($votes);

      foreach ($votes as $key => $peoplee) {
        $votesStr[$key] = implode(", ", $peoplee);
      }

      foreach ($votesStr as $key => $peoplee) {
        $options[$key]['people'] = $peoplee;
      }

      $tpl->set('options', $options);

    # If the user has not voted...
    } else {

      # Get the list of options for this poll
      $query = fetch_row(do_query("SELECT `Question` FROM `poll_questions` WHERE `ID` = $pollIndex"));
      $tpl->set('question', $query[0]);
      $res = do_query("SELECT * FROM `poll_options` WHERE `PollID` = $pollIndex");
      $options = array();
      while ($row = fetch_row($res)) {
        $options[] = array("id" => $row["OptionID"], "text"=>$row["Response"]);
      }
      $tpl->set('options', $options);
    }

    # Find out if there are any other polls
    $previous = array();
    foreach ($polls as $ID => $question) {
      if ($ID != $pollIndex) {
        $previous[] = array("id" => $ID, "question" => $question["Question"]);
      }
    }

    if (empty($previous)) {
      $previous = false;
    }

    $tpl->set('pollid', $pollIndex, true);
    $tpl->set('previous', $previous, true);
    $tpl->set('voted', $voted, true);
    $tpl->set('preview', $preview, true);
    $tpl->set('poll', true, true);

  }

  $moderation = array();

  # Get the list of polls that need approval
  if ($user->isLeader()) {
    foreach ($pollsMod as $ID => $question) {
      $moderation[] = array("id" => $ID, "question" => $question[1], "creator" => userpage($question[2]));
    }
  }

  if (empty($moderation)) {
    $moderation = false;
  }

  $createLink = false;
  if ($POLL_CREATION || $user->isLeader()) {
    $createLink = true;
  }

  $tpl->set('createLink', $createLink, true);
  $tpl->set('moderation', $moderation, true);

  fetch();
?>
