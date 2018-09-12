<?php
  header('Content-Type:application/json;charset=utf-8');

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
      return 'NO JSON DATAS';
    } else if (empty($output)) {
      mail("emmanuel.deiller.rivamedia@gmail.com","[" . $bridge . "] No matches were found at this URL => ", $targetUrl . "\n\n");
      return 'NO RESULTS';
    } elseif ($showContent){
      return $output;
    }
  }

  //$length = 10;
  //$crypto_strong = true;
  //$token = bin2hex(openssl_random_pseudo_bytes($length, $crypto_strong));

  $api_url      = isset($_POST['api_url']) ? $_POST['api_url'] : isset($_GET['api_url']) ? $_GET['api_url'] : 'https://extraction.import.io/query/extractor/';
  $api_name     = isset($_POST['api_name']) ? $_POST['api_name'] : isset($_GET['api_name']) ? $_GET['api_name'] : 'a105bd82-206b-473f-b6cc-63f4900fdc0c';
  $apikey       = isset($_POST['api_key']) ? $_POST['api_key']: isset($_GET['api_key']) ? $_GET['api_key'] : '7970b3d557714c728e28ca833612bf18771971a0083d0a8b21f37031fa36570f880bfc8a75535f1afc68bee75e83676905e0357395bdbb2e202083662079895da2430b4e9e019a5b826059ada6bbb4cb';
  $scrapping_url            = $_POST['scrapping_url'] ?: isset($_GET['scrapping_url']) ? $_GET['scrapping_url'] : 'http://www.boatshop24.co.uk/boats-for-sale?list_type=table&order=newest&page=1';
  //$api_full_url = 'https://extraction.import.io/query/extractor/a105bd82-206b-473f-b6cc-63f4900fdc0c?_apikey=7970b3d557714c728e28ca833612bf18771971a0083d0a8b21f37031fa36570f880bfc8a75535f1afc68bee75e83676905e0357395bdbb2e202083662079895da2430b4e9e019a5b826059ada6bbb4cb&url=http%3A%2F%2Fwww.boatshop24.co.uk%2Fboats-for-sale%3Flist_type%3Dtable%26page%3D1%26order%3Dnewest;
  $api_full_url   = isset($_POST['api_full_url']) ? isset($_POST['api_full_url']) : $_GET['api_full_url'] ? $_GET['api_full_url'] : $api_url . $api_name . '?_apikey=' . $apikey . '&url='. urlencode($scrapping_url);

  $bridge 		  = isset($_POST['bridge']) ? $_POST['bridge'] : isset($_GET['bridge']) ? $_GET['bridge'] : 'www.boatshop24.co.uk';
  $showContent 	  = true;
  $timeout        = 20;

  $content = fileGetContentsCurl($api_full_url, $showContent, $timeout, $bridge);
  echo $content;
