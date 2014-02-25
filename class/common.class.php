<?php
session_start();
require_once("database.class.php");
class common extends database
{
	function userLogin($username, $pass)
	{
		if ($username=="administrator")
		{
			if ($pass=="admin")
			{
				$_SESSION['user'] = "admin";
			}
			else
			{
				die("Password salah.");	
			}
		}
		else
		{
			die("Username salah.");
		}
	}
	
	function isLogin()
	{
		if ( isset( $_SESSION['nip'] ) )
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}
}
?>