<?php

/*======================================================================*\
* || #################################################################### ||
* || #                Script Coded By Mustafa Taj Elsir                 # ||
* || # Copyright ©2011-2012 www.c4p.ma All Rights Reserved.             # ||
* || # This file may not be redistributed in whole or significant part. # ||
* || # ------------------- This IS NOT FREE SOFTWARE ------------------ # ||
* || #################################################################### ||
* \*======================================================================*/
if (!defined('OIU'))
{
    die('Access denied');
}
class Database
{
    var $dbhost;
    var $dbuser;
    var $dbpassword;
    var $dbname;
    var $total_query;
    var $total_query_string;
    var $total_free_result;
    var $db_link;
    var $ErrorLine;
    var $template_error_path;
    var $last_query;
    var $sqlIDs = array();
    function Database($dbhost = '', $dbuser = '', $dbpassword = '', $dbname = '', $template_error_path = '/')
    {
        $this->dbhost = $dbhost;
        $this->dbuser = $dbuser;
        $this->dbpassword = $dbpassword;
        $this->dbname = $dbname;
        $this->total_query = 0;
        $this->total_free_result = 0;
        $this->template_error_path = $template_error_path;
    }
    function record_count($sql)
    {
        $mSQL = $this->query_read($sql);
        $num_row = $this->num_rows($mSQL);
        $this->free_result($mSQL);
        unset($sql, $mSQL);
        return $num_row;
    }
    function fetch_array($sql)
    {
        return @mysql_fetch_array($sql);
    }
    function print_db_error()
    {
        $data = file_get_contents($this->template_error_path . 'SQL_Error.tpl');
        $data = str_replace(array(
            '{sqlError}',
            '{ERROR_PATH}',
            '{LAST_QUERY}',
            '{ERROR_LINE}'), array(
            $this->db_error(),
            'http://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"],
            $this->last_query,
            $this->ErrorLine), $data);
        echo $data;
        exit();
    }
    function query_first($sql)
    {
        $this->last_query = $sql;
        $queryresult = $this->query_write($sql);
        $returnarray = @mysql_fetch_array($queryresult);
        $this->free_result($queryresult);
        $this->total_query++;
        $this->total_query_string[] = $sql;
        return $returnarray;
    }
    function Connection($line = 0)
    {
        $this->ErrorLine = $line;
        $this->db_link = @mysql_connect($this->dbhost, $this->dbuser, $this->dbpassword) or die($this->print_db_error());
        @mysql_select_db($this->dbname) or die($this->print_db_error());
        unset($this->dbhost, $this->dbuser, $this->dbpassword, $this->dbname);
    }
    function stop_db($_link = '')
    {
        if (empty($_link))
            $_link = $this->db_link;
        unset($this->total_query_string, $this->total_query, $this->total_free_result);
        @mysql_close($_link);
    }
    function query_read($query, $line = 0, $errormsg = true)
    {
        $this->ErrorLine = $line;
        $this->total_query++;
        $this->total_query_string[] = $query;
        $this->last_query = $query;
        if ($errormsg == true)
        {
            $return = @mysql_query($query) or die($this->print_db_error());
        } elseif (!$errormsg)
        {
            $return = @mysql_query($query);
        }
        $this->sqlIDs[] = $return;
        return $return;
    }
    function query_write($query, $line = 0, $errormsg = true)
    {
        $this->ErrorLine = $line;
        $this->total_query++;
        $this->total_query_string[] = $query;
        $this->last_query = $query;
        if ($errormsg == true)
        {
            $return = @mysql_query($query) or die($this->print_db_error());
        } elseif (!$errormsg)
        {
            $return = @mysql_query($query);
        }
        $this->sqlIDs[] = $return;
        return $query;
    }
    function record($query)
    {
        return @mysql_fetch_array($query);
    }
    function num_rows($query)
    {
        return @mysql_num_rows($query);
    }
    function db_error()
    {
        return mysql_error();
    }
    function affected_rows()
    {
        return @mysql_affected_rows();
    }
    function free_result($query)
    {
        $this->total_free_result++;
        return @mysql_free_result($query);
    }
    function free_all_results(){
        if (count($this->sqlIDs) == 0) return true;
        foreach ($this->sqlIDs as $key){
            $this->total_free_result++;
            @mysql_free_result($key);
        }
        
    }
    function fields_count($row, $table, $where = '')
    {
        $where = ($where != '') ? "where $where" : "";
        $sql = $this->query_read("select count($row) as total from $table $where");
        if ($this->num_rows($sql) == 0)
            return 0;
        $record = $this->record($sql);
        return $record["total"];
    }
    function escape_string($string)
    {
        return @mysql_real_escape_string($string);
    }
    function fetch_object($string)
    {
        return @mysql_fetch_object($string);
    }
    function strip_backticks($text)
    {
        return str_replace('`', "'", $text);
    }
    function get_last_sql()
    {
        return $this->last_query;
    }
}

?>
