<?php

include('test_main.php');

$user_test_id=$_REQUEST["user_test_id"];
$q_id=$_REQUEST["q_id"];
$answer=$_REQUEST["answer"];


$check_default=mysql_query("select * from user_choice where user_test_id='$user_test_id' && q_id='$q_id'", $db);

if ($rec=mysql_fetch_row($check_default))
    {
	if ($answer == 0)
		{
		$sqlstring="UPDATE user_choice SET answer=NULL WHERE user_test_id='$user_test_id' && q_id='$q_id'";
		$result=mysql_query($sqlstring, $db);
	
		if (!$result)
			{
			die('Database query error:' . mysql_error());
			}
		}
	else
		{
		$sqlstring="UPDATE user_choice SET answer='$answer' WHERE user_test_id='$user_test_id' && q_id='$q_id'";
		$result=mysql_query($sqlstring, $db);
	
		if (!$result)
			{
			die('Database query error:' . mysql_error());
			}
		}
    }
else
    {
	if ($answer == 0)
		{
            $sqlstring="INSERT INTO user_choice (user_test_id, q_id, answer) VALUES ('$user_test_id', '$q_id', '')";
		$result=mysql_query($sqlstring, $db);
	
		if (!$result)
			{
			die('Database query error:' . mysql_error());
			}
		}
	else
		{
		$sqlstring="INSERT INTO user_choice (user_test_id, q_id, answer) VALUES ('$user_test_id', '$q_id', '$answer')";
		$result=mysql_query($sqlstring, $db);
	
		if (!$result)
			{
			die('Database query error:' . mysql_error());
			}
		}
    }
?>