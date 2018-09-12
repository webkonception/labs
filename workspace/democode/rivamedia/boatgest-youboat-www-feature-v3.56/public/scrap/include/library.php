<?php
function recursive_array_search($needle,$haystack) {
    foreach($haystack as $key=>$value) {
        $current_key=$key;
        if($needle===$value OR (is_array($value) && recursive_array_search($needle,$value) !== false)) {
            return $current_key;
        }
    }
    return false;
}
function convert_to_slug($str, $separator = '-') {
    $str = strtolower(trim($str));
    $str = preg_replace('/[^a-z0-9-]/', $separator, $str);
    $str = preg_replace('/-+/', $separator, $str);
    return rtrim($str, $separator);
}

/*
 * php delete function that deals with directories recursively
 */
function delete_files($target) {
    if(is_dir($target)){
        $files = glob( $target . '*', GLOB_MARK ); //GLOB_MARK adds a slash to directories returned
        foreach( $files as $file ) {
            delete_files( $file );
        }

        rmdir( $target );
    } elseif(is_file($target)) {
        unlink( $target );
    }
}
function rrmdir($target) {
    if (file_exists($target)) {
        $dir = opendir($target);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                $full = $target . '/' . $file;
                if (is_dir($full)) {
                    rrmdir($full);
                } else {
                    unlink($full);
                }
            }
        }
        closedir($dir);
        rmdir($target);
    }
}

function import(PDO $connection, $table, array $records)
{
    $fields = array_keys($records[0]);
    $placeHolders = substr(str_repeat(',?', count($fields)), 1);
    $values = [];
    foreach ($records as $record) {
        array_push($values, array_values($record));
    }
    unset($records);

    $query = 'INSERT INTO ' . $table . ' (';
    $query .= implode(',', $fields);
    $query .= ') VALUES (';
    $query .= implode('),(', array_fill(0, count($records), $placeHolders));
    $query .= ')';

    $statement = $connection->prepare($query);
    $statement->execute($values);
}

function displayErrors($errors, $title) {
    $htmlErrors = '';
    if (!empty($errors)) {
        $htmlErrors .= '<div class="col-sm-12 alert alert-danger">' . "\n";
        $htmlErrors .= '<h4>' . $title .'</h4><br>' . "\n";
        $htmlErrors .= '<ul class="text-danger">' . "\n";
        if (is_array($errors)) {
            foreach ($errors as $key => $error) {
                $htmlErrors .= '<li>';
                echo $key . ' : ' . "\n";
                if (is_array($error)) {
                    $htmlErrors .= '<ul>' . "\n";
                    foreach ($error as $k => $err) {
                        $htmlErrors .= '<li>' . $err . '</li>' . "\n";
                    }
                    unset($error);
                    $htmlErrors .= '</ul>' . "\n";
                } else {
                    $htmlErrors .= $error;
                }
                $htmlErrors .= '</li>' . "\n";
            }
            unset($errors);
        } else {
            $htmlErrors .= $errors;
        }
        $htmlErrors .= '</ul>' . "\n";
        $htmlErrors .= '</div>' . "\n";
    }
    return $htmlErrors;
}

function fileGetContentsCurl($targetUrl, $showContent = true, $timeout, $gateaway) {

    $ch = curl_init(); // open a cURL instance
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_URL, $targetUrl); /// set the URL to download
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE); /// Follow any redirects
    //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); // tell cURL to return the data
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    $output = curl_exec($ch); // pulls the webpage from the internet
    $CurlErr = curl_error($ch);
    $CurlErrNo = curl_errno($ch);
    $CurlErrMessage = curl_strerror($CurlErrNo);
    curl_close($ch); // closes the connection

    if ($CurlErrNo != 0) {
        mail("emmanuel.deiller.rivamedia@gmail.com", "[" . $gateaway . "] Unable to access the site. " . '[Error : ' . $CurlErrNo . ' / ' . $CurlErrMessage . ']', "[" . $gateaway . "]" . "\n\n" . '[Error : ' . $CurlErrNo . ' / ' . $CurlErrMessage . ']' . "\n" . "Unable to access the site." . "\n" . $targetUrl . "\n-->" . $effectivetURL . "\n" . "=> " . $CurlErr . "\n\n");
        return 'Unable to access the site';
    } else if (empty($output)) {
        mail("emmanuel.deiller.rivamedia@gmail.com", "[" . $gateaway . "] No matches were found. ", "[" . $gateaway . "]" . "\n\n" . "No matches were found." . "\n" . $targetUrl . "\n-->" . $effectivetURL . "\n" . "=> " . $CurlErr . "\n\n");
        return 'NO RESULTS';
    } elseif ($showContent){
        return $output;
    }
}

