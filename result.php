<?php


session_start();

if (!isset($_SESSION['examiner_user']))
    {
    header('Location: 404');
    die('');
    }
else
    {
    $uid_session1=$_SESSION['examiner_user'];
    //session_destroy();
    include('main.php');
    echo ('
        <script type="text/javascript" language="javascript">
            function do_err()
                {
                    return true
                }
            onerror=do_err;
        </script>
    ');
    $check_default=mysql_query("select * from tests where be_default = '1'", $db);
    $check_default=mysql_fetch_row($check_default);
	
	echo ('<article id="show_stats"><form method="POST" action="logout">');
	
    if ($check_default[7] == 1)
        {
        $align="right";
        $rtl_input="rtl";
        }
    else
        {
        $align="left";
        $rtl_input="ltr";
        }
    $uid_session=mysql_query("select * from users where userid = '$uid_session1'", $db);
    $uid_session=mysql_fetch_row($uid_session);
    $choices1=
        mysql_query("SELECT * FROM user_test WHERE user_id = '$uid_session[0]' AND test_id = '$check_default[0]'", $db);

    if (isset($_REQUEST["timespent"]))
        {
        $timespent=$_REQUEST['timespent'];
        $update_time_length=
            "UPDATE user_test SET time_length='$timespent' WHERE user_id='$uid_session[0]' AND test_id='$check_default[0]'";
        $update_time_length=mysql_query($update_time_length, $db);

        if (!$update_time_length)
            {
            die('Could not INSERT INTO user_test:' . mysql_error());
            }
        }
		
		if (($check_default[9] == 0) && ($check_default[10] == 0))
	{
        die('
                <div class="msg">
                <div class="content">

                <div class="error_box clearfix" style="width:30em;">
                    <div class="box_icon" data-icon="w" aria-hidden="true"></div>
                    <div class="content clearfix">' . _SHOW_ANSWERS_AND_SHOW_MARK_NOT_ALLOWED . '</div>
                </div>

                <div id="back" class="button_wrap clearfix">
                    <input type="submit" class="button" value="' . _EXAM_END . '" name="B1">
                </div>

                </div>
                </div>
                </form><footer><p>&copy; Copyright 2013 Mohammad Ali Karimi. All rights reserved.</p></footer></div></body></html>
             ');
	}
    $choices=mysql_fetch_row($choices1);
    $user_test_id=$choices[0];
    $choices=mysql_query("SELECT * FROM user_choice WHERE user_test_id = '$choices[0]' order by id ASC");
    $rec=mysql_fetch_row($choices);
    ///////////////Number of Correct, Incorrect and Non-Answered Questions
    $num_correct_answers=0;
    $num_non_answered=0;
    $num_all_answers=mysql_query("select * from user_choice where user_test_id='$user_test_id'");
    $num_all_answers=mysql_num_rows($num_all_answers);
    ///////////////
    $counter=1;

	if ($check_default[9] == 1)
        {
            echo ('
            <section class="msg">
			<div class="content">
                <div class="info_box clearfix" style="width:17.5em;">
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
            <form method="POST" action="logout">
            <div class="clearfix pagehead">
            <h2>' . _EXAM_QUESTIONS . '</h2></div>');
        }
            $anclass1 = $anclass2 = $anclass3 = $anclass4 = "";
            $ansign1 = $ansign2 = $ansign3 = $ansign4 = "";
    do
        {
        $question=mysql_query("select * from questions where id = '$rec[2]'", $db);
        $question=mysql_fetch_row($question);

        ///////////////Answer Icon
        if ($question[7] == $rec[3])
            {
                $num_correct_answers++;
                $anu1=$anu2=$anu3=$anu4="";
                $answer = '<span data-icon="d" aria-hidden="true"></span>';
                $ansign = "correct_sign";
                $anclass1 = $anclass2 = $anclass3 = $anclass4 = "";
                $title=_EXAM_YOUR_ANSWER_IS_CORRECT;

            }
        else if ($rec[3] == "")
            {
                $answer = '<span data-icon="," aria-hidden="true"></span>';
                $ansign = "null_sign";
                $title=_EXAM_YOUR_ANSWER_IS_NULL;
                $anu1=$anu2=$anu3=$anu4="";
                $anclass1 = $anclass2 = $anclass3 = $anclass4 = "";
                $ansign1 = $ansign2 = $ansign3 = $ansign4 = "";
                $num_non_answered++;
            }
        else
            {
                $anu1 = $anu2 = $anu3 = $anu4 = "";
                $anclass1 = $anclass2 = $anclass3 = $anclass4 = "";
                $ansign1 = $ansign2 = $ansign3 = $ansign4 = "";

            switch ($rec[3]){
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
                default:
                    $anu4 = '';
                    $anclass4 = "";
                    $ansign4 = "";
            }
                $answer = '<span data-icon="." aria-hidden="true"></span>';
                $ansign = "incorrect_sign";
                $title=_EXAM_YOUR_ANSWER_IS_INCORRECT;
            }

            $an1 = $an2 = $an3 = $an4 = "";

        switch ($question[7]){
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
if ($check_default[9] == 1)
        {
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

    }
        $counter++;
        } while ($rec=mysql_fetch_row($choices));
	if ($check_default[9] == 1)
		{
		echo ('</section>');
		}

    $num_incorrect_answers=$num_all_answers - ($num_correct_answers + $num_non_answered);
	if ($check_default[10] == 0)
		{
            die('
                <div class="msg">
                <div class="content">

                <div class="error_box clearfix" style="width:30em;">
                    <div class="box_icon" data-icon="w" aria-hidden="true"></div>
                    <div class="content clearfix">' . _SHOW_MARK_NOT_ALLOWED . '</div>
                </div>

                <div id="back" class="button_wrap clearfix">
                    <input type="submit" class="button" value="' . _EXAM_END . '" name="B1">
                </div>

                </div>
                </div>
                </form><footer><p>&copy; Copyright 2013 Mohammad Ali Karimi. All rights reserved.</p></footer></div></body></html>
             ');

		}
	if ($check_default[9] == 0)
		{
            echo('
                <div class="msg">
                <div class="content">

                <div class="error_box clearfix" style="width:30em;">
                    <div class="box_icon" data-icon="w" aria-hidden="true"></div>
                    <div class="content clearfix">' . _SHOW_ANSWERS_NOT_ALLOWED . '</div>
                </div>

                </div>
                </div>
                ');
		}
    echo ('
            <section id="exam_result">
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

            </tbody></table>');

    if ($check_default[8] == 1)
        {
        $num_correct_answers_neg=$num_correct_answers - round(($num_incorrect_answers / 3), 2);

        echo ('<div class="info_box clearfix" style="width:20em; margin:1em;">
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
        }
    else
        {
        echo ('<div class="info_box clearfix" style="width:20em; margin:1em;">
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
            </div>');
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
        echo ('
        </table>
        </div>
        </div>
		<div id="back" class="button_wrap clearfix">
		<input type="submit" id="back_b" class="button" value="' . _EXAM_END . '">
		</div>
		</div>
		</section>
		</form>
		</article>');
    }
        ?>

<?php include('footer1.php');?>