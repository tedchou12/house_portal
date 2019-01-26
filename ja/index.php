<?php
ini_set('include_path', '../');

require_once dirname(dirname(__FILE__)) .'/Bootstrap/Autoload.php';

$GLOBALS['app'] = new Bootstrap\App('ja');
$GLOBALS['app']->start();

?>
