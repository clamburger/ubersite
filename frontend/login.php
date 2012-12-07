<?php
  include_once("includes/start.php");

  $title = "Login";
  $tpl->set('title', $title);

  if ($SEGMENTS[1]) {
    $redirect = $SEGMENTS[1];
  } else {
    $redirect = "index";
  }

  # If the user is already logged in, redirect them to the index.
  if (isset($_SESSION['username'])) {
    header("Location: /$redirect");
  }

  $tpl->set("form-username", false);

  if (isset($_POST["username"]) && isset($_POST["password"])) {
    # Look up the user and validate them.
    $username = strtolower(trim($_POST['username']));
    $result = checkLogin($username, $_POST['password']);
    # Everything checks out.
    if ($result == "success") {
      $_SESSION['username'] = $username;
      header("Location: $redirect");
    # Incorrect password.
    } else if ($result == "password") {
      $messages->addMessage(new Message("error", "The specified password was incorrect."));
      $tpl->set("form-username", $_POST['username']);
    # Username doesn't exist.
    } else if ($result == "username") {
      $messages->addMessage(new Message("error", "That user does not exist."));
    # Either username or password was incorrect.
    } else if ($result == "incorrect") {
      $messages->addMessage(new Message("error", "Invalid username or password."));
      $tpl->set("form-username", $_POST['username']);
    # Login is valid, but user is not in the database.
    } else if ($result == "database") {
      $messages->addMessage(new Message("error",
        "Your login details are valid, but you do not have an entry in the database. " .
        "Please contact a tech leader for assistance."));
      $tpl->set("form-username", $_POST['username']);
    } else {
      $messages->addMessage(new Message("error", $WRAPPER_ERROR));
    }
  }

  $DISABLE_UBER_BUTTON = true;
  fetch();
?>
