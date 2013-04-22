<?php

header("Content-Type: text/html; charset=utf-8");

if (!isset($_COOKIE['examiner'])) {
	header('Location: index');
} else {
	include('admin_config.php');

	$result_rtl = mysql_query("SELECT * FROM settings WHERE id = '1'", $db);
	$rtl_array = mysql_fetch_row($result_rtl);

	if ($rtl_array[4] == 1) {
		$align = "right";
		$rtl_input = "rtl";
	} else {
		$align = "left";
		$rtl_input = "ltr";
	}
	$TName = $NOQ = $Be_Default = $Prof_or_User = $random = $time = $rtl = $Minus_Mark = "";

	//////////////////////////case 1: Add First Question
	if (isset($_REQUEST["case1"])) {
		$case = "first_q";
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
		$q_counter = 1;

		echo ('<article id="add_question">
		<form action="' . $_SERVER["PHP_SELF"] . '" method="post" onSubmit="return CheckForm(this);">
		<input type="hidden" name="TName" value="' . $TName . '">
		<input type="hidden" name="NOQ" value="' . $NOQ . '">
		<input type="hidden" name="time" value="' . $time . '">
		<input type="hidden" name="Prof_or_User" value="' . $Prof_or_User . '">
		<input type="hidden" name="Be_Default" value="' . $Be_Default . '">
		<input type="hidden" name="random" value="' . $random . '">
		<input type="hidden" name="rtl" value="' . $rtl . '">
		<input type="hidden" name="Minus_Mark" value="' . $Minus_Mark . '">
		<input type="hidden" name="Show_Answers" value="' . $Show_Answers . '">
		<input type="hidden" name="Show_Mark" value="' . $Show_Mark . '">
		<input type="hidden" name="case2" value="">');

		echo ('
		<div class="button_wrap left clearfix" style="margin-bottom:1em;"><h1>' . _ADD_Q_COUNTER . ': ' . $q_counter . '</h1></div>
		');
		include('q_a.php');

		echo ('</form>');
	} else if (isset($_REQUEST["case"])) {
		$tid = $_REQUEST["tid"];
		$case = $_REQUEST["case"];
        echo ('<article id="add_question">');
		if ($case == "add") {
			$question = addslashes($_REQUEST["question"]);
			$choice1 = addslashes($_REQUEST["choice1"]);
			$choice2 = addslashes($_REQUEST["choice2"]);
			$choice3 = addslashes($_REQUEST["choice3"]);
			$choice4 = addslashes($_REQUEST["choice4"]);
			$answer = $_REQUEST["answer"];
			$sqlstring =
				"INSERT INTO questions (test_id, question, choice1, choice2, choice3, choice4, answer) VALUES ('$tid', '$question', '$choice1', '$choice2', '$choice3', '$choice4', '$answer')";
			$result = mysql_query($sqlstring, $db);

			if (!$result) {
				die('Database query error:' . mysql_error());
			}

			echo ('
			<div class="info_box clearfix" style="width:100%;">
		    <div class="box_icon" data-icon="y" aria-hidden="true"></div>
		    <div class="content clearfix">' . _ADD_NEW_Q_THIS_Q_ADDED . '.</div>
		    </div>
			');
        }

		echo ('
		<form action="' . $_SERVER["PHP_SELF"] . '" method="post" onSubmit="return CheckForm(this);">
		<input type="hidden" name="case" value="add"><input type="hidden" name="tid" value="' . $tid . '">');

		include('q_a.php');

		echo ('</form>');
	} //////////////////////////case 2: Add Second Question + Insert into table tests
	else if (isset($_REQUEST["case2"])) {
		$case = "second_q";
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
		$question = addslashes($_REQUEST["question"]);
		$choice1 = addslashes($_REQUEST["choice1"]);
		$choice2 = addslashes($_REQUEST["choice2"]);
		$choice3 = addslashes($_REQUEST["choice3"]);
		$choice4 = addslashes($_REQUEST["choice4"]);
		$answer = $_REQUEST["answer"];

		//Add test properties
		if ($Be_Default == 1) {
			$sqlstring = "UPDATE tests SET Be_Default='0'";
			$result = mysql_query($sqlstring, $db);

			if (!$result) {
				die('Database query error:' . mysql_error());
			}
		}
		$sqlstring =
			"INSERT INTO tests (TName, NOQ, Be_Default, Prof_or_User, random, time, rtl, Minus_Mark, show_answers, show_mark) VALUES ('$TName', '$NOQ', '$Be_Default', '$Prof_or_User', '$random', '$time', '$rtl', '$Minus_Mark', '$Show_Answers','$Show_Mark')";
		$result = mysql_query($sqlstring, $db);

		if (!$result) {
			die('Database query error:' . mysql_error());
		}
		//Add first Question
		$sqlstring =
			"INSERT INTO questions (test_id, question, choice1, choice2, choice3, choice4, answer) VALUES (LAST_INSERT_ID(), '$question', '$choice1', '$choice2', '$choice3', '$choice4', '$answer')";
		$result = mysql_query($sqlstring, $db);

		if (!$result) {
			die('Database query error:' . mysql_error());
		}
		$q_counter = mysql_query("SELECT MAX(ID) AS LAST FROM tests");
		$q_counter = mysql_fetch_array($q_counter);
		$q_counter = mysql_query("SELECT * FROM questions WHERE `test_id` = $q_counter[LAST]", $db);
		$q_counter = mysql_num_rows($q_counter) + 1;

		echo ('
		<article id="add_question">
		<div class="info_box clearfix" style="width:21em;">
		<div class="box_icon" data-icon="y" aria-hidden="true"></div>
		<div class="content clearfix">' . _FIRST_EXAM_ADDED . '.<br>' . FIRST_Q_ADDED . '.</div>
		</div>
		<form method="POST" action="all_tests">
		<div class="button_wrap left clearfix" style="margin-bottom:1em;"><h1>' . _ADD_Q_COUNTER . ': ' . $q_counter . '</h1>
		<input class="button" type="submit" value="' . _ADMIN_Q_FINISH . '"></div>
		</form>
		<form action="' . $_SERVER["PHP_SELF"] . '" method="post" onSubmit="return CheckForm(this);">
		<input type="hidden" name="case3" value="">');
		include('q_a.php');

		echo ('</form>');
	} //////////////////////////case 3: Add Second Question + Insert into table qestion
	else if (isset($_REQUEST["case3"])) {
		$question = addslashes($_REQUEST["question"]);
		$choice1 = addslashes($_REQUEST["choice1"]);
		$choice2 = addslashes($_REQUEST["choice2"]);
		$choice3 = addslashes($_REQUEST["choice3"]);
		$choice4 = addslashes($_REQUEST["choice4"]);
		$answer = $_REQUEST["answer"];
		//Add next Questions
		$last_test_id = mysql_query("SELECT MAX(ID) AS LAST_ID FROM tests");
		$last_test_id = mysql_fetch_array($last_test_id);
		$sqlstring =
			"INSERT INTO questions (test_id, question, choice1, choice2, choice3, choice4, answer) VALUES ($last_test_id[LAST_ID], '$question', '$choice1', '$choice2', '$choice3', '$choice4', '$answer')";
		$result = mysql_query($sqlstring, $db);

		if (!$result) {
			die('Database query error:' . mysql_error());
		}
		$q_counter = mysql_query("SELECT MAX(ID) AS LAST FROM tests");
		$q_counter = mysql_fetch_array($q_counter);
		$q_counter = mysql_query("SELECT * FROM questions WHERE `test_id` = $q_counter[LAST]", $db);
		$q_counter = mysql_num_rows($q_counter) + 1;

		echo ('<article id="add_question">
		<div class="info_box clearfix" style="width:21em;">
		<div class="box_icon" data-icon="y" aria-hidden="true"></div>
		<div class="content clearfix">' . _NEXT_EXAM_ADDED . '.</div>
		</div>
		<form method="POST" action="all_tests">
		<div class="button_wrap left clearfix" style="margin-bottom:1em;"><h1>' . _ADD_Q_COUNTER . ': ' . $q_counter . '</h1>
		<input class="button" type="submit" value="' . _ADMIN_Q_FINISH . '"></div>
		</form>
		<form action="' . $_SERVER["PHP_SELF"] . '" method="post" onSubmit="return CheckForm(this);">
		<input type="hidden" name="case3" value="">
		<input type="hidden" name="q_counter" value="' . $q_counter . '">');
		include('q_a.php');

		echo ('</form>');
	} else {
        die('
        <article class="msg">
        <div class="content">

		<div class="info_box clearfix" style="width:21em;">
            <div class="box_icon" data-icon="y" aria-hidden="true"></div>
            <div class="content clearfix">' . _ADMIN_NOT_ALLOWED . '!</div>
		</div>

		<div id="back" class="button_wrap">
            <a class="button" id="back_b" href="../admin"><div data-icon="h" aria-hidden="true" class="grid_img"></div>
            <div class="grid_txt">' . _ADMIN_HOME . '</div></a>
		</div>

		</div>
        </article>
		');
	}
    echo ('
	<script language="javascript">
		function dosubmit() {
			document.forms[0].action = "all_tests"
			document.forms[0].method = "POST"
			document.forms[0].submit()
		}
	</script>
	<script language="JavaScript">
		function CheckForm(formID) {
			if (formID.question.value == "") { alert("'
        . _ADD_Q_ALTER_FILL . '");
			formID.question.focus(); return false; }
			if (formID.choice1.value == "") { alert("' . _ADD_Q_ALTER_FILL . '");
			formID.choice1.focus(); return false; }
			if (formID.choice2.value == "") { alert("' . _ADD_Q_ALTER_FILL . '");
			formID.choice2.focus(); return false; }
			if (formID.choice3.value == "") { alert("' . _ADD_Q_ALTER_FILL . '");
			formID.choice3.focus(); return false; }
			if (formID.choice4.value == "") { alert("' . _ADD_Q_ALTER_FILL . '");
			formID.choice4.focus(); return false; }

			var fieldRequired = Array("answer");
			var fieldDescription = Array("answer");
			var alertMsg = "'
        . _ADMIN_ADD_Q_CHOOSE_ANSWER
        . '";

			var l_Msg = alertMsg.length;

			for (var i = 0; i < fieldRequired.length; i++){
			var obj = formID.elements[fieldRequired[i]];
			if (obj){
				if (obj.type == null){
					var blnchecked = false;
					for (var j = 0; j < obj.length; j++){
						if (obj[j].checked){
							blnchecked = true;
						}
					}
					if (!blnchecked){
						alertMsg += " ";
					}
					continue;
				}

				switch(obj.type){
				case "select-one":
					if (obj.selectedIndex == -1 || obj.options[obj.selectedIndex].text == ""){
						alertMsg += " ";
					}
					break;
				default:
				}
			}
			}

			if (alertMsg.length == l_Msg){
			return true;
			}else{
			alert(alertMsg);
			return false;
			}
			return true;
		}
	</script> ');

    echo ('<!-- TinyMCE -->
	<script type="text/javascript" src="../tinymce/js/tinymce/tinymce.min.js"></script>
	<script type="text/javascript" src="../tinymce/js/tinymce/jquery.tinymce.min.js"></script>
<!-- /TinyMCE -->');
}
?>

<?php include('../footer.php');?>