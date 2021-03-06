<?php

session_start();

if (!isset($_REQUEST["run"]) || !isset($_SESSION['examiner_user']))
    {
        header("Refresh: 5; url=index.php");
        include('main.php');
        echo('
                <article class="msg">

                <div class="error_box clearfix">
                <div class="box_icon" data-icon="w" aria-hidden="true"></div>
                <div class="content clearfix">' . _ADMIN_NOT_ALLOWED . '</div>
                </div>
                </article>

                <article id="wait"><div class="content box">
                <div class="content wait"><div data-icon="9" aria-hidden="true" class="grid_img"></div>
                <div class="content grid_txt">Please Wait...</div></div>
                </article>');
        include ('footer1.php');
        include('footer_end.php');
        die();
    }
include('test_main.php');
include('test_header.php');
$check_default = $db->db_query("SELECT * FROM tests WHERE be_default = '1'");
$exam = $db->single();

if (!$rec = $db->single())
{
    echo('
		<article class="msg">

		<div class="error_box clearfix">
			<div class="box_icon" data-icon="w" aria-hidden="true"></div>
			<div class="content clearfix">' . _NO_DEFAULT . '</div>
		</div>

		</article>');
    include ('footer1.php');
    include('footer_end.php');
    die();
}
else
{
    $uid_session = $_SESSION['examiner_user'];
    if (!($uid_session=="test" || _DEBUG=="off")){
        $pars = array(
            ':uid_session' => $uid_session
        );
        $uid_session = $db->db_query("SELECT * FROM users WHERE userid = :uid_session",$pars);
        $uid_session = $db->single();
        $pars = array(
            ':rec' => $rec[0],
            ':uid_session' => $uid_session[0]
        );
        $check_hold = $db->db_query("SELECT * FROM user_test WHERE user_id=:uid_session AND test_id=:rec",$pars);

        if ($check_hold=$db->single())
            {
                echo('
                    <article class="msg">

                    <div class="error_box clearfix">
                        <div class="box_icon" data-icon="w" aria-hidden="true"></div>
                        <div class="content clearfix">' . _EXAM_SESSION_HAVE_HELD1 . ' ' . $_SESSION['examiner_user'] . ' ' . _EXAM_SESSION_HAVE_HELD2 . '</div>
                    </div>
                    <div id="back" class="button_wrap clearfix">
                        <a id="back_b" class="button" href="result"><div data-icon="c" aria-hidden="true" class="grid_img"></div>
                        <div class="grid_txt">' . _EXAM_SHOW_RESULT . '</div></a>
                    </div>

                    </article>');
                include ('footer1.php');
                include('footer_end.php');
                die();
            }
    }
    if ($rec[7] == 1) {
        $align = "right";
        $rtl_input = "rtl";
    } else {
        $align = "left";
        $rtl_input = "ltr";
    }
    $uid = $_SESSION['examiner_user'];
    if (!($uid=="test") || _DEBUG=="off"){
        $pars = array(
            ':uid' => $uid
        );
        $uid = $db->db_query("SELECT * FROM users WHERE userid = :uid",$pars);
        $uid = $db->single();
        $pars = array(
            ':uid' => $uid[0],
            ':rec0' => $rec[0],
            ':rec6' => $rec[6]
        );
        $sqlstring = "INSERT INTO user_test (user_id, test_id, date, time_length) VALUES (:uid, :rec0, NOW(),:rec6':00')";
        $result = $db->db_query($sqlstring,$pars);
        $user_test_id = $db->lastInsertId();

    } elseif ($uid=="test" && _DEBUG=="on") {
        $sqlstring = "SELECT * FROM user_test WHERE user_id=1 AND test_id=:rec0";
        $pars = array(
            ':rec0' => $rec[0],
        );
        $result = $db->db_query($sqlstring,$pars);
        $uc_r = $db->single();
        $num_results = $db->rowCount();
        if($num_results>0) {
            $pars = array(
                ':rec0' => $rec[0],
                ':rec6' => $rec[6],
            );
            $sqlstring = 'UPDATE user_test SET test_id=:rec0, date=NOW(), time_length=:rec6":00" WHERE user_id=1 AND test_id=:rec0';
            $r = $db->db_query($sqlstring,$pars);
            $user_test_id=$uc_r[0];
        }
        else {
            $sqlstring = "INSERT INTO user_test (user_id, test_id, date, time_length) VALUES (1, :rec0, NOW(),:rec6':00')";
            $pars = array(
                ':rec0' => $rec[0],
                ':rec6' => $rec[6],
            );
            $r = $db->db_query($sqlstring,$pars);
            $user_test_id = $db->lastInsertId();
        }
    }
    $test_date = date("F j, Y, g:i a");
    $hour = 0;

    if ($rec[6] > 60) {
        $hour = (int)($rec[6] / 60);
        $minute = ($rec[6] % 60);
    } else
        $minute = $rec[6];

    echo ('<form method="POST" action="result" name="Time">');

            $rec = $exam;

            $counter = 1;
    $pars = array(
        ':rec0' => $rec[0]
    );
            $test_noq = $db->db_query("SELECT * FROM questions WHERE test_id=:rec0",$pars);
            $test_noq = $db->rowCount();

            if ($test_noq > $rec[2])
                $test_noq = $rec[2];

    $pars = array(
        ':rec0' => $rec[0],
    );
            if ($rec[5] == 1)
                $result = $db->db_query("SELECT * FROM questions WHERE test_id = :rec0 ORDER BY RAND() LIMIT ".$test_noq,$pars);
            else
                $result = $db->db_query("SELECT * FROM questions WHERE test_id = :rec0 ORDER BY id LIMIT ".$test_noq,$pars);
            $recs2 = $db->resultset();
            echo('<article id="show_test">');
            foreach ($recs2 as $i => $rec2) {
                $pars = array(
                    ':user_test_id' => $user_test_id,
                    ':rec2' => $rec2[0]
                );
                if ($uid=="test" && _DEBUG=="on"){
                    $uc_chk=$db->db_query("select * from user_choice where user_test_id=:user_test_id && q_id=:rec2",$pars);
                    $uc_rec=$db->single();
                    if ($uc_rec<=0){
                        $insert_q = "INSERT INTO user_choice (user_test_id, q_id) VALUES (:user_test_id, :rec2)";
                        $insert_q = $db->db_query($insert_q,$pars);
                    } else {
                        $insert_q="UPDATE user_choice SET answer=NULL WHERE user_test_id=:user_test_id && q_id=:rec2";
                        $insert_q=$db->db_query($insert_q,$pars);
                    }
                } else {
                    $insert_q = "INSERT INTO user_choice (user_test_id, q_id) VALUES (:user_test_id, :rec2)";
                    $insert_q = $db->db_query($insert_q,$pars);
                }
                echo ('
                        <div class="question">
                        <div class="q_head">
                            <div class="q_num">' . $counter . '</div>
                            <div class="q_sign null_q">
                            <div class="content">
                            <label for="' . $rec2[0] . '0">' . _INSERT_UNANSWERED . '</label>
                            <input type="radio" value="" id="' . $rec2[0] . '0" name="' . $rec2[0] . '"
                            onclick="getData(\'insert?user_test_id=' . $user_test_id . '&q_id=' . $rec2[0] . '&answer=0\', \'targetDiv\')">
                            </div>
                            </div>
                        </div>
                        <div class="q_body content">
                        <div class="q content">' . $rec2[2] . '</div>
                        <table class="choices content"><tbody>
                        <tr>
                        <td class="t_f">
                        <input type="radio" value="1" id="' . $rec2[0] . '1" name="' . $rec2[0] . '"
                        onclick="getData(\'insert?user_test_id=' . $user_test_id . '&q_id=' . $rec2[0] . '&answer=1\', \'targetDiv\')">
                        </td>
                        <td class="q_c"><label for="' . $rec2[0] . '1">' . _ADMIN_CHART_CHOICE1 . ')</label></td>
                        <td><label for="' . $rec2[0] . '1">' . $rec2[3] . '</label></td>
                        <td></td>
                        </tr>
                        <tr>
                        <td class="t_f">
                        <input type="radio" value="2" id="' . $rec2[0] . '2" name="' . $rec2[0] . '"
                        onclick="getData(\'insert?user_test_id=' . $user_test_id . '&q_id=' . $rec2[0] . '&answer=2\', \'targetDiv\')">
                        </td>
                        <td class="q_c"><label for="' . $rec2[0] . '2">' . _ADMIN_CHART_CHOICE2 . ')</label></td>
                        <td><label for="' . $rec2[0] . '2">' . $rec2[4] . '</label></td>
                        <td></td>
                        </tr>
                        <tr>
                        <td class="t_f">
                        <input type="radio" value="3" id="' . $rec2[0] . '3" name="' . $rec2[0] . '"
                        onclick="getData(\'insert?user_test_id=' . $user_test_id . '&q_id=' . $rec2[0] . '&answer=3\', \'targetDiv\')">
                        </td>
                        <td class="q_c"><label for="' . $rec2[0] . '3">' . _ADMIN_CHART_CHOICE3 . ')</label></td>
                        <td><label for="' . $rec2[0] . '3">' . $rec2[5] . '</label></td>
                        <td></td>
                        </tr>
                        <tr>
                        <td class="t_f">
                        <input type="radio" value="4" id="' . $rec2[0] . '4" name="' . $rec2[0] . '"
                        onclick="getData(\'insert?user_test_id=' . $user_test_id . '&q_id=' . $rec2[0] . '&answer=4\', \'targetDiv\')">
                        </td>
                        <td class="q_c"><label for="' . $rec2[0] . '4">' . _ADMIN_CHART_CHOICE4 . ')</label></td>
                        <td><label for="' . $rec2[0] . '4">' . $rec2[6] . '</label></td>
                        <td></td>
                        </tr>
                        </tbody></table>
                        </div>
                        </div>
                ');
                $counter++;

            }

            echo ('
                    <input type="hidden" size="5" name="timespent">
                    <div class="button_wrap left clearfix">
                    <input class="button" type="submit" value="' . _EXAM_END2 . '" name="B1" ' . _ONBEFORE2 . '>
                    </div>
                    </form>
                    <div id="targetDiv"></div>
                    </article>
            ');

        }
        ?>

        <?php
        include('footer1.php');
        echo ('
        <script type="text/javascript" type="text/javascript">
            startday = new Date();

            clockStart = startday.getTime();
            window.onload = Go;
        </script>
        <SCRIPT>
            ap_showWaitMessage(\'waitDiv\', 0);
            sc_fade();
        </SCRIPT>
        <script type="text/javascript">

            var currentTime = new Date()
            var month = currentTime.getMonth() + 1
            var day = currentTime.getDate()
            var year = currentTime.getFullYear()
            var hours = currentTime.getHours()
            var seconds = currentTime.getSeconds()
            var minutes = currentTime.getMinutes()
            if (minutes+'.$minute.'>59){
                minutes = minutes+'.$minute.'-59
                hours++
            } else {
                minutes = minutes+'.$minute.'
            }
            /*if((hours+'.$hour.'> 11) && (hours+'.$hour.'< 24)){
                var pm = "PM"
            } else {
                var pm = "AM"
            }*/
            //if(hours+'.$hour.'> 12){
               // hours = hours+'.$hour.'-12
            //} else {
                //hours = hours+'.$hour.'
            //}
            if(hours+'.$hour.'>= 24){
                hours = hours+'.$hour.'-24
                day = day+1;
            } else {
                hours = hours+'.$hour.'
                day = day;
            }

            TargetDate = ""+month+"/"+day+"/"+year+" "+hours+":"+minutes+":"+seconds;
            CountActive = true;
            CountStepper = -1;
            LeadingZero = true;
            if ('.$hour.'>0){
                DisplayFormat = "%%H%% '._EXAM_HOURS.'&nbsp;'._EXAM_TIME_AND.' %%M%% '._EXAM_MINUTES.'&nbsp;'._EXAM_TIME_AND.' %%S%% '._EXAM_SECONDS.'";
            } else {
                DisplayFormat = "%%M%% '._EXAM_MINUTES.'&nbsp;'._EXAM_TIME_AND.' %%S%% '._EXAM_SECONDS.'";
            }
            FinishMessage = "'._EXAM_ENDED.'";

        function calcage(secs, num1, num2) {
          s = ((Math.floor(secs/num1))%num2).toString();
          if (LeadingZero && s.length < 2)
            s = "0" + s;
          return "<b>" + s + "</b>";
        }

        function CountBack(secs, t) {

            if (secs < (0.15 * t)) {
                $(".cntdwn").addClass("warn");
            }
            if (secs < (0.07 * t)) {
                $(".cntdwn").removeClass("warn")
                $(".cntdwn").addClass("incorrect_sign");
            }
            if (secs < 0) {
                ' . _ONBEFORE3 . '
                $(".cntdwn").addClass("end");
                 $("#cntdwn").html(FinishMessage);
                 ap_showWaitMessage(\'waitDiv\', 1);
                 location.href="result";

                return;
            }
            DisplayStr = DisplayFormat.replace(/%%D%%/g, calcage(secs,86400,100000));
            DisplayStr = DisplayStr.replace(/%%H%%/g, calcage(secs,3600,24));
            DisplayStr = DisplayStr.replace(/%%M%%/g, calcage(secs,60,60));
            DisplayStr = DisplayStr.replace(/%%S%%/g, calcage(secs,1,60));

           $("#cntdwn").html(DisplayStr);
            if (CountActive){
            s=secs+CountStepper;
            setTimeout(CountBack, SetTimeOutPeriod, s, t);
            }
        }


        if (typeof(TargetDate)=="undefined")
          TargetDate = "12/31/2020 5:00 AM";
        if (typeof(DisplayFormat)=="undefined")
          DisplayFormat = "%%D%% Days, %%H%% Hours, %%M%% Minutes, %%S%% Seconds.";
        if (typeof(CountActive)=="undefined")
          CountActive = true;
        if (typeof(FinishMessage)=="undefined")
          FinishMessage = "";
        if (typeof(CountStepper)!="number")
          CountStepper = -1;
        if (typeof(LeadingZero)=="undefined")
          LeadingZero = true;


        CountStepper = Math.ceil(CountStepper);
        if (CountStepper == 0)
          CountActive = false;
        var SetTimeOutPeriod = (Math.abs(CountStepper)-1)*1000 + 990;
        var dthen = new Date(TargetDate);
        var dnow = new Date();

        if(CountStepper>0)
          ddiff = new Date(dnow-dthen);
        else
          ddiff = new Date(dthen-dnow);
        gsecs = Math.floor(ddiff.valueOf()/1000);
        CountBack(gsecs,gsecs);
        </script>
        <SCRIPT>
            sc_fade();
        </SCRIPT>
        ');
        include('footer_end.php');
        ?>
