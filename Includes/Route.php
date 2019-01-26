<?php

namespace Includes;


class Route
{
    function __construct()
    {
        $this->init();
    }

    function init()
    {
        if (isset($_REQUEST['route'])) {
            $route_arr = explode('/', $_REQUEST['route']);
            if (count($route_arr)) {
                $app = $route_arr[0];
                
                if (isset($route_arr[1]) && !empty($route_arr[1])) {
                    $action = $route_arr[1];
                }
            }
        }

        $this->app = empty($app) ? 'Home' : $app;
        $this->action = empty($action) ? 'index' : $action;
        $this->item_id = isset($route_arr[2]) ? $route_arr[2] : 0;
        $this->extra_params = '';

        if (count($route_arr) > 3) {
            unset($route_arr[0]);
            unset($route_arr[1]);
            unset($route_arr[2]);

            $route_arr = implode('/', $route_arr);
            $this->extra_params = $route_arr;
        }
    }

    function direct()
    {
        $app = $this->app;
        $action = $this->action;

        require_once BETHLEHEM_ROOT_DIR ."/Includes/Controller.php";
        $class = "App\\".strtolower($this->app)."Controller";
        $file = BETHLEHEM_APP_DIR ."/{$this->app}Controller.php";

        if (file_exists($file)) {
            require_once $file;

            if (class_exists($class)) {
                $app = new $class($this->item_id, $this->extra_params);
                if (method_exists($class, $action)) {
                    return $app->$action($this->item_id);
                } else {
                    $GLOBALS['app']->redirectLink("/{$this->app}");
                }
            } else {
                // die('Class is not exist');
            }
        } else {
            // die('File is not exist');
        }

        // $GLOBALS['app']->redirectLink("/Home");
    }
}
