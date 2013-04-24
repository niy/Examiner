<?php

session_start();

if (!isset($_REQUEST["run"]) || !isset($_SESSION['examiner_user']))
    {
        header("Refresh: 5; url=index.php");
        include('main.php');
        die('
                <article class="msg">
                <div class="content">

                <div class="error_box clearfix" style="width:21em;">
                <div class="box_icon" data-icon="w" aria-hidden="true"></div>
                <div class="content clearfix">' . _ADMIN_NOT_ALLOWED . '</div>
                </div>
                </div>
                </article>

                <article id="wait"><div class="content box">
                <div class="content wait"><div data-icon="9" aria-hidden="true" class="grid_img"></div>
                <div class="content grid_txt">Please Wait...</div></div>
                </div>
                </article>

                <footer><p>&copy; Copyright 2013 Mohammad Ali Karimi. All rights reserved.</p></footer></div></body>
                </html>
        ');
    }
include('test_main.php');
include('test_header.php');
$check_default = mysql_query("SELECT * FROM tests WHERE be_default = '1'", $db);

if (!$rec = mysql_fetch_row($check_default))
{
    die('
		<article class="msg">
		<div class="content">

		<div class="error_box clearfix" style="width:21em;">
			<div class="box_icon" data-icon="w" aria-hidden="true"></div>
			<div class="content clearfix">' . _NO_DEFAULT . '</div>
		</div>

		</div>
		</article><footer><p>&copy; Copyright 2013 Mohammad Ali Karimi. All rights reserved.</p></footer></div></body></html>
		');
}
else
{
    $uid_session = $_SESSION['examiner_user'];
    $uid_session = mysql_query("SELECT * FROM users WHERE userid = '$uid_session'", $db);
    $uid_session = mysql_fetch_row($uid_session);
    $check_hold = mysql_query("SELECT * FROM user_test WHERE user_id='$uid_session[0]' AND test_id='$rec[0]'", $db);

    if ($check_hold=mysql_fetch_row($check_hold))
        {
            die('
				<article class="msg">
				<div class="content">

				<div class="error_box clearfix" style="width:21em;">
					<div class="box_icon" data-icon="w" aria-hidden="true"></div>
					<div class="content clearfix">' . _EXAM_SESSION_HAVE_HELD1 . ' ' . $_SESSION['examiner_user'] . ' ' . _EXAM_SESSION_HAVE_HELD2 . '</div>
				</div>
				<div id="back" class="button_wrap">
					<a id="back_b" class="button" href="result"><div data-icon="c" aria-hidden="true" class="grid_img"></div>
					<div class="grid_txt">' . _EXAM_SHOW_RESULT . '</div></a>
				</div>

				</div>
				</article><footer><p>&copy; Copyright 2013 Mohammad Ali Karimi. All rights reserved.</p></footer></div></body>
				</html>
			');
        }

    if ($rec[7] == 1) {
        $align = "right";
        $rtl_input = "rtl";
    } else {
        $align = "left";
        $rtl_input = "ltr";
    }
    $uid = $_SESSION['examiner_user'];
    $uid = mysql_query("SELECT * FROM users WHERE userid = '$uid'", $db);
    $uid = mysql_fetch_row($uid);
    $test_date = date("F j, Y, g:i a");
    $sqlstring =
        "INSERT INTO user_test (user_id, test_id, date, time_length) VALUES ('$uid[0]', '$rec[0]', NOW(),'$rec[6]:00')";
    $result = mysql_query($sqlstring, $db);

    if (!$result) {
        die('Could not INSERT INTO user_test:' . mysql_error());
    }
    $user_test_id = mysql_insert_id();
    $hour = 0;

    if ($rec[6] > 60) {
        $hour = (int)($rec[6] / 60);
        $minute = ($rec[6] % 60);
    } else
        $minute = $rec[6];


    ?>
    <script language="javascript" type="text/javascript">
        startday = new Date();

        clockStart = startday.getTime();
        window.onload = Go;
    </script>
    <?php
    echo ('
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
        if((hours+'.$hour.'> 11) && (hours+'.$hour.'< 24)){
            var pm = "PM"
        } else {
            var pm = "AM"
        }
        if(hours+'.$hour.'> 12){
            hours = hours+'.$hour.'-12
        } else {
            hours = hours+'.$hour.'
        }

        TargetDate = ""+month+" "+day+","+year+" "+hours+":"+minutes+":"+seconds+" "+pm+"";
        BackColor = "red";
        ForeColor = "white";
        CountActive = true;
        CountStepper = -1;
        LeadingZero = true;
        if ('.$hour.'>0){
            DisplayFormat = "%%H%% '._EXAM_HOURS.'&nbsp;'._EXAM_TIME_AND.' %%M%% '._EXAM_MINUTES.'&nbsp;'._EXAM_TIME_AND.' %%S%% '._EXAM_SECONDS.'";
        } else {
            DisplayFormat = "%%M%% '._EXAM_MINUTES.'&nbsp;'._EXAM_TIME_AND.' %%S%% '._EXAM_SECONDS.'";
        }
        FinishMessage = "'._EXAM_ENDED.'";


    </script>');

    echo ('<script language="javascript">
	function calcage(secs, num1, num2) {
      s = ((Math.floor(secs/num1))%num2).toString();
      if (LeadingZero && s.length < 2)
        s = "0" + s;
      return "<b>" + s + "</b>";
    }

    function CountBack(secs, t) {

        if (secs < (0.15 * t)) {
            document.getElementById("cntdwn").className = "content cntdwn warn";
        }
        if (secs < (0.07 * t)) {
            document.getElementById("cntdwn").className = "content cntdwn incorrect_sign";
        }
        if (secs < 0) {
            ' . _ONBEFORE3 . '
            //location.href="result";
            document.getElementById("cntdwn").innerHTML = FinishMessage;

            return;
        }
        DisplayStr = DisplayFormat.replace(/%%D%%/g, calcage(secs,86400,100000));
        DisplayStr = DisplayStr.replace(/%%H%%/g, calcage(secs,3600,24));
        DisplayStr = DisplayStr.replace(/%%M%%/g, calcage(secs,60,60));
        DisplayStr = DisplayStr.replace(/%%S%%/g, calcage(secs,1,60));

        document.getElementById("cntdwn").innerHTML = DisplayStr;
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
    </script>');

    ?>
    <form method="POST" action="result" name="Time">
        <?php
        do {
            $counter = 1;
            $test_noq = mysql_query("SELECT * FROM questions WHERE test_id='$rec[0]'", $db);
            $test_noq = mysql_num_rows($test_noq);

            if ($test_noq > $rec[2])
                $test_noq = $rec[2];

            if ($rec[5] == 1)
                $result =
                    mysql_query("SELECT * FROM questions WHERE test_id = '$rec[0]' ORDER BY RAND() LIMIT $test_noq");
            else
                $result =
                    mysql_query("SELECT * FROM questions WHERE test_id = '$rec[0]' ORDER BY id LIMIT $test_noq");
            $rec2 = mysql_fetch_row($result);
            echo('<article id="show_test">');
            do {
                $insert_q = "INSERT INTO user_choice (user_test_id, q_id) VALUES ('$user_test_id', '$rec2[0]')";
                $insert_q = mysql_query($insert_q, $db);

                if (!$insert_q) {
                    die('Database query error:' . mysql_error());
                }
        ?>

                <?php
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
                ?>


                <?php
                $counter++;
            } while ($rec2 = mysql_fetch_row($result));

            echo ('
                    <input type="hidden" size="5" name="timespent">
                    <div class="button_wrap left clearfix">
                    <input class="button" type="submit" value="' . _EXAM_END2 . '" name="B1" ' . _ONBEFORE2 . '>
                    </div>
                    </form>
                    <div id="targetDiv"></div>
                    </article>
            ');

        } while ($rec = mysql_fetch_row($check_default));
        }
        ?>
        <SCRIPT>
            ap_showWaitMessage('waitDiv', 0);
            sc_fade();
        </SCRIPT>
<?php include('footer1.php');?>
        <SCRIPT>
            sc_fade();
        </SCRIPT>