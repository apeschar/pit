<?php

class PitU
{
  /**
   * Convert underscore_words to CamelcaseWords
   *
   * @param string $string
   * @return string
   */
  public static function underscoresToCamelcase($string)
  {
    return ucfirst(preg_replace('|_([a-z])|e', 'strtoupper("$1")', $string));
  }
  
  /**
   * Generate random alphanumeric string that always starts with a letter
   *
   * @param integer $length
   * @return string
   */
  public static function randomString($length = 40)
  {
    assert('is_integer($length)');
    assert('$length > 0');
    $alphachars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWX';
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $string = $alphachars[mt_rand(0, strlen($alphachars) - 1)];
    $len = strlen($chars) - 1;
    for($i = 0; $i < ($length-1); $i++) $string .= $chars[mt_rand(0, $len)];
    return $string;
  }

  /**
   * Validate an e-mail address
   *
   * @param string $address
   * @param boolean $real_check
   * @return boolean
   */
  public static function validateEmail($address, $real_check = true)
  {
    if(!preg_match('|^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$|i', $address)) {
      return false;
    }
    return true;
  }
}

