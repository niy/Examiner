<?php
require '../inc/PasswordHash.php';
$t_hasher = new PasswordHash(8, FALSE);
header("Content-Type: text/html; charset=utf-8");

if (!isset($_COOKIE['examiner'])) {
	header('Location: index');
} else {
	include('admin_config.php');

	echo ('
	<script type="text/javascript">
		function dosubmit() {
			document.forms[0].action = "all_users";
			document.forms[0].method = "post";
			document.forms[0].submit();
		}
	</script>');

	if (isset($_REQUEST["case"]) && isset($_REQUEST["uid"])) {
		$case = $_REQUEST["case"];
		$uid = $_REQUEST["uid"];
        $pars = array(
            ':uid'=>$uid
        );
		$result = $db->db_query("SELECT * FROM users WHERE id=:uid",$pars);
		$rec = $db->single();

		if ($case == "edituser") {
			if (!(isset($_REQUEST["end"]))) {
				echo ('
					<script type="text/javascript">
						function CheckForm(formID) {
							if (formID.userid.value == "") { alert("' . _ADMIN_ADD_USER_ENTER_ID . '");
							formID.userid.focus(); return false; }
							return true;
						}
					</script>
				');

				echo ('
						<article id="edit_user">
                        <div class="content box">
                        <h1 class="title" style="margin-bottom: .2em;">' . _ADMIN_ADD_USER . '</h1>
						<form action="edit_user?case=edituser" method="post" onSubmit="return CheckForm(this);">

						<div class="label '. $align .'">' . _ADMIN_ADD_USER_NAME . ':</div>
						<input type="text" name="uname" value="' . $rec[1] . '" dir="' . $rtl_input . '">

						<div class="label '. $align .'">' . _ADMIN_ADD_USER_LAST_NAME . ':</div>
						<input type="text" name="ulname" value="' . $rec[2] . '" dir="' . $rtl_input . '">

						<div class="label '. $align .'">' . _ADMIN_ADD_USER_FATHER_NAME . ':</div>
						<input type="text" name="fname" value="' . $rec[3] . '" dir="' . $rtl_input . '">

						<div class="label '. $align .'">' . _ADMIN_ADD_USER_USER_ID . '</div>
						<input type="text" name="userid" value="' . $rec[4] . '" dir="ltr">

						<div class="label '. $align .'">' . _ADMIN_ADD_USER_EMAIL . ':</div>
						<input type="text" name="email" value="' . $rec[6] . '" dir="ltr">

						<input type="hidden" name="uid" value="' . $rec[0] . '">
						<input type="hidden" name="end" value="">
						<input type="hidden" name="last_uid" value="' . $rec[4] . '">

						<div class="button_wrap left clearfix">
                        <input class="button" type="submit" value="' . _ADMIN_CONTINUE . '">
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
				$userid = $_REQUEST["userid"];
				$email = $_REQUEST["email"];
				$last_uid = $_REQUEST["last_uid"];
                $pars = array(
                    ':userid'=>$userid
                );
				$check_uname = $db->db_query("SELECT * FROM users WHERE userid=:userid",$pars);

				if (($last_uid !== $userid) && ($rec = $db->single())) {
					echo ('
				            <script type="text/javascript">
						        function dosubmit() {
									document.forms[0].action = "all_users"
									document.forms[0].method = "post"
									document.forms[0].submit()
								}
							</script>
							<script type="text/javascript">
								function CheckForm(formID) {
									if (formID.userid.value == "") { alert("' . _ADMIN_ADD_USER_ENTER_ID . '");
									formID.userid.focus(); return false; }
									return true;
								}
							</script> ');

					echo ('
							<article id="edit_user">
                            <div class="content box">
                            <h1 class="title" style="margin-bottom: .2em;">' . _ADMIN_ADD_USER . '</h1>
							<form action="edit_user?case=edituser" method="post" onSubmit="return CheckForm(this);">

							<div class="label '. $align .'">' . _ADMIN_ADD_USER_NAME . ':</div>
							<input type="text" name="uname" value="' . $uname . '" dir="' . $rtl_input . '">

							<div class="label '. $align .'">' . _ADMIN_ADD_USER_LAST_NAME . ':</div>
							<input type="text" name="ulname" value="' . $ulname . '" dir="' . $rtl_input . '">

							<div class="label '. $align .'">' . _ADMIN_ADD_USER_FATHER_NAME . ':</div>
							<input type="text" name="fname" value="' . $fname . '" dir="' . $rtl_input . '">

							<div class="label '. $align .'">' . _ADMIN_ADD_USER_USER_ID . '</div>
							<input type="text" name="userid" value="" dir="ltr" size="25">

							<div class="label '. $align .'">'. _ADMIN_ADD_USER_EMAIL . ':</div>
							<input type="text" name="email" value="' . $email . '" dir="ltr" size="30">

							<input type="hidden" name="end" value="">
							<input type="hidden" name="uid" value="' . $uid . '">
							<input type="hidden" name="last_uid" value="' . $last_uid . '">

							<div class="button_wrap left clearfix">
                            <input class="button" type="submit" value="' . _ADMIN_CONTINUE . '">
                            <input class="button bad" type=button name=bt1 value="' . _ADMIN_FORM_CANCEL . '" onClick="dosubmit()">
                            </div>
							</form>');
				} else {

					$sqlstring = "UPDATE users SET FName=:uname, LName=:ulname, fatherName=:fname, userid=:userid, email= :email WHERE id=:uid";
                    $pars = array(
                        ':uname' => $uname,
                        ':ulname' => $ulname,
                        ':fname' => $fname,
                        ':userid' => $userid,
                        ':email' => $email,
                        ':uid' => $uid,
                    );
                    $result = $db->db_query($sqlstring, $pars);

					echo('
					<article class="msg">
                    <div class="info_box clearfix" >
                    <div class="box_icon" data-icon="y" aria-hidden="true"></div>
                    <div class="content clearfix">
                    <h1>' . _ADMIN_ADD_USER_EDIT_USER . '</h1>
                    <ul><li>' . _ADMIN_SYSTEM_EDITED_USER . '</li><ul>
                    <ul>
                    <li><b>' . _ADMIN_ADD_USER_NAME_AND_LNAME . ':</b> ' . $uname . ' ' . $ulname . '</li>
                    <li><b>' . _ADMIN_ADD_USER_FATHER_NAME . ':</b> ' . $fname . '</li>
                    <li><b>' . _ADMIN_ADD_USER_USER_ID . ':</b> ' . $userid . '</li>
                    <li><b>' . _ADMIN_ADD_USER_EMAIL . ':</b> ' . $email . '</li>
                    </ul>
                    </div>
                    </div>
                    <div id="back" class="button_wrap clearfix">
                    <a class="button" id="back_b" href="users"><div data-icon="b" aria-hidden="true" class="grid_img"></div><div class="grid_txt">' . _ADMIN_ADD_EDIT_USER . '</div></a>
                    </div>
                    </article>');
                    include('../footer.php');
                    include('../footer_end.php');
                    die();
				}
			}
		} else if ($case == "deleteuser_test") {

			$test_id = $_REQUEST['test_id'];
            $pars = array(
                ':test_id' => $test_id,
            );
			$test_prop = $db->db_query("SELECT * FROM tests WHERE id=:test_id",$pars);
			$test_prop = $db->single();
			$uid = $_REQUEST["uid"];
			echo ('
                    <form action="edit_user?case=deleteuser" method="post">
                    <article id="delete_test">
                    <div class="content box">

                    <h1>' . _ADMIN_ADD_USER_DELETE_USER . '</h1>
                    ' . _ADMIN_USER_DELETE_ARE_YOU_SURE_1 . '<b>' . $rec[4] . '</b>
                    ' . _ADMIN_USER_DELETE_ARE_YOU_SURE_3 . ': <b>' . $test_prop[1] . '</b>
                    ' . _ADMIN_USER_DELETE_ARE_YOU_SURE_4 . '
                    <div class="info_box clearfix">
                    <div class="box_icon" data-icon="y" aria-hidden="true"></div>
                    <div class="content clearfix">
                    <h1>'. _ADMIN_NOTE . '</h1><ul><li>'
                    . _ADMIN_DELETE_USER_EXAM_ALERT . '</li></ul>
                    </div>
                    </div>
                    <input type="hidden" name="uid" value="' . $uid . '">
                    <input type="hidden" name="end" value="">

                    <div class="button_wrap left clearfix">
                    <input class="button bad" type="button" onclick = "getData(\'delete_user_test.php?user_id=' . $uid . '&test_id=' . $test_id . '\', \'targetDiv\')" value="' . _ADMIN_ADD_USER_DELETE_USER . '">
                    <input class="button" type="button" name="bt1" value="' . _ADMIN_FORM_CANCEL . '" onClick="javascript: window.history.go(-1)">
                    </div>

                    </div>
                    <div id="targetDiv">
                    </div>
                    </article>
                    </form>

            ');
		} else if ($case == "deleteuser") {
			if (!(isset($_REQUEST["end"]))) {
				$uid = $_REQUEST["uid"];
                $pars = array(
                    ':uid'=>$uid
                );
				$result = $db->db_query("SELECT * FROM users WHERE id=:uid",$pars);
				$rec = $db->single();

				echo ('
				    <form action="edit_user?case=deleteuser" method="post">
                    <article id="delete_test">
                    <div class="content box">

                    <h1>' . _ADMIN_ADD_USER_DELETE_USER . '</h1>
                    ' . _ADMIN_USER_DELETE_ARE_YOU_SURE_1 . '' . $rec[4] . ' ' . _ADMIN_USER_DELETE_ARE_YOU_SURE_2 . '
                    <div class="info_box clearfix">
                    <div class="box_icon" data-icon="y" aria-hidden="true"></div>
                    <div class="content clearfix">
                    <h1>'. _ADMIN_NOTE . '</h1><ul><li>'
                    . _ADMIN_ADD_USER_DELETE_ALL_EXAM_AND_CHOICE_WILL_DELETE . '</li></ul>
                    </div>
                    </div>
                    <input type="hidden" name="uid" value="' . $uid . '">
                    <input type="hidden" name="end" value="">

                    <div class="button_wrap left clearfix">
                    <input class="button bad" type="submit" value="' . _ADMIN_ADD_USER_DELETE_USER . '">
                    <input class="button" type="button" name="bt1" value="' . _ADMIN_FORM_CANCEL . '" onClick="javascript: window.history.go(-1)">
                    </div>

                    </div>
                    <div id="targetDiv">
                    </div>
                    </article>
                    </form>
		        ');
			} else {
				$uid = $_REQUEST["uid"];
                $pars = array(
                    ':uid' => $uid,
                );
				$result = $db->db_query("DELETE FROM users WHERE id=:uid",$pars);

				$result = $db->db_query("SELECT * FROM user_test WHERE user_id =:uid",$pars);
                $recs = $db->resultset();
                foreach ($recs as $i => $rec) {
                    $pars1 = array(
                        ':rec' => $rec[0],
                    );
					$result2 = $db->db_query("DELETE FROM user_choice WHERE user_test_id=:rec",$pars1);
				}
				$result = $db->db_query("DELETE FROM user_test WHERE user_id=:uid",$pars);

				echo('
				<article id="delete_test">
                <div class="content">
                <div class="info_box clearfix" >
                <div class="box_icon" data-icon="y" aria-hidden="true"></div>
                <div class="content clearfix">
                <h1>' . _ADMIN_ADD_USER_DELETE_USER . '</h1>
                <ul><li>' . _ADMIN_ADD_USER_DELETED_USER . '</li></ul>
                </div>
                </div>
                <div id="back" class="button_wrap clearfix">
                <a class="button" id="back_b" href="users"><div data-icon="b" aria-hidden="true" class="grid_img"></div><div class="grid_txt">' . _ADMIN_ADD_EDIT_USER . '</div></a>
                </div>
                </div>
                </article>');
                include('../footer.php');
                include('../footer_end.php');
                die();
			}
		} else if ($case == "newpass") {
			if (!(isset($_REQUEST["end"]))) {


				echo ('<article><div class="content login box">
				<h1 class="title">' . _ADMIN_ADD_USER_RESET_PASSWORD . '</h1>
		        <form action="edit_user?case=newpass" method="post" onSubmit="return CheckForm(this);">

		        <div class="label '. $align .'">' . _ADMIN_ADD_USER_PASSWORD_NEW . ':</div>
                <input type="password" name="new_pass" value="" dir="ltr">

                <div class="label '. $align .'">' . _ADMIN_ADD_USER_CONFIRM_PASSWORD . ':</div>
                <input type="password" name="new_pass_confirm" value="" dir="ltr">

                <input type="hidden" name="uid" value="' . $rec[0] . '">
                <input type="hidden" name="end" value="">
                <div class="button_wrap left clearfix">
				<input class="button" type="submit" value="' . _ADMIN_CONTINUE . '" name="B1">
				<input class="button bad" type=button name=bt1 value="' . _ADMIN_FORM_CANCEL . '" onClick="dosubmit()">
				</div>

				</form>
				</div>
				</article>');

			} else //if (isset ($_REQUEST["end"]))
			{
				$new_pass = $_REQUEST["new_pass"];
				$new_pass_confirm = $_REQUEST["new_pass_confirm"];
				$uid = $_REQUEST["uid"];
                $pars = array(
                    ':uid' => $uid
                );
				$check_uname = $db->db_query("SELECT * FROM users WHERE id=:uid",$pars);
				$rec = $db->single();
                $hash = $t_hasher->HashPassword($new_pass);

				$sqlstring = "UPDATE users SET password=:hash WHERE id=:uid";
                $pars1 = array(
                    ':uid' => $uid,
                    ':hash' => $hash
                );
				$result = $db->db_query($sqlstring, $pars1);

				echo('
				<article class="msg">


                <div class="info_box clearfix">
                    <div class="box_icon" data-icon="y" aria-hidden="true"></div>
                    <div class="content clearfix">
                    <h1>' . _ADMIN_ADD_USER_EDIT_USER . '</h1>
                    <ul><li>' . _ADMIN_ADD_USER_EDITED_PASSWORD_1 . ' ' . $rec[4] . ' ' . _ADMIN_ADD_USER_EDITED_PASSWORD_2 . '.</li></ul>
                    </div>
                </div>

                <div id="back" class="button_wrap clearfix">
                    <a class="button" id="back_b" href="users"><div data-icon="b" aria-hidden="true" class="grid_img"></div>
                    <div class="grid_txt">' . _ADMIN_ADD_EDIT_USER . '</div></a>
                </div>

                </article>');
                include('../footer.php');
                include('../footer_end.php');
                die();
			}
		} else {
			echo('
			<article class="msg">

            <div class="info_box clearfix">
                <div class="box_icon" data-icon="y" aria-hidden="true"></div>
                <div class="content clearfix">' . _ADMIN_NOT_ALLOWED . '!</div>
            </div>

            <div id="back" class="button_wrap clearfix">
                <a class="button" id="back_b" href="../admin"><div data-icon="h" aria-hidden="true" class="grid_img"></div>
                <div class="grid_txt">' . _ADMIN_HOME . '</div></a>
            </div>

            </article>');
            include('../footer.php');
            include('../footer_end.php');
            die();
		}
	} else {
		if (isset($_REQUEST["case"]) && $_REQUEST["case"] == "delete_all_users") {
			if (!(isset($_REQUEST["end"]))) {

				echo ('
				<form action="edit_user?case=delete_all_users" method="post">
				<article id="delete_test">
                    <div class="content box">

                    <h1>' . _ADMIN_ADD_USER_DELETE_USER . '</h1>
                    ' . _ADMIN_DELETE_USERS_ARE_YOU_SURE . '
                    <div class="info_box clearfix">
                    <div class="box_icon" data-icon="y" aria-hidden="true"></div>
                    <div class="content clearfix">
                    <h1>'. _ADMIN_NOTE . '</h1><ul><li>'
                    . _ADMIN_DELETE_USERS_ALL_EXAM_AND_CHOICE_WILL_DELETE . '</li></ul>
                    </div>
                    </div>
                    <input type="hidden" name="end" value="">

                    <div class="button_wrap left clearfix">
                    <input class="button bad" type="submit" value="' . _ADMIN_DELETE_USERS_DELETE_USERS . '">
                    <input class="button" type="button" name="bt1" value="' . _ADMIN_FORM_CANCEL . '" onClick="javascript: window.history.go(-1)">
                    </div>

                    </div>
                </article>
		        </form>');
			} else {

				$result = $db->db_query("TRUNCATE users");

				$result = $db->db_query("TRUNCATE user_test");

				$result = $db->db_query("TRUNCATE user_choice");

				echo('
				<article class="msg">

                <div class="info_box clearfix" >
                    <div class="box_icon" data-icon="y" aria-hidden="true"></div>
                    <div class="content clearfix">
                    <h1>' . _ADMIN_ADD_USER_DELETE_USER . '</h1>
                    <ul><li>' . _ADMIN_DELETE_USERS_DELETED_USERS . '</li></ul>
                    </div>
                </div>

                <div id="back" class="button_wrap clearfix">
                    <a class="button" id="back_b" href="users"><div data-icon="b" aria-hidden="true" class="grid_img"></div>
                    <div class="grid_txt">' . _ADMIN_ADD_EDIT_USER . '</div></a>
                </div>

                </article>');
                include('../footer.php');
                include('../footer_end.php');
                die();
			}
		} else if (isset($_REQUEST["case"]) && $case == "delete_users_test") {
			$test_id = $_REQUEST['test_id'];
			if (!(isset($_REQUEST["end"]))) {
				echo ('
				<form action="edit_user?case=delete_users_test" method="post">
                    <article id="delete_test">
                    <div class="content box">

                    <h1>' . _ADMIN_ADD_USER_DELETE_USER . '</h1>
                    ' . _ADMIN_DELETE_USERS_EXAM_ARE_YOU_SURE . '
                    <div class="info_box clearfix" >
                    <div class="box_icon" data-icon="y" aria-hidden="true"></div>
                    <div class="content clearfix">
                    <h1>'. _ADMIN_NOTE . '</h1><ul><li>'
                    . _ADMIN_DELETE_USERS_EXAM_ALL_CHOICES_WILL_BE_DELETED . '</li></ul>
                    </div>
                    </div>
                    <input type="hidden" name="end" value="">
		            <input type="hidden" name="test_id" value="' . $test_id . '">

                    <div class="button_wrap left clearfix">
                    <input class="button bad" type="submit" value="' . _ADMIN_STATS_DELETE_EXAM_USERS . '">
                    <input class="button" type="button" name="bt1" value="' . _ADMIN_FORM_CANCEL . '" onClick="javascript: window.history.go(-1)">
                    </div>

                    </div>
                    <div id="targetDiv">
                    </div>
                    </article>
                    </form>
                ');
			} else {
				$test_id = $_REQUEST['test_id'];
                $pars = array(
                    ':test_id' => $test_id
                );
				$result = $db->db_query("SELECT * FROM user_test WHERE test_id=:test_id",$pars);
                $recs = $db->resultset();
                foreach ($recs as $i => $rec) {
                    $pars1 = array(
                        ':$rec' => $rec[0]
                    );
					$result2 = $db->db_query("DELETE FROM user_choice WHERE user_test_id=:rec",$pars1);
				}

				$result2 = $db->db_query("DELETE FROM user_test WHERE test_id=:test_id",$pars);

				echo('
				<article class="msg">

                <div class="info_box clearfix" >
                    <div class="box_icon" data-icon="y" aria-hidden="true"></div>
                    <div class="content clearfix">
                    <h1>' . _ADMIN_NOT_ALLOWED . '</h1>
                    <ul><li>' . _ADMIN_DELETED_USERS_EXAM . '.</li></ul>
                    </div>
                </div>

                <div id="back" class="button_wrap clearfix">
                    <a class="button" id="back_b" href="charts"><div data-icon="b" aria-hidden="true" class="grid_img"></div>
                    <div class="grid_txt">' . _ADMIN_CHARTS . '</div></a>
                </div>

                </article>
			');
                include('../footer.php');
                include('../footer_end.php');
                die();
			}
		} else
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
        include('../footer.php');
        include('../footer_end.php');
        die();

	}
}
?>

<?php include('../footer.php');
echo ('<script language = "javascript">
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
	</script>');
    echo ('<script type="text/javascript">
		<!--
		function CheckForm(formID) {
		if (formID.new_pass.value == "") { alert("' . _ADMIN_ADD_USER_ENTER_PASSWORD . '");
		formID.new_pass.focus(); return false; }
		if (formID.new_pass_confirm.value == "") { alert("' . _ADMIN_ADD_USER_ENTER_CONFIRM_PASSWORD . '");
		formID.new_pass_confirm.focus(); return false; }
		if (formID.new_pass.value !== formID.new_pass_confirm.value) { alert("' . _ADMIN_ADD_USER_PASSWORD_AND_CONFIRM_NOT_MATCH . '");
		formID.new_pass_confirm.focus(); return false; }
		return true;
		}
		//-->
		</script> ');

?>
