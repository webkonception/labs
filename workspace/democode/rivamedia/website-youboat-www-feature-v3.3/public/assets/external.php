<?php
    if (! function_exists('fileGetContentsCurl')) {
        function fileGetContentsCurl($targetUrl, $showContent, $timeout, $bridge) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_URL, $targetUrl);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            $output = curl_exec($ch);
            $CurlErr = curl_error($ch);
            curl_close($ch);

            if ($CurlErr) {
                mail("emmanuel.deiller.rivamedia@gmail.com","[" . $bridge ."] Unable to access the site", $targetUrl." => " . $CurlErr . "\n\n");
                return "false";
            } else if (empty($output)) {
                mail("emmanuel.deiller.rivamedia@gmail.com","[" . $bridge . "] No matches were found at this URL => ", $targetUrl . "\n\n");
                return "false";
            } elseif ($showContent) {
                return $output;
            }
        }
    }
    if (! function_exists('externalAssets')) {
        function externalAssets($urls)
        {
            header('Keep-Alive: 300',1);
            header('Connection: keep-alive',1);
            header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime(__FILE__)).' GMT', true, 200);
            header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + (60 * 60 * 24 * 30)));
            header('Content-Type:application/javascript');

            $content = '';
            foreach ($urls as $url) {
                //$content .= file_get_contents($url);
                $content .= "\n" . '/* ' . basename($url) . ' */' . "\n";
                $output= fileGetContentsCurl($url, TRUE, 2000, 'external_assets');

                $pattern = '/(?:(?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:(?<!\:|\\\|\')\/\/.*))/';
                $pattern = '/(?:(?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:(?<!\:|\\\|\'|\")\/\/.*))/';

                $patternJS = '/((?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:\/\/.*))/';
                $patternCSS = '!/\*[^*]*\*+([^/][^*]*\*+)*/!';

                $output = preg_replace($pattern, '', $output);
                $output = preg_replace('|\s\s+|', ' ', $output);
                $output = preg_replace("/\r|\n/", '', $output);
                $output = preg_replace("/\t/", '', $output);

                $content .= $output;
                $content .= "\n";
            }
            //$content = preg_replace('#/\*.*?\*/#s', '', $content);
            //$content = preg_replace("/\r|\n/", '', $content);
            return $content;
        }
        if (isset($_GET['urls'])) {
            $urls = explode('|', $_GET['urls']);
            echo externalAssets($urls);
        } else if (isset($_GET['type'])) {
            if ('css'===$_GET['type']) {

            } else if ('extcss'===$_GET['type']) {

            } else if ('js'===$_GET['type']) {

            } else if ('extjs'===$_GET['type']) {

            }
        } else {
            echo '';
        }

    }