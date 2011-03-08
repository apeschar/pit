<?php

require_once 'PitIncludePath.php';

class PitAutoloader
{
  protected static $_try = array(
    '%s/%s.class.php',
    '%s/%s.php',
  );

  /**
   * Try to load the specified class
   *
   * @param string $class
   * @return bool
   */
  public static function load($class)
  {
    foreach(PitIncludePath::getArray() as $dir)
    {
      foreach(self::$_try as $try)
      {
        $file = sprintf($try, $dir, $class);
        if(is_file($file))
        {
          require_once $file;
          return true;
        }
      }
    }

    return false;
  }

  /**
   * Register autoloader
   *
   * @return void
   */
  public static function register()
  {
    spl_autoload_register(array(__CLASS__, 'load'));
  }
}

