<?php

$base_url = '';

$pathInfo = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : getenv('PATH_INFO');
if (@$pathInfo) {
    $base_url .= dirname($pathInfo);
} else {
    $base_url .= isset($_SERVER['SCRIPT_NAME']) ? dirname($_SERVER['SCRIPT_NAME']) : dirname(getenv('SCRIPT_NAME'));
}

$path_level = 0;
while($base_url && !file_exists(str_repeat("../", $path_level) . 'index.php')) {
    $base_url = dirname($base_url);
    $path_level++;
}

if (substr($base_url, -1, 1) == "/") {
    $base_url = substr($base_url, 0, strlen($base_url) - 1);
}

if (empty($base_url)) {
    $GLOBALS['bethlehem']['setting']['base_url'] = "/";
} else {
    $GLOBALS['bethlehem']['setting']['base_url'] = $base_url;
}

