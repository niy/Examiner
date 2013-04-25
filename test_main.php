<?php

require_once ('config.php');
$db = mysql_connect (_DBHOST,_DBUSER,_DBPASS);
mysql_select_db (_DBNAME,$db);
$result = mysql_query ("select * from settings where id = '1'", $db);

if (!$result){
    require_once ('language/farsi.php');
    echo ('<!doctype html><html dir="rtl">');
    include ('index_header.php');
    echo('
		<article class="msg">

		<div class="error_box clearfix">
			<div class="box_icon" data-icon="w" aria-hidden="true"></div>
			<div class="content clearfix">' . _EXAMINER_INSTALL_FARSI1 . '</div>
		</div>

		</article>');
    include ('footer1.php');
    include('footer_end.php');
    die();
    }
else
    {
    $rec=mysql_fetch_row($result);
    $system_language=$rec[3];
    include_once('language/' . $rec[3] . '.php');

    if ($rec[4] == 1)
        echo ('<!doctype html><html dir="rtl">');
    else
        echo ('<!doctype html><html>');
    $result_rtl=mysql_query("select * from settings where id = '1'", $db);
    $rtl_array=mysql_fetch_row($result_rtl);

    if ($rtl_array[4] == 1)
        {
        $align="right";
        $rtl_input="rtl";
        }
    else
        {
        $align="left";
        $rtl_input="ltr";
        }
    include('index_header.php');
    }
//////////////bg
?>
