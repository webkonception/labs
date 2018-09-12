<?php
/*
 * jQuery File Upload Plugin PHP Example
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

error_reporting(E_ALL | E_STRICT);
require('UploadHandler.php');

$custom_dir = $_REQUEST['custom_dir'];
//$_REQUEST['root'] = 'youboat-www_website';
$host = $_REQUEST['host'] ? $_REQUEST['host'] : 'http://' . $_SERVER['HTTP_HOST'];
//$root = $_REQUEST['root'] ? $_SERVER['DOCUMENT_ROOT'] . '/../../' . $_REQUEST['root'] . '/public/' : $_SERVER['DOCUMENT_ROOT'];
$root = $_REQUEST['root'] ? str_replace(['youboat-www_boatgest', 'boatgest-youboat'] , [$_REQUEST['root'], $_REQUEST['root']], $_SERVER['DOCUMENT_ROOT']) : $_SERVER['DOCUMENT_ROOT'];

$upload_dir = $root . $custom_dir;
$upload_url = $host . $custom_dir;
//$upload_url = $custom_dir;
$options = [
    'upload_dir'=>$upload_dir,
    //'script_url'=>'http://' . $host . '/server/php/',
    'upload_url'=>$upload_url,
    //'user_dirs'=>true
];
if(isset($_REQUEST['filename_prefix'])) {
    $options['filename_prefix'] = $_REQUEST['filename_prefix'];
}
if(isset($_REQUEST['filename'])) {
    $options['filename'] = $_REQUEST['filename'];
}

/*
class CustomUploadHandler extends UploadHandler {
    protected function get_user_id() {
        if(isset($_SESSION['current_ad_id'])) {
            return $_SESSION['current_ad_id'];
        } else {
            @session_start();
            return session_id();
        }
    }
}*/
/*
class CustomUploadHandler extends UploadHandler {
    protected function trim_file_name($file_path, $name, $size, $type, $error, $index, $content_range) {
        $name = 'photo_' . microtime(true);
        $name = str_replace('.', '', $name);
        return $name;
    }
}
*/
//$upload_handler = new CustomUploadHandler($options);

$upload_handler = new UploadHandler($options);