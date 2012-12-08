<?php
/**
 * A dummy user with minimal privileges.
 * Used for when nobody is logged in and wget.
 */
class NullUser extends User {

  function __construct($wget = false) {
    $this->UserID = "";
    $this->Name = "";
    $this->Category = "camper";
    $this->Admin = false;
    $this->LoggedIn = false;
    $this->Wget = $wget;
  }

  function needsPasswordChange() {
    return false;
  }

}