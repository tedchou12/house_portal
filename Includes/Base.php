<?php

namespace Includes;


abstract class Base
{
    var $db;
    var $fields;


    function __construct($id=0)
    {
        //$this->db = clone($GLOBALS['app']->db);

        if (is_numeric($id) && $id > 0) {
            $this->load($id);
        }
    }

    function getKey()
    {
        return $this->primary_key;
    }

    function searchQuery($query='*', $where='', $join='', $orderby='', $orderdir='', $key='', $start=0, $limit=-1, $groupby='', $having='')
    {
        if ($orderby) {
            $sql = "SELECT %s FROM %s %s WHERE %s ORDER BY %s %s";
        } else {
            $sql = "SELECT %s FROM %s %s WHERE %s";
        }

        if ($query == '*') {
            $query_sql = "*";
        } else if (is_array($query)) {
            $query_sql = implode(", ", $query);
        } else {
            $query_sql = $query;
        }

        if (empty($where)) {
            $where = "1=1";
        } else if (is_array($where)) {
            $tmp = implode(" AND ", $where);
            $where = $tmp;
        }

        if (empty($orderby)) {
            $orderby = $this->primary_key;
        }

        if (empty($orderdir)) {
            $orderdir = 'ASC';
        }

        $extra_sql = '';

        if ($groupby) {
            $extra_sql = ' GROUP BY ' . $groupby;

            if ($having) {
            $extra_sql .= ' HAVING ' . $having;
            }
        }

        $this->db->query(sprintf($sql, $query_sql, $this->table, $join, $where, $orderby, $orderdir) . $extra_sql, __LINE__,__FILE__,$start,$limit);
        $result = array();

        do {
            $this->db->nextRecord();
            $tmp = $this->db->Record;

            if ($tmp) {
                if ($key) {
                    $result[$tmp[$key]] = $tmp;
                } else {
                    $result[] = $tmp;
                }
            }
        }
        while(is_array($tmp));

        return $result;
    }

    function searchWithFields($fields, $where='', $orderby='', $orderdir='', $key='', $start=0, $limit=-1, $join='')
    {
        return $this->searchQuery($fields, $where, $join, $orderby, $orderdir, $key, $start, $limit);
    }

    function searchAll($where='', $orderby='', $orderdir='', $key='', $join='', $query='*')
    {
        return $this->searchQuery($query, $where, $join, $orderby, $orderdir, $key, 0, -1);
    }

    function search($where='', $orderby='', $orderdir='', $key='', $start=0, $limit=0, $join='', $query='*')
    {
        $limit = empty($limit) ? $GLOBALS['bethlehem']['setting']['pagination_next_matches'] : $limit;
        return $this->searchQuery($query, $where, $join, $orderby, $orderdir, $key, $start, $limit);
    }

    function searchCount($where='', $join='')
    {
        $sql = "SELECT count(%s) FROM %s %s WHERE %s";

        if (empty($where)) {
            $where = "1=1";
        } else if (is_array($where)) {
            $tmp = implode(" AND ", $where);
            $where = $tmp;
        }

        $this->db->query(sprintf($sql, $this->primary_key, $this->table, $join, $where));
        $this->db->nextRecord();
        $tmp = $this->db->Record;

        return intval($tmp[0]);
    }

    function store($force_insert=False)
    {
        $key = $this->primary_key;

        if (empty($key) || empty($this->fields[$key]) || $force_insert === True) {
            $this->db_insert($force_insert);
        } else {
            $this->db_update();
        }

        return $this->fields[$key];
    }

    function dbquote($name)
    {
        return $this->db->Link_ID->qstr($name);
    }

    function dbnamequote($name)
    {
        return $this->db->name_quote($name);
    }

    function delete($key = '')
    {
        if(!$key){
            $key = $this->primary_key;
        }

        $key_value = $this->fields[$key];
        $table = $this->table;

        if ($key && $key_value) {
            $where = array(
                "$key = '$key_value'"
            );

            return $this->db->delete($table, $where);
        }
    }

    function db_query($sql)
    {
        $this->db->query($sql);
    }

