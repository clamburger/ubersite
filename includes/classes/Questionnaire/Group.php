<?php
namespace Questionnaire;

class Group {
  public $groupID;
  public $title;
  public $questions = [];
  public $collapsible = false;
  public $comments = true;
  
  function __construct($details, $questions) {
    $this->groupID = $details->GroupID;
    $this->title = $details->Title;
    foreach ($details->Questions as $questionID) {
      if (isset($questions[$questionID])) {
        $this->questions[] = $questions[$questionID];
      } else {
        error("Could not find question with ID \"$questionID\"");
      }
    }
    if (isset($details->Collapsible)) {
      $this->collapsible = $details->Collapsible;
    }
    if (isset($details->Comments)) {
      $this->comments = $details->Comments;
    }
  }
  
  function renderHTML() {
    $out = "";
    $out .= "<fieldset class='question-group'>";
    $out .= "<legend>".$this->title."</legend>";
    foreach ($this->questions as $key => $question) {
      $out .= $question->renderHTML(($key+1).". ");
    }
    if ($this->comments) {
      $out .= "<label for='question-{$this->groupID}-comments' class='spacing'>Any other comments?</label>";
      $out .= "<textarea rows=3 name='{$this->groupID}-comments' id='question-{$this->groupID}-comments'></textarea>";
    }
    $out .= "</fieldset>";
    return $out;
  }
}