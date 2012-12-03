<?php
// URL rewriter
// Courtesy of http://stackoverflow.com/questions/893218/rewrite-for-all-urls
$_SERVER['REQUEST_URI_PATH'] = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$SEGMENTS = explode('/', trim($_SERVER['REQUEST_URI_PATH'], '/'));
$SEGMENTS = array_map("strtolower", $SEGMENTS);

for ($i = 0; $i <= 9; $i++) {
    if (!isset($SEGMENTS[$i])) {
        $SEGMENTS[$i] = null;
    }
}

$PAGE = $SEGMENTS[0];
// End URL rewriter

if (strlen($PAGE) == 0) {
    $PAGE = "index";
}

if (file_exists("frontend/$PAGE.php")) {
  require_once("frontend/$PAGE.php");
} else {
  echo "404 File Not Found";
}
?>