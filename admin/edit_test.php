<?php

header("Content-Type: text/html; charset=utf-8");

if (!isset($_COOKIE['examiner'])) {
	header('Location: index');
} else {
	if ((isset($_REQUEST["tid"])) && !(isset($_REQUEST["end"]))) {
		include('admin_config.php');
		$tid = $_REQUEST["tid"];
        $pars = array(':tid'=>$tid);
		$result = $db->db_query("SELECT * FROM tests WHERE id=:tid",$pars);
		$rec = $db->single();

		/////////////
		if ($rec[3] == 1) {
			$default_checked = "checked";
			$default_checked2 = "";
		} else {
			$default_checked2 = "checked";
			$default_checked = "";
		}

		/////////////
		if ($rec[4] == 1) {
			$prof_checked = "checked";
			$prof_checked2 = "";
		} else {
			$prof_checked2 = "checked";
			$prof_checked = "";
		}

		/////////////
		if ($rec[5] == 1) {
			$random_checked = "checked";
			$random_checked2 = "";
		} else {
			$random_checked2 = "checked";
			$random_checked = "";
		}

		/////////////
		if ($rec[7] == 1) {
			$rtl_checked = "checked";
			$rtl_checked2 = "";
		} else {
			$rtl_checked2 = "checked";
			$rtl_checked = "";
		}

		/////////////
		if ($rec[8] == 1) {
			$minus_checked = "checked";
			$minus_checked2 = "";
		} else {
			$minus_checked2 = "checked";
			$minus_checked = "";
		}

		/////////////
		if ($rec[9] == 1) {
			$show_answers_checked = "checked";
			$show_answers_checked2 = "";
		} else {
			$show_answers_checked2 = "checked";
			$show_answers_checked = "";
		}

		/////////////
		if ($rec[10] == 1) {
			$show_mark_checked = "checked";
			$show_mark_checked2 = "";
		} else {
			$show_mark_checked2 = "checked";
			$show_mark_checked = "";
		}

		echo ('
	<script type="text/javascript">
		function dosubmit() {
		document.forms[0].action = "all_tests"
		document.forms[0].method = "POST"
		document.forms[0].submit()
		}
	</script>
	<script type="text/javascript">
		function CheckForm(formID) { 
		if (formID.TName.value == "") { alert("' . _ADMIN_ADD_EXAM_ENTER_TNAME . '");
		formID.TName.focus(); return false; } 
		if (formID.NOQ.value == "") { alert("' . _ADMIN_ADD_EXAM_ENTER_NOQ . '");
		formID.NOQ.focus(); return false; } 
		if (formID.time.value == "") { alert("' . _ADMIN_ADD_EXAM_ENTER_TIME . '");
		formID.time.focus(); return false; } 

		return true; 
		}
		</script> ');

		echo ('<article id="edit_test">
        <div class="content box">
        <h1 class="title" style="margin-bottom: .2em;">' . _ADMIN_EDIT_EXAM . '</h1>
        <h2 class="title">'. _ADMIN_ADD_EXAM_FIRST_PROPERTIES . '</h2>
		<form action="edit_test" method="post" onSubmit="return CheckForm(this);">

		<div class="label '. $align .'">' . _ADMIN_TITLE . ':</div>
		<input type="text" name="TName" value="'. $rec[1] . '" dir="' . $rtl_input . '">

		<div class="label '. $align .'">'. _ADMIN_NOQ . ':</div>
		<input type="text" name="NOQ" value="'. $rec[2] . '" dir="left">

		<div class="label '. $align .'">' . _ADMIN_EXAM_TIME . ' (' . _ADMIN_TIME_MINUTE . '):</div>
		<input type="text" name="time" value="' . $rec[6] . '" dir="ltr">

		<div class="label '. $align .'">' . _ADMIN_PROF_OR_USER . '</div><ul class="'. $align .'"><li><label for="prof">'
		 . _ADMIN_PROF_OR_USER_1 . '</label><input type="radio" value="1" ' . $prof_checked . ' id="prof" name="Prof_or_User"></li><li><label  for="user">
		' . _ADMIN_PROF_OR_USER_0 . ' </label><input type="radio" ' . $prof_checked2. ' id="user" name="Prof_or_User" value="0"></li></ul>

		<div class="label '. $align .'">' . _ADMIN_BE_DEFAULT
			. '</div>');
		$result_check_default = $db->db_query("SELECT * FROM tests WHERE 'be_default' = '1'");
		$result_check_default_num = $db->rowCount();

		if ($result_check_default_num > 0) {
			$result_check_default = $db->single();
			$is_default = $result_check_default[1];

        echo ('
        <div class="info_box clearfix">
        <div class="box_icon" data-icon="y" aria-hidden="true"></div>
        <div class="content clearfix">' . _ADMIN_THIS_IS_DEFAULT . ' (<b>'
                . $is_default . '</b>) ' . _ADMIN_THIS_IS_DEFAULT_REMAIMED . '</div>
        </div>
        ');
		}

		echo ('
		<ul class="'. $align .'">
        <li><label for="d_y">' . _ADMIN_BE_DEFAULT_1 . '</label>
		<input type="radio" value="1" ' . $default_checked . ' id="d_y" name="Be_Default"></li>

		<li><label  for="d_n">' . _ADMIN_BE_DEFAULT_0 . '</label>
		<input type="radio" ' . $default_checked2 . ' id="d_n" name="Be_Default" value="0"></li></ul>

		<div class="label '. $align .'">' . _ADMIN_RANDOM . '</div>
        <ul class="'. $align .'">
		<li><label for="r_y">' . _ADMIN_RANDOM_1 . '</label>
		<input type="radio" value="1" ' . $random_checked . ' id="r_y" name="random"></li>

		<li><label for="r_n">' . _ADMIN_RANDOM_0 . '</label>
		<input type="radio" ' . $random_checked2 . ' id="r_n" name="random" value="0"></li></ul>

		<div class="label '. $align .'">' . _ADMIN_RTL . '</div>
        <ul class="'. $align .'">
		<li><label for="rtl_y">' . _ADMIN_RTL_1 . '</label>
		<input type="radio" value="1" ' . $rtl_checked . ' id="rtl_y" name="rtl"></li>

		<li><label for="rtl_n">' . _ADMIN_RTL_0 . '</label>
		<input type="radio" ' . $rtl_checked2 . ' id="rtl_n" name="rtl" value="0"></li></ul>

		<div class="label '. $align .'">' . _ADMIN_MINUS_MARK . '</div>
        <ul class="'. $align .'">
		<li><label for="m_y">' . _ADMIN_MINUS_MARK_1 . '</label>
		<input type="radio" value="1" ' . $minus_checked . ' id="m_y" name="Minus_Mark"></li>

		<li><label for="m_n">' . _ADMIN_MINUS_MARK_0 . '</label>
		<input ' . $minus_checked2 . ' type="radio" id="m_n" name="Minus_Mark" value="0"></li></ul>

		<div class="label '. $align .'">' . _ADMIN_SHOW_ANSWERS . '</div>
        <ul class="'. $align .'">
		<li><label for="s_y">' . _ADMIN_MINUS_MARK_1 . '</label>
		<input type="radio" value="1" ' . $show_answers_checked . ' id="s_y" name="Show_Answers"></li>

		<li><label for="s_n">' . _ADMIN_MINUS_MARK_0 . '</label>
		<input ' . $show_answers_checked2 . ' type="radio" id="s_n" name="Show_Answers" value="0"></li></ul>

		<div class="label '. $align .'">' . _ADMIN_SHOW_MARK . '</div>
        <ul class="'. $align .'">
		<li><label for="sm_y">' . _ADMIN_MINUS_MARK_1 . '</label>
		<input type="radio" value="1" ' . $show_mark_checked . ' id="sm_y" name="Show_Mark"></li>
		<li><label for="sm_n">' . _ADMIN_MINUS_MARK_0 . '</label>
		<input ' . $show_mark_checked2 . ' type="radio" id="sm_n" name="Show_Mark" value="0"></li></ul>

		<input type="hidden" name="tid" value="' . $tid . '">
		<input type="hidden" name="end" value="">

		<div class="button_wrap left clearfix">
		<input class="button" type="submit" value="' . _ADMIN_EDIT_EXAM_END . '">
		<input class="button bad" type=button name=bt1 value="' . _ADMIN_FORM_CANCEL . '" onClick="dosubmit()">
		</div>

		</form>
		</div>
        </article>');

	} else if ((isset($_REQUEST["tid"])) && (isset($_REQUEST["end"]))) {
		include('admin_config.php');
		$tid = $_REQUEST["tid"];
		$TName = addslashes($_REQUEST["TName"]);
		$NOQ = $_REQUEST["NOQ"];
		$Be_Default = $_REQUEST["Be_Default"];
		$Prof_or_User = $_REQUEST["Prof_or_User"];
		$random = $_REQUEST["random"];
		$time = $_REQUEST["time"];
		$rtl = $_REQUEST["rtl"];
		$Minus_Mark = $_REQUEST["Minus_Mark"];
		$Show_Answers = $_REQUEST["Show_Answers"];
		$Show_Mark = $_REQUEST["Show_Mark"];

		//Edit test properties
		if ($Be_Default == 1) {
			$sqlstring = "UPDATE tests SET Be_Default='0'";
			$result = $db->db_query($sqlstring);
		}
		$edit_test = "UPDATE tests SET TName=:TName, NOQ=:NOQ, Be_Default=:Be_Default, Prof_or_User=:Prof_or_User, random=:random, time=:time, rtl=:rtl, Minus_Mark=:Minus_Mark, show_answers=:Show_Answers, show_mark=:Show_Mark WHERE id=:tid";
        $pars = array(
            ':TName' => $TName,
            ':NOQ' => $NOQ,
            ':Be_Default' => $Be_Default,
            ':Prof_or_User' => $Prof_or_User,
            ':random' => $random,
            ':time' => $time,
            ':rtl' => $rtl,
            ':Minus_Mark' => $Minus_Mark,
            ':Show_Answers' => $Show_Answers,
            ':Show_Mark' => $Show_Mark,
            ':tid' => $tid
        );
        $edit_test = $db->db_query($edit_test, $pars);

        echo('
            <article id="delete_test">
            <div class="content">
            <div class="info_box clearfix" >
            <div class="box_icon" data-icon="y" aria-hidden="true"></div>
            <div class="content clearfix">
            <h1>' . _ADMIN_EDIT_EXAM . '</h1>
            <ul><li>' . _ADMIN_EXAM_EDITED . '</li></ul>
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