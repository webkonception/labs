<?php
    ini_set('max_execution_time', 360); // Maximum execution time of each script, in seconds (I CHANGED THIS VALUE)
    ini_set('max_input_time', 120); // Maximum amount of time each script may spend parsing request data
    //ini_set('max_input_nesting_level', 64); // Maximum input variable nesting level
    ini_set('memory_limit', '256M'); // Maximum amount of memory a script may consume (128MB by default)
    //ini_set('memory_limit', '-1');

    set_time_limit (0);
    require_once('include/library.php');
    $gateaway       = 'www.boatshop24.co.uk';
    //$selectedDate = isset($_GET['date']) ? $_GET['date'] : '';
    $step = isset($_GET['step']) ? $_GET['step'] : 1;
    $total_pages = isset($_GET['total_pages']) ? $_GET['total_pages'] : null;
    $total_results = isset($_GET['total_results']) ? $_GET['total_results'] : null;
    $scrappingInfoFile = isset($_GET['scrappingInfoFile']) ? urldecode($_GET['scrappingInfoFile']) : null;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>

    <title>GateAway Scrapping</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="assets/font-awesome/4.6.3/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="assets/jquery/1.12.4/jquery.min.js"></script>
    <script src="assets/bootstrap/3.3.6/js/bootstrap.min.js"></script>

    <script src="js/scripts.js"></script>
</head>

