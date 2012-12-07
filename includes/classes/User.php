<?php
/**
 * Holds information about a user.
 */
class User {
  public $UserID;
  public $Name;
  public $Category;
  public $HasProfile;
  private $PasswordChanged;
  private $Admin;

  function __construct($row) {
    $this->UserID = $row['UserID'];
    $this->Name = $row['Name'];
    $this->Category = $row['Category'];
    $this->Admin = $row['Admin'];
    $this->PasswordChanged = $row['PasswordChanged'];
    $this->HasProfile = $row['InfoFilled'];
  }

  /* TODO: this is not future proof */
  function isCamper() {
    return $this->Category == "camper";
  }

  function isLeader() {
    return !$this->isCamper();
  }

  function isAdmin() {
    return $this->isLeader() && $this->Admin;
  }

  function needsPasswordChange() {
    return !$this->PasswordChanged;
  }

  /*
   * This is here for compatibility with bits of code that expect a name
   * instead of an object.
   */
  function __toString() {
    //trigger_error('Casting User to string is not recommended; '
    //  . 'use $user->Name instead', E_USER_DEPRECATED);
    return $this->Name;
  }

}