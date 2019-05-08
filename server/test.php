<?php

define('APP_PATH', __DIR__.'/../application/');
require __DIR__.'/../thinkphp/base.php';
App::run()->send();

app\common\lib\redis\Predis::getInstance()->sAdd('test','hh2');