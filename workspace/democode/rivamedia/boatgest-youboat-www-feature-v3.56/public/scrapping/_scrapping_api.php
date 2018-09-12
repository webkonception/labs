<?php
function fileGetContentsCurl($url, $showContent, $timeout, $bridge) {
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
  $resultat = curl_exec($ch);
  $CurlErr = curl_error($ch);
  curl_close($ch);

  if ($CurlErr) {
    mail("emmanuel.deiller.rivamedia@gmail.com","[" . $bridge ."] Unable to access the site",$url." => " . $CurlErr . "\n\n");
    return false;
  }else if (empty($resultat)) {
    mail("emmanuel.deiller.rivamedia@gmail.com","[" . $bridge . "] No matches were found at this URL => ", $url . "\n\n");
    return false;
  }
  elseif ($showContent){
    return $resultat;
  }
}
$bridge 		= 'www.boatshop24.co.uk';
$url            = 'https://extraction.import.io/Something is wrong/extractor/a105bd82-206b-473f-b6cc-63f4900fdc0c?_apikey=7970b3d557714c728e28ca833612bf18771971a0083d0a8b21f37031fa36570f880bfc8a75535f1afc68bee75e83676905e0357395bdbb2e202083662079895da2430b4e9e019a5b826059ada6bbb4cb&url=http%3A%2F%2Fwww.boatshop24.co.uk%2Fboats-for-sale%3Flist_type%3Dtable%26url%3Dmaxdim%26order%3Dnewest%26page%3D1';
$showContent 	= true;
$timeout        = 5;

$content = fileGetContentsCurl($url, $showContent, $timeout, $bridge);

echo $content;
