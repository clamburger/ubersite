<?php
  # Set some default values and include some files
  error_reporting(E_ALL | E_STRICT);
  ini_set('display_errors', 'On');
  if (!file_exists("camp-data/config/config.json")) {
    header("Location: /setup/setup.php");
  }

  require_once("libraries/bTemplate.php");
  $tpl = new bTemplate();

  include_once("jsonLoader.php");

  date_default_timezone_set($TIMEZONE);

  include_once("database.php");

  $tpl->set("campname", $CAMP_NAME);
  $tpl->set("campyear", $CAMP_YEAR);
  $tpl->set("stylesheet", $STYLESHEET);

  $leader = false;
  $admin = false;
  $loggedIn = false;
  $feedback = false;
  $alert = array();
  $error = array();
  $warning = false;
  $success = false;
  $emulation = false;
  $questionnaire = false;
  $screenWidth = 1024;
  $idleTime = 60 * 45; // 15 minutes
  $script = explode("/", $_SERVER['SCRIPT_NAME']);
  $pageName = $PAGE;

  $queryCount = 0;

  # Stupid magic quote workaround
  if (get_magic_quotes_gpc()) {
    foreach ($_POST as $key => $value) {
      $_POST[$key] = stripslashes($value);
    }
  }

  # Some helper functions
  include_once("functions.php");

  # Process user session and details
  session_start();

  if (isset($_SESSION['loggedout'])) {
    $warning = "You were idle for too long and were automatically logged out.";
    unset($_SESSION['loggedout']);
  }

  if (isset($_SESSION['message'])) {
    if ($_SESSION['message'][0] == "success") {
      $success = $_SESSION['message'][1];
    } else if ($_SESSION['message'][0] == "warning") {
      $warning = $_SESSION['message'][1];
    }
    if (isset($_SESSION['message'][2])) {
      $storedValue = $_SESSION['message'][2];
    }
    unset($_SESSION['message']);
  }

  # Populate array of users (UserID => Name)
  $query = "SELECT `UserID`, `Name` FROM `people`";
  $result = do_query($query);
  $people = array();
  while ($row = fetch_row($result)) {
    $people[$row['UserID']] = $row['Name'];
  }

  $query = "SELECT `UserID`, `Group` FROM `people_groups`";
  $result = do_query($query);
  $groups = array();
  while ($row = fetch_row($result)) {
    if (!isset($groups[$row['UserID']])) {
      $groups[$row['UserID']] = array();
    }
    $groups[$row['UserID']][] = $row['Group'];
  }

  if (substr($_SERVER['HTTP_USER_AGENT'], 0, 5) == "Wget/") {
    $wget = true;
    $_SESSION['username'] = "wget";
    $_SESSION['wget'] = true;
    $leader = 0;
    $admin = 0;
  } else {
    if (isset($_SESSION['wget'])) {
      echo "You are coming out of wget mode. Please follow through to the <a href='/logout'>logout</a> page.";
      die();
    }
    $wget = false;
    if (isset($_SESSION['username'])) {
      $username = $_SESSION['username'];
      // If the logged in user no longer exists, something bad happened.
      if (!isset($people[$username])) {
        header("Location: /logout");
      }
      $result = fetch_row(do_query("SELECT `Category`, `Admin` FROM people WHERE UserID = '$username'"));
      if ($result['Category'] == 'leader' || $result['Category'] == 'director') {
        $leader = 1;
      } else {
        $leader = 0;
      }
      $admin = $result['Admin'];
      # Handle emulation
      if ($admin && isset($_GET['emulate'])) {
        $_SESSION['emulate'] = $_GET['emulate'];
      }

      if ($admin && isset($_GET['friday'])) {
        if (empty($_GET['friday'])) {
          unset($_SESSION['friday']);
        } else {
          $_SESSION['friday'] = true;
        }
      }

      if ($admin && isset($_SESSION['friday'])) {
        $emulation[] = "Friday";
      }

      if ($admin && isset($_GET['nanobyte'])) {
        if (empty($_GET['nanobyte'])) {
          unset($_SESSION['nanobyte']);
        } else {
          $_SESSION['nanobyte'] = true;
        }
      }

      if ($admin && isset($_SESSION['nanobyte'])) {
        $NANOBYTE = true;
        $emulation[] = "Nanobyte";
      }

      if ($admin && isset($_SESSION['emulate'])) {
        if ($_SESSION['emulate'] == "camper") {
          $leader = 0;
          $admin = 0;
          $emulation[] = "Camper";
        } else if ($_SESSION['emulate'] == "leader") {
          $leader = 1;
          $admin = 0;
          $emulation[] = "Leader";
        } else {
          unset($_SESSION['emulate']);
        }
      }

      $loggedIn = true;

      if ($emulation) {
        $emulation = implode(", ", $emulation);
      }

    } else {
      /*$secondSQL = mysql_connect("192.168.0.254", "root", "mete0r");
      mysql_select_db("ubertweak", $secondSQL);

      $query = "SELECT * FROM `logins` WHERE `IP` = '{$_SERVER['REMOTE_ADDR']}'";
      $result = do_query($query);
      if (mysql_num_rows($result) === 0) {
        //$error[] = "Could not detect user. Please contact a tech leader. (IP: {$_SERVER['REMOTE_ADDR']})";
        $error[] = "Could not detect user. You will need to login the old fashioned way. (IP: {$_SERVER['REMOTE_ADDR']})";
      } else {
        $row = fetch_row($result);
        if (!isset($people[$row['Username']])) {
          //$error[] = "Could not detect user. Please contact a tech leader. (IP: {$_SERVER['REMOTE_ADDR']}, User: {$row['Username']})";
          $error[] = "Could not detect user. You will need to login the old fashioned way. (IP: {$_SERVER['REMOTE_ADDR']}, User: {$row['Username']})";
        } else {
          $_SESSION['username'] = $row['Username'];
          refresh();
        }
      }

      mysql_connect("localhost", "root", "gideon");
      mysql_select_db("ubertweak_n11");*/

      # Redirect to login page if not logged in
      if ($pageName != "login") {
        if ($pageName == "logout") {
          header("Location: /login");
        } else {
          header("Location: /login/$pageName");
        }
      }
    }
  }
  $curTime = time();

  # Disable error reporting for non-admins
  if (!$admin) {
     error_reporting(0);
  }

