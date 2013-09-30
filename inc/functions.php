<?php

// makes current menu entry 'active'
// string $page to compare with $_GET

function active_url($page) {
    global $_params;
    if (isset($_params['1']) AND $_params['1'] == $page) {
        echo " class=\"active\"";
    }
}

// deletes session data and browser cookies

function delete_session() {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );

    session_destroy();
}

// sort array by date
// used by usort()

function cmp( $a, $b ) {
    return strtotime($a["date"]) - strtotime($b["date"]);
}

// get directory size
// return integer

function dirsize($directory) {
    $size = 0;
    foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory)) as $file){
        $size+=$file->getSize();
    }
    return $size;
} 

// convert bytes to human readable output

function format_bytes($size, $precision = 1) {
    $base = log($size) / log(1024);
    $suffixes = array('', 'k', 'M', 'G', 'T');   
    return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
}


// read a (log)file

function read_logfile($f) {
        if (file_exists($f)) {
        $fh = fopen($f, "r");
        echo "<pre class=\"pre-scrollable\">";
        while (!feof($fh)) {
           $line = fgets($fh);
           echo $line;
        }
        echo "</pre>";
        fclose($fh);
    } else { 
        echo "<div class=\"alert alert-error\"><p><b>Oh!</b> " . $f . " not found!</p></div>";
    }
}

?>
