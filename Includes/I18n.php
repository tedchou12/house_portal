<?php

namespace Includes;


class I18n
{
    function __construct($locale='tw', $app='common')
    {
        $this->locale = $locale;
        $this->app = $app;
        $this->load();
    }

    function load()
    {
        $this->string_data = array();

	    $common_lang = BETHLEHEM_APP_DIR ."/Common/languages/{$this->locale}.php";
  		if (file_exists($common_lang)) {
  		    $data_lang = require $common_lang;
  			$this->string_data += $data_lang;
  		}

  		$app_lang = BETHLEHEM_APP_DIR ."/{$this->app}/languages/{$this->locale}.php";
  		if (file_exists($app_lang)) {
  		    $data_lang = require $app_lang;
  			if (is_array($data_lang))
  			$this->string_data += $data_lang;
  		}
    }

    function translate($key='')
    {
      if (isset($this->string_data[$key])) {
          return $this->string_data[$key];
      } else if (isset($this->string_data[strtolower($key)])) {
          return $this->string_data[strtolower($key)];
      }

      if ($this->locale == 'en') {
        $key = ucfirst($key);
      }

      return $key;
    }
}
