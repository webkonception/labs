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
    function externalAssets($urls, $type = '', $compress = TRUE)
    {
        header('Keep-Alive: 300',1);
        header('Connection: keep-alive',1);
        header('Last-Modified: '.gmdate('D, d M Y H:i:s', filemtime(__FILE__)).' GMT', true, 200);
        header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + (60 * 60 * 24 * 30)));

        if (isset($type)) {
            $header_type = explode('-', $type);
            $header_type = end($header_type);
            if('css' === $header_type) {
                header('Content-Type:text/css');
            } else if ('js' === $header_type) {
                header('Content-Type:text/javascript');
            }

        }

        $content = '';
        foreach ($urls as $url) {
            //$content .= "\n" . '// ' . basename($url) . '' . "\n";

            if (preg_match("~^(?:f|ht)tps?://~i", $url)) {
                $output = fileGetContentsCurl($url, TRUE, 1000, 'assets_' . (isset($type) ? $type : '_external'));
            } else {
                $output = file_get_contents(__DIR__ .'/' . str_replace('assets/', '', $url), TRUE);
            }

            if (TRUE === $compress && !preg_match('/\.min/i', $url)) {
                $pattern = '/(?:(?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:(?<!\:|\\\|\')\/\/.*))/';
                $pattern = '/(?:(?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:(?<!\:|\\\|\'|\")\/\/.*))/';

                $patternJS = '/((?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:\/\/.*))/';
                $patternCSS = '!/\*[^*]*\*+([^/][^*]*\*+)*/!';

                $output = preg_replace($pattern, '', $output);
                $output = preg_replace('|\s\s+|', ' ', $output);
                $output = preg_replace("/\r|\n/", '', $output);
                $output = preg_replace("/\t/", '', $output);
            }
            if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
                $replace = '/' . dirname($url) . '/../';
                $output = str_replace(['(../', '(\'../', '("../'], ['(' . $replace, '(\'' . $replace, '("' . $replace], $output);
            }
            $content .= $output;
            $content .= "\n";

        }
        //$content = preg_replace('#/\*.*?\*/#s', '', $content);
        //$content = preg_replace("/\r|\n/", '', $content);
        return $content;
    }
    if (isset($_GET['urls'])) {
        $urls = $_GET['urls'];
        /*if (isset($_GET['country_code'])) {
            $urls = str_replace('%country_code%', $_GET['country_code'], $urls);
        }
        if (isset($_GET['theme_color'])) {
            $urls = str_replace('%theme_color%', $_GET['theme_color'], $urls);
        }*/
        $urls = explode('|', $urls);
        $type = isset($_GET['type']) ? $_GET['type'] : '';

        $compress = isset($_GET['compress']) ? $_GET['compress'] : FALSE;
        echo externalAssets($urls, $type, $compress);
    } else {
        echo '';
    }

}