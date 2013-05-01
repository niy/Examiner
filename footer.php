<footer><small>&copy; 2013 Mohammad Ali Karimi. All rights reserved.</small></footer></div>
<script type="text/javascript">
    function downloadJSAtOnload() {
        var element = document.createElement("script");
        element.src = "../js/jquery-1.9.1.min.js";
        document.body.appendChild(element);
        element = document.createElement("script");
        element.src = "../js/jquery.powertip.min.js";
        document.body.appendChild(element);
        element = document.createElement("script");
        element.src = "../js/footable.js";
        document.body.appendChild(element);
        element = document.createElement("script");
        element.src = "../js/functions.js";
        document.body.appendChild(element);
    }
    if (window.addEventListener)
        window.addEventListener("load", downloadJSAtOnload, false);
    else if (window.attachEvent)
        window.attachEvent("onload", downloadJSAtOnload);
    else window.onload = downloadJSAtOnload;
</script>