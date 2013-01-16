<?php
namespace Questionnaire;

class Page {
  public $pageID;
  public $title;
  public $intro = false;
  public $questions = [];
  
  function __construct($details, $questions, $groups) {
    $this->pageID = $details->PageID;
    $this->title = $details->Title;
    foreach ($details->Questions as $ID) {
      if (isset($groups[$ID])) {
        $this->questions[] = $groups[$ID];
      } else if (isset($questions[$ID])) {
        $this->questions[] = $questions[$ID];
      } else {
        error("Could not find group or question with ID \"$ID\"");
      }
    }
    if (isset($details->Intro)) {
      $this->intro = $details->Intro;
    }
  }

  function renderHTML() {
    $out = "";
    if ($this->intro) {
      $out .= "<p>$this->intro</p>";
    }
    foreach ($this->questions as $item) {
      $out .= $item->renderHTML();
    }
    return $out;
  }
}