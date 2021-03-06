<?php

class PitController {
  /**
   * Call specified action.
   *
   * @param string $action
   * @param array $args
   * @return mixed
   */
  public function action($action, array $args = array()) {
    // determine action method and retrieve reflection class
    $action_method = 'execute' . $action;
    if(!method_exists($this, $action_method)) {
      throw new Exception(sprintf('No such method: %s', $action_method));
    }
    $method = new ReflectionMethod($this, $action_method);

    // try to match $args with function parameters
    $params = $method->getParameters();

    // does this method accept remaining arguments?
    $has_rest = false;
    $rest_index = 0;
    foreach($params as $param) {
      if($param->getName() == 'args') {
        $has_rest = true;
        break;
      }
      $rest_index++;
    }

    // try to get a value for every method param
    $values = array();
    foreach($params as $param) {
      if($param->getName() == 'args') {
        $values[] = array();
      } elseif(isset($args[$param->getName()])) {
        $values[] = $args[$param->getName()];
        unset($args[$param->getName()]);
      } elseif($param->isDefaultValueAvailable()) {
        $values[] = $param->getDefaultValue();
      } else {
        throw new Exception(sprintf('No argument found for method param: %s',
                                    $param->getName()));
      }
    }

    // get rid of remaining arguments
    if(sizeof($args) > 0) {
      if($has_rest) {
        $values[$rest_index] = $args;
      } else {
        throw new Exception(sprintf('Arguments left but no $args param: %s',
                                    implode(', ', array_keys($args))));
      }
    }

    // invoke function
    $this->beforeExecute($action);
    $return = call_user_func_array(array($this, $action_method), $values);
    $this->afterExecute($action);
    return $return;
  }

  /**
   * Perform pre-action tasks.
   *
   * @return void
   */
  protected function beforeExecute($action) {
  }

  /**
   * Perform post-action tasks.
   *
   * @return void
   */
  protected function afterExecute($action) {
  }

  /**
   * Redirect to URL or route.
   *
   * @param string $dest Destination
   * @param array $args Arguments for URL assembly
   * @return void
   */
  protected function redirect($dest, array $args = array()) {
    if(preg_match('|^@([a-z0-9_]+)\.([a-z0-9_]+)$|i', $dest, $matches)) {
      $args = array_merge($args, array(
        'controller' => $matches[1],
        'action'     => $matches[2]
      ));
      $dest = Pit::assemble($args);
    }

    header('Location: ' . $dest);
    exit;
  }

  /**
   * Output JSON and exit
   *
   */
  protected function returnJSON($response) {
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
  }
}

