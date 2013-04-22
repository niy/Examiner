<?php

require_once ('../config.php');
include('../main_admin.php');

if (isset($_COOKIE['examiner']))
    {
        echo ('
        <nav class="admin_menu">
            <ul>
                <li><a title="'. _ADMIN_HOME. '" href="../admin"><span data-icon="h" aria-hidden="true"></span></a></li>
                <li><a title="'. _ADMIN_INDEX_ADD_EXAM. '" href="add_test"><span data-icon="a" aria-hidden="true"></span></a></li>
                <li><a title="'. _ADMIN_EDIT_EXAMS. '" href="all_tests"><span data-icon="t" aria-hidden="true"></span></a></li>
                <li><a title="'. _ADMIN_DEFINE_DEFAULT. '" href="default"><span data-icon="d" aria-hidden="true"></span></a></li>
                <li><a title="'. _ADMIN_ADD_EDIT_USER. '" href="users"><span data-icon="u" aria-hidden="true"></span></a></li>
                <li><a title="'. _ADMIN_CHARTS. '" href="charts"><span data-icon="c" aria-hidden="true"></span></a></li>
                <li><a title="'. _ADMIN_SETTINGS . '" href="settings"><span data-icon="s" aria-hidden="true"></span></a></li>
                <li><a title="' ._ALTLOGOUT. '" href="logout"><span data-icon="l" aria-hidden="true"></span></a></li>
            </ul>
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