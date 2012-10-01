<?php
  require_once("../includes/start.php");
  if (!$leader) {
    die;
  }

  function comparePhotos($a, $b) {
    return strcmp($a["class"], $b["class"]);
  }

  function getEvents() {
    $result = array();
    $query = "SELECT day, activity FROM timetable";
    $res = do_query($query);
    while ($row = fetch_row($res)) {
      $result[] = $row["day"] . ": " . $row["activity"];
    }
    return $result;
  }

  function getEventId($filename) {
    $query = "SELECT DateTaken FROM photo_processing\n" .
             "WHERE FileName = '$filename'";
    $row = fetch_row(do_query($query));
    $date = strtotime($row["DateTaken"]);
    $day = date("l", $date);
    $time = date("H:i", $date);
    $query = "SELECT Activity FROM timetable\n" .
             "WHERE Day = '$day' AND Start < '$time' AND End > '$time'";
    $res = fetch_row(do_query($query));
    $activity = $res["Activity"];
    return array_search("$day: $activity", getEvents());
  }

  function getUnprocessedPhotos() {
    $photos = array();
    $query = "SELECT FileName, CampWebsite, DateTaken FROM photo_processing\n" .
             "WHERE DateReviewed IS NULL";
    $res = do_query($query);
    while ($row = fetch_row($res)) {
      $class = "photoFrame";
      if (!is_null($row["CampWebsite"])) {
        $class .= intval($row["CampWebsite"]) ? " publish" : " trash";
      }
      $photos[] = array("filename"=> $row["FileName"],
                        "taken"=> $row["DateTaken"],
                        "class"=> $class);
    }
    usort($photos, "comparePhotos");
    return $photos;
  }

  function publish($fn) {
    global $username;
    $query = "UPDATE photo_processing\n" .
             "SET Reviewer = '$username', CampWebsite = 1\n" .
             "WHERE Filename = '$fn'";
    if (!do_query($query)) {
      header("HTTP/1.1 404 Not Found");
      die("No such image.");
    }
    // Update tags, event.
    $event = $_POST["event"];
    if ($event) {
      do_query("INSERT INTO photo_event_tags VALUES('$fn', '$event')");
    }

    $tags = array();
    $people = explode(",", $_POST["people"]);
    foreach ($people as $person) {
      $tags[] = "('$fn', '$person')";
    }
    if (count($tags)) {
      do_query("INSERT INTO photo_tags VALUES" . implode(",", $tags));
    }
  }

  function publishRest($fn) {
    global $username;
    $query = "UPDATE photo_processing\n" .
             "SET Reviewer = '$username', CampWebsite = 1\n" .
             "WHERE CampWebsite IS NULL AND DateReviewed IS NULL";
    $res = do_query($query);
    echo $res;
  }

  function trash($fn) {
    global $username;
    $query = "UPDATE photo_processing\n" .
             "SET Reviewer = '$username', CampWebsite = 0\n" .
             "WHERE Filename = '$fn'";
    if (!do_query($query)) {
      header("HTTP/1.1 404 Not Found");
      die("No such image.");
    }
  }

  function finalise() {
    global $username;
    // Copy files across.
    $query = "SELECT Filename, CampWebsite FROM photo_processing\n" .
             "WHERE Reviewer = '$username' AND CampWebsite IS NOT NULL\n" .
             "  AND DateReviewed IS NULL";
    $res = do_query($query);
    $finalised = array();
    while ($row = fetch_row($res)) {
      $fn = $row["Filename"];
      $finalised[] = "'$fn'";
      if (intval($row["CampWebsite"]) === 0) {
        continue;
      }
      if (!copy("../camp-data/uploads/$fn", "../camp-data/photos/$fn")) {
        header("HTTP/1.1 503 Internal Server Error");
        die("Couldn't write $fn");
      }
    }
    $num = do_query("UPDATE photo_processing SET DateReviewed = NOW()\n" .
                    "WHERE Filename IN (" . implode(", ", $finalised) . ")");

  }

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $urlParts = getUrlParts("kindreds-lab.php", array("method", "fn"), 1);
    extract($urlParts);
    // Validate.
    switch ($method) {
      case "publish":
      case "trash":
        if (!isset($fn)) {
          header("HTTP/1.1 408 Bad Request");
          die("No filename given for $method");
        }
        break;
      case "publishrest":
      case "finalise":
        break;
      default:
        header("HTTP/1.1 408 Bad Request");
        die("No matching function.");
    }
    switch ($method) {
      case "publish":
        publish($fn);
        break;
      case "publishrest":
        publishRest();
        break;
      case "trash":
        trash($fn);
        break;
      case "finalise":
        finalise();
        break;
    }
    $photos = getUnprocessedPhotos();
    $unproc = 0;
    foreach ($photos as $photo) {
      if ($photo["class"] !== "photoFrame") break;
      $unproc++;
    }
    print json_encode(array("photos"=>$photos, "count"=>$unproc));
    die;
  }

  $urlParts = getUrlParts("kindreds-lab.php", array("method", "fn"));
  if (isset($urlParts["method"])) {
    switch ($urlParts["method"]) {
      case "event":
        print getEventId($urlParts["fn"]);
        die;
    }
  }

  $photos = getUnprocessedPhotos();
  $unproc = 0;
  foreach ($photos as $photo) {
    if ($photo["class"] !== "photoFrame") break;
    $unproc++;
  }
  $tpl->set("title", "Photo Processor");
  $tpl->set("events", getEvents());
  $tpl->set("people", json_encode($people));
  $tpl->set("rPeople", json_encode(array_flip($people)));
  $tpl->set("pictures", $photos);
  $tpl->set("current", $unproc ? $photos[0] : array("filename"=>""));
  $tpl->set("number", $unproc, true);
  $tpl->set("suffix", $unproc == 1 ? "" : "s");
  $tpl->set("nop", false, true);
  $tpl->set("js", "photo-processing.js");
  fetch();
?>
