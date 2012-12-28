<?php

	error_reporting(E_ALL);
	include_once("../libraries/bTemplate.php");
	
	if (file_exists("../camp-data/config/config.json") && !isset($_GET['override'])) {
		header("Location: ../index.php");
	}
	
	# Grab version information
  include_once("../includes/classes/Software.php");
	
	# Remove any old configuration from previous setup attempts
	if (file_exists("../camp-data/config/config.setup.json")) {
		unlink("../camp-data/config/config.setup.json");
	}
	
	$tpl = new bTemplate();
	
	$tpl->set("version", Software::$version);
	$tpl->set("codename", Software::$codename);
	$tpl->set("software", Software::$name);

	$criticalError = false;
	$checks = array_fill_keys(array("configWritable", "mysql", "mysqli", "ssh", "ldap"), true);
	
	if (PHP_VERSION_ID < 50400) {
		$checks["php"] = "<span class='label important'>".PHP_VERSION."</span>";
		$criticalError = true;
	} else {
		$checks["php"] = "<span class='label success'>".PHP_VERSION."</span>";
	}
	
	# Check required directories
	
	$directories = array("camp-data", "camp-data/photos", "camp-data/photos/cache", "camp-data/profiles", "camp-data/uploads", "camp-data/config", "camp-data/uploads/codechallenge", "camp-data/uploads/codechallenge/admin");
	
	foreach ($directories as $dirName) {
		if (file_exists("../$dirName")) {
			# Directory exists, check if it's writable
			if (!is_writable("../$dirName")) {
				$checks["configWritable"] = "the <tt>$dirName</tt> directory needs to be writable. Installation cannot continue.";
				$criticalError = true;
				break;
			}
		} else {
			# Doesn't exist: try and create it
			if (!@mkdir("../$dirName")) {
				$checks["configWritable"] = "the <tt>$dirName</tt> directory could not be created. Installation cannot continue.";
				$criticalError = true;
				break;
			}
			chmod("../$dirName", 0777);
		}
	}
	
	if (!function_exists("mysqli_connect")) {
		$checks["mysqli"] = "you may need to install <tt>php-mysql</tt>. Installation cannot continue.";
		$criticalError = true;
	}
	if (!function_exists("mysql_connect")) {
		$checks["mysql"] = "you may need to install <tt>php-mysql</tt>. Installation cannot continue.";
		$criticalError = true;
	}
	if (!function_exists("ssh2_connect")) {
		$checks["ssh"] = "SSH authentication will be unavailable.";
	}
	if (!function_exists("ldap_connect")) {
		$checks["ldap"] = "LDAP authentication will be unavailable.";
	}
	
	$tpl->set("ldap", $checks["ldap"] === true);
	$tpl->set("ssh", $checks["ssh"] === true);
	
	foreach ($checks as $key => $value) {
		if ($key == "php") {
			continue;
		}
		if ($value === true) {
			$checks[$key] = "<span class='label success'>yes</span>";
		} else {
			if ($key == "ssh" || $key == "ldap") {
				$checks[$key] = "<span class='label warning'>no</span> - $value";
			} else {
				$checks[$key] = "<span class='label important'>no</span> - $value";
			}
		}
	}
	
	$tpl->set("checks", $checks);
	$tpl->set("error", $criticalError);
	
	
	echo $tpl->fetch("setup.tpl");
	
?>