error_reporting(E_ALL);

  # If the last page load was more than 15 minutes ago, log the user out
  if (isset($_SESSION['time']) && !$DEVELOPER_MODE) {
    $difference = $curTime - $_SESSION['time'];
    if ($difference > $idleTime && $loggedIn && !$wget && $AUTHENTCATION_TYPE != "ssh") {
      session_destroy();
      session_start();

      # If it was more than 6 hours ago they probably don't care any more.
      if ($difference < 60*60*60*6) {
        $_SESSION['loggedout'] = true;
      }
      header("Location: /login");
    }
  }

  # Find out what's currently happening
  $_SESSION['time'] = $curTime;

  if (!$wget) {
    $activity = whats_on();
    $tpl->set('whatson', $activity);
    if (isset($activity['Activity'])) {
      $tpl->set('enabled', true, true);
    } else {
      $tpl->set('enabled', false, true);
    }
  }

  # FRIDAY, FRIDAY, GOTTA GET DOWN ON FRIDAY
  # EVERYBODY'S LOOKING FORWARD TO THE WEEKEND
  $TGIF = false;

  $day = date("D");
  $hour = date("G");

  if (date("D") == "Fri" || isset($_SESSION['friday'])) {
    $TGIF = true;
    $day = "Fri";
  }

  eval('$questionnaire = ' . $QUESTIONNAIRE_CONDITION . ';');

  if (!isset($_SESSION['screenWidth']) && !isset($_GET['screenWidth'])) {
    $tpl->set('processWidth', true, true);
  } else {
    if (isset($_GET['screenWidth'])) {
      $width = $_GET['screenWidth'];
      if (is_numeric($width) && (int)$width == $width) {
        $_SESSION['screenWidth'] = $width;
      } else {
        // If the input is invalid, assume worst-case scenario.
        $_SESSION['screenWidth'] = 1024;
      }
    }

    // The screen width should never be below 1024
    $screenWidth = max(1024, $_SESSION['screenWidth']);
    $tpl->set('processWidth', false, true);
  }

  if ($loggedIn && !$wget) {
    $passwordNeedsChanging = false;

    # Check if the user needs to change their password
    $query = "SELECT `PasswordChanged` FROM `people` WHERE `UserID` = '$username'";
    $result = do_query($query);
    $row = fetch_row($result);
    if ($row[0] === "0" and $AUTH_TYPE == "mysql") {
      if ($pageName != "change-password") {
        header("Location: /change-password");
      }
      $passwordNeedsChanging = true;
    }

    # Check if there are any captions that need to be approved
    if (($pageName != "photos") and ($pageName != "view-photo") and ($admin)) {
      $count = checkCaptions();
      if ($count > 0) {
        $alert[] = "You have $count <a href='/photos/admin'>unapproved photo ".suffix("caption", $count)."</a> to approve or destroy.";
      }
    }

    # Check if there are any quotes that need to be approved
    if (($pageName != "quotes") and ($admin)) {
      $query = "SELECT COUNT(*) FROM `quotes` WHERE `Status` = 0";
      $result = do_query($query);
      $row = fetch_row($result);
      if ($row[0] > 0) {
        $alert[] = "You have {$row[0]} <a href='/quotes'>unapproved ".suffix("quote", $row[0])."</a> to approve or destroy.";
      }
    }

    # Check if there are any polls that need to be approve
    if (($pageName != "polls") and ($admin)) {
      $query = "SELECT COUNT(*) FROM `poll_questions` WHERE `Status` = 0";
      $result = do_query($query);
      $row = fetch_row($result);
      if ($row[0] > 0) {
        $alert[] = "You have {$row[0]} <a href='/polls'>unapproved ".suffix("poll", $row[0])."</a> to approve or destroy.";
      }
    }

    //if (($pageName != "photo_processing") and ($admin) and (!$DEVELOPER_MODE)) {
    //  $query = "SELECT COUNT(*) FROM `photo_processing` WHERE `Reviewer` IS NULL";
    //  $result = do_query($query);
    //  $row = fetch_row($result);
    //  if ($row[0] > 0) {
    //    $alert[] = "There are {$row[0]} <a href='/photo_processing'>unreviewed ".suffix("photo", $row[0])."</a> that need processing.";
    //  }
    //}

    # Check if the user has filled in their profile
    if (!isset($_GET['id'])) {
      $_GET['id'] = false;
    }

    if (($pageName != "person") and ($_GET['id'] != $username)) {
      if ($TGIF && $CONTACT_DETAILS) {
        $query = "SELECT COUNT(*) FROM `contacts` WHERE `UserID` = '$username'";
        $result = do_query($query);
        $row = fetch_row($result);
        if ($row[0] === '0') {
          $alert[] = "Don't forget to fill in your <a href='/person/$username'>contact information</a> if you want to keep in touch with other guys on camp! (Don't click the link if you are in the middle of the questionnaire though!)";
        }
      }
      if (!$TGIF) {
        $query = "SELECT `InfoFilled` FROM `people` WHERE `UserID` = '$username'";
        $result = do_query($query);
        $row = fetch_row($result);
        if ($row[0] === '0') {
          # Intro games check
          if (date("D") == "Sun") {
            $_SESSION['message'] = array("warning", "Hold it! You need to fill in your profile before you can go any further.");
            header("Location: /person/$username");
          }
          $alert[] = "You have not yet filled in your <a href='/person/$username'>about page</a>: why not do that now?";
        }
      }
    }
    if ($_GET['id'] === false) {
      unset($_GET['id']);
    }

    if ($DEVELOPER_MODE && $admin) {
      $questionnaire = true;
    }

    # Check if the user has submitted their questionnaire
    if ($pageName != $QUESTIONNAIRE_PAGE && $questionnaire && !$leader) {
      if (num_rows(do_query("SELECT `UserID` FROM `questionnaire` WHERE `UserID` = '$username'")) === 0) {
        $alert[] = "You have not yet filled in your <a href='$QUESTIONNAIRE_PAGE'>questionnaire</a>. Please fill it out now.";
        $questionnaire = true;
      }
    }

    # Check if there has been any feedback submitted for the questionnaire yet
    if ($leader && num_rows(do_query("SELECT `UserID` FROM `questionnaire` INNER JOIN `people` USING(`UserID`) WHERE `category` = 'camper'"))) {
      $feedback = true;
    }

    /*if ($admin) {
      $feedback = true;
    }*/

    # Record the page access
    $page = $PAGE;
    $reqString = userInput($_SERVER['REQUEST_URI']);
    $userAgent = userInput($_SERVER['HTTP_USER_AGENT']);
    $filename = userInput($_SERVER['SCRIPT_FILENAME']);
    if (isset($_SERVER['HTTP_REFERER'])) {
      $refer = "'".userInput($_SERVER['HTTP_REFERER'])."'";
    } else {
      $refer = "NULL";
    }

    # Don't bother recording automatic page refreshes.
    if (substr($reqString, -11) != "autorefresh") {
      $query = "INSERT INTO `access` (`Timestamp`, `UserID`, `Page`, `RequestString`, `RequestMethod`, `IP`, `UserAgent`, `Filename`, `Refer`) ";
      $query .= "VALUES (NOW(), '$username', '$page', '$reqString', '{$_SERVER['REQUEST_METHOD']}', '{$_SERVER['REMOTE_ADDR']}',";
      $query .= "'$userAgent', '$filename', $refer)";
      do_query($query);
    }

  }

  if (isset($_GET['standalone'])) {
    $tpl->set('standalone', false, true);
    $wget = true;
    $admin = false;
    $standalone = true;
    $tpl->set('standalone-logo', dataURI("resources/img/logo.png", "image/png"));
    $tpl->set('standalone-icon', dataURI("resources/img/icon.png", "image/png"));
    $layoutCSS = file_get_contents("resources/css/layout.css");
    $colourCSS = file_get_contents("resources/css/$STYLESHEET.css");
    $tpl->set('standalone-style', $layoutCSS . "\n\n" . $colourCSS);
  } else {
    $tpl->set('standalone', true, true);
    $standalone = false;
  }

  # Combine any alert texts
  if (count($alert) == 0) {
    $alert = false;
  } else {
    $alert = implode("<br />", $alert);
  }

  # Combine any error texts
  if (count($error) == 0) {
    $error = false;
  } else {
    $error = implode("<br />", $error);
  }

  # If the position is equal to an existing position it will be made into a child.
  # If the parent is not specified, it will be put under "Other Stuff".
  # Items are added in order of priority (zero is the best priority).

  $menu = json_decode(file_get_contents("camp-data/config/menu.json"), true);

  $pages = array();
  foreach ($menu as $filename => $item) {
    $itemArray = array(
        "name" => false,
        "pixels" => 0,
        "priority" => 0,
        "special" => false,
        "longName" => false,
        "parent" => false,
        "requirements" => true);

    if (isset($item["requirements"])) {
      eval('$item["requirements"] = ' . $item["requirements"] . ';');
    }

    if (!isset($item["longName"])) {
      $item["longName"] = $item["name"];
    }
    $pages[$filename] = array_merge($itemArray, $item);
  }

  // Magic numbers!
  $availablePixels = $screenWidth - 17 - 10 - 65 - 226;
  if ($admin && $DEVELOPER_MODE) {
    $availablePixels -= 45;
  }

  // Step 1: get rid of all the pages we aren't going to use.
  foreach ($pages as $pageName => $information) {
    if (!$information["requirements"]) {
      unset($pages[$pageName]);
    }
  }

  $menu = array();
  $children = array();
  $couldNotAdd = array();
  $additionLog = array();

  // Step 2: loop through each priority, adding items as we go.
  $priority = 0;
  while ($priority <= 9) {
    foreach ($pages as $pageName => $information) {
      if ($pageName == "other-stuff") {
        continue; // Special case
      }
      if ($information["priority"] === $priority) {
        // Check if this item should be a child
        if (isset($menu[$information["position"]])) {
          $children[$information["position"]][] = $pageName;

        // Check if there are enough pixels for this item to fit
        } else if ($information["pixels"] <= $availablePixels &&
                   $information["name"]) {
          $menu[$information["position"]] = $pageName;
          $availablePixels -= $information["pixels"];
          $additionLog[] = $information["position"];

        // Oh noes! Out of room!
        } else {
          $couldNotAdd[] = $pageName;
        }
      }
    }
    $priority += 1;
  }

  // Step 3: add any items that couldn't fit as children.
  if (!empty($couldNotAdd)) {
    // Add the Other Stuff menu item
    $information = $pages["other-stuff"];
    $menu[$information["position"]] = "other-stuff";
    $availablePixels -= $information["pixels"];

    // This loop will remove items in the reverse order we added them.
    while ($availablePixels < 0) {
      $mostRecent = array_pop($additionLog);
      $couldNotAdd[] = $menu[$mostRecent];
      $information = $pages[$menu[$mostRecent]];
      $availablePixels += $information["pixels"];
      unset($menu[$mostRecent]);
    }

    // Add the left-over items as children
    foreach ($couldNotAdd as $loner) {
      $parent = $pages[$loner]["parent"];
      if (!$parent) {
        $parent = 'other-stuff';
      }
      $parentPos = $pages[$parent]["position"];
      $children[$parentPos][$pages[$loner]["position"]] = $loner;
      ksort($children[$parentPos]);
    }
  }

  // Sort the menu according to position
  ksort($menu);

  if ($loggedIn || $wget) {
    $loginURL = "";
  } else {
    $loginURL = "/login/";
  }

  // Step 4: construct the HTML for the navigation bar.
  $menuHTML = "";
  foreach ($menu as $key => $filename) {
    // Check if it's a special item
    if ($pages[$filename]["special"]) {
      $menuHTML .= "<li class=\"special\">";
    } else {
      $menuHTML .= "<li>";
    }

    // We don't want the Other Stuff button to link anywhere
    if ($filename == "other-stuff") {
      $menuHTML .= "<a>{$pages[$filename]['name']}</a>";
    } else {
      $menuHTML .= "<a href='{$loginURL}/{$filename}'>{$pages[$filename]['name']}</a>";
    }

    // Check if the item has any children
    if (isset($children[$key])) {
      $menuHTML .= "\n\t<ul>\n";
      foreach ($children[$key] as $childName) {
        $menuHTML .= "\t<li><a href='{$loginURL}/{$childName}'>{$pages[$childName]['longName']}</a></li>\n";
      }
      $menuHTML .= "\t</ul>";
    }
    $menuHTML .= "</li>\n";
  }

  if ($screenWidth > 1024 || $wget || !$loggedIn) {
    $small = false;
  } else {
    $small = true;
  }

  $tpl->set('menu', $menuHTML);
  $tpl->set('small', $small);

  $tpl->set('alert', $alert);
  $tpl->set('error', $error);
  $tpl->set('success', $success);
  $tpl->set('warning', $warning);
  $tpl->set('head', false);

  $tpl->set('whatson', $tpl->fetch('templates/whats-on.tpl'));
  $tpl->set('shortTitle', "");

  $tpl->set('js', false);
  $tpl->set('loggedin', $loggedIn);
  if ($loggedIn || $wget) {
    $tpl->set('loginURL', false);
  } else {
    $tpl->set('loginURL', "login/");
  }

  $tpl->set('questionnaire', $questionnaire);
  $tpl->set('feedback', $feedback);

  $tpl->set('currentUser', $username);
  if ($username) {
     $tpl->set('currentName', $people[$username]);
  }

  $tpl->set('config', $jsonConfig);

  $tpl->set('leader', $leader);
  $tpl->set('camper', !$leader);
  $tpl->set('admin', $admin);
  $tpl->set('developer', $admin && $DEVELOPER_MODE);

  $tpl->set('wget', $wget);
  $tpl->set('emulation', $emulation);

  $tpl->set('showQueries', $SHOW_QUERIES);

  $tpl->set('software', $SOFTWARE_NAME);
  $tpl->set('version', $SOFTWARE_VERSION);
  if (isset($SOFTWARE_CODENAME)) {
    $tpl->set('codename', $SOFTWARE_CODENAME);
  } else {
    $tpl->set('codename', false);
  }
?>