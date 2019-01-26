<?php

namespace Library;


class Pagination
{
    var $total = 0;
    var $current = 1;
    var $start = 0;
    var $limit = 0;
    var $max_page = 0;
    var $tag_page = 7;
    
    function __construct()
    {
        $this->id = "page_selector";
        
        $this->start = 0;
        $this->limit = $GLOBALS['bethlehem']['setting']['pagination_next_matches'];
        $this->total = 0;
        $this->current = 1;
        
        $this->max_page = 0;
        
        $this->url = [];
        $this->params = [];
    }
    
    function setConfig($config=[])
    {
        if (isset($config['url'])) {
            $this->url = $config['url'];
        }
        
        if (isset($config['start']) && is_numeric($config['start'])) {
            $this->start = $config['start'];
        }
        
        if (isset($config['limit']) && !empty($config['limit']) && is_numeric($config['limit']) && $config['limit'] > 0) {
            $this->limit = $config['limit'];
        }

        if (isset($config['total']) && !empty($config['total']) && is_numeric($config['total']) && $config['total'] > 0) {
            $this->total = $config['total'];
        }
        
        if ($this->total >= 1) {
            $this->max_page = ($this->total % $this->limit) ? ((int)($this->total / $this->limit) + 1) : (int)($this->total / $this->limit);
        } else {
            $this->max_page = 0;
        }
        
        if ($this->start > 0) {
            $this->current = (int)(($this->start + $this->limit) / $this->limit);
        } else {
            $this->current = 1;
        }
        
        if (isset($config['params'])) {
            $this->params = $config['params'];
        }
        
        return $this;
    }
    
    function create()
    {
        return $this->getContent();
    }
    
    function getContent()
    {
        $first_page_text = sprintf('<a href="javascript:goToPage(1);"><strong>%s</strong><span class="bethlehem-item-code">&nbsp;&nbsp;(P1)</span></a>', Lang('first page'));
        $previous_page_text = $this->current > 1 ? sprintf('<a href="javascript:goToPage(%d);"><strong>%s</strong><span class="bethlehem-item-code">&nbsp;&nbsp;(P2)</span></a>', $this->current - 1, Lang('previous page')) : sprintf('<span class="bethlehem-item-grey"><strong>%s</strong><span class="bethlehem-item-code">&nbsp;&nbsp;(P2)</span></span>', Lang('previous page'));
        $next_page_text = $this->current < $this->max_page ? sprintf('<a href="javascript:goToPage(%d);"><strong>%s</strong><span class="bethlehem-item-code">&nbsp;&nbsp;(P4)</span></a>', $this->current + 1, Lang('next page')) : sprintf('<span class="bethlehem-item-grey"><strong>%s</strong><span class="bethlehem-item-code">&nbsp;&nbsp;(P4)</span></span>', Lang('next page'));
        $last_page_text = sprintf('<a href="javascript:goToPage(%d);"><strong>%s</strong><span class="bethlehem-item-code">&nbsp;&nbsp;(P5)</span></a>', $this->max_page, Lang('last page'));

        if ($this->max_page <= 1) {
            return '';
        }
        
        $page_selector = '';
        for ($i=1; $i<=$this->max_page; $i++) {
            $page_selector .= sprintf('<option value="%d" %s>%d</option>', $i, $this->current == $i ? 'selected' : '',$i);
        }
        $page_selector = '<select id="'. $this->id .'" onchange="goToPage(this.value);";>'. $page_selector .'</select><span class="bethlehem-item-code">&nbsp;&nbsp;(P3)</span>';
        
        $style = '  <style>
                        .bethlehem-item-pagination a {
                            color: black;
                        }
                    </style>';
        $script = ' <script>
                        function goToPage(page)
                        {
                            var start = (page - 1) * '. $this->limit .';
                            var params = '. json_encode($this->params) .'
                            params["start"] = start;
                            search'. $GLOBALS['app']->route->app .'ListRows("'. $GLOBALS['app']->link("/{$GLOBALS['app']->route->app}/search") .'", params);
                        }
                    </script>';
        
        
        return "{$style}{$script}<div class=\"bethlehem-item-pagination\">{$first_page_text}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$previous_page_text}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$page_selector}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$next_page_text}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$last_page_text}</div>";
    }
}
