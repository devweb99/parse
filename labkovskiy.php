<?php

define('ROOT', $_SERVER['DOCUMENT_ROOT']);
define('DS', DIRECTORY_SEPARATOR);

define('TELEGRAM_API_KEY', '1482796866:AAGFwAvr3lA4iXqgpCMwYqIMQhvqMzNBW2k');
define('TELEGRAM_CHAT_ID', '-396611555');

include_once ROOT . DS . 'vendor' . DS . 'autoload.php';

use Parses\LabkovskiyClass;
use Classes\ParserClass;
use Telegram\Bot\Api;

$lectoriumart = new ParserClass(new LabkovskiyClass('/events/event/publichnaya-onlajn-konsultatsiya-pro-samootsenku-5/'), new Api(TELEGRAM_API_KEY));
$lectoriumart->sendTelegram();







