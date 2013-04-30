<?php

require_once ('../config.php');
include('../test_main.php');
$tid=$_REQUEST['t_id'];
$q_id=$_REQUEST['q_id'];
$pars = array(':q_id'=>$q_id);
$result=$db->db_query("DELETE FROM questions WHERE id=:q_id",$pars);

if (isset($_REQUEST['case'])){
$case = $_REQUEST['case'];
	if ($case==1)
	{
    $pars = array(':q_id'=>$q_id);
	$result=$db->db_query("DELETE FROM user_choice WHERE q_id=:q_id",$pars);
	}
	else
		{
            $pars = array(':q_id'=>$q_id);
		    $user_choice =$db->db_query("SELECT * FROM user_choice WHERE q_id=:q_id",$pars);
            $user_choice_set=$db->resultset();
            foreach ($user_choice_set as $i => $rec) {
                $pars = array(':rec'=>$rec[1]);
			    $result=$db->db_query("DELETE FROM user_test WHERE id=:rec",$pars);
			    $result=$db->db_query("DELETE FROM user_choice WHERE user_test_id=:rec",$pars);
		    }
		}
}
echo ('
        <div class="msg">
			<div class="content">
			<div class="info_box clearfix" >
			<div class="box_icon" data-icon="y" aria-hidden="true"></div>
			<div class="content clearfix">
			<h1>' . _ADMIN_DELETE_THIS_QUESTION_SURELY . '</h1>
			<ul><li>' . _ADMIN_DELETED_THIS_QUESTION . '</li></ul>
			</div>
			</div>
			<div id="back" class="button_wrap clearfix">
			<a class="button" id="back_b" href="questions?tid=' . $tid . '"><div data-icon="b" aria-hidden="true" class="grid_img"></div><div class="grid_txt">' . _ADMIN_RETURN . '</div></a>
			</div>
			</div>
	    </div>
');
?>