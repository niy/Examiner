<?php
/*
function p_round($num)
{
    if ($num > 0)
        $x = $num - floor($num);
    else
        $x = $num;
    return (($x = 0) ? round($num, 0, PHP_ROUND_HALF_DOWN) : round($num, 0, PHP_ROUND_HALF_DOWN) + 1);
}*/

if (!isset($_COOKIE['examiner'])) {
    header('Location: index');
}
include('admin_config.php');
?>
<div id="fifteenth"></div>
<?php

if (isset($_REQUEST["case"])) {
    $case = $_REQUEST["case"];
    $test_id = $_REQUEST["test_id"];
    $pars = array(
        ':test_id' => $test_id
    );
    $test_prop = $db->db_query("SELECT * FROM tests WHERE id=:test_id",$pars);
    $test_prop = $db->single();
    $test_id_q = $test_prop[0];

    if ($test_prop[3] == 1)
        $Be_Default = _RANDOM_1;
    else
        $Be_Default = _RANDOM_0;

    if ($test_prop[4] == 1)
        $reg_user = _BY_PROF;
    else
        $reg_user = _BY_USER;

    if ($test_prop[5] == 1)
        $test_random = _RANDOM_1;
    else
        $test_random = _RANDOM_0;

    if ($test_prop[7] == 1)
        $test_RTL = _RTL_1;
    else
        $test_RTL = _RTL_0;

    if ($test_prop[8] == 1)
        $minus_mark = _MINUS_MARK_1;
    else
        $minus_mark = _MINUS_MARK_0;

    if ($test_prop[9] == 1)
        $show_answers = _ADMIN_MINUS_MARK_1;
    else
        $show_answers = _ADMIN_MINUS_MARK_0;

    if ($test_prop[10] == 1)
        $show_mark = _ADMIN_MINUS_MARK_1;
    else
        $show_mark = _ADMIN_MINUS_MARK_0;


    if ($case == "u") {
        $pars = array(
            ':test_id' => $test_id
        );
        $select_users = $db->db_query("SELECT * FROM user_test WHERE test_id=:test_id",$pars);
        $ineachpage=12;

        if (!(isset($_REQUEST["p"]))) {
            $start = 0;
            $finish = $start + $ineachpage;
        } else {
            $start = ($_REQUEST["p"]-1) * $ineachpage;
            $finish = $start + $ineachpage;
        }
        $pars = array(
            ':test_id' => $test_id
        );
        $num_users = $db->db_query("SELECT * FROM user_test WHERE test_id=:test_id ORDER BY id",$pars);
        $num_users = $db->rowCount();
        $pars = array(
            ':test_id' => $test_id
        );
        $result = $db->db_query("SELECT * FROM user_test WHERE test_id=:test_id ORDER BY id LIMIT ".$start.", ".$ineachpage,$pars);

        echo ('
        	<article id="show_stats">
			<div class="clearfix pagehead">
            <h1>' . _ADMIN_CHARTS_LIST_USER_EXAM . ': ' . $test_prop[1] . '</h1><a class="bar_icon blue info_icon" onMouseover="third2(\'<table class=inf><tbody><tr><td>'
            . _EXAM_NAME . '</td><td class=even>' . $test_prop[1] . '</td></tr><tr class=even><td>'
            . _EXAM_TIME . '</td><td class=even>' . $test_prop[6] . '</td></tr><tr><td>'
            . _EXAM_BE_DEFAULT . '</td><td class=even>' . $Be_Default . '</td></tr><tr class=even><td>'
            . _EXAM_REGISTRAR . '</td><td class=even>' . $reg_user . '</td></tr><tr><td>'
            . _EXAM_RANDOM . '</td><td class=even>' . $test_random . '</td></tr><tr class=even><td>'
            . _EXAM_ALIGNMENT . '</td><td class=even>' . $test_RTL . '</td></tr><tr><td>'
            . _EXAM_MINUS_MARK . '</td><td class=even>' . $minus_mark . '</td></tr><tr class=even><td>'
            . _EXAM_SHOW_ANSWERS . '</td><td class=even>' . $show_answers . '</td></tr><tr><td>'
            . _EXAM_SHOW_MARK . '</td><td class=even>' . $show_mark . '</td></tr></tbody></table>\', \'white\', 250)"; onMouseout="first4();">
            <span data-icon="y" aria-hidden="true" class="grid_img"></span></a>
            <a id="delete_all_b" class="button bad" href="edit_user?case=delete_users_test&test_id=' . $test_prop[0] . '" title="' . _ADMIN_STATS_DELETE_EXAM_USERS . '"><span data-icon="!" aria-hidden="true"></span></a>
            </div>');

        if (!$rec = $db->single()) {
            echo('
                </article>
                <article class="msg">
                <div class="clearfix pagehead">
                    <h1>' . _ADMIN_SHOW_ALL_USERS . '</h1>
                 </div>
                    <div class="content">
                        <div class="info_box clearfix" >
                            <div class="box_icon" data-icon="y" aria-hidden="true"></div>
                            <div class="content clearfix">
                            ' . _ADMIN_NO_USER_EXAM_FOUND . '
                            </div>
                        </div>
                        <div id="back" class="button_wrap clearfix">
			            <a class="button" id="back_b" href="charts"><div data-icon="b" aria-hidden="true" class="grid_img"></div>
			            <div class="grid_txt">' . _ADMIN_CHARTS . '</div></a>
		                </div>
                    </div>
                </article>');
            include('../footer.php');
            include('../footer_end.php');
            die();
        }

/******************************************************************************/

        echo ('
	    <table data-filter="#filter" class="test_list small">
		<thead>
			<tr>
				<th data-class="expand" data-sort-initial="true" scope="col" id="lname">' . _ADMIN_ADD_USER_LAST_NAME . '</th>
				<th scope="col" id="fname">' . _ADMIN_ADD_USER_NAME . '</th>
				<th scope="col" id="uname">' . _ADMIN_ADD_USER_USER_ID . '</th>
				<th data-hide="phone,tablet" scope="col" id="date">' . _ADMIN_EXAM_DATE . '</th>
				<th data-hide="phone,tablet" scope="col" id="ans">' . EXAM_ADMIN_NUM_ALL_ANSWERS . '</th>
				<th data-hide="phone,tablet" scope="col" id="time">' . EXAM_ADMIN_USER_TIME . '</th>
				<th data-hide="phone" id="cans">' . EXAM_NUM_CORRECT_ANSWERS . '</th>
				<th data-hide="phone" id="wans">' . EXAM_NUM_INCORRECT_ANSWERS . '</th>
				<th data-hide="phone" id="nans">' . EXAM_NUM_NON_ANSWERED . '</th>
				<th scope="col" id="grade">' . _EXAM_FINAL_GRADE_NOT_NEG . '</th>
				<th scope="col" id="tools">' . _EXAM_TOOLS . '</th>
			</tr>
		</thead>
		');
        echo ('<tbody>');
        $tr_num = 1;
        $recs = $db->resultset();
        foreach ($recs as $i => $rec) {
            $pars = array(
                ':rec' => $rec[1]
            );
            $result2 = $db->db_query("SELECT * FROM users WHERE id=:rec",$pars);
            $user_prop = $db->single();

            $pars = array(
                ':rec' => $rec[1],
                ':test_prop' => $test_prop[0]
            );
            $choices = $db->db_query("SELECT * FROM user_test WHERE user_id = :rec AND test_id = :test_prop",$pars);
            $choices2 = $db->single();
            $user_test_id = $choices2[0];

            $pars = array(
                ':choices2' => $choices2[0]
            );
            $choices = $db->db_query("SELECT * FROM user_choice WHERE user_test_id = :choices2",$pars);
            $choices_set = $db->resultset();
            $num_all_answers = $db->rowCount();
            $num_correct_answers = 0;
            $num_non_answered = 0;

            foreach ($choices_set as $j => $choices_check) {

                $pars = array(
                    ':choices_check' => $choices_check[2]
                );
                $question = $db->db_query("SELECT * FROM questions WHERE id = :choices_check",$pars);
                $question = $db->single();

                if ($question[7] == $choices_check[3]) {
                    $num_correct_answers++;
                }

                if ($choices_check[3] == "") {
                    $num_non_answered++;
                }
            }
            $num_incorrect_answers = $num_all_answers - ($num_correct_answers + $num_non_answered);
            if ($tr_num % 2 == 0)
                $tr_class = 'even';
            else
                $tr_class = '';
            echo ('
            <tr class="'.$tr_class.'">
				<td>' . $user_prop[2] . '</td>
				<td>' . $user_prop[1]. '</td>
				<td>' . $user_prop[4]. '</td>
				<td>' . $choices2[3]. '</td>

				<td>' . $num_all_answers. '
				<a class="bar_icon blue info_icon" href="statistics?case=uq&test_id=' . $test_id_q . '&user_id=' . $user_prop[0]. '" title="' . _EXAM_ADMIN_SHOW_USERS. '"><span data-icon="y" aria-hidden="true" class="grid_img"></span></a>
                </td>

				<td>' . $choices2[4]. '</td>
				<td>'. $num_correct_answers . ' (' . round(($num_correct_answers / $num_all_answers) * 100, 2). ' %)</td>
				<td>'. $num_incorrect_answers . ' (' . round(($num_incorrect_answers / $num_all_answers) * 100, 2). ' %)</td>
				<td>' . $num_non_answered. ' (' . round(($num_non_answered / $num_all_answers) * 100, 2) . ' %)</td>');

            if ($test_prop[8] == 1) {
                $num_correct_answers_neg = $num_correct_answers - round(($num_incorrect_answers / 3), 2);

                echo ('<td>' . $num_correct_answers_neg . ' ('
                    . round(($num_correct_answers_neg / $num_all_answers) * 100, 2) . ' %)</td>');
            } else {
                echo ('<td>' . $num_correct_answers . ' (' . round(($num_correct_answers / $num_all_answers) * 100, 2) . ' %)</td>
		');
            }

            echo ('<td class="tools"><a class="bar_icon delete" href="edit_user?case=deleteuser_test&uid=' . $user_prop[0] . '&test_id=' . $test_prop[0] . '" title="' . _ADMIN_DELETE_USER_FROM_THIS_EXAM . '"><span data-icon="r" aria-hidden="true" class="grid_img"></span></a></td>
			</tr>');
            $tr_num++;
        }

        echo ('</tbody></table>');

        /**Pagination**/
        $page=1;
        if(isset($_GET['p']) && $_GET['p']!=''){
            $page=$_GET['p'];
        }
        echo pagination($ineachpage,$page,'?case=' . $case . '&test_id=' . $test_id . '&p=',$num_users);
        /**</Pagination>**/

        echo ('</article>');

    } else if ($case == "uq") {
        $uq_id = $_REQUEST['user_id'];
        $pars = array(
            ':test_id' => $test_id
        );
        $check_default = $db->db_query("SELECT * FROM tests WHERE id = :test_id",$pars);
        $check_default = $db->single();

        if ($check_default[7] == 1) {
            $align = "right";
            $rtl_input = "rtl";
        } else {
            $align = "left";
            $rtl_input = "ltr";
        }
        $pars = array(
            ':uq_id' => $uq_id
        );
        $uid_session = $db->db_query("SELECT * FROM users WHERE id = :uq_id",$pars);
        $uid_session = $db->single();
        $pars = array(
            ':uq_id' => $uq_id,
            ':test_id' => $test_id
        );
        $choices = $db->db_query("SELECT * FROM user_test WHERE user_id = :uq_id AND test_id = :test_id",$pars);
        $choices = $db->single();
        $user_test_id = $choices[0];
        $pars = array(
            ':choices' => $choices[0]
        );
        $choices = $db->db_query("SELECT * FROM user_choice WHERE user_test_id = :choices",$pars);
        $rec = $db->single();
        $recs = $db->resultset();
        ///////////////Number of Correct, Incorrect and Non-Answered Questions
        $num_correct_answers = 0;
        $num_non_answered = 0;
        $pars = array(
            ':user_test_id' => $user_test_id
        );
        $num_all_answers = $db->db_query("SELECT * FROM user_choice WHERE user_test_id=:user_test_id",$pars);
        $num_all_answers = $db->rowCount();
        ///////////////
        $counter = 1;

        echo ('
            <article id="show_stats">
            <section class="msg">
			<div class="content">
                <div class="info_box clearfix" >
                    <div class="box_icon" data-icon="y" aria-hidden="true"></div>
                    <div class="content clearfix">
                        <ul>
                            <li>
                            <table class="inf"><tbody>
                            <tr><td><span class="correct" data-icon="d" aria-hidden="true"></span></td><td>' . _EXAM_YOUR_ANSWER_IS_CORRECT . '</td></tr>
                            <tr><td><span class="incorrect" data-icon="." aria-hidden="true"></span></td><td>' . _EXAM_YOUR_ANSWER_IS_INCORRECT . '</td></tr>
                            <tr><td><span class="null" data-icon="," aria-hidden="true"></span></td><td>' . _EXAM_YOUR_ANSWER_IS_NULL . '</td></tr>
                            <tr><td><span class="correct" data-icon="z" aria-hidden="true"></span></td><td>' . _EXAM_CORRECT_CHOICE . '</td></tr>
                            <tr><td><span class="incorrect" data-icon="x" aria-hidden="true"></span></td><td>' . _EXAM_YOUR_INCORRECT_CHOICE . '</td></tr>
                            </tbody></table>
                            </li>
                        </ul>
                    </div>
			    </div>
			</div>
			</section>
            <section id="questions">
            <form method="POST" action="index">
            <div class="clearfix pagehead">
            <h2>' . _EXAM_QUESTIONS . '</h2></div>');
        $anclass1 = $anclass2 = $anclass3 = $anclass4 = "";
        $ansign1 = $ansign2 = $ansign3 = $ansign4 = "";

        foreach ($recs as $i => $rec) {
            $pars = array(
                ':rec' => $rec[2]
            );
            $question = $db->db_query("SELECT * FROM questions WHERE id = :rec",$pars);
            $question = $db->single();

            ///////////////Answer Icon
            if ($question[7] == $rec[3]) {
                $num_correct_answers++;
                $anu1 = $anu2 = $anu3 = $anu4 = "";
                $anclass1 = $anclass2 = $anclass3 = $anclass4 = "";
                $ansign1 = $ansign2 = $ansign3 = $ansign4 = "";
                $answer = '<span data-icon="d" aria-hidden="true"></span>';
                $ansign = "correct_sign";
                $title=_EXAM_YOUR_ANSWER_IS_CORRECT;
            } else if ($rec[3] == "") {
                $answer = '<span data-icon="," aria-hidden="true"></span>';
                $ansign = "null_sign";
                $title=_EXAM_YOUR_ANSWER_IS_NULL;
                $anu1 = $anu2 = $anu3 = $anu4 = "";
                $anclass1 = $anclass2 = $anclass3 = $anclass4 = "";
                $ansign1 = $ansign2 = $ansign3 = $ansign4 = "";
                $num_non_answered++;
            } else {
                $anu1 = $anu2 = $anu3 = $anu4 = "";
                $anclass1 = $anclass2 = $anclass3 = $anclass4 = "";
                $ansign1 = $ansign2 = $ansign3 = $ansign4 = "";

                switch ($rec[3]) {
                    case 1:
                        $anu1 = '<span data-icon="x" aria-hidden="true"></span>';
                        $anclass1 = "incorrect_q";
                        $ansign1 = "incorrect_sign";
                        break;

                    case 2:
                        $anu2 = '<span data-icon="x" aria-hidden="true"></span>';
                        $anclass2 = "incorrect_q";
                        $ansign2 = "incorrect_sign";
                        break;

                    case 3:
                        $anu3 = '<span data-icon="x" aria-hidden="true"></span>';
                        $anclass3 = "incorrect_q";
                        $ansign3 = "incorrect_sign";
                        break;

                    case 4:
                        $anu4 = '<span data-icon="x" aria-hidden="true"></span>';
                        $anclass4 = "incorrect_q";
                        $ansign4 = "incorrect_sign";
                        break;
                }
                $answer = '<span data-icon="." aria-hidden="true"></span>';
                $ansign = "incorrect_sign";
                $title=_EXAM_YOUR_ANSWER_IS_INCORRECT;
            }

            $an1 = $an2 = $an3 = $an4 = "";

            switch ($question[7]) {
                case 1:
                    $an1 = '<span class="correct" data-icon="z" aria-hidden="true"></span>';
                    break;

                case 2:
                    $an2 = '<span class="correct" data-icon="z" aria-hidden="true"></span>';
                    break;

                case 3:
                    $an3 = '<span class="correct" data-icon="z" aria-hidden="true"></span>';
                    break;

                case 4:
                    $an4 = '<span class="correct" data-icon="z" aria-hidden="true"></span>';
                    break;
            }

            echo ('
	<div class="question">
        <div class="q_head">
            <div class="q_num">' . $counter . '</div>
            <div class="q_sign ' . $ansign . '" title="' . $title . '">
            '. $answer . '
            </div>
        </div>
        <div class="q_body content">
            <div class="q content">' . $question[2] . '</div>
            <table class="choices content"><tbody>
            <tr class="' . $anclass1 . '">
            <td class="t_f ' . $ansign1 . '">' . $an1 . '' . $anu1 . '</td>
            <td class="q_c">' . _ADMIN_CHART_CHOICE1 . ')</td>
            <td>' . $question[3] . '</td>
            <td></td>
            </tr>
            <tr class="' . $anclass2 . '">
            <td class="t_f ' . $ansign2 . '">' . $an2 . '' . $anu2 . '</td>
            <td class="q_c">' . _ADMIN_CHART_CHOICE2 . ')</td>
            <td>' . $question[4] . '</td>
            <td></td>
            </tr>
            <tr class="' . $anclass3 . '">
            <td class="t_f ' . $ansign3 . '">' . $an3 . '' . $anu3 . '</td>
            <td class="q_c">' . _ADMIN_CHART_CHOICE3 . ')</td>
            <td>' . $question[5] . '</td>
            <td></td>
            </tr>
            <tr class="' . $anclass4 . '">
            <td class="t_f ' . $ansign4 . '">' . $an4 . '' . $anu4 . '</td>
            <td class="q_c">' . _ADMIN_CHART_CHOICE4 . ')</td>
            <td>' . $question[6] . '</td>
            <td></td>
            </tr>
            </tbody></table>
        </div>
    </div>
	');

            $counter++;
        }

        $num_incorrect_answers = $num_all_answers - ($num_correct_answers + $num_non_answered);

/**************************************************************************************************************/
        echo ('
        </section><section id="exam_result">
            <div class="box_title"><h2 class="content">'. _EXAM_TOTAL_RESULTS . '</h2></div>
            <div class="content">
            <table class="inf"><tbody>

            <tr><td><span data-icon="t" aria-hidden="true"></span></td><td>' . EXAM_NUM_ALL_ANSWERS . '</td><td>' . $num_all_answers . '</td></tr>

             <tr><td><span class="correct" data-icon="z" aria-hidden="true"></span></td>
             <td>' . EXAM_NUM_CORRECT_ANSWERS . '</td>
             <td>'. $num_correct_answers . ' ('. round(($num_correct_answers / $num_all_answers) * 100, 2) . ' ' . _EXAM_PERCENT . ')</td></tr>

             <tr><td><span class="incorrect" data-icon="x" aria-hidden="true"></span></td><td>' . EXAM_NUM_INCORRECT_ANSWERS . '</td>
             <td>' . $num_incorrect_answers . ' ('
            . round(($num_incorrect_answers / $num_all_answers) * 100, 2) . ' ' . _EXAM_PERCENT . ')</td></tr>

             <tr><td><span class="null" data-icon="," aria-hidden="true"></span></td><td>' . EXAM_NUM_NON_ANSWERED . '</td>
             <td>' . $num_non_answered . ' ('
            . round(($num_non_answered / $num_all_answers) * 100, 2) . ' ' . _EXAM_PERCENT . ')</td></tr>

            </tbody></table>
        ');

        if ($check_default[8] == 1) {
            $num_correct_answers_neg = $num_correct_answers - round(($num_incorrect_answers / 3), 2);

            echo ('
            <div class="info_box clearfix" >
			<div class="box_icon" data-icon="y" aria-hidden="true"></div>
			<div class="content">
			<ul><li>' . _EXAM_NEGATIVE_1 . '</li></ul>
			</div>
			</div>
            <div id="total_grade">
            <div class="box_title"><h3 class="content">' . _EXAM_FINAL_GRADE_NEG . '</h3></div>
            <div id="grad" class="content">'
                . $num_correct_answers_neg . ' (' . round(($num_correct_answers_neg / $num_all_answers) * 100, 2) . ' '
                . _EXAM_PERCENT . ')</div>
            </div>');
        } else {
            echo ('
            <div class="info_box clearfix" >
			<div class="box_icon" data-icon="y" aria-hidden="true"></div>
			<div class="content">
			<ul><li>' . _EXAM_NEGATIVE_0 . '</li></ul>
			</div>
			</div>
            <div id="total_grade">
            <div class="box_title"><h3 class="content">' . _EXAM_FINAL_GRADE_NOT_NEG . '</h3></div>
            <div id="grad" class="content">'
                . $num_correct_answers . ' (' . round(($num_correct_answers / $num_all_answers) * 100, 2) . ' '
                . _EXAM_PERCENT . ')</div>
            </div>
            ');
        }

        ?>
        <div id="exam_stats_wrap" class="content">
        <div id="exam_stats">
        <canvas id="canvas" width="100" height="100">
        </canvas>

        <table id="mydata">
            <tbody>
            <tr class="correct_q">
                <td>
                    <?php
                    echo ('' . EXAM_NUM_CORRECT_ANSWERS2 . '</td>
                    <td>'. round(($num_correct_answers / $num_all_answers) * 100, 2) . '');
                    ?>
                </td>
            </tr>

            <tr class="incorrect_q">
                <td>
                    <?php
                    echo ('' . EXAM_NUM_INCORRECT_ANSWERS2 . '</td>
                    <td>'. round(($num_incorrect_answers / $num_all_answers) * 100, 2) . '');
                    ?>
                </td>
            </tr>

            <tr class="null_q">
                <td>
                    <?php
                    echo ('' . EXAM_NUM_NON_ANSWERED2 . '</td>
                    <td>'. round(($num_non_answered / $num_all_answers) * 100, 2) . '');
                    ?>
                </td>
            </tr>
            </tbody>
        </table>


        <?php
        echo ('
        </table>
        </div>
        </div>
		<div id="back" class="button_wrap clearfix">
		<a class="button" id="back_b" onclick="javascript: window.history.go(-1)"><div data-icon="b" aria-hidden="true" class="grid_img"></div><div class="grid_txt">' . _ADMIN_RETURN . '</div></a>
		</div>
		</div>
		</section>
		</article>');
    } else if ($case == "q") {
        $pars = array(
            ':test_id' => $test_id
        );
        $select_users = $db->db_query("SELECT * FROM questions WHERE test_id=:test_id",$pars);
        $num_users = $db->rowCount();
        $ineachpage=12;

        if (!(isset($_REQUEST["p"]))) {
            $start = 0;
            $finish = $start + $ineachpage;
        } else {
            $start = ($_REQUEST["p"]-1) * $ineachpage;
            $finish = $start + $ineachpage;
        }
        $pars = array(
            ':test_id' => $test_id
        );
        $test_prop = $db->db_query("SELECT * FROM tests WHERE id=:test_id",$pars);
        $test_prop = $db->single();
        ///////////////////////
        $test_id_q = $test_prop[0];
        $pars = array(
            ':test_id' => $test_id
        );
        $result = $db->db_query("SELECT * FROM questions WHERE test_id=:test_id ORDER BY id LIMIT ".$start.", ".$ineachpage,$pars);
        $recs = $db->resultset();

        if ($test_prop[3] == 1)
            $Be_Default = _RANDOM_1;
        else
            $Be_Default = _RANDOM_0;

        if ($test_prop[4] == 1)
            $reg_user = _BY_PROF;
        else
            $reg_user = _BY_USER;

        if ($test_prop[5] == 1)
            $test_random = _RANDOM_1;
        else
            $test_random = _RANDOM_0;

        if ($test_prop[7] == 1) {
            $test_RTL = _RTL_1;
            $rtl = "rtl";
        } else {
            $test_RTL = _RTL_0;
            $rtl = "ltr";
        }

        if ($test_prop[8] == 1)
            $minus_mark = _MINUS_MARK_1;
        else
            $minus_mark = _MINUS_MARK_0;

        if ($test_prop[9] == 1)
            $show_answers = _ADMIN_MINUS_MARK_1;
        else
            $show_answers = _ADMIN_MINUS_MARK_0;

        if ($test_prop[10] == 1)
            $show_mark = _ADMIN_MINUS_MARK_1;
        else
            $show_mark = _ADMIN_MINUS_MARK_0;


        echo ('
            <article id="show_stats">
			<div class="clearfix pagehead">
            <h1>' . _ADMN_CHARTS_DETAILS . ': ' . $test_prop[1] . '</h1><a class="bar_icon blue info_icon" onMouseover="third2(\'<table class=inf><tbody><tr><td>'
            . _EXAM_NAME . '</td><td class=even>' . $test_prop[1] . '</td></tr><tr class=even><td>'
            . _EXAM_TIME . '</td><td class=even>' . $test_prop[6] . '</td></tr><tr><td>'
            . _EXAM_BE_DEFAULT . '</td><td class=even>' . $Be_Default . '</td></tr><tr class=even><td>'
            . _EXAM_REGISTRAR . '</td><td class=even>' . $reg_user . '</td></tr><tr><td>'
            . _EXAM_RANDOM . '</td><td class=even>' . $test_random . '</td></tr><tr class=even><td>'
            . _EXAM_ALIGNMENT . '</td><td class=even>' . $test_RTL . '</td></tr><tr><td>'
            . _EXAM_MINUS_MARK . '</td><td class=even>' . $minus_mark . '</td></tr><tr class=even><td>'
            . _EXAM_SHOW_ANSWERS . '</td><td class=even>' . $show_answers . '</td></tr><tr><td>'
            . _EXAM_SHOW_MARK . '</td><td class=even>' . $show_mark . '</td></tr></tbody></table>\', \'white\', 250)"; onMouseout="first4();">
            <span data-icon="y" aria-hidden="true" class="grid_img"></span></a>
            </div>');

        if (!$rec = $db->single()) {
            echo('
                </article>
                <article class="msg">
                <div class="clearfix pagehead">
                    <h1>' . _ADMIN_SHOW_ALL_QUESTIONS . '</h1>
                 </div>
                    <div class="content">
                        <div class="info_box clearfix" >
                            <div class="box_icon" data-icon="y" aria-hidden="true"></div>
                            <div class="content clearfix">
                            ' . _ADMIN_NO_QUESTION_FOUND . '
                            </div>
                        </div>
                        <div id="back" class="button_wrap clearfix">
			            <a class="button" id="back_b" onClick="javascript: window.history.go(-1)"><div data-icon="b" aria-hidden="true" class="grid_img"></div>
			            <div class="grid_txt">' . _ADMIN_RETURN . '</div></a>
		                </div>
                    </div>
                </article>');
            include('../footer.php');
            include('../footer_end.php');
            die();
        }


/*************************************************************/
        echo ('
        <table id="test_stats_detail" class="test_list small">
		<thead>
			<tr>
				<th scope="col" id="q_title"></th>
				<th scope="col" id="q_ans_num" class="num">' . _ADMIN_CHART_NUMBER_OF_Q_USERS . '</th>
				<th scope="col" id="qc1" class="precent">' . _ADMIN_CHART_CHOICE1 . '</th>
				<th scope="col" id="qc2" class="precent">' . _ADMIN_CHART_CHOICE2 . '</th>
				<th scope="col" id="qc3" class="precent">' . _ADMIN_CHART_CHOICE3 . '</th>
				<th scope="col" id="qc4" class="precent">' . _ADMIN_CHART_CHOICE4 . '</th>
				<th scope="col" id="qnc" class="precent">' . _ADMIN_CHART_NO_CHOICE . '</th>
			</tr>
		</thead>
        ');
        echo ('<tbody>');
        $tr_num = 1;

        foreach ($recs as $i => $rec) {
            if ($tr_num % 2 == 0)
                $tr_class = 'even';
            else
                $tr_class = '';
            $pars = array(
                ':rec' => $rec[0]
            );
            $num_answers = $db->db_query("SELECT * FROM user_choice WHERE q_id = :rec",$pars);
            $num_all = $db->rowCount();
            $num_answers = $db->db_query("SELECT * FROM user_choice WHERE q_id = :rec AND answer=1",$pars);
            $num_1 = $db->rowCount();

            if ($num_1 == 0)
                $num_1_pr = 0;
            else
                $num_1_pr = round(($num_1 / $num_all) * 100, 2);
            $num_answers = $db->db_query("SELECT * FROM user_choice WHERE q_id = :rec AND answer=2",$pars);
            $num_2 = $db->rowCount();

            if ($num_2 == 0)
                $num_2_pr = 0;
            else
                $num_2_pr = round(($num_2 / $num_all) * 100, 2);
            $num_answers = $db->db_query("SELECT * FROM user_choice WHERE q_id = :rec AND answer=3",$pars);
            $num_3 = $db->rowCount();

            if ($num_3 == 0)
                $num_3_pr = 0;
            else
                $num_3_pr = round(($num_3 / $num_all) * 100, 2);
            $num_answers = $db->db_query("SELECT * FROM user_choice WHERE q_id = :rec AND answer=4",$pars);
            $num_4 = $db->rowCount();

            if ($num_4 == 0)
                $num_4_pr = 0;
            else
                $num_4_pr = round(($num_4 / $num_all) * 100, 2);
            $num_no_answer = $num_all - $num_1 - $num_2 - $num_3 - $num_4;

            if ($num_no_answer == 0)
                $num_no_answer_pr = 0;
            else
                $num_no_answer_pr = round(($num_no_answer / $num_all) * 100, 2);

            $bg1 = $bg2 = $bg3 = $bg4 = "";

            switch ($rec[7]) {
                case 1:
                    $bg1 = 'correct_sign';
                    break;

                case 2:
                    $bg2 = 'correct_sign';
                    break;

                case 3:
                    $bg3 = 'correct_sign';
                    break;

                case 4:
                    $bg4 = 'correct_sign';
                    break;
            }

            echo ('
                <tr onclick="$(\'#' . $rec[0] . '\').toggle();" class="q_details '.$tr_class.'">
				<td>
                <table class="q_txt"><tr><td class="q_choices">
				<a href="#" title="' . _ADMIN_CHART_CHOICES . '"><span class="blue info_icon" data-icon="=" aria-hidden="true"></span></a></td>
				<td>' . $rec[2] . '</td></tr></table></td>

				<td><a href="statistics?case=uu&test_id=' . $test_prop[0] . '&q_id=' . $rec[0] . '&show=all">' . $num_all . '</a></td>
				<td class="' . $bg1 . '"><a href="statistics?case=uu&test_id=' . $test_prop[0] . '&q_id=' . $rec[0] . '&show=1">' . $num_1 . '</a> (' . $num_1_pr . '%)</td>
				<td class="' . $bg2 . '"><a href="statistics?case=uu&test_id=' . $test_prop[0] . '&q_id=' . $rec[0] . '&show=2">' . $num_2 . '</a> (' . $num_2_pr . '%)</td>
				<td class="' . $bg3 . '"><a href="statistics?case=uu&test_id=' . $test_prop[0] . '&q_id=' . $rec[0] . '&show=3">' . $num_3 . '</a> (' . $num_3_pr . '%)</td>
				<td class="' . $bg4 . '"><a href="statistics?case=uu&test_id=' . $test_prop[0] . '&q_id=' . $rec[0] . '&show=4">' . $num_4 . '</a> (' . $num_4_pr . '%)</td>
		        <td><a href="statistics?case=uu&test_id=' . $test_prop[0] . '&q_id=' . $rec[0] . '&show=null">' . $num_no_answer . '</a> (' . $num_no_answer_pr . '%)</td>
				');

            echo ('</tr>');
            echo ('
                <tr style="display: none;" id="' . $rec[0] . '">
                <td class="content" colspan="7">
                <div class="inner_choices">
                <table class="choices content"><tbody>
                <tr class="even"><td class="q_c even">' . _ADMIN_CHART_CHOICE1 . ')</td><td>' . $rec[3] . '</td></tr>
                <tr class="even"><td class="q_c even">' . _ADMIN_CHART_CHOICE2 . ')</td><td>' . $rec[4] . '</td></tr>
                <tr class="even"><td class="q_c even">' . _ADMIN_CHART_CHOICE3 . ')</td><td>' . $rec[5] . '</td></tr>
                <tr class="even"><td class="q_c even">' . _ADMIN_CHART_CHOICE4 . ')</td><td>' . $rec[6] . '</td></tr>
                </tbody>
                </table>
                </div>
                </td>
                </tr>
            ');
            $tr_num++;
        }

        echo ('</tbody></table>');

        /**Pagination**/
        $page=1;
        if(isset($_GET['p']) && $_GET['p']!=''){
            $page=$_GET['p'];
        }
        echo pagination($ineachpage,$page,'?case=q&test_id=' . $test_id . '&p=',$num_users);
        /**</Pagination>**/

        echo ('</article>');

    } else if ($case == "uu") {
        $q_id = $_REQUEST["q_id"];
        $show = $_REQUEST["show"];

        switch ($show) {
            case 'all':
                $show = '';
                break;

            case 1:
                $show = 'and answer=1';
                break;

            case 2:
                $show = 'and answer=2';
                break;

            case 3:
                $show = 'and answer=3';
                break;

            case 4:
                $show = 'and answer=4';
                break;

            case 'null':
                $show = 'and answer IS NULL';
                break;
        }
        $pars = array(
            ':q_id' => $q_id
        );
        $select_users = $db->db_query("SELECT * FROM user_choice WHERE q_id=:'q_id' ".$show,$pars);
        $ineachpage=12;

        if (!(isset($_REQUEST["p"]))) {
            $start = 0;
            $finish = $start + $ineachpage;
        } else {
            $start = ($_REQUEST["p"]-1) * $ineachpage;
            $finish = $start + $ineachpage;
        }
        $pars = array(
            ':q_id' => $q_id,
        );
        $result = $db->db_query("SELECT * FROM user_choice WHERE q_id=:q_id ".$show."ORDER BY id LIMIT ".$start.", ".$ineachpage,$pars);

        $recs = $db->resultset();
        $pars = array(
            ':q_id' => $q_id
        );
        $num_users = $db->db_query("SELECT * FROM user_choice WHERE q_id=:q_id ".$show." ORDER BY id",$pars);
        $num_users = $db->rowCount();
        $question = $db->db_query("SELECT * FROM questions WHERE id = :q_id",$pars);
        $question = $db->single();

        ///////////////////////
        echo ('
            <article id="show_stats">
			<div class="clearfix pagehead">
            <h1>' . _EXAM_NAME . ': ' . $test_prop[1] . '</h1><a class="bar_icon blue info_icon" onMouseover="third2(\'<table class=inf><tbody><tr><td>'
            . _EXAM_NAME . '</td><td class=even>' . $test_prop[1] . '</td></tr><tr class=even><td>'
            . _EXAM_TIME . '</td><td class=even>' . $test_prop[6] . '</td></tr><tr><td>'
            . _EXAM_BE_DEFAULT . '</td><td class=even>' . $Be_Default . '</td></tr><tr class=even><td>'
            . _EXAM_REGISTRAR . '</td><td class=even>' . $reg_user . '</td></tr><tr><td>'
            . _EXAM_RANDOM . '</td><td class=even>' . $test_random . '</td></tr><tr class=even><td>'
            . _EXAM_ALIGNMENT . '</td><td class=even>' . $test_RTL . '</td></tr><tr><td>'
            . _EXAM_MINUS_MARK . '</td><td class=even>' . $minus_mark . '</td></tr><tr class=even><td>'
            . _EXAM_SHOW_ANSWERS . '</td><td class=even>' . $show_answers . '</td></tr><tr><td>'
            . _EXAM_SHOW_MARK . '</td><td class=even>' . $show_mark . '</td></tr></tbody></table>\', \'white\', 250)"; onMouseout="first4();">
            <span data-icon="y" aria-hidden="true" class="grid_img"></span></a>
            </div>
            ');
        $an1 = $an2 = $an3 = $an4 = "";
        $anclass1 = $anclass2 = $anclass3 = $anclass4 = "";
        $ansign1 = $ansign2 = $ansign3 = $ansign4 = "";
        switch ($question[7]) {
            case 1:
                $an1 = '<span data-icon="z" aria-hidden="true"></span>';
                $anclass1 = "correct_q";
                $ansign1 = "correct_sign";
                break;

            case 2:
                $an2 = '<span data-icon="z" aria-hidden="true"></span>';
                $anclass2 = "correct_q";
                $ansign2 = "correct_sign";
                break;

            case 3:
                $an3 = '<span data-icon="z" aria-hidden="true"></span>';
                $anclass3 = "correct_q";
                $ansign3 = "correct_sign";
                break;

            case 4:
                $an4 = '<span data-icon="z" aria-hidden="true"></span>';
                $anclass4 = "correct_q";
                $ansign4 = "correct_sign";
                break;
        }

        echo ('
        <div class="question">
	    <div class="q_head">
        <h3 class="content">' . _ADMIN_CHART_Q_TITLE . '</h3>
        </div>

        <div class="q_body content">
        <div class="q content">' . $question[2] . '</div>
        <table class="choices content"><tbody>
        <tr class="' . $anclass1 . '">
        <td class="t_f ' . $ansign1 . '">' . $an1 . '</td>
        <td class="q_c">' . _ADMIN_CHART_CHOICE1 . ')</td>
        <td>' . $question[3] . '</td>
        <td></td>
        </tr>
        <tr class="' . $anclass2 . '">
        <td class="t_f ' . $ansign2 . '">' . $an2 . '</td>
        <td class="q_c">' . _ADMIN_CHART_CHOICE2 . ')</td>
        <td>' . $question[4] . '</td>
        <td></td>
        </tr>
        <tr class="' . $anclass3 . '">
        <td class="t_f ' . $ansign3 . '">' . $an3 . '</td>
        <td class="q_c">' . _ADMIN_CHART_CHOICE3 . ')</td>
        <td>' . $question[5] . '</td>
        <td></td>
        </tr>
        <tr class="' . $anclass4 . '">
        <td class="t_f ' . $ansign4 . '">' . $an4 . '</td>
        <td class="q_c">' . _ADMIN_CHART_CHOICE4 . ')</td>
        <td>' . $question[6] . '</td>
        <td></td>
        </tr>
        </tbody></table>
        </div>
        </div>
        ');

        if (!$rec = $db->single()) {
            echo('
                </article>
                <article class="msg">
                <div class="clearfix pagehead">
                    <h1>' . _ADMIN_SHOW_ALL_USERS . '</h1>
                 </div>
                    <div class="content">
                        <div class="info_box clearfix" >
                            <div class="box_icon" data-icon="y" aria-hidden="true"></div>
                            <div class="content clearfix">
                            ' . _ADMIN_NO_USER_EXAM_FOUND_WITH_THIS_ANSWER . '
                            </div>
                        </div>
                        <div id="back" class="button_wrap clearfix">
			            <a class="button" id="back_b" onclick="javascript: window.history.go(-1)"><div data-icon="b" aria-hidden="true" class="grid_img"></div>
			            <div class="grid_txt">' . _ADMIN_RETURN . '</div></a>
		                </div>
                    </div>
                </article>
                ');
            include('../footer.php');
            include('../footer_end.php');
            die();
        }

        ///////////////////////////////////////

        $bg1 = $bg2 = $bg3 = $bg4 = "";

        switch ($question[7]) {
            case 1:
                $bg1 = 'correct_sign';
                break;

            case 2:
                $bg2 = 'correct_sign';
                break;

            case 3:
                $bg3 = 'correct_sign';
                break;

            case 4:
                $bg4 = 'correct_sign';
                break;
        }

        echo ('
        <table class="test_list small">
		<thead>
			<tr>
				<th data-class="expand" data-sort-initial="true" scope="col" id="lname">' . _ADMIN_ADD_USER_LAST_NAME . '</th>
				<th scope="col" id="fname">' . _ADMIN_ADD_USER_NAME . '</th>
				<th scope="col" id="uname">' . _ADMIN_ADD_USER_USER_ID . '</th>
				<th data-hide="phone,tablet" scope="col" id="date">' . _ADMIN_EXAM_DATE . '</th>
				<th data-hide="phone,tablet" scope="col" id="q_ans_num" class="num">' . EXAM_ADMIN_NUM_ALL_ANSWERS . '</th>
				<th data-hide="phone,tablet" scope="col" id="time">' . EXAM_ADMIN_USER_TIME . '</th>
				<th scope="col" id="qc1" class="precent">' . _ADMIN_CHART_CHOICE1 . '</th>
				<th scope="col" id="qc2" class="precent">' . _ADMIN_CHART_CHOICE2 . '</th>
				<th scope="col" id="qc3" class="precent">' . _ADMIN_CHART_CHOICE3 . '</th>
				<th scope="col" id="qc4" class="precent">' . _ADMIN_CHART_CHOICE4 . '</th>
				<th scope="col" id="qnc" class="precent">' . _ADMIN_CHART_NO_CHOICE . '</th>
			</tr>
		</thead>
        ');
        echo ('<tbody>');
        $tr_num = 1;
        foreach ($recs as $i => $rec) {
            if ($tr_num % 2 == 0)
                $tr_class = 'even';
            else
                $tr_class = '';
            $pars = array(
                ':rec' => $rec[1]
            );
            $result3 = $db->db_query("SELECT * FROM user_test WHERE id=:rec",$pars);
            $user_test_prop = $db->single();

            $pars = array(
                ':user_test_prop' => $user_test_prop[1]
            );
            $result2 = $db->db_query("SELECT * FROM users WHERE id=:user_test_prop",$pars);
            $user_prop = $db->single();

            $pars = array(
                ':user_test_prop' => $user_test_prop[0]
            );
            $choices2 = $db->db_query("SELECT * FROM user_choice WHERE user_test_id = :user_test_prop",$pars);
            $num_all_answers = $db->rowCount();

            $pars1 = array(
                ':user_test_prop' => $user_test_prop[0],
                ':q_id' => $q_id
            );
            $choices = $db->db_query("SELECT * FROM user_choice WHERE user_test_id = :user_test_prop AND q_id=:q_id",$pars1);
            $choices_check = $db->single();

            $an1 = $an2 = $an3 = $an4 = $an5 = "";

            switch ($choices_check[3]) {
                case 1:
                    $an1 = '<span data-icon="z" aria-hidden="true"></span>';
                    break;

                case 2:
                    $an2 = '<span data-icon="z" aria-hidden="true"></span>';
                    break;

                case 3:
                    $an3 = '<span data-icon="z" aria-hidden="true"></span>';
                    break;

                case 4:
                    $an4 = '<span data-icon="z" aria-hidden="true"></span>';
                    break;

                default:
                    $an5 = '<span data-icon="z" aria-hidden="true"></span>';
            }

            echo ('<tr class="'.$tr_class.'">
				<td>' . $user_prop[2] . '</td>
				<td>' . $user_prop[1] . '</td>
				<td>' . $user_prop[4] . '</td>
				<td>' . $user_test_prop[3] . '</td>
				<td>' . $num_all_answers . ' <a href="statistics?case=uq&test_id=' . $test_id_q . '&user_id=' . $user_prop[0] . '" title="' . _EXAM_ADMIN_SHOW_USERS_QUESTIONS . '"><span class="blue info_icon" data-icon="y" aria-hidden="true"></span></a></td>
				<td>' . $user_test_prop[4] . '</td>
				<td class="precent ' . $bg1 . '">' . $an1 . '</td>
				<td class="precent ' . $bg2 . '">' . $an2 . '</td>
				<td class="precent ' . $bg3 . '">' . $an3 . '</td>
				<td class="precent ' . $bg4 . '">' . $an4 . '</td>
				<td class="precent">' . $an5 . '</td>
				</tr>');
            $tr_num++;
        }

        echo ('</tbody></table>');
        /**Pagination**/
        $page=1;
        if(isset($_GET['p']) && $_GET['p']!=''){
            $page=$_GET['p'];
        }
        echo pagination($ineachpage,$page,'?case=uu&test_id=' . $test_id . '&q_id=' . $q_id . '&show=' . $show . '&p=',$num_users);
        /**</Pagination>**/

        echo ('</article>');
    }
} else {
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
?>

<?php include('../footer.php');?>
        <script type="text/javascript">
            window.onload = function () {
                (function () { //keep the global space clean

                    ///// STEP 0 - setup

                    // source data table and canvas tag
                    var data_table = document.getElementById('mydata');
                    var canvas = document.getElementById('canvas');
                    var td_index = 1; // which TD contains the data

                    ///// STEP 1 - Get the, get the, get the data!

                    // get the data[] from the table
                    var tds, data = [], color, colors = [], value = 0, total = 0;
                    var trs = data_table.getElementsByTagName('tr'); // all TRs

                    for (var i = 0; i < trs.length; i++) {
                        tds = trs[i].getElementsByTagName('td'); // all TDs

                        if (tds.length === 0)
                            continue;                            //  no TDs here, move on

                        // get the value, update total
                        value = parseFloat(tds[td_index].innerHTML);
                        data[data.length] = value;
                        total += value;
                    }
                    colors[0] = "#C9EDAD";
                    colors[1] = "#edcac7";
                    colors[2] = "#afafaf";
                    ///// STEP 2 - Draw pie on canvas


                    // exit if canvas is not supported
                    if (typeof canvas.getContext === 'undefined') {
                        return;
                    }

                    // get canvas context, determine radius and center
                    var ctx = canvas.getContext('2d');
                    var canvas_size =
                        [
                            canvas.width,
                            canvas.height
                        ];

                    var radius = Math.min(canvas_size[0], canvas_size[1]) / 2;
                    var center =
                        [
                            canvas_size[0] / 2,
                            canvas_size[1] / 2
                        ];

                    var sofar = 0; // keep track of progress
                    // loop the data[]
                    for (var piece in data) {

                        var thisvalue = data[piece] / total;

                        ctx.beginPath();
                        ctx.moveTo(center[0], center[1]);                           // center of the pie
                        ctx.arc(                                                    // draw next arc
                            center[0], center[1], radius, Math.PI * (-0.5 + 2 * sofar), // -0.5 sets set the start to be top
                            Math.PI * (-0.5 + 2 * (sofar + thisvalue)), false);

                        ctx.lineTo(center[0], center[1]);                           // line back to the center
                        ctx.closePath();
                        ctx.fillStyle = colors[piece];                              // color
                        ctx.fill();

                        sofar += thisvalue;                                         // increment progress tracker
                    }


                    ///// DONE!
                })(); // exec!
            }
        </script>

<?php
include('../footer_end.php');
?>