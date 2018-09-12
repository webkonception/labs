<?php
    header('Content-type: text/xml');

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
        } elseif ($showContent){
            return $output;
        }
    }
    function camelize($value, $lcfirst = true) {
        $value = preg_replace("/([_-\s]?([a-z0-9]+))/e", "ucwords('\\2')", $value);
        return ($lcfirst ? strtolower($value[0]) : strtoupper($value[0]))
        + substr($value, 1);
    }

    function array2xml($array, $xml = false) {
    //echo '<pre>'; var_dump($array);echo '</pre>';

        if ($xml === false) {
            $xml = new SimpleXMLElement('<root/>');
        }
        foreach ($array as $key => $value) {
            //var_dump("key $key");
            //$key = preg_replace('/\/_/','_',$key);
            if(!preg_match('/\/_/', $key)) {
                if (is_numeric($key)) {
                    $key = 'item';
                }
                if (is_array($value)) {
                    array2xml($value, $xml->addChild($key));
                } else {
                    $xml->addChild($key, $value);
                }
            }
        }
        return $xml->asXML();
    }

    if (isset($_POST['api_full_url']) || isset($_GET['api_full_url'])) {
        $api_full_url   = $_POST['api_full_url'] ?: $_GET['api_full_url'] ?: '';
    } else {

        // ====================
        $apiKey = '7970b3d557714c728e28ca833612bf18771971a0083d0a8b21f37031fa36570f880bfc8a75535f1afc68bee75e83676905e0357395bdbb2e202083662079895da2430b4e9e019a5b826059ada6bbb4cb';

        /////////////////////////////////
        /*
        $name = '0_boatshop24_co_uk-ads_total_results_pages';
        $connector = '3091ce5a-5fc8-435c-ae24-c7473c02fcf2';
        $url = 'http://www.boatshop24.co.uk/boats-for-sale?order=latest&list_type=table&page=1&url=maxdim';
        //https://import.io/data/mine/?tag=EXTRACTOR&id=3091ce5a-5fc8-435c-ae24-c7473c02fcf2
        $api_full_url ='https://api.import.io/store/connector/' . $connector . '/_query?input=webpage/url:' . urlencode($url) .'&_apikey=' . $apiKey;
        */
        ///////////////////////////////////
        $name = '1_boatshop24_co_uk-ads_links';
        $connector = 'fb098754-e9b8-45a8-9215-7f954ef8d08e';
        $url = 'http://www.boatshop24.co.uk/boats-for-sale?order=latest&list_type=table&page=1&url=maxdim';
        //https://import.io/data/mine/?tag=EXTRACTOR&id=fb098754-e9b8-45a8-9215-7f954ef8d08e
        //**$api_full_url = 'https://api.import.io/store/connector/' . $connector . '/_query?input=webpage/url:' . urlencode($url) . '&_apikey=' . $apiKey;

        //////////////////////////////////////
        /*
        $name = '2_boatshop24_co_uk-ads_details';
        $connector = '00992084-552b-486b-803e-98969b240c8d';
        $url = 'http://www.boatshop24.co.uk/cabin-cruiser/beneteau-antares-30/106208';
        //https://import.io/data/mine/?tag=EXTRACTOR&id=00992084-552b-486b-803e-98969b240c8d
        //full
        $api_full_url ='https://api.import.io/store/connector/' . $connector . '/_query?input=webpage/url:' . urlencode($url) . '&_apikey=' . $apiKey;
        */
        //////////////////////////////////////
        $name = '2_boatshop24_co_uk-ads_details_light';
        $connector = '342f858a-f692-4461-b89e-44c1084f23b5';
        $url = 'http://www.boatshop24.co.uk/cabin-cruiser/beneteau-antares-30/106208';
        //https://import.io/data/mine/?tag=EXTRACTOR&id=342f858a-f692-4461-b89e-44c1084f23b5
        //**$api_full_url = 'https://api.import.io/store/connector/' . $connector . '/_query?input=webpage/url:' . urlencode($url) . '&_apikey=' . $apiKey;
        //////////////////////////////////////
        /*
        $name = '2_boatshop24_co_uk-ads_print_details';
        $connector = 'e9a7d7d5-86e6-45c3-b18f-983930e3e629';
        $url = 'http://www.boatshop24.co.uk/cabin-cruiser/beneteau-antares-30/106208';
        //https://import.io/data/mine/?tag=EXTRACTOR&id=e9a7d7d5-86e6-45c3-b18f-983930e3e629
        $api_full_url ='https://api.import.io/store/connector/' . $connector . '/_query?input=webpage/url:' . urlencode($url) .'&_apikey=' . $apiKey;
        */
        ////////////////////////////////////////////
        /*
        $name = '2_boatshop24_co_uk-ads_print_details_light';
        $connector = '11425a16-f6c1-4be5-a628-5c323b656dd2';
        $url = 'http://www.boatshop24.co.uk/cabin-cruiser/beneteau-antares-30/106208';
        //https://import.io/data/mine/?tag=EXTRACTOR&id=11425a16-f6c1-4be5-a628-5c323b656dd2
        $api_full_url ='https://api.import.io/store/connector/' . $connector . '/_query?input=webpage/url:' . urlencode($url) . '&_apikey=' . $apiKey;
        */

        //#######################################
        // dash.import.io
        //#######################################
        /////////////////////////////////
        /*
        $name = '2_boatshop24.co.uk_ads_details';
        $extractor = '65b6b991-1a9c-4de8-be49-00cd57a759e7';
        $url = 'http://www.boatshop24.co.uk/cabin-cruiser/beneteau-antares-30/106208';
        //https://dash.import.io/65b6b991-1a9c-4de8-be49-00cd57a759e7/settings
        $api_full_url ='https://extraction.import.io/query/extractor/65b6b991-1a9c-4de8-be49-00cd57a759e7?_apikey=' . $apiKey .'&url=' . urlencode($url);
        */
        //////////////////////////////////////
        /*
        $name = '2_boatshop24.co.uk_ads_print_details';
        $extractor = '93cf2712-bee4-43ad-8b07-e5ce8fe01fc9';
        $url = 'http://www.boatshop24.co.uk/cabin-cruiser/beneteau-antares-30/106208';
        //https://dash.import.io/93cf2712-bee4-43ad-8b07-e5ce8fe01fc9/integrate
        $api_full_url ='https://extraction.import.io/query/extractor/' . $extractor .'?_apikey=' . $apiKey .'&url=' . urlencode($url);
        */
        //#######################################
        //#######################################
    }
    //$api_full_url ='https://api.import.io/store/connector/00992084-552b-486b-803e-98969b240c8d/_query?input=webpage/url:http%3A%2F%2Fwww.boatshop24.co.uk%2Fsailboat%2Fwesterly-konsort%2F107696&_apikey=7970b3d557714c728e28ca833612bf18771971a0083d0a8b21f37031fa36570f880bfc8a75535f1afc68bee75e83676905e0357395bdbb2e202083662079895da2430b4e9e019a5b826059ada6bbb4cb';

    if (isset($api_full_url)) {

        //$json_datas = file_get_contents($api_full_url);
        $bridge = $_POST['bridge'] ?: $_GET['bridge'] ?: 'www.boatshop24.co.uk';
        $showContent = true;
        $timeout = 20;

        $json_datas = fileGetContentsCurl($api_full_url, $showContent, $timeout, $bridge);
        //$json_datas = file_get_contents($api_full_url);

        if (isset($json_datas)) {
            $json_array_datas = json_decode($json_datas, true);

            // ====================
            /*
            echo "<pre>";
            print_r($json_array_datas);
            echo "</pre>";
            */
            // ====================

            $tmp_labels_array = [];
            $tmp_values_array = [];

            $tmp_caracts_values_array = [];
            $tmp_caracts_categories = '';

            if (array_key_exists('extractorData', $json_array_datas)) {
                $jsonArray = $json_array_datas['extractorData']['data'][0]['group'];
            } else {
                if (count($json_array_datas['results']) == 1) {
                    $jsonArray = $json_array_datas['results'][0];
                } elseif (count($json_array_datas['results']) > 1) {
                    $jsonArray = $json_array_datas['results'];
                }
            }
            if ($jsonArray && count($jsonArray) > 0) {

                $jsonArray['api_name'] = $name;

                foreach ($jsonArray as $key => $value) {
                    $pattern = '/_categories/';
                    if (preg_match($pattern, $key)) {
                        $tmp_key = preg_replace($pattern, '', $key);
                        $tmp_array = $jsonArray[$tmp_key];
                        foreach ($tmp_array as $_key => $_value) {
                            if (in_array($_value, $jsonArray[$key])) {
                                $tmp_caracts_categories = preg_replace('/\s+/', '_', strtolower($_value));
                                $tmp_caracts_values_array[$tmp_key][$tmp_caracts_categories] = [];
                                //array_push($tmp_caracts_values_array[$tmp_key][$tmp_caracts_categories], ['title' => $_value]);
                                array_push($tmp_caracts_values_array[$tmp_key][$tmp_caracts_categories], ['title' => preg_replace('/:$/', '', $_value)]);;
                            } else {
                                array_push($tmp_caracts_values_array[$tmp_key][$tmp_caracts_categories], $_value);
                            }
                        }
                        $jsonArray[$tmp_key] = $tmp_caracts_values_array[$tmp_key];
                        unset($jsonArray[$key]);
                    }
                    $pattern = '/_labels/';
                    if (preg_match($pattern, $key)) {
                        $tmp_key = preg_replace($pattern, '', $key);
                        $jsonArray[$tmp_key] = [];
                        //$tmp_labels_array[$tmp_key] = $value;
                        $tmp_labels_array[$tmp_key] = preg_replace('/:$/', '', $value);
                        unset($jsonArray[$key]);
                    }
                    $pattern = '/_values/';
                    if (preg_match($pattern, $key)) {
                        $tmp_key = preg_replace($pattern, '', $key);
                        $tmp_values_array[$tmp_key] = $value;
                        unset($jsonArray[$key]);
                    }
                }

                foreach ($tmp_values_array[$tmp_key] as $key => $value) {
                    array_push($jsonArray[$tmp_key], [
                        'label' => $tmp_labels_array[$tmp_key][$key],
                        'value' => $tmp_values_array[$tmp_key][$key]
                    ]);
                }

                // ====================
                /*
                echo "<pre>";
                print_r($jsonArray);
                echo "</pre>";
                */
                // ====================
            } else {
                $jsonArray['error'] = 'NO RESULTS';
            }
        } else {
            $jsonArray['error'] = 'NO JSON DATAS';
        }
    } else {
        $jsonArray['error'] = "NULL";
    }
    print array2xml($jsonArray);
