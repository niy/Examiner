<!doctype html>
<html>
<head>
    <!--[if lte IE 7]><script src="../js/lte-ie7.js"></script><![endif]-->
    <!--[if lt IE 9]>
    <script src="http://css3-mediaqueries-js.googlecode.com/files/css3-mediaqueries.js"></script>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="../css/style.css" media="all">
    <link rel="stylesheet" type="text/css" href="../css/jquery.powertip.min.css" media="all">
    <title>Examiner</title>
<?php
    require_once ('../config.php');
if (isset($_REQUEST["language"])) {
	$admin_id = $_REQUEST["admin_id"];
	$password = $_REQUEST["password"];
	$language = $_REQUEST["language"];
	$rtl = $_REQUEST["rtl"];

} else {
	header('Location: ../index');
	die();
}

?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="title" content="Examiner Online Examination Management System">
    <meta name="description" content="Examiner is a online examination management system.">
    <meta name="author" content="Mohammad Ali Karimi">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <link rel="shortcut icon" href="favicon.ico">
    <style>
        .info_box ul {float: left;}
        .info_box .box_icon {float: left;}
    </style>
</head>
<body>
<div id="wrap">
    <header>
        <h1><div id="floater"></div><a id="logo" class="logo" href="index">Examiner</a></h1>
    </header>
    <article class="msg">
		<div class="info_box clearfix" >
			<div class="box_icon" data-icon="y" aria-hidden="true"></div>
			<div class="content clearfix">
				<h1></h1>
				<ul>
					<?php

					//Creating DataBase
					$db = mysql_connect(_DBHOST, _DBUSER, _DBPASS);
					if (!$db) {
						die ('Couldn not connect:' . mysql_error());
					}
					if (_DBNAME == "examiner") {
						if (mysql_query('CREATE DATABASE examiner CHARACTER SET utf8 COLLATE utf8_unicode_ci;', $db)) {
							echo "<li> Database created successfully</li>";
						} else {
							echo '<li class="incorrect"> Error at creating database: ' . mysql_error() . '</li>';
						}
						$db = mysql_connect(_DBHOST, _DBUSER, _DBPASS);
					}
					//Creating Tables
					$db_selected = mysql_select_db(_DBNAME, $db);
					if (!$db_selected) {
						die ('<li class="incorrect">Can\'t use examiner :' . mysql_error().'</li>');
					}
					//Users
					if (mysql_query('CREATE TABLE users (id INT NOT NULL AUTO_INCREMENT, FName VARCHAR(30), LName VARCHAR(30), fatherName VARCHAR(30), userid VARCHAR(20) NOT NULL, password VARCHAR(20) NOT NULL, email VARCHAR(50), PRIMARY KEY  (id))', $db)) {
						echo '<li>Table "users" created successfully</li>';
					} else {
						echo '<li class="incorrect">Error ocurred at creating table : ' . mysql_error() . '</li>';
					}
					//Tests
					if (mysql_query('CREATE TABLE tests (id INT NOT NULL AUTO_INCREMENT, TName VARCHAR(250), NOQ INT, be_default TINYINT(1) DEFAULT "0", prof_or_user TINYINT(1) DEFAULT "0", random TINYINT(1) DEFAULT "0", time INT, rtl TINYINT(1) DEFAULT "1", minus_mark TINYINT(1) DEFAULT "0", show_answers TINYINT(1) DEFAULT "1", show_mark TINYINT(1) DEFAULT "1", PRIMARY KEY (id))', $db)) {
						echo '<li>Table "tests" created successfully</li>';
					} else {
						echo '<li class="incorrect">Error ocurred at creating table : ' . mysql_error() . '</li>';
					}
					//Questions
					if (mysql_query('CREATE TABLE questions (id INT NOT NULL AUTO_INCREMENT, test_id INT NOT NULL, question TEXT NOT NULL, choice1 TEXT, choice2 TEXT, choice3 TEXT, choice4 TEXT, answer TINYINT(5) NOT NULL, PRIMARY KEY (id))', $db)) {
						echo '<li> Table "questions" created successfully</li>';
					} else {
						echo '<li class="incorrect">Error ocurred at creating table : ' . mysql_error() . '</li>';
					}
					//User_Test
					if (mysql_query('CREATE TABLE user_test (id INT NOT NULL AUTO_INCREMENT, user_id INT, test_id INT, date DATE, time_length TINYTEXT, PRIMARY KEY (id))', $db)) {
						echo '<li>Table "user_test" created successfully</li>';
					} else {
						echo '<li class="incorrect"> Error ocurred at creating table : ' . mysql_error() . '</li>';
					}
					//User_Test_Questions
					if (mysql_query('CREATE TABLE user_choice (id INT NOT NULL AUTO_INCREMENT, user_test_id INT NOT NULL, q_id INT NOT NULL, answer TINYINT(5), PRIMARY KEY (id))', $db)) {
						echo '<li>Table "user_choice" created successfully</li>';
					} else {
						echo '<li class="incorrect">Error ocurred at creating table : ' . mysql_error() . '</li>';
					}
					//System Settings
					if (mysql_query('CREATE TABLE settings (id INT NOT NULL, admin_id VARCHAR(20) NOT NULL, password VARCHAR(20) NOT NULL, language VARCHAR(30), rtl TINYINT(1), PRIMARY KEY (id))', $db)) {
						echo '<li>Table "settings" created successfully</li>';
					} else {
						echo '<li class="incorrect">Error ocurred at creating table : ' . mysql_error() . '</li>';
					}
                    //Debugging Account
                    if (mysql_query('INSERT INTO users (FName, LName, fatherName, userid, password, email) VALUES ("Tester", "Rodriguez", "Testing", "test", "test", "test@example.com")')){
                        echo ('<li>Debugging Account (test:test) created successfully</li>');
                    } else {
                        echo '<li class="incorrect">Error ocurred at creating Debugger User : ' . mysql_error() . '</li>';
                    }
					echo('
				</ul>
			</div>
		</div>
	');

	require_once ('../config.php');
	$db = mysql_connect(_DBHOST, _DBUSER, _DBPASS);
	mysql_select_db(_DBNAME, $db);
    $sqlstring = "INSERT INTO settings (id, admin_id, password, language, rtl) VALUES (\"1\", \"$admin_id\", \"$password\", \"$language\", \"$rtl\")";
    $result = mysql_query($sqlstring, $db);
	if (!$result) {
		die ('Database query error:' . mysql_error());
	} else {
	echo ('
		<div class="info_box clearfix">
			<div class="box_icon" data-icon="y" aria-hidden="true"></div>
			<div class="content clearfix">
			<h1>Examiner installed successfully...</h1>
			<p style="direction: rtl; text-align: right; margin-bottom: .5em;">
			<b>اگزمینر </b>به طور كامل نصب گرديد، براي ورود به كنترل‌پنل مديريت، بر روي دكمه زير كليك كنيد
			</p><p dir="ltr"><span lang="en-us">
			<span lang="en-us">Click the button below to enter the Control Panel.</span></p>
			</div>
		</div>
	<div id="back" class="button_wrap clearfix">
		<a id="back_b" class="button" href="../admin"><div data-icon="h" aria-hidden="true" class="grid_img"></div>
		<div class="grid_txt">Examiner\'s Control Panel</div></a>
	</div>
</article>');
 }
?>
<?php include('../footer.php');
 include('../footer_end.php');?>
