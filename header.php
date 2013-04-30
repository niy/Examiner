<?php
echo ('
    <head>
    <!--[if lte IE 7]><script src="js/lte-ie7.js"></script><![endif]-->
    <!--[if lt IE 9]>
	<script src="http://css3-mediaqueries-js.googlecode.com/files/css3-mediaqueries.js"></script>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="css/style.css" media="all">
    <link rel="stylesheet" type="text/css" href="css/jquery.powertip.min.css" media="all">
    <title>' . _TITLE . '</title>
    <meta charset="utf-8">');
    if (isset($_SERVER['HTTP_USER_AGENT'])&& (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false))
        header('X-UA-Compatible: IE=edge,chrome=1');
    echo('
    <meta name="title" content="Examiner Online Examination Management System">
    <meta name="description" content="Examiner is a online examination management system.">
    <meta name="author" content="Mohammad Ali Karimi">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <link rel="shortcut icon" href="favicon.ico">');
    if (isset($system_align)) {
        echo ('
        <style>
        .info_box ul {float:'.$system_align.';}
        .info_box .box_icon {float:'.$system_align.';}
        </style>
        ');
    }
    echo ('
    </head>
    <body>
    <div id="wrap">
    <header id="user">
        <div id="head"><div id="floater"></div><h1><a id="logo" class="logo" href="index">Examiner</a></h1></div>
        ');
if (isset ($_SESSION['examiner_user'])) {
    echo ('
        <nav class="admin_menu">
            <ul>
                <li><a title="' ._EXAM_ENTER_WITH_NEW_USER_NAME. '" href="logout"><span data-icon="l" aria-hidden="true"></span></a></li>
            </ul>
        </nav>
        ');
}
    echo ('</header>');
?>