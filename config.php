<?php

/************ DATABASE CONFIGURATION ************/
/*	This parameters are used to connect to		*/
/*	your server's database.                 	*/
/*	Usually this information is provided to you	*/
/*	by your web-hosting service provider.   	*/
/*			                            		*/
define("_DBHOST","localhost");
define("_DBUSER","root");
define("_DBPASS","toor");
define("_DBNAME","examiner");
require_once ('inc/db.php');
$db = new db();
/*												*/
/************************************************/

/******************** DEBUG *********************/
/*	When set to "on" you can test-drive	the		*/
/*	software using a predefined username and	*/
/*	password. (Values= on | off)				*/
/*												*/
/*			||----------------------||			*/
/*			||	Username:	test	||			*/
/*			||	Password:	test	||			*/
/*			||----------------------||			*/
/*												*/
define("_DEBUG","on");							//
/*												*/
/************************************************/

?>