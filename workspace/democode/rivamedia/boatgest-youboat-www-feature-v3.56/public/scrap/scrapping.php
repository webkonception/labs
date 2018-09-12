<?php
    require_once('include/library.php');

    header('Content-Type:text/html;charset=utf-8');

    $content        = '/';
    $target_url     = isset($_POST['target_url']) ? $_POST['target_url'] : (isset($_GET['target_url']) ? $_GET['target_url'] : '');
    $gateaway       = isset($_POST['gateaway']) ? $_POST['gateaway'] : (isset($_GET['gateaway']) ? $_GET['gateaway'] : 'www.boatshop24.co.uk');
    $showContent    = isset($_POST['show']) ? $_POST['show'] : (isset($_GET['show']) ? $_GET['show'] : false);
    $timeout        = 20;

    if(!empty($target_url) && !empty($gateaway)) {
        $content = fileGetContentsCurl($target_url, $showContent, $timeout, $gateaway);
    }
    if($showContent == 'true') {
        echo($content);
    } else {
        return $content;
    }