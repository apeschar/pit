<?php

class PitFlash {
  /**
   * Add flash message to queue
   *
   * @param string $type Message type
   * @param string $message Message
   * @return void
   */
  public static function flash($type, $message = null) {
    if(func_num_args() == 1) {
      $message = $type;
      $type = null;
    }
    $msg = new stdClass;
    $msg->message = $message;
    $msg->type = $type;
    self::addMessage($msg);
  }

  /**
   * Get all messages of specified type(s) and remove them from the queue
   *
   * @param string [$type,...]
   * @return array
   */
  public static function getMessages($type = null) {
    $types = func_get_args();
    $msgs = PitSession::get('PitFlash', 'messages', array());
    $ret = array();
    foreach($msgs as $key => $msg) {
      if(!$types || in_array($msg->type, $types)) {
        unset($msgs[$key]);
        $ret[] = $msg;
      }
    }
    PitSession::set('PitFlash', 'messages', $msgs);
    return $ret;
  }

  /**
   * Actually store a message in the session
   *
   * @param stdClass $message
   * @return void
   */ 
  protected static function addMessage(stdClass $message) {
    $i = PitSession::get('PitFlash', 'i', 1);
    $msgs = PitSession::get('PitFlash', 'messages', array());
    $msgs[$i] = $message;
    PitSession::set('PitFlash', 'i', $i + 1);
    PitSession::set('PitFlash', 'messages', $msgs);
  }

  /**
   * Clear the message queue
   *
   * @param string [$type,...]
   * @return void
   */
  public static function clearQueue($type = null) {
    $types = func_get_args();
    if($types) {
      $msgs = PitSession::get('PitFlash', 'messages', array());
      foreach($msgs as $key => $msg) {
        if(in_array($msg->type, $types)) {
          unset($msgs[$key]);
        }
      }
      PitSession::set('PitFlash', 'messages', $msgs);
    } else {
      PitSession::set('PitFlash', 'messages', array());
    }
  }
}

