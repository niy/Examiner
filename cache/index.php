<?php
// a function to receive an write some data into a file
function get_and_write($url, $cache_file) {
    $string = file_get_contents($url);
    $f = fopen($cache_file, 'w');
    fwrite ($f, $string, strlen($string));
    fclose($f);
    return $string;
}

// a function that opens and and puts the data into a single var
function read_content($path) {
    $f = fopen($path, 'r');
    $buffer = '';
    while(!feof($f)) {
        $buffer .= fread($f, 2048);
    }
    fclose($f);
    return $buffer;
}

$cache_file = 'cached/cache.page.php';
$url = 'index';

if (!empty($_GET['p']) && preg_match('/^[a-z\-]*$/', $_GET['p'])) {
    $cache_file = 'cached/cache-'.$_GET['p'].'.php';
    $url = $_GET['p'];
} else {
    header ("HTTP/1.1 301 Moved Permanently");
    header("Location: ".$url);
    exit;
}

if (file_exists($cache_file)) { // is there a cache file?
    $timedif = (time() - filemtime($cache_file)); // how old is the file?
    if ($timedif < 3600*24) { // get a new file 24 hours
        $html = read_content($cache_file); // read the content from cache
    } else { // create a new cache file
        $html = get_and_write($url, $cache_file);
    }
} else { // no file? create a cache file
    $html = get_and_write($url, $cache_file);
}
echo $html;
exit;
?>