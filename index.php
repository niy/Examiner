<?php

include('config.php');

$db = mysql_connect(_DBHOST, _DBUSER, _DBPASS);
mysql_select_db(_DBNAME, $db);
$check_default = mysql_query("SELECT * FROM tests WHERE  be_default = '1'");

if (!$check_default) {
	include('main.php');
	die();
} else {
	if ($default_n = mysql_num_rows($check_default) < 1) {
		include('main.php');
		echo('
		<article class="msg">
		<div class="content">

		<div class="error_box clearfix" >
			<div class="box_icon" data-icon="w" aria-hidden="true"></div>
			<div class="content clearfix">' . _NO_DEFAULT . '</div>
		</div>

		</div>
		</article>');
        include ('footer1.php');
        include('footer_end.php');
        die();
	}
	$check_default = mysql_fetch_row($check_default);
	if (isset($_REQUEST["uname"])) {
		$uname = $_REQUEST["uname"];
		$pass = $_REQUEST["pass"];

        if ($uname=="test" && $pass=="test") {

            if (_DEBUG=="on"){
                $uid = 1;
                session_start();
                $_SESSION['examiner_user'] = 'test';
                header('Location: index');
            } else {
                header('Location: index?wrong');
            }
        }
		$check_security = mysql_query("SELECT * FROM users WHERE userid='$uname' AND password='$pass'", $db);

		if ($check_security = mysql_fetch_row($check_security)) {
			$check_hold =
				mysql_query(
					"SELECT * FROM user_test WHERE user_id='$check_security[0]' AND test_id='$check_default[0]'",
					$db);

			if ($check_hold = mysql_fetch_row($check_hold)) {
				session_start();
				$_SESSION['examiner_user'] = $uname;
				include('main.php');
				echo('
				<article class="msg">

				<div class="error_box clearfix" >
					<div class="box_icon" data-icon="w" aria-hidden="true"></div>
					<div class="content clearfix">' . _EXAM_SESSION_HAVE_HELD1 . ' ' . $_SESSION['examiner_user'] . ' ' . _EXAM_SESSION_HAVE_HELD2 . '</div>
				</div>
				<div id="back" class="button_wrap clearfix">
					<a id="back_b" class="button" href="result"><div data-icon="c" aria-hidden="true" class="grid_img"></div>
					<div class="grid_txt">' . _EXAM_SHOW_RESULT . '</div></a>
				</div>

				</article>');
                include ('footer1.php');
                include('footer_end.php');
                die();
			}

			if ($check_security[1] == "" || $check_security[2] == "" || $check_security[3] == "") {
				if (!(isset($_REQUEST["ufname"]))) { //edit properties of user
					include('main.php');

					echo ('
                            <article id="add_user">
                            <div class="content box">
                            <h1>' . _EXAM_COMPLETE_CHARACTERISTICS . '</h1>
                            <form action="index" method="post" onSubmit="return CheckForm(this);">

                            <div class="label '. $align .'">' . _ADMIN_ADD_USER_USER_ID . ':</div>
                            <input type="text" disabled="disabled" name="uname" value="' . $uname . '" dir="ltr">

                            <div class="label '. $align .'">' . _ADMIN_ADD_USER_NAME . ':</div>
                            <input type="text" name="ufname" value="' . $check_security[1] . '" dir="' . $rtl_input . '">

                            <div class="label '. $align .'">' . _ADMIN_ADD_USER_LAST_NAME . ':</div>
                            <input type="text" name="ulname" value="' . $check_security[2] . '" dir="' . $rtl_input . '">

                            <div class="label '. $align .'">' . _ADMIN_ADD_USER_FATHER_NAME . '</div>
                            <input type="text" name="fname" value="' . $check_security[3] . '" dir="ltr">

                            <div class="label '. $align .'">' . _ADMIN_ADD_USER_EMAIL . '</div>
                            <input type="text" name="email" value="' . $check_security[6] . '" dir="ltr">

                            <input type="hidden" name="uid" value="' . $check_security[0] . '">
						    <input type="hidden" name="pass" value="' . $pass . '">
							<input type="hidden" name="uname" value="' . $uname . '">

                            <div class="button_wrap left clearfix">
                            <input class="button" type="submit" value="' . _ADMIN_EDIT_USER_END . '">
                            <input class="button bad" type=button name=bt1 value="' . _ADMIN_FORM_CANCEL . '" onClick="dosubmit()">
                            </div>

                            </form>
                            </div>
                            </article>
							');
				} else { //register the user
					$uid = $_REQUEST["uid"];
					$ufname = $_REQUEST["ufname"];
					$ulname = $_REQUEST["ulname"];
					$fname = $_REQUEST["fname"];
					$email = $_REQUEST["email"];
					header('Location: index');
					session_start();
					$_SESSION['examiner_user'] = $uname;
					$sqlstring =
						"UPDATE users SET FName='$ufname', LName='$ulname', fatherName='$fname', email= '$email' WHERE id='$uid'";
					$result = mysql_query($sqlstring, $db);

					if (!$result) {
						die('Database query error:' . mysql_error());
					}
				}
			} else { //let the user take the test
				header('Location: index');
				$uid = $check_security[0];
				session_start();
				$_SESSION['examiner_user'] = $uname;
			}
		} else { //username or password wrong!
            $check_admin = mysql_query("SELECT * FROM settings WHERE admin_id='$uname' AND password='$pass'", $db);
            if (mysql_fetch_row($check_admin)) {
                setcookie('examiner', 'examiner', time() + 36000, '/');
                header('Location: admin');
            } else
                header('Location: index?wrong');
        }

	} else { //login
		session_start();
		include('main.php');

		if (!isset($_SESSION['examiner_user'])) {
			echo ('<script language="JavaScript">
			function CheckForm(formID) { 
			if (formID.uname.value == "") { alert("' . _ADMIN_ENTER_USERNAME . '"); 
			formID.uname.focus(); return false; } 
			if (formID.pass.value == "") { alert("' . _ADMIN_ENTER_PASSWORD . '"); 
			formID.pass.focus(); return false; } 		
			return true; 
			}
			</script> ');

			if ($check_default[4] == 0) {
				$EXAM_USER_REGISTER = _EXAM_USER_REGISTER_BY_USER;
				$EXAM_USER_REGISTER2 = _EXAM_USER_REGISTER_BY_USER2;
			} else {
				$EXAM_USER_REGISTER = _EXAM_USER_REGISTER_BY_PROF;
				$EXAM_USER_REGISTER2 = _EXAM_USER_REGISTER_BY_PROF2;
			}

			echo ('
			<article class="msg">
                    <div class="info_box clearfix" style="max-width:30em;">
                        <div class="box_icon" data-icon="y" aria-hidden="true"></div>
                        <div class="content clearfix">
                        <h1>' . _EXAM_THIS_EXAM . '<b>' . $check_default[1]
                            . '</b>' . _EXAM_DEFINED_AS_DEFAULT . '</h1><ul><li>' . _EXAM_PROF_DEFINED . '' . $EXAM_USER_REGISTER . '</li><li>'
                            . _EXAM_PROF_DEFINED2 . ' ' . $EXAM_USER_REGISTER2 . '</li></ul>
                        </div>
                    </div>

                <div class="login box">
                    <div class="content">
	                    <h1 class="title">' . _EXAM_SYSTEM_ALERT . '</h1>
	                    ');
            if (isset($_REQUEST['wrong'])) {
                echo ('<div class="error"><span data-icon="w" aria-hidden="true"></span> ' . _ADMIN_SYSTEM_ALERT_WRONG_PREVIOUS_U_P . '</div>');
            }
            echo ('<form method="POST" action="index" onSubmit="return CheckForm(this);">
	                        <div class="label '. $align .'">' . _ADMIN_USERNAME . ':</div>
	                        <input type="text" name="uname" dir="ltr">

	                        <div class="label '. $align .'">' . _ADMIN_PASSWORD . ':</div>
	                        <input type="password" name="pass" dir="ltr">

                            <div class="button_wrap left clearfix">
                                <input class="button good" type="submit" value="' . _ADMIN_ENTER . '" name="B1">

		    ');

			if ($check_default[4] == 0) {
				echo ('
				<input type="hidden" name="reg">
				<script language=javascript>
                function dosubmit() {
                document.forms[0].action = "register"
                document.forms[0].method = "post"
                document.forms[0].submit()
                }</script>
                <input class="button bad blue" type="button" name="bt1" value="' . _EXAM_USER_REGISTER_BY_USER_REGISTER . '" onClick="dosubmit()">
                ');
			}

			echo ('
	        </div>
            </form>
            </div>
            </div>
            </article>');
		} else {
			$uid_session = $_SESSION['examiner_user'];
            if (!($uid_session=="test") || _DEBUG=="off"){
                $uid_session = mysql_query("SELECT * FROM users WHERE userid = '$uid_session'", $db);
                $uid_session = mysql_fetch_row($uid_session);

                $check_hold =
                    mysql_query("SELECT * FROM user_test WHERE user_id='$uid_session[0]' AND test_id='$check_default[0]'",
                        $db);

                if ($check_hold = mysql_fetch_row($check_hold)) {
                    echo('
                    <article class="msg">

                    <div class="error_box clearfix" >
                        <div class="box_icon" data-icon="w" aria-hidden="true"></div>
                        <div class="content clearfix">' . _EXAM_SESSION_HAVE_HELD1 . ' ' . $_SESSION['examiner_user'] . ' ' . _EXAM_SESSION_HAVE_HELD2 . '</div>
                    </div>
                    <div id="back" class="button_wrap clearfix">
                        <a id="back_b" class="button" href="result"><div data-icon="c" aria-hidden="true" class="grid_img"></div>
                        <div class="grid_txt">' . _EXAM_SHOW_RESULT . '</div></a>
                    </div>

                    </article>');
                    include ('footer1.php');
                    include('footer_end.php');
                    die();
                }
            }
			if ($check_default[5] == 1){
				$is_random = _EXAM_RANDOM_1;
                $rand_ico = '3';
            }
			else{
				$is_random = _EXAM_RANDOM_0;
                $rand_ico = 'o';
            }
			if ($check_default[8] == 1){
				$nagative_mark = _EXAM_NEGATIVE_1;
                $neg_ico = '1';
                $neg_ico_class = 'incorrect_sign';
                $neg_class = 'incorrect_q';
            }
			else{
				$nagative_mark = _EXAM_NEGATIVE_0;
                $neg_ico = '2';
                $neg_ico_class = '';
                $neg_class = '';
            }
			$test_noq = mysql_query("SELECT * FROM questions WHERE test_id='$check_default[0]'", $db);
			$test_noq = mysql_num_rows($test_noq);

			if ($test_noq > $check_default[2])
				$test_noq = $check_default[2];

			echo ('
			        <script  language="javascript"  type="text/javascript">
                    function disableForm(theform) {
                    if (document.all || document.getElementById) {
                    for (i = 0; i < theform.length; i++) {
                    var tempobj = theform.elements[i];
                    if (tempobj.type.toLowerCase() == "submit" || tempobj.type.toLowerCase() == "reset")
                    tempobj.disabled = true;
                    }
                    return true;
                    }
                    else {
                    return false;
                       }
                    }
                    </script>
                    <article id="exam_prop">
                    <div class="box">
                    <div class="box_title"><h2 class="content"> ' . $check_default[1] . '</h2></div>
                    <div class="content">
                    <form autocomplete="off" method="POST" action="test" onSubmit="return disableForm(this);">
                    <table><tbody>
                    <tr><td class="icon"><span data-icon="#" aria-hidden="true" title="' . _EXAM_NUMBER_OF_Q . '"></span></td><td>' . $test_noq . '</td></tr>
                    <tr><td class="icon"><span data-icon="4" aria-hidden="true" title="' . _EXAM_EXAM_TIME . '"></span></td><td>' . $check_default[6] . ' ' . _EXAM_MINUTES . '</td></tr>
                    <tr><td class="icon"><span data-icon="' . $rand_ico . '" aria-hidden="true"></span></td><td>' . $is_random . '</td></tr>
                    <tr><td class="icon '.$neg_ico_class.'"><span data-icon="' . $neg_ico . '" aria-hidden="true"></span></td><td class="'.$neg_class.'">' . $nagative_mark . '</td></tr>
                    </tbody></table>

                    <div id="rules" class="box">
                    <div class="box_title"><h2 class="content"> ' . _EXAM_NECESSARY_EXPLANATIONS . '</h2></div>
                    <div class="content">
                    ' . _EXAM_REST_OF_EXPLANATIONS . '
                    </div>
                    </div>
                    <input type="hidden" name="agree">
                    <input type="hidden" name="reg">
                    <script language=javascript>
                        function dosubmit() {
                        document.forms[0].action = "logout"
                        document.forms[0].method = "post"
                        document.forms[0].submit()
                    }</script>
                    <input type="hidden" name="run">
                    <div class="button_wrap left clearfix">
                    <input class="button" type="submit" value="' . _EXAM_START_EXAM . '" name="B1">
                    <input class="button incorrect bad" type="button" name="bt1" value="' . _EXAM_CANCEL_EXAM . '" onClick="dosubmit()">
                    </div>
                    </form>
                    </div>
                    </div>
                    </article>
                    ');
		}
	}
}
?>

<?php
include('footer1.php');
include('footer_end.php');
?>