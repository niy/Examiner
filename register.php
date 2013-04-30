<?php
require 'inc/PasswordHash.php';
$t_hasher = new PasswordHash(8, FALSE);
session_start();

include('config.php');

require_once('inc/recaptchalib.php');
$publickey = "6LdjpOASAAAAAMdSrcme8oXg_Sa0RK4yLqBgFDad";
$privatekey = "6LdjpOASAAAAAEJyNHHphrTsmX3Rn0B_wF4GppAs";
# the response from reCAPTCHA
$resp = null;
$error = null;

$check_default = $db->db_query("SELECT * FROM tests WHERE be_default = '1'");
$check_default_reg = $db->single();

if (!($check_default_reg[4] == 0)) {
	include('main.php');
	echo('<article id="add_question"><div class="content">
		<div class="info_box clearfix">
		<div class="box_icon" data-icon="y" aria-hidden="true"></div>
		<div class="content clearfix">' . _ADMIN_NOT_ALLOWED . '!</div>
		</div>
		</div>
		</article>
		');
    include ('footer1.php');
    include('footer_end.php');
    die();
}

if (!isset($_SESSION['examiner_user'])) {
	if (!(isset($_REQUEST["reg"])) && isset($_REQUEST["step2"])) {
		if (!(isset($_REQUEST["uid"])) | !(isset($_REQUEST["password"]))) {header("location: index");die();}

        $safe_to_go = true;
        $resp = recaptcha_check_answer ($privatekey,
            $_SERVER["REMOTE_ADDR"],
            $_POST["recaptcha_challenge_field"],
            $_POST["recaptcha_response_field"]);
        if ($resp->is_valid) {
            $safe_to_go = true;
        } else $safe_to_go = false;

        if ($safe_to_go) {
            $uname = $_REQUEST["uname"];
            $ulname = $_REQUEST["ulname"];
            $fname = $_REQUEST["fname"];
            $uid = $_REQUEST["uid"];
            $password = $_REQUEST["password"];
            $email = $_REQUEST["email"];
            $pars = array(
                ':uid' => $uid
            );
            $check_uname = $db->db_query("SELECT * FROM users WHERE userid=:uid",$pars);

            if ($rec = $db->single()) {
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
                        if (formID.recaptcha_response_field.value == "") { alert("' . _ADMIN_ENTER_CAPTCHA . '");
			            formID.recaptcha_response_field.focus(); return false; }
                        return true;
                        }
                        </script> ');
                echo ('
                <script type="text/javascript">
                 var RecaptchaOptions = {
                    theme : \'clean\'
                 };
                 </script>
                ');
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

                        <input type="hidden" name="end" value="">');

                    echo ('<div id="captcha">');
                    if (isset($_REQUEST['wrongcaptcha'])) {
                        echo ('<div class="error"><span data-icon="w" aria-hidden="true"></span> ' . _ADMIN_SYSTEM_ALERT_WRONG_CAPTCHA . '</div>');
                    }
                    echo recaptcha_get_html($publickey, $error);
                    echo ('</div>');

                echo('
                        <div class="button_wrap left clearfix">
                        <input class="button" type="submit" value="' . _ADMIN_ADD_USER_END . '">
                        <input class="button bad" type=button name=bt1 value="' . _ADMIN_FORM_CANCEL . '" onClick="dosubmit()">
                        </div>

                        </form>
                        </div>
                        </article>
                    ');

            } else {
                $hash = $t_hasher->HashPassword($password);
                $sqlstring = "INSERT INTO users (FName, LName, fatherName, userid, password, email) VALUES (:uname, :ulname, :fname, :uid, :hash, :email)";
                $pars = array(
                    ':uname' => $uname,
                    ':ulname' => $ulname,
                    ':fname' => $fname,
                    ':uid' => $uid,
                    ':hash' => $hash,
                    ':email' => $email
                );
                $result = $db->db_query($sqlstring,$pars);

                $_SESSION['examiner_user'] = $uid;
                header("Refresh: 5; url=index.php");
                include('main.php');
                echo('
                        <article class="msg">

                        <div class="info_box clearfix">
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

                        </article>
                    ');
                include ('footer1.php');
                include('footer_end.php');
                die();

            }
        } else header('Location: register?reg&wrongcaptcha');
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
					if (formID.recaptcha_response_field.value == "") { alert("' . _ADMIN_ENTER_CAPTCHA . '");
			        formID.recaptcha_response_field.focus(); return false; }
					return true;
					}
					</script> ');
        echo ('
                <script type="text/javascript">
                 var RecaptchaOptions = {
                    theme : \'clean\'
                 };
                 </script>
                ');

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

                    <input type="hidden" name="end" value="">');
        echo ('<div id="captcha">');
        if (isset($_REQUEST['wrongcaptcha'])) {
            echo ('<div class="error"><span data-icon="w" aria-hidden="true"></span> ' . _ADMIN_SYSTEM_ALERT_WRONG_CAPTCHA . '</div>');
        }
        echo recaptcha_get_html($publickey, $error);
        echo ('</div>');

        echo('
        <div class="button_wrap left clearfix">
					<input class="button" type="submit" value="' . _ADMIN_ADD_USER_END . '">
					<input class="button bad" type=button name=bt1 value="' . _ADMIN_FORM_CANCEL . '" onClick="dosubmit()">
					</div>

					</form>
					</div>
					</article>
				');

	} else {
        if (!isset($_REQUEST['wrongcaptcha']))
		header('Location: index');
	}
} else {
	include('main.php');
    echo('
		<article class="msg">

		<div class="error_box clearfix">
			<div class="box_icon" data-icon="w" aria-hidden="true"></div>
			<div class="content clearfix">' . _EXAM_REGISTER_COOKIE_THERE_IS . '</div>
		</div>

		</article>
		');
}
?>

<?php include('footer1.php');
include('footer_end.php');
?>