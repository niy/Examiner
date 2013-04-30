<!doctype html>
<html>
    <head>
        <!--[if lte IE 7]><script src="js/lte-ie7.js"></script><![endif]-->
        <!--[if lt IE 9]>
        <script src="http://css3-mediaqueries-js.googlecode.com/files/css3-mediaqueries.js"></script>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <link rel="stylesheet" type="text/css" href="../css/style.css" media="all">
        <link rel="stylesheet" type="text/css" href="css/style.css" media="all">
        <title>Examiner - 404</title>
        <meta charset="utf-8">
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
            <article id="a404">
                <div class="content box">
                    <h1><div data-icon="&#x22;" aria-hidden="true"></div>Whoops!</h1>
                    <div>Whatever you're lookin' for, it's not here!</div>
                    <?php
                    if (isset($_GET) && count($_GET)>0){
                        $i=0;
                        $array=$_GET;
                        echo('<div class="null small">Oh! and what the heck is ');
                        if (count($array)==1) {
                            $key = key($array);
                            echo(htmlspecialchars($key));
                            echo (' anyway?</div>');
                        }
                        elseif (count($array)>1 && count($array)<5) {
                            while ($key = key($array)) {
                                $i++;
                                if ($i==count($array)) {
                                    echo($key);
                                }
                                elseif ($i==count($array)-1) {
                                    echo(htmlspecialchars($key). ' or ');
                                }
                                else {
                                    echo(htmlspecialchars($key). ', ');
                                }
                                next($array);
                            }
                            echo (' anyway?</div>');
                        }
                        elseif (count($array)>5) {
                            echo('wrong with you? <div class="incorrect">GET A JOB!</div></div>');
                        }
                    }

                    ?>
                    <div id="back" class="button_wrap clearfix">
                        <a class="button" id="back_b" href="index"><div data-icon="h" aria-hidden="true" class="grid_img"></div>
                            <div class="grid_txt">Take me Home</div></a>
                    </div>
                </div>
            </article>
            <footer>
                <small>&copy; Copyright 2013 Mohammad Ali Karimi. All rights reserved.</small>
            </footer>
        </div>
    </body>
</html>