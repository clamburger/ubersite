<?php
/**
 * A dummy user with minimal privileges.
 * Used for when nobody is logged in.
 */
class NullUser extends User {

  function __construct($wget = false) {
    $this->UserID = "";
    $this->Name = "";
    $this->Category = "camper";
    $this->Admin = false;
    $this->LoggedIn = false;
  }

  function needsPasswordChange() {
    return false;
  }

}