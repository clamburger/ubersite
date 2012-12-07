<?php
/*
 * Stores a number of messages which will be displayed to the user.
 * It's not really a queue, since all messages get displayed at once.
 */
class MessageQueue {

  // Each type of message is stored as a subarray of $messages.
  private $messages = array();

  function addMessage(Message $message) {
    if (!isset($this->messages[$message->type])) {
      $this->messages[$message->type] = array();
    }
    $this->messages[$message->type][] = $message[$message->message];
  }

  /*
   * Returns all the HTML necessary to print the message boxes.
   *
   * All text is escaped and all messages of the same type are combined
   * into the same box.
   */
  function getAllMessageHTML() {
    $completeHtml = array();
    foreach ($this->messages as $type => $messages) {
      $thisTypeHtml =
        "<div class='alert alert-$type'>\n" .
        "  <button class='close pull-right'>×<button>\n" .
        "  <ul class='unstyled nomargin'>\n";
      foreach ($messages as $text) {
        $thisTypeHtml .= "      <li>" . htmlspecialchars($text) . "</li>\n";
      }
      $thisTypeHtml .=
        "  </ul>\n" .
        "</div>\n";
      $completeHtml[] = $thisTypeHtml;
    }

    return implode("\n\n", $completeHtml);
  }
}