function parseArray($beg_tag, $close_tag, $datas, $returnFullArray) {
    if(preg_match("@$beg_tag(.*)$close_tag@Ui", $datas, $matching_data))
    {
        return $returnFullArray ? $matching_data : $matching_data[0];
    }
}

function progressCallback($resource, $download_size = 0, $downloaded = 0, $upload_size = 0, $uploaded = 0)
{
    $effectivetURL = curl_getinfo($resource, CURLINFO_EFFECTIVE_URL);
}

// this function will return page content using caches (we will load original sources not more than once per hour)
function getWebsiteContent($targetUrl, $suffix = '', $gateaway = "gateaway_YB", $subdir = '', $timeout = 20, $grab = false, $pattern = '', $force = false)
{
    // our folder with cache files
    $date = date('YmdH');
    $date = date('Ymd');
    $sCacheFolder = '/scrap/cache/' . $date . '/' . $gateaway . $subdir;

    // cache filename
    $ext = 'html';
    $sFilename = $suffix . '.' . $ext;

    $srcUrl = preg_replace("@^(https|http)?://[^/]+/@", "", $targetUrl);
    $pathinfo = pathinfo($srcUrl);
    $basename = array_key_exists('basename', $pathinfo) ? $pathinfo['basename'] : '';
    $filename = array_key_exists('filename', $pathinfo) ? $pathinfo['filename'] : '';
    $extension = array_key_exists('extension', $pathinfo) ? $pathinfo['extension'] : '';
    if('xml' == $extension) {
        $ext = $extension;
        $sFilename = $basename;
    }

    $pathname = $_SERVER['DOCUMENT_ROOT'] . $sCacheFolder;
    if (!is_dir($pathname) && strlen($pathname) > 0) {
        mkdir($pathname . '/', 0777, TRUE);
    }
    $filename_dest = $pathname . '/' . $sFilename;
    if($force && file_exists($filename_dest) ) {
        @unlink($filename_dest);
    }
    if (!file_exists($filename_dest)) {
        $ch = curl_init($targetUrl); // open a cURL instance
        $fp = fopen($filename_dest, 'w');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

        curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, false );
        curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, 2 );

        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 3);

        curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
        /* Spoof the User-Agent header value; just to be safe */
        //curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.15) Gecko/20080623 Firefox/2.0.0.15');

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  FALSE);

        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        /* This ensures 404 Not Found (and similar) will be treated as errors */
        curl_setopt($ch, CURLOPT_FAILONERROR, TRUE);

        /* This might/should help against accidentally downloading mp3 files and such, but it doesn't really work :/  */
        $header[] = "Accept: text/html, text/*";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        //curl_setopt($ch, CURLOPT_HTTPHEADER, Array('User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.15) Gecko/20080623 Firefox/2.0.0.15'));
        //curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); // DONT USE /!\


        // This is required to curl give us some progress
        // if this is not set to false the progress function never
        // gets called
        curl_setopt($ch, CURLOPT_NOPROGRESS, false);

        // Set up the callback
        curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, 'progressCallback');

        $output = curl_exec($ch); // pulls the webpage from the internet
        $CurlErr = curl_error($ch);
        $CurlErrNo = curl_errno($ch);
        $CurlErrMessage = curl_strerror($CurlErrNo);
        $effectivetURL = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        //$httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch); // closes the connection
        fclose($fp);

        if ($CurlErrNo != 0) {
            //mail("emmanuel.deiller.rivamedia@gmail.com", "[" . $gateaway . "] Unable to access the site. " . '[Error : ' . $CurlErrNo . ' / ' . $CurlErrMessage . ']', "[" . $gateaway . "]" . "\n\n" . '[Error : ' . $CurlErrNo . ' / ' . $CurlErrMessage . ']' . "\n" . "Unable to access the site." . "\n" . $targetUrl . "\n-->" . $effectivetURL . "\n" . "=> " . $CurlErr . "\n\n");
            $error['unable_to_access'][] = $effectivetURL;
            //return 'Unable to access the site';
            if (file_exists($filename_dest)) {
                //unlink($filename_dest);
            }
            return $error;
        } else if (!$output) {
            //mail("emmanuel.deiller.rivamedia@gmail.com", "[" . $gateaway . "] No matches were found. ", "[" . $gateaway . "]" . "\n\n" . "No matches were found." . "\n" . $targetUrl . "\n-->" . $effectivetURL . "\n" . "=> " . $CurlErr . "\n\n");
            $error['no_results'][] = $effectivetURL;
            //return 'NO RESULTS';
            if (file_exists($filename_dest)) {
                unlink($filename_dest);
            }
            return $error;
        }

    }
    $return = $grab ? '' : file_get_contents($filename_dest);
    /*if(!empty($return)) {
        $html = preg_replace('/(<head[^>]*>.+?<\/head>|<script[^>]*>.+?<\/script>|<style[^>]*>.+?<\/style>|<form[^>]*>.+?<\/form>|<iframe.+">)/is', '', $return); // remove scripts/style/form/iframe
        if(is_array($html) && !empty($html[0])) {
            $return = $html[0];
        } else {
            $return = $html;
        }
    }*/
    //$pattern = !empty($pattern) ? $pattern : "/<body[^>]*>(.*?)<\/body>/isU";
    $pattern = !empty($pattern) ? $pattern : '';

    if (!empty($return) && !empty($pattern)) {
        //var_dump($pattern);die();
        preg_match_all($pattern, $return, $list);
        $return = end($list);
        //$return = preg_replace("/<script.*?\/script>/is", "", $return); // remove scripts
        $return = preg_replace('/(<head[^>]*>.+?<\/head>|<script[^>]*>.+?<\/script>|<style[^>]*>.+?<\/style>|<form[^>]*>.+?<\/form>|<iframe.+">)/is', '', $return); // remove scripts/style/form/iframe
        if(is_array($return) && !empty($return[0])) {
            return $return[0];
            //$return = $html[0];
        } else {
            //$return = $html;
        }
        //var_dump($return);

        /*echo '<pre>';
        var_dump('$return');
        var_dump(end($return));
        echo '</pre>';
        die();*/
    }
    return $return;
}

