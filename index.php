<?php
error_reporting(E_ALL); ini_set('display_errors', 'on');

use \Jacwright\RestServer\RestServer;

require __DIR__ . '/source/Jacwright/RestServer/RestServer.php';
require 'MailController.php';

spl_autoload_register(); // don't load our classes unless we use them

$server = new RestServer();
// $server->refreshCache(); // uncomment momentarily to clear the cache if classes change in production mode

$server->addClass('MailController');

$server->handle();
