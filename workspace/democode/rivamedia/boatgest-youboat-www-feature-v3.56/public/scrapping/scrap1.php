<?php
  $nb_links_page = 0;
  $scrapping = [];
  // 01 : www.boatshop24.co.uk_results_pages
  $api_url        = 'https://extraction.import.io/query/extractor/';
  $api_name       = 'a105bd82-206b-473f-b6cc-63f4900fdc0c';
  $scrapping_url  = urlencode('http://www.boatshop24.co.uk/boats-for-sale?list_type=table&order=newest&page=1');
  $url            = 'http://labs.rivamedia.fr/scrapping/scrapping_api.php?api_url=' . $api_url . '&api_name=' . $api_name . '&scrapping_url=' . $scrapping_url;

  $json           = file_get_contents($url);
  //echo '<br>'.'*************' . '<br>';
  //echo '$json : <br>';
  //var_dump($json);

  $jfo = json_decode($json);
  echo '$jfo : <br>';
  var_dump($jfo);
  echo '<br>'.'==============================' . '<br>';

  $extractorData = $jfo->extractorData;

  echo '<br>'.'*************$extractorDataUrl' . '<br>';
  //$extractorDataUrl = $jfo->extractorData->url;
  $extractorDataUrl = $extractorData>url;
  echo($extractorDataUrl);
    $scrapping['url'] = $extractorDataUrl;

  echo '<br>'.'*************$extractorDataResourceId' . '<br>';
  //$extractorDataResourceId = $jfo->extractorData->resourceId;
  $extractorDataResourceId = $extractorData->resourceId;
  echo($extractorDataResourceId);
    $scrapping['resource_id'] = $extractorDataResourceId;

  //echo '<br>'.'*************$extractorDataData' . '<br>';
  //$extractorDataData = $jfo->extractorData->data;
  $extractorDataData = $extractorData->data;
  //var_dump($extractorDataData);

  //echo '<br>'.'*************$extractorDataData[0]' . '<br>';
  //var_dump($extractorDataData[0]);

  //echo '<br>'.'*************$extractorDataData[0]->group' . '<br>';
  //var_dump($extractorDataData[0]->group);

  //echo '<br>'.'*************$extractorDataData[0]->group[0]' . '<br>';
  //var_dump($extractorDataData[0]->group[0]);

  //echo '<br>'.'*************$extractorDataData[0]->group[0]->ad_total_results' . '<br>';
  //var_dump($extractorDataData[0]->group[0]->ad_total_results);

  echo '<br>'.'*************$extractorDataData[0]->group[0]->ad_total_results[0]->text' . '<br>';
  $ad_total_results = $extractorDataData[0]->group[0]->ad_total_results[0]->text;
  echo(ad_total_results);
    $scrapping['ad_total_results'] = $ad_total_results;

  echo '<br>'.'*************$extractorDataData[0]->group[0]->ad_total_pages[0]->text' . '<br>';
  $ad_total_pages = $extractorDataData[0]->group[0]->ad_total_pages[0]->text;
  echo($ad_total_pages);
    $scrapping['ad_total_pages'] = $ad_total_pages;

  echo '<br>'.'==============================' . '<br>';
  echo '<br>'.'==============================' . '<br>';

  echo '<br>'.'++++++$extractorDataData[0]->group[0]' . '<br>';
  $groups = $extractorDataData[0]->group[0];
  var_dump($groups);
  echo '<br>#####$extractorDataData[0]->group' . '<br>';
  $groups = $extractorDataData[0]->group;
  var_dump($groups);

  //$array_json_group         = json_decode($json, TRUE)->extractorData->data[0]->group;
  $array_json_group         = json_decode($json, TRUE)["extractorData"]["data"][0]["group"];
  $jsonIterator = new RecursiveIteratorIterator(
    new RecursiveArrayIterator($array_json_group),
    RecursiveIteratorIterator::SELF_FIRST);

  foreach ($jsonIterator as $key => $val) {
      if(is_array($val)) {
          if($key != '0' ) echo "<br>$key:";
      } else {
          echo "$val<br>";
          $array_links [] = $val;
      }
  }
