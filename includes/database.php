<?php

  include("functions.php");

  //Connect
  $CONNECTION = mysql_connect($MYSQL_HOST, $MYSQL_USER, $MYSQL_PASSWORD) or report_error(false, true, true);
  mysql_select_db($MYSQL_DATABASE) or report_error(false, true, true);

  function close_connection(){
    global $CONNECTION;
    mysql_close($CONNECTION);
  }

  $queryList = array();

  function do_query($query, $failSilently = false, $failLoudly = false){
    global $username;
    global $queryCount;
    global $queryList;

    $queryCount++;
    $queryList[] = $query;

    $result = mysql_query($query);
    if ($result === false && !$failSilently) {
      report_error($query, !$failLoudly);
    }
    return $result;
  }

  function fetch_row($resource){
    return mysql_fetch_array($resource);
  }

  function num_rows($resource){
    return mysql_num_rows($resource);
  }

  function report_error($query, $kill = true, $raw = false) {
    $error = mysql_error();
    echo "<pre><strong>A ".($raw ? "major " : "")."MySQL error has occurred.</strong>\n";

    $username = "";
    if (isset($_SESSION['username'])) {
      $username = $_SESSION['username'];
    }

    if (!$raw) {
      $reqString = userInput($_SERVER['REQUEST_URI']);
      $reqMethod = userInput($_SERVER['REQUEST_METHOD']);
      $safeQuery = userInput($query);
      $safeError = userInput($error);
      $query2 = "INSERT INTO `errors` (`UserID`, `RequestString`, `RequestMethod`, `Query`, `Error`) ";
      $query2 .= "VALUES('$username', '$reqString', '$reqMethod', '$safeQuery', '$safeError')";
      $result2 = do_query($query2, true);
      if ($result2 === false) {
        echo "This error <strong>was unable to be logged</strong>!\n";
        $error2 = mysql_error();
      } else {
        echo "This error has been logged and will be reported to the authorities.\n";
      }
    }

    echo "If you have any additional questions, please contact your nearest camp leader.\n\n";

    if (!$raw) {
      echo "<strong>Query:</strong> $query\n";
    }

    echo "<strong>Error:</strong> $error\n";

    if (isset($result2) && $result2 === false && !$raw) {
      echo "\n<strong>Query:</strong> $query2\n";
      echo "<strong>Error:</strong> $error2\n";
    }
    echo "</pre>";

    if ($kill) {
      die();
    }
  }
?>
