<?php

class Pit {
  /**
   * Get a PitRouter object
   *
   */
  public static function getRouter() {
    static $router;
    if(!isset($router)) {
      $router = new PitRouter;
    }
    return $router;
  }
  
  /**
   * Load routes from config file
   *
   * @param string $config_file
   * @return void
   */
  public static function loadRoutes($config_file = null) {
    $router = self::getRouter();
    if(!file_exists($config_file)) {
      throw new Exception(sprintf('Configuration file does not exist: %s', $config_file));
    }
    if(!is_file($config_file)) {
      throw new Exception(sprintf('Configuration file exists, but is not a file: %s', $config_file));
    }
    if(!is_readable($config_file)) {
      throw new Exception(sprintf('Configuration file exists, but is not readable: %s', $config_file));
    }
    if(preg_match('|\.yml$|', $config_file)) {
      $router->loadYAMLFile($config_file);
    } elseif(preg_match('|\.conf$|', $config_file)) {
      $router->loadConfFile($config_file);
    } else {
      throw new Exception(sprintf('Configuration file extension should be either .yml or .conf: %s'), $config_file);
    }
  }
}

