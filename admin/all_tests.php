<?php
function p_round ($num){
    if ($num > 0)
        $x = $num - floor($num);
    else
        $x = $num;
    return (($x = 0) ? round($num,0,PHP_ROUND_HALF_DOWN) : round($num,0,PHP_ROUND_HALF_DOWN) + 1);
}
header("Content-Type: text/html; charset=utf-8");

if (!isset($_COOKIE['examiner']))
    {
    header('Location: index');
    }
else
    {
    include('admin_config.php');
    $ineachpage="12";

    if (!(isset($_REQUEST["start"])))
        {
        $start=0;
        $finish=$start + $ineachpage;
        }
    else
        {
        $start=$_REQUEST["start"];
        $finish=$start + $ineachpage;
        }
    $result=mysql_query("select * from tests ORDER BY id DESC LIMIT $start,$ineachpage", $db);
    $result2=mysql_query("select * from tests ORDER BY id DESC", $db);
    $num_users=mysql_num_rows($result2);
/*******************/

    if (!$result)
        {
        die('Database query error:' . mysql_error());
        }

    if (!$rec=mysql_fetch_row($result))
        {
        echo('
        <article id="show_tests"><div class="clearfix pagehead">
            <h1>' . _ADMIN_SHOWW_ALL_EXAMS . '</h1>
            <a id="add_test_b" class="button good" href="edit_test" title="' . _ADMIN_INDEX_ADD_EXAM . '"><span data-icon="a" aria-hidden="true"></span></a>
            </div><div class="content">
                <div class="info_box clearfix" >
                    <div class="box_icon" data-icon="y" aria-hidden="true"></div>
                    <div class="content clearfix">
                    ' . _ADMIN_NO_EXAM_FOUND . '
                    </div>
                </div>
            </div>
		</article>
		');
        include('../footer.php');
        include('../footer_end.php');
        die();
        }
    echo ('<article id="show_tests">
            <div class="clearfix pagehead">
            <h1>' . _ADMIN_SHOWW_ALL_EXAMS . '</h1>
            <a id="add_test_b" class="button" href="add_test" title="' . _ADMIN_INDEX_ADD_EXAM . '"><span data-icon="a" aria-hidden="true"></span></a>
            <a id="find" class="button" href="#" title="' . _ADMIN_FIND . '"><span data-icon="f" aria-hidden="true"></span></a><input type="text" id="filter" placeholder="' . _ADMIN_FIND . '">
            </div>
            ');

    echo ('
	<table data-filter="#filter" class="test_list">
        <thead>
            <tr>
                <th data-class="expand" data-sort-initial="true" scope="col" id="name">' . _EXAM_NAME . '</th>
                <th scope="col" id="q_num">' . _EXAM_NOQ_ADDED . '</th>
                <th data-hide="phone" scope="col" id="q_num_final">' . _EXAM_NOQ . '</th>
                <th scope="col" id="time">' . _EXAM_TIME . '</th>
                <th data-hide="phone" scope="col" id="def">' . _EXAM_BE_DEFAULT . '</th>
                <th data-hide="phone,tablet" scope="col" id="register">' . _EXAM_REGISTRAR . '</th>
                <th data-hide="phone,tablet" scope="col" id="rand">' . _EXAM_RANDOM . '</th>
                <th data-hide="phone,tablet" scope="col" id="align">' . _EXAM_ALIGNMENT . '</th>
                <th data-hide="phone,tablet" scope="col" id="minus">' . _EXAM_MINUS_MARK . '</th>
                <th data-hide="phone,tablet" scope="col" id="show_ans">' . _EXAM_SHOW_ANSWERS . '</th>
                <th data-hide="phone,tablet" scope="col" id="show_mark">' . _EXAM_SHOW_MARK . '</th>
                <th scope="col" id="tools">' . _EXAM_TOOLS . '</th>

            </tr>
        </thead>
		');

    echo ('<tbody>');
	$tr_num = 1;
    do
        {
        if ($rec[3] == 1)
            $Be_Default='<span data-icon="d" aria-hidden="true"></span>';
        else
            $Be_Default='-';

        if ($rec[4] == 1)
            $reg_user=_BY_PROF;
        else
            $reg_user=_BY_USER;

        if ($rec[5] == 1)
            $test_random=_RANDOM_1;
        else
            $test_random=_RANDOM_0;

        if ($rec[7] == 1)
            $test_RTL=_RTL_1;
        else
            $test_RTL=_RTL_0;

        if ($rec[8] == 1)
            $minus_mark=_MINUS_MARK_1;
        else
            $minus_mark=_MINUS_MARK_0;
			
		if ($rec[9] == 1)
            $show_answers=_ADMIN_MINUS_MARK_1;
        else
            $show_answers=_ADMIN_MINUS_MARK_0;
			
		if ($rec[10] == 1)
            $show_mark=_ADMIN_MINUS_MARK_1;
        else
            $show_mark=_ADMIN_MINUS_MARK_0;
			
        $result_noq=mysql_query("select * from questions where test_id=$rec[0]", $db);
        $result_noq=mysql_num_rows($result_noq);
        if ($tr_num%2==0) //TEEEEEMPPPPPP 561516654444&@GUG765r67RI&KGY^EQK*&@G#K*QH@*&HE*&@H*(*&&
		$tr_class = 'even';
		else
        $tr_class = '';
        echo ('<tr class="'.$tr_class.'">
				<td>' . $rec[1] . '</td>
				<td>' . $result_noq . '</td>
				<td>' . $rec[2] . '</td>
				<td>' . $rec[6] . '</td>
				<td>' . $Be_Default . '</td>
				<td>' . $reg_user . '</td>
				<td>' . $test_random . '</td>
				<td>' . $test_RTL . '</td>
				<td>' . $minus_mark . '</td>
				<td>' . $show_answers . '</td>
				<td>' . $show_mark . '</td>
				<td>
				<a class="bar_icon modify" href="edit_test?tid=' . $rec[0] . '" title="' . _ADMIN_EDIT_EXAM_PROP . '"><span data-icon="m" aria-hidden="true" class="grid_img"></span></a>
				<a class="bar_icon modify_q" href="questions?tid=' . $rec[0] . '" title="' . _ADMIN_EDIT_QUESTIONS . '"><span data-icon="n" aria-hidden="true" class="grid_img"></span></a>
				<a class="bar_icon add" href="add_question?case=addq&tid=' . $rec[0] . '" title="' . ADMIN_ADD_Q_TO_EXAM . '"><span data-icon="q" aria-hidden="true" class="grid_img"></span></a>
				<a class="bar_icon delete" href="delete_test?tid=' . $rec[0] . '" title="' . ADMIN_DELETE_EXAM . '"><span data-icon="r" aria-hidden="true" class="grid_img"></span></a>
				</td>
			</tr>');
			$tr_num++;

        } while ($rec=mysql_fetch_row($result));

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
    }
?>

<?php include('../footer.php');
include('../footer_end.php');
?>