<?php

  function fileGetImageCurl($targetUrl, $image_type, $showContent, $timeout, $bridge) {
    $return = '';
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
      mail("emmanuel.deiller.rivamedia@gmail.com","[" . $bridge ."] Unable to access to ", $targetUrl . " => " . $CurlErr . "\n\n");
      $return = 'NO RESPONSE';
    } else if (empty($output)) {
      mail("emmanuel.deiller.rivamedia@gmail.com","[" . $bridge . "] No matches were found at this URL => ", $targetUrl . "\n\n");
      $return = 'NO IMAGE';
    } else {
      $filename = basename($targetUrl);
      list($subdir) = explode("_", $filename);

      $pathname = $_SERVER['DOCUMENT_ROOT'] . '/assets/images/ads/' . $subdir ;
      if (!is_dir($pathname) && strlen($pathname)>0) {
        mkdir($pathname .'/', 0777, true);
      }
      $filename_dest = $pathname . '/' . $filename;

      if (file_exists($filename_dest)) {
        $return = "The file $filename_dest exists";
      } else {
        //list($width, $height, $image_type) = getimagesize($targetUrl);

        switch ($image_type)
        {
          case 1: $src = imagecreatefromgif($targetUrl); break;
          case 2: $src = imagecreatefromjpeg($targetUrl);  break;
          case 3: $src = imagecreatefrompng($targetUrl); break;
          default: return '';  break;
        }
        //$src = $output;

        switch ($image_type) {
          case 1:
            imagegif($src, $filename_dest);
            break;
          case 2:
            imagejpeg($src, $filename_dest, 100);
            break; // best quality
          case 3:
            imagepng($src, $filename_dest, 0);
            break; // no compression
          default:return '';
            break;
        }

        if($showContent) {
          switch ($image_type)
          {
            case 1: $src = imagecreatefromgif($filename_dest); break;
            case 2: $src = imagecreatefromjpeg($filename_dest);  break;
            case 3: $src = imagecreatefrompng($filename_dest); break;
            default: return '';  break;
          }
          switch ($image_type) {
            case 1:
              $final_image = imagegif($src);
              break;
            case 2:
              $final_image = imagejpeg($src, NULL, 100);
              break; // best quality
            case 3:
              $final_image = imagepng($src, NULL, 0);
              break; // no compression
            default:return '';
              break;
          }
          $return  = $final_image;

        }
      }

    }
    //return $return;
    echo $return;
  }

  $img_src        = isset($_POST['img_src']) ? $_POST['img_src'] : isset($_GET['img_src']) ? $_GET['img_src'] : '';
  $bridge 		  = isset($_POST['bridge']) ? $_POST['bridge'] : isset($_GET['bridge']) ? $_GET['bridge'] : 'www.boatshop24.co.uk';
  $showContent 	  = isset($_POST['show_content']) && $_POST['show_content'] == 1 ? true : isset($_GET['show_content']) && $_GET['show_content'] == 1 ? true : false;
  $timeout        = 10;


  //$showContent = true;
  list($width, $height, $image_type) = getimagesize($img_src);
  $ctype = "";
  switch ($image_type) {
    case 1:
      $ctype = "image/gif";
      break;
    case 2:
      $ctype = "image/jpeg";
      break;
    case 3:
      $ctype = "image/png";
      break;
    default:
      $ctype = '';
      break;
  }
  if($showContent == true && '' != $ctype) {
    header('Content-type: ' . $ctype);
    fileGetImageCurl($img_src, $image_type, $showContent, $timeout, $bridge);
  } else if ($showContent == true && '' == $ctype) {
    fileGetImageCurl($img_src, $image_type, $showContent, $timeout, $bridge);
  }