<?php

include("wrappers.php");

$LINEBREAKS = array("\r\n", "\n", "\r");

function suffix($string, $integer) {
  if ((int)$integer != 1 || (float)$integer != 1.0) {
    $string .= "s";
  }
  return $string;
}

function md5_salted($password) {
  global $SALT;
  return hash("whirlpool", $SALT.$password);
}

# Generate the "what's on" box
function whats_on(){
  $today = date("l");
  $time = date("H:i");
  $query = "SELECT *, (TIME_TO_SEC('$time') - TIME_TO_SEC(`Start`)) / (TIME_TO_SEC(`End`) - TIME_TO_SEC(`Start`)) * 150 ";
  $query .= " AS `Percent` FROM `timetable` WHERE `Day` = '$today' AND `Start` <= '$time' AND `End` > '$time'";
  $result = do_query($query);
  if (num_rows($result)) {
    return fetch_row($result);
  }
  return array("Time" => "$time");
}

set_time_limit(0);

function generate_thumbnail($filename, $width, $height) {
  require_once "../libraries/phpThumb/ThumbLib.inc.php";

  global $script;
  global $thumbnailsGenerated;
  global $thumbnailLimit;
  global $thumbnailError;

  # Generate the filename for the thumbnail
  $fileinfo = pathinfo($filename);

  # pathinfo() returns different values depending on the OS
  if (isset($fileinfo['filename'])) {
    $thumbFile = "{$fileinfo['filename']}-({$width}x{$height}).{$fileinfo['extension']}";
  } else {
    $fileBase = substr($fileinfo['basename'], 0, -strlen($fileinfo['extension'])-1);
    $thumbFile = "$fileBase-({$width}x{$height}).{$fileinfo['extension']}";
  }

  # If the file already exists, don't need to do anything
  if (!file_exists("../camp-data/photos/cache/$thumbFile")) {

    if (isset($thumbnailLimit) && $thumbnailsGenerated >= $thumbnailLimit && $thumbnailLimit != -1) {
      return false;
    }

    // Generate a thumbnail using a phpThumb object
    $phpThumb = PhpThumbFactory::create($filename);
    $phpThumb->resize($width, $height);
    $phpThumb->save("../camp-data/photos/cache/$thumbFile");

    $thumbnailsGenerated++;
  }

  # Return the name of the file
  return $thumbFile;
}

function action($action, $firstID = false, $secondID = false, $adminAction = false) {
  global $username;

  $page = explode("/", $_SERVER['SCRIPT_NAME']);
  $page = substr($page[count($page) - 1], 0, -4);

  if (!$firstID) {
    $firstID = 'NULL';
  } else {
    $firstID = "'$firstID'";
  }

  if (!$secondID) {
    $secondID = 'NULL';
  } else {
    $secondID = "'$secondID'";
  }

  if (!$adminAction) {
    $adminAction = 0;
  } else {
    $adminAction = 1;
  }

  $query = "INSERT INTO `actions` (`UserID`, `Timestamp`, `Page`, `Action`, `SpecificID1`, `SpecificID2`, `AdminAction`)";
  $query .= " VALUES ('$username', NOW(), '$page', '$action', $firstID, $secondID, $adminAction)";
  do_query($query);
}

# Give this function a date and it'll tell you how long ago it was
function howlong($date) {

  $now  = time();
$past  = strtotime($date);
  $time  = date("g:i A", $past);

  $difference  = $now - $past;

  if ($difference >= 60*60) {
    $dayDiff  = (date("U", strtotime("today")) - date("U", strtotime("today", $past))) / (60*60*24);
    $pastName  = date("l", $past);
  }

  if ($difference < -60) {
    $string = "In the future";
  } else if ($difference < 60) {
    $string = "Less than a minute ago";
  } else if ($difference < 60*2) {
    $string = "1 minute ago";
  } else if ($difference < 60*60) {
    $string = floor($difference / 60);
    $string .= " minutes ago";
  } else if ($dayDiff == 0) {
    $string = "Today at $time";
  } else if ($dayDiff == 1) {
    $string = "Yesterday at $time";
  } else if ($dayDiff < 7) {
    $string = "$pastName at $time";
  } else {
    $string = "Over a week ago";
  }

  return $string;
}

