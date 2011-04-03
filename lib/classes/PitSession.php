<?php

class PitSession {
  /**
   * Get session variable
   *
   * @param string [$namespace] Namespace
   * @param string $key Key
   * @param string $default Default
   * @return mixed
   */
  public static function get($namespace, $key = null, $default = null) {
    if(func_num_args() == 1) {
      $key = $namespace;
      $namespace = 'DEFAULT';
    }
    if(isset($_SESSION['PIT']['ns'][$namespace][$key])) {
      return $_SESSION['PIT']['ns'][$namespace][$key];
    } else {
      return $default;
    }
  }

  /**
   * Set session variable
   *
   * @param string [$namespace] Namespace
   * @param string $key Key
   * @param mixed $value Value
   * @return void
   */
  public static function set($namespace, $key, $value = null) {
    if(func_num_args() == 2) {
      $value = $key;
      $key = $namespace;
      $namespace = 'DEFAULT';
    }
    if(!isset($_SESSION['PIT']) || !is_array($_SESSION['PIT'])) {
      $_SESSION['PIT'] = array();
    }
    $_SESSION['PIT']['ns'][$namespace][$key] = $value;
  }
 
  /**
   * Delete a namespace
   *
   * @param string $namespace Namespace
   * @return void
   */
  public static function wipeNamespace($namespace) {
    if(!isset($_SESSION['PIT']) || !is_array($_SESSION['PIT'])) {
      $_SESSION['PIT'] = array();
    }
    if(isset($_SESSION['PIT']['ns'][$namespace])) {
      unset($_SESSION['PIT']['ns'][$namespace]);
    }
  }
  
  /**
   * Delete all session data
   *
   * @return void
   */
  public static function wipe() {
    unset($_SESSION['PIT']);
  }
}
