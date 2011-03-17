<?php

class PitConfig {
  protected static $_config;

  /**
   * Load YAML file into namespace.
   *
   * @param string $ns
   * @param string $file
   * @param string $env
   * @return void
   */
  public static function loadYAML($ns, $file, $env = null) {
    if(!is_file($file)) {
      throw new Exception(sprintf('PitConfig: no such file: %s', $file));
    }
    $config = sfYaml::load($file);
    if(func_num_args() >= 3) {
      if(!is_string($env)) {
        throw new Exception('Environment must be string.');
      }
      $config = array_merge(isset($config['all']) ? $config['all'] : array(),
                            isset($config[$env]) ? $config[$env] : array());
    }
    self::$_config[$ns] = $config;
  }

  /**
   * Get specified key from namespace
   *
   * @param string $ns
   * @param string $key
   * @param mixed $default
   * @return mixed
   */
  public static function get($ns, $key, $default = null) {
    $default_specified = func_num_args() >= 3;
    if(!isset(self::$_config[$ns])) {
      throw new Exception(sprintf('PitConfig: no such namespace: %s', $ns));
    }
    $var = self::$_config[$ns];
    $pieces = explode('.', $key);
    foreach($pieces as $piece) {
      if(isset($var[$piece])) {
        $var = $var[$piece];
      } elseif($default_specified) {
        return $default;
      } else {
        throw new Exception(sprintf('PitConfig: no such key: %s', $key));
      }
    }
    return $var;
  }
}

