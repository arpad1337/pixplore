<?php

class Database
{
	public $specialQuery;
	
	public function __construct(){
		$link = mysql_connect('localhost', 'root', 'asdasd');
		if (!$link) {
			  throw new DatabaseException('Could not connect to database',110);
		}
		if (!mysql_select_db('pixplore')) throw new DatabaseException('Could not select the table in database',111);;
	}
	
	public function insertQuery($to,$fields = array())
	{
		$queryA = "INSERT INTO `".$to."` (";
		$queryB = ") VALUES (";
		foreach($fields as $k => $v)
		{
			$queryA .= "`".$k."`,";
			$queryB .= "'".addslashes($v)."',";
		}
		$query = substr($queryA,0,strlen($queryA)-1).substr($queryB,0,strlen($queryB)-1).')';
		mysql_query($query);
		return mysql_insert_id();
	}
	
	public function updateQuery($to,$fields = array(),$where)
	{
		$query = "UPDATE `".$to."` SET ";
		foreach($fields as $k => $v)
		{
			$query .= "`".$k."` = "."'".addslashes($v)."',";
		}
		$query = substr($query,0,strlen($query)-1)." WHERE ".$where;
		return (mysql_query($query));
	}
	
	public function deleteQuery($from,$where)
	{
		$query = "DELETE FROM `".$from."` WHERE ".$where;
		return mysql_query($query);
	}
	
	public function getRow($from,$fields,$where)
	{
		$where = (!empty($where))?' WHERE '.$where:'';
		$query = "SELECT ";
		foreach($fields as $key)
		{
			$query .= '`'.$key.'`,';
		}
		$query = substr($query,0,strlen($query)-1).'FROM `'.$from.'`'.$where;
		$result = mysql_query($query);
		
		return (mysql_num_rows($result) > 0)?parseData(mysql_fetch_assoc($result)):false;
	}
	
	public function getRows($from,$fields,$where = null,$limit = null, $offset = null)
	{
		$offset = (!empty($offset))?$offset:0;
		$limit = (!empty($limit))?'LIMIT '.$offset.','.$limit:null;
		$where = (!empty($where))?' WHERE '.$where:'';
		$query = "SELECT ";
		foreach($fields as $key)
		{
			$query .= '`'.$key.'`,';
		}
		$query = substr($query,0,strlen($query)-1).'FROM `'.$from.'`'.$where. ' '.$limit;

		$result = mysql_query($query);
		if(mysql_num_rows($result) > 0){
			while( $row = mysql_fetch_assoc($result)){ $data[] = $row; }
			return parseMultipleData($data);
		}else return false;
	}
	
	public function executeSpecialQuery()
	{
		$query = $this->specialQuery;
		$result = mysql_query($query);
		if(mysql_num_rows($result) > 0){
			while( $row = mysql_fetch_assoc($result)){ $data[] = $row; }
			return parseMultipleData($data);
		}else return false;
	}
	
	public function isExist($table,$where)
	{
		$query = "SELECT * FROM ".$table." WHERE ".$where;
		return (mysql_num_rows(mysql_query($query)) == 1);
	}
}

function parseData($array)
{
	foreach($array as $k => $v)
	{
		if(substr($v,0,1) == "[" || substr($v,0,1) == "{")
		{
			$array[$k] = json_decode($v);
		}
	}
	return $array;
}

function parseMultipleData($array)
{
	foreach($array[0] as $k => $v)
	{
		if(substr($v,0,1) == "[" || substr($v,0,1) == "{")
		{
			$array[0][$k] = json_decode($v);
			for( $i=1; $i<count($array) ; $i++)
			{
				$array[$i][$k] = json_decode($array[$i][$k]);
			}
		}
	}
	return $array;
}


?>