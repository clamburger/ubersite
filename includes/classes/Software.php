<?php
class Software {
  static $name = "ÜberSite";
  static $version = "2.1.0";
  static $codename = "Fenix";           // set to false if no codename
  static $codenameDescription = false;  // set to false if no codename description

  static function getFullName() {
    if (Software::$codename) {
      return Software::$name . " " . Software::$codename;
    } else {
      return Software::$name . " " . Software::$version;
    }
  }

  // These functions are used for templating
  function getName() {
    return Software::$name;
  }

  function getVersion() {
    return Software::$version;
  }

  function getCodename() {
    return Software::$codename;
  }

  function getCodenameDescription() {
    return Software::$codenameDescription;
  }
}