    function db_update()
    {
        $sql = "UPDATE %s SET %s WHERE %s";
        $values = array();

        foreach ($this::default_fields as $field_name => $type) {
            if ($field_name != $this->getKey()) {
                if (isset($this->fields[$field_name]) && $this->fields[$field_name] !== NULL) {
                    $values[] = $this->db->name_quote($field_name) . "=" . $this->db->Link_ID->qstr($this->fields[$field_name]);
                }
            }
        }

        $key = $this->primary_key;
        $where = $this->db->name_quote($this->getKey()) . " = " . $this->db->Link_ID->qstr($this->fields[$key]);
        if (count($values)) {
            $this->db->query(sprintf($sql, $this->table, implode(",", $values), $where));
        }
    }

    function db_insert($force_insert=False)
    {
        $sql = "INSERT INTO %s (%s) VALUES (%s)";

        $values = array();

        if ($force_insert === True) {
            $values[$this->db->name_quote($this->getKey())] = $this->db->Link_ID->qstr($this->fields[$this->getKey()]);
        }

        foreach ($this::default_fields as $field_name => $type) {
            if ($field_name != $this->getKey()) {
                if (isset($this->fields[$field_name]) && $this->fields[$field_name] !== NULL) {
                    $values[$this->db->name_quote($field_name)] = $this->db->Link_ID->qstr($this->fields[$field_name]);
                } else if (!isset($this->fields[$field_name])) {
                    $values[$this->db->name_quote($field_name)] = $this->db->Link_ID->qstr($this::default_fields[$field_name]);
                }
            }
        }

        if (count($values)) {
            $this->db->query(sprintf($sql, $this->table, implode(",", array_keys($values)), implode(",", array_values($values))));

            if ($this->primary_key) {
                $key = $this->getKey();
                $this->fields[$key] = $this->db->get_last_insert_id($this->table, $this->primary_key);
            }
        }
    }

    function bind($data)
    {
        foreach ($this::default_fields as $field_name => $default_var) {
            if (isset($data[$field_name])) {
                if (is_string($data[$field_name])) {
                    $this->fields[$field_name] = stripslashes(trim($data[$field_name]));
                } else {
                    $this->fields[$field_name] = $data[$field_name];
                }
            }
        }
    }

    function load($id)
    {
        $where = $this->db->name_quote($this->primary_key) . '=' . $this->db->Link_ID->qstr($id);
        $record = $this->search($where);

        if (is_array($record) && count($record) == 1) {
            $data = $record[0];

            foreach ($this::default_fields as $field_name => $default_var) {
                if (isset($data[$field_name])) {
                    $this->fields[$field_name] = $data[$field_name];
                }
            }

            return true;
        } else {
            return false;
        }
    }

    function loadKey($key, $id)
    {
        $where = $this->db->name_quote($key) . '=' . $this->db->Link_ID->qstr($id);
        $record = $this->search($where);

        if (is_array($record) && count($record) == 1) {
            $data = $record[0];

            foreach ($this::default_fields as $field_name => $default_var) {
                $this->fields[$field_name] = $data[$field_name];
            }

            return true;
        }

        return false;
    }

    function toString()
    {
        $out = array();

        $vars = $this->fields;
        foreach ($vars as $var => $type) {
            $out[$var] = $this->fields[$var];
        }

        return $out;
    }

    function getId()
    {
        return $this->getVar($this->primary_key);
    }

    function setVar($key='', $var='')
    {
        if (!empty($key) && is_array($this::default_fields) && array_key_exists($key, $this::default_fields)) {
            $this->fields[$key] = $var;
        }
    }

    function getVar($key='')
    {
        return (!empty($key) && is_array($this::default_fields) && is_array($this->fields) && array_key_exists($key, $this::default_fields) && array_key_exists($key, $this->fields)) ? $this->fields[$key] : '' ;
    }

    function isId($id=0, $type='number')
    {
        if ($type == 'number') {
            return (!empty($id) && is_numeric($id) && $id > 0);
        } else {
            return True;
        }
    }
}
