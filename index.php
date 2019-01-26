<?php

require_once dirname(__FILE__) .'/Bootstrap/Autoload.php';

$GLOBALS['app'] = new Bootstrap\App();
$GLOBALS['app']->start();