function userpage($userID, $unbold = false) {
  global $people;

  if (!isset($people[$userID])) {
    $name = $userID;
  } else {
    $name = str_replace(" ", "&nbsp;", $people[$userID]);
  }

  if ($unbold) {
    return "<a href='/person.php?id=$userID' class='pollLink'>$name</a>";
  } else {
    return "<a href='/person.php?id=$userID'>$name</a>";
  }
}

function checkCaptions() {
  $query = "SELECT COUNT(*) FROM `photo_captions` WHERE `Status` = 0";
  $result = do_query($query);
  $row = fetch_row($result);

  return $row[0];
}

# This will both make it safe for input into the database and safe to output again.
function userInput($input, $mysql = true) {
  $output = $input;
  if ($mysql) {
    $output = mysql_real_escape_string($output);
  }
  $output = str_replace(array("<", ">", '"'), array("&lt;", "&gt;", "&quot;"), $output);
  $output = trim($output);
  return $output;
}


# <img src="data_url('filename.png', 'image/png');" />
function dataURI($file, $mime) {
  $contents = file_get_contents($file);
  $base64   = base64_encode($contents);
  return "data:$mime;base64,$base64";
}

function fetch($filename = false, $HTML = false) {
  global $tpl;
  global $queryCount;
  global $queryList;

  if (!$filename) {
    $filename = basename($_SERVER["SCRIPT_FILENAME"], ".php");
  }

  $queryHTML = "";
  foreach ($queryList as $q) {
    $queryHTML .= "<li>$q</li>";
  }

  $tpl->set('queryCount', $queryCount);
  $tpl->set('queries', $queryHTML);
  if (!isset($tpl->scalars['contenttitle'])) {
    $tpl->set('contenttitle', $tpl->scalars['title']);
  }

  $tpl->set('titleuber', uberButton());

  if ($HTML) {
    $tpl->set('content', $HTML);
  } else {

    $tpl->set('content', $tpl->fetch("../templates/$filename.tpl"));
  }

  $page = $tpl->fetch('../templates/master.tpl');
  // Clean up any extreneous <tag:s.
  echo preg_replace("/\<tag:[^\/]* \/>/", "", $page);
}

# Stores a success or error message that will be shown on the next page load.
# Can also store an abritrary value which will be stored in $storedValue.
# Can only store one message at a time.
function storeMessage($type, $string, $value = null) {
  $_SESSION['message'] = array($type, $string);
  if ($value !== NULL) {
    $_SESSION['message'][2] = $value;
  }
}

function refresh() {
  header("Location: http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}");
}

function getUrlParts($expectedUrl, $names, $require=0) {
  $urlParts = explode("/", str_replace("?".$_SERVER["QUERY_STRING"], "",
                                       $_SERVER["REQUEST_URI"]));
  if ($expectedUrl && !in_array($urlParts[1], $expectedUrl, true)) {
    return false;
  }

  $return = array();
  for ($i = 0; $i < count($names); ++$i) {
    if (($i + 2) >= count($urlParts)) {
      if ($i < $require) {
        header("HTTP/1.1 408 Bad Request"); // Bad request
        return false;
      }
      break;
    }
    $return[$names[$i]] = $urlParts[$i+2];
  }
  return $return;
}

function getUberJson($url) {
  global $username;
  $query = "SELECT uber.UserID AS UserID, Name, Ubered\n" .

           "FROM uber INNER JOIN people ON uber.UserID = people.UserID\n" .
           "WHERE Url = '" . md5($url) . "' AND Ubered != 0";
  //echo $query;
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
  return json_encode($ret);
}

function uberButton($async=TRUE, $url=NULL) {
  if ($url === NULL) {
    // Set to the current path
    $url = $_SERVER["REQUEST_URI"];
  }
  $result = "<div class='uber'>\n" .
            "  <span class='count'>0</span>&uuml;ber\n" .
            "  <script type='text/javascript'>\n" .
            "    var scripts = document.getElementsByTagName('script');\n";
  if ($async) {
    $result .= "    new UberButton(scripts[scripts.length - 1].parentNode,\n" .
               "                   '$url');\n";
  } else {
    $obj = getUberJson($url);
    $result .= "    new UberButton(scripts[scripts.length - 1].parentNode,\n" .
               "                   '$url', $obj);\n";
  }
  $result .= "  </script>\n" .
             "</div>";
  return $result;
}
?>
