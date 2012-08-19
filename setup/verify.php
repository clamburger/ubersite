<?php

error_reporting(0);

extract($_POST, EXTR_SKIP);

function valid($var) {

	if (!isset($_POST[$var])) {
		return false;
	}
	
	$value = trim($_POST[$var]);
	if (empty($value)) {
		return false;
	}
	
	return true;
}

function checkMissing() {
	global $results, $details, $items, $v;

	foreach ($items as $item) {
		if (valid($item)) {
			$results[$item] = "";
			$details[$item] = "";
			$v[$item] = true;
		} else {
			$results[$item] = "error";
			$details[$item] = "This field is required.";
			$v[$item] = false;
		}
	}
	
}

function checkSuccess() {
	global $results, $details;

	if (count(array_keys($results, "")) + count(array_keys($results, "warning")) == count($results)) {
		$results = array_fill_keys(array_keys($results), "success");
		return true;
	} else {
		return false;
	}
}

function finish() {
	global $final, $results, $details, $items;
	
	$final = checkSuccess();

	$CONFIG_FILE = "../camp-data/config/config.setup.json";

	if ($final) {
		if (file_exists($CONFIG_FILE)) {
			$config = json_decode(file_get_contents($CONFIG_FILE), true);
		} else {
			$config = array();
		}
		
		foreach ($items as $item) {
			if ($item == "campName") {
				$config[$item] = htmlentities(utf8_decode($_POST[$item]));
			} else {
				$config[$item] = $_POST[$item];
			}
		}
		
		file_put_contents($CONFIG_FILE, json_encode($config));
	}		
		
	echo json_encode(array("results" => $results, "details" => $details, "success" => $final));
	die();
}
	
$results = array();
$details = array();
$v = array();

if ($formName == "camp-information") {

	$items = array("campName", "campYear", "directors", "timezone", "stylesheet");
	
	checkMissing();

	if ($v["campYear"] && preg_match("/^[12][0-9]{3}$/", $campYear) === 0) {
		$results["campYear"] = "error";
		$details["campYear"] = "Year must be between 1000 and 2999.";
	}
	
	$timezones = DateTimeZone::listIdentifiers();
	if ($v["timezone"] && array_search($timezone, $timezones) === false) {
		$results["timezone"] = "error";
		$details["timezone"] = "Invalid timezone specified.";
	}
	
	finish();

} else if ($formName == "mysql-database") {

	$items = array("mysqlHost", "mysqlUser", "mysqlPassword", "mysqlDatabase");
	checkMissing();
	
	if ($v["mysqlHost"] && $v["mysqlUser"] && $v["mysqlPassword"]) {
		$result = @mysql_connect($mysqlHost, $mysqlUser, $mysqlPassword);
		
		if (!$result) {			
			$error = mysql_errno();
			
			if ($error == 1045) {
				$results["mysqlUser"] = "error";
				$results["mysqlPassword"] = "error";
				$details["mysqlUser"] = "Username or password is invalid.";
			} else if ($error == 2002 || $error == 2003) {
				$results["mysqlHost"] = "error";
				$details["mysqlHost"] = "Cannot connect to server.";
			} else {
				$results["mysqlHost"] = "error";
				$details["mysqlHost"] = "MySQL error " + mysql_errno();
			}
		
		} else if ($v["mysqlDatabase"]) {
		
			$result2 = mysql_select_db($mysqlDatabase);
			
			if (!$result2) {
				$error = mysql_errno();
				$results["mysqlDatabase"] = "error";
				
				if ($error == 1049) {
					$mysqlDatabase = mysql_real_escape_String($mysqlDatabase);
					$result3 = mysql_query("CREATE DATABASE `$mysqlDatabase`");
					
					if (!$result3) {
						$error = mysql_errno();
						
						if ($error == 1044) {
							$details["mysqlDatabase"] = "Access denied, cannot create database";
						} else {
							$details["mysqlDatabase"] = "MySQL error " + $error;
						}
						
					} else {
						$results["mysqlDatabase"] = "";
					}
					
				} else if ($error == 1044) {
					$details["mysqlDatabase"] = "Access denied to database";
				} else if ($error == 1102) {
					$details["mysqlDatabase"] = "Invalid database name";
				} else {
					$details["mysqlDatabase"] = "MySQL error " + $error;
				}
			}
			
			$query = "SHOW TABLES LIKE 'people'";
			$result4 = mysql_query($query);
			if (mysql_num_rows($result4) > 0) {
				$results["mysqlDatabase"] = "error";
				$details["mysqlDatabase"] = "Database already in use";
			}
			
		}
		
		
	}
	
	finish();
	
} else if ($formName == "authentication") {

	$items = array("authType");
	checkMissing();
	
	if ($authType == "LDAP") {
		$items[] = "serverLDAP";
	} else if ($authType == "SSH") {
		$items[] = "serverSSH";
	}
	checkMissing();
	
	finish();

} else if ($formName == "admin-user") {

	$items = array("adminName", "adminUser", "adminPass");
	checkMissing();
	finish();
	
}

?>