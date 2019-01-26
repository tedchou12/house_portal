<?php

function GetVar($variable, $method='any', $default_value='')
{
    if(!is_array($method)) {
        $method = [$method];
    }

    foreach ($method as $type) {
        switch (strtoupper($type)) {
            case 'FILE':
                return isset($_FILES[$variable]) ? $_FILES[$variable] : NULL;
            case 'COOKIE':
                return isset($_COOKIE[$variable]) ? $_COOKIE[$variable] : ($default_value === '' ? NULL : $default_value);
                break;
            case 'POST':
                return isset($_POST[$variable]) ? $_POST[$variable] : ($default_value === '' ? NULL : $default_value);
                break;
            case 'GET':
                return isset($_GET[$variable]) ? $_GET[$variable] : ($default_value === '' ? NULL : $default_value);
                break;
            case 'REQUEST':
            case 'ANY':
                return isset($_REQUEST[$variable]) ? $_REQUEST[$variable] : ($default_value === '' ? NULL : $default_value);
                break;
        }
    }

    return NULL;
}

function &CreateObject($object_cmd)
{
    list($object_type, $object_name) = explode('.', $object_cmd);

    if ($object_type == 'Model'  ||  $object_type == 'Controller') {
        $class_name = "{$object_name}{$object_type}";
        $file_path = BETHLEHEM_APP_DIR ."/{$object_name}{$object_type}.php";
        $class_name = "App\\{$object_name}{$object_type}";
    } else if ($object_type == 'Lib' || $object_type == 'Library') {
        $class_name = $object_name;
        $file_path = BETHLEHEM_ROOT_DIR ."/Library/{$object_name}.php";
        $class_name = "Library\\$object_name";
    } else {die($object_cmd);
        die('no file');
    }

    if (file_exists($file_path)) {
        require_once $file_path;

        if (class_exists($class_name)) {
            $args = func_get_args();

            if (count($args) == 1) {
                $obj = new $class_name();
                return $obj;
            } else if (count($args) > 1) {
                $code = '$obj =& new ' . $class_name . '(';
                foreach($args as $n => $arg) {
                    if ($n) {
                        $code .= ($n > 1 ? ',' : '') . '$args[' . $n . ']';
                    }
                }
                $code .= ');';
                $obj = new Library\Captcha($args[1]);
                return $obj;
            }
        }
        die($class_name . ";;;;");
    }

    die($file_path);
}

function Lang($key)
{
    return $GLOBALS['app']->i18n->translate($key);
}
