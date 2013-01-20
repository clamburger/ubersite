<?php
namespace Questionnaire;

class Question {
  public $questionID;
  public $question;
  public $answerType;
  public $answerOptions = false;
  public $answerOther = false;

  const OTHER_RESPONSE = 42;
  const DEFAULT_COLOUR = "white";

  // TODO: we shouldn't have any special cases for things like this (not here, anyway)
  static $lengthLookup = [
    "--",
    "Far too short",
    "A little too short",
    "Just right",
    "A little too long",
    "Far too long"
  ];

  static $coloursFive = [
    "white",
    "#F8696B",
    "#FBAA77",
    "#FFEB84",
    "#B1D580",
    "#63BE7B"
  ];

  static $coloursFiveTwo = [
    "white",
    "#F8696B",
    "#FFEB84",
    "#63BE7B",
    "#FFEB84",
    "#F8696B"
  ];

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
        $out .= "<li><label><input type='radio' name='{$this->questionID}' value='".($key+1)."'> $option</label></li>\n";
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

  function getAnswerString($response) {
    // Either an empty string or no answer selected
    if (!$response) {
      return false;
    }

    // For text, return as-is.
    if ($this->answerType == "Textarea" || $this->answerType == "Text") {
      return $response;
    }

    // 1-5 dropdowns are returned as-is
    // TODO: remove this special behaviour and make them like other dropdowns
    if ($this->answerType == "1-5") {
      return $response;
    }

    // Check the lookup table
    if ($this->answerType == "Length") {
      return self::$lengthLookup[$response];
    }

    // Look up the correct key in the answerOptions array.
    // Subtract 1 from the response since response values go from 1 to [x] and our arrays go from 0 to [x-1]
    if ($this->answerType == "Dropdown" || $this->answerType == "Radio") {
      if ($this->answerOther && $response == count($this->answerOptions)) {
        return self::OTHER_RESPONSE;
      }
      return $this->answerOptions[$response - 1];
    }

    return "<span style='font-color: red;'>Not yet implemented: {$this->answerType}</span>";

  }

  // TODO: not entirely pleased with having to include $users, perhaps there's a better way
  function renderFeedback($allResponses, $users) {
    $output = "<h3>{$this->question}</h3>\n";
    $output .= "<ul>";
    if (!isset($allResponses[$this->questionID])) {
      $output .= "<li><em style='color: silver;'>No responses for this question</em></li>";
    } else {
      foreach ($allResponses[$this->questionID] as $response) {
        $sig = "- <em>{$users[$response['UserID']]}</em>";
        $stringResponse = $this->getAnswerString($response['Answer']);
        if ($stringResponse === self::OTHER_RESPONSE) {
          $output .= "<li>Other: ".$allResponses[$this->questionID."-other"]
                                                [$response['UserID']]['Answer']." $sig</li>";
        } else if ($stringResponse) {
          $output .= "<li>$stringResponse $sig</li>\n";
        }
      }
    }
    $output .= "</ul>";
    return $output;
  }

  function getColour($response) {
    if ($this->answerType == "1-5") {
      return self::$coloursFive[$response];
    } else if ($this->answerType == "Length") {
      return self::$coloursFiveTwo[$response];
    } else {
      return self::DEFAULT_COLOUR;
    }
  }
}