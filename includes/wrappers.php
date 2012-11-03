<?php

$WRAPPER_ERROR = "Implementation error! This must be fixed by a tech leader.";

# This file defines conditional functions based on the authentication type.
# It's best to just read through it to understand it.

# All functions should return "success" on success.
# Other return values:

# checkLogin:
	# "password" for incorrect password
	# "username" for non-existant username
	# "incorrect" for incorrect username or password
	
# changePassword:
	# "password" for incorrect old password

if ($AUTH_TYPE == "mysql") {
	
	function checkLogin($username, $password) {
		$username = mysql_real_escape_string($username);
		$query = "SELECT `Password` FROM `people` WHERE `UserId` = '$username'";
		$result = do_query($query);
		if ($row = fetch_row($result)) {
          if (is_null($row["Password"]) && $password === $username) {
            return "success";
          }
		  if (md5_salted($password) == $row['Password']) {
			return "success";
		  } else {
			return "password";
		  }
		} else {
		  return "username";
		}
	}
	
	function changePassword($username, $oldPassword, $newPassword) {
		$query = "SELECT `Password` FROM `people` WHERE `UserId` = '$username'";
		$row = fetch_row(do_query($query));
				 
		if (md5_salted($oldPassword) != $row['Password'] &&
		    !(is_null($row["Password"]) && $oldPassword === $username)) {
			return "password";
		} else {
			$query = "UPDATE `people` SET `Password` = '".md5_salted($newPassword);
			$query .= "', `PasswordChanged` = 1 WHERE `UserId` = '$username'";
			do_query($query);
			return "success";
		}
	}
	
	function resetPassword($username) {
		$query = "UPDATE `people` SET `Password` = '".md5_salted($username);
		$query .= "', `PasswordChanged` = 0 WHERE `UserId` = '$username'";
		do_query($query);
		return "success";
	}
	
	# All that needs to be done for a new account is to set the password.
	function newAccount($username) {
		return resetPassword($username);
	}
	
	function deleteAccount($username) {
		return "success";
	}
	
} else if ($AUTH_TYPE == "ldap") {

	$LDAP = ldap_connect($LDAP_SERVER);

	function checkLogin($username, $password) {
		global $LDAP;
		
		$dn = "uid=$username,ou=Users,dc=ubertweak,dc=su";
		@ldap_bind($LDAP, $dn, $password);
		if (ldap_errno($LDAP) !== 0) {
			return "incorrect";
		}
		
		// Check if the user has an entry in the database	
		$username = mysql_real_escape_string(substr($username, 0, 6));
		$query = "SELECT `UserID` FROM `people` WHERE `UserID` = '$username'";
		$result = do_query($query);
		if (mysql_num_rows($result) === 0) {
			return "database";
		}
			
		return "success";		
	}
	
	function changePassword($username, $oldPassword, $newPassword) {
		return false;
	}
	
	function resetPassword($username) {
		return false;
	}
	
	function newAccount($username) {
		return false;
	}
	
	function deleteAccount($username) {
		return false;
	}
	
} else if ($AUTH_TYPE == "ssh") {

	$SSH = ssh2_connect($SSH_SERVER, 22);

	function checkLogin($username, $password) {
		global $SSH;
		
		if (!@ssh2_auth_password($SSH, $username, $password)) {
			return "incorrect";
		}
		
		// Check if the user has an entry in the database	
		$username = mysql_real_escape_string(substr($username, 0, 6));
		$query = "SELECT `UserID` FROM `people` WHERE `UserID` = '$username'";
		$result = do_query($query);
		if (mysql_num_rows($result) === 0) {
			return "database";
		}
		
		return "success";
	}
	
	function changePassword($username, $oldPassword, $newPassword) {
		global $SSH;
		
		if (!@ssh2_auth_password($SSH, $username, $oldPassword)) {
			return "password";
		}
				
	}
}
		