function getXPath($sUrlSrc) {
    // Load the source
    //$dom = new DOMDocument();
    $dom = new DOMDocument('1.0', 'utf-8');
    //@$dom->loadHTML($sUrlSrc);
    libxml_use_internal_errors(true);

    //if (!$dom->loadHTML($sUrlSrc))
    if (!$dom->loadHTML(mb_convert_encoding($sUrlSrc, 'HTML-ENTITIES', 'UTF-8')))
    {
        $errors="";
        foreach (libxml_get_errors() as $error)  {
            $errors.=$error->message."<br/>";
        }
        libxml_clear_errors();
        return "libxml errors:<br>$errors";
    }

    $xpath = new DomXPath($dom);

    return $xpath;
}
function scrappingInfo($contentInfo = 'lorem', $gateaway = 'gateaway_YB') {

    $return = false;

    // our folder with cache files
    //$date = date('YmdH');
    $date = date('Ymd');
    $contentInfo = "Date : ". date('Y/m/d H:i:s') . "|" . $contentInfo . "\n";
    $sCacheFolder = '/scrap/cache/' . $date . '/' . $gateaway;

    // cache filename
    $sFilename = '/scrappingInfo.txt';
    $pathname = $_SERVER['DOCUMENT_ROOT'] . $sCacheFolder ;
    if (!is_dir($pathname) && strlen($pathname)>0) {
        mkdir($pathname .'/', 0777, TRUE);
    }
    $filename_dest = $pathname . '/' . $sFilename;

    if (file_exists($filename_dest)) {
        unlink($filename_dest);
    }

    if (! file_exists($filename_dest)) {
        $fp = fopen($filename_dest, 'w+');
        fclose($fp);
    }

    // Assurons nous que le fichier est accessible en écriture
    if (is_writable($filename_dest)) {

        $file_link = '<a href="' . $sCacheFolder . $sFilename . '" target="_blank"><strong>' . $sCacheFolder . $sFilename . '</strong></a>';

        // Dans notre exemple, nous ouvrons le fichier $filename_dest en mode d'ajout
        // Le pointeur de fichier est placé à la fin du fichier
        // c'est là que $contentInfo sera placé
        if (!$handle = fopen($filename_dest, 'a')) {
            $return = "Unable to open file : " . $sCacheFolder . $sFilename;
            return $return;
            //exit;
        }

        // Ecrivons quelque chose dans notre fichier.
        if (fwrite($handle, $contentInfo) === FALSE) {
            $return = "Unable to write to the file : " . $sCacheFolder . $sFilename;
            return $return;
            //exit;
        }

        $return[] = "Writing to the file $file_link has <strong class='text-success'>succeeded</strong> !" . "\r\n";
        $return[] = $sCacheFolder . $sFilename;
        //$return .= $contentInfo;

        fclose($handle);

    } else {
        $return = "The file <strong>$filename_dest</strong> is not writable.";
    }
    return $return;
}


