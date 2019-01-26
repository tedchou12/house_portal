<?php

namespace Includes;


class Controller 
{
    var $params;

    function __construct($params='')
    {
        $this->init($params);
    }

    function init($params='')
    {
        $params = explode('/', $params);

        foreach ($params as $line) {
            $this->params[] = $line;

            if (strpos($line, '-') !== False) {
                $ary_line_split_dash = explode('-', $line);
                if (is_numeric($ary_line_split_dash[0])) {
                    continue;
                }

                $str_params_key = $ary_line_split_dash[0];
                unset($ary_line_split_dash[0]);
                $this->params[$str_params_key] = implode('-', $ary_line_split_dash);
            }
        }
    }
}
