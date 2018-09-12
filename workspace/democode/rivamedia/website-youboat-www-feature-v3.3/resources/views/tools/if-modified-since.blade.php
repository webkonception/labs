<?php
    $fp = fopen($_SERVER["SCRIPT_FILENAME"], "r");
    $etag = md5(serialize(fstat($fp)));
    fclose($fp);
    header("Cache-Control: must-revalidate");
    header("Last-Modified: ".gmdate("D, d M Y H:i:s", $SelectS['timestamp'])." GMT");
    header('Etag: '.$etag);
    header("Expires: -1");

    if ((@strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $SelectS['timestamp']) && (trim($_SERVER['HTTP_IF_NONE_MATCH']) == $etag)) {
        header("HTTP/1.1 304 Not Modified");
        exit;
    }