<?php

header("Content-Type: text/html; charset=utf-8");

if (!isset($_COOKIE['examiner'])) {
    header('Location: index');
} else {
    include('admin_config.php');

    if (!(isset($_REQUEST["tid"]))) {
        $ineachpage = "30";

        if (!(isset($_REQUEST["start"]))) {
            $start = 0;
            $finish = $start + $ineachpage;
        } else {
            $start = $_REQUEST["start"];
            $finish = $start + $ineachpage;
        }
        $result = mysql_query("SELECT * FROM tests ORDER BY id LIMIT $start,$ineachpage", $db);
        $result2 = mysql_query("SELECT * FROM tests ORDER BY id DESC", $db);
        $num_users = mysql_num_rows($result2);

        ///////////////////////
        echo ('
	        <article id="set_default">
			<div class="clearfix pagehead">
			<h1>' . _ADMIN_DEFINE_DEFAULT . '</h1>
			</div>'
        );

        if (!$result) {
            die('Database query error:' . mysql_error());
        }

        if (!$rec = mysql_fetch_row($result)) {
            die('
                    <div class="clearfix pagehead">
                        <h1>' . _ADMIN_SHOWW_ALL_EXAMS . '</h1>
                        <a id="add_test_b" class="button good" href="add_test" title="' . _ADMIN_INDEX_ADD_EXAM . '"><span data-icon="a" aria-hidden="true"></span></a>
                        </div><div class="content">
                            <div class="info_box clearfix" >
                                <div class="box_icon" data-icon="y" aria-hidden="true"></div>
                                <div class="content clearfix">
                                ' . _ADMIN_NO_EXAM_FOUND . '
                                </div>
                            </div>
                        </div>
                    </article>
                    <footer><p>&copy; Copyright 2013 Mohammad Ali Karimi. All rights reserved.</p></footer>
                    </div></body></html>');
        }
        echo ('
	    <table class="test_list">
		<thead>
			<tr>
			    <th scope="col" id="is_def"></th>
				<th scope="col" id="name">' . _EXAM_NAME . '</th>
				<th scope="col" id="def">' . _EXAM_BE_DEFAULT . '</th>
			</tr>
		</thead>
		');
        echo ('<tbody>');
        $tr_num = 1;
        $ds="";
        do {
            if ($rec[3] == 1) {
                $d_class="correct_q";
                $d_sign="correct_sign";
                $ds= '<span data-icon="d" aria-hidden="true"></span>';
                $Be_Default =
                    '<a class="bar_icon delete" title="' . _DEFAULT_NOT_BE_BEFAULT . '" href="default?tid=' . $rec[0]. '&no"><span data-icon="v" aria-hidden="true" class="grid_img"></span></a>';
            }
            else {
                $d_class="";
                $d_sign="";
                $ds= '';
                $Be_Default =
                    '<a class="bar_icon add" title="' . _DEFAULT_BE_BEFAULT . '" href="default?tid=' . $rec[0]. '"><span data-icon="d" aria-hidden="true" class="grid_img"></span></a>';
            }
            if ($tr_num % 2 == 0)
                $tr_class = 'even';
            else
                $tr_class = '';
            echo ('
            <tr class="'.$tr_class.' '. $d_class . '">
                <td class="t_f '. $d_sign .'">' . $ds . '</td>
				<td>' . $rec[1] . '</td>
				<td class="def">' . $Be_Default . '</td>
			</tr>');
//	echo ("<td align=\"right\" bgcolor=\"#F1F3F8\"><font face=\"Tahoma\" style=\"font-size: 9pt\">".$rec[2]."</font></td>");
            $tr_num++;
        } while ($rec = mysql_fetch_row($result));

        echo ('</tbody></table>');
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

        echo ('</article>');
    } else //if(isset($_REQUEST["tid"]))
    {
        if (isset($_REQUEST["no"])) {
            $tid = $_REQUEST["tid"];
            $edit_test = "UPDATE tests SET Be_Default=0 WHERE id=$tid";
            $edit_test = mysql_query($edit_test, $db);

            if (!$edit_test)
                die('Database query error:' . mysql_error());
            die('
            <article class="msg">

            <div class="info_box clearfix" >
            <div class="box_icon" data-icon="y" aria-hidden="true"></div>
            <div class="content clearfix">
            <h1>' . _ADMIN_EDIT_EXAM . '</h1>
            <ul><li>' . _ADMIN_EXAM_EDITED . '</li></ul>
            </div>
            </div>
            <div id="back" class="button_wrap clearfix">
            <a class="button" id="back_b" href="../admin"><div data-icon="b" aria-hidden="true" class="grid_img"></div><div class="grid_txt">' . _ADMIN_HOME . '</div></a>
            </div>

            </article><footer><p>&copy; Copyright 2013 Mohammad Ali Karimi. All rights reserved.</p></footer></div></body></html>
		    ');
        } else {
            $tid = $_REQUEST["tid"];
            $sqlstring = "UPDATE tests SET Be_Default='0'";
            $result = mysql_query($sqlstring, $db);

            if (!$result) {
                die('Database query error:' . mysql_error());
            }
            $edit_test = "UPDATE tests SET Be_Default=1 WHERE id=$tid";
            $edit_test = mysql_query($edit_test, $db);

            if (!$edit_test)
                die('Database query error:' . mysql_error());
            die('
            <article class="msg">

            <div class="info_box clearfix" >
            <div class="box_icon" data-icon="y" aria-hidden="true"></div>
            <div class="content clearfix">
            <h1>' . _ADMIN_EDIT_EXAM . '</h1>
            <ul><li>' . _ADMIN_EXAM_EDITED . '</li></ul>
            </div>
            </div>
            <div id="back" class="button_wrap clearfix">
            <a class="button" id="back_b" href="../admin"><div data-icon="b" aria-hidden="true" class="grid_img"></div><div class="grid_txt">' . _ADMIN_HOME . '</div></a>
            </div>

            </article><footer><p>&copy; Copyright 2013 Mohammad Ali Karimi. All rights reserved.</p></footer></div></body></html>
		    ');
        }
    }
}
?>

<?php include('../footer.php');?>