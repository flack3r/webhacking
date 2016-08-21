<?php
	$db_name = "bbs";
	$user_name = "phper";
	$user_password = "phper2003";

	$dbconn = mysql_connect("localhost", $user_name, $user_password);
	if(!$dbconn)
	{
		die('could not connect ' . mysql_error());
	}
	$status = mysql_select_db($db_name, $dbconn);
	if(!$status)
	{
		die("can't use bbs" . mysql_error());
	}
?>