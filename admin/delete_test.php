<?php

header("Content-Type: text/html; charset=utf-8");

if (!isset($_COOKIE['examiner'])) {
	header('Location: index');
} else {
	include('admin_config.php');

	if ((isset($_REQUEST["tid"])) && !(isset($_REQUEST["end"]))) {
		$tid = $_REQUEST["tid"];
        $pars = array(':tid'=>$tid);
		$result = $db->db_query("SELECT * FROM tests WHERE id=:tid",$pars);
		$rec = $db->single();

		echo ('<SCRIPT type="text/javascript">
	function dosubmit() {
	document.forms[0].action = "all_tests";
	document.forms[0].method = "POST";
	document.forms[0].submit();
	}
	</SCRIPT>');

		echo ('
		<article id="delete_test"><div class="content box">
		<form action="delete_test" method="post">

		<h1>' . _ADMIN_DELETE_EXAM . '</h1>
		' . _ADMIN_EXAM_DELETE_ARE_YOU_SURE . '

		<input type="hidden" name="tid" value="' . $tid . '">
		<input type="hidden" name="end" value="">

		<div class="button_wrap left clearfix">
		<input class="button bad" type="submit" value="' . _ADMIN_DELETE_EXAM_END . '" name="B1">
		<input class="button" type="button" name="bt1" value="' . _ADMIN_FORM_CANCEL . '" onClick="dosubmit()">
		</div>

		</div>
		</article>
		');
	} else if ((isset($_REQUEST["tid"])) && (isset($_REQUEST["end"]))) {
		$tid = $_REQUEST["tid"];
        $pars = array(':tid'=>$tid);
		$result = $db->db_query("DELETE FROM tests WHERE id=:tid",$pars);

		$result = $db->db_query("DELETE FROM questions WHERE test_id=:tid",$pars);

		echo('
            <article id="delete_test">
            <div class="content">
            <div class="info_box clearfix" >
            <div class="box_icon" data-icon="y" aria-hidden="true"></div>
            <div class="content clearfix">
            <h1>' . _ADMIN_DELETE_EXAM . '</h1>
            <ul><li>' . _ADMIN_EXAM_DELETED . '</li>
            <li>' . _ADMIN_EXAM_QUESTIONS_DELETED . '</li></ul>
            </div>
            </div>
            <div id="back" class="button_wrap clearfix">
            <a class="button" id="back_b" href="all_tests"><div data-icon="b" aria-hidden="true" class="grid_img"></div><div class="grid_txt">' . _ADMIN_EDIT_EXAMS . '</div></a>
            </div>
            </div>
            </article>');
        include ('../footer.php');
        include('../footer_end.php');
        die();
	} else {
        echo('
        <article class="msg">

		<div class="info_box clearfix" >
            <div class="box_icon" data-icon="y" aria-hidden="true"></div>
            <div class="content clearfix">' . _ADMIN_NOT_ALLOWED . '!</div>
		</div>

		<div id="back" class="button_wrap clearfix">
            <a class="button" id="back_b" href="../admin"><div data-icon="h" aria-hidden="true" class="grid_img"></div>
            <div class="grid_txt">' . _ADMIN_HOME . '</div></a>
		</div>

        </article>');
        include ('../footer.php');
        include('../footer_end.php');
        die();
	}
}
?>

<?php include('../footer.php');
include('../footer_end.php');?>