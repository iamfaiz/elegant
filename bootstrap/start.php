<?php
///////////////////////
// Start the session //
///////////////////////
session_start();
/////////////////////////////////////
// Require the composer autoloader //
/////////////////////////////////////
require_once __DIR__.'/../vendor/autoload.php';
//////////////////
// Boot the App //
//////////////////
$app = new Core\Framework\App;
$app->boot();
