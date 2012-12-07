<?php
  include_once("includes/start.php");
  $title = "Change Password";
  $tpl->set('title', $title);

  if ($user->needsPasswordChange()) {
    $messages->removeAll("alert");
    $messages->addMessage(new Message("alert",
      "Since this is the first time you've logged in, you'll need to change your password" .
        " before you can do anything else."));
  }

  if (isset($_POST["oldpassword"]) && isset($_POST["newpassword"]) && isset($_POST["retypedpassword"])) {
    # Check if the passwords match
    if ($_POST["newpassword"] != $_POST["retypedpassword"]) {
      $messages->addMessage(new Message("error", "Passwords do not match."));
    } else {
      $result = changePassword($username, $_POST['oldpassword'], $_POST['newpassword']);
      if ($result == "password") {
        $messages->addMessage(new Message("error", "Incorrect old password."));
      } else if ($result == "success") {
        storeMessage("success", "Password successfully changed.");
        action("change");
        refresh();
      } else {
        $messages->addMessage(new Message("error", $WRAPPER_ERROR));
      }
    }
  } else {
  }

  # Reset the specified user's password
  if ($user->isLeader() && !$user->needsPasswordChange() && isset($_POST["userreset"])) {
    $result = resetPassword($_POST['userreset']);
    if ($result == "success") {
      action("reset", $_POST['userreset']);
      storeMessage("success", "Password for {$_POST['userreset']} successfully reset.");
      refresh();
    } else {
      $messages->addMessage(new Message("error", $WRAPPER_ERROR));
    }
  }
  $twig->addGlobal("users", $people);
  $DISABLE_UBER_BUTTON = true;
  fetch();
?>