<body>
<div id="scrapping" class="container">
    <div class="row">
        <div class="col-sm-12">
            <h1>Gateaway : <strong><?php echo $gateaway; ?></strong></h1>
        </div>
    </div>

    <!--    // 01 : www.boatshop24.co.uk_results_pages-->
    <?php
    if(1 == $step) {
        ?>
        <div class="well">
            <div class="row">
                <div class="col-sm-12">
                    <h3>[01] <?php echo $gateaway; ?> Total Pages / Results</h3>
                </div>

                <div class="col-sm-12">
                    <div class="progress">
                        <div id="progress_bar_01" class="" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%"></div>
                    </div>
                </div>

                <script>
                    var $ProgressBar = $("#progress_bar_01");
                </script>

                <?php
                $timeout = 10;

                $page = 1;
                $scrapping_url = 'https://www.boatshop24.co.uk/boats-for-sale/uk?order=latest&list_type=list&page=' . $page;
                //grab distant url
                $sUrlSrc = getWebsiteContent($scrapping_url, 'page_' . $page, $gateaway, '/pages', $timeout, true, '', true);

                $errors = [];
                //grab local file
                $sUrlSrc = getWebsiteContent($scrapping_url, 'page_' . $page, $gateaway, '/pages', $timeout);
                if (!empty($sUrlSrc) && is_array($sUrlSrc)) {
                    $errors = $errors + $sUrlSrc;
                } else if (!empty($sUrlSrc)) {
                    $xpath = getXPath($sUrlSrc);

                    // total_pages
                    $script = '';
                    $html = '';
                    $html .= '<div class="col-sm-6">' . "\n";
                    $xPathQuery = '//*[@id="page"]//div[@class="gallery_navigation"][1]/form[@id="form_search"]/table//div[@class="nav"]/a[not(@class="nav_next")][last()]';
                    $scrapping = [];
                    if (!is_null($elements = $xpath->query($xPathQuery))) {
                        if (false !== $elements && ($elements instanceof Traversable || is_array($elements) || is_object($elements))) {
                            foreach ($elements as $element) {
                                if (!empty($element)) {
                                    $total_pages = $element->firstChild->nodeValue;
                                    $html .= 'Total pages : <strong>' . $total_pages . '</strong><br>';
                                    $scrapping['total_pages'] = $total_pages;
                                }
                            }
                            unset($elements);
                        }

                        $script = '<script>' . "\n";
                        $script .= 'progressBar($ProgressBar, 50);' . "\n";
                        $script .= '</script>' . "\n";
                    }
                    $html .= '</div>' . "\n";
                    echo $html;
                    echo $script;

                    //total results
                    $html = '';
                    $html .= '<div class="col-sm-6">' . "\n";
                    $script = '';
                    $xPathQuery = '//*[@id="page"]//td[@class="main_cell"]//span[@class="total"]';
                    if (!is_null($elements = $xpath->query($xPathQuery))) {
                        if (false !== $elements && ($elements instanceof Traversable || is_array($elements) || is_object($elements))) {
                            foreach ($elements as $element) {
                                if (!empty($element)) {
                                    $total_results = preg_replace('/ results/', '', $element->firstChild->nodeValue);
                                    $html .= 'Total results : <strong>' . $total_results . '</strong><br>';
                                    $scrapping['total_results'] = $total_results;
                                }
                            }
                            unset($elements);
                        }
                        $script = '<script>' . "\n";
                        $script .= 'progressBar($ProgressBar, 100);' . "\n";
                        $script .= '</script>' . "\n";
                    }
                    $html .= '</div>' . "\n";
                    echo $html;
                    echo $script;
                }

                echo displayErrors($errors, 'grab local file');
                ?>
            </div>
        </div>
        <?php
        unset($html, $script, $scrapping_url, $sUrlSrc, $xpath, $xPathQuery, $elements, $element, $errors, $key, $error, $k, $err);
        ?>
        <!--<script>window.location = '<?php echo $_SERVER['SCRIPT_NAME'] . '?step=2&total_pages=' . $total_pages . '&total_results=' . $total_results; ?>';</script>-->
        <a href="<?php echo $_SERVER['SCRIPT_NAME'] . '?step=2&total_pages=' . $total_pages . '&total_results=' . $total_results; ?>" class="btn btn-success">Go to Step 02 : Listing Pages Download</a>
    <?php
    }
    ?>
    <!--    // 02 : www.boatshop24.co.uk_results_links_download-->
    <?php
    if(2 == $step) {
        $scrapping['total_pages'] = !empty($scrapping['total_pages']) ? $scrapping['total_pages'] : $total_pages;
        $scrapping['total_results'] = !empty($scrapping['total_results']) ? $scrapping['total_results'] : $total_results;
        ?>
        <div class="well">
            <div class="row">
                <?php
                if (!empty($scrapping['total_pages']) && !empty($scrapping['total_results'])) {
                    ?>
                    <div class="col-sm-12">
                        <h3>[02] <?php echo $gateaway; ?> Listing Pages Download</h3>
                    </div>

                    <div class="col-sm-12">
                        <div class="progress">
                            <h4 class="lead title">[02-01] Download Listing Pages</h4>
                            <div id="progress_bar_02_download_listing_pages" class="progress-bar active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%"></div>
                        </div>
                    </div>

                    <script>
                        var $ProgressBar_download_listing_pages = $("#progress_bar_02_download_listing_pages");
                        var $ProgressBar_download_listing_pages_parent = $ProgressBar_download_listing_pages.parent(".progress");
                        var $ProgressBarTitle_download_listing_pages = $ProgressBar_download_listing_pages_parent.find(".title");
                    </script>
                    <?php
                    $total_pages = $scrapping['total_pages'];
                    $timeout = 20;

                    $page = 1;
                    $counter_download_listing_pages = 1;
                    $errors = [];
                    $pageListingEmpty = [];
                    // grab distant urls
                    for ($page; $page <= $total_pages; $page++) {
                        echo "<!-- $page -->";
                        $scrapping_url = 'https://www.boatshop24.co.uk/boats-for-sale/uk?order=latest&list_type=list&page=' . $page;
                        $sUrlSrc = getWebsiteContent($scrapping_url, 'page_' . $page, $gateaway, '/pages', $timeout, true);
                        if (!empty($sUrlSrc) && is_array($sUrlSrc)) {
                            $errors = $errors + $sUrlSrc;
                            $pageListingEmpty[] = 'page_' . $page;
                        } else {
                            $script = '<script>' . "\n";
                            //$script .= '$ProgressBar_download_listing_pages_parent.show();' . "\n";
                            $script .= '$ProgressBarTitle_download_listing_pages.find(".infos").remove();' . "\n";
                            $script .= '$ProgressBarTitle_download_listing_pages.append("<span class=\'infos\'> (' . $counter_download_listing_pages . ' / ' . $total_pages . ')</span>");' . "\n";
                            $script .= 'progressBar($ProgressBar_download_listing_pages, ' . ceil(($counter_download_listing_pages * 100) / $total_pages) . ', false);' . "\n";
                            $script .= '</script>' . "\n";
                            echo $script;
                            $counter_download_listing_pages++;
                        }
                    }
                    if(!empty($pageListingEmpty)) {
                        $subdir = '/pages';
                        $date = date('Ymd');
                        $sCacheFolder = '/scrap/cache/' . $date . '/' . $gateaway . $subdir;
                        $pathname = $_SERVER['DOCUMENT_ROOT'] . $sCacheFolder;
                        foreach($pageListingEmpty as $file) {
                            $sFilename = $file . '.html';
                            $filename_dest = $pathname . '/' . $sFilename;
                            @unlink($filename_dest);
                            $errors['deleted_file'][] = $filename_dest;
                        }
                    }
                    echo displayErrors($errors, 'Download Listing Pages');
                }
                ?>
            </div>
        </div>
    <?php
    unset($html, $script, $scrapping_url, $sUrlSrc, $counter_download_listing_pages, $errors, $key, $error, $k, $err,
        $pageListingEmpty);
        //$subdir, $date, $sCacheFolder, $pathname, $pageListingEmpty, $sFilename, $file, $filename_dest);
    ?>
        <!--<script>window.location = '<?php echo $_SERVER['SCRIPT_NAME'] . '?step=3&total_pages=' . $total_pages . '&total_results=' . $total_results; ?>';</script>-->
        <a href="<?php echo $_SERVER['SCRIPT_NAME'] . '?step=3&total_pages=' . $total_pages . '&total_results=' . $total_results; ?>" class="btn btn-success">Go to Step 03 : Listing Pages Processing</a>
    <?php
    }
    ?>
    <!--    // 03 : www.boatshop24.co.uk_results_links_processing-->
    <?php
    if(3 == $step) {
        $scrapping['total_pages'] = !empty($scrapping['total_pages']) ? $scrapping['total_pages'] : $total_pages;
        $scrapping['total_results'] = !empty($scrapping['total_results']) ? $scrapping['total_results'] : $total_results;
        ?>
        <div class="well">
            <div class="row">
                <?php
                if(!empty($scrapping['total_pages']) && !empty($scrapping['total_results'])) {
                ?>
                    <div class="col-sm-12">
                        <h3>[03] <?php echo $gateaway; ?> Listing Pages Processing</h3>
                    </div>

                    <div class="col-sm-6">
                        <div class="progress">
                            <h4 class="lead title">[03-01] Loading (local) Listing Pages</h4>
                            <div id="progress_bar_03_loading_listing_pages" class="" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%"></div>
                        </div>
                    </div>

                    <script>
                        var $ProgressBar_loading_listing_pages = $("#progress_bar_03_loading_listing_pages");
                        var $ProgressBar_loading_listing_pages_parent = $ProgressBar_loading_listing_pages.parent(".progress");
                        var $ProgressBarTitle_loading_listing_pages = $ProgressBar_loading_listing_pages_parent.find(".title");
                    </script>

                    <div class="col-sm-6">
                        <div class="progress">
                            <h4 class="lead title">[03-02] Processing Ad's Links <em>(from listing)</em></h4>
                            <div id="progress_bar_03_extracting_links" class="" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%"></div>
                        </div>
                    </div>

                    <script>
                        var $ProgressBar_extracting_links = $("#progress_bar_03_extracting_links");
                        var $ProgressBar_extracting_links_parent = $ProgressBar_extracting_links.parent(".progress");
                        var $ProgressBarTitle_extracting_links = $ProgressBar_extracting_links_parent.find(".title");
                    </script>

                    <div class="col-sm-12">
                        <div class="progress">
                            <h4 class="lead title">[03-03] Merging Total Ad's Links</h4>
                            <div id="progress_bar_03_array_links" class="" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%"></div>
                        </div>
                    </div>

                    <script>
                        var $ProgressBar_array_links = $("#progress_bar_03_array_links");
                        var $ProgressBar_array_links_parent = $ProgressBar_array_links.parent(".progress");
                        var $ProgressBarTitle_array_links = $ProgressBar_array_links_parent.find(".title");
                    </script>

                    <?php
                    $total_pages    = $scrapping['total_pages'];
                    $timeout        = 30;

                    $page           = 1;
                    $j              = 0 ;
                    $counter_loading_listing_pages  = 1;
                    $counter_extracting_links  = 0;
                    //$counter_extracting_txts   = 1;
                    $errors = [];
                    // grab local files

                    for($page; $page <= $total_pages; $page++) {
                        echo "<!-- $page -->";
                        $scrapping_url  = 'https://www.boatshop24.co.uk/boats-for-sale/uk?order=latest&list_type=list&page=' . $page;
                        $sUrlSrc = getWebsiteContent($scrapping_url, 'page_' . $page, $gateaway, '/pages', $timeout, false, '', true);
                        if(!empty($sUrlSrc) && is_array($sUrlSrc)) {
                            $errors = $errors + $sUrlSrc;
                        } else if(!empty($sUrlSrc)) {
                            $script = '<script>' . "\n";
                            //$script .= '$ProgressBar_loading_listing_pages_parent.show();' . "\n";
                            $script .= '$ProgressBarTitle_loading_listing_pages.find(".infos").remove();' . "\n";
                            $script .= '$ProgressBarTitle_loading_listing_pages.append("<span class=\'infos\'> (' . $counter_loading_listing_pages . ' / ' . $total_pages . ')</span>");' . "\n";
                            $script .= 'progressBar($ProgressBar_loading_listing_pages, ' . ceil(($counter_loading_listing_pages*100) / $total_pages) . ');' . "\n";
                            $script .= '</script>' . "\n";
                            echo $script;
                            $counter_loading_listing_pages ++;

                            $xpath = getXPath($sUrlSrc);

                            $xPathQuery = '//*[@id="page"]//table//td[@class="desc-top"]/div/a/@href';
                            if (!is_null($elements = $xpath->query($xPathQuery))) {
                                //if(is_object($element) && get_class($element) == 'DOMElement') {
                                $j = 0 ;
                                if (false !== $elements && ($elements instanceof Traversable || is_array($elements) || is_object($elements))) {
                                    foreach ($elements as $element) {
                                        if (!empty($element)) {
                                            $url = $element->nodeValue;
                                            $url = preg_match("/^(http|https):\/\//i", $url) ? $url : 'http://' . $gateaway . $element->nodeValue;
                                            $scrapping['links'][$page-1][$j]['url'] =  $url;
                                            $j++;
                                            $script = '<script>' . "\n";
                                            //$script .= '$ProgressBar_extracting_links_parent.show();' . "\n";
                                            $script .= '$ProgressBarTitle_extracting_links.find(".infos").remove();' . "\n";
                                            $script .= '$ProgressBarTitle_extracting_links.append("<span class=\'infos\'> (' . $counter_extracting_links . ' / ' . $total_results . ')</span>");' . "\n";
                                            $script .= 'progressBar($ProgressBar_extracting_links, ' . ceil(($counter_extracting_links*100) / $total_results) . ');' . "\n";
                                            $script .= '</script>' . "\n";
                                            echo $script;
                                            $counter_extracting_links ++;
                                        }
                                    }
                                    unset($elements);
                                }
                            }
                        }
                    }
                    if(!empty($scrapping['links'])) {
                        $contentInfo = '';
                        $scrapping_links = [];
                        if(is_array($scrapping['links'])) {
                            $contentInfo .= 'Total pages : ' . $total_pages . "|";
                            $contentInfo .= 'Total results : ' . $total_results . "\n";
                            $links = new RecursiveIteratorIterator(new RecursiveArrayIterator($scrapping['links']));
                            $counter_array_links = 0;
                            foreach ($links as $key => $val) {
                                if('url' == $key) {
                                    ////echo $val . "<br>";
                                    $contentInfo .= $val . "\n";
                                    $scrapping_links[] = $val;
                                    //var_dump(round(($counter*100)/$total_results));
                                    $script = '<script>' . "\n";
                                    //$script .= '$ProgressBar_array_links_parent.show();' . "\n";
                                    $script .= '$ProgressBarTitle_array_links.find(".infos").remove();' . "\n";
                                    $script .= '$ProgressBarTitle_array_links.append("<span class=\'infos\'> (' . $counter_array_links . ' / ' . $total_results . ')</span>");' . "\n";
                                    $script .= 'progressBar($ProgressBar_array_links, ' . ceil(($counter_array_links*100)/$total_results) . ');' . "\n";
                                    $script .= '</script>' . "\n";
                                    $counter_array_links ++;
                                    echo $script;
                                }
                                if('txt' == $key) {
                                    //echo "$val<br>";
                                    $contentInfo .= $val . "\n";
                                }
                            }
                            unset($links);
                        }

                        if(!empty($contentInfo)) {
                            $scrappingInfo = scrappingInfo($contentInfo, $gateaway);
                            if($scrappingInfo) {
                                $html = "<em>" . $scrappingInfo[0] . "</em>" . "\n";
                                $scrappingInfoFile = $scrappingInfo[1];
                            } else {
                                $html = "<em>/!\ NO SCRAPPING INFO /!\</em>" . "\n";
                            }
                            ?>
                            <div class="col-sm-12">
                                <h4 class="lead title">[03-04] Store Listing Ad's Links</h4>
                                <?php echo $html; ?>
                            </div>
                <?php
                        }
                    }

                    echo displayErrors($errors, 'Loading (local) Listing Pages');
                }
                ?>
            </div>
        </div>
    <?php
        unset($html,$script,$scrapping_url,$sUrlSrc,$xpath,$xPathQuery,$elements,$element,$counter_loading_listing_pages,$counter_extracting_links,$counter_extracting_txts,$contentInfo,$errors,$key,$error,$k,$err);
    ?>
        <!--<script>alert('04 : www.boatshop24.co.uk_link_details_download');</script>-->
        <!--<script>window.location = '<?php echo $_SERVER['SCRIPT_NAME'] . '?step=4&total_pages=' . $total_pages . '&total_results=' . $total_results . '&scrappingInfoFile=' . urlencode($scrappingInfoFile); ?>';</script>-->
        <a href="<?php echo $_SERVER['SCRIPT_NAME'] . '?step=4&total_pages=' . $total_pages . '&total_results=' . $total_results . '&scrappingInfoFile=' . urlencode($scrappingInfoFile); ?>" class="btn btn-success">Go to Step 04 : Pages Details Download</a>
    <?php
    }
    ?>
    <?php
    if(4 == $step && !empty($scrappingInfoFile)) {
        $scrapping_links = file($_SERVER['DOCUMENT_ROOT'] . $scrappingInfoFile, FILE_IGNORE_NEW_LINES);
        array_shift($scrapping_links);
        $result = array_unique($scrapping_links);
        $scrapping_links = $result;
    ?>
        <!--    // 04 : www.boatshop24.co.uk_link_details_download-->
        <div class="well">
            <div class="row">
            <?php
                if(!empty($scrapping_links)) {
            ?>
                    <div class="col-sm-12">
                        <h3>[04] <?php echo $gateaway; ?> Pages Details Download</h3>
                    </div>

                    <div class="col-sm-12">
                        <div class="progress">
                            <h4 class="lead title">[04-01] Download Ad's pages</h4>
                            <div id="progress_bar_04_download_ads_pages" class="progress-bar active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%"></div>
                        </div>
                    </div>

                    <script>
                        var $ProgressBar_download_ads_pages = $("#progress_bar_04_download_ads_pages");
                        var $ProgressBar_download_ads_pages_parent = $ProgressBar_download_ads_pages.parent(".progress");
                        var $ProgressBarTitle_download_ads_pages = $ProgressBar_download_ads_pages_parent.find(".title");
                    </script>

            <?php
                    $counter_download_ads_pages = 0;
                    $timeout = 30;
                    $errors = [];
                    $total_results = count($scrapping_links);
                    // grab distant urls
                    foreach ($scrapping_links as $link) {
                        //$html .= '    <p><a href="' . $link . '" target="_blank">' . $link . '</a></p>';
                        $ad_id = substr(strrchr($link, "/"), 1);
                        if(!empty($ad_id)) {
                            //$sUrlSrc = getWebsiteContent($link, 'ad_detail_' . $ad_id, $gateaway, '/ads/' . $ad_id, $timeout, true, '', true);
                            $sUrlSrc = getWebsiteContent($link, 'ad_detail_' . $ad_id, $gateaway, '/ads/' . $ad_id, $timeout, true);
                            if (!empty($sUrlSrc) && is_array($sUrlSrc)) {
                                $errors = $errors + $sUrlSrc;
                            } else {
                                $script = '<script>' . "\n";
                                //$script .= '$ProgressBar_download_ads_pages_parent.show();' . "\n";
                                $script .= '$ProgressBarTitle_download_ads_pages.find(".infos").remove();' . "\n";
                                $script .= '$ProgressBarTitle_download_ads_pages.append("<span class=\'infos\'> (' . $counter_download_ads_pages . ' / ' . $total_results . ')</span>");' . "\n";
                                $script .= 'progressBar($ProgressBar_download_ads_pages, ' . ceil(($counter_download_ads_pages * 100) / $total_results) . ', false);' . "\n";
                                $script .= '</script>' . "\n";
                                echo $script;
                                $counter_download_ads_pages++;
                            }
                        }
                    }
                    unset($scrapping_links);

                    echo displayErrors($errors, 'Download Ad\'s pages');
                }
            ?>
            </div>
        </div>
    <?php
    unset($html,$script,$link,$ad_id,$sUrlSrc,$counter_download_ads_pages,$errors,$key,$error,$k,$err);
    ?>
        <!--<script>alert('05 : www.boatshop24.co.uk_pages_details_processing');</script>-->
        <!--<script>window.location = '<?php echo $_SERVER['SCRIPT_NAME'] . '?step=5&scrappingInfoFile=' . urlencode($scrappingInfoFile); ?>';</script>-->
        <a href="<?php echo $_SERVER['SCRIPT_NAME'] . '?step=5&scrappingInfoFile=' . urlencode($scrappingInfoFile); ?>" class="btn btn-success">Go to Step 05 : Pages Details Processing</a>
    <?php
    }
    ?>
    <?php
    if(5 == $step && !empty($scrappingInfoFile)) {
        $scrapping_links = file($_SERVER['DOCUMENT_ROOT'] . $scrappingInfoFile, FILE_IGNORE_NEW_LINES);
        array_shift($scrapping_links);
        $result = array_unique($scrapping_links);
        $scrapping_links = $result;
    ?>
        <!--    // 05 : www.boatshop24.co.uk_pages_details_processing-->
        <div class="well">
            <div class="row">
            <?php
            if(!empty($scrapping_links)) {
            ?>
                <div class="col-sm-12">
                    <h3>[05] <?php echo $gateaway; ?> Pages Details Processing</h3>
                </div>

                <div class="col-sm-12">
                    <div class="progress">
                        <h4 class="lead title">[05-01] Processing (local) Pages Details Datas</h4>
                        <div id="progress_bar_05_pages_details_datas" class="" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%"></div>
                    </div>
                </div>

                <script>
                    var $ProgressBar_pages_details_datas = $("#progress_bar_05_pages_details_datas");
                    var $ProgressBar_pages_details_datas_parent = $ProgressBar_pages_details_datas.parent(".progress");
                    var $ProgressBarTitle_pages_details_datas = $ProgressBar_pages_details_datas_parent.find(".title");
                </script>

                <div class="col-sm-12">
                    <div class="progress">
                        <h4 class="lead title">[05-02] Extracting (local) Pages Details Datas</h4>
                        <div id="progress_bar_05_extracting_pages_details_datas" class="" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%"></div>
                    </div>
                </div>

                <script>
                    var $ProgressBar_extracting_pages_details_datas = $("#progress_bar_05_extracting_pages_details_datas");
                    var $ProgressBar_extracting_pages_details_datas_parent = $ProgressBar_extracting_pages_details_datas.parent(".progress");
                    var $ProgressBarTitle_extracting_pages_details_datas = $ProgressBar_extracting_pages_details_datas_parent.find(".title");
                </script>

            <?php
                $counter_processing_pages_details_datas = 1;
                $timeout = 30;
                $ads = [];
                $currentAd = [];

                $errors = [];
                $total_results = count($scrapping_links);
                //$sqlImport = '';

                $empty_links = $error_links = $expired_links = $disabled_links = $success_links = '';
                $cleanBeforeScrapping = isset($_GET['cleanBeforeScrapping']) ? $_GET['cleanBeforeScrapping'] : FALSE;
                $force2update = isset($_GET['force2update']) ? $_GET['force2update'] : FALSE;
                $pdoErrors = '';

                $host       = '127.0.0.1';
                $dbname     = 'youboat-www';
                $port       = '8889';
                $charset    = 'utf8';
                $login      = 'root';
                $pass       = 'root';
                $pdo_conf   = 'mysql:host=' . $host . ';dbname=' . $dbname . ';port=' . $port . ';charset=' . $charset;

                try {
                    //$pdo = new PDO('mysql:host=127.0.0.1;dbname=youboat-www;port=8889;charset=utf8', 'root', 'root');
                    $pdo = new PDO($pdo_conf, $login, $pass);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $tblName = 'scrapping_ads_details';

                    if($cleanBeforeScrapping) {
                        $sql = "DELETE FROM $tblName WHERE ad_referrer = :ad_referrer";
                        $stmt = $pdo->prepare($sql);
                        //$stmt->bindParam(':ad_referrer', $gateaway, PDO::PARAM_STR);
                        //$stmt->execute();
                        $stmt->execute( [":ad_referrer" => $gateaway] );
                    }

                    $dataToInsert = [];

                    $processingXpath[] = ['id'=>'ad_ref', 'xpath'=>'//*[@class="ad-detail-wrapper"]/div[@class="ad_header_block"]/div[@class="top"]//span[@class="ref"]'];
                    $processingXpath[] = ['id'=>'ad_title', 'xpath'=>'//*[@class="ad-detail-wrapper"]/div[@class="ad_header_block"]/div[@class="top"]/h1[@class="title"]'];

                    $processingXpath[] = ['id'=>'ad_type_cat_name', 'xpath'=>'//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td[contains(.,"Type:")]/following::td[1]|//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td[contains(.,"Category:")]/following::td[1]'];
                    $processingXpath[] = ['id'=>'ad_type_cat_atitle', 'xpath'=>'//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td[contains(.,"Type:")]/following::td[1]/a|//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td[contains(.,"Category:")]/following::td[1]/a']; ///@title
                    $processingXpath[] = ['id'=>'ad_type_cat_url', 'xpath'=>'//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td[contains(.,"Type:")]/following::td[1]/a/@href|//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td[contains(.,"Category:")]/following::td[1]/a/@href'];

                    // !!!!

                    $processingXpath[] = ['id'=>'ad_subcategory_name', 'xpath'=>'//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td[contains(.,"Subcategory:")]/following::td[1]'];
                    $processingXpath[] = ['id'=>'ad_subcategory_atitle', 'xpath'=>'//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td[contains(.,"Subcategory:")]/following::td[1]/a']; ///@title
                    $processingXpath[] = ['id'=>'ad_subcategory_url',   'xpath'=>'//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td[contains(.,"Subcategory:")]/following::td[1]/a/@href'];

                    // !!!!
                    $processingXpath[] = ['id'=>'ad_location', 'xpath'=>'//*[@class="ad-detail-wrapper"]/div[@class="ad_header_block"]/div[@class="top"]//a[@class="location"]'];

                    /*
                     * //*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td[contains(.,"Manufacturer:")]/following::td[@class="label_value"][1]
                     * //*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td[contains(.,"Manufacturer:")]/following::td[@class="label_value"][1]/a/@title
                     */
                    $processingXpath[] = ['id'=>'ad_manufacturer_name', 'xpath'=>'//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td[contains(.,"Manufacturer:")]/following::td[@class="label_value"][1]'];
                    $processingXpath[] = ['id'=>'ad_manufacturer_atitle', 'xpath'=>'//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td[contains(.,"Manufacturer:")]/following::td[1]/a']; ///@title
                    $processingXpath[] = ['id'=>'ad_manufacturer_url', 'xpath'=>'//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td[contains(.,"Manufacturer:")]/following::td[1]/a/@href'];

                    // @TODO : voir si besoin complément xpath pour ad_model_name
                    /*
                     *
                     */
                    $processingXpath[] = ['id'=>'ad_model_name',    'xpath'=>'//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td[not(@style="display: none;")]/span[contains(.,"Model:")][1]/following::span[@class="label_value"][1]'];
                    $processingXpath[] = ['id'=>'ad_model_atitle',   'xpath'=>'//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td/span[contains(.,"Model:")]/following::span[1]/a']; ///@title
                    $processingXpath[] = ['id'=>'ad_model_url', 'xpath'=>'/*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td/span[contains(.,"Model:")]/following::span[1]/a/@href'];

                    $processingXpath[] = ['id'=>'ad_mooring_country', 'xpath'=>'//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td[contains(.,"Mooring Country:")]/following::td[1]'];
                    $processingXpath[] = ['id'=>'ad_width', 'xpath'=>'//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td[contains(.,"Width:")]/following::td[1]'];
                    $processingXpath[] = ['id'=>'ad_length', 'xpath'=>'//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td[contains(.,"Length:")]/following::td[1]'];

                    $processingXpath[] = ['id'=>'ad_price', 'xpath'=>'//*[@class="ad-detail-wrapper"]/div[@class="ad_header_block"]/div[@class="top"]/div[@class="price"]/text()'];
                    $processingXpath[] = ['id'=>'ad_price_descr', 'xpath'=>'//*[@class="ad-detail-wrapper"]/div[@class="ad_header_block"]/div[@class="top"]/div[@class="price"]/span[@class="price_descr"]'];

                    $processingXpath[] = ['id'=>'ad_sale', 'xpath'=>'//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td[not(@style="display: none;")]/span[contains(.,"Sale:")]/following::span[@class="label_value"][1]'];
                    $processingXpath[] = ['id'=>'ad_sale_type_condition', 'xpath'=>'//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td[contains(.,"Sale Type:")]/following::td[1]|//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td[contains(.,"Condition:")]/following::td[1]'];
                    $processingXpath[] = ['id'=>'ad_sales_status', 'xpath'=>'//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td[contains(.,"Sales status:")]/following::td[1]'];

                    $processingXpath[] = ['id'=>'ad_dealer_name', 'xpath'=>'//*[@class="ad_info"]//div[@class="item_contact_descr ad_url_min"]//a/@title'];
                    $processingXpath[] = ['id'=>'ad_dealer_url', 'xpath'=>'//*[@class="ad_info"]//div[@class="item_contact_descr ad_url_min"]//a/@href'];
                    $processingXpath[] = ['id'=>'ad_phones', 'xpath'=>'//*[@class="ad-buttons-block"]//span[@class="phone-number"]'];

                    $processingXpath[] = ['id'=>'ad_photo', 'xpath'=>'//*[@id="big_photo_link"]/img/@src'];
                    $processingXpath[] = ['id'=>'ad_photos', 'xpath'=>'//*[@class="ad_images_block"]/ul/li//a/@data-img'];
                    $processingXpath[] = ['id'=>'ad_photos_thumbs', 'xpath'=>'//*[@class="ad_images_block"]//ul[@class="jcarousel-skin-vivapets"]//img/@src'];

                    $processingXpath[] = ['id'=>'ad_description_caracts_block', 'xpath'=>'//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]'];
                    //@TODO : update xptah for ad_description_full to get html useful description
                    $processingXpath[] = ['id'=>'ad_description_full', 'xpath'=>'//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]//div[contains(@class,"ad_descr")]'];
                    $processingXpath[] = ['id'=>'ad_description_caracts_labels', 'xpath'=>'//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td[@class="label"]|//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td/span[@class="label"]'];
                    $processingXpath[] = ['id'=>'ad_description_caracts_values', 'xpath'=>'//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td[@class="label_value"]|//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td/span[@class="label_value"]'];

                    $processingXpath[] = ['id'=>'ad_specifications_caracts_block', 'xpath'=>'//*[@class="ad-detail-wrapper"]/div[@class="ad_info"]/div[@id="ad-block-specs"]//table[@class="other_details"]'];
                    $processingXpath[] = ['id'=>'ad_specifications_full', 'xpath'=>'//*[@class="ad-detail-wrapper"]/div[@class="ad_info"]/div[@id="ad-block-specs"]'];
                    $processingXpath[] = ['id'=>'ad_specifications_caracts', 'xpath'=>'//*[@class="ad-detail-wrapper"]/div[@class="ad_info"]/div[@id="ad-block-specs"]//table[@class="other_details"]//td[@class="label"]|//*[@class="ad-detail-wrapper"]/div[@class="ad_info"]/div[@id="ad-block-specs"]//table[@class="other_details"]//td/span[@class="label"]'];
                    $processingXpath[] = ['id'=>'ad_specifications_caracts_values', 'xpath'=>'//*[@class="ad-detail-wrapper"]/div[@class="ad_info"]/div[@id="ad-block-specs"]//table[@class="other_details"]//td[@class="label_value"]|//*[@class="ad-detail-wrapper"]/div[@class="ad_info"]/div[@id="ad-block-specs"]//table[@class="other_details"]//td/span[@class="label_value"]'];

                    $processingXpath[] = ['id'=>'ad_features_caracts_block', 'xpath'=>'//*[@class="ad-detail-wrapper"]/div[@class="ad_info"]/div[@id="ad-block-features"]//div[@class="ad-block-content"]'];
                    $processingXpath[] = ['id'=>'ad_features_full', 'xpath'=>'//*[@class="ad-detail-wrapper"]/div[@class="ad_info"]/div[@id="ad-block-features"]'];
                    $processingXpath[] = ['id'=>'ad_features_caracts', 'xpath'=>'//*[@class="ad-detail-wrapper"]/div[@class="ad_info"]/div[@id="ad-block-features"]//div[@class="ad-block-content"]/ul[@class="multi"]/li'];
                    //$processingXpath[] = ['id'=>'ad_features_caracts', 'xpath'=>'//*[@class="ad-detail-wrapper"]/div[@class="ad_info"]/div[@id="ad-block-features"]//div[@class="ad-block-content"]/ul[@class="multi"]/li[@class="category"]//following::li'];
                    $processingXpath[] = ['id'=>'ad_features_caracts_categories', 'xpath'=>'//*[@class="ad-detail-wrapper"]/div[@class="ad_info"]/div[@id="ad-block-features"]//div[@class="ad-block-content"]/ul[@class="multi"]/li[@class="category"]'];

                    $processingXpath[] = ['id'=>'ad_year_built', 'xpath'=>'//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td[contains(.,"Year built:")]/following::td[1]'];
                    $processingXpath[] = ['id'=>'ad_propulsion', 'xpath'=>'//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td[contains(.,"Propulsion:")]/following::td[1]'];
                    $processingXpath[] = ['id'=>'ad_nb_engines', 'xpath'=>'//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td[contains(.,"No. of engines:")]/following::td[1]'];

                    // grab local files
                    $z = 0;
                    $countEmptyPage = 0;
                    $countErrorLinks = 0;
                    $countExpiredLinks = 0;
                    $countAdsUpdated = 0;
                    $countAdsDisabled = 0;
                    foreach ($scrapping_links as $link) {
                        $z ++;
                        //echo $z . ' -> ' . $link . '<br>';
                        $domain = parse_url($link, PHP_URL_HOST);
                        $ad_id = substr(strrchr($link, "/"), 1);
                        if(!empty($ad_id)) {
                            //$sUrlSrc = getWebsiteContent($link, 'ad_detail_' . $ad_id, $gateaway, '/ads/' . $ad_id, $timeout, false, "/<body.*\/body>/isU");
                            //$sUrlSrc = getWebsiteContent($link, 'ad_detail_' . $ad_id, $gateaway, '/ads/' . $ad_id, $timeout, false, "/<body[^>]*>(.*?)<\/body>/isU");
                            ////$sUrlSrc = getWebsiteContent($link, 'ad_detail_' . $ad_id, $gateaway, '/ads/' . $ad_id, $timeout, false,  '/(<div[^>]*id=[\'|\"]page[\'|\"][^>]*>)(.*?)<\/div>/isU', true); // unlink forced
                            $sUrlSrc = getWebsiteContent($link, 'ad_detail_' . $ad_id, $gateaway, '/ads/' . $ad_id, $timeout, false,  '/(<div[^>]*id=[\'|\"]page[\'|\"][^>]*>)(.*?)<\/div>/isU');
                            // @TODO REGEX PARAM  TEST);
                            //$sUrlSrc = getWebsiteContent($link, 'ad_detail_' . $ad_id, $gateaway, '/ads/' . $ad_id, $timeout, false, $regex = "<div id=('|\")page('|\")>(.*?)<\/div>");
                            if (!empty($sUrlSrc) && is_array($sUrlSrc)) {
                                //$error_links .= '    <p>[' . $z . ']<a href="' . $link . '" target="_blank" class="text-danger">[ ' . $link . ' ]</a></p>';
                                $error_links .= '[' . $z . '] - ' . $link . ' | ' . $ad_id . '<br>' . "\n";
                                $errors = $errors + $sUrlSrc;
                                $countErrorLinks ++;

                                // remove file
                                $date = date('Ymd');
                                $subdir = '/ads/' . $ad_id;
                                $sCacheFolder = '/scrap/cache/' . $date . '/' . $gateaway . $subdir;
                                $pathname = $_SERVER['DOCUMENT_ROOT'] . $sCacheFolder;
                                rmdir($pathname);
                            } else if (!empty($sUrlSrc)) {
                                //$success_links .= '    <p>[' . $z . ']<a href="' . $link . '" target="_blank" class="text-success">[ ' . $link . ' ]</a></p>';
                                //$success_links .= '[' . $z . '] - ' . $link . '<br>' . "\n";
                                $need2update = TRUE;
                                $need2disable = FALSE;
                                $date = date("Y-m-d");
                                $currentDate = date("Y-m-d H:i:s");

                                $xpath = getXPath($sUrlSrc);

                                //expired
                                if (!is_null($elements = $xpath->query('//*[@class="ad_expired"]'))) {
                                    if (false !== $elements && ($elements instanceof Traversable || is_array($elements) || is_object($elements))) {
                                        foreach ($elements as $element) {
                                            if (!empty($element)) {
                                                //$expired_links .= '    <p>[' . $z . ']<a href="' . $link . '" target="_blank" class="text-warning">[ ' . $link . ' ]</a></p>';
                                                $expired_links .= '[' . $z . '] - ' . $link . '<br>' . "\n";
                                                $val= $element->nodeValue;
                                                $need2update = FALSE;
                                                $need2disable = TRUE;
                                                //var_dump($val);
                                                //die();
                                                $countExpiredLinks ++;
                                            }
                                        }
                                    }
                                //} elseif (!$force2update && !is_null($elements = $xpath->query('//*[@class="ad-detail-wrapper"]/div[@class="ad_header_block"]/div[@class="top"]//span[@class="ref"]'))) {
                                } elseif (!is_null($elements = $xpath->query('//*[@class="ad-detail-wrapper"]/div[@class="ad_header_block"]/div[@class="top"]//span[@class="ref"]'))) {
                                    if (false !== $elements && ($elements instanceof Traversable || is_array($elements) || is_object($elements))) {
                                        foreach ($elements as $element) {
                                            if (!empty($element)) {
                                                $val= $element->nodeValue;
                                                $ad_ref = preg_replace('/^ad ref. /', '', $val);
                                                $ad_ref = preg_replace('/^annonce ref. /', '', $ad_ref);
                                            }
                                        }
                                        unset($elements);
                                    }
                                    //echo ">> " . $ad_ref . '<br>';
                                    if(!empty($ad_ref)) {
                                        if($force2update) {
                                            $need2update = TRUE;
                                        } else {
                                            $sql = "SELECT COUNT(*) as count, updated_at from $tblName WHERE ad_ref = ? LIMIT 1";
                                            $stmt = $pdo->prepare($sql);
                                            $stmt->bindParam(1, $ad_ref, PDO::PARAM_INT);
                                            $stmt->execute();
                                            //if ($stmt->fetchColumn()) die('found');
                                            $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                            if ($result['count'] > 0 &&  preg_match('/' . $date . '/', preg_split("/[\s,]+/", $result['updated_at'])[0])) {
                                                $need2update = FALSE;
                                            } elseif ($result['count'] > 0 &&  !preg_match('/' . $date . '/', preg_split("/[\s,]+/", $result['updated_at'])[0])) {
                                                $need2update = TRUE;
                                            }
                                        }
                                    }
                                }

                                if($need2disable) {
                                    //$sql = "UPDATE $tblName SET ad_sales_status = 'removed', updated_at = NOW() WHERE ad_pageUrl = :ad_pageUrl";
                                    $sql = "UPDATE $tblName SET ad_sales_status = 'removed', updated_at = NOW() WHERE ad_ref = :ad_ref";
                                    $stmt = $pdo->prepare($sql);
                                    //$stmt->bindParam(':ad_pageUrl', $link, PDO::PARAM_STR);
                                    //$stmt->bindParam(':ad_ref', $link, PDO::PARAM_STR);

                                    try {
                                        $pdo->beginTransaction();
                                        // Exécute la requête avec variables "protégées"
                                        //echo 'Exécute la requête avec variables "protégées"' . "<br>";
                                        //$result = $stmt->execute();
                                        //$result = $stmt->execute( [":ad_pageUrl" => $link] );
                                        $result = $stmt->execute( [":ad_ref" => $ad_ref] );

                                        if(!$result) {
                                            echo displayErrors($stmt->errorInfo() . '<br>' . $link . ' | ' . $ad_ref . ' PDO Errors');
                                        } else {
                                            $disabled_links .= '[' . $z . '] - ' . $link . ' | ' . $ad_ref . '<br>' . "\n";
                                            $countAdsDisabled ++;
                                        }
                                        $pdo->commit();
                                    } catch (PDOException $e){
                                        $pdoErrors = '<h4>PDOException UPDATE Disabled</h4><p class="text-danger">' . $e->getMessage() . '<br>' .  $link . ' | ' . $ad_ref . '</p>';
                                        $pdoErrors = '<div class="col-sm-12 alert alert-danger">' . $pdoErrors . '</div>';
                                        echo $pdoErrors;
                                    }
                                } elseif($need2update) {
                                    $processing[] = ['id'=>'ad_country_code', 'value'=>'uk'];
                                    $processing[] = ['id'=>'ad_referrer', 'value'=>$gateaway];

                                    $processing[] = ['id'=>'ad_pageUrl', 'value'=>$link];

                                    $processing[] = ['id'=>'created_at', 'value'=>$currentDate];
                                    $processing[] = ['id'=>'updated_at', 'value'=>$currentDate];
                                    $processing[] = ['id'=>'deleted_at', 'value'=>NULL];

                                    ////$processing[] = ['id'=>'start_date', 'value'=>'0000-00-00 00:00:00'];
                                    ////$processing[] = ['id'=>'end_date', 'value'=>'0000-00-00 00:00:00'];
                                    ////$processing[] = ['id'=>'status', 'value'=>'active'];

                                    $processing = array_merge($processingXpath, $processing);

                                    foreach ($processing as $process) {
                                        if(array_key_exists('value', $process)) {
                                            //echo '<br>' . $process['id'];
                                            //echo ' = ' . $process['value'];
                                            //$ads[$ad_id][$process['id']] = $process['value'];
                                            $currentAd[$process['id']] = $process['value'];

                                            //$sqlKeys .= '`' . $process['id'] .'`, ';
                                            //$sqlValues .= mysqli::escape_string($process['value']);

                                            //$PDOKeys .= $process['id'] .', ';
                                            //$PDOValues .= ':' . $process['id'] .', ';
                                            //$PDOValuesARRAY[':' . $process['id']] = ':' . $process['id'] .', ';
                                        } else if(array_key_exists('xpath', $process)) {
                                            //echo '<br>' . $process['id'];
                                            //echo ' = ' . $process['xpath'];
                                            //$elements = $xpath->query($process['xpath']);
                                            $value = '';
                                            $delimiter = '';
                                            if (!is_null($elements = $xpath->query($process['xpath']))) {
                                                if (false !== $elements && ($elements instanceof Traversable || is_array($elements) || is_object($elements))) {
                                                    foreach ($elements as $element) {
                                                        if (!empty($element)) {
                                                            //var_dump($element->nodeType);
                                                            $val = $element->nodeValue;
                                                            switch ($process['id']) {
                                                                case 'ad_photo' :
                                                                    $val = preg_replace('/^\//', 'http://' . $domain . '/', $val);
                                                                    $delimiter = '';
                                                                    break;
                                                                case 'ad_photos' :
                                                                case 'ad_photos_thumbs' :
                                                                    $val = preg_replace('/^\//', 'http://' . $domain . '/', $val);
                                                                    $delimiter = ';';
                                                                    break;
                                                                case 'ad_phones':
                                                                case 'ad_description_caracts_labels' :
                                                                case 'ad_description_caracts_values' :
                                                                case 'ad_specifications_caracts' :
                                                                case 'ad_specifications_caracts_values' :
                                                                case 'ad_features_caracts' :
                                                                case 'ad_features_caracts_categories' :
                                                                    $delimiter = ';';
                                                                    break;
                                                                default :
                                                                    $delimiter = '';
                                                                    break;
                                                                case 'ad_ref':
                                                                    $val = preg_replace('/^ad ref. /', '', $val);
                                                                    $val = preg_replace('/^annonce ref. /', '', $val);
                                                                    $val = $ad_id . '|' . $val;
                                                                    $delimiter = '';
                                                                    break;
                                                                //@TODO : update xptah for ad_description_full to get html useful description
                                                                /*case 'ad_description_full':
                                                                    $html = $element->ownerDocument->saveHTML($element);
                                                                    var_dump($html);
                                                                    die();
                                                                    break;*/
                                                            }
                                                            $value .= $val . $delimiter;
                                                        }
                                                        //$sqlKeys .= '`' . $process['id'] .'`, ';
                                                        //$sqlValues .= mysqli::escape_string($element->nodeValue);
                                                    }

                                                    unset($elements);
                                                }
                                                //$ads[$ad_id][$process['id']] = $value;

                                                $currentAd[$process['id']] = $value;

                                                // set clean value for _name
                                                if(preg_match('/_name/', $process['id']) || preg_match('/_title/', $process['id']) ) {
                                                    $val  = $currentAd[$process['id']];
                                                    $currentAd[$process['id']] = preg_replace('/\s+/', ' ', str_replace([',', ' ,'], [' ', ' '], $val));
                                                    unset($val);
                                                }

                                                // clean description _full to reduce size of datas
                                                if(preg_match('/_full/', $process['id'])) {
                                                    $full = $currentAd[$process['id']];

                                                    $block2removeProcessId = preg_replace('/_full/', '_caracts_block', $process['id']);
                                                    $block2remove = $currentAd[$block2removeProcessId];

                                                    $full = str_replace($block2remove, '', $full);
                                                    $full = preg_replace('/\s+/', ' ', $full);
                                                    $currentAd[$process['id']] = $full;

                                                    unset($currentAd[$block2removeProcessId], $full, $block2remove, $block2removeProcessId);
                                                }
                                                // set clean value for _name
                                                if(preg_match('/ad_description_full/', $process['id']) ) {
                                                    $val  = $currentAd[$process['id']];
                                                    $val = preg_replace('/& nbsp;/', ' ', $val);
                                                    $val = preg_replace('/&nbs p;/', ' ', $val);
                                                    $val = preg_replace('/&n bsp;/', ' ', $val);
                                                    $val = preg_replace('/&nb sp;/', ' ', $val);
                                                    $val = str_replace(': ; ;', ':', $val);
                                                    $val = str_replace([' ,', ',', ' .', '.', ' ;', ';'], [', ', ', ', '. ', '. ', '; ', '; '], $val);
                                                    $currentAd[$process['id']] = preg_replace('/\s+/', ' ', $val);
                                                    unset($val);
                                                }

                                                // set _name with _atitle if _name is empty
                                                if(preg_match('/_atitle/', $process['id'])) {
                                                    $atitle  = $currentAd[$process['id']];
                                                    $nameToCheckProcessId = preg_replace('/_atitle/', '_name', $process['id']);
                                                    $nameToCheck = $currentAd[$nameToCheckProcessId];
                                                    if(!isset($nameToCheck) || empty($nameToCheck)) {
                                                        //$currentAd[$nameToCheckProcessId] = $currentAd[$process['id']];
                                                        $currentAd[$nameToCheckProcessId] = preg_replace('/\s+/', ' ', str_replace([',', ' ,'], [' ', ' '], $atitle));
                                                    }
                                                    unset($currentAd[$process['id']], $atitle, $nameToCheck, $nameToCheckProcessId);
                                                }

                                            }
                                        }
                                    }
                                    unset($processing);

                                    /*$sqlInsert = "INSERT INTO $tblName (";
                                    $sqlInsert .= $sqlKeys;
                                    //$sqlInsert .= '`start_date`, `end_date`, `status`, `created_at`, `updated_at`, `deleted_at`';
                                    $sqlInsert .= ') VALUES (';
                                    $sqlInsert .= $sqlValues;
                                    //$sqlInsert .= "'0000-00-00 00:00:00', '0000-00-00 00:00:00', 'active', $currentDate, $currentDate, NULL";
                                    $sqlInsert .= ');';

                                    $sqlImport .= $sqlInsert . "\n";
                                    */

                                    //$fields = array_keys($ads[$ad_id]);
                                    $fields = array_keys($currentAd);
                                    $colNames = implode(',', $fields);
                                    $placeHolders = substr(str_repeat(',?', count($fields)), 1);

                                    // (optional) setup the ON DUPLICATE column names
                                    $updateCols = array();
                                    foreach ($fields as $curCol) {
                                        $updateCols[] = $curCol . " = VALUES($curCol)";
                                    }
                                    unset($fields);
                                    //var_dump('$updateCols');
                                    //var_dump($updateCols);
                                    $onDup = implode(', ', $updateCols);

                                    $values = [];
                                    //array_push($values, array_values($ads[$ad_id]));
                                    array_push($values, array_values($currentAd));

                                    $sql = "INSERT INTO $tblName (";
                                    $sql .= $colNames ;
                                    //$sql .= 'start_date, end_date, status, created_at, updated_at, deleted_at';
                                    $sql .= ') VALUES (';
                                    $sql .= $placeHolders;
                                    //$sql .= ':start_date, :end_date, :status, :created_at, :updated_at, :deleted_at';
                                    //$sql .= ');';
                                    $sql .= ') ';
                                    $sql .= "ON DUPLICATE KEY UPDATE $onDup";

                                    // Prépare la requête avec deux variables, précédées de ":"
                                    $stmt = $pdo->prepare($sql);
                                    $counter_processing_pages_details_datas++;
                                    try {
                                        $pdo->beginTransaction();
                                        // Exécute la requête avec variables "protégées"
                                        //echo 'Exécute la requête avec variables "protégées"' . "<br>";
                                        $result = $stmt->execute($values[0]);
                                        $newId = $pdo->lastInsertId();
                                        if(!$result) {
                                            echo displayErrors($stmt->errorInfo() . '<br>' . $link, 'PDO Errors');
                                        } else {
                                            /*$script = '<script>' . "\n";
                                            //$script .= '$ProgressBar_pages_details_datas_parent.show();' . "\n";
                                            $script .= '$ProgressBarTitle_pages_details_datas.find(".infos").remove();' . "\n";
                                            $script .= '$ProgressBarTitle_pages_details_datas.append("<span class=\'infos\'> (' . $counter_processing_pages_details_datas . ' / ' . $total_results . ') [' . $link . ']</span>");' . "\n";
                                            $script .= 'progressBar($ProgressBar_pages_details_datas, ' . ceil(($counter_processing_pages_details_datas * 100) / $total_results) . ');' . "\n";
                                            $script .= '</script>' . "\n";
                                            echo $script;
                                            $counter_processing_pages_details_datas++;
                                            */
                                            $success_links .= '[' . $z . '] - ' . $link . '<br>' . "\n";
                                            $countAdsUpdated ++;
                                        }
                                        $pdo->commit();
                                    } catch (PDOException $e){
                                        $pdoErrors = '<h4>PDOException INSERT or UPDATE</h4><p class="text-danger">' . $e->getMessage() . '<br>' . $link . '</p>';
                                        $pdoErrors = '<div class="col-sm-12 alert alert-danger">' . $pdoErrors . '</div>';
                                        echo $pdoErrors;
                                    }
                                    $script = '<script>' . "\n";
                                    //$script .= '$ProgressBar_pages_details_datas_parent.show();' . "\n";
                                    $script .= '$ProgressBarTitle_pages_details_datas.find(".infos").remove();' . "\n";
                                    $script .= '$ProgressBarTitle_pages_details_datas.append("<span class=\'infos\'> (' . $counter_processing_pages_details_datas . ' / ' . $total_results . ') [' . $link . ']</span>");' . "\n";
                                    $script .= 'progressBar($ProgressBar_pages_details_datas, ' . ceil(($counter_processing_pages_details_datas * 100) / $total_results) . ');' . "\n";
                                    $script .= '</script>' . "\n";
                                    echo $script;
                                    //if($counter_processing_pages_details_datas>=1) break;
                                }
                            } else {
                                $id = parse_url($link);
                                $id = $id['path'];
                                $id = explode('/',$id);
                                $id = end($id);
                                if(!empty($id)) {
                                    $base = preg_replace('/scrappingInfo.txt/', '', $scrappingInfoFile) . 'ads/';
                                    $toRedoLink = $_SERVER['DOCUMENT_ROOT'] . $base . $id;
                                    //delete_files($toRedoLink);
                                    rrmdir($toRedoLink);
                                    //echo  'Empty >> [' . $z . ']<a href="' . $link . '" target="_blank" class="text-info">[ ' . $link . ' ]</a>' . $toRedoLink . '</br>';
                                    $empty_links .= '[' . $z . '] - ' . $link . '<br>' . "\n";
                                    $countEmptyPage ++;
                                }
                            }
                        }
                    }
                    unset($scrapping_links);
                    $errors = displayErrors($errors, 'Loading (local) Scrapping Ad\'s pages');
                    echo $errors;
                } catch (PDOException $e){
                    $pdoErrors = '<h4>PDOException Connect or DELETE</h4><p class="text-danger">' . $e->getMessage() . '</p>';
                    $pdoErrors = '<div class="col-sm-12 alert alert-danger">' . $pdoErrors . '</div>';
                    echo $pdoErrors;
                }

                $report = '';
                $report .= $pdoErrors . "\n";
                $report .= $errors . "\n";

                if(!empty($empty_links)) {
                    $empty_links = '<div class="col-sm-12 alert alert-danger">' .
                    '<h4><strong>' . $countEmptyPage . '</strong> Empty Pages</h4>' .
                    '<p><a href="' . $_SERVER['SCRIPT_NAME'] . '?step=4&total_pages=' . $total_pages . '&total_results=' . $total_results . '&scrappingInfoFile=' . urlencode($scrappingInfoFile) .'" class="btn btn-lg btn-block btn-danger">RE-Go to Step 04 : Pages Details Download</a></p>' .
                    $empty_links .
                    '</div>';
                    //echo $empty_links;
                    $report .= $empty_links . "\n";
                }
                if(!empty($error_links)) {
                    $error_links = '<div class="col-sm-12 alert alert-danger">' .
                    '<h4><strong>' . $countErrorLinks . '</strong> Error Links</h4>' .
                    '<p><a href="' . $_SERVER['SCRIPT_NAME'] . '?step=4&total_pages=' . $total_pages . '&total_results=' . $total_results . '&scrappingInfoFile=' . urlencode($scrappingInfoFile) .'" class="btn btn-lg btn-block btn-danger">RE-Go to Step 04 : Pages Details Download</a></p>' .
                    $error_links .
                    '</div>';
                    //echo $error_links;
                    $report .= $error_links . "\n";
                }
                if(!empty($expired_links)) {
                    $expired_links = '<div class="col-sm-12 alert alert-warning">' .
                        '<h4><strong>' . $countExpiredLinks . '</strong> Expired Links</h4>' .
                        $expired_links .
                        '</div>';
                    //echo $expired_links;
                    $report .= $expired_links . "\n";
                }
                if(!empty($disabled_links)) {
                    $disabled_links = '<div class="col-sm-12 alert alert-info">' .
                        '<h4><strong>' . $countAdsDisabled . '</strong> Disabled Links</h4>' .
                        $disabled_links .
                        '</div>';
                    //echo $expired_links;
                    $report .= $disabled_links . "\n";
                }
                echo $report;
                if(!empty($success_links)) {
                    $success_links = '<div class="col-sm-12 alert alert-success">' .
                    '<h4>Success Links <strong class="text-info">' . $countAdsUpdated . ' Ads updated</strong></h4>' .
                    $success_links .
                    '</div>';
                    echo $success_links;
                    $report .= '<div class="col-sm-12 alert alert-success">' .
                    '<h4>Success Links <strong class="text-info">' . $countAdsUpdated . ' Ads updated</strong></h4>' .
                    '</div>' . "\n";
                }

                $to = "emmanuel.deiller.rivamedia@gmail.com";
                $subject = "[" . $gateaway . "] Report" . ' [' . $date . ']';
                $message = $report;
                $message = '<html>
                    <head>
                        <title>' . $subject . '</title>
                        <meta name="viewport" content="width=device-width, initial-scale=1">
                        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
                        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
                    </head>
                    <body>' .
                        '<h1>' . $subject . '</h1>' .
                        $report .
                    '</body>
                </html>';
                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

                // Mail report
                mail($to, $subject, $message, $headers);
                unset($report);
            }
            ?>
            </div>
        </div>
    <?php
    unset($html,$script,$scrapping_links,$link,$ad_id,$sUrlSrc,$counter_processing_pages_details_datas,$processing,$process,$errors,$key,$error,$k,$err);
    ?>
    <?php
    }
    ?>
    <?php
    if(6 == $step && !empty($scrappingInfoFile)) {
        $subdir = '/pages';
        $date = date('Ymd');
        $sCacheFolder = '/scrap/cache/' . $date . '/' . $gateaway . $subdir;
        $pathname = $_SERVER['DOCUMENT_ROOT'] . $sCacheFolder;
        rrmdir($pathname);
    ?>
        <!--    // 06 : www.boatshop24.co.uk_results_links_removing -->
        <div class="well">
            <div class="row">
                <div class="col-sm-12">
                    <h3>[05] <?php echo $gateaway; ?> Listing Pages removing</h3>
                </div>

                <div class="col-sm-12">
                </div>
            </div>
        </div>
    <?php
    }
    ?>
</div>

</body>
</html>