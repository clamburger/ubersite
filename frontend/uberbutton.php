<?php
  require_once("../includes/start.php");

  $urlParts = explode("/", str_replace("?".$_SERVER["QUERY_STRING"], "",
                                       $_SERVER["REQUEST_URI"]), 3);
  if (count($urlParts !== 3) && $urlParts[0] !== "" &&
      $urlParts[1] !== "uber") {
    header("HTTP/1.1 408 Bad Request"); // Bad request
    die;
  }
  $url = '/' . $urlParts[2];
  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // insert or update.
    if (!is_numeric($_POST["uber"])) die;
    $uber = intval($_POST["uber"]);
    $query = "INSERT INTO uber VALUES\n" .
             "('$username', '" . md5($url) . "', " . $uber . ")\n".
             "  ON DUPLICATE KEY UPDATE ubered = $uber";
    do_query($query);
  }

  print getUberJson($url);
?>
