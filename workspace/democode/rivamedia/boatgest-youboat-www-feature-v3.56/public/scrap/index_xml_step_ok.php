<?php
    // DB conf connexion
    $host = '127.0.0.1';
    $dbname = 'youboat-www';
    $port = '8889';
    $charset = 'utf8';
    $login = 'root';
    $pass = 'root';
    $pdo_conf = 'mysql:host=' . $host . ';dbname=' . $dbname . ';port=' . $port . ';charset=' . $charset;

    ini_set('max_execution_time', 360); // Maximum execution time of each script, in seconds (I CHANGED THIS VALUE)
    ini_set('max_input_time', 120); // Maximum amount of time each script may spend parsing request data
    //ini_set('max_input_nesting_level', 64); // Maximum input variable nesting level
    ini_set('memory_limit', '256M'); // Maximum amount of memory a script may consume (128MB by default)
    //ini_set('memory_limit', '-1');

    set_time_limit (0);

    require_once('include/library.php');
    require_once('include/dealers_infos.php');

    //$selectedDate     = isset($_GET['date']) ? $_GET['date'] : '';
    $gateaway           = isset($_GET['gateaway']) ? $_GET['gateaway'] : 'www.seaside.fr';
    $step               = isset($_GET['step']) ? $_GET['step'] : 1;
    $total_pages        = isset($_GET['total_pages']) ? $_GET['total_pages'] : null;
    $total_results      = isset($_GET['total_results']) ? $_GET['total_results'] : null;
    $scrappingInfoFile  = isset($_GET['scrappingInfoFile']) ? urldecode($_GET['scrappingInfoFile']) : null;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>

    <title>GateAway Scrapping</title>
    <meta name="google-site-verification" content="ZUa1-uwrom7zzOT7iyxngZVnrEiGJe5P3E5WrnA8fxQ">
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

    <?php
    $timeout                = 10;
    $page                   = 1;

    $gateaway               = 'www.seaside.fr';
    $id_client              = $dealer_infos[$gateaway]['id_client'];
    $dealer_name            = isset($dealer_infos[$gateaway]['name']) && !empty($dealer_infos[$gateaway]['name']) ? $dealer_infos[$gateaway]['name'] : '';

    $dealer_address         = isset($dealer_infos[$gateaway]['address']) && !empty($dealer_infos[$gateaway]['address']) ? ucwords(mb_strtolower($dealer_infos[$gateaway]['address'])) : '';
    $dealer_zip             = isset($dealer_infos[$gateaway]['zip']) && !empty($dealer_infos[$gateaway]['zip']) ? $dealer_infos[$gateaway]['zip'] : '';
    $dealer_city            = isset($dealer_infos[$gateaway]['city']) && !empty($dealer_infos[$gateaway]['city']) ? ucwords(mb_strtolower($dealer_infos[$gateaway]['city'])) : '';
    $dealer_country         = isset($dealer_infos[$gateaway]['country']) && !empty($dealer_infos[$gateaway]['country']) ? ucwords(mb_strtolower($dealer_infos[$gateaway]['country'])) : 'uk';
    $dealer_country_contract_code    = isset($dealer_infos[$gateaway]['country_contract_code']) && !empty($dealer_infos[$gateaway]['country_contract_code']) ? $dealer_infos[$gateaway]['country_contract_code'] : 'uk';

    $dealer_mooring_country = isset($dealer_infos[$gateaway]['mooring_country']) && !empty($dealer_infos[$gateaway]['mooring_country']) ? ucwords(mb_strtolower($dealer_infos[$gateaway]['mooring_country'])) : '';

    $dealer_location = !empty($dealer_address) ? $dealer_address : '' ;
    $dealer_location .= empty($dealer_location) ? '' : ', ' ;
    $dealer_location .= !empty($dealer_zip) ? $dealer_zip : '' ;
    $dealer_location .= empty($dealer_location) ? '' : ', ' ;
    $dealer_location .= !empty($dealer_country) ? $dealer_country : '' ;
    //$dealer_location .= empty($dealer_location) ? '' : ', ' ;
    //$dealer_location .= !empty($dealer_country_contract_code) ? mb_strtoupper($dealer_country_contract_code) : '' ;

    if(empty($dealer_mooring_country)) {
        $dealer_mooring_country = $dealer_location;
    }

    $dealer_domain          = isset($dealer_infos[$gateaway]['domain']) && !empty($dealer_infos[$gateaway]['domain']) ? $dealer_infos[$gateaway]['domain'] : '';
    $dealer_phone           = isset($dealer_infos[$gateaway]['phone']) && !empty($dealer_infos[$gateaway]['phone']) ? $dealer_infos[$gateaway]['phone'] : '';
    $url_prefix_id          = isset($dealer_infos[$gateaway]['url_prefix_id']) && !empty($dealer_infos[$gateaway]['url_prefix_id']) ? $dealer_infos[$gateaway]['url_prefix_id'] : '';

    $scrapping_url  = 'http://www.youboat.fr/automat/scraping/' . $id_client .'.xml';

    $processingXpath = $dealer_infos[$gateaway]['xpath'];

    //grab distant url
    $subdir = '/pages';

    // save local file xml
    $sUrlSrc = getWebsiteContent($scrapping_url, '', $gateaway, $subdir , $timeout, true);
    $date = date('Ymd');

    $sCacheFolder = '/scrap/cache/' . $date . '/' . $gateaway . $subdir;

    $xml_content = file_get_contents($_SERVER['DOCUMENT_ROOT'] . $sCacheFolder . '/' . $id_client .'.xml');

    $annonces = simplexml_load_file($_SERVER['DOCUMENT_ROOT'] . $sCacheFolder . '/' . $id_client .'.xml');

    $total_results = 0;
    $disabled_links = '';
    if($annonces !==  FALSE) {
        $head = '<div class="well"><div class="row">' . "\n";

        $total_results = count($annonces->annonce);
        $head .= '<div class="col-sm-4"><strong>New ads: </strong>' . $total_results . '</div>' . "\n";

        /*foreach ($annonces->annonce as $annonce) {
            echo 'id : ', $annonce->id->__toString(), '<br>';
            echo 'type : ', $annonce->type->__toString(), '<br>';
            echo 'marque : ', $annonce->marque->__toString(), '<br>';
            echo 'modele : ', $annonce->modele->__toString(), '<br>';
            echo 'longueur : ', $annonce->longueur->__toString(), '<br>';
            echo 'largeur : ', $annonce->largeur->__toString(), '<br>';
            echo 'annee : ', $annonce->annee->__toString(), '<br>';
            echo 'visible : ', $annonce->visible->__toString(), '<br>';
            echo 'port_depart : ', $annonce->port_depart->__toString(), '<br>';
            echo 'moteur_nb : ', $annonce->moteur_nb->__toString(), '<br>';
            echo 'moteur_marque : ', $annonce->moteur_marque->__toString(), '<br>';
            echo 'moteur_propulsion : ', $annonce->moteur_propulsion->__toString(), '<br>';
            echo 'moteur_nbheure : ', $annonce->moteur_nbheure->__toString(), '<br>';
            echo 'moteur_puissance : ', $annonce->moteur_puissance->__toString(), '<br>';
            echo 'moteur_info : ', $annonce->moteur_info->__toString(), '<br>';
            echo 'prix : ', $annonce->prix->__toString(), '<br>';
            $commentaires = $annonce->commentaires->__toString();
            $commentaires = preg_replace('/\./', '. ', $commentaires);
            $commentaires = preg_replace('/\s+/', ' ', $commentaires);

            echo 'commentaires : ', $commentaires, '<br>';
            //$apiKey = 'AIzaSyBlrZdu97jNP0TZAScOo4Dx2Yre-Cwgznc';
            //$source = 'es';
            //$target = 'en';
            //echo '<br>Translate :<br>' ;
            //echo Translate($commentaires, $source, $target, $apiKey);

            if(count($annonce->photo) > 0) {
                foreach ($annonce->photo as $photo) {
                    echo 'photo : ', $photo, '<br>';
                }
            }
            echo '<hr>', '<br>';
        }*/

        // new ads
        $ads = [];
        foreach ($annonces->annonce as $annonce) {
            $ad_id = $annonce->id->__toString();
            $ads[] = $ad_id . '|' . convert_to_slug($gateaway, '_') . '_' . $ad_id;
        }

        $result = [];
        try {
            $pdo = new PDO($pdo_conf, $login, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $tblName = 'scrapping_ads_details';

            $head .= '<div class="col-sm-4">' . "\n";
            $head .= '<strong>Ad\'s from exsiting ' . $tblName . '</strong>: ';

            $sql = "SELECT ad_ref from $tblName WHERE ad_referrer = :ad_referrer";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([":ad_referrer" => $gateaway]);
            $result = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
            if (!empty($result)) {
                $head .= count($result);
            }
            $head .= '</div>' . "\n";
        } catch (PDOException $e) {
            $pdoErrors = '<h4>PDOException Connect</h4><p class="text-danger">' . $e->getMessage() . '</p>' . "\n";
            $pdoErrors = 'p class="col-sm-12 alert alert-danger">' . $pdoErrors . '</p>' . "\n";
            $head .= '<div class="col-sm-4">' . "\n";
            $head .= $pdoErrors;
            $head .= '</div>' . "\n";
        }

        $diff=array_diff($result,$ads);

        $head .= '<div class="col-sm-4">' . "\n";
        $head .= '<strong>Diff ads: </strong>';
        $head .= count($diff);
        $head .= '</div>' . "\n";

        if(!empty($diff)) {

            $head .= '<div class="col-sm-12">' . "\n";
            $head .= '<hr>' . "\n";
            foreach($diff as $id => $ad_ref) {
                $sql = "UPDATE $tblName SET ad_sales_status = 'removed', updated_at = NOW() WHERE ad_ref = :ad_ref";
                $stmt = $pdo->prepare($sql);
                try {
                    $pdo->beginTransaction();
                    // Exécute la requête avec variables "protégées"
                    //$result = $stmt->execute( [":ad_pageUrl" => $link] );
                    $result = $stmt->execute( [":ad_ref" => $ad_ref] );

                    if(!$result) {
                        $head .= displayErrors($stmt->errorInfo() . '<br>' . $ad_ref . 'PDO Errors');
                    } else {
                        $head .= '[' . $ad_ref . '] ' . "\n";
                        $disabled_links .= '[' . $ad_ref . '] ' . '<br>' . "\n";
                    }
                    $pdo->commit();
                } catch (PDOException $e){
                    $pdoErrors = '<h4>PDOException UPDATE Disabled</h4><p class="text-danger">' . $e->getMessage() . '<br>' . $ad_ref . '</p>';
                    $pdoErrors = '<div class="col-sm-12 alert alert-danger">' . $pdoErrors . '</div>';
                    $head .= $pdoErrors;
                }
            }
            $head .= '</div>' . "\n";
        }

        $head .= '</div></div>' . "\n";

        echo $head;
    }
?>
    <!--    // 01 : www.boatshop24.co.uk_pages_details_processing-->
    <div class="well">
        <div class="row">
            <?php
            if(!empty($total_results)) {
            ?>
                <div class="col-sm-12">
                    <h3>[01] <?php echo $gateaway; ?> Pages Details Processing</h3>
                </div>

                <div class="col-sm-12">
                    <div class="progress">
                        <h4 class="lead title">[01-01] Processing (local) Pages Details Datas</h4>
                        <div id="progress_bar_01_pages_details_datas" class="" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%"></div>
                    </div>
                </div>

                <script>
                    var $ProgressBar_pages_details_datas = $("#progress_bar_01_pages_details_datas");
                    var $ProgressBar_pages_details_datas_parent = $ProgressBar_pages_details_datas.parent(".progress");
                    var $ProgressBarTitle_pages_details_datas = $ProgressBar_pages_details_datas_parent.find(".title");
                </script>

                <div class="col-sm-12">
                    <div class="progress">
                        <h4 class="lead title">[01-02] Extracting (local) Pages Details Datas</h4>
                        <div id="progress_bar_01_extracting_pages_details_datas" class="" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%"></div>
                    </div>
                </div>

                <script>
                    var $ProgressBar_extracting_pages_details_datas = $("#progress_bar_01_extracting_pages_details_datas");
                    var $ProgressBar_extracting_pages_details_datas_parent = $ProgressBar_extracting_pages_details_datas.parent(".progress");
                    var $ProgressBarTitle_extracting_pages_details_datas = $ProgressBar_extracting_pages_details_datas_parent.find(".title");
                </script>

                <?php
                $counter_processing_pages_details_datas = 0;
                $timeout = 30;
                //$ads = [];
                $currentAd = [];

                $errors = [];
                //$sqlImport = '';

                $empty_links = $error_links = $expired_links = $success_links = /*$disabled_links =*/ '';
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

                    // grab local files
                    $z = 0;
                    $countEmptyPage = 0;
                    $countErrorLinks = 0;
                    $countExpiredLinks = 0;
                    $countAdsUpdated = 0;
                    $countAdsDisabled = 0;

                    //$xml = simplexml_load_string($xml_content);

                    foreach ($annonces->annonce as $annonce) {
                        $z ++;
                        if (!empty($annonce)) {
                            $ad_id = $annonce->id->__toString();
                            $ad_status =  isset($annonce->status) ? $annonce->status : '';
                            $ad_ref = $ad_id . '|' . convert_to_slug($gateaway, '_') . '_' . $ad_id;
                            //echo '<pre>';var_dump($ad_ref);echo '</pre>';

                            $type = $annonce->type->__toString();
                            if(preg_match('/location/i', $type)) {
                                $url_prefix_id = '';
                            }

                            $manufacturer = $annonce->marque->__toString();
                            if(!empty($manufacturer)) {
                                $manufacturer = ucwords(mb_strtolower(preg_replace('/\s+/', ' ', $manufacturer)));
                                $manufacturer_url = convert_to_slug($manufacturer, '-') . '-';
                            } else {
                                //$manufacturer_url = 'manufacturer' . '-';
                                $manufacturer_url = '';
                            }

                            $model = $annonce->modele->__toString();
                            if(!empty($model)) {
                                $model = ucwords(mb_strtolower(preg_replace('/\s+/', ' ', $model)));
                                $model_url = convert_to_slug($model, '-') . '-';
                            } else {
                                //$model_url = 'model' . '-';
                                $model_url = '';
                            }

                            //$link = 'http://' . $dealer_domain . '/' . $type . '-' . $manufacturer_url . $model_url . $url_prefix_id . '-' . $ad_id;
                            $link = 'http://' . $dealer_domain . '/' . $type . '/' . $manufacturer_url . $model_url . '/' . $ad_id;
                            $link = preg_replace('/--/', '-', $link);
                            $link = preg_replace('/-\//', '/', $link);

                            $need2update = TRUE;
                            $need2disable = FALSE;
                            $date = date("Y-m-d");
                            $currentDate = date("Y-m-d H:i:s");

                            if(!empty($ad_ref)) {

                                //expired
                                if (!empty($ad_status) && $ad_status == 'expired') {
                                    $expired_links .= '[' . $z . '] - ' . $link . ' | ' . $ad_ref . '<br>' . "\n";
                                    $need2update = FALSE;
                                    $need2disable = TRUE;
                                    $countExpiredLinks++;
                                }

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

                            if($need2disable) {
                                //$sql = "UPDATE $tblName SET ad_sales_status = 'removed', updated_at = NOW() WHERE ad_pageUrl = :ad_pageUrl";
                                $sql = "UPDATE $tblName SET ad_sales_status = 'removed', updated_at = NOW() WHERE ad_ref = :ad_ref";
                                $stmt = $pdo->prepare($sql);

                                try {
                                    $pdo->beginTransaction();
                                    // Exécute la requête avec variables "protégées"
                                    //$result = $stmt->execute( [":ad_pageUrl" => $link] );
                                    $result = $stmt->execute( [":ad_ref" => $ad_ref] );

                                    if(!$result) {
                                        echo displayErrors($stmt->errorInfo() . '<br>' . $link . ' | ' . $ad_ref . 'PDO Errors');
                                    } else {
                                        $disabled_links .= '[' . $z . '] - ' . $link . ' | ' . $ad_ref . '<br>' . "\n";
                                        $countAdsDisabled ++;
                                    }
                                    $pdo->commit();
                                } catch (PDOException $e){
                                    $pdoErrors = '<h4>PDOException UPDATE Disabled</h4><p class="text-danger">' . $e->getMessage() . '<br>' . $link . ' | ' . $ad_ref . '</p>';
                                    $pdoErrors = '<div class="col-sm-12 alert alert-danger">' . $pdoErrors . '</div>';
                                    echo $pdoErrors;
                                }
                            } elseif($need2update) {
                                $processing[] = ['id'=>'ad_ref', 'value'=>$ad_ref];
                                $processing[] = ['id'=>'ad_title', 'value'=>trim($manufacturer . ' ' . $model)];
                                $processing[] = ['id'=>'ad_country_code', 'value'=>$dealer_country_contract_code];
                                $processing[] = ['id'=>'ad_location', 'value'=>$dealer_country];
                                //$processing[] = ['id'=>'ad_mooring_country', 'value'=>$ad_location];
                                $processing[] = ['id'=>'ad_phones', 'value'=>$dealer_phone];
                                $processing[] = ['id'=>'ad_referrer', 'value'=>$gateaway];
                                $processing[] = ['id'=>'ad_dealer_name', 'value'=>$dealer_name];
                                $processing[] = ['id'=>'ad_dealer_url', 'value'=>'http://' . $dealer_domain];

                                $processing[] = ['id'=>'ad_pageUrl', 'value'=>$link];

                                $processing[] = ['id'=>'created_at', 'value'=>$currentDate];
                                $processing[] = ['id'=>'updated_at', 'value'=>$currentDate];
                                $processing[] = ['id'=>'deleted_at', 'value'=>NULL];

                                ////$processing[] = ['id'=>'start_date', 'value'=>'0000-00-00 00:00:00'];
                                ////$processing[] = ['id'=>'end_date', 'value'=>'0000-00-00 00:00:00'];
                                ////$processing[] = ['id'=>'status', 'value'=>'active'];
                                $processing[] = ['id'=>'ad_sales_status', 'value'=>'active'];

                                $processing = array_merge($processingXpath, $processing);

                                $photos = '';
                                foreach ($processing as $process) {

                                    if('ad_photos' != $process['id'] && !empty($photos)) {
                                        $currentAd['ad_photos']  = $photos;
                                        $currentAd['ad_photo']  = explode(';', $photos)[0];
                                        $photos = '';
                                    }

                                    if(array_key_exists('value', $process)) {
                                        //$ads[$ad_id][$process['id']] = $process['value'];
                                        $currentAd[$process['id']] = $process['value'];
                                    } else if(array_key_exists('xpath', $process)) {
                                        $value = '';
                                        $delimiter = '';
                                        if (!is_null($elements = $annonce[0]->xpath($process['xpath']))) {
                                            if (false !== $elements && ($elements instanceof Traversable || is_array($elements) || is_object($elements))) {
                                                foreach ($elements as $element) {
                                                    if (!empty($val = $element->__toString())) {
                                                        switch ($process['id']) {
                                                            case 'ad_location' :
                                                                $val = str_replace(['N/A'], [''], $val);
                                                                //$val .= !empty($val) ? ', ' . $dealer_country : $dealer_country;
                                                                $delimiter = '';
                                                                if(empty($val)) {
                                                                    $val = $dealer_location;
                                                                }
                                                                break;
                                                            case 'ad_mooring_country' :
                                                                $val = str_replace(['N/A'], [''], $val);
                                                                $delimiter = '';
                                                                if(empty($val)) {
                                                                    $val = $dealer_mooring_country;
                                                                }
                                                                break;
                                                            case 'ad_photo' :
                                                                $val = preg_replace('/^\//', 'http://' . $dealer_domain . '/', $val);
                                                                $delimiter = '';
                                                                break;
                                                            case 'ad_photos' :
                                                            case 'ad_photos_thumbs' :
                                                                $val = preg_replace('/^\//', 'http://' . $dealer_domain . '/', $val);
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
                                                            /*case 'ad_ref':
                                                                $val = preg_replace('/^ad ref. /i', '', $val);
                                                                $val = preg_replace('/^annonce ref. /i', '', $val);
                                                                $delimiter = '';
                                                                break;*/
                                                        }
                                                        $value = $val . $delimiter;
                                                    }

                                                    /*if(preg_match('/ad_mooring_country/', $process['id'])) {
                                                        $value = str_replace(['N/A'], [''], $value);
                                                        if(empty($value)) {
                                                            $value = $dealer_mooring_country;
                                                        }
                                                        //$currentAd['ad_location'] = $value;
                                                    }*/

                                                    //$ads[$ad_id][$process['id']] = $value;
                                                    $currentAd[$process['id']] = $value;

                                                    if(preg_match('/ad_photos/', $process['id'])) {
                                                        $photos .= $value;
                                                    }

                                                    // set clean value for _name
                                                    if(preg_match('/_name/', $process['id']) || preg_match('/_title/', $process['id']) ) {
                                                        $val  = $currentAd[$process['id']];
                                                        $val = str_replace([',', ' ,'], [' ', ' '], $val);
                                                        $val = preg_replace('/\s+/', ' ', $val);
                                                        $val = mb_strtolower($val);
                                                        $val = ucwords($val);
                                                        $currentAd[$process['id']] = $val;
                                                        //$ads[$ad_id][$process['id']] = $val;
                                                        unset($val);
                                                    }

                                                    // set clean value for _name
                                                    if(preg_match('/_url/', $process['id'])) {
                                                        $val  = $currentAd[$process['id']];
                                                        $val = convert_to_slug(preg_replace('/moteur/i', 'bateau-moteur', $val)) . '/';
                                                        $val = ltrim($val, '/');
                                                        $val = rtrim($val, '/');
                                                        $val = '/' . $val;
                                                        $currentAd[$process['id']] = $val;
                                                        //$ads[$ad_id][$process['id']] = $val;
                                                        unset($val);
                                                    }

                                                    // set clean value for ad_type_cat_name
                                                    if(preg_match('/ad_type_cat_name/', $process['id'])) {
                                                        $val  = $currentAd[$process['id']];
                                                        $val = preg_replace('/moteur/i', 'bateau-moteur', $val);
                                                        $val = convert_to_slug($val, ' ');
                                                        $val = ucwords($val);
                                                        $currentAd[$process['id']] = $val;
                                                        //$ads[$ad_id][$process['id']] = $val;
                                                        unset($val);
                                                    }

                                                    // clean description _full to reduce size of datas
                                                    if(preg_match('/_full/', $process['id'])) {
                                                        $full = $currentAd[$process['id']];

                                                        $block2removeProcessId = preg_replace('/_full/', '_caracts_block', $process['id']);
                                                        $block2remove = '';
                                                        if(array_key_exists($block2removeProcessId, $currentAd)) {
                                                            $block2remove = $currentAd[$block2removeProcessId];
                                                        }

                                                        if(!isset($block2remove) || empty($block2remove)) {
                                                            $full = str_replace($block2remove, '', $full);
                                                        }
                                                        $full = preg_replace('/\./', '. ', $full);
                                                        $full = preg_replace('/\s+/', ' ', $full);
                                                        $currentAd[$process['id']] = $full;
                                                        //$ads[$ad_id][$process['id']] = $full;

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
                                                        //$ads[$ad_id][$process['id']] = preg_replace('/\s+/', ' ', $val);
                                                        unset($val);
                                                    }

                                                    // set _name with _atitle if _name is empty
                                                    if(preg_match('/_atitle/', $process['id'])) {
                                                        $atitle  = $currentAd[$process['id']];
                                                        $nameToCheckProcessId = preg_replace('/_atitle/', '_name', $process['id']);
                                                        $nameToCheck = '';
                                                        if(array_key_exists($nameToCheckProcessId, $currentAd)) {
                                                            $nameToCheck = $currentAd[$nameToCheckProcessId];
                                                        }
                                                        if(!isset($nameToCheck) || empty($nameToCheck)) {
                                                            $atitle = str_replace([',', ' ,'], [' ', ' '], $atitle);
                                                            $atitle = preg_replace('/\s+/', ' ', $atitle);
                                                            $currentAd[$nameToCheckProcessId] = $atitle;
                                                            //$ads[$ad_id][$nameToCheckProcessId] = $atitle;
                                                        }
                                                        unset($currentAd[$process['id']], $atitle, $nameToCheck, $nameToCheckProcessId);
                                                    }

                                                    if(preg_match('/ad_manufacturer_name/', $process['id']) ) {
                                                        if(empty($currentAd[$process['id']])) {
                                                            $value = 'manufacturer';
                                                        }
                                                    }
                                                }
                                                unset($elements);
                                            }
                                        }
                                    }
                                }
                                unset($processing);

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

                                //echo '<pre>';
                                //var_dump(array_values($currentAd));
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
                                        echo displayErrors($stmt->errorInfo() . '<br>' . $link . ' | ' . $ad_ref . 'PDO Errors');
                                    } else {
                                        $script = '<script>' . "\n";
                                        //$script .= '$ProgressBar_extracting_pages_details_datas_parent.show();' . "\n";
                                        $script .= '$ProgressBarTitle_extracting_pages_details_datas.find(".infos").remove();' . "\n";
                                        $script .= '$ProgressBarTitle_extracting_pages_details_datas.append("<span class=\'infos\'> (' . $counter_processing_pages_details_datas . ' / ' . $total_results . ') [' . $link . ']</span>");' . "\n";
                                        $script .= 'progressBar($ProgressBar_extracting_pages_details_datas, ' . ceil(($counter_processing_pages_details_datas * 100) / $total_results) . ');' . "\n";
                                        $script .= '</script>' . "\n";
                                        echo $script;
                                        //$counter_processing_pages_details_datas++;

                                        $success_links .= '[' . $z . '] - ' . $link . ' | ' . $ad_ref . '<br>' . "\n";
                                        $countAdsUpdated ++;
                                    }
                                    $pdo->commit();
                                } catch (PDOException $e){
                                    $pdoErrors = '<h4>PDOException INSERT or UPDATE</h4><p class="text-danger">' . $e->getMessage() . '<br>' . $link . ' | ' . $ad_ref . '</p>';
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
    unset($html,$script,$link,$ad_id,$sUrlSrc,$counter_processing_pages_details_datas,$processing,$process,$errors,$key,$error,$k,$err);
    ?>

    <?php
    if(2 == $step && !empty($scrappingInfoFile)) {
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