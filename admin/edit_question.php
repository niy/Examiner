<?php

header("Content-Type: text/html; charset=utf-8");

if (!isset($_COOKIE['examiner'])) {
	header('Location: index');
} else {
	include('admin_config.php');

	if (isset($_REQUEST['case'])) {
		$case = $_REQUEST['case'];

		if ($case == "delete") {
			$q_id = $_REQUEST['q_id'];
            $tid = $_REQUEST['t_id'];
            echo ('
            <script language = "javascript">
	          var XMLHttpRequestObject = false;

              if (window.XMLHttpRequest) {
                XMLHttpRequestObject = new XMLHttpRequest();
              } else if (window.ActiveXObject) {
                XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
              }

              function getData(dataSource, divID)
              {
                if(XMLHttpRequestObject) {
                  var obj = document.getElementById(divID);
                  XMLHttpRequestObject.open("GET", dataSource);

                  XMLHttpRequestObject.onreadystatechange = function()
                  {
                    if (XMLHttpRequestObject.readyState == 4 &&
                      XMLHttpRequestObject.status == 200) {
                        obj.innerHTML = XMLHttpRequestObject.responseText;
                    }
                  }

                  XMLHttpRequestObject.send(null);
                }
              }
            </script>
            ');
			$is_in_relation = mysql_query("SELECT * FROM user_choice WHERE q_id = '$q_id'");
			$is_in_relation = mysql_num_rows($is_in_relation);
			if ($is_in_relation < 1) {
                echo ('
                    <article id="delete_q"><div class="content box">
                    <form action="index" method="post">

                    <h1>' . _ADMIN_DELETE_THIS_QUESTION_SURELY . '</h1>
                    ' . _ADMIN_DELETE_THIS_QUESTION_ARE_YOU_SURE . '

                    <div class="button_wrap left clearfix">
                    <input type="button" class="button bad" onclick = "getData(\'delete_q.php?t_id=' . $tid . '&q_id=' . $q_id . '\', \'targetDiv\')" value="' . _ADMIN_DELETE_THIS_QUESTION_SURELY . '">
                    <input class="button" type="button" name="bt1" value="' . _ADMIN_FORM_CANCEL . '" onClick="javascript: window.history.go(-1)">

                    </div>

                    </form>
                    </div>
                    <div id="targetDiv">
                    </div>
                    </article>
                ');
			} else {
                echo ('
                    <article id="delete_test"><div class="content box">
                    <form action="index" method="post">

                    <h1>' . _ADMIN_DELETE_THIS_QUESTION_SURELY . '</h1>
                    ' . _ADMIN_DELETE_THIS_QUESTION_ARE_YOU_SURE_IS_IN_RELATION . '

                    <div class="button_wrap left clearfix">
                    <input type="button" class="button bad" onclick = "getData(\'delete_q.php?q_id=' . $q_id . '&case=1\', \'targetDiv\')" value="' . _ADMIN_DELETE_THIS_QUESTION_SURELY_1 . '">
                    <input type="button" class="button bad" onclick = "getData(\'delete_q.php?q_id=' . $q_id . '&case=2\', \'targetDiv\')" value="' . _ADMIN_DELETE_THIS_QUESTION_SURELY_2 . '">
                    <input class="button" type="button" name="bt1" value="' . _ADMIN_FORM_CANCEL . '" onClick="javascript: window.history.go(-1)">

                    </div>
                    </form>
                    </div>
                    </article>
		        ');
			}
		}
	} else if (isset($_REQUEST['q_id']) && !isset($_REQUEST['question']) && !isset($_REQUEST['case'])) {
		$q_id = $_REQUEST["q_id"];
		$question = mysql_query("SELECT * FROM questions WHERE id = '$q_id'");
		$question = mysql_fetch_row($question);
		$result_rtl = mysql_query("SELECT * FROM tests WHERE id = '$question[1]'", $db);
		$rtl_array = mysql_fetch_row($result_rtl);

		if ($rtl_array[7] == 1) {
			$align = "right";
			$rtl_input = "rtl";
		} else {
			$align = "left";
			$rtl_input = "ltr";
		}
		$checked1 = $checked2 = $checked3 = $checked4 = "";

		switch ($question[7]) {
			case 1:
				$checked1 = "checked";
				break;

			case 2:
				$checked2 = "checked";
				break;

			case 3:
				$checked3 = "checked";
				break;

			case 4:
				$checked4 = "checked";
				break;
		}
		$q_counter = 1;

		echo ('<article id="add_question">
		<form action="' . $_SERVER["PHP_SELF"] . '" method="post" onSubmit="return CheckForm(this);">
		<input type="hidden" name="q_id" value="' . $q_id . '">
		<input type="hidden" name="tid" value="' . $question[1] . '">


		<div class="button_wrap left clearfix" style="margin-bottom:1em;"><h1>' . _ADMIN_EDIT_QUESTIONS . '</h1>
		<input class="button bad" type=button name=bt1 value="' . _ADMIN_FORM_CANCEL . '" onClick="javascript: window.history.go(-1)">
		</div>
		<div class="content box">
		<label for="question"><div class="label ' . $align . '">' . _ADMIN_ADD_Q_Q . '</div></label>
		<textarea id="question" dir="' . $rtl_input . '" name="question" style="width: 100%; height: 10em">' . $question[2] . '</textarea>
		<div class="label ' . $align . '"><a href="javascript:setup();">' . _ADMIN_ADD_EXAM_ENABLE_ALL_EDITOR . '</a></div>

		<input ' . $checked1 . ' type="radio" value="1" id="a1" name="answer">
		<label for="a1">' . _ADMIN_ADD_CHOICE1 . '</label>
		<textarea id="choice1" dir="' . $rtl_input . '" name="choice1" style="width: 100%; height: 7em">' . $question[3] . '</textarea>

		<input ' . $checked2 . ' type="radio" value="2" id="a2" name="answer">
		<label for="a2">' . _ADMIN_ADD_CHOICE2 . '</label>
		<textarea id="elm2" dir="' . $rtl_input . '" name="choice2" style="width: 100%; height: 7em">' . $question[4] . '</textarea>

		<input ' . $checked3 . ' type="radio" value="3" id="a3" name="answer">
		<label for="a3">' . _ADMIN_ADD_CHOICE3 . '</label>
		<textarea id="elm3" dir="' . $rtl_input . '" name="choice3" style="width: 100%; height: 7em">' . $question[5] . '</textarea>

		<input ' . $checked4 . ' type="radio" value="4" id="a4" name="answer">
		<label for="a4">' . _ADMIN_ADD_CHOICE4 . '</label>
		<textarea id="elm4" dir="' . $rtl_input . '" name="choice4" style="width: 100%; height: 7em">' . $question[6] . '</textarea>

		<div class="button_wrap left clearfix">
		<input class="button good" type="submit" value="' . _ADMIN_EDIT_Q_FINISH . '" />
		</div>
		 </div>
		 </form>
		</article>');
	} else if (isset($_REQUEST['q_id']) && isset($_REQUEST['question'])) {
		//UPDATE Question
		$q_id = $_REQUEST["q_id"];
		$question = addslashes($_REQUEST["question"]);
		$choice1 = addslashes($_REQUEST["choice1"]);
		$choice2 = addslashes($_REQUEST["choice2"]);
		$choice3 = addslashes($_REQUEST["choice3"]);
		$choice4 = addslashes($_REQUEST["choice4"]);
		$answer = $_REQUEST["answer"];
		$tid = $_REQUEST["tid"];
		$sqlstring =
			"UPDATE questions SET question='$question', choice1='$choice1', choice2='$choice2', choice3='$choice3', choice4='$choice4', answer='$answer' WHERE id=$q_id";
		$result = mysql_query($sqlstring, $db);

		if (!$result) {
			die('Database query error:' . mysql_error());
		}
		die('
			<article class="msg">

			<div class="info_box clearfix" >
			<div class="box_icon" data-icon="y" aria-hidden="true"></div>
			<div class="content clearfix">
			<h1>' . _ADMIN_EDIT_QUESTIONS . '</h1>
			<ul><li>' . _ADMIN_QUESTION_EDITED . '</li></ul>
			</div>
			</div>
			<div id="back" class="button_wrap clearfix">
			<a class="button" id="back_b" href="questions?tid=' . $tid . '"><div data-icon="b" aria-hidden="true" class="grid_img"></div><div class="grid_txt">' . _ADMIN_EDIT_QUESTIONS . '</div></a>
			</div>

			</article><footer><p>&copy; Copyright 2013 Mohammad Ali Karimi. All rights reserved.</p></footer></div></body></html>
		');
	} else {
		die('
		<article class="msg">

		<div class="info_box clearfix" >
			<div class="box_icon" data-icon="y" aria-hidden="true"></div>
			<div class="content clearfix">' . _ADMIN_NOT_ALLOWED . '!</div>
		</div>

		<div id="back" class="button_wrap clearfix">
			<a class="button" id="back_b" href="../admin"><div data-icon="h" aria-hidden="true" class="grid_img"></div>
			<div class="grid_txt">' . _ADMIN_HOME . '</div></a>
		</div>

		</article>
		');
	}
    echo ('<script language="JavaScript">
		<!--
		function CheckForm(formID) {
		if (formID.question.value == "") { alert("' . _ADD_Q_ALTER_FILL . '");
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
		//-->
		</script> ');

    echo ('<!-- TinyMCE -->
<script type="text/javascript" src="../tinymce/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="../tinymce/js/tinymce/jquery.tinymce.min.js"></script>
<!-- /TinyMCE -->');
}
?>
<?php include('../footer.php');?>