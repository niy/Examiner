<?php

header("Content-Type: text/html; charset=utf-8");

if (!isset($_COOKIE['examiner'])) {
	header('Location: index');
} else {
	include('admin_config.php');
	$ineachpage=12;

	if (!(isset($_REQUEST["p"]))) {
		$start = 0;
		$finish = $start + $ineachpage;
	} else {
		$start = ($_REQUEST["p"]-1) * $ineachpage;
		$finish = $start + $ineachpage;
	}
    $result2 = $db->db_query("SELECT * FROM tests ORDER BY id DESC");
    $num_users = $db->rowCount();

	$result = $db->db_query("SELECT * FROM tests ORDER BY id DESC LIMIT ".$start.", ".$ineachpage);

	echo ('
	<article id="show_stats">
			<div class="clearfix pagehead">
			<h1>' . _ADMIN_CHARTS_TOTAL . '</h1>
			<a id="find" class="button" href="#" title="' . _ADMIN_FIND . '"><span data-icon="f" aria-hidden="true"></span></a><input type="text" id="filter" placeholder="' . _ADMIN_FIND . '">
			</div>'
	);

	if (!$result) {
		echo('
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
		</article>');
        include ('../footer.php');
        include('../footer_end.php');
        die();
	}

	?>
	<div id="fifteenth">
	</div>

	<?php
	echo ('
	<table data-filter="#filter" class="test_list">
		<thead>
			<tr>
				<th data-class="expand" data-sort-initial="true" scope="col" id="name">' . _EXAM_NAME . '</th>
				<th data-hide="phone,tablet" scope="col" id="q_num">' . _EXAM_NOQ_ADDED . '<a class="bar_icon blue" onMouseover="third2(\'' . _EXAM_ADMIN_NOQ_IN_DB . '\', \'white\', 170)"; onMouseout="first4()"> <span data-icon="?" aria-hidden="true" class="grid_img"></span></a></th>
				<th data-hide="phone,tablet" scope="col" id="q_num_final">' . _EXAM_ADMIN_SHOW_NOQ . '<a class="bar_icon blue" onMouseover="third2(\'' . _EXAM_ADMIN_NOQ_DEPENDS_ON_DB . '\', \'white\', 170)"; onMouseout="first4()"> <span data-icon="?" aria-hidden="true" class="grid_img"></span></a></th>
				<th scope="col" id="examinee">' . _EXAM_ADMIN_MEM_NUMS . '</th>
				<th scope="col" id="cratio">' . _EXAM_ADMIN_AVERAGE . ' <a class="bar_icon blue" onMouseover="third2(\'' . _EXAM_ADMIN_AVE_PERCENT_MINUS . '\', \'white\', 170)"; onMouseout="first4()"> <span data-icon="?" aria-hidden="true" class="grid_img"></span></a></th>
				<th data-hide="phone" scope="col" id="time">' . _EXAM_ADMIN_AVERAGE_TIME . '</th>
				<th scope="col" id="stats">' . _EXAM_ADMIN_TOTAL_STATISTICS . '</th>

			</tr>
		</thead>
		');
	echo ('<tbody>');
	$tr_num = 1;
    $tests_set=$db->resultset();
    foreach ($tests_set as $m => $rec) {
		$ave = 0;
		$all_qs = 0;
		$ave_null = 0;

		if ($rec[3] == 1)
			$Be_Default = _RANDOM_1;
		else
			$Be_Default = _RANDOM_0;

		if ($rec[4] == 1)
			$reg_user = _BY_PROF;
		else
			$reg_user = _BY_USER;

		if ($rec[5] == 1)
			$test_random = _RANDOM_1;
		else
			$test_random = _RANDOM_0;

		if ($rec[7] == 1)
			$test_RTL = _RTL_1;
		else
			$test_RTL = _RTL_0;

		if ($rec[8] == 1)
			$minus_mark = _MINUS_MARK_1;
		else
			$minus_mark = _MINUS_MARK_0;

		if ($rec[9] == 1)
			$show_answers = _ADMIN_MINUS_MARK_1;
		else
			$show_answers = _ADMIN_MINUS_MARK_0;

		if ($rec[10] == 1)
			$show_mark = _ADMIN_MINUS_MARK_1;
		else
			$show_mark = _ADMIN_MINUS_MARK_0;
        $pars = array(':rec'=>$rec[0]);
		$result_noq = $db->db_query("SELECT * FROM questions WHERE test_id=:rec",$pars);
		$show_final_noq = $result_noq = $db->rowCount();
        $pars = array(':rec'=>$rec[0]);
		$result_mem_nums2 = $db->db_query("SELECT * FROM user_test WHERE test_id=:rec",$pars);

		$result_mem_nums = $db->rowCount();

		///Average//////////////////
        $pars = array(':rec'=>$rec[0]);
		$choices = $db->db_query("SELECT * FROM user_test WHERE test_id = :rec",$pars);
        $choices_set3 = $db->resultset();
		foreach ($choices_set3 as $i => $choices3) {
            $pars = array(':choices3'=>$choices3[0]);
			$choices2 = $db->db_query("SELECT * FROM user_choice WHERE user_test_id = :choices3",$pars);
            $choices_set2 = $db->resultset();
            foreach ($choices_set2 as $j => $reec) {
                $pars = array(':reec'=>$reec[2]);
				$question = $db->db_query("SELECT * FROM questions WHERE id = :reec",$pars);
				$question = $db->single();

				if ($question[7] == $reec[3]) {
					$ave++;
				}
				if ($reec[3] == "") {
					$ave_null++;
				}
				$all_qs++;
			}
		}

		if (!$all_qs == 0) {
			if ($rec[8] == 1)
				$ave = round((($ave - round((($all_qs - $ave_null - $ave) / 3), 2)) / $all_qs), 2) * 100;
			else
				$ave = round(($ave / $all_qs), 2) * 100;
		}
        $pars = array(':rec'=>$rec[0]);
		$ave_time = $db->db_query("SELECT AVG(time_length) FROM user_test WHERE test_id = :rec",$pars);
		$ave_time = $db->single();

		/////////////////////////////////////////////////////////////

		if ($show_final_noq > $rec[2])
			$show_final_noq = $rec[2];

		if ($tr_num % 2 == 0)
			$tr_class = 'even';
		else
			$tr_class = '';


		echo ('
		<tr class="'.$tr_class.'">
			<td>' . $rec[1] . '
            <a class="bar_icon blue info_icon" onMouseover="third2(\'<table class=inf><tbody><tr><td>'
            . _EXAM_NOQ . '</td><td class=even>'. $rec[2] . '</td></tr><tr class=even><td>'
            . _EXAM_TIME . '</td><td class=even>' . $rec[6] . '</td></tr><tr><td>'
            . _EXAM_BE_DEFAULT . '</td><td class=even>' . $Be_Default. '</td></tr><tr class=even><td>'
            . _EXAM_REGISTRAR . '</td><td class=even>' . $reg_user . '</td></tr><tr><td>'
            . _EXAM_RANDOM . '</td><td class=even>' . $test_random . '</td></tr><tr class=even><td>'
            . _EXAM_ALIGNMENT . '</td><td class=even>' . $test_RTL . '</td></tr><tr><td>'
            . _EXAM_MINUS_MARK . '</td><td class=even>' . $minus_mark . '</td></tr><tr class=even><td>'
            . _EXAM_SHOW_ANSWERS . '</td><td class=even>' . $show_answers. '</td></tr><tr><td>'
            . _EXAM_SHOW_MARK . '</td><td class=even>' . $show_mark. '</td></tr></tbody></table>\', \'white\', 170)"; onMouseout="first4()"><span data-icon="y" aria-hidden="true" class="grid_img"></span></a>
            </td>

			<td>' . $result_noq
			. ' <a class="bar_icon blue info_icon" href="questions?tid=' . $rec[0] . '" title="' . _EXAM_ADMIN_SHOW_ALL_QUESTIONS. '"><span data-icon="y" aria-hidden="true" class="grid_img"></span></a>
			</td>

			<td>
			' . $show_final_noq
			. '
			</td>

			<td>
			' . $result_mem_nums
			. ' <a class="bar_icon blue info_icon" href="statistics?case=u&test_id=' . $rec[0] . '" title="'. _EXAM_ADMIN_SHOW_USERS . '"><span data-icon="y" aria-hidden="true" class="grid_img"></span></a>
			</td>

			<td>
			' . $ave
			. '%
			</td>

			<td>' . round($ave_time[0], 2)
			. '
			</td>

			<td class="stats">
			<a class="bar_icon blue stats_icon" title="' . _EXAM_ADMIN_TOTAL_STATISTICS_2
			. '" href="statistics?case=q&test_id=' . $rec[0]
			. '"><span data-icon="c" aria-hidden="true" class="grid_img"></span></a>
			</td>
			</tr>');
		$tr_num++;
	}

	echo ('</tbody></table>');

    /**Pagination**/
    $page=1;
    if(isset($_GET['p']) && $_GET['p']!=''){
        $page=$_GET['p'];
    }
    echo pagination($ineachpage,$page,'?p=',$num_users);
    /**</Pagination>**/

    /*if ($num_users > $ineachpage) //Pagination
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
    }*/

    echo ('</article>');
}
include('../footer.php');
include('../footer_end.php');
?>
