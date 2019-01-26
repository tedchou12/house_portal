<?php

namespace Bootstrap;
use Includes\DB, Includes\I18N, Includes\Route, Includes\Session;


class App
{
    var $db;
  	var $file;
  	var $i18n;
  	var $perm;
    var $route;
  	var $session;

    function __construct($lang='')
    {
        $GLOBALS['app'] =& $this;
        if (isset($lang) && $lang) {
          setcookie('lang', $lang, time() + (86400 * 30), "/");
          $this->lang = $lang;
        } elseif (isset($_COOKIE['lang']) && $_COOKIE['lang']) {
          $this->lang = $_COOKIE['lang'];
        } else {
          switch (substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2)) {
            case 'ja' :
              $this->lang = 'ja';
              break;
            case 'zh' :
              $this->lang = 'zh';
              break;
            default :
              $this->lang = 'en';
              break;
          }
        }

        $this->setup();
    }

    function setup()
    {
        $this->route = new Route;
		    $app = $this->route->app;
        $action = $this->route->action;

		    // $this->db = new DB;
        // $this->db->set_app('Library');
        // $this->db->Halt_On_Error = 'yes';
        // $this->db->connect(
        //     $GLOBALS['bethlehem']['setting']['db_name'],
        //     $GLOBALS['bethlehem']['setting']['db_host'],
        //     $GLOBALS['bethlehem']['setting']['db_port'],
        //     $GLOBALS['bethlehem']['setting']['db_user'],
        //     $GLOBALS['bethlehem']['setting']['db_pass'],
        //     $GLOBALS['bethlehem']['setting']['db_type']
        // );

        $this->session = new Session;
    		// $this->lang = isset($_REQUEST['language']) ? $_REQUEST['language'] : (isset($_COOKIE['lang']) ? $_COOKIE['lang'] : $this->lang);

    		// if (isset($_REQUEST['language'])) {
    		// 	$this->session->setCookie('lang', $_REQUEST['language']);
    		// }

        $this->i18n = new I18n($this->lang, $this->route->app);
    }

    function start()
    {
        if (!empty($GLOBALS['bethlehem']['setting']['authentication'])) {
			$verified = $this->session->verify();
            if (!$verified && ($this->route->action != 'login' && $this->route->action != 'action')) {
                $this->redirectLink('/Account/login');
            } else if ($verified && $this->route->app == 'account' && $this->route->action == 'login') {
				$this->redirectLink('/Home');
			}
        }

		$this->view = CreateObject('Lib.View');
        $this->route->direct();
    }

    function link($url = '', $extravars = '')
    {
      if (isset($url{0}) && $url{0} != '/') {
        $app = $this->route->app;
		    if ($app == 'home') {

        } else if ($app != 'login' && $app != 'logout') {
          $url = '/'.$url;
        }
      }

			if ((isset($url{0}) && $url{0} != '/') || $GLOBALS['bethlehem']['setting']['base_url'] != '/')
			{
				if((isset($url{0}) && $url{0} != '/') && substr($GLOBALS['bethlehem']['setting']['base_url'],-1) != '/')
				{
					$url = $GLOBALS['bethlehem']['setting']['base_url'] .'/'. $url;
				}
				else
				{
					$url = $GLOBALS['bethlehem']['setting']['base_url'] . $url;
				}
			}

			if(isset($GLOBALS['plus_info']['default_setting']['force_https'])  &&  $GLOBALS['plus_info']['default_setting']['force_https']  && (empty($_SERVER['HTTPS']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] != 'https')) {
				if(substr($url ,0,4) != 'http')
				{
					$url = 'https://'.$GLOBALS['plus_info']['server']['hostname'].$url;
				}
				else
				{
					$url = str_replace ( 'http:', 'https:', $url);
				}
			}

			// check if the url already contains a query and ensure that vars is an array and all strings are in extravars
			if (strpos($url, '?') !== False) {
				list($url,$othervars) = explode('?',$url);
			}

			if ($extravars && is_array($extravars))
			{
				$vars = $extravars;
				$extravars = $othervars;
			}
			else
			{
				$vars = array();
				if (isset($othervars) && $othervars) $extravars .= '&'.$othervars;
			}

			// parse extravars string into the vars array
			if ($extravars)
			{
				foreach(explode('&',$extravars) as $expr)
				{
					list($var,$val) = explode('=', $expr,2);
					if (substr($var,-2) == '[]')
					{
						$vars[substr($var,0,-2)][] = $val;
					}
					else
					{
						$vars[$var] = $val;
					}
				}
			}

			if (count($vars))
			{
				$query = array();
				foreach($vars as $key => $value)
				{
					if (is_array($value))
					{
						foreach($value as $val)
						{
							$query[] = $key.'[]='.urlencode($val);
						}
					}
					else
					{
						$query[] = $key.'='.urlencode($value);
					}
				}
				$url .= '?' . implode('&',$query);
			}

			return $url;
    }

    function redirectLink($url = '',$extravars='')
    {
        $this->redirect($this->link($url, $extravars));
    }

    function redirect($url = '')
    {
        $url = empty($url) ? $_SERVER['PHP_SELF'] : $url;
        Header("Location: {$url}");
        die();
    }

    function view($assign=[], $options=[])
    {
      $template_name = empty($options['template_name']) ? $this->route->action : $options['template_name'];

      if (empty($options['NO_HTMLHEADER'])) {
        echo $this->view->displayHtmlHeader();
      }

      if (empty($options['NO_NAVBAR'])) {
        echo $this->view->displayNavbar();
      }

      $assign['URL_ROOT_DIR'] = $this->link('/');
      $assign['URL_RESOURCE_DIR'] = $this->link('/Resources');
      $assign['TXT_CURRENT_APP'] = $this->route->app;

      $this->view->setTemplate("{$template_name}.tpl.php");
      $this->view->assign('VARS', $assign);
      $this->view->assign('LANGS', $this->i18n->string_data);
      $this->view->display();

      if (empty($options['NO_HTMLFOOTER'])) {
        echo $this->view->displayHtmlFooter();
      }
    }
}
