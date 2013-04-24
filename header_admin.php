<?php

echo ('
    <head>
    <!--[if lte IE 7]><script src="../js/lte-ie7.js"></script><![endif]-->
    <!--[if lt IE 9]>
	<script src="http://css3-mediaqueries-js.googlecode.com/files/css3-mediaqueries.js"></script>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="../css/style.css" media="all">
    <link rel="stylesheet" type="text/css" href="../css/jquery.powertip.min.css" media="all">
    <title>' . _TITLE . '</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="title" content="Examiner Online Examination Management System">
    <meta name="description" content="Examiner is a online examination management system.">
    <meta name="author" content="Mohammad Ali Karimi">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <link rel="shortcut icon" href="../favicon.ico">
    ');
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
    <header class="clearfix">
        <h1><div id="floater"></div><a id="logo" class="logo" href="../admin">Examiner</a></h1>

');

?>