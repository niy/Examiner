<?php

header("Content-Type: text/html; charset=utf-8");

if (!isset($_COOKIE['examiner'])) {
    header('Location: index');
} else {
    include('admin_config.php');
    $ineachpage = "12";

    if (!(isset($_REQUEST["p"]))) {
        $start = 0;
        $finish = $start + $ineachpage;
    } else {
        $start = ($_REQUEST["p"]-1) * $ineachpage;
        $finish = $start + $ineachpage;
    }
    $result = mysql_query("SELECT * FROM users ORDER BY id LIMIT $start,$ineachpage", $db);
    $result2 = mysql_query("SELECT * FROM users ORDER BY id DESC", $db);
    $num_users = mysql_num_rows($result2);

    ///////////////////////

    echo ('
        <article id="show_users">
        ');

    if (!$result) {
        die('Database query error:' . mysql_error());
    }

    if (!$rec = mysql_fetch_row($result)) {
        echo('
            <div class="clearfix pagehead">
            <h1>' . _ADMIN_SHOW_ALL_USERS . '</h1>
            <a id="add_test_b" class="button good" href="users?case=adduser" title="' . _ADMIN_ADD_USER . '"><span data-icon="i" aria-hidden="true"></span></a>
            </div>
            <div class="content">
                <div class="info_box clearfix" >
                    <div class="box_icon" data-icon="y" aria-hidden="true"></div>
                    <div class="content clearfix">
                    ' . _ADMIN_NO_USER_FOUND . '
                    </div>
                </div>
            </div>
            </article>
            ');
        include ('../footer.php');
        include('../footer_end.php');
        die();
    }

    echo ('
        <div class="clearfix pagehead">
        <h1>' . _ADMIN_ALL_USERS . '</h1>
        <a id="delete_all_b" class="button bad" href="edit_user?case=delete_all_users" title="' . _ADMIN_DELETE_ALL_USERS . '"><span data-icon="!" aria-hidden="true"></span></a>
        <a id="add_test_b" class="button" href="users?case=adduser" title="' . _ADMIN_ADD_USER . '"><span data-icon="i" aria-hidden="true"></span></a>
        <a id="find" class="button" href="#" title="' . _ADMIN_FIND . '"><span data-icon="f" aria-hidden="true"></span></a><input type="text" id="filter" placeholder="' . _ADMIN_FIND . '">
        </div>

        <table data-filter="#filter" class="test_list">
        <thead>
            <tr>
                <th data-class="expand" data-sort-initial="true" scope="col" id="lname">' . _ADMIN_ADD_USER_LAST_NAME . '</th>
                <th scope="col" id="fname">' . _ADMIN_ADD_USER_NAME . '</th>
                <th data-hide="phone,tablet" scope="col" id="father">' . _ADMIN_ADD_USER_FATHER_NAME . '</th>
                <th scope="col" id="uname">' . _ADMIN_ADD_USER_USER_ID . '</th>
                <th data-hide="phone" scope="col" id="email">' . _ADMIN_ADD_USER_EMAIL . '</th>
                <th scope="col" id="tools">' . _EXAM_TOOLS . '</th>
            </tr>
        </thead>
    ');
    echo ('<tbody>');

    $tr_num = 1;
    do {

        if ($tr_num % 2 == 0)
            $tr_class = 'even';
        else
            $tr_class = '';

        echo ('
                <tr class="'.$tr_class.'">
                    <td>' . $rec[2] . '</td>
                    <td>' . $rec[1] . '</td>
                    <td>' . $rec[3] . '</td>
                    <td>' . $rec[4] . '</td>
                    <td>' . $rec[6] . '</td>
                    <td>
                    <a class="bar_icon modify" href="edit_user?case=edituser&uid=' . $rec[0] . '" title="' . _ADMIN_ADD_USER_EDIT_USER . '"><span data-icon="m" aria-hidden="true" class="grid_img"></span></a>
                    <a class="bar_icon modify_q" href="edit_user?case=newpass&uid=' . $rec[0] . '" title="' . _ADMIN_ADD_USER_RESET_PASSWORD . '"><span data-icon="k" aria-hidden="true" class="grid_img"></span></a>
                    <a class="bar_icon delete" href="edit_user?case=deleteuser&uid=' . $rec[0] . '" title="' . _ADMIN_ADD_USER_DELETE_USER . '"><span data-icon="r" aria-hidden="true" class="grid_img"></span></a>
                    </td>
			    </tr>
			');
        $tr_num++;
    } while ($rec = mysql_fetch_row($result));

    echo ('</tbody></table>');

    /**Pagination**/
    $page=1;
    if(isset($_GET['p']) && $_GET['p']!=''){
        $page=$_GET['p'];
    }
    echo pagination($ineachpage,$page,'?p=',$num_users);
    /**</Pagination>**/

    /*
        if ($num_users > $ineachpage) //Pagination
        {
            echo ('<ul class="content pagination" style="width: '. p_round($num_users / $ineachpage) * 2.6 .'em;">');
            $page_number=0;

            for ($x=0; $x < $num_users / $ineachpage; $x++)
            {
                $y = $x * $ineachpage;
                $page_number++;
                $iif=$finish / $ineachpage;

                if ($x + 1 == $iif)
                    echo ('<li id="current_page" class="page_num">' . $page_number . '</li>');
                else
                    echo ('<a href="?start=' . $y . '"><li class="page_num">' . $page_number . '</li></a>');
            }
            echo ('</ul>');
        }
    */
    echo ('</article>');
}
?>

<?php include('../footer.php');
include('../footer_end.php');
?>