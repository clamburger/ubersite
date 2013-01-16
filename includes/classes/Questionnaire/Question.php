<?php
namespace Questionnaire;

class Question {
  public $questionID;
  public $question;
  public $answerType;
  public $answerOptions = false;
  public $answerOther = false;

  function __construct($details) {
    $this->questionID = $details->QuestionID;
    $this->question = $details->Question;
    $this->answerType = $details->AnswerType;
    if (isset($details->AnswerOptions)) {
      $this->answerOptions = $details->AnswerOptions;
    }
    if (isset($details->AnswerOther)) {
      $this->answerOther = $details->AnswerOther;
    }
  }
  
  function renderHTML($prefix = "") {
    $out = "<div class='question question-".strtolower($this->answerType)."'>";
    if ($this->answerType == "Radio") {
      $out .= $prefix.$this->question;
      $out .= "<ul>";
      foreach ($this->answerOptions as $key => $option) {
        $out .= "<li><label><input type='radio' name='{$this->questionID}'> $option</label></li>\n";
      }
      if ($this->answerOther) {
        $out .= "<input type='text' name='{$this->questionID}-other' class='other'>";
      }
    } else if ($this->answerType == "Textarea") {
      $out .= "<label for='question-{$this->questionID}'>$prefix{$this->question}</label><br>";
      $out .= "<textarea rows=3 id='question-{$this->questionID}' name='{$this->questionID}'></textarea>";
    } else if ($this->answerType == "Text") {
      $out .= "<label for='question-{$this->questionID}'>$prefix{$this->question}</label><br>";
      $out .= "<input type='text' id='question-{$this->questionID}' name='{$this->questionID}'>";
    } else if ($this->answerType == "1-5") {
      $out .= "<label for='question-{$this->questionID}'>$prefix{$this->question}</label>";
      $out .= "<select id='question-{$this->questionID}' name='{$this->questionID}'>";
      $out .= "  <option value='0'>--</option>\n";
      $out .= "  <option value='5' style='background-color: #63BE7B;'>5</option>\n";
      $out .= "  <option value='4' style='background-color: #B1D580;'>4</option>\n";
      $out .= "  <option value='3' style='background-color: #FFEB84;'>3</option>\n";
      $out .= "  <option value='2' style='background-color: #FBAA77;'>2</option>\n";
      $out .= "  <option value='1' style='background-color: #F8696B;'>1</option>\n";
      $out .= "</select>\n";
    } else if ($this->answerType == "Length") {
      $out .= "<label for='question-{$this->questionID}'>$prefix{$this->question}</label>";
      $out .= "<select id='question-{$this->questionID}' name='{$this->questionID}'>";
      $out .= "  <option value='0'>--</option>\n";
      $out .= "  <option value='5' style='background-color: #F8696B;'>Far too long</option>\n";
      $out .= "  <option value='4' style='background-color: #FFEB84;'>A little too long</option>\n";
      $out .= "  <option value='3' style='background-color: #63BE7B;'>Just right</option>\n";
      $out .= "  <option value='2' style='background-color: #FFEB84;'>A little too short</option>\n";
      $out .= "  <option value='1' style='background-color: #F8696B;'>Far too short</option>\n";
      $out .= "</select>\n";
    } else if ($this->answerType == "Dropdown") {
      $out .= "<label for='question-{$this->questionID}'>$prefix{$this->question}</label>";
      $out .= "<select id='question-{$this->questionID}' name='{$this->questionID}'>";
      $out .= "  <option value='0'>--</option>\n";
      foreach ($this->answerOptions as $key => $option) {
        $out .= "  <option value='".($key+1)."'>$option</option>\n";
      }
      $out .= "</select>\n";
    } else {
      $out = "<div style='color: red; font: 15px tahoma;'>{$this->questionID}: \"{$this->answerType}\" not yet implemented</div>";
    }
    $out .= "</div>";
    return $out;
  }
}