<?php
  include_once("../includes/start.php");
  $title = "Login";
  $tpl->set('title', $title);

  if (isset($_GET['url']) && file_exists($_GET['url'])) {
    $redirect = $_GET['url'];
  } else {
    $redirect = "index.php";
  }

  # If the user is already logged in, redirect them to the index.
  if (isset($_SESSION['username'])) {
    header("Location: $redirect");
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
      $tpl->set("error", "The specified password was incorrect.");
      $tpl->set("form-username", $_POST['username']);
    # Username doesn't exist.
    } else if ($result == "username") {
      $tpl->set("error", "That user does not exist.");
    # Either username or password was incorrect.
    } else if ($result == "incorrect") {
      $tpl->set("error", "Invalid username or password.");
      $tpl->set("form-username", $_POST['username']);
    # Login is valid, but user is not in the database.
    } else if ($result == "database") {
      $tpl->set("error", "Your login details are valid, but you do not have an entry in the database. Please contact a tech leader for assistance.");
      $tpl->set("form-username", $_POST['username']);
    } else {
      $tpl->set("error", $WRAPPER_ERROR);
    }
  }

  fetch();
?>
