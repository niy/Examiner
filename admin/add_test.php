<?php

header("Content-Type: text/html; charset=utf-8");

if (!isset($_COOKIE['examiner'])) {
    header('Location: index');
} else {
    include('admin_config.php');

    echo ('
        <article id="add_test">
        <div class="content box">
        <h1 class="title" style="margin-bottom: .2em;">' . _ADMIN_ADD_EXAM. '</h1>
        <h2 class="title">'. _ADMIN_ADD_EXAM_FIRST_PROPERTIES . '</h2>
		<form action="add_question" method="post" onSubmit="return CheckForm(this);">

		<div class="label '. $align .'">' . _ADMIN_TITLE . ':</div>
		<input type="text" name="TName" value="" dir="'. $rtl_input . '">

        <div class="label '. $align .'">'. _ADMIN_NOQ . ':</div>
		<input type="text" name="NOQ" value="" dir="left">

		<div class="label '. $align .'">' . _ADMIN_EXAM_TIME . ' (' . _ADMIN_TIME_MINUTE . '):</div>
		<input type="text" name="time" value="" dir="ltr">

		<div class="label '. $align .'">' . _ADMIN_PROF_OR_USER . '</div><ul class="'. $align .'"><li><label for="prof">'
        . _ADMIN_PROF_OR_USER_1 . '</label><input type="radio" value="1" checked id="prof" name="Prof_or_User"></li><li><label  for="user">'
        . _ADMIN_PROF_OR_USER_0 . '</label><input type="radio" id="user" name="Prof_or_User" value="0"></li></ul>

        <div class="label '. $align .'">' . _ADMIN_BE_DEFAULT
        . '</div>');

    $result_check_default = $db->db_query("SELECT * FROM tests WHERE `be_default` = '1'");
    $result_check_default_num = $db->rowCount();

    if ($result_check_default_num > 0) {
        $result_check_default = $db->single();
        $is_default = $result_check_default[1];

        echo ('
        <div class="info_box clearfix">
        <div class="box_icon" data-icon="y" aria-hidden="true"></div>
        <div class="content clearfix">' . _ADMIN_THIS_IS_DEFAULT . ' (<b>'
            . $is_default . '</b>) ' . _ADMIN_THIS_IS_DEFAULT_REMAIMED . '</div>
        </div>
        ');
    }

    echo ('
        <ul class="'. $align .'">
        <li><label for="d_y">' . _ADMIN_BE_DEFAULT_1 . '</label>
		<input type="radio" value="1" checked id="d_y" name="Be_Default"></li>

		<li><label  for="d_n">' . _ADMIN_BE_DEFAULT_0 . '</label>
		<input type="radio" id="d_n" name="Be_Default" value="0"></li></ul>

		<div class="label '. $align .'">' . _ADMIN_RANDOM . '</div>
        <ul class="'. $align .'">
		<li><label for="r_y">' . _ADMIN_RANDOM_1 . '</label>
		<input type="radio" value="1" checked id="r_y" name="random"></li>

		<li><label for="r_n">' . _ADMIN_RANDOM_0 . '</label>
		<input type="radio" id="r_n" name="random" value="0"></li></ul>

		<div class="label '. $align .'">' . _ADMIN_RTL . '</div>
        <ul class="'. $align .'">
		<li><label for="rtl_y">' . _ADMIN_RTL_1 . '</label>
		<input type="radio" value="1" checked id="rtl_y" name="rtl"></li>

		<li><label for="rtl_n">' . _ADMIN_RTL_0 . '</label>
		<input type="radio" id="rtl_n" name="rtl" value="0"></li></ul>

		<div class="label '. $align .'">' . _ADMIN_MINUS_MARK . '</div>
        <ul class="'. $align .'">
		<li><label for="m_y">' . _ADMIN_MINUS_MARK_1 . '</label>
		<input type="radio" value="1" checked id="m_y" name="Minus_Mark"></li>

		<li><label for="m_n">' . _ADMIN_MINUS_MARK_0 . '</label>
		<input type="radio" id="m_n" name="Minus_Mark" value="0"></li></ul>

		<div class="label '. $align .'">' . _ADMIN_SHOW_ANSWERS . '</div>
        <ul class="'. $align .'">
		<li><label for="s_y">' . _ADMIN_MINUS_MARK_1 . '</label>
		<input type="radio" value="1" checked id="s_y" name="Show_Answers"></li>

		<li><label for="s_n">' . _ADMIN_MINUS_MARK_0 . '</label>
		<input type="radio" id="s_n" name="Show_Answers" value="0"></li></ul>

		<div class="label '. $align .'">' . _ADMIN_SHOW_MARK . '</div>
        <ul class="'. $align .'">
		<li><label for="sm_y">' . _ADMIN_MINUS_MARK_1 . '</label>
		<input type="radio" value="1" checked id="sm_y" name="Show_Mark"></li>

		<li><label for="sm_n">' . _ADMIN_MINUS_MARK_0 . '</label>
		<input type="radio" id="sm_n" name="Show_Mark" value="0"></li></ul>

		<input type="hidden" name="case1" value="">

		<div class="button_wrap left clearfix">
		<input class="button" type="submit" value="' . _ADMIN_ADD_EXAM_NEXT_PAGE . '">
		<input class="button bad" type=button name=bt1 value="' . _ADMIN_FORM_CANCEL . '" onClick="dosubmit()">
		</div>

		</form>
		</div>
        </article>');
}
?>

<?php include('../footer.php');
echo ('
<script type="text/javascript">
    function dosubmit() {
        document.forms[0].action = "index"
        document.forms[0].method = "POST"
        document.forms[0].submit()
    }
</SCRIPT>
<script type="text/javascript">
    function CheckForm(formID) {
        if (formID.TName.value == "") { alert("' . _ADMIN_ADD_EXAM_ENTER_TNAME . '");
            formID.TName.focus(); return false; }
        if (formID.NOQ.value == "") { alert("' . _ADMIN_ADD_EXAM_ENTER_NOQ . '");
            formID.NOQ.focus(); return false; }
        if (formID.NOQ.value < 1) { alert("' . _ADMIN_ADD_EXAM_MORE_NOQ . '");
            formID.NOQ.focus(); return false; }
        if (formID.time.value == "") { alert("' . _ADMIN_ADD_EXAM_ENTER_TIME . '");
            formID.time.focus(); return false; }
        if (formID.time.value < 1) { alert("' . _ADMIN_ADD_EXAM_MORE_TIME . '");
            formID.time.focus(); return false; }
        return true;
    }
</script>
');
include('../footer_end.php');
?>