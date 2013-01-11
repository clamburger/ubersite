<?php

error_reporting(0);

rename("../camp-data/config/config.setup.json", "../camp-data/config/config.json");

$config = json_decode(file_get_contents("../camp-data/config/config.json"), true);

$mysql = new mysqli($config["mysqlHost"], $config["mysqlUser"], $config["mysqlPassword"], $config["mysqlDatabase"]);
$mysql->multi_query(file_get_contents("database.sql"));
while ($mysql->next_result());

$salt = json_decode(file_get_contents("../includes/constants.json"))->salt;

$name = $mysql->real_escape_string($config["adminName"]);
$user = $mysql->real_escape_string($config["adminUser"]);
$password = password_hash($password, PASSWORD_DEFAULT);

$query = "INSERT INTO `people` (`UserID`, `Name`, `Category`, `Admin`, `Password`, `PasswordChanged`) ";
$query .= "VALUES (\"$user\", \"$name\", 'leader', 1, \"$password\", 1)";
$result = $mysql->real_query($query);

unset($config["adminUser"]);
unset($config["adminName"]);
unset($config["adminPass"]);

# Other default config options
$config["developerMode"] = true;
$config["questionnairePage"] = "questionnaire";
$config["questionnaireCondition"] = '$day == "Fri"';
$config["pollCreation"] = true;
$config["suggestionCategories"] = array("trosnoth", "achievements", "website");
$config["contactDetails"] = true;
$config["showQueries"] = false;

# MySQL username and password stored in separate file for security
file_put_contents("../camp-data/config/database.php",
'<?php
$MYSQL_USER = "'.$config["mysqlUser"].'";
$MYSQL_PASSWORD = "'.$config["mysqlPassword"].'";
$MYSQL_HOST = "'.$config["mysqlHost"].'";
$MYSQL_DATABASE = "'.$config["mysqlDatabase"].'";
?>');

# Copy the database revision over to DBV
copy("../setup/database_revision", "../libraries/dbv/data/meta/revision");

unset($config["mysqlUser"]);
unset($config["mysqlPassword"]);

file_put_contents("../camp-data/config/config.json", json_encode($config));
copy("menu.json", "../camp-data/config/menu.json");

echo "done";

?>
