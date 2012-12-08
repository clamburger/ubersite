<?php
  # A simple wrapper file which loads in various json files
  $jsonConstants = json_decode(file_get_contents("includes/constants.json"), true);
  $jsonConfig = json_decode(file_get_contents("camp-data/config/config.json"), true);

  $variables = array_merge($jsonConstants, $jsonConfig);

  # Convert the CamelCase variable names into ALL_CAPS
  # Partially to adhere to a coding standard, partially for backwards compatability

  function fromCamelCase($str) {
    $str[0] = strtolower($str[0]);
    $func = create_function('$c', 'return "_" . strtolower($c[1]);');
    return strtoupper(preg_replace_callback('/([A-Z])/', $func, $str));
  }

  $constants = array();
  foreach ($variables as $varName => $value) {
    $varName = fromCamelCase($varName);
    $constants[$varName] = $value;
  }

  include("camp-data/config/database.php");

  $CAMP_DAYS = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday");

  extract($constants);

?>
