<?php
/**
 * This script imports users from accounts.txt.
 * It should in a format just like that of /etc/passwd
 */

if (PHP_SAPI != "cli") {
  echo "<div style='font-family: Consolas, \"Liberation Sans\", courier, monospace'>";
  echo "This script must be run via the command line.";
  exit;
}

require_once("../camp-data/config/database.php");
$mysql = new MySQLi($MYSQL_HOST, $MYSQL_USER, $MYSQL_PASSWORD, $MYSQL_DATABASE);

$query = "INSERT INTO `people` (`UserID`, `Name`, `Category`, `Password`, `PasswordChanged`) VALUES (?, ?, \"camper\", NULL, 1)";
$stmt = $mysql->prepare($query);
$stmt->bind_param("ss", $userID, $name);

$data = explode("\n", trim(file_get_contents("accounts.txt")));

foreach ($data as $line) {
  $info = explode(":", $line);
  $userID = $info[0];
  $name = $info[4];
  $stmt->execute();
}


echo "All done";

?>