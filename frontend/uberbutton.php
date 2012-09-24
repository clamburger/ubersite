<?php
  require_once("../includes/start.php");

  $urlParts = explode("/", str_replace("?".$_SERVER["QUERY_STRING"], "",
                                       $_SERVER["REQUEST_URI"]), 3);
  if (count($urlParts !== 3) && $urlParts[0] !== "" &&
      $urlParts[1] !== "uber") {
    header("HTTP/1.1 408 Bad Request"); // Bad request
    die;
  }
  $url = $urlParts[2];
  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // insert or update.
    if (!is_numeric($_POST["uber"])) die;
    $uber = intval($_POST["uber"]);
    $query = "INSERT INTO uber VALUES\n" .
             "('$username', '" . md5($url) . "', " . $uber . ")\n".
             "  ON DUPLICATE KEY UPDATE ubered = $uber";
    do_query($query);
  }

  $query = "SELECT uber.UserID AS UserID, Name, Ubered\n" .
           "FROM uber INNER JOIN people ON uber.UserID = people.UserID\n" .
           "WHERE Url = '" . md5($url) . "' AND Ubered != 0";
  $res = do_query($query);
  $ret = array("ubered"=>false, "count"=>0, "people"=>array());
  $people = array();
  while ($row = fetch_row($res)) {
    if ($row["UserID"] === $username) {
      $ret["ubered"] = true;
      array_unshift($people, "You");
    } else {
      $people[] = "<a href='/person.php?id=" . $row["UserID"] . "'>" .
                  $row["Name"] . "</a>";
    }
    $ret["count"]++;
  }
  switch ($ret["count"]) {
    case 3:
      $ret["people"] = "${people[0]}, ${people[1]} and ${people[2]}";
      break;
    case 2:
      $ret["people"] = "${people[0]} and ${people[1]}";
      break;
    case 1:
      $ret["people"] = $people[0];
      break;
    default:
      $ret["people"] = implode(", ", array_slice($people, 0, 2));
      $ret["people"] .= " and " . ($ret["count"] - 2) . " others";
      break;
  }
  print json_encode($ret);
?>
