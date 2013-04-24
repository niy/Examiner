<?php

header("Content-Type: text/html; charset=utf-8");

if (!isset($_COOKIE['examiner'])) {
    header('Location: index');
} else {
    include('admin_config.php');

    if (!(isset($_REQUEST['tid'])))
        die('<article id="add_question"><div class="content">
		<div class="info_box clearfix" >
		<div class="box_icon" data-icon="y" aria-hidden="true"></div>
		<div class="content clearfix">' . _ADMIN_NOT_ALLOWED . '!</div>
		</div>
		<a href="../admin">' . _ADMIN_HOME . '</a>
		</div>
        </article>
		');
    $tid = $_REQUEST['tid'];

    $questions = mysql_query("SELECT * FROM questions WHERE test_id = '$tid'");
    $rec = mysql_fetch_row($questions);
    $counter = 1;

    echo ('
    <article id="show_questions">
    ');
    $tr_num = 1;
    do {
        $an1 = $an2 = $an3 = $an4 = "";
        $anclass1 = $anclass2 = $anclass3 = $anclass4 = "";
        $ansign1 = $ansign2 = $ansign3 = $ansign4 = "";

        switch ($rec[7]) {
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


        echo ('<div class="question">
	<div class="q_head">
        <div class="q_num">' . $counter . '</div>
        <div class="q_tools">
        <a class="bar_icon modify" href="edit_question?q_id=' . $rec[0] . '" title="' . _ADMIN_EDIT_THIS_QUESTION . '"><span data-icon="m" aria-hidden="true"></span></a>
        <a class="bar_icon delete" href="edit_question?case=delete&t_id='.$tid.'&q_id=' . $rec[0] . '" title="' . _ADMIN_DELETE_THIS_QUESTION . '"><span data-icon="r" aria-hidden="true" class="grid_img"></span></a>
        </div>
    </div>
    <div class="q_body content">
	<div class="q content">' . $rec[2] . '</div>
    <table class="choices content"><tbody>
	<tr class="' . $anclass1 . '">
	<td class="t_f ' . $ansign1 . '">' . $an1 . '</td>
	<td class="q_c">' . _ADMIN_CHART_CHOICE1 . ')</td>
	<td>' . $rec[3] . '</td>
	<td></td>
	</tr>
	<tr class="' . $anclass2 . '">
	<td class="t_f ' . $ansign2 . '">' . $an2 . '</td>
	<td class="q_c">' . _ADMIN_CHART_CHOICE2 . ')</td>
	<td>' . $rec[4] . '</td>
	<td></td>
	</tr>
	<tr class="' . $anclass3 . '">
	<td class="t_f ' . $ansign3 . '">' . $an3 . '</td>
	<td class="q_c">' . _ADMIN_CHART_CHOICE3 . ')</td>
	<td>' . $rec[5] . '</td>
	<td></td>
	</tr>
	<tr class="' . $anclass4 . '">
	<td class="t_f ' . $ansign4 . '">' . $an4 . '</td>
	<td class="q_c">' . _ADMIN_CHART_CHOICE4 . ')</td>
	<td>' . $rec[6] . '</td>
	<td></td>
	</tr>
	</tbody></table>
	</div>
	</div>
	');
        $counter++;

    } while ($rec = mysql_fetch_row($questions));

    echo ('</article>
	');
}
?>

<?php include('../footer.php');?>