function makeCSV($headingsArray = [], $datasArray = [], $gateaway = 'gateaway_YB') {

    //$return = false;

    $headings   = !empty($headingsArray) ? $headingsArray : [];
    $datas      = !empty($datasArray) ? $datasArray : [];
    $datas      = $headings + $datas;

    // our folder with cache files
    $date = date('Ymd');
    $sCacheFolder = '/scrap/cache/' . $date . '/' . $gateaway;

    // cache filename
    $date = date('YmdH');
    $sFilename = '/' . $date . '_' . $gateaway .'_scrapping.csv';
    $pathname = $_SERVER['DOCUMENT_ROOT'] . $sCacheFolder ;
    if (!is_dir($pathname) && strlen($pathname)>0) {
        mkdir($pathname .'/', 0777, TRUE);
    }
    $filename_dest = $pathname . '/' . $sFilename;

    if (!empty($headingsArray) && empty($datasArray) && file_exists($filename_dest)) {
        unlink($filename_dest);
    }
    if (! file_exists($filename_dest)) {
        $fp = fopen($filename_dest, 'w+');
        fclose($fp);
    }

    // Assurons nous que le fichier est accessible en écriture
    if (is_writable($filename_dest)) {

        $file_link = '<a href="' . $sCacheFolder . $sFilename . '" target="_blank"><strong>' . $sCacheFolder . $sFilename . '</strong></a>';

        // Dans notre exemple, nous ouvrons le fichier $filename_dest en mode d'ajout
        // Le pointeur de fichier est placé à la fin du fichier
        // c'est là que $datas sera placé
        if (!$handle = fopen($filename_dest, 'a+')) {
            $return = "Unable to open file : " . $sCacheFolder . $sFilename;
            return $return;
            exit;
        }

        // Ecrivons quelque chose dans notre fichier.
        //if (fwrite($handle, $datas) === FALSE) {
        if (fputcsv($handle, $datas) === FALSE) {
            $return = "Unable to write to the file : " . $sCacheFolder . $sFilename;
            return $return;
            exit;
        }

        $return = "Writing to the file $file_link has <strong class='text-success'>succeeded</strong> !" . "\r\n";

        fclose($handle);

    } else {
        $return = "The file <strong>$filename_dest</strong> is not writable.";
    }
    //return $return;
}

// We will use this function to generate a CSV
function exportCSV($headings=false, $rows=false, $filename=false)
{
    // Ensure that we have data to be able to export the CSV
    if ((!empty($headings)) AND (!empty($rows)))
    {
        // modify the name somewhat
        $name = ($filename !== false) ? $filename : "export.csv";

        // Set the headers we need for this to work
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $name);

        header('Content-Disposition: attachment; filename="' . $name .'"');
        header('Content-Description: File Transfer');
        header('Content-Type: text/csv');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');

        // Start the ouput
        //$output = fopen('php://output', 'w');
        ////$output = @fopen('php://temp/maxmemory:'. (5*1024*1024), 'w+');
        if (!$output = @fopen('php://temp/maxmemory:'. (5*1024*1024), 'w+')) {
            $return = "Unable to open file : " . $filename;
            exit;
        }
        // Create the headers
        if(fputcsv($output, $headings) === FALSE) {
            $return = "Unable to write to the file : " . $filename;
            exit;
        }

        // Then loop through the rows
        foreach($rows as $row)
        {
            // Add the rows to the body
            if(fputcsv($output, $row) === FALSE) {
                $return = "Unable to write to the file : " . $filename;
                exit;
            }
        }
        unset($rows);
        $return = "Writing to the file $file_link has <strong class='text-success'>succeeded</strong> !" . "\r\n";

        // Exit to close the stream off
        exit();
    }

    // Default to a failure
    return false;
}

////
class wSpider
{
    var $ch; /// going to used to hold our cURL instance
    var $html; /// used to hold resultant html data
    var $binary; /// used for binary transfers
    var $url; /// used to hold the url to be downloaded

    function wSpider()
    {
        $this->html = "";
        $this->binary = 0;
        $this->url = "";
    }
    function fetchPage($url)
    {
        $this->url = $url;
        if (isset($this->url)) {
            $this->ch = curl_init (); /// open a cURL instance
            curl_setopt ($this->ch, CURLOPT_RETURNTRANSFER, TRUE); // tell cURL to return the data
            curl_setopt ($this->ch, CURLOPT_URL, $this->url); /// set the URL to download
            curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, TRUE); /// Follow any redirects
            curl_setopt($this->ch, CURLOPT_BINARYTRANSFER, $this->binary); /// tells cURL if the data is binary data or not
            $this->html = curl_exec($this->ch); // pulls the webpage from the internet
            curl_close ($this->ch); /// closes the connection
        }
    }
    function parse_array($beg_tag, $close_tag)
    {
        preg_match_all("($beg_tag.*$close_tag)siU", $this->html, $matching_data);
        return $matching_data[0];
    }
}

function Translate($text, $source = 'fr', $target = 'en', $apiKey = '', $showContent = true, $timeout = 10)
{
    $text = urlencode($text);

    //$url = 'https://translate.googleapis.com/translate_a/t?client=p&key=' . $apiKey . '&sl=' . $source . '&tl=' . $target . '&dt=t&q=' . $text . '&multires=1';
    $url = 'https://translate.googleapis.com/translate_a/single?client=gtx&sl=' . $source . '&tl=' . $target . '&dt=t&q=' . $text;
    //echo $url . '<br>';
    $ch = curl_init(); // open a cURL instance

    curl_setopt($ch, CURLOPT_HEADER, 0);
    //$headers['Accept'] = 'application/json';
    //$headers['Content-Type'] = 'application/json';
    //curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    curl_setopt($ch, CURLOPT_URL, $url); /// set the URL to download
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE); /// Follow any redirects
    //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); // tell cURL to return the data

    curl_setopt($ch, CURLOPT_ENCODING, 'iso-8859-1');
    /* Spoof the User-Agent header value; just to be safe */
    //curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.15) Gecko/20080623 Firefox/2.0.0.15');

    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    $output = curl_exec($ch); // pulls the webpage from the internet

    $CurlErr = curl_error($ch);
    $CurlErrNo = curl_errno($ch);
    $CurlErrMessage = curl_strerror($CurlErrNo);
    curl_close($ch); // closes the connection
    echo '<pre>';
    //echo 'Source: ' . $text . '<br>';
    $output = str_replace(',,,3', '',$output);
    //$output = str_replace('[[[', '{',$output);
    $output = str_replace('[[[', '[',$output);
    //$output = str_replace(']],,"' . $source . '"]', '}]',$output);
    $output = str_replace(']],,"' . $source . '"]', ']',$output);
    //$output = str_replace(']]]', '}]]',$output);
    //$output = str_replace('],[', '},{',$output);
    $output = str_replace('","', '" => "',$output);
    //$output = str_replace(']],,', '}],,',$output);
    var_dump($output);
    echo  '<br>-';
    $z = [$output];
    var_dump($z);
    var_dump(implode("<br>", $z));
    //$return = explode('[[',$output);
    //$return = explode('"',$output);
    //$return = str_replace('[[[', '',$return);
    //$return = str_replace(']],,', '',$return);
    //$return = str_replace('],[', '',$return);
    //var_dump($return[1]);
    die();

    if ($CurlErrNo != 0) {
        mail("emmanuel.deiller.rivamedia@gmail.com", "[" . $gateaway . "] Unable to access the site. " . '[Error : ' . $CurlErrNo . ' / ' . $CurlErrMessage . ']', "[" . $gateaway . "]" . "\n\n" . '[Error : ' . $CurlErrNo . ' / ' . $CurlErrMessage . ']' . "\n" . "Unable to access the site." . "\n" . $targetUrl . "\n-->" . $effectivetURL . "\n" . "=> " . $CurlErr . "\n\n");
        return 'Unable to access the site';
    } else if (empty($output)) {
        mail("emmanuel.deiller.rivamedia@gmail.com", "[" . $gateaway . "] No matches were found. ", "[" . $gateaway . "]" . "\n\n" . "No matches were found." . "\n" . $targetUrl . "\n-->" . $effectivetURL . "\n" . "=> " . $CurlErr . "\n\n");
        return 'NO RESULTS';
    } elseif ($showContent) {
        return $output;
    }

    /*

                        $handle = curl_init($url);
                        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
                        $response = curl_exec($handle);
    echo '<pre>';
                        var_dump('$response');
                        var_dump($response);
                        var_dump('json_encode($response)');
                        var_dump(json_encode($response));
                        var_dump('json_decode($response)');
                        var_dump(json_decode($response));
                        var_dump('explode');
                        var_dump(explode('"',$response));
    die();
                        $responseDecoded = json_decode($response, true);
                        curl_close($handle);

                        //echo 'Source: ' . $text . '<br>';
                        echo 'Translation: ' . $responseDecoded['data']['translations'][0]['translatedText'];

                        $return = explode('"',$output);

                        return  $return;*/
}