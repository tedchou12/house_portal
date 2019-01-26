<?php

namespace Includes;
use Includes\Base;


class Session extends Base
{
    var $cache_results;
    var $table = 'session';
    var $primary_key = 'session_id';
    const default_fields = [
        'session_id' => NULL,
        'session_account' => '1',
        'session_delete' => '',
        'session_data' => '',
        'session_created' => '',
        'session_expired' => ''
    ];
    
    
    function __construct()
    {
        parent::__construct();
        $this->ssid = GetVar('ssid', 'COOKIE');
        
        if (!empty($this->ssid)) {
            $this->load($this->ssid);
        }
    }
    
    function create($id='', $name='', $hash='')
    {
        $now = time();
        $expire = $now + $GLOBALS['bethlehem']['setting']['session_timeout'];
        
        $ssid = $now . $name .'bethlehem-session';
        $this->setCookie('ssid', md5($ssid));
        
        $this->setVar('session_id', md5($ssid));
        $this->setVar('session_account', $id);
        $this->setVar('session_delete', '0');
        $this->setVar('session_created', $now);
        $this->setVar('session_expired', $expire);
        $this->store(True);
    }
    
    function store($force_insert=False)
    {
        if (empty($this->getVar('session_id')) || empty($this->getVar('session_expired'))) {
            return False;
        }
        
        if (!empty($this->getVar('session_data'))) {
            $this->setVar('session_data', json_encode($this->getVar('session_data')));
        }
        
        parent::store($force_insert);
    }
    
    function load($id=0)
    {
        parent::load($id);
        
        if ($this->getId() == $id) {
            $this->setVar('session_data', json_decode($this->getVar('session_data'), True));
        }
    }
    
    function verify($ssid='')
    {
        if (empty($ssid = empty($ssid) ? $this->ssid : $ssid)) {
            return False;
        }
        
        if ($this->getVar('session_id') == $this->ssid) {
            return $this->fields;
        }
        
        $sessions = $this->search(sprintf('`session_id` = %s', $this->db->quote($this->ssid)));
        return empty($sessions[0]) || empty($sessions[0]['account_id']) ? False : $sessions[0];
    }
    
    function setCookie($cookiename,$cookievalue='',$cookietime=0)
    {
        if ((empty($_COOKIE[$cookiename]) || $cookievalue === '') || ($cookiename && $cookievalue)) {
            $baseUrl = $GLOBALS['bethlehem']['setting']['base_url'];

            if (empty($cookietime)) {
                $cookietime  = time() + $GLOBALS['bethlehem']['setting']['session_timeout'];
            }

            if ($cookiename && $baseUrl) {
                setcookie($cookiename,$cookievalue,$cookietime, $baseUrl);
            }
        }
    }
    
    function setMessage($msg='')
    {
        $this->fields['session_data']['app_message'] = (string)$msg;
        $this->store();
    }
    
    function getMessage()
    {
        if (!empty($this->fields['session_data'])) {
            if (is_array($this->fields['session_data'])) {
                $session_data = $this->fields['session_data'];
            } else {
                $session_data = json_decode($this->fields['session_data'], True);
            }
        }

        $msg = '';
        if (isset($session_data['app_message']) && (string)$session_data['app_message'] != '') {
            $msg = $session_data['app_message'];
            unset($session_data['app_message']);
            $this->setVar('session_data', $session_data);
            $this->store();
        }

        return $msg;
        $msg = isset($this->fields['session_data']['app_message']) ? (string)$this->fields['session_data']['app_message'] : '';
        if (isset($this->fields['session_data']['app_message'])) {
            unset($this->fields['session_data']['app_message']);
        }
        $this->store();
        return $msg;
    }
    
    function clean()
    {
        $this->delete();
    }
}
