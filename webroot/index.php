<?php

define('DS', DIRECTORY_SEPARATOR);                    #directory separator
define('ROOT', dirname(dirname(__FILE__)));     #директория на пункта выше чем index.html
define('VIEWS_PATH', ROOT.DS.'views');

require_once (ROOT.DS.'lib'.DS.'init.php');

session_start();

App::run($_SERVER['REQUEST_URI']);