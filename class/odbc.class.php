<?php
class odbc
{
	function __construct()
	{
		$this->dbconnect();
	}
	
	function dbconnect()
	{
		odbc_connect("localhost", "root", "");
		//mysql_select_db("absensi_djkn");
	}
	
	function dbFetchArray($query)
	{
		$rs = @mysql_query($query)or die(mysql_error()."<br>".$query);
		while ( $data = @mysql_fetch_array($rs, MYSQL_ASSOC) )
		{
			$return[] = $data;
		}
		if ( count($return) == 0 )
		{
			$return = "null";
		}
		return $return;
	}
	
	function dbExecuteQuery($query)
	{
		$rs = @mysql_query($query)or die(mysql_error());
		if ( $rs )
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}
	
	function dbCountRow($query)
	{
		$rs = @mysql_query($query)or die(mysql_error());
		return mysql_num_rows($rs);
	}
}
?>