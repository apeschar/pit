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

  /**
   * Resize an image
   *
   * @param resource $gd
   * @param integer $max_width
   * @param integer $max_height
   * @return resource
   */
  public static function resizeImage($src, $max_width, $max_height) {
    $src_width = imagesx($src);
    $src_height = imagesy($src);

    // calculate new width/height
    $dst_width = $src_width;
    $dst_height = $src_height;
    if($dst_width > $max_width) {
      $frac = $max_width / $dst_width;
      $dst_width *= $frac;
      $dst_height *= $frac;
    }
    if($dst_height > $max_height) {
      $frac = $max_height / $dst_height;
      $dst_width *= $frac;
      $dst_height *= $frac;
    }
    $dst_width = intval(round($dst_width));
    $dst_height = intval(round($dst_height));

    // resize needed?
    if($dst_width == $src_width && $dst_height == $src_height) {
      return $src;
    }

    // resize image
    $dst = imagecreatetruecolor($dst_width, $dst_height);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $dst_width, $dst_height,
                       $src_width, $src_height);
    imagedestroy($src);
    return $dst;
  }

  /**
   * Generate a random filename for a multi-level storage system
   *
   * e.g. 'AA/BB/CC/DEFGHIJKLMNOP'
   *
   * @param string $filename
   * @return string
   */
  public static function randomFilename() {
    $name = self::randomString();
    return substr($name, 0, 2) . '/' . substr($name, 2, 2)
           . '/' . substr($name, 4, 2) . '/' . substr($name, 6);
  }
}

