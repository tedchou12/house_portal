<?php

namespace Library;
use DB;


class File
{  
    var $app;
    
    function __construct($app='')
    {
        $this->app = empty($app) ? $GLOBALS['app']->route->app : $app;
    }
    
    function upload($var=[])
    {
        $tmp_name = isset($var['tmp_name']) ? $var['tmp_name'] : NULL;
        $real_name = isset($var['real_name']) ? $var['real_name'] : NULL;
        
        if (empty($tmp_name) || empty($real_name)) {
            return False;
        }
        
        $destination = sprintf('%s/%s/%s', $GLOBALS['bethlehem']['setting']['upload_folder'], $this->app, $real_name);
        move_uploaded_file($tmp_name, $destination);
        return "/{$this->app}/{$real_name}";
    }

    function getUploadPath($var=[])
    {
        $tmp_name = isset($var['tmp_name']) ? $var['tmp_name'] : NULL;
        $real_name = isset($var['real_name']) ? $var['real_name'] : NULL;
        
        if (empty($tmp_name) || empty($real_name)) {
            return False;
        }
        
        $destination = sprintf('%s/%s/%s', $GLOBALS['bethlehem']['setting']['upload_folder'], $this->app, $real_name);
        move_uploaded_file($tmp_name, $destination);
        return "/{$this->app}/{$real_name}";
    }
    
    function download()
    {
        
    }
    
    function genLink($path='')
    {
        if (empty($path)) {
            return '';
        }

        $path = base64_encode($path);
        
        return $GLOBALS['app']->link('/download.php', "raw={$path}");
    }
}