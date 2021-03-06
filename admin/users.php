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

		if (($case == "adduser") && !(isset($_REQUEST["end"]))) {

			echo ('
			<article id="add_user">
				<div class="content box">
					<h1 calss="title">' . _ADMIN_ADD_USER . '</h1>
					<div class="info_box clearfix">
					<div class="box_icon" data-icon="y" aria-hidden="true"></div>
					<div class="content clearfix">' . _STARS_ARE_NECESSARY . '</div>
					</div>
					<form action="users?case=adduser" method="post" onSubmit="return CheckForm(this);">

					<div class="label '. $align .'">' . _ADMIN_ADD_USER_NAME . ':</div>
					<input type="text" name="uname" value="" dir="' . $rtl_input . '">

					<div class="label '. $align .'">' . _ADMIN_ADD_USER_LAST_NAME . ':</div>
					<input type="text" name="ulname" value="" dir="' . $rtl_input . '">

					<div class="label '. $align .'">' . _ADMIN_ADD_USER_FATHER_NAME . ':</div>
					<input type="text" name="fname" value="" dir="' . $rtl_input . '">

					<div class="label '. $align .'">' . _ADMIN_ADD_USER_USER_ID . '</div>
					<input type="text" name="uid" value="" dir="ltr">

					<div class="label '. $align .'">' . _ADMIN_ADD_USER_PASSWORD . '</div>
					<input type="password" name="password" value="" dir="ltr">

					<div class="label '. $align .'">' . _ADMIN_ADD_USER_CONFIRM_PASSWORD . '</div>
					<input type="password" name="password_confirm" value="" dir="ltr">

					<div class="label '. $align .'">' . _ADMIN_ADD_USER_EMAIL . ':</div>
					<input type="text" name="email" value="" dir="ltr" size="30">

					<input type="hidden" name="end" value="">

					<div class="button_wrap left clearfix">
		            <input class="button" type="submit" value="' . _ADMIN_ADD_USER_END . '">
		            <input class="button bad" type=button name=bt1 value="' . _ADMIN_FORM_CANCEL . '" onClick="dosubmit()">
		            </div>

					</form>
				</div>
            </article>
			');

		} else //if (isset ($_REQUEST["end"]))
		{
			$uname = $_REQUEST["uname"];
			$ulname = $_REQUEST["ulname"];
			$fname = $_REQUEST["fname"];
			$uid = $_REQUEST["uid"];
			$password = $_REQUEST["password"];
			$email = $_REQUEST["email"];
            $pars = array(
                ':uid' => $uid,
            );
			$check_uname = $db->db_query("SELECT * FROM users WHERE userid=:uid",$pars);

			if ($rec = $db->single()) {
				echo ('
				<script type="text/javascript">
				function dosubmit() {
					document.forms[0].action = "users"
					document.forms[0].method = "post"
					document.forms[0].submit()
				}
				</script>
				<script type="text/javascript">
				function CheckForm(formID) {
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
				</script>
				');
				echo ('
				<article id="add_user">
				<div class="content box">
					<h1 class="title">' . _ADMIN_ADD_USER . '</h1>
					<div class="info_box clearfix">
					<div class="box_icon" data-icon="y" aria-hidden="true"></div>
					<div class="content clearfix">' . _STARS_ARE_NECESSARY . '</div>
					</div>
					<div class="error_box clearfix">
					<div class="box_icon" data-icon="w" aria-hidden="true"></div>
					<div class="content clearfix">' . _ADMIN_USERNAME_TAKEN . '</div>
					</div>
					<form action="users?case=adduser" method="post" onSubmit="return CheckForm(this);">

					<div class="label '. $align .'">' . _ADMIN_ADD_USER_NAME . ':</div>
					<input type="text" name="uname" value="' . $uname . '" dir="' . $rtl_input . '">

					<div class="label '. $align .'">' . _ADMIN_ADD_USER_LAST_NAME . ':</div>
					<input type="text" name="ulname" value="' . $ulname . '" dir="' . $rtl_input . '">

					<div class="label '. $align .'">' . _ADMIN_ADD_USER_FATHER_NAME . ':</div>
					<input type="text" name="fname" value="' . $fname . '" dir="' . $rtl_input . '">

					<div class="label '. $align .'">' . _ADMIN_ADD_USER_USER_ID . '</div>
					<input type="text" autofocus name="uid" value="" dir="ltr">

					<div class="label '. $align .'">' . _ADMIN_ADD_USER_PASSWORD . '</div>
					<input type="password" name="password" value="' . $password . '" dir="ltr">

					<div class="label '. $align .'">' . _ADMIN_ADD_USER_CONFIRM_PASSWORD . '</div>
					<input type="password" name="password_confirm" value="' . $password . '" dir="ltr">

					<div class="label '. $align .'">' . _ADMIN_ADD_USER_EMAIL . ':</div>
					<input type="text" name="email" value="' . $email . '" dir="ltr" size="30">

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

				echo('
				    <article class="msg">

                    <div class="info_box clearfix">
                        <div class="box_icon" data-icon="y" aria-hidden="true"></div>
                        <div class="content clearfix">
                            <h1 class="title">' . _ADMIN_ADD_USER . '</h1>
                            ' . _ADMIN_SYSTEM_ADDED_USER . ':
                            <ul>
                                <li>' . _ADMIN_ADD_USER_NAME_AND_LNAME . ': ' . $uname . ' ' . $ulname . '</li>
                                <li>' . _ADMIN_ADD_USER_FATHER_NAME . ': ' . $fname . '</li>
                                <li>' . _ADMIN_ADD_USER_USER_ID . ': ' . $uid . '</li>
                                <li>' . _ADMIN_ADD_USER_EMAIL . ': ' . $email . '</li>
                            </ul>
                        </div>
                    </div>

                    <div id="back" class="button_wrap clearfix">
                        <a class="button" id="back_b" href="users"><div data-icon="u" aria-hidden="true" class="grid_img"></div>
                        <div class="grid_txt">' . _ADMIN_ADD_EDIT_USER . '</div></a>
                    </div>

                    </article>
				');
                include('../footer.php');
                include('../footer_end.php');
                die();
			}
		}
	} else {
		echo ('
			<article id="grid_wrap">
			<nav class="grid users">
			<div id="pad" class="content clearfix">
			<ul>
			<li><a href="all_users"><div data-icon="u" aria-hidden="true" class="grid_img"></div>
			<div class="grid_txt">'. _ADMIN_ALL_USERS . '</div></a></li>

			<li><a href="?case=adduser"><div data-icon="i" aria-hidden="true" class="grid_img"></div>
			<div class="grid_txt">'. _ADMIN_ADD_USER. '</div></a></li></ul></div></nav>
			</article>
		');
	}
}
?>

<?php include('../footer.php');

echo ('
		<script type="text/javascript">
		function dosubmit() {
		document.forms[0].action = "settings";
		document.forms[0].method = "post";
		document.forms[0].submit();
		}
	</script>');
echo ('
				<script type="text/javascript">
				function dosubmit() {
					document.forms[0].action = "users"
					document.forms[0].method = "post"
					document.forms[0].submit()
				}
				</script>
				<script type="text/javascript">
				function CheckForm(formID) {
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
				</script>
			');

include('../footer_end.php');?>