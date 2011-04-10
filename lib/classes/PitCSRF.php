<?php

class PitCSRF {
  private static $_activated = false;

  /**
   * Check CSRF token
   *
   */
  public static function activate() {
    if(self::$_activated) {
      return;
    }

    // get token
    if(!$token = PitSession::get('PitCSRF', 'token')) {
      $token = PitU::randomString(40);
      PitSession::set('PitCSRF', 'token', $token);
    }

    // verify token on POST request
    if(!empty($_POST)) {
      if(!isset($_POST['PIT_CSRF_TOKEN'])) {
        header('HTTP/1.0 400 Bad Request');
        echo '<h1>400 Bad Request</h1>';
        echo '<p>No CSRF token was found.</p>';
        exit;
      } elseif($_POST['PIT_CSRF_TOKEN'] != $token) {
        header('HTTP/1.0 400 Bad Request');
        echo '<h1>400 Bad Request</h1>';
        echo '<p>Invalid CSRF token specified.</p>';
        exit;
      }
    }

    self::$_activated = true;
  }

  /**
   * Check whether PitCSRF is activated.
   *
   */
  private static function _checkActive() {
    if(!self::$_activated) {
      throw new Exception('Call PitCSRF::activate() before using other methods.');
    }
  }

  /**
   * Get CSRF token
   *
   * @return string
   */
  public static function getToken() {
    self::_checkActive();
    return PitSession::get('PitCSRF', 'token');
  }

  /**
   * Get HTML for hidden form element containing token.
   *
   * @return string
   */
  public static function getHTML() {
    self::_checkActive();
    $token = self::getToken();
    return sprintf('<div style="display:none;"><input type="hidden" name="PIT_CSRF_TOKEN" value="%s"/></div>', $token);
  }
}

