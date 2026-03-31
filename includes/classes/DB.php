<?php
interface DB 
{ 	
    public function error(); 
    public function errno(); 
    public function escape($string); 
    public function executeQry(string $query);     
    public function getTotalRow(object $resourceid);
    public function fetchValue(string $tbl, string $field, string $con);    
    public function singleValue(string $tbl, string $col, string $con);
    public function deleteRec(string $table, string $condition);
    public function fetch_array($result, $array_type = MYSQLI_BOTH);
    public function fetch_row($result); 
    public function fetch_assoc($result);
    public function fetch_object($result);
    public function num_rows($result);
    public function insert_id();	
} 
?>