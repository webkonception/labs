<?php
  $nb_links_page = 0;
  $scrapping = [];

  // 01 : www.boatshop24.co.uk_results_pages
  echo "===========================================================" . "<br>";
  echo "== 01 : www.boatshop24.co.uk_results_pages" . "<br>";
  echo "===========================================================" . "<br>";
  //https://extraction.import.io/query/extractor/a105bd82-206b-473f-b6cc-63f4900fdc0c?_apikey=7970b3d557714c728e28ca833612bf18771971a0083d0a8b21f37031fa36570f880bfc8a75535f1afc68bee75e83676905e0357395bdbb2e202083662079895da2430b4e9e019a5b826059ada6bbb4cb&url=http%3A%2F%2Fwww.boatshop24.co.uk%2Fboats-for-sale%3Flist_type%3Dtable%26url%3Dmaxdim%26order%3Dnewest%26page%3D1
  $api_url        = 'https://extraction.import.io/query/extractor/';
  $api_name       = 'a105bd82-206b-473f-b6cc-63f4900fdc0c';
  $api_key        = '7970b3d557714c728e28ca833612bf18771971a0083d0a8b21f37031fa36570f880bfc8a75535f1afc68bee75e83676905e0357395bdbb2e202083662079895da2430b4e9e019a5b826059ada6bbb4cb';
  $scrapping_url  = 'http://www.boatshop24.co.uk/boats-for-sale?list_type=table&order=newest&page=1';
  $bridge         = 'www.boatshop24.co.uk';
  $url            = 'http://labs.rivamedia.fr/scrapping/scrapping_api.php?api_url=' . $api_url . '&api_name=' . $api_name . '&scrapping_url=' . urlencode($scrapping_url) . '&bridge=' . $bridge;
  $api_full_url   = $api_url . $api_name . '?_apikey=' . $api_key . '&url=' . urlencode($scrapping_url);
  echo "## api_full_url > " . $api_full_url . "<br>";

  $url            = 'http://labs.rivamedia.fr/scrapping/scrapping_api.php?api_full_url=' . urlencode($api_full_url) . '&bridge=' . $bridge;
  echo "## url > " . $url . "<br>";

  $json           = file_get_contents($url);
  $jfo            = json_decode($json);

  $pageData = $jfo->pageData;
  if (200 === $pageData->statusCode) {
    $extractorData = $jfo->extractorData;
    $extractorDataData = $extractorData->data;
    $ad_total_results = $extractorDataData[0]->group[0]->ad_total_results[0]->text;
    echo "## ad_total_results > " . $ad_total_results . "<br>";
      $scrapping[$bridge]['ad_total_results'] = $ad_total_results;

    $ad_total_pages = $extractorDataData[0]->group[0]->ad_total_pages[0]->text;
    echo "## ad_total_pages > " . $ad_total_pages . "<br>";
      $scrapping[$bridge]['ad_total_pages'] = $ad_total_pages;

    if( $ad_total_pages > 0) {
      // 02 : www.boatshop24.co.uk_ads_links
      echo "<br>===========================================================" . "<br>";
      echo "== 02 : www.boatshop24.co.uk_ads_links" . "<br>";
      echo "===========================================================" . "<br>";
      //https://extraction.import.io/query/extractor/e6138c59-0785-48b2-8df1-09a810fb34a3?_apikey=7970b3d557714c728e28ca833612bf18771971a0083d0a8b21f37031fa36570f880bfc8a75535f1afc68bee75e83676905e0357395bdbb2e202083662079895da2430b4e9e019a5b826059ada6bbb4cb&url=http%3A%2F%2Fwww.boatshop24.co.uk%2Fboats-for-sale%3Flist_type%3Dtable%26url%3Dmaxdim%26order%3Dnewest%26page%3D1
      $api_url        = 'https://extraction.import.io/query/extractor/';
      $api_name       = 'e6138c59-0785-48b2-8df1-09a810fb34a3';
      $api_key        = '7970b3d557714c728e28ca833612bf18771971a0083d0a8b21f37031fa36570f880bfc8a75535f1afc68bee75e83676905e0357395bdbb2e202083662079895da2430b4e9e019a5b826059ada6bbb4cb';

      $ad_total_pages = 2;
      $array_links = [];
      for ($i=1; $i <= $ad_total_pages; $i++) {
        echo "<br>===========================================================" . "<br>";
        echo "== 02-$i" . "<br>";
        echo "===========================================================" . "<br>";
        $scrapping_url  = 'http://www.boatshop24.co.uk/boats-for-sale?list_type=table&order=newest&page=' . $i;
        $bridge         = 'www.boatshop24.co.uk';
        $url            = 'http://labs.rivamedia.fr/scrapping/scrapping_api.php?api_url=' . $api_url . '&api_name=' . $api_name . '&scrapping_url=' . urlencode($scrapping_url) . '&bridge=' . $bridge;
        $api_full_url   = $api_url . $api_name . '?_apikey=' . $api_key . '&url=' . urlencode($scrapping_url);
        echo "## api_full_url > " . $api_full_url . "<br>";

        $url            = 'http://labs.rivamedia.fr/scrapping/scrapping_api.php?api_full_url=' . urlencode($api_full_url) . '&bridge=' . $bridge;
        echo "## url > " . $url . "<br>";

        $json           = file_get_contents($url);
        $jfo            = json_decode($json);

        $pageData = $jfo->pageData;
        if (200 === $pageData->statusCode) {
          $extractorData = $jfo->extractorData;
          $extractorDataData = $extractorData->data;
          $extractorDataDataGroup = $extractorData->data[0]->group;
          //echo '<pre>';var_dump($extractorDataDataGroup);echo '</pre>';
          foreach($extractorDataDataGroup as $key => $group) {
            $ad_title = $group->ad_link[0]->text;
            $ad_url = $group->ad_url[0]->text;
            $array_links [] = ['title'=>$ad_title, 'url'=> 'http://' . $bridge . $ad_url];
          }
        }
      }
      $scrapping[$bridge]['ads'] = $array_links;
      //echo '<pre>';var_dump($scrapping);echo '</pre>';
    }

    if (count($scrapping[$bridge]['ads']) > 0 ) {
      // 03 : www.boatshop24.co.uk_ads_details
      echo "<br>===========================================================" . "<br>";
      echo "== 03 : www.boatshop24.co.uk_ads_details" . "<br>";
      echo "===========================================================" . "<br>";
      //https://extraction.import.io/query/extractor/65b6b991-1a9c-4de8-be49-00cd57a759e7?_apikey=7970b3d557714c728e28ca833612bf18771971a0083d0a8b21f37031fa36570f880bfc8a75535f1afc68bee75e83676905e0357395bdbb2e202083662079895da2430b4e9e019a5b826059ada6bbb4cb&url=http%3A%2F%2Fwww.boatshop24.co.uk%2Ffishing-boats%2Fromany-21-by-gordon-payne%2F105645
      $api_url        = 'https://extraction.import.io/query/extractor/';
      $api_name       = '65b6b991-1a9c-4de8-be49-00cd57a759e7';
      $api_key        = '7970b3d557714c728e28ca833612bf18771971a0083d0a8b21f37031fa36570f880bfc8a75535f1afc68bee75e83676905e0357395bdbb2e202083662079895da2430b4e9e019a5b826059ada6bbb4cb';

      $array_details = [];
      $j = 0;

      foreach ($scrapping[$bridge]['ads'] as $key => $value) {
          echo($scrapping[$bridge]['ads'][$key]["url"]);
          echo "<br>===========================================================" . "<br>";
          echo "== 03-" . ($key+1) . "<br>";
          echo "===========================================================" . "<br>";
          $scrapping_url  = $scrapping[$bridge]['ads'][$key]["url"];
          $bridge         = 'www.boatshop24.co.uk';
          $url            = 'http://labs.rivamedia.fr/scrapping/scrapping_api.php?api_url=' . $api_url . '&api_name=' . $api_name . '&scrapping_url=' . urlencode($scrapping_url) . '&bridge=' . $bridge;
          $api_full_url   = $api_url . $api_name . '?_apikey=' . $api_key . '&url=' . urlencode($scrapping_url);
          //echo "## api_full_url > " . $api_full_url . "<br>";

          $url            = 'http://labs.rivamedia.fr/scrapping/scrapping_api.php?api_full_url=' . urlencode($api_full_url) . '&bridge=' . $bridge;
          //echo "## url > " . $url . "<br>";

          $json           = file_get_contents($url);
          $jfo            = json_decode($json);


          $pageData = $jfo->pageData;
          if (200 === $pageData->statusCode) {

            //echo '<pre>';var_dump(json_decode($json, TRUE)["extractorData"]["data"][0]["group"]);echo '</pre>';
            //echo '---<br>';
            echo '<pre>';var_dump(json_encode($jfo->extractorData->data[0]->group, TRUE));echo '</pre>';
            //echo '---<br>';

            //$extractorData = $jfo->extractorData;
            //echo '<pre>';var_dump($extractorData->data[0]->group);echo '</pre>';
            //echo '<pre>';var_dump(json_encode($extractorData->data[0]->group, TRUE));echo '</pre>';
            //echo '<pre>';var_dump(json_decode(json_encode($extractorData->data[0]->group), TRUE));echo '</pre>';
            //echo '---<br>';

            //$extractorDataData = $extractorData->data;
            //echo '<pre>';var_dump(json_encode($extractorDataData[0]->group, TRUE));echo '</pre>';
            //echo '---<br>';

            //$extractorDataDataGroup = $extractorData->data[0]->group;
            //echo '<pre>';var_dump($extractorDataDataGroup);echo '</pre>';
            /*foreach($extractorDataDataGroup as $key => $group) {
              //echo "key : " . $key . "<br>";
              //echo '<pre>';var_dump($group);echo '</pre>';
              //$ad_title = $group->ad_title[0]->text;
              //var_dump($ad_title); echo "<br>";
              ////$ad_location = $group->ad_location[0]->text;
              ////$ad_ref = $group->ad_ref[0]->text;

              ////$ad_url = $group->ad_url[0]->text;
              ////$array_links [] = ['title'=>$ad_title, 'url'=> 'http://' . $bridge . $ad_url];

            }*/
            /*
            $array_json_group         = json_decode($json, TRUE)["extractorData"]["data"][0]["group"];
            //echo "array_json_group<pre>";var_dump($array_json_group);echo "</pre>";
            $jsonIterator = new RecursiveIteratorIterator(
              new RecursiveArrayIterator($array_json_group),
              RecursiveIteratorIterator::SELF_FIRST);

            foreach ($jsonIterator as $key => $val) {
                if(is_array($val)) {
                    //echo "$key:<br>";
                } else {
                    echo "$key => $val<br>";
                    $array_links [] = $val;
                }
            }
            */
          }
          $j ++;
          //if ($j>1) break;

      }

    }
}
