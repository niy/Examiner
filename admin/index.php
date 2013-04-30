<?php
require '../inc/PasswordHash.php';
$t_hasher = new PasswordHash(8, FALSE);
include('../config.php');
session_start();
require_once('../inc/recaptchalib.php');
$publickey = "6LdjpOASAAAAAMdSrcme8oXg_Sa0RK4yLqBgFDad";
$privatekey = "6LdjpOASAAAAAEJyNHHphrTsmX3Rn0B_wF4GppAs";
# the response from reCAPTCHA
$resp = null;
$error = null;

header("Content-Type: text/html; charset=utf-8");

if (isset($_REQUEST["uname"])) {
    $uname = $_REQUEST["uname"];
    $pass = $_REQUEST["pass"];

    $safe_to_go = true;
    if (isset($_SESSION['try']) && $_SESSION['try'] > 2) {
        $resp = recaptcha_check_answer ($privatekey,
            $_SERVER["REMOTE_ADDR"],
            $_POST["recaptcha_challenge_field"],
            $_POST["recaptcha_response_field"]);
        if ($resp->is_valid) {
            $safe_to_go = true;
        } else $safe_to_go = false;
    }

    if ($safe_to_go) {
        $pars = array(
            ':uname' => $uname
        );
        $check_security=$db->db_query("SELECT * FROM settings WHERE admin_id=:uname", $pars);
        $st=$db->single();
        $stored_hash=$st[2];
        $check = $t_hasher->CheckPassword($pass, $stored_hash);
        $hash = $t_hasher->HashPassword('admin');
        if ($check) {
            $_SESSION['try'] = 0;
            $rec = $db->single();
            header('Location: index');
            setcookie('examiner', 'examiner', time() + 36000, '/');
        } else {
            if (!isset($_SESSION['try'])) {
                $_SESSION['try'] = 1;
            } else {
                $_SESSION['try']=$_SESSION['try']+1;
            }
            header('Location: index?wrong');
        }
        include('../main.php');
    } else header('Location: index?wrongcaptcha');
} else { //if not trying to login
    include('admin_config.php');

    if (!isset($_COOKIE['examiner'])) { //if admin is not logged in

        echo ('
            <article>');

            echo ('
            <script type="text/javascript">
             var RecaptchaOptions = {
		        theme : \'custom\',
		        custom_theme_widget: \'recaptcha_widget\'
	            };
             </script>
            ');

        echo ('
            <form method="POST" action="index" onSubmit="return CheckForm(this);">
            <div class="content login box">
	        <h1 class="title">' . _ADMIN_SYSTEM_ALERT . '</h1>
	        ');
	        if (isset($_REQUEST['wrong'])) {
                echo ('<div class="error"><span data-icon="w" aria-hidden="true"></span> ' . _ADMIN_SYSTEM_ALERT_WRONG_PREVIOUS_U_P . '</div>');
            }
        echo ('
	        <div class="label '. $align .'">' . _ADMIN_USERNAME . ':</div>
	        <input type="text" name="uname" dir="ltr">

	        <div class="label '. $align .'">' . _ADMIN_PASSWORD . ':</div>
	        <input type="password" name="pass" dir="ltr">');

            if (isset($_SESSION['try']) && $_SESSION['try'] > 2) {
                echo ('<div id="captcha">');
                if (isset($_REQUEST['wrongcaptcha'])) {
                    echo ('<div class="error"><span data-icon="w" aria-hidden="true"></span> ' . _ADMIN_SYSTEM_ALERT_WRONG_CAPTCHA . '</div>');
                }
                echo recaptcha_get_html($publickey, $error);
                echo ('</div>');
            }

        echo ('
            <div class="button_wrap left clearfix">
	        <input  class="button good" type="submit" value="' . _ADMIN_ENTER . '" name="B1">
            </div>

            </div>
            </form>
            </article>
        ');
    } else {
        echo ('
            <article id="grid_wrap">
			<nav class="grid admin">
			<div id="pad" class="content clearfix">
			<ul>
                <li><a href="add_test"><div data-icon="a" aria-hidden="true" class="grid_img"></div><div class="grid_txt">' . _ADMIN_INDEX_ADD_EXAM . '</div></a></li>
                <li><a href="all_tests"><div data-icon="t" aria-hidden="true" class="grid_img"></div><div class="grid_txt">' . _ADMIN_EDIT_EXAMS . '</div></a></li>
                <li><a href="default"><div data-icon="d" aria-hidden="true" class="grid_img"></div><div class="grid_txt">' . _ADMIN_DEFINE_DEFAULT . '</div></a></li>
                <li><a href="users"><div data-icon="u" aria-hidden="true" class="grid_img"></div><div class="grid_txt">' . _ADMIN_ADD_EDIT_USER . '</div></a></li>
                <li><a href="charts"><div data-icon="c" aria-hidden="true" class="grid_img"></div><div class="grid_txt">' . _ADMIN_CHARTS . '</div></a></li>
                <li><a href="settings"><div data-icon="s" aria-hidden="true" class="grid_img"></div><div class="grid_txt">' . _ADMIN_SETTINGS . '</div></a></li>
            </ul>
            </div>
			</nav>
            </article>
        ');
    }
}
?>

<?php include('../footer.php');
echo ('
        <script type="text/javascript">
            function CheckForm(formID) {
                if (formID.uname.value == "") { alert("' . _ADMIN_ENTER_USERNAME . '");
                formID.uname.focus(); return false; }
                if (formID.pass.value == "") { alert("' . _ADMIN_ENTER_PASSWORD . '");
                formID.pass.focus(); return false; }
                if (formID.recaptcha_response_field.value == "") { alert("' . _ADMIN_ENTER_CAPTCHA . '");
			    formID.recaptcha_response_field.focus(); return false; }
                return true;
            }
		</script>
		');

include('../footer_end.php');?>