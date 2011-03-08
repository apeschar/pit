<?php

if(!defined('PIT_ROOT')) define('PIT_ROOT', dirname(__FILE__));

// update include path
require_once PIT_ROOT . '/lib/classes/PitIncludePath.php';
PitIncludePath::prepend(PIT_ROOT . '/lib/classes');

// autoloader
require_once 'PitAutoloader.php';
PitAutoloader::register();

