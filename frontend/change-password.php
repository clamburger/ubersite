<?php
  include_once("includes/start.php");
  $title = "Change Password";
  $tpl->set('title', $title);

  if (isset($_POST["oldpassword"]) && isset($_POST["newpassword"]) && isset($_POST["retypedpassword"])) {
    # Check if the passwords match
    if ($_POST["newpassword"] != $_POST["retypedpassword"]) {
      $tpl->set("error", "Passwords do not match.");
    } else {
      $result = changePassword($username, $_POST['oldpassword'], $_POST['newpassword']);
      if ($result == "password") {
        $tpl->set("error", "Incorrect old password.");
        $tpl->set('alert', false);
      } else if ($result == "success") {
        storeMessage("success", "Password successfully changed.");
        action("change");
        refresh();
      } else {
        $tpl->set("error", $WRAPPER_ERROR);
      }
    }
  } else {
    if ($user->needsPasswordChange()) {
      $alert = "Since this is the first time you've logged in, you'll need to change your password"
        . " before you can do anything else.";
      $tpl->set('alert', $alert, true);
    }
  }

  # Reset the specified user's password
  if ($user->isLeader() && !$user->needsPasswordChange() && isset($_POST["userreset"])) {
    $result = resetPassword($_POST['userreset']);
    if ($result == "success") {
      action("reset", $_POST['userreset']);
      storeMessage("success", "Password for {$_POST['userreset']} successfully reset.");
      refresh();
    } else {
      $tpl->set("error", $WRAPPER_ERROR);
    }
  }
  $twig->addGlobal("users", $people);
  $DISABLE_UBER_BUTTON = true;
  fetch();
?>
