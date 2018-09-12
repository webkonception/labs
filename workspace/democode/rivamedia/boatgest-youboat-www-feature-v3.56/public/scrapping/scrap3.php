<?php
  $nb_links_page = 0;
  $scrapping = [];

  // 01 : www.boatshop24.co.uk_results_pages
  echo "===========================================================" . "<br>";
  echo "== 01 : www.boatshop24.co.uk_results_pages" . "<br>";
  echo "===========================================================" . "<br>";
  //
  https://extraction.import.io/query/extractor/a105bd82-206b-473f-b6cc-63f4900fdc0c?_apikey=7970b3d557714c728e28ca833612bf18771971a0083d0a8b21f37031fa36570f880bfc8a75535f1afc68bee75e83676905e0357395bdbb2e202083662079895da2430b4e9e019a5b826059ada6bbb4cb&url=http%3A%2F%2Fwww.boatshop24.co.uk%2Fboats-for-sale%3Flist_type%3Dtable%26url%3Dmaxdim%26order%3Dnewest%26page%3D1
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

  echo ">file_get_contents" . "<br>";
  $json           = file_get_contents($url);
  echo ">json_decode" . "<br>";
  $jfo            = json_decode($json);
  //echo '$jfo : <br>';
  //var_dump($jfo);

  $extractorData = $jfo->extractorData;
  /*
  $extractorDataUrl = $extractorData>url;
  echo "## extractorDataUrl > " . $extractorDataUrl . "<br>";
  $scrapping[$bridge]['url'] = $extractorDataUrl;
  */
  /*
  $extractorDataResourceId = $extractorData->resourceId;
  echo "## extractorDataResourceId > " . $extractorDataResourceId . "<br>";
    $scrapping[$bridge]['resource_id'] = $extractorDataResourceId;
  */
  $extractorDataData = $extractorData->data;
  $ad_total_results = $extractorDataData[0]->group[0]->ad_total_results[0]->text;
  echo "## ad_total_results > " . $ad_total_results . "<br>";
    $scrapping[$bridge]['ad_total_results'] = $ad_total_results;

  $ad_total_pages = $extractorDataData[0]->group[0]->ad_total_pages[0]->text;
  echo "## ad_total_pages > " . $ad_total_pages . "<br>";
    $scrapping[$bridge]['ad_total_pages'] = $ad_total_pages;

  // 02 : www.boatshop24.co.uk_ads_links
  echo "===========================================================" . "<br>";
  echo "== 02 : www.boatshop24.co.uk_ads_links" . "<br>";
  echo "===========================================================" . "<br>";
  //https://extraction.import.io/query/extractor/e6138c59-0785-48b2-8df1-09a810fb34a3?_apikey=7970b3d557714c728e28ca833612bf18771971a0083d0a8b21f37031fa36570f880bfc8a75535f1afc68bee75e83676905e0357395bdbb2e202083662079895da2430b4e9e019a5b826059ada6bbb4cb&url=http%3A%2F%2Fwww.boatshop24.co.uk%2Fboats-for-sale%3Flist_type%3Dtable%26url%3Dmaxdim%26order%3Dnewest%26page%3D1
  $api_url        = 'https://extraction.import.io/query/extractor/';
  $api_name       = 'e6138c59-0785-48b2-8df1-09a810fb34a3';

  $ad_total_pages = 2;
  $array_links = [];
  for ($i=1; $i <= $ad_total_pages; $i++) {
    echo "===========================================================" . "<br>";
    echo "== 02-$i" . "<br>";
    echo "===========================================================" . "<br>";
    $scrapping_url  = 'http://www.boatshop24.co.uk/boats-for-sale?list_type=table&order=newest&page=' . $i;
    $url            = 'http://labs.rivamedia.fr/scrapping/scrapping_api.php?api_url=' . $api_url . '&api_name=' . $api_name . '&scrapping_url=' . urlencode($scrapping_url);
    echo "## url > " . $url . "<br>";

    echo ">file_get_contents" . "<br>";
    $json           = file_get_contents($url);
    echo ">json_decode" . "<br>";
    $jfo            = json_decode($json);
    //echo '$jfo : <br>';
    //var_dump($jfo);

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
  $scrapping[$bridge]['ads'] = $array_links;
  echo '<pre>';var_dump($scrapping);echo '</pre>';
