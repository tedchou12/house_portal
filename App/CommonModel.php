<?php

namespace App;
use Includes\Base;


class CommonModel extends Base
{
    
    function __construct($id=0) 
    {
        parent::__construct($id);
    }
}


class CommonLog extends Base
{
    var $cache_results;
    var $table = '%s_log';
    var $primary_key = 'log_id';
    const default_fields = [
        'log_id' => NULL,
        'log_item' => '',
        'log_raw' => '',
        'log_created' => '',
        'log_creator' => ''
    ];
    
    
    function __construct($table='log', $id=0)
    {
        $this->table = empty($table) ? '' : sprintf($this->table, $table);
        parent::__construct($id);
    }
    
    function store()
    {
        $this->setVar('log_created', date('Y-m-d H:i:s'));
        $this->setVar('log_creator', GetCurrentUserId());
        parent::store();
    }
    
    function setRaw($input=[])
    {
        $this->setVar('log_raw', json_encode($input));
    }
    
    function getRaw()
    {
        json_decode($this->getVar('log_raw'));
    }
    
    function setItem($id=0)
    {
        $this->setVar('log_item', $id);
    }
    
    function loadHistory($id=0)
    {
        $histories = [];
        
        if ($this->isId($id)) {
            $results = $this->search(sprintf("`log_item` = %d", $id));
            
            if (!empty($results) && is_array($results)) {
                foreach ($results as $k => $item) {
                    $histories[] = [
                        'log_id' => $item['log_id'],
                        'log_item' => $item['log_item'],
                        'log_raw' => json_decode($item['log_raw'], true),
                        'log_created' => $item['log_created'],
                        'log_creator' => $item['log_creator']
                    ];
                }
            }
        }
        
        return $histories;
    }
}


define('REPORT_TYPE_NORMAL', 0);
define('REPORT_TYPE_MILESTONE', 1);
define('REPORT_TYPE_BOTH', 2);


class CommonReport extends CommonModel
{
    var $cache_results;
    var $table = 'report';
    var $primary_key = 'report_id';
    const default_fields = [
        'report_id' => NULL,
        'report_type' => '0',
        'report_item' => '',
        'report_item_id' => '',
        'report_status' => '',
        'report_description' => '',
        'report_geolocation' => '',
        'report_fee' => '',
        'report_extra' => '',
        'report_created' => '',
        'report_creator' => '',
        'report_modified' => '',
        'report_modifier' => ''
    ];
    
    
    function __construct($id=0)
    {
        parent::__construct($id);
    }

    function load($id)
    {
        parent::load($id);

        return $this->getId();
    }
    
    function store()
    {
        if (is_array($this->getVar('report_extra'))) {
            $this->setVar('report_extra', json_encode($this->getVar('report_extra')));
        }
        
        $this->setVar('report_modified', date('Y-m-d H:i:s'));
        $this->setVar('report_modifier', GetCurrentuserId());
        
        if (!$this->getId()) {
            $this->setVar('report_created', date('Y-m-d H:i:s'));
            $this->setVar('report_creator', GetCurrentuserId());
        }
        
        return parent::store();
    }

    function getLastReportId($item='', $item_id=0)
    {
        $item = empty($item) ? $this->getVar('report_item') : $item;
        $item_id = empty($item_id) ? $this->getVar('report_item_id') : $item_id;
        $results = $this->search(sprintf("`report_item_id` = %d AND `report_item` = '%s'", $item_id, $item), '', '', '', '', '', '', 'MAX(`report_id`) AS `report_id`');

        return isset($results[0]['report_id']) ? $results[0]['report_id'] : 0;
    }
}
