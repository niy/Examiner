<?php

include('../test_main.php');

$test_id=$_REQUEST['test_id'];
$uid=$_REQUEST["user_id"];
$pars = array(
    ':uid'=>$uid,
    ':test_id'=>$test_id
);
$result=$db->db_query("SELECT * FROM user_test WHERE user_id=:uid AND test_id=:test_id",$pars);
$rec=$db->single();
$pars1 = array(
    ':rec'=>$rec[0]
);
$result2=$db->db_query("DELETE FROM user_choice WHERE user_test_id=:rec[0]",$pars1);

$result2=$db->db_query("DELETE FROM user_test WHERE user_id=:uid AND test_id=:test_id",$pars);

echo ('
        <div class="msg">
			<div class="content">
			<div class="info_box clearfix" >
			<div class="box_icon" data-icon="y" aria-hidden="true"></div>
			<div class="content clearfix">
			<h1>' . _ADMIN_ADD_USER_DELETE_USER . '</h1>
			<ul><li>' . _ADMIN_ADD_USER_DELETED_USER . '</li></ul>
			</div>
			</div>
			<div id="back" class="button_wrap clearfix">
			<a class="button" id="back_b" href="charts"><div data-icon="b" aria-hidden="true" class="grid_img"></div><div class="grid_txt">' . _ADMIN_RETURN . '</div></a>
			</div>
	    </div>
');
?>