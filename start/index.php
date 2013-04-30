
<?php
            header("Refresh: 5; url=../index.php");
?>
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
            <title>Welcome to Examiner</title>
            <meta charset="utf-8">
                <?php
                if (isset($_SERVER['HTTP_USER_AGENT'])&& (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false))
                    header('X-UA-Compatible: IE=edge,chrome=1');
                ?>
            <meta name="description" content="Examiner is a online examination management system.">
            <meta name="author" content="Mohammad Ali Karimi">
            <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
            <link rel="shortcut icon" href="favicon.ico">
            </head>
            <body>
            <div id="wrap">
            <header>
                <div id="head"><div id="floater"></div><h1><a id="logo" class="logo" href="index">Examiner</a></h1></div>
            </header>
            <article id="wait"><div class="content box">
            <div class="content wait"><div data-icon="9" aria-hidden="true" class="grid_img"></div>
            <div class="content grid_txt">Please Wait...</div></div>
            </div>
            </article>
<?php
            include('../footer.php');
            include('../footer_end.php');
            die();

?>