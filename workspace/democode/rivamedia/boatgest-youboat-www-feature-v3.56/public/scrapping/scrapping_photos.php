  <?php
  $bridge 	   = isset($_POST['bridge']) ? $_POST['bridge'] : isset($_GET['bridge']) ? $_GET['bridge'] : 'www.boatshop24.co.uk';

  $prefix_url    = isset($_POST['prefix_url']) ? $_POST['prefix_url'] : isset($_GET['prefix_url']) ? $_GET['prefix_url'] : 'http://www.boatshop24.co.uk';

  $showContent 	  = isset($_POST['show_content']) && $_POST['show_content'] == 1 ? 1 : isset($_GET['show_content']) && $_GET['show_content'] == 1 ? 1 : false;
  $array_img_src = [
    '/img/adphotos/import/144/100144_hallberg-rassy-36_photo_0_1456818019_img.jpg',
    '/img/adphotos/import/144/100144_hallberg-rassy-36_photo_2_1456818329_img.jpg',
    '/img/adphotos/import/144/100144_hallberg-rassy-36_photo_3_1456818329_img.jpg',
    '/img/adphotos/import/144/100144_hallberg-rassy-36_photo_4_1456818328_img.jpg',
    '/img/adphotos/import/144/100144_hallberg-rassy-36_photo_5_1456818328_img.jpg',
    '/img/adphotos/import/144/100144_hallberg-rassy-36_photo_6_1456818327_img.jpg',
    '/img/adphotos/import/144/100144_hallberg-rassy-36_photo_7_1456818328_img.jpg',
    '/img/adphotos/import/144/100144_hallberg-rassy-36_photo_8_1456818327_img.jpg',
    '/img/adphotos/import/144/100144_hallberg-rassy-36_photo_9_1456818327_img.jpg',
    '/img/adphotos/import/144/100144_hallberg-rassy-36_photo_10_1456818327_img.jpg',
    '/img/adphotos/import/144/100144_hallberg-rassy-36_photo_11_1456818326_img.jpg',
    '/img/adphotos/import/144/100144_hallberg-rassy-36_photo_12_1456818326_img.jpg',
    '/img/adphotos/import/144/100144_hallberg-rassy-36_photo_13_1456818325_img.jpg',
    '/img/adphotos/import/144/100144_hallberg-rassy-36_photo_14_1456818326_img.jpg',
    '/img/adphotos/import/144/100144_hallberg-rassy-36_photo_15_1456818324_img.jpg',
    '/img/adphotos/import/144/100144_hallberg-rassy-36_photo_16_1456818325_img.jpg',
    '/img/adphotos/import/144/100144_hallberg-rassy-36_photo_17_1456818325_img.jpg',
    '/img/adphotos/import/144/100144_hallberg-rassy-36_photo_18_1456818324_img.jpg',
    '/img/adphotos/import/144/100144_hallberg-rassy-36_photo_19_1456818324_img.jpg',
    '/img/adphotos/import/144/100144_hallberg-rassy-36_photo_20_1456818323_img.jpg',
    '/img/adphotos/import/144/100144_hallberg-rassy-36_photo_21_1456818323_img.jpg',
    '/img/adphotos/import/144/100144_hallberg-rassy-36_photo_22_1456818322_img.jpg',
    '/img/adphotos/import/144/100144_hallberg-rassy-36_photo_23_1456818323_img.jpg',
    '/img/adphotos/import/144/100144_hallberg-rassy-36_photo_24_1456818322_img.jpg',
    '/img/adphotos/import/144/100144_hallberg-rassy-36_photo_25_1456818322_img.jpg',
    '/img/adphotos/import/144/100144_hallberg-rassy-36_photo_26_1456818322_img.jpg',
    '/img/adphotos/import/144/100144_hallberg-rassy-36_photo_27_1456818321_img.jpg',
    '/img/adphotos/import/144/100144_hallberg-rassy-36_photo_28_1456818321_img.jpg',
    '/img/adphotos/import/144/100144_hallberg-rassy-36_photo_29_1456818320_img.jpg',
    '/img/adphotos/import/144/100144_hallberg-rassy-36_photo_30_1456818321_img.jpg',
    '/img/adphotos/import/144/100144_hallberg-rassy-36_photo_31_1456818319_img.jpg',
    '/img/adphotos/import/144/100144_hallberg-rassy-36_photo_32_1456818320_img.jpg',
    '/img/adphotos/import/144/100144_hallberg-rassy-36_photo_33_1456818320_img.jpg',
    '/img/adphotos/import/144/100144_hallberg-rassy-36_photo_34_1456818319_img.jpg',
    '/img/adphotos/import/144/100144_hallberg-rassy-36_photo_35_1456818319_img.jpg',
    '/img/adphotos/import/144/100144_hallberg-rassy-36_photo_36_1456818318_img.jpg',
    '/img/adphotos/import/144/100144_hallberg-rassy-36_photo_37_1456818319_img.jpg',
    '/img/adphotos/import/144/100144_hallberg-rassy-36_photo_38_1456818318_img.jpg',
    '/img/adphotos/import/144/100144_hallberg-rassy-36_photo_39_1456818318_img.jpg',
    '/img/adphotos/import/144/100144_hallberg-rassy-36_photo_40_1456818317_img.jpg',
    '/img/adphotos/import/144/100144_hallberg-rassy-36_photo_41_1456818317_img.jpg',
    '/img/adphotos/import/144/100144_hallberg-rassy-36_photo_42_1456818317_img.jpg',
    '/img/adphotos/import/144/100144_hallberg-rassy-36_photo_43_1456818316_img.jpg',
    '/img/adphotos/import/144/100144_hallberg-rassy-36_photo_44_1456818316_img.jpg',
    '/img/adphotos/import/144/100144_hallberg-rassy-36_photo_45_1456818315_img.jpg',
    '/img/adphotos/import/144/100144_hallberg-rassy-36_photo_46_1456818316_img.jpg',
    '/img/adphotos/import/144/100144_hallberg-rassy-36_photo_47_1456818314_img.jpg',
    '/img/adphotos/import/144/100144_hallberg-rassy-36_photo_48_1456818315_img.jpg',
    '/img/adphotos/import/144/100144_hallberg-rassy-36_photo_49_1456818315_img.jpg',
  ];
  foreach($array_img_src as $img_src) {
    $img_src = urlencode($prefix_url . $img_src);
    //$img_src = $prefix_url . $img_src;

    $url            = 'http://' . $_SERVER["SERVER_NAME"] .':8888/scrapping/scrapping_save_photos.php?img_src=' . $img_src . '&bridge=' . $bridge .'&show_content=' . $showContent;
    echo '<br>' . $url;
    //file_get_contents($url);
  }
