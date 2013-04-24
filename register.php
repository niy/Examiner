<?php

session_start();

include('config.php');
$db = mysql_connect(_DBHOST, _DBUSER, _DBPASS);
mysql_select_db(_DBNAME, $db);
$check_default = mysql_query("SELECT * FROM tests WHERE be_default = '1'", $db);
$check_default_reg = mysql_fetch_row($check_default);

if (!($check_default_reg[4] == 0)) {
	include('main.php');
	die('<article id="add_question"><div class="content">
		<div class="info_box clearfix" style="width:21em;">
		<div class="box_icon" data-icon="y" aria-hidden="true"></div>
		<div class="content clearfix">' . _ADMIN_NOT_ALLOWED . '!</div>
		</div>
		</div>
		</article>
		');
}

if (!isset($_SESSION['examiner_user'])) {
	if (!(isset($_REQUEST["reg"])) && isset($_REQUEST["step2"])) {
		if (!(isset($_REQUEST["uid"])) | !(isset($_REQUEST["password"])))
			die();
		$uname = $_REQUEST["uname"];
		$ulname = $_REQUEST["ulname"];
		$fname = $_REQUEST["fname"];
		$uid = $_REQUEST["uid"];
		$password = $_REQUEST["password"];
		$email = $_REQUEST["email"];
		$check_uname = mysql_query("SELECT * FROM users WHERE userid='$uid'", $db);

		if ($rec = mysql_fetch_row($check_uname)) {
			include('main.php');

			echo ('
					<SCRIPT LANGUAGE=JAVASCRIPT>
					function dosubmit() {
					document.forms[0].action = "logout"
					document.forms[0].method = "POST"
					document.forms[0].submit()
					}</SCRIPT><script language="JavaScript">
					function CheckForm(formID) {
					if (formID.uname.value == "") { alert("' . _EXAM_REGISTER_ENTER_NAME . '");
					formID.uname.focus(); return false; }
					if (formID.ulname.value == "") { alert("' . _EXAM_REGISTER_ENTER_LAST_NAME . '");
					formID.ulname.focus(); return false; }
					if (formID.fname.value == "") { alert("' . _EXAM_REGISTER_ENTER_FATHER_NAME . '");
					formID.fname.focus(); return false; }
					if (formID.uid.value == "") { alert("' . _ADMIN_ADD_USER_ENTER_ID . '");
					formID.uid.focus(); return false; }
					if (formID.password.value == "") { alert("' . _ADMIN_ADD_USER_ENTER_PASSWORD . '");
					formID.password.focus(); return false; }
					if (formID.password_confirm.value == "") { alert("' . _ADMIN_ADD_USER_ENTER_CONFIRM_PASSWORD . '");
					formID.password_confirm.focus(); return false; }
					if (formID.password_confirm.value !== formID.password.value) { alert("' . _ADMIN_ADD_USER_PASSWORD_AND_CONFIRM_NOT_MATCH . '");
					formID.password.focus(); return false; }
					return true;
					}
					</script> ');

			echo ('
					<article id="add_user">
					<div class="content box">
					<h1>' . _ADMIN_ADD_USER . '</h1>
					<div class="error_box clearfix">
					<div class="box_icon" data-icon="w" aria-hidden="true"></div>
					<div class="content clearfix">' . _ADMIN_ADD_USER_WRONG_USER_ID1 . ' ' . $uid . ' ' . _ADMIN_ADD_USER_WRONG_USER_ID2 . '</div>
					</div>

					<form action="register?step2=0" method="post" onSubmit="return CheckForm(this);">

					<div class="label ' . $align . '">' . _ADMIN_ADD_USER_NAME . ':</div>
					<input type="text" name="uname" value="' . $uname . '" dir="ltr">

					<div class="label ' . $align . '">' . _ADMIN_ADD_USER_LAST_NAME . ':</div>
					<input type="text" name="ulname" value="' . $ulname . '" dir="' . $rtl_input . '">

					<div class="label ' . $align . '">' . _ADMIN_ADD_USER_FATHER_NAME . ':</div>
					<input type="text" name="fname" value="' . $fname . '" dir="' . $rtl_input . '">

					<div class="label ' . $align . '">' . _ADMIN_ADD_USER_USER_ID . '</div>
					<input type="text" name="uid" value="" autofocus dir="ltr">

					<div class="label ' . $align . '">' . _ADMIN_ADD_USER_PASSWORD . '</div>
					<input type="password" name="password" value="' . $password . '" dir="ltr">

					<div class="label ' . $align . '">' . _ADMIN_ADD_USER_CONFIRM_PASSWORD . '</div>
					<input type="password" name="password_confirm" value="' . $password . '" dir="ltr">

					<div class="label ' . $align . '">' . _ADMIN_ADD_USER_EMAIL . '</div>
					<input type="text" name="email" value="' . $email . '" dir="ltr">

                    <input type="hidden" name="end" value="">

					<div class="button_wrap left clearfix">
					<input class="button" type="submit" value="' . _ADMIN_ADD_USER_END . '">
					<input class="button bad" type=button name=bt1 value="' . _ADMIN_FORM_CANCEL . '" onClick="dosubmit()">
					</div>

					</form>
					</div>
					</article>
				');

		} else {
			$sqlstring =
				"INSERT INTO users (FName, LName, fatherName, userid, password, email) VALUES ('$uname', '$ulname', '$fname', '$uid', '$password', '$email')";
			$result = mysql_query($sqlstring, $db);

			if (!$result) {
				die('Database query error:' . mysql_error());
			}
			$_SESSION['examiner_user'] = $uid;
			header("Refresh: 5; url=index.php");
			include('main.php');
            die('
				    <article class="msg">
                    <div class="content">

                    <div class="info_box clearfix" style="width:29em; height: 10em;">
                        <div class="box_icon" data-icon="y" aria-hidden="true"></div>
                        <div class="content clearfix">
                            <h1>' . _ADMIN_ADD_USER . '</h1>
                            ' . _ADMIN_SYSTEM_ADDED_USER . ':
                            <ul>
                                <li>' . _ADMIN_ADD_USER_NAME_AND_LNAME . ': ' . $uname . ' ' . $ulname . '</li>
                                <li>' . _ADMIN_ADD_USER_FATHER_NAME . ': ' . $fname . '</li>
                                <li>' . _ADMIN_ADD_USER_USER_ID . ': ' . $uid . '</li>
                                <li>' . _ADMIN_ADD_USER_EMAIL . ': ' . $email . '</li>
                            </ul>
                        </div>
                    </div>

                    <div id="wait"><div class="content box">
                    <div class="content wait"><div data-icon="9" aria-hidden="true" class="grid_img"></div>
                    <div class="content grid_txt">' . _EXAM_WAIT_5_MINUTES . '</div></div>
                    </div>
                    </div>

                    </div>
                    </article>
				');

		}
	} else if (isset($_REQUEST["reg"]) && !(isset($_REQUEST["step2"]))) {

		include('main.php');
        echo ('
					<SCRIPT LANGUAGE=JAVASCRIPT>
					function dosubmit() {
					document.forms[0].action = "logout"
					document.forms[0].method = "POST"
					document.forms[0].submit()
					}</SCRIPT><script language="JavaScript">
					function CheckForm(formID) {
					if (formID.uname.value == "") { alert("' . _EXAM_REGISTER_ENTER_NAME . '");
					formID.uname.focus(); return false; }
					if (formID.ulname.value == "") { alert("' . _EXAM_REGISTER_ENTER_LAST_NAME . '");
					formID.ulname.focus(); return false; }
					if (formID.fname.value == "") { alert("' . _EXAM_REGISTER_ENTER_FATHER_NAME . '");
					formID.fname.focus(); return false; }
					if (formID.uid.value == "") { alert("' . _ADMIN_ADD_USER_ENTER_ID . '");
					formID.uid.focus(); return false; }
					if (formID.password.value == "") { alert("' . _ADMIN_ADD_USER_ENTER_PASSWORD . '");
					formID.password.focus(); return false; }
					if (formID.password_confirm.value == "") { alert("' . _ADMIN_ADD_USER_ENTER_CONFIRM_PASSWORD . '");
					formID.password_confirm.focus(); return false; }
					if (formID.password_confirm.value !== formID.password.value) { alert("' . _ADMIN_ADD_USER_PASSWORD_AND_CONFIRM_NOT_MATCH . '");
					formID.password.focus(); return false; }
					return true;
					}
					</script> ');

        echo ('
					<article id="add_user">
					<div class="content box">
					<h1>' . _ADMIN_ADD_USER . '</h1>

					<form action="register?step2=0" method="post" onSubmit="return CheckForm(this);">

					<div class="label ' . $align . '">' . _ADMIN_ADD_USER_NAME . ':</div>
					<input type="text" name="uname" value="" dir="ltr">

					<div class="label ' . $align . '">' . _ADMIN_ADD_USER_LAST_NAME . ':</div>
					<input type="text" name="ulname" value="" dir="' . $rtl_input . '">

					<div class="label ' . $align . '">' . _ADMIN_ADD_USER_FATHER_NAME . ':</div>
					<input type="text" name="fname" value="" dir="' . $rtl_input . '">

					<div class="label ' . $align . '">' . _ADMIN_ADD_USER_USER_ID . '</div>
					<input type="text" name="uid" value="" dir="ltr">

					<div class="label ' . $align . '">' . _ADMIN_ADD_USER_PASSWORD . '</div>
					<input type="password" name="password" value="" dir="ltr">

					<div class="label ' . $align . '">' . _ADMIN_ADD_USER_CONFIRM_PASSWORD . '</div>
					<input type="password" name="password_confirm" value="" dir="ltr">

					<div class="label ' . $align . '">' . _ADMIN_ADD_USER_EMAIL . '</div>
					<input type="text" name="email" value="" dir="ltr">

                    <input type="hidden" name="end" value="">

					<div class="button_wrap left clearfix">
					<input class="button" type="submit" value="' . _ADMIN_ADD_USER_END . '">
					<input class="button bad" type=button name=bt1 value="' . _ADMIN_FORM_CANCEL . '" onClick="dosubmit()">
					</div>

					</form>
					</div>
					</article>
				');

	} else {
		header('Location: index');
	}
} else {
	include('main.php');
    echo('
		<article class="msg">
		<div class="content">

		<div class="error_box clearfix" style="width:21em;">
			<div class="box_icon" data-icon="w" aria-hidden="true"></div>
			<div class="content clearfix">' . _EXAM_REGISTER_COOKIE_THERE_IS . '</div>
		</div>

		</div>
		</article>
		');
}
?>

<?php include('footer1.php'); ?>