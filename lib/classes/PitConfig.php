<?php

class PitConfig {
  protected static $_config;

  /**
   * Load YAML file into namespace.
   *
   * @param string $ns
   * @param string $file
   * @return void
   */
  public static function loadYAML($ns, $file) {
    if(!is_file($file)) {
      throw new Exception(sprintf('PitConfig: no such file: %s', $file));
    }
    self::$_config[$ns] = sfYaml::load($file);
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

