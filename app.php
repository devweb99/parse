<?php

define('ROOT', $_SERVER['DOCUMENT_ROOT']);
define('DS', DIRECTORY_SEPARATOR);

include_once ROOT . 'vendor' . DS . 'autoload.php';

use Parses\LectoriumartClass;
use Classes\ParserClass;


$lectoriumart = new ParserClass(new LectoriumartClass('/shop/'));
var_dump($lectoriumart->getAndCheckNewMessage());





