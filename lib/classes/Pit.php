<?php

class Pit {
  protected static $_404file;

  /**
   * Get a PitRouter object
   *
   * @return PitRouter
   */
  public static function getRouter() {
    static $router;
    if(!isset($router)) {
      $router = new PitRouter;
      $router->setBaseURL('/');
    }
    return $router;
  }

  /**
   * Generate an url using PitRouter
   *
   * @param array $params
   * @return string
   */
  public static function assemble($params) {
    return call_user_func_array(array(self::getRouter(), 'assemble'), func_get_args());
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

  /**
   * Complete the request.
   *
   */
  public static function completeRequest() {
    // retrieve PATH_INFO
    if(isset($_SERVER['PATH_INFO'])) {
      $path_info = $_SERVER['PATH_INFO'];
    } elseif(isset($_SERVER['ORIG_PATH_INFO'])) {
      $path_info = $_SERVER['ORIG_PATH_INFO'];
    } else {
      $path_info = '';
    }
    $path_info = ltrim($path_info, '/');
    
    // try to route url
    $router = self::getRouter();
    $route = $router->route($path_info);

    if(!$route) {
      return self::display404();
    }

    // load controller
    $controller_class = ucfirst($route['controller']) . 'Controller';
    $controller = eval('return new ' . $controller_class . ';');

    if(!$controller) {
      throw new Exception('Could not instantiate controller.');
    }

    // call action
    $args = $route;
    unset($args['controller'], $args['action']);
    $controller->action($route['action'], $args);
  }

  /**
   * Get 404 page location
   *
   * @return string
   */
  public static function get404File() {
    if(self::$_404file) {
      return self::$_404file;
    } else {
      return PIT_ROOT . '/data/404.php';
    }
  }

  /**
   * Set 404 page location
   *
   * @param string
   * @return void
   */
  public static function set404File($file) {
    self::$_404file = $file;
  }

  /**
   * Display 404 page
   *
   * @return void
   */
  public static function display404() {
    header('HTTP/1.0 404 Not Found');
    $file = self::get404File();
    if(preg_match('|\.php$|', $file)) {
      require $file;
    } else {
      readfile($file);
    }
  }
}

