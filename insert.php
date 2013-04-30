<?php

include('test_main.php');

$user_test_id=$_REQUEST["user_test_id"];
$q_id=$_REQUEST["q_id"];
$answer=$_REQUEST["answer"];

$pars = array(
    ':user_test_id' => $user_test_id,
    ':q_id' => $q_id
);
$check_default=$db->db_query("select * from user_choice where user_test_id=:user_test_id && q_id=:q_id", $pars);
$rec=$db->single();

if ($rec>0)
    {
	if ($answer == 0)
		{
		$sqlstring="UPDATE user_choice SET answer=NULL WHERE user_test_id=:user_test_id && q_id=:q_id";
		$result=$db->db_query($sqlstring, $pars);
        }

	else
		{
            $pars = array(
                ':user_test_id' => $user_test_id,
                ':q_id' => $q_id,
                ':answer' => $answer
            );
		$sqlstring="UPDATE user_choice SET answer=:answer WHERE user_test_id=:user_test_id && q_id=:q_id";
		$result=$db->db_query($sqlstring, $pars);

		}
    }
else
    {
	if ($answer == 0)
		{
            $sqlstring="INSERT INTO user_choice (user_test_id, q_id, answer) VALUES (:user_test_id, :q_id, '')";
		$result=$db->db_query($sqlstring, $pars);

		}
	else
		{
            $pars = array(
                ':user_test_id' => $user_test_id,
                ':q_id' => $q_id,
                ':answer' => $answer
            );
		$sqlstring="INSERT INTO user_choice (user_test_id, q_id, answer) VALUES (:user_test_id, :q_id, :answer)";
		$result=$db->db_query($sqlstring, $pars);

		}
    }
?>