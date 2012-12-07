<?php
/**
 * This script resets the password of a specified user.
 */

if (PHP_SAPI != "cli") {
  echo "<div style='font-family: Consolas, \"Liberation Sans\", courier, monospace'>";
  echo "This script must be run via the command line.";
  exit;
}

if ($argc < 2 || $argc > 3) {
  echo "usage: php ".basename(__FILE__). " <username> [<password>]\n";
  exit;
}
?>