<?php

require_once ('config.php');

$result = $db->db_query("select * from settings where id = '1'");

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
    $rec=$db->single();
    $system_language=$rec[3];
    include_once('language/' . $rec[3] . '.php');

    if ($rec[4] == 1)
        echo ('<!doctype html><html dir="rtl">');
    else
        echo ('<!doctype html><html>');
    $result_rtl=$db->db_query("select * from settings where id = '1'");
    $rtl_array=$db->single();

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
