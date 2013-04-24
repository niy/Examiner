<?php

require_once ('../config.php');
include('../main_admin.php');

if (isset($_COOKIE['examiner']))
    {
        echo ('
        <nav class="admin_menu">
            <ul>
                <li><a title="'. _ADMIN_HOME. '" href="../admin"><span data-icon="h" class="menu_ico" aria-hidden="true"></span><div class="menu_txt">'. _ADMIN_HOME. '</div></a></li>
                <li><a title="'. _ADMIN_INDEX_ADD_EXAM. '" href="add_test"><span data-icon="a" class="menu_ico" aria-hidden="true"></span><div class="menu_txt">'. _ADMIN_INDEX_ADD_EXAM. '</div></a></li>
                <li><a title="'. _ADMIN_EDIT_EXAMS. '" href="all_tests"><span data-icon="t" class="menu_ico" aria-hidden="true"></span><div class="menu_txt">'. _ADMIN_EDIT_EXAMS. '</div></a></li>
                <li><a title="'. _ADMIN_DEFINE_DEFAULT. '" href="default"><span data-icon="d" class="menu_ico" aria-hidden="true"></span><div class="menu_txt">'. _ADMIN_DEFINE_DEFAULT. '</div></a></li>
                <li><a title="'. _ADMIN_ADD_EDIT_USER. '" href="users"><span data-icon="u" class="menu_ico" aria-hidden="true"></span><div class="menu_txt">'. _ADMIN_ADD_EDIT_USER. '</div></a></li>
                <li><a title="'. _ADMIN_CHARTS. '" href="charts"><span data-icon="c" class="menu_ico" aria-hidden="true"></span><div class="menu_txt">'. _ADMIN_CHARTS. '</div></a></li>
                <li><a title="'. _ADMIN_SETTINGS . '" href="settings"><span data-icon="s" class="menu_ico" aria-hidden="true"></span><div class="menu_txt">'. _ADMIN_SETTINGS. '</div></a></li>
                <li><a title="' ._ALTLOGOUT. '" href="logout"><span data-icon="l" class="menu_ico" aria-hidden="true"></span><div class="menu_txt">'. _ALTLOGOUT. '</div></a></li>
            </ul>
            <a href="#" id="pull" title="Menu"><span data-icon="=" aria-hidden="true"></span></a>
        </nav>
        </header>');
    }
else {
    echo ('</header>');
}
$result_rtl=mysql_query("select * from settings where id = '1'", $db);
$rtl_array=mysql_fetch_row($result_rtl);

if ($rtl_array[4] == 1)
    {
    $align="right";
    }
else
    {
    $align="left";
    }
?>