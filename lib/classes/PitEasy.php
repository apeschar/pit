<?php

class PitEasy {
  /**
   * Set up routing using PitRouter
   *
   * @param string $config_file
   * @return PitRouter
   */
  public static function router($config_file = null) {
    // create router
    $router = new PitRouter();

    // load configuration file
    if(!is_null($config_file)) {
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

    return $router;
  }
}

