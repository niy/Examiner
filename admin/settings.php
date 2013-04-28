<?php
require '../inc/PasswordHash.php';
$t_hasher = new PasswordHash(8, FALSE);

header("Content-Type: text/html; charset=utf-8");

if (!isset($_COOKIE['examiner'])) {
    header('Location: index');
} else {
    include('admin_config.php');

    if (isset($_REQUEST["case"])) {
        $case = $_REQUEST["case"];
        if ($case == "changepass") {
            if (isset($_REQUEST["p_uname"])) {
                $p_uname = $_REQUEST["p_uname"];
                $p_pass = $_REQUEST["p_pass"];

                $check_security=mysql_query("SELECT * FROM settings WHERE admin_id='$p_uname'", $db);
                $st=mysql_fetch_row($check_security);
                $stored_hash=$st[2];
                $check = $t_hasher->CheckPassword($p_pass, $stored_hash);
                if ($check) {  //if user is authorized
                    //Print "Change Password" form
                    $rec = mysql_fetch_row($check_security);
                    echo ('
                    <article>
                    <form method="POST" action="settings?case=changepass" onSubmit="return CheckForm(this);">
					<div class="content login box">

					<h1 class="title"> ' . _ADMIN_SYSTEM_ALERT_U_P . ' </h1>

					<div class="label '. $align .'">' . _ADMIN_NEW_USERNAME . ':</div>
					<input type="text" name="new_uname" dir="ltr">

					<div class="label '. $align .'">' . _ADMIN_NEW_PASSWORD . ':</div>
					<input type="password" name="new_pass" dir="ltr">

					<div class="label '. $align .'">' . _ADMIN_NEW_AGAIN_PASSWORD . ':</div>
					<input type="password" name="new_again_pass" dir="ltr">

                    <div class="button_wrap left clearfix">
					<input class="button" type="submit" value="' . _ADMIN_CONTINUE . '" name="B1">
					<input class="button bad" type=button name=bt1 value="' . _ADMIN_FORM_CANCEL . '" onClick="dosubmit()">
					</div>

					</div>
					</form>
					</article>
					');
                }
                else { // if admin is not authored show error + form
                    echo ('
                        <article>
                        <form method="POST" action="settings?case=changepass" onSubmit="return CheckForm(this);">
                        <div class="content login box">

                        <h1 class="title"> ' . _ADMIN_SYSTEM_ALERT_U_P . '</h1>
                        <div class="error"><span data-icon="w" aria-hidden="true"></span> ' . _ADMIN_SYSTEM_ALERT_WRONG_PREVIOUS_U_P . '</div>

                        <div class="label '. $align .'">' . _ADMIN_PREVIOUS_USERNAME . ':</div>
                        <input type="text" name="p_uname" dir="ltr">

                        <div class="label '. $align .'">' . _ADMIN_PREVIOUS_PASSWORD . ':</div>
                        <input type="password" name="p_pass" dir="ltr">

                        <div class="button_wrap left clearfix">
                        <input class="button" type="submit" value="' . _ADMIN_CONTINUE . '" name="B1">
                        <input class="button bad" type="button" name="bt1" value="' . _ADMIN_FORM_CANCEL . '" onClick="dosubmit()">
                        </div>

                        </div>
                        </form>
                        </article>
			');
                }
            }
            else if (isset($_REQUEST["new_uname"])) { //if new name is supplied. change the username
                $new_uname = $_REQUEST["new_uname"];
                $new_pass = $_REQUEST["new_pass"];
                $hash = $t_hasher->HashPassword($new_pass);
                $change_u_p = "UPDATE settings SET admin_id='$new_uname', password ='$hash' WHERE id=1";
                $change_u_p = mysql_query($change_u_p, $db);

                if (!$change_u_p) {
                    die('Database query error:' . mysql_error());
                }
                echo('
                    <article class="msg">
                    <div class="info_box clearfix" >
                    <div class="box_icon" data-icon="y" aria-hidden="true"></div>
                    <div class="content clearfix">
                    <h1>' . _ADMIN_SYSTEM_ALERT_U_P . '</h1>
                    <ul><li>' . _ADMIN_SYSTEM_CHANGED_U_P . '</li></ul>
                    </div>
                    </div>
                    <div id="back" class="button_wrap clearfix">
                    <a class="button" id="back_b" href="settings""><div data-icon="b" aria-hidden="true" class="grid_img"></div><div class="grid_txt">' . _ADMIN_SETTINGS . '</div></a>
                    </div>
                    </article>');
                include('../footer.php');
                include('../footer_end.php');
                die();
            } else { //if new name is not supplied show the admin auth form
                echo ('
                    <article>
                    <form method="POST" action="settings?case=changepass" onSubmit="return CheckForm(this);">
                    <div class="content login box">
                    <h1 class="title">' . _ADMIN_SYSTEM_ALERT_U_P . '</h1>

                    <div class="label '. $align .'">' . _ADMIN_PREVIOUS_USERNAME . ':</div>
                    <input type="text" name="p_uname" dir="ltr">

                    <div class="label '. $align .'">' . _ADMIN_PREVIOUS_PASSWORD . ':</div>
                    <input type="password" name="p_pass" dir="ltr">

                    <div class="button_wrap left clearfix">
                    <input class="button" type="submit" value="' . _ADMIN_CONTINUE . '" name="B1">
                    <input class="button bad" type="button" name="bt1" value="' . _ADMIN_FORM_CANCEL . '" onClick="dosubmit()">
                    </div>

                    </div>
                    </form>
                    </article>
			');
            }
        } else { //if request is not Change Password. check for "Change Language" request.
            if (isset($_REQUEST["language"])) { //if requested lang is set change settings.
                $language = $_REQUEST["language"];
                $rtl = $_REQUEST["rtl"];
                $change_settings = "UPDATE settings SET language='$language', rtl ='$rtl' WHERE id=1";
                $change_settings = mysql_query($change_settings, $db);
                if (!$change_settings) {
                    die('Database query error:' . mysql_error());
                }
                if ($rtl == 1)
                    $rtl = "RTL";
                else
                    $rtl = "LTR";
                echo('
                    <article class="msg">

                    <div class="info_box clearfix" >
                    <div class="box_icon" data-icon="y" aria-hidden="true"></div>
                    <div class="content clearfix">
                    <h1>' . _ADMIN_SYSTEM_ALERT_LANGUAGE . '</h1>
                    <ul>
                    <li>'. _ADMIN_SYSTEM_ALERT_LANGUAGE_TO . ' ' . $language . ' ' . _ADMIN_SYSTEM_ALERT_LANGUAGE_DONE . '</li>
                    <li>'. _ADMIN_SYSTEM_ALERT_RTL_TO . ' ' . $rtl . ' ' . _ADMIN_SYSTEM_ALERT_RTL_DONE . '</li>
                    </ul>
                    </div>
                    </div>
                    <div id="back" class="button_wrap clearfix">
                    <a class="button" id="back_b" href="settings""><div data-icon="b" aria-hidden="true" class="grid_img"></div><div class="grid_txt">' . _ADMIN_SETTINGS . '</div></a>
                    </div>

                    </article>');
                include('../footer.php');
                include('../footer_end.php');
                die();
            }
            else //if language has not been requested to change
            {
                if ($rtl_input == "rtl") {
                    $checked = "checked";
                    $checked2 = "";
                } else {
                    $checked = "";
                    $checked2 = "checked";
                }

                echo ('
                    <article>
                    <form method="POST" action="settings?case=changesettings">
                    <div class="content login box">
                    <h1 class="title">' . _ADMIN_SYSTEM_ALERT_LANGUAGE . '</h1>
                    <div class="label '. $align .'">' . _ADMIN_SETTINGS_LANGUAGE . ':</div>
                    <select size="1" dir="ltr" id="lang" name="language">
                ');
                $dir = opendir("../language");

                while (false !== ($file = readdir($dir))) { //read language files available
                    if (strpos($file, '.php', 1)) {
                        $rest = substr("$file", 0, -4);

                        if ($rest == $system_language)
                            echo ('<option selected value="' . $rest . '">' . ucwords($rest) . '</option>');
                        else
                            echo ('<option value="' . $rest . '">' . ucwords($rest) . '</option>');
                    }
                }

                closedir($dir);

                echo ('
                    </select>

                    <div class="label '. $align .'">'. _ADMIN_SETTINGS_RTL . ':</div>
                    <input type="radio" value="1" ' . $checked . ' name="rtl">' . _ADMIN_SYSTEM_ALERT_RTL_RTL . '
                    <input type="radio" name="rtl" value="0" ' . $checked2 . '>' . _ADMIN_SYSTEM_ALERT_RTL_LTR . '

                    <div class="button_wrap left clearfix">
                    <input class="button" type="submit" value="' . _ADMIN_CONTINUE . '" name="B1">
                    <input class="button bad" type="button" name="bt1" value="' . _ADMIN_FORM_CANCEL . '" onClick="dosubmit()">
                    </div>

                    </div>
                    </form>
                    </article>
			');
            }
        }
    }
    else //if no case has been requested yet (aka. show home page)
    {
        echo ('
            <article id="grid_wrap">

			<nav class="grid settings">
			<div id="pad" class="content clearfix">
			<ul>

            <li><a href="../admin"><div data-icon="h" aria-hidden="true" class="grid_img"></div>
            <div class="grid_txt">'. _ADMIN_HOME . '</div></a></li>

            <li><a href="?case=changesettings"><div data-icon="p" aria-hidden="true" class="grid_img"></div>
            <div class="grid_txt">'. _ADMIN_CHANGE_SETTINGS . '</div></a></li>

            <li><a href="?case=changepass"><div data-icon="g" aria-hidden="true" class="grid_img"></div>
            <div class="grid_txt">'. _ADMIN_CHANGE_U_P. '</div></a></li></ul></div></nav>


            <div id="about"><ul class="content"><li><a href="https://github.com/niy/examiner"><img src="../img/logo_tny.png"/></a></li><li>' . _EXAMINER_OTMS . '</li><li>'
            . _EXAMINER_VERSION .
            '</li><li><a href="https://github.com/niy/examiner">' . _EXAMINER_HOMEPAGE . '</a></li><li>'
            . _EXAMINER_PROGRAMMER_CONTACT . ': ' . _EXAMINER_PROGRAMMER_EMAIL . '</li></ul>
		    </div>

		    </article>
		');
    }
}
?>
<?php include('../footer.php');

echo ('
    <script language="javascript">
        function dosubmit() {
            document.forms[0].action = "settings"
            document.forms[0].method = "POST"
            document.forms[0].submit()
        }
    </script>
    ');
echo ('
                    <script language="JavaScript">
                        function CheckForm(formID) {
                            if (formID.new_uname.value =="") { alert("' . _ADMIN_ENTER_NEW_USERNAME . '");
                            formID.new_uname.focus(); return false; }
                            if (formID.new_pass.value =="") { alert("' . _ADMIN_ENTER_NEW_PASSWORD . '");
                            formID.new_pass.focus(); return false; }
                            if (formID.new_again_pass.value =="") { alert("' . _ADMIN_ENTER_NEW_PASSWORD_CONFIRM . '");
                            formID.new_again_pass.focus(); return false; }
                            if (formID.new_pass.value !== formID.new_again_pass.value) { alert("' . _ADMIN_NEW_PASSWORD_NOT_MATCH . '");
                            formID.new_pass.focus(); return false; }
                            return true;
                        }
					</script> ');

include('../footer_end.php');?>