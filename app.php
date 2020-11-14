<?php

define('ROOT', $_SERVER['DOCUMENT_ROOT']);
define('DS', DIRECTORY_SEPARATOR);

include_once ROOT . 'vendor' . DS . 'autoload.php';

use Parses\LectoriumartClass;

$t = new LectoriumartClass('/shop/');





