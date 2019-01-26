<?php

if (isset($_GET['debug'])) {
  ini_set('display_errors', true);
}

define('BETHLEHEM_ROOT_DIR', $base_directory);
define('BETHLEHEM_LIB_DIR', BETHLEHEM_ROOT_DIR.'/Library');
define('BETHLEHEM_APP_DIR', BETHLEHEM_ROOT_DIR.'/App');

$GLOBALS['bethlehem']['setting'] = array(
    'db_host' => 'localhost',
    'db_port' => '3306',
    'db_name' => 'litocrm',
    'db_user' => 'litocrm',
    'db_pass' => 'HKXTeMT9ASuBTjPm',
    'db_type' => 'mysqli',
    'authentication' => true && false,
    'session_timeout' => 14400,
    'pagination_next_matches' => 30,
    'upload_folder' => BETHLEHEM_ROOT_DIR .'/../STS-Upload',
    'language' => 'tw',
);

require_once dirname(__FILE__) .'/conflict.php';

$GLOBALS['bethlehem']['setting']['module'] = array(
    'home' => array(
        'enabled' => True
    ),
    // 'account' => array(
    //     'enabled' => True,
    //     'permission' => array(
    //         'action' => array('index', 'search')
    //     )
    // )
);

if (file_exists(dirname(__FILE__) .'/override.php')) {
    require_once dirname(__FILE__) .'/override.php';
}

if (defined('BETHLEHEM_OVERRIDE_FILE')) {
    require_once BETHLEHEM_OVERRIDE_FILE;
}
