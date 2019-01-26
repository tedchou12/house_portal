<?php

namespace Library;


class Request
{
    var $url = '';
    var $type = 'POST';
    var $data = [];
    var $fields_string = '';
    
    function __construct($url='', $type='POST', $data=[])
    {
        $this->setUrl($url);
        $this->setType($type);
        $this->setData($data);
    }

    function setUrl($url='')
    {
        if (!empty($url)) {
            $this->url = $url;
        }

        if ($this->url && substr($this->url, 0, 1) == "/") {
            $this->url = 'http://localhost' . $this->url;
        }
    }

    function setType($type='')
    {
        if (!empty($type)) {
            $type = strtoupper($type);

            if ($type == 'POST' || $type == 'GET') {
                $this->type = $type;
            }
        }
    }

    function setData($data=[])
    {
        if (!empty($data) && is_array($data)) {
            $this->data = $data;
        }
    }

    function serialize()
    {
        $this->fields_string = '';
        foreach ($this->data as $key => $value) {
            if (!is_array($value)) {
                $this->fields_string .= urlencode($key) .'='. urlencode($value) .'&';
            } else {
                foreach($value as $v) {
                    $this->fields_string .= urlencode($key) .'[]='. urlencode($v) .'&';
                }
            }
        }
        rtrim($this->fields_string, '&');
    }

    function send()
    {
        $this->serialize();

        if ($this->type == 'GET') {
            if (strpos($this->url, '?') === False) {
                $this->url .= '?'. $this->fields_string;
            } else {
                $this->url .= '&'. $this->fields_string;
            }
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, True);
        
        if ($this->type == 'POST') {
            curl_setopt($ch, CURLOPT_POST, True);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->fields_string);
        } else if ($this->type == 'GET') {

        } else {
            die('SEND ERROR');
        }
        

        $result = curl_exec($ch);
        $errno = curl_errno($ch);
        $error = curl_error($ch);

        curl_close($ch);

        return $result;
    }
}