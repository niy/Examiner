<?php
$prompt = 0;

if ($prompt==1)
{
define("_ONBEFORE2",'onclick="quit(\'ch\');"');
define("_ONBEFORE3",'quit(\'ch\');');
$onbefore = 'onbeforeunload="quit(\'prompt\');"';
}
else
{
define("_ONBEFORE2",'');
define("_ONBEFORE3",'');
$onbefore = '';
}
echo ('<head>
    <!--[if lte IE 7]><script src="../js/lte-ie7.js"></script><![endif]-->
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
    <meta name="description" content="Examiner is a online examination management system.">
    <meta name="author" content="Mohammad Ali Karimi">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <link rel="shortcut icon" href="favicon.ico">');
if ($prompt==1)
{
echo ('
<script language="javascript">
var x;
function quit(what){

if (what=="ch")
{
x=1;
}

if (!(x==1))
{
event.returnValue="'._EXAM_CLOSE_WINDOW_ALERT.'"
}


} 
</script> ');
}
 echo ('<SCRIPT>

var DHTML = (document.getElementById || document.all || document.layers);

function ap_getObj(name)

{

if (document.getElementById)

{

return document.getElementById(name).style;

}

else if (document.all)

{

return document.all[name].style;

}

else if (document.layers)

{

return document.layers[name];

}

}

function ap_showWaitMessage(div,flag)

{

if (!DHTML) return;

var x = ap_getObj(div);

x.visibility = (flag) ? \'visible\':\'hidden\'

if(! document.getElementById)

if(document.layers)

x.left=280/2;

return true;

}

</SCRIPT>

<script type="text/javascript" language="javascript">
<!--

function do_err()
    {
        return true
    }
onerror=do_err;

function no_cp()
    {
        clipboardData.clearData();setTimeout("no_cp()",100)
    }
no_cp();

//-->
</script>
<script type="text/javascript">

function md(e) 
{ 
  try { if (event.button==2||event.button==3) return false; }  
  catch (e) { if (e.which == 3) return false; } 
}
document.oncontextmenu = function() { return false; }
document.ondragstart   = function() { return false; }
document.onmousedown   = md;

</script>
<SCRIPT type="text/javascript">
if (typeof document.onselectstart!="undefined") {
  document.onselectstart=new Function ("return false");
}
else{
  document.onmousedown=new Function ("return false");
  document.onmouseup=new Function ("return true");
}
</SCRIPT>
');

echo ('
    <script language = "javascript">
      var XMLHttpRequestObject = false;

      if (window.XMLHttpRequest) {
        XMLHttpRequestObject = new XMLHttpRequest();
      } else if (window.ActiveXObject) {
        XMLHttpRequestObject = new ActiveXObject("Microsoft.XMLHTTP");
      }

      function getData(dataSource, divID)
      {
        if(XMLHttpRequestObject) {
          var obj = document.getElementById(divID);
          XMLHttpRequestObject.open("GET", dataSource);

          XMLHttpRequestObject.onreadystatechange = function()
          {
            if (XMLHttpRequestObject.readyState == 4 &&
              XMLHttpRequestObject.status == 200) {
                obj.innerHTML = XMLHttpRequestObject.responseText;
            }
          }

          XMLHttpRequestObject.send(null);
        }
      }
    </script>
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
    <body '.$onbefore.'>
    <div id="wrap">
    ');

echo ('
    <div id=waitDiv>
    <div data-icon="9" aria-hidden="true" class="grid_img"></div>
    </div>
    <script>
      ap_showWaitMessage(\'waitDiv\', 1);
    </script>
');
?>