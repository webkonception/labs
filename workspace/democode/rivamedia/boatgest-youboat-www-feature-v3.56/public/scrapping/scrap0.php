<?php
  $nb_links_page = 0;
  // 01 : www.boatshop24.co.uk_results_pages
  $api_url        = 'https://extraction.import.io/query/extractor/';
  $api_name       = 'a105bd82-206b-473f-b6cc-63f4900fdc0c';
  $scrapping_url  = 'http://www.boatshop24.co.uk/boats-for-sale?list_type=table&order=newest&page=1';
  $url            = 'http://labs.rivamedia.fr/scrapping/scrapping_api.php?api_url=' . $api_url . '&api_name=' . $api_name . '&scrapping_url=' . $scrapping_url;
  //var_dump($url);

  $json           = file_get_contents($url);
  $array_json     = json_decode($json, true)["extractorData"]["data"][0]["group"][0]["ad_total_pages"][0]["text"];
  $nb_pages       = $array_json;
  $nb_pages = 2;
  //echo "<pre>";
  //var_dump($nb_pages);
  //echo "</pre>";
  //die();

  echo '1]-------' . '<br>';
  echo 'url : ' . $url . '<br>';
  echo 'nb_pages : ' . $nb_pages . '<br>';
  echo '---------' . '<br><br>';

  $array_links = [];
  $json_array_links = '';
  for ($i=1; $i<=$nb_pages; $i++) {
    // 02 : www.boatshop24.co.uk_results_pages
    $api_url            = 'https://extraction.import.io/query/extractor/';
    $api_name           = 'e6138c59-0785-48b2-8df1-09a810fb34a3';
    $scrapping_url      = 'http://www.boatshop24.co.uk/boats-for-sale?list_type=table&order=newest&page=' . $i;
    $url                = 'http://labs.rivamedia.fr/scrapping/scrapping_api.php?api_url=' . $api_url . '&api_name=' . $api_name . '&scrapping_url=' . $scrapping_url;
    //echo "<pre>";var_dump($url);echo "</pre>";

    echo '2-' . $i .']-------' . '<br>';
    echo 'url : ' . $url . '<br>';
    echo '---------' . '<br><br>';

    $json               = file_get_contents($url);
    //echo $json;
    //echo '<br>' . '---------' . '<br>';

    echo '======' . '<br>';
    echo '======' . '<br>';
    $array_json   = json_decode($json, TRUE);
    //echo "array_json<pre>";var_dump($array_json);echo "</pre>";
    /*$jsonIterator = new RecursiveIteratorIterator(
      new RecursiveArrayIterator(array_json),
      RecursiveIteratorIterator::SELF_FIRST);

    foreach ($jsonIterator as $key => $val) {
        if(is_array($val)) {
            //echo "$key:<br>";
        } else {
            echo "$key => $val<br>";
        }
    }
    echo '======' . '<br>';
    echo '======' . '<br>';*/
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
    echo '======' . '<br>';
    echo '======' . '<br>';


    echo '<br>' . '---------' . '<br>';
    echo '$json->extractorData->data : '. '<br>';
    echo $json->extractorData->data;

//    $array_links    = array_merge($array_links, $array_json);
//    $nb_links_page  = count($array_json);
//    $json_array_links = json_encode($array_links);

    //$array_links    = array_merge($array_links, $array_json_group);
    echo "array_links<pre>";var_dump($array_links);echo "</pre>";
    $nb_links_page  += count($array_json_group);
    $json_array_links = json_encode($array_links);
  }
  //echo "<pre>";var_dump($array_links);echo "</pre>";

  echo '<br>nb_links_page  : ' . $nb_links_page;

  $nb_links_page  = count($array_links);
  echo '<br>nb_links_page  : ' . $nb_links_page;
/*
    for ($j=0; $j<=$nb_links_page; $j++) {
      echo "<pre>";
      var_dump($array_json[$j]["ad_link"][0]["text"]);
      echo "</pre>";
    }
*/

  echo '<br>';

  $jsonIterator = new RecursiveIteratorIterator(
    new RecursiveArrayIterator(json_decode($json_array_links, TRUE)),
    RecursiveIteratorIterator::SELF_FIRST);

  foreach ($jsonIterator as $key => $val) {
      if(is_array($val)) {
          //echo "$key:<br>";
      } else {
          echo "$key => $val<br>";
      }
  }
