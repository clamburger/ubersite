<?php
/**
 * This script resets the password of a specified user.
 * It takes two parameters: the username (required) and the new password (optional)
 * If the new password isn't specified, it will be set to the username.
 * The user will be required to change their password when they next log in.
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

$username = $argv[1];
// Use the password if specified, otherwise use the username
$password = $argc == 3 ? $argv[2] : $argv[1];

require_once("../camp-data/config/database.php");
$mysql = new MySQLi($MYSQL_HOST, $MYSQL_USER, $MYSQL_PASSWORD, $MYSQL_DATABASE);
$query = "SELECT EXISTS(SELECT 1 FROM `people` WHERE `UserID` = ?)";
$stmt = $mysql->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result()->fetch_row();

if (!$result[0]) {
  echo "Error: user $username not found\n";
  exit(1);
}

require_once("../libraries/password_compat.php");
$hash = password_hash($password, PASSWORD_DEFAULT);
if (!$hash) {
  echo "Error: could not generate password hash\n";
  exit(1);
}

$query = "UPDATE `people` SET `Password` = ?, `PasswordChanged` = 0 WHERE `UserID` = ?";
$stmt = $mysql->prepare($query);
$stmt->bind_param("ss", $hash, $username);
$stmt->execute();

if ($stmt->affected_rows) {
  echo "Password for $username successfully updated.\n";
  echo "They will be prompted to change their password the next time they log in.\n";
} else {
  echo "Error: zero affected rows.\n";
}

?>