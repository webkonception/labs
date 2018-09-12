<?php
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

  $nb_links_page = 0;
  $scrapping = [];

  // 0_boatshop24_co_uk-ad_total_results_pages
  echo "===========================================================" . "<br>";
  echo "== 0_boatshop24_co_uk-ad_total_results_pages" . "<br>";
  echo "===========================================================" . "<br>";
  //https://import.io/data/mine/?tag=EXTRACTOR&id=3091ce5a-5fc8-435c-ae24-c7473c02fcf2
  //https://api.import.io/store/connector/3091ce5a-5fc8-435c-ae24-c7473c02fcf2/_query?input=webpage/url:http%3A%2F%2Fwww.boatshop24.co.uk%2Fboats-for-sale%3Forder%3Dlatest%26list_type%3Dtable%26page%3D1%26url%3Dmaxdim&&_apikey=7970b3d557714c728e28ca833612bf18771971a0083d0a8b21f37031fa36570f880bfc8a75535f1afc68bee75e83676905e0357395bdbb2e202083662079895da2430b4e9e019a5b826059ada6bbb4cb
  $api_url        = 'https://api.import.io/store/connector/';
  $api_name       = '3091ce5a-5fc8-435c-ae24-c7473c02fcf2';
  $api_key        = '7970b3d557714c728e28ca833612bf18771971a0083d0a8b21f37031fa36570f880bfc8a75535f1afc68bee75e83676905e0357395bdbb2e202083662079895da2430b4e9e019a5b826059ada6bbb4cb';
  $scrapping_url  = 'http://www.boatshop24.co.uk/boats-for-sale?order=latest&list_type=table&page=1';

  $api_full_url   = $api_url . $api_name . '/_query?input=webpage/url:' . urlencode($scrapping_url) . '&_apikey=' . $api_key;

  $bridge         = 'www.boatshop24.co.uk';
  $url            = 'http://' . $_SERVER["SERVER_NAME"] .'/scrapping/scrapping_api.php?api_full_url=' . urlencode($api_full_url) . '&bridge=' . $bridge;

  $json_datas           = file_get_contents($url);
  //$json_datas           = file_get_contents($api_full_url);
  $json_array_datas     = json_decode($json_datas);
  //$json_array_datas     = json_decode($json_datas,true);

  if ($json_array_datas->errorType && count($json_array_datas->errorType) > 0) {
    $errorType      = $json_array_datas->errorType;
    $array_errors[$errorType][] = $api_full_url;
    echo '<div style="color:#A00">';

    echo "<br>## scrapping_url > " . $scrapping_url . "<br>";
    echo "<br>## api_full_url > " . $api_full_url . "<br>";
    echo "<br>## url > " . $url . "<br>";

    echo '<br>';
    echo '!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!<br>';
    echo '!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!<br>';
    echo '<pre>';var_dump($errorType);echo '</pre>';
    echo '<pre>';var_dump($json_array_datas->error);echo '</pre>';
    echo '!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!<br>';
    echo '!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!<br>';
    echo '</div>';
    echo '<a href="#" onclick="javascript:window.location.reload();return false;">Recharger la page</a>';
    //die();
  } elseif (count($json_array_datas->results) > 0) {
    //} elseif (count($json_array_datas['results']) > 0) {
    echo '<div style="color:#0A0">';
    echo "<br>## scrapping_url > " . $scrapping_url . "<br>";
    echo "<br>## api_full_url > " . $api_full_url . "<br>";
    echo "<br>## url > " . $url . "<br>";

    //echo "<br>## scrapping_url > " . $scrapping_url . "<br>";
    //echo "<br>## api_full_url > " . $api_full_url . "<br>";
    //echo "<br>## url > " . $url . "<br>";
    $results = $json_array_datas->results;
    //$results = $json_array_datas['results'];
    $ad_total_results = $results[0]->ad_total_results;
    //$ad_total_results = $results[0]['ad_total_results'];
    echo "<br>## ad_total_results > " . $ad_total_results . "<br>";
    $scrapping[$bridge]['ad_total_results'] = $ad_total_results;

    $ad_total_pages = $results[0]->ad_total_pages;
    //$ad_total_pages = $results[0]['ad_total_pages'];
    echo "<br>## ad_total_pages > " . $ad_total_pages . "<br>";
    echo '</div>';

    $scrapping[$bridge]['ad_total_pages'] = $ad_total_pages;

    $ad_total_pages = 1;

    if( $ad_total_pages > 0) {
      // 1_boatshop24_co_uk-ads_links
      echo "<br>===========================================================" . "<br>";
      echo "== 1_boatshop24_co_uk-ads_links" . "<br>";
      echo "===========================================================" . "<br>";
      //https://import.io/data/mine/?tag=EXTRACTOR&id=fb098754-e9b8-45a8-9215-7f954ef8d08e
      //https://api.import.io/store/connector/fb098754-e9b8-45a8-9215-7f954ef8d08e/_query?input=webpage/url:http%3A%2F%2Fwww.boatshop24.co.uk%2Fboats-for-sale%3Forder%3Dlatest%26list_type%3Dtable%26url%3Dmaxdim%26page%3D1&&_apikey=7970b3d557714c728e28ca833612bf18771971a0083d0a8b21f37031fa36570f880bfc8a75535f1afc68bee75e83676905e0357395bdbb2e202083662079895da2430b4e9e019a5b826059ada6bbb4cb
      $api_url        = 'https://api.import.io/store/connector/';
      $api_name       = 'fb098754-e9b8-45a8-9215-7f954ef8d08e';
      $api_key        = '7970b3d557714c728e28ca833612bf18771971a0083d0a8b21f37031fa36570f880bfc8a75535f1afc68bee75e83676905e0357395bdbb2e202083662079895da2430b4e9e019a5b826059ada6bbb4cb';

      $array_links = [];
      for ($i=1; $i <= $ad_total_pages; $i++) {
        $scrapping_url  = 'http://www.boatshop24.co.uk/boats-for-sale?order=latest&list_type=table&page=' . $i . '&url=maxdim';
        $api_full_url   = $api_url . $api_name . '/_query?input=webpage/url:' . urlencode($scrapping_url) . '&_apikey=' . $api_key;
        //echo "<br>## api_full_url > " . $api_full_url . "<br>";

        $bridge         = 'www.boatshop24.co.uk';
        $url            = 'http://' . $_SERVER["SERVER_NAME"] .'/scrapping/scrapping_api.php?api_full_url=' . urlencode($api_full_url) . '&bridge=' . $bridge;
        //echo "<br>## url > " . $url . "<br>";

        echo "<br>    ===========================================================" . "<br>";
        echo "    == 1-" . $i . "_boatshop24_co_uk-ads_links" ."<br>";
        echo "    == scrapping_url > " . $scrapping_url . "<br>";
        echo "    == api_full_url > " . $api_full_url . "<br>";
        echo "    == url > " . $url . "<br>";
        echo "    ===========================================================" . "<br>";

        $json_datas           = file_get_contents($url);
        //$json_datas           = file_get_contents($api_full_url);
        $json_array_datas     = json_decode($json_datas);

        if ($json_array_datas->errorType && count($json_array_datas->errorType) > 0) {
          $errorType = $json_array_datas->errorType;
          $array_errors[$errorType][] = $api_full_url;
          echo '<div style="color:#A00">';

          echo "<br>## scrapping_url > " . $scrapping_url . "<br>";
          echo "<br>## api_full_url > " . $api_full_url . "<br>";
          echo "<br>## url > " . $url . "<br>";

          echo '<br>';
          echo '!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!<br>';
          echo '!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!<br>';
          echo '<pre>';var_dump($errorType);echo '</pre>';
          echo '<pre>';var_dump($json_array_datas->error);echo '</pre>';
          echo '!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!<br>';
          echo '!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!<br>';
          echo '</div>';
          //echo '<a href="#" onclick="javascript:window.location.reload();return false;">Recharger la page</a>';
          //die();
        } elseif (count($json_array_datas->results) > 0) {

          echo '<div style="color:#0A0">';
          //echo "<br>## scrapping_url > " . $scrapping_url . "<br>";
          //echo "<br>## api_full_url > " . $api_full_url . "<br>";
          //echo "<br>## url > " . $url . "<br>";

          $results = $json_array_datas->results;
          //$results = $json_array_datas['results'];
          $ad_total_results = $results[0]->ad_total_results;
          foreach($results as $key => $result) {
            $ad_title = $result->ad_title;
            $ad_url = $result->ad_url;
            $array_links [] = ['title'=>$ad_title, 'url'=> $ad_url];
            echo "<br>>> " . $ad_title . " <=> " . $ad_url;
          }
          echo '</div>';
        } else {
          $array_errors['no_ads_links'][] = $api_full_url;
          echo '<div style="color:#A00">';

          echo "<br>## NO ADS LINKS <br>";
          echo "<br>## api_full_url > " . $api_full_url . "<br>";
          echo "<br>## url > " . $url . "<br>";
          echo '</div>';

        }
      }
      $scrapping[$bridge]['ads'] = $array_links;
      //echo '<pre>';var_dump($scrapping[$bridge]['ads']);echo '</pre>';
    } else {
      $array_errors['no_pages'][] = $api_full_url;
      echo '<div style="color:#A00">';
      echo 'NO PAGES<br>';
      echo '<pre>';var_dump($scrapping[$bridge]['ad_total_pages']);echo '</pre>';
      echo '</div>';
    }
    if (count($scrapping[$bridge]['ads']) > 0 ) {
      // 2_boatshop24_co_uk-ads_details
      echo "<br>===========================================================" . "<br>";
      echo "== 2_boatshop24_co_uk-ads_details" . "<br>";
      echo "===========================================================" . "<br>";
      //https://import.io/data/mine/?tag=EXTRACTOR&id=00992084-552b-486b-803e-98969b240c8d
      //https://api.import.io/store/connector/00992084-552b-486b-803e-98969b240c8d/_query?input=webpage/url:http%3A%2F%2Fwww.boatshop24.co.uk%2Fmotorboat%2Focean-master-oceanmaster-640-cabin%2F16026%23&&_apikey=7970b3d557714c728e28ca833612bf18771971a0083d0a8b21f37031fa36570f880bfc8a75535f1afc68bee75e83676905e0357395bdbb2e202083662079895da2430b4e9e019a5b826059ada6bbb4cb
      $api_url        = 'https://api.import.io/store/connector/';
      $api_name       = '00992084-552b-486b-803e-98969b240c8d';
      $api_key        = '7970b3d557714c728e28ca833612bf18771971a0083d0a8b21f37031fa36570f880bfc8a75535f1afc68bee75e83676905e0357395bdbb2e202083662079895da2430b4e9e019a5b826059ada6bbb4cb';

      // bis_2_boatshop24_co_uk-ads_details
      // https://api.import.io/store/connector/91a48cdc-8e23-4061-b315-df1215e57b65/_query?input=webpage/url:http%3A%2F%2Fwww.boatshop24.co.uk%2Fcanal-narrow-boats%2Fsea-otter-27%2F108695&&_apikey=7970b3d557714c728e28ca833612bf18771971a0083d0a8b21f37031fa36570f880bfc8a75535f1afc68bee75e83676905e0357395bdbb2e202083662079895da2430b4e9e019a5b826059ada6bbb4cb
      $api_name       = '91a48cdc-8e23-4061-b315-df1215e57b65';

      $array_details = [];
      $j = 0;

      $xml = true;
      foreach ($scrapping[$bridge]['ads'] as $key => $value) {
          $scrapping_url  = $scrapping[$bridge]['ads'][$key]["url"];
          $api_full_url   = $api_url . $api_name . '/_query?input=webpage/url:' . urlencode($scrapping_url) . '&_apikey=' . $api_key;
          //echo "<br>## api_full_url > " . $api_full_url . "<br>";

          $bridge         = 'www.boatshop24.co.uk';

          echo "<br>    ===========================================================" . "<br>";
          echo "    == 2-" . ($key+1) . "_boatshop24_co_uk-ads_details" . "<br>";
          echo "    == scrapping_url > " . $scrapping_url . "<br>";
          echo "    == api_full_url > " . $api_full_url . "<br>";
          echo "    == url > " . $url . "<br>";

        $ads_ref = end(explode('/', $scrapping_url));

          if($xml) {
            echo '<div style="color:#0A0">';

            //echo "<br>## scrapping_url > " . $scrapping_url . "<br>";
            //echo "<br>## api_full_url > " . $api_full_url . "<br>";
            //echo "<br>## url > " . $url . "<br>";

            $url            = 'http://' . $_SERVER["SERVER_NAME"] .'/scrapping/json-xml.php?api_full_url=' . urlencode($api_full_url) . '&bridge=' . $bridge;
            //echo "<br>## url > " . $url . "<br>";
            echo '<a target="_blank" href="' . $url .'">[XML] ' . $scrapping_url . '</a><br>';
            //$xml_datas      = file_get_contents($url);
            //echo $xml_datas;
            //$filename = '../xml/'. end(explode('/', $scrapping_url)) . '.xml';
            $filename = $_SERVER["DOCUMENT_ROOT"] . '/scrapping/xml/' . $ads_ref . '.xml';
            $xml_datas      = file_get_contents($url);
            if (preg_match('/NO RESULTS/', $xml_datas)) {
                $array_errors['no_results'][] = $ads_ref;
            } elseif (preg_match('/NO JSON DATAS/', $xml_datas)) {
                $array_errors['no_json_datas'][] = $ads_ref;
            }  elseif (preg_match('/NULL/', $xml_datas)) {
                $array_errors['null'][] = $ads_ref;
            } else {
              $fp = @fopen($filename,'w');
              if(!$fp) {
                echo('Error cannot create XML file');
              }
              fwrite($fp,$xml_datas);
              fclose($fp);
            }

            echo '</div>';
          } else {
            $url            = 'http://' . $_SERVER["SERVER_NAME"] .'/scrapping/scrapping_api.php?api_full_url=' . urlencode($api_full_url) . '&bridge=' . $bridge;
            //echo "<br>## url > " . $url . "<br>";
            $json_datas     = file_get_contents($url);
            var_dump($json_datas);
            //$json_datas     = file_get_contents($api_full_url);
            if (isset($json_datas)) {

              if (preg_match('/false/', $json_datas)) {
                $array_errors['null'][] = $ads_ref;
              }

              $json_array_datas     = json_decode($json_datas);
              if ($json_array_datas->errorType && count($json_array_datas->errorType) > 0) {
                $errorType = $json_array_datas->errorType;
                $array_errors[$errorType][] = $ads_ref;
                echo '<div style="color:#A00">';

                echo "<br>## scrapping_url > " . $scrapping_url . "<br>";
                echo "<br>## api_full_url > " . $api_full_url . "<br>";
                echo "<br>## url > " . $url . "<br>";

                echo '<br>';
                echo '!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!<br>';
                echo '!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!<br>';
                echo '<pre>';var_dump($errorType);echo '</pre>';
                echo '<pre>';var_dump($json_array_datas->error);echo '</pre>';
                echo '!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!<br>';
                echo '!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!<br>';
                echo '</div>';
                //echo '<a href="#" onclick="javascript:window.location.reload();return false;">Recharger la page</a>';
                //die();

              } elseif (count($json_array_datas->results) > 0) {
                echo '<div style="color:#0A0">';
                echo 'GO TO SAVE TO MYSQL !!!<br>';
                echo '<pre>';var_dump($json_datas);echo '</pre>';
                echo '</div>';
                //echo "<br>## scrapping_url > " . $scrapping_url . "<br>";
                //echo "<br>## api_full_url > " . $api_full_url . "<br>";
                //echo "<br>## url > " . $url . "<br>";

                $results = $json_array_datas->results;
                //echo '<pre>';var_dump($results);echo '</pre>';
                echo '</div>';
              }
            } else {
              if (preg_match('/NO JSON DATAS/', $json_datas)) {
                $array_errors['no_json_datas'][] = $ads_ref;
              }
              echo '<div style="color:#A00">';
              echo 'NO JSON DATAS<br>';
              echo "<br>## scrapping_url > " . $scrapping_url . "<br>";
              echo "<br>## api_full_url > " . $api_full_url . "<br>";
              echo "<br>## url > " . $url . "<br>";
              echo '<pre>';var_dump($json_datas);echo '</pre>';
              echo '</div>';
            }

          }
          echo "    ===========================================================" . "<br>";

          $j ++;
          //if ($j>1) break;
      }
    } else {
      $array_errors['no_links'][] = $api_full_url;
      echo '<div style="color:#A00">';
      echo 'NO LINKS<br>';
      echo '<pre>';var_dump($scrapping[$bridge]['ads']);echo '</pre>';
      echo '</div>';
    }
  } else {
    $array_errors['no_ad_total_results_pages'][] = $api_full_url;
    echo '<div style="color:#A00">';
    echo 'NO AD_TOTAL RESULTS PAGES<br>';
    echo "<br>## scrapping_url > " . $scrapping_url . "<br>";
    echo "<br>## api_full_url > " . $api_full_url . "<br>";
    echo "<br>## url > " . $url . "<br>";
    echo '<pre>';var_dump($json_array_datas);echo '</pre>';
    echo '</div>';
  }

echo '<pre>';
var_dump($array_errors);
echo '</pre>';
$filename = $_SERVER["DOCUMENT_ROOT"] . '/scrapping/xml/errors.xml';

$fp = @fopen($filename,'w');
if(!$fp) {
  echo('Error cannot create ERROR XML file');
}
fwrite($fp,array2xml($array_errors));
fclose($fp);

