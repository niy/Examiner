
function setup() {
    tinymce.init({
        selector: "textarea",
        plugins: [
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table contextmenu paste"
        ],
        theme_advanced_toolbar_align : "left",
        toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
    });
}
function sc_fade(){
    var target = $('.cntdwn');
    var targetHeight = target.outerHeight();

    $(document).scroll(function(e){
        var scrollPercent = (targetHeight - window.scrollY) / targetHeight;
        if(scrollPercent >= 0){
            target.css('opacity', 1);
        }
        else {
            target.css('opacity', 0.5);
        }
    });
}
function initStopwatch()
{
    var myTime = new Date();
    return ((myTime.getTime() - clockStart) / 1000);
}

function getSecs()
{
    var tSecs = Math.round(initStopwatch());
    var iSecs = tSecs % 60;
    var iMins = Math.round((tSecs - 30) / 60);
    var sSecs = "" + ((iSecs > 9) ? iSecs : "0" + iSecs);
    var sMins = "" + ((iMins > 9) ? iMins : "0" + iMins);
    document.Time.timespent.value = sMins + ":" + sSecs;
    window.setTimeout('getSecs()', 1000);
}

function Go()
{
    window.setTimeout('getSecs()', 1)
}

function showhide(id)
{
    if (document.getElementById)
    {
        obj = document.getElementById(id);

        if (obj.style.display == "none")
        {
            obj.style.display = "";
        }

        else
        {
            obj.style.display = "none";
        }
    }
}

var sixteenth = -62;

var seventeenth = 21;
var eighteenth = document.all;
var nineteenth = document.getElementById && !document.all;
var twentieth = false;

if (eighteenth || nineteenth)
    var first2 =
        document.all
            ? document.all["fifteenth"]
            : document.getElementById
            ? document.getElementById("fifteenth")
            : "";

function second2()
{
    return (document.compatMode && document.compatMode != "BackCompat") ? document.documentElement : document.body;
}

function third2(fourth2, fifth2, sixth2)
{
    if (nineteenth || eighteenth)
    {
        if (typeof sixth2 != "undefined")
            first2.style.width = sixth2 + "px";

        if (typeof fifth2 != "undefined" && fifth2 != "")
            first2.style.backgroundColor = fifth2;
        first2.innerHTML = fourth2;
        twentieth = true;
        return false;
    }
}

function fifteenth3(e)
{
    if (twentieth)
    {
        var sixteenth3 = (nineteenth) ? e.pageX : event.x + second2().scrollLeft;
        var seventeenth3 = (nineteenth) ? e.pageY : event.y + second2().scrollTop;
        var eighteenth3 =
            eighteenth
                && !window.opera
                ? second2().clientWidth - event.clientX - sixteenth
                : window.innerWidth - e.clientX - sixteenth - 20;
        var nineteenth3 =
            eighteenth
                && !window.opera
                ? second2().clientHeight - event.clientY - seventeenth
                : window.innerHeight - e.clientY - seventeenth - 20;
        var twentieth3 = (sixteenth < 0) ? sixteenth * (-1) : -960;

        if (eighteenth3 < first2.offsetWidth)
            first2.style.left = eighteenth
                ? second2().scrollLeft + event.clientX - first2.offsetWidth + "px"
                : window.pageXOffset + e.clientX - first2.offsetWidth + "px";

        else if (sixteenth3 < twentieth3)
            first2.style.left = "5px";

        else
            first2.style.left = sixteenth3 + sixteenth + "px";

        if (nineteenth3 < first2.offsetHeight)
            first2.style.top = eighteenth
                ? second2().scrollTop + event.clientY - first2.offsetHeight - seventeenth + "px"
                : window.pageYOffset + e.clientY - first2.offsetHeight - seventeenth + "px";

        else
            first2.style.top = seventeenth3 + seventeenth + "px";
        first2.style.visibility = "visible";
        first2.style.display = "block";
    }
}

function first4()
{
    if (nineteenth || eighteenth)
    {
        twentieth = false;
        first2.style.visibility = "hidden";
        first2.style.display = "none";
        first2.style.left = "0px";
        first2.style.backgroundColor = '';
        first2.style.width = '';
    }
}
document.onmousemove = fifteenth3;

function examinerPopUp(url_pop,name,etc) {

    var lef = (window.screen.width-500)/2;
    var to = (window.screen.height-400)/2;

    Yaldex=window.open(url_pop,'accwindow','width=500,height=400,left='+lef+',top='+to);
    Yaldex.focus();
}
$('.admin_menu a').powerTip({
    fadeInTime: 60,
    fadeOutTime:40,
    closeDelay:50,
    smartPlacement:true,
    placement: 's'
});