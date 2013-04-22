<?php

require_once ('../config.php');
include('../test_main.php');
$tid=$_REQUEST['t_id'];
$q_id=$_REQUEST['q_id'];
$result=mysql_query("DELETE FROM questions WHERE id='$q_id'");

if (!$result)
    {
    die('Database query error:' . mysql_error());
    }
if (isset($_REQUEST['case'])){
$case = $_REQUEST['case'];
	if ($case==1)
	{
	$result=mysql_query("DELETE FROM user_choice WHERE q_id='$q_id'");

	if (!$result)
		{
		die('Database query error:' . mysql_error());
		}
	}
	else
		{
		$user_choice =mysql_query("SELECT * FROM user_choice WHERE q_id='$q_id'", $db);
		do {
			$result=mysql_query("DELETE FROM user_test WHERE id='$rec[1]'");

			if (!$result)
				{
				die('Database query error:' . mysql_error());
				}
			$result=mysql_query("DELETE FROM user_choice WHERE user_test_id='$rec[1]'");

			if (!$result)
				{
				die('Database query error:' . mysql_error());
				}
			
		} while ($rec=mysql_fetch_row($user_choice));
		}
}
echo ('
        <div class="msg">
			<div class="content">
			<div class="info_box clearfix" style="width:27em;">
			<div class="box_icon" data-icon="y" aria-hidden="true"></div>
			<div class="content clearfix">
			<h1>' . _ADMIN_DELETE_THIS_QUESTION_SURELY . '</h1>
			<ul><li>' . _ADMIN_DELETED_THIS_QUESTION . '</li></ul>
			</div>
			</div>
			<div id="back" class="button_wrap">
			<a class="button" id="back_b" href="questions?tid=' . $tid . '"><div data-icon="b" aria-hidden="true" class="grid_img"></div><div class="grid_txt">' . _ADMIN_RETURN . '</div></a>
			</div>
			</div>
	    </div>
');
?>