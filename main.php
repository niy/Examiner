<?php

require_once ('config.php');
$result = $db->db_query("SELECT * FROM settings WHERE id = '1'");

if (!$result) {
    require_once ('language/farsi.php');
    echo ('<!doctype html><html dir="rtl">');
    include ('header.php');

    echo ('<article id="install" class="login">
    <section class="msg">
		<div class="info_box clearfix">
            <div class="box_icon" data-icon="y" aria-hidden="true"></div>
            <div class="content clearfix">
                <ul>
                <li>' . _EXAMINER_INSTALL_FARSI . '.</li>
                <li style="direction:ltr; text-align:left; margin-top:1em;">' . _EXAMINER_INSTALL_ENGLISH .'</li>
                </ul>
            </div>
		</div>
	</section>
    <div class="content box" style="margin-top:1em;">
    <form method="POST" action="install/" onSubmit="return CheckForm(this);">

		<div class="label right">' . _ADMIN_ADMIN_USERNAME . '</div>
		<input type="text" name="admin_id" value="" dir="ltr" size="25">

		<div class="label right">' . _ADMIN_ADD_USER_PASSWORD . '</div>
		<input type="password" name="password" value="" dir="ltr" size="25">

		<div class="label right">' . _ADMIN_ADD_USER_CONFIRM_PASSWORD . '</div>
		<input type="password" name="password_confirm" value="" dir="ltr" size="25">

		<div class="label right">' . _ADMIN_SETTINGS_LANGUAGE . ':</div>
		<select dir="ltr" size="1" id="lang" name="language">');
    ?>

    <?php
    $dir = opendir("language");

    while (false !== ($file = readdir($dir))) {
        if (strpos($file, '.php', 1)) {
            $rest = substr("$file", 0, -4);

            if ($rest == $system_language)
                echo ('<option selected value="' . $rest . '">' . ucwords($rest) . '</option>');
            else
                echo ('<option value="' . $rest . '">' . ucwords($rest) . '</option>');
        }
    }

    closedir($dir);

    echo ('</select>
    <div class="label right">' . _ADMIN_SETTINGS_RTL . ':</div>
    <input type="radio" value="1" checked  name="rtl">' . _ADMIN_SYSTEM_ALERT_RTL_RTL . '

	<input type="radio" name="rtl" value="0">' . _ADMIN_SYSTEM_ALERT_RTL_LTR . '

    <div class="button_wrap left clearfix">
	<input class="button" type="submit" value="Install Examiner" name="B1">
	</div>

	</form>
	</div>
	</article>
	');
    echo ('
    <script type="text/javascript">
    function dosubmit() {
        document.forms[0].action = "index"
        document.forms[0].method = "post"
        document.forms[0].submit()
    }
    </script>
    <script type="text/javascript">
		function CheckForm(formID) {
		if (formID.admin_id.value == "") { alert("' . _ADMIN_INSTALL_ENTER_USERNAME . '");
		formID.admin_id.focus(); return false; }
		if (formID.password.value == "") { alert("' . _ADMIN_INSTALL_ENTER_PASSWORD . '");
		formID.password.focus(); return false; }
		if (formID.password_confirm.value == "") { alert("' . _ADMIN_INSTALL_ADD_USER_ENTER_CONFIRM_PASSWORD . '");
		formID.password_confirm.focus(); return false; }
		if (formID.password_confirm.value !== formID.password.value) { alert("' . _ADMIN_INSTALL_ADD_USER_PASSWORD_AND_CONFIRM_NOT_MATCH . '");
		formID.password.focus(); return false; }
		return true;
		}
	</script> ');
    include ('footer1.php');
    include('footer_end.php');
    die();
    //////////////End form


} else {
    $rec = $db->single();
    $system_language = $rec[3];
    include('language/' . $rec[3] . '.php');

    if ($rec[4] == 1)
        echo ('<!doctype html><html dir="rtl">');
    else
        echo ('<!doctype html><html>');
    $result_rtl = $db->db_query("SELECT * FROM settings WHERE id = '1'");
    $rtl_array = $db->single();
    if ($rtl_array[4] == 1) {
        $system_align = $align = "right";
        $system_rtl_input = $rtl_input = "rtl";
    } else {
        $system_align = $align = "left";
        $system_rtl_input = $rtl_input = "ltr";
    }
    include('header.php');
}
//////////////bg
?>