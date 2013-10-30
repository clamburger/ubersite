<?php
include_once("includes/start.php");
$DISABLE_UBER_BUTTON = true;

$tpl->set("title", "Questionnaire Editor");

use Questionnaire\Question;
use Questionnaire\Group;
use Questionnaire\Page;

$query = "SELECT * FROM `questionnaires` ORDER BY `Id` ASC";
$result = do_query($query);
$questionnaires = [];
while ($row = mysql_fetch_assoc($result)) {
  $questionnaires[$row['Id']] = $row;
}

// Which questionnaire.
$id = $SEGMENTS[1];

if (!$id || !isset($questionnaires[$id]))  {
  foreach ($questionnaires as &$questionnaire) {
    $data = json_decode($questionnaire['Pages'], true);
    $questionnaire['Pages'] = count($data['Pages']);
    $questionnaire['Groups'] = count($data['Groups']);
    $questionnaire['Questions'] = count($data['Questions']);
  }
  $twig->addGlobal("questionnaires", $questionnaires);
  $twig->addGlobal("selectionMode", true);
  fetch();
  exit;
}

$twig->addGlobal("selectionMode", false);

$questionnaire = $questionnaires[$id];
$tpl->set("contenttitle", $questionnaire['Name']);

// We can't use "questionnaire" since it's already taken.
// This is why we can't have nice things.
$twig->addGlobal("q", $questionnaire);

fetch();