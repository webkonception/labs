<?php
    /**
     * Global helpers file with misc functions
     *
     */


    function preg_match_array($needle,$array)
    {
        foreach($array as $key => $value)
            if (preg_match("/$needle/i", $value))
                return $key;
        return false;
    }


    if (! function_exists('multi_array_search')) {
        function multi_array_search($search_for, $search_in) {
            foreach ($search_in as $element) {
                if ( ($element === $search_for) || (is_array($element) && multi_array_search($search_for, $element)) ){
                    return true;
                }
            }
            return false;
        }
    }
    if (! function_exists('in_array')) {
        function in_arrayi($needle, $haystack) {
            return in_array(strtolower($needle), array_map('strtolower', $haystack));
        }
    }
    if (! function_exists('search_array')) {
        function search_array($needle, $haystack) {
            if (in_arrayi($needle, $haystack)) {
                return true;
            }
            foreach ($haystack as $element) {
                if (in_arrayi($needle, $haystack) && search_array($needle, $element)) {
                    return true;
                }
            }
            return false;
        }
    }
    if (! function_exists('shuffle_with_keys')) {
        function shuffle_with_keys(&$array, $start, $end, $preserve_keys) {
            $random = array();
            $keys = array_keys($array);

            shuffle($keys);

            foreach($keys as $key) {
                $random[$key] = $array[$key];
                unset($array[$key]);
            }
            if(isset($start) && isset($end)) {
                $random = array_slice($random, $start, $end, $preserve_keys);
            }
            return $random;
        }
    }
    if (! function_exists('shuffle_assoc')) {
        function shuffle_assoc(&$array)
        {
            $random = array();
            $keys = array_keys($array);

            shuffle($keys);

            foreach ($keys as $key) {
                $random[$key] = $array[$key];
            }

            return $random;
        }
    }
    if (! function_exists('fileGetContentsCurl')) {
        function fileGetContentsCurl($targetUrl, $showContent, $timeout, $bridge) {
            $bridge = !empty($bridge) ? $bridge : $_SERVER['SERVER_NAME'];
            $timeout = !empty($timeout) ? $timeout : 30;
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
    }

    if (! function_exists('fileGetImageCurl')) {
        function fileGetImageCurl($targetUrl, $targetDir, $imageType, $showContent, $timeout, $bridge, $params = []) {
            $bridge = !empty($bridge) ? $bridge : $_SERVER['SERVER_NAME'];
            $timeout = !empty($timeout) ? $timeout : 30;
            $return = false;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_URL, $targetUrl);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

            //curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: '. parse_url($targetUrl, PHP_URL_HOST)));
            if(isset($params['header_language']) && !empty($params['header_language'])) {
                curl_setopt($ch, CURLOPT_HTTPHEADER, [$params['header_language']]);
            }
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            $output = curl_exec($ch);
            $CurlErr = curl_error($ch);
            curl_close($ch);

            if ($CurlErr) {
                mail("emmanuel.deiller.rivamedia@gmail.com","[" . $bridge ."] Unable to access to ", $targetUrl . " => " . $CurlErr . "\n\n");
                $return = false;
            } else if (empty($output)) {
                mail("emmanuel.deiller.rivamedia@gmail.com","[" . $bridge . "] No matches were found at this URL => ", $targetUrl . "\n\n");
                $return = false;
            } else {
                if(is_array($params) && count($params) > 0) {
                    $filename = $params['filename'];
                    $assets_path = $params['assets_path'];
                } else {
                    $filename = basename($targetUrl);
                    list($subdir) = explode("_", $filename);

                    $targetDir = isset($targetDir) && !empty($targetDir) ? $targetDir : 'photos/';
                    $assets_path = '/assets/' . $targetDir . $subdir;
                }
                $pathname = $_SERVER['DOCUMENT_ROOT'] . $assets_path ;
                if (!is_dir($pathname) && strlen($pathname)>0) {
                    mkdir($pathname .'/', 0777, true);
                }
                $filename_dest = $pathname . '/' . $filename;

                //if (file_exists($filename_dest)) {
                    //$return = "The file $filename_dest exists";
                //} else {
                    //list($width, $height, $imageType) = getimagesize($targetUrl);

                    switch ($imageType)
                    {
                        case 1: $src = imagecreatefromgif($targetUrl); break;
                        case 2: $src = imagecreatefromjpeg($targetUrl);  break;
                        case 3: $src = imagecreatefrompng($targetUrl); break;
                        default: return '';  break;
                    }
                    //$src = $output;

                    switch ($imageType) {
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
                        switch ($imageType)
                        {
                            case 1: $src = imagecreatefromgif($filename_dest); break;
                            case 2: $src = imagecreatefromjpeg($filename_dest);  break;
                            case 3: $src = imagecreatefrompng($filename_dest); break;
                            default: return '';  break;
                        }
                        switch ($imageType) {
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
                    } else {
                        $return = TRUE;
                    }
                //}

            }
            return $return;
        }
    }

    if (! function_exists('ifModifiedSince')) {
        function ifModifiedSince()
        {
            //get the last-modified-date of this very file
            $lastModified = filemtime(__FILE__);

            //get a unique hash of this file (etag)
            $etagFile = md5_file(__FILE__);
            //$etagFile = md5(serialize(__FILE__)));
            //$etagFile = md5($lastModified . __FILE__);

            //get the HTTP_IF_MODIFIED_SINCE header if set
            $ifModifiedSince = (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : false);

            //get the HTTP_IF_NONE_MATCH header if set (etag: unique file hash)
            $etagHeader = (isset($_SERVER['HTTP_IF_NONE_MATCH']) ? trim($_SERVER['HTTP_IF_NONE_MATCH']) : false);

            //set last-modified header
            header("Last-Modified: " . gmdate("D, d M Y H:i:s", $lastModified) . " GMT");

            //set etag-header
            header("Etag: $etagFile");
            header('ETag: "' . $etagFile . '"');

            //make sure caching is turned on
            header('Cache-Control: public');

            //check if page has changed. If not, send 304 and exit
            //if (@strtotime($ifModifiedSince) == $lastModified || $etagHeader == $etagFile)
            if (@strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE'])==$lastModified || $etagHeader == $etagFile)
            {
                header("HTTP/1.1 304 Not Modified");
                exit;
            }

            // test code
            //die( "This page was last modified: ".date("d.m.Y H:i:s",time()));
        }
    }

    if (! function_exists('getFirstElement')) {
        function getFirstElement ($elementList, $delimiter = ';') {
            if (isset($elementList) && !empty($elementList)) {
                list($firstElement) = explode($delimiter, $elementList);
                return $firstElement;
            } else {
                return '';
            }
        }
    }

    if (! function_exists('getElements')) {
        function getElements ($elementList, $delimiter = ';', $index = null) {
            if (isset($elementList) && !empty($elementList)) {
                $elements = explode($delimiter, $elementList);
                if (isset($index) && !empty($index)) {
                    return $elements[$index];
                } else {
                    return $elements;
                }
            } else {
                return '';
            }
        }
    }

    if (! function_exists('base64_encode_image')) {
        function base64_encode_image($filename) {
            if ($filename) {
                //$ext = end(explode('.', $filename));
                //$ext = preg_replace('/^.*\.([^.]+)$/D', '$1', $filename);
                //$ext = substr($filename, strrpos($filename, '.') + 1);
                $ext = substr(strrchr($filename, '.'), 1);
                $imgbinary = fread(fopen($filename, "r"), filesize($filename));
                return 'data:image/' . $ext . ';base64,' . base64_encode($imgbinary);
            }
        }
    }

    if (! function_exists('html_base64_encode_image')) {
        function html_base64_encode_image($filename) {
            if ($filename) {
                return '<img src="' . base64_encode_image ($filename) . '" />';
            }
        }
    }

    if (! function_exists('getEnumValues')) {
        function getEnumValues($table, $column)
        {
            $type = DB::select( DB::raw("SHOW COLUMNS FROM $table WHERE Field = '$column'") )[0]->Type;
            preg_match('/^enum\((.*)\)$/', $type, $matches);
            $enum = array();
            foreach( explode(',', $matches[1]) as $value )
            {
                $v = trim( $value, "'" );
                $enum = array_add($enum, $v, $v);
            }
            return $enum;
        }
    }

    if (! function_exists('trans_url')) {
        /**
         * Generate a translated url.
         *
         * @param string $locale
         * @param string $url
         * @param array  $attributes
         *
         * @return string
         */
        function trans_url($locale = null, $url = null, $attributes = array())
        {
            $transUrl =  app('laravellocalization')->getLocalizedURL($locale, $url, $attributes);
            return $transUrl;
        }
    }

    if (! function_exists('trans_route')) {
        /**
         * Generate a translated route.
         *
         * @param string $locale
         * @param string $url
         * @param array  $attributes
         *
         * @return string
         */
        function trans_route($locale, $transKeyName, $attributes = array())
        {
            $transRoute = app('laravellocalization')->getURLFromRouteNameTranslated($locale, $transKeyName, $attributes);
            return $transRoute;
        }
    }
    if (! function_exists('link_trans_route')) {
        /**
         * Generate a html link translated route .
         *
         * @param string $url
         * @param string $alt
         * @param array  $attributes
         * @param bool   $secure
         *
         * @return string
         */
        function link_trans_route($route, $title = null, $attributes = [], $secure = null)
        {
            $currentLocale = app()->getLocale();
            $url =  url(app('laravellocalization')->getURLFromRouteNameTranslated($currentLocale, 'routes.' . $route));
            $title = is_null($title) ?: trans($title);
            $attributes['title'] = isset($attributes['title']) ? $attributes['title'] : $title;
            return app('html')->link($url, $title, $attributes, $secure);
        }
    }

    if (! function_exists('link_trans_url')) {
        /**
         * Generate a HTML link translated link.
         *
         * @param string $url
         * @param string $alt
         * @param array  $attributes
         * @param bool   $secure
         *
         * @return string
         */
        function link_trans_url($url, $title = null, $params = [], $attributes = [], $secure = null)
        {
            //$url =  url(LaravelLocalization::localizeURL($url));
            $url =  url(app('laravellocalization')->localizeURL($url));
            if(count($params) > 0) {
                foreach($params as $key => $param) {
                    $url .= '/' . $param;
                }
            }
            $title = is_null($title) ?: trans($title);
            $attributes['title'] = isset($attributes['title']) ? $attributes['title'] : $title;
            return app('html')->link($url, $title, $attributes, $secure);
        }
    }

    if (! function_exists('image')) {
        /**
         * Generate a HTML image.
         *
         * @param string $url
         * @param string $alt
         * @param array  $attributes
         * @param bool   $secure
         *
         * @return string
         */
        function image($url, $alt = null, $attributes = [], $secure = null)
        {
            $attributes['alt'] = $alt;
            return app('html')->image($url, $alt, $attributes, $secure);
        }
    }

    if (! function_exists('image_ext')) {
        /**
         * Generate a HTML image.
         *
         * @param string $url
         * @param string $alt
         * @param array  $attributes
         * @param bool   $secure
         *
         * @return string
         */
        function image_ext($referrer = '', $url, $targetDir = null, $alt = null, $attributes = [], $secure = null)
        {
            //if ($referrer) {
            if (!empty($url)) {
                $attributes['alt'] = $alt;
                //$targetUrl = urlencode($referrer . $url);
                $targetUrl = $referrer . $url;

                $filename = basename($targetUrl);
                list($subdir) = explode("_", $filename);

                $targetDir = isset($targetDir) && !empty($targetDir) ? $targetDir : 'images/photos/';
                $assets_path = '/assets/' . $targetDir . $subdir;
                $pathname = $_SERVER['DOCUMENT_ROOT'] . $assets_path ;
                $filename_dest = $pathname . '/' . $filename;

                if (file_exists($filename_dest)) {
                    return app('html')->image($assets_path . '/' . $filename, $alt, $attributes, $secure);
                } else {
                    try {
                        //debug('try ' .$targetUrl);
                        //$getimagesize = @getimagesize($targetUrl);
                        $getimagesize = getimagesize($targetUrl);
                    } catch(Exception $e) {
                        //debug('catch ' .$e);
                        $getimagesize = false;
                    }
                    //debug($getimagesize);
                    if ($getimagesize) {
                        list($width, $height, $imageType) = $getimagesize;
                        $ctype = "";
                        switch ($imageType) {
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
                        $timeout     = 15;
                        $showContent = false;
                        if(fileGetImageCurl($targetUrl, $targetDir, $imageType, $showContent, $timeout, $referrer)) {
                            return app('html')->image($assets_path . '/' . $filename, $alt, $attributes, $secure);
                        } else {
                            //$attributes['data-src'] = $assets_path . '/' . $filename;
                            //return app('html')->image('assets/img/picture-broken-link.png', $alt, $attributes, $secure);
                            return false;
                        }
                    } else {
                        //$attributes['data-src'] = $assets_path . '/' . $filename;
                        //return app('html')->image('assets/img/picture-broken-link.png', $alt, $attributes, $secure);
                        return false;
                    }
                }
            }
        }
    }

    if (! function_exists('url_image_ext')) {
        /**
         * Generate a HTML url image.
         *
         * @param string $url
         * @param string $alt
         * @param array  $attributes
         * @param bool   $secure
         *
         * @return string
         */
        function url_image_ext($referrer = '', $url, $targetDir = '', $params)
        {
            if (!empty($url)) {
                $targetUrl = $referrer . $url;

                $extension = pathinfo(parse_url($targetUrl)['path'], PATHINFO_EXTENSION);
                $filename = $params['image_name'] . '.' . $extension;
                $params['filename'] = $filename;
                $subDir = isset($params['sub_dir']) ? $params['sub_dir'] : $params['ad_id'] . '_' . str_slug($params['ad_title']);

                $targetDir = !empty($targetDir) ? $targetDir : 'photos/';
                $assetsPath = '/assets/' . $targetDir . $subDir;
                $params['assets_path'] = $assetsPath;
                $public_path = isset($params['public_path']) ? $params['public_path'] : $_SERVER['DOCUMENT_ROOT'];
                $pathname = $public_path . $assetsPath ;
                $filename_dest = $pathname . '/' . $filename;
                if(isset($params['force']) && $params['force'] && file_exists($filename_dest)) {
                    unlink($filename_dest);
                }

                if (file_exists($filename_dest)) {
                    return url($assetsPath . '/' . $filename);
                } else {
                    try {
                        //$getimagesize = @getimagesize($targetUrl);
                        $getimagesize = getimagesize($targetUrl);
                    } catch(Exception $e) {
                        //debug('catch ' .$e);
                        $getimagesize = false;
                    }
                    //debug($getimagesize);
                    if ($getimagesize) {
                        list($width, $height, $imageType) = $getimagesize;
                        $ctype = "";
                        switch ($imageType) {
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
                        $timeout     = 15;
                        $showContent = false;
                        if(fileGetImageCurl($targetUrl, $targetDir, $imageType, $showContent, $timeout, $referrer, $params)) {
                            //return url($assetsPath . '/' . $filename);
                            return $assetsPath . '/' . $filename;
                        } else {
                            //return url('assets/img/picture-broken-link.png');
                            return false;
                        }
                    } else {
                        //return url('assets/img/picture-broken-link.png');
                        return false;
                    }
                }
            } else {
                return false;
            }
        }
    }

    if (! function_exists('thumbnail')) {
        function thumbnail($srcUrl, $width = 320, $height = null, $watermark = false, $showImg = false, $name_size = true, $quality = 70)
        {
            $srcUrl = preg_replace("@^https?://[^/]+/@", "", $srcUrl);
            $pathinfo = pathinfo($srcUrl);
            //$filename = basename($srcUrl);
            $basename = $pathinfo['basename'];
            $filename = $pathinfo['filename'];
            //$ext = substr(strrchr($basename, '.'), 1);
            $extension = $pathinfo['extension'];
            //$dirname = dirname($srcUrl);
            $dirname = $pathinfo['dirname'];
            //$realpath = realpath($dirname);

            $thumbnail_dir = ($width == '100%' || $height == '100%') ? '/' : '/thumbnail/';

            $w = $width;
            $h = $height;

            if ($width == '100%' || $height == '100%') {
                $width = '';
                $height = '';
                $name_size = false;
                if ($watermark) {
                    $width = 'Y';
                    $height = 'B';
                    $name_size = true;
                }
            }

            if ($watermark) {
                $thumbnail_dir = '/' . 'YB' . $thumbnail_dir;
            }
            //$public_path = isset($params['public_path']) ? $params['public_path'] : $_SERVER['DOCUMENT_ROOT'];
            $public_path = $_SERVER['DOCUMENT_ROOT'];

            $thumbnail_path_name = $public_path . '/' . $dirname . $thumbnail_dir;
            if ($name_size) {
                $name_size = '_' . $width . $height;
            } else {
                $name_size = '';
            }
            if ($watermark) {
                $name_size = '_' . $width . $height;
            }
            $filename_dest =  $thumbnail_path_name . $filename . $name_size . '.' . $extension;
            if (file_exists($filename_dest)) {
                // filename_dest already exists
                if ($showImg) {
                    $thumb = app('html')->image($dirname . $thumbnail_dir . $filename . $name_size . '.' . $extension, '', []);
                } else {
                    $thumb = '/' . $dirname . $thumbnail_dir . $filename . $name_size . '.' . $extension;
                }
            } else {
                $filename_src = $_SERVER['DOCUMENT_ROOT'] . '/' . $srcUrl;

                if (file_exists($filename_src)) {
                    //$img = Image::make($filename_src)->fit($w,$h);
                    //$img = Image::make($filename_src)->widen($w, function ($constraint) {
                    //    $constraint->upsize();
                    //});
                    $img = Image::make($filename_src)->resize($w, $h, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                    if ($watermark) {
                        $watermark_src = ($width == 'Y' || $height == 'B') ? $_SERVER['DOCUMENT_ROOT'] . '/assets/img/watermark.png' : $_SERVER['DOCUMENT_ROOT'] . '/assets/img/watermark-small.png';
                        $img->insert($watermark_src, 'bottom-right', 10, 10)->iptc('Copyright 2017 YouBoat.com');
                    }
                    if (!is_dir($thumbnail_path_name) && strlen($thumbnail_path_name) > 0) {
                        mkdir($thumbnail_path_name, 0777, true);
                    }
                    $img->save($filename_dest, $quality);
                    if ($showImg) {
                        //return $img->response('jpg');
                        $thumb = app('html')->image($dirname . $thumbnail_dir . $filename . $name_size . '.' . $extension, '', []);
                    } else {
                        $thumb = '/' . $dirname . $thumbnail_dir . $filename . $name_size . '.' . $extension;
                    }
                } else {
                    return false;
                }
            }
            $thumb = preg_replace('/^\/\//', '/', $thumb);
            $thumb = preg_replace('/^\/assets/', 'assets', $thumb);
            return $thumb;
        }
    }

    if (! function_exists('app_name')) {
        /**
         * Helper to grab the application name
         *
         * @return mixed
         */
        function app_name()
        {
            return config('app.name');
        }
    }

    /**
     * Prepends a resource URL with the CDN prefix specified in .env "CDN" value
     *
     * @param  array  $asset The relative path to the asset. Should not include a preceeding slash
     * @return string
     *
     */
    if (!function_exists('cdn')) {
        function cdn($asset)
        {
            //Check if we added cdn's to the .env file
            $cdn = env('CDN', null);

            if (is_null($cdn)) {
                return asset($asset);
            }

            return "//" . rtrim($cdn, "/") . "/" . ltrim($asset, "/");
        }
    }

    /**
     * Produces a Redirect object with the appropriate validation message
     * @param string $route
     * @param Illuminate\Support\Facades\Validator $validator
     * @param array $params
     * @return \Illuminate\Support\Facades\Redirect
     */
    if (!function_exists('redirectWithValidation')) {
        function redirectWithValidation($route, $validator, $params = [])
        {
            return redirect()->route($route, $params)
                ->withErrors($validator)
                ->withInput();
        }
    }

    /**
     * Pre-fills a form input with value from session / model
     *
     * @param  string  $fieldName
     * @param  \Illuminate\Database\Eloquent\Model|null  $model
     * @param string prefix
     * @return string
     *
     */
    if (!function_exists('field')) {
        function field($fieldName, $model = null, $prefix = '')
        {
            return old($prefix ? $prefix . '.' . $fieldName : $fieldName, !empty($model) ? $model->$fieldName : "");
        }
    }

    /**
     * Generates HTML for a set of select field options
     *
     * @param  array  $optionList Array of options. Each element should be an array with "value" and "label" elements.
     * @param  multiple  $slectedValue
     * @return string
     *
     */
    if (!function_exists('options')) {
        function options($optionList, $selectedValue = null)
        {
            $options = [];
            foreach ($optionList as $option) {
                $options[] = '<option value="' . e($option['value']) . '"' . ($option['value'] == $selectedValue ? ' selected="selected"' : '') .
                    '>' . e($option['label']) . '</option>';
            }
            return implode("", $options);
        }
    }
    /**
     * Check if an Remote file exists
     *
     * @param  string    $url
     * @access static
     * @return bool      True if the url is accessible and false if the url is unaccessible or does not exist
     * @throws Exception An exception will be thrown when url is not set
     */

    if (!function_exists('checkRemoteFile')) {
        function checkRemoteFile($url)
        {
            if (null === $url || '' === trim($url)) {
                throw new Exception('The url to check must be a not empty string');
                //return false;
            }

            $file_headers = @get_headers($url);
            if (preg_match("|200|", $file_headers[0])) {
                return true;
            } else if (preg_match("|404|", $file_headers[0])) {
                return false;
            } else if (preg_match("|302|", $file_headers[0])) {
                return false;
            } else {
                return false;
            }
            /*switch($file_headers[0])
            {
                case 'HTTP/1.1 404 Not Found':
                    return false;
                    break;

                case 'HTTP/1.1 302 Moved Temporarily':
                    return false;
                    break;

                default:
                    return true;

            }*/
        }
    }

    /**
     * Check if an external file url exists
     *
     * @param  string    $url
     * @access static
     * @return bool      True if the url is accessible and false if the url is unaccessible or does not exist
     * @throws Exception An exception will be thrown when Curl session fails to start
     */
    if (!function_exists('url_exists')) {
        function url_exists($url) {
            // Version 4.x supported
            if (null === $url || '' === trim($url)) {
                throw new Exception('The url to check must be a not empty string');
                //return false;
            }

            $handle   = curl_init($url);

            if (false === $handle) {
                throw new Exception('Fail to start Curl session');
                //return false;
            }
            curl_setopt($handle, CURLOPT_HEADER, false);
            curl_setopt($handle, CURLOPT_FAILONERROR, true);  // this works
            curl_setopt($handle, CURLOPT_HTTPHEADER, Array("User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.15) Gecko/20080623 Firefox/2.0.0.15") ); // request as if Firefox
            curl_setopt($handle, CURLOPT_NOBODY, true);
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, false);

            // grab Url
            $connectable = curl_exec($handle);

            // close Curl resource, and free up system resources
            curl_close($handle);

            return $connectable;
        }
    }

    if (!function_exists('formatPrice')) {
        function formatPrice($price, $_countryCode = '', $finalPrice = false)
        {
            $price_formated = '';
            if (is_numeric($price)) {
                if($finalPrice) {
                    $price_formated = $price;
                } else {
                    $currentCountryCode = !empty(config('app.country_code')) ? config('app.country_code') : 'uk';
                    if (empty($_countryCode)) {
                        $_countryCode = (null !== config('youboat.' . $currentCountryCode . '.country_code')) ? config('youboat.' . $currentCountryCode . '.country_code') : 'GB';
                    }
                    $CountryLocaleFull = Search::getCountryLocaleFull(mb_strtoupper($_countryCode));
                    $locale = 'en-GB';
                    if (!empty($CountryLocaleFull['locales'])) {
                        list($locale) = explode(',', $CountryLocaleFull['locales']);
                    }
                    $locale = str_replace('-', '_', $locale);
                    $locale .= '.utf-8';
                    setlocale(LC_MONETARY, $locale);

                    $money_format = '%= (#10.0n';

                    $price_formated = money_format($money_format, $price);
                    $price_formated = preg_replace('!\s+!', ' ', $price_formated);
                    $price_formated = trim($price_formated);
                }
            } else {
                $price_formated = trim(preg_replace('!\s+!', ' ', $price));
            }

            return $price_formated;
        }
    }

    /*if (!function_exists('formatPriceCurrencyNF')) {
        function formatPriceCurrency($price, $country_id = '', $finalPrice = false)
        {
            $my_local_settings = localeconv();
            $locale_region = 'en-GB'; // default
            $region = $locale_region;
            $local_currency = trim($my_local_settings['int_curr_symbol']);
            $currency =  $local_currency; // default

            $CountryLocaleFull = [];
            if (empty($country_id)) {
                $currentCountryCode = !empty(config('app.country_code')) ? config('app.country_code') : 'uk';
                $_countryCode = (null !== config('youboat.' . $currentCountryCode . '.country_code')) ? config('youboat.' . $currentCountryCode . '.country_code') : 'GB';
                $CountryLocaleFull = Search::getCountryLocaleFull(mb_strtoupper($_countryCode));
            } else {
                $CountryLocaleFull = Search::getCountryLocaleFull($country_id);
            }

            if (!empty($CountryLocaleFull)) {
                $CountryLocales = array_key_exists('locales', $CountryLocaleFull) && !empty($CountryLocaleFull['locales']) ?  explode(',', $CountryLocaleFull['locales']) : '';
                $currency = array_key_exists('currency', $CountryLocaleFull) && !empty($CountryLocaleFull['currency']) ? $CountryLocaleFull['currency'] : $currency;

                //array_push($CountryLocales,str_replace('-', '_', $CountryLocales[0]).'.utf-8');
                list($region) = explode(',', $CountryLocaleFull['locales']);
            }

            $price_formated = '';
            if (!empty($price)) {
                $fmt = new NumberFormatter($region, NumberFormatter::CURRENCY );
                //$price_formated = $fmt->formatCurrency($price, $currency)."\n";
                $fmt->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, 0);
                $fmt->setAttribute(NumberFormatter::PADDING_POSITION, 3);
                $price_formated = $fmt->format($price, 0);
                //$price_formated = preg_replace('/.00$/', '', $price_formated);
                //$price_formated = preg_replace('/,00/', '', $price_formated);
                //$price_formated = preg_replace('/,/', ' ', $price_formated);
                if($currency != $local_currency) {
                    //$price_converted = convertToCurrencyWithGoogle($price, $currency, $local_currency);
                    $price_converted = convertToCurrencyWithGoogleV2($price, $currency, $local_currency);
                    //$price_converted = convertToCurrencyWithYahoo($price, $currency, $local_currency);
                    if($finalPrice) {
                        $fmt = new NumberFormatter($locale_region, NumberFormatter::PARSE_INT_ONLY );
                        $fmt->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, 0);
                        $fmt->setAttribute(NumberFormatter::ROUNDING_MODE, 6);
                        $price_converted = $fmt->format($price_converted, 0);
                        $price_formated = $price_converted;
                    } else {
                        $fmt = new NumberFormatter($locale_region, NumberFormatter::CURRENCY );
                        $fmt->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, 0);
                        $fmt->setAttribute(NumberFormatter::ROUNDING_MODE, 6);
                        $price_converted = $fmt->format($price_converted, 0);
                        $price_formated .= ' <em class="price_converted">[' . $price_converted . ']</em>';
                    }
                }
            }
            return $price_formated;
        }
    }*/

    if (!function_exists('formatPriceCurrency')) {
        function formatPriceCurrency($price, $country_id = '', $finalPrice = false, $showPriceConverted = true)
        {
            $locale_region = 'en-GB'; // default
            $locale_region = str_replace('-', '_', $locale_region);
            $locale_region .= '.utf-8';
            $region = $locale_region;
            setlocale(LC_MONETARY, $region);
            $my_local_settings = localeconv();
            $local_currency = trim($my_local_settings['int_curr_symbol']);
            $currency =  $local_currency; // default

            $CountryLocaleFull = [];
            if (empty($country_id)) {
                $currentCountryCode = !empty(config('app.country_code')) ? config('app.country_code') : 'uk';
                $_countryCode = (null !== config('youboat.' . $currentCountryCode . '.country_code')) ? config('youboat.' . $currentCountryCode . '.country_code') : 'GB';
                $CountryLocaleFull = Search::getCountryLocaleFull(mb_strtoupper($_countryCode));
            } else {
                $CountryLocaleFull = Search::getCountryLocaleFull($country_id);
            }

            if (!empty($CountryLocaleFull)) {
                $CountryLocales = array_key_exists('locales', $CountryLocaleFull) && !empty($CountryLocaleFull['locales']) ?  explode(',', $CountryLocaleFull['locales']) : '';
                $currency = array_key_exists('currency', $CountryLocaleFull) && !empty($CountryLocaleFull['currency']) ? $CountryLocaleFull['currency'] : $currency;

                //array_push($CountryLocales,str_replace('-', '_', $CountryLocales[0]).'.utf-8');
                list($region) = explode(',', $CountryLocaleFull['locales']);
            }
            $region = str_replace('-', '_', $region);
            $region .= '.utf-8';

            $price_formated = '';
            if (!empty($price)) {
                if (is_numeric($price)) {
                    setlocale(LC_MONETARY, $region);
                    $money_format = ($currency == 'EUR') ? '%= (#10.0i' : '%= (#10.0n';
                    //$money_format = '%= (#10.0n';
                    $price_formated = money_format($money_format, $price);
                    $price_formated = preg_replace('!\s+!', ' ', $price_formated);
                    $price_formated = trim($price_formated);

                    if ($currency != $local_currency) {
                        //$price_converted = convertToCurrencyWithGoogle($price, $currency, $local_currency);
                        $price_converted = convertToCurrencyWithGoogleV2($price, $currency, $local_currency);
                        //$price_converted = convertToCurrencyWithYahoo($price, $currency, $local_currency);
                        if ($finalPrice) {
                            $price_converted = preg_replace('!\s+!', ' ', $price_converted);
                            $price_converted = trim($price_converted);
                            $price_formated = number_format($price_converted, 0, '', '');
                        } else if ($showPriceConverted) {
                            setlocale(LC_MONETARY, $locale_region);
                            $money_format = '%= (#10.0n';
                            $price_converted = money_format($money_format, $price_converted);
                            $price_converted = preg_replace('!\s+!', ' ', $price_converted);
                            $price_converted = trim($price_converted);
                            $price_formated .= ' <em class="price_converted">[' . $price_converted . ']</em>';
                        } else {
                            setlocale(LC_MONETARY, $locale_region);
                            $money_format = '%= (#10.0n';
                            $price_converted = money_format($money_format, $price_converted);
                            $price_converted = preg_replace('!\s+!', ' ', $price_converted);
                            $price_converted = trim($price_converted);
                            $price_formated = $price_converted;
                        }
                    } /*else {
                        $price_formated = preg_replace('!\s+!', ' ', $price);
                        $price_formated = trim($price_formated);
                        if ($finalPrice && !is_string($price_formated)) {
                            $price_formated = number_format($price_formated, 0, '', '');
                        }
                    }*/
                } else {
                    $price_formated = preg_replace('!\s+!', ' ', $price);
                    $price_formated = trim($price_formated);
                    if ($finalPrice && !is_string($price_formated)) {
                        $price_formated = number_format($price_formated, 0, '', '');
                    }
                }
            }
            $price_formated = preg_replace('/EUR/', '&euro;', $price_formated);
            return $price_formated;
        }
    }

    if (!function_exists('formatPriceCurrencyByRegion')) {
        function formatPriceCurrencyByRegion($price, $region = '', $finalPrice = false)
        {
            $price_formated = '';
            if (!empty($price) &&!empty($region)) {
                if (is_numeric($price)) {
                    setlocale(LC_MONETARY, $region);
                    $money_format = ($currency == 'EUR') ? '%= (#10.0i' : '%= (#10.0n';
                    //$money_format = '%= (#10.0n';
                    $price_formated = money_format($money_format, $price);
                    $price_formated = preg_replace('!\s+!', ' ', $price_formated);
                    $price_formated = trim($price_formated);

                    if ($currency != $local_currency) {
                        //$price_converted = convertToCurrencyWithGoogle($price, $currency, $local_currency);
                        $price_converted = convertToCurrencyWithGoogleV2($price, $currency, $local_currency);
                        //$price_converted = convertToCurrencyWithYahoo($price, $currency, $local_currency);
                        if ($finalPrice) {
                            $price_converted = preg_replace('!\s+!', ' ', $price_converted);
                            $price_converted = trim($price_converted);
                            $price_formated = number_format($price_converted, 0, '', '');
                        } else {
                            setlocale(LC_MONETARY, $locale_region);
                            $money_format = '%= (#10.0n';
                            $price_converted = money_format($money_format, $price_converted);
                            $price_converted = preg_replace('!\s+!', ' ', $price_converted);
                            $price_converted = trim($price_converted);
                            $price_formated .= ' <em class="price_converted">[' . $price_converted . ']</em>';
                        }
                    } else {
                        if ($finalPrice) {
                            $price_converted = preg_replace('!\s+!', ' ', $price);
                            $price_converted = trim($price_converted);
                            $price_formated = number_format($price_converted, 0, '', '');
                        }
                    }
                } else {
                    $price_formated = preg_replace('!\s+!', ' ', $price);
                    $price_formated = trim($price_formated);
                    if ($finalPrice) {
                        $price_formated = number_format($price_formated, 0, '', '');
                    }
                }
            }
            $price_formated = preg_replace('/EUR/', '&euro;', $price_formated);
            return $price_formated;
        }
    }

    if(!function_exists('convertToCurrencyWithGoogle')) {
        function convertToCurrencyWithGoogle($amount, $currency_from, $currency_to){
            $url  = "https://www.google.com/finance/converter?a=$amount&from=$currency_from&to=$currency_to";
            $data = file_get_contents($url);
            preg_match("/<span class=bld>(.*)<\/span>/",$data, $converted);
            $converted = preg_replace("/[^0-9.]/", "", $converted[1]);
            return round($converted, 3);
        }
    }
    if(!function_exists('convertToCurrencyWithGoogleV2')) {
        function convertToCurrencyWithGoogleV2($amount, $currency_from, $currency_to) {
            $amount = urlencode($amount);
            $currency_from = urlencode($currency_from);
            $currency_to = urlencode($currency_to);

            $url = "http://www.google.com/finance/converter?a=$amount&from=$currency_from&to=$currency_to";

            $ch = curl_init();
            $timeout = 0;
            curl_setopt ($ch, CURLOPT_URL, $url);
            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);

            curl_setopt ($ch, CURLOPT_USERAGENT,
                "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
            curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $rawdata = curl_exec($ch);
            curl_close($ch);
            $data = explode('bld>', $rawdata);
            $data = explode($currency_to, $data[1]);
            return round($data[0], 2);
        }
    }
    if(!function_exists('convertToCurrencyWithYahoo')) {
        function convertToCurrencyWithYahoo($amount, $currency_from, $currency_to){
            $yql_base_url = "http://query.yahooapis.com/v1/public/yql";
            $yql_query = 'select * from yahoo.finance.xchange where pair in ("'.$currency_from.$currency_to.'")';
            $yql_query_url = $yql_base_url . "?q=" . urlencode($yql_query);
            $yql_query_url .= "&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys";

            $yql_session = curl_init();
            $timeout = 0;
            curl_setopt ($yql_session, CURLOPT_URL, $yql_query_url);
            curl_setopt($yql_session, CURLOPT_RETURNTRANSFER,true);
            curl_setopt ($yql_session, CURLOPT_USERAGENT,
                "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
            curl_setopt ($yql_session, CURLOPT_CONNECTTIMEOUT, $timeout);
            $yqlexec = curl_exec($yql_session);
            $yql_json =  json_decode($yqlexec,true);
            $currency_output = (float) $amount*$yql_json['query']['results']['rate']['Rate'];
            return $currency_output;
        }
    }

    if(!function_exists('getEffectiveUrl')) {
        function getEffectiveUrl($url)
        {
            // Version 4.x supported
            if (null === $url || '' === trim($url)) {
                //throw new Exception('The url to check must be a not empty string');
                //return false;
                return 'no link';
            }
            $ch = curl_init($url);
            if (false === $ch) {
                //throw new Exception('Fail to start Curl session');
                //return false;
                return 'no link';
            }
            //curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Must be set to true so that PHP follows any "Location:" header
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // grab Url
            $connectable = curl_exec($ch); // $connectable will contain all headers

            $url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL); // This is what you need, it will return you the last effective URL

            // close Curl resource, and free up system resources
            curl_close($ch);

            // Uncomment to see all headers
            /*
            echo "<pre>";
            print_r($connectable);echo"<br>";
            echo "</pre>";
            */

            return $url;
        }
    }

    if (!function_exists('unCacheView')) {
        // $template == '*' deletes ALL view caches
        // $template == 'home' will delete first match of cache for 'home.blade.php' or 'home.php'
        function unCacheView($template) {
            //$cachedViewsDirectory=app('path.storage').'/framework/views/';
            $cachedViewsDirectory=storage_path('framework/views');
            if($template=='*')
            {
                $files = glob($cachedViewsDirectory.'*');
                foreach($files as $file)
                {
                    if(is_file($file))
                    {
                        @unlink($file);
                    }
                }
            } else {
                $cacheKey=MD5(app('view.finder')->find($template));
                @unlink($cachedViewsDirectory.$cacheKey);
            }
        }
    }

    if (!function_exists('sslMail')) {
        function sslMail() {
            $transport = Swift_SmtpTransport::newInstance(
                \Config::get('mail.host'),
                \Config::get('mail.port'),
                \Config::get('mail.encryption')
            )
                ->setUsername(\Config::get('mail.username'))
                ->setPassword(\Config::get('mail.password'))
                ->setStreamOptions(['ssl' => \Config::get('mail.ssloptions')]);

            $mailer = Swift_Mailer::newInstance($transport);
            Mail::setSwiftMailer($mailer);
        }
    }

    if (! function_exists('cleanString')) {
        function cleanString($string, $toRemove = [])
        {
            $patternsToRemove = ['/Unknown/', '/N\/A/', '/\//', '/-/'];
            $patternsToRemove += $toRemove;
            $blank = array_fill(0, count($toRemove), '');
            return preg_replace($patternsToRemove, $blank, $string);
        }
    }

    if (! function_exists('generateStrongPassword')) {
        function generateStrongPassword($length = 9, $add_dashes = false, $available_sets = 'luds', $already_sets = [])
        {
            $sets = array();
            $sets = array_merge($sets, $already_sets);
            if(strpos($available_sets, 'l') !== false)
                $sets[] = 'abcdefghjkmnpqrstuvwxyz';
            if(strpos($available_sets, 'u') !== false)
                $sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
            if(strpos($available_sets, 'd') !== false)
                $sets[] = '23456789';
            if(strpos($available_sets, 's') !== false)
                $sets[] = '!@#$%&*?';
            $all = '';
            $password = '';
            foreach($sets as $set)
            {
                $password .= $set[array_rand(str_split($set))];
                $all .= $set;
            }
            $all = str_split($all);
            for($i = 0; $i < $length - count($sets); $i++)
                $password .= $all[array_rand($all)];
            $password = str_shuffle($password);
            if(!$add_dashes)
                return $password;
            $dash_len = floor(sqrt($length));
            $dash_str = '';
            while(strlen($password) > $dash_len)
            {
                $dash_str .= substr($password, 0, $dash_len);
                $password = substr($password, $dash_len);
            }
            $dash_str .= $password;
            return $dash_str;
        }
    }

    if (! function_exists('verifyEmail')) {
        function verifyEmail($toemail, $fromemail, $getdetails = false)
        {
            $result = '';
            $details = '';
            // Get the domain of the email recipient
            $email_arr = explode('@', $toemail);
            $domain = array_slice($email_arr, -1);
            $domain = $domain[0];

            // Trim [ and ] from beginning and end of domain string, respectively
            $domain = ltrim($domain, '[');
            $domain = rtrim($domain, ']');

            if ('IPv6:' == substr($domain, 0, strlen('IPv6:'))) {
                $domain = substr($domain, strlen('IPv6') + 1);
            }

            $mxhosts = array();
            // Check if the domain has an IP address assigned to it
            if (filter_var($domain, FILTER_VALIDATE_IP)) {
                $mx_ip = $domain;
            } else {
                // If no IP assigned, get the MX records for the host name
                getmxrr($domain, $mxhosts, $mxweight);
            }

            if (!empty($mxhosts)) {
                $mx_ip = $mxhosts[array_search(min($mxweight), $mxhosts)];
            } else {
                // If MX records not found, get the A DNS records for the host
                if (filter_var($domain, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                    $record_a = dns_get_record($domain, DNS_A);
                    // else get the AAAA IPv6 address record
                } elseif (filter_var($domain, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                    $record_a = dns_get_record($domain, DNS_AAAA);
                }

                if (!empty($record_a)) {
                    $mx_ip = $record_a[0]['ip'];
                } else {
                    // Exit the program if no MX records are found for the domain host
                    $result = 'invalid';
                    $details .= 'No suitable MX records found.';

                    return ((true == $getdetails) ? array($result, $details) : $result);
                }
            }

            // Open a socket connection with the hostname, smtp port 25
            $connect = @fsockopen($mx_ip, 25);

            if ($connect) {

                // Initiate the Mail Sending SMTP transaction
                if (preg_match('/^220/i', $out = fgets($connect, 1024))) {

                    // Send the HELO command to the SMTP server
                    fputs($connect, "HELO $mx_ip\r\n");
                    $out = fgets($connect, 1024);
                    $details .= $out."\n";

                    // Send an SMTP Mail command from the sender's email address
                    fputs($connect, "MAIL FROM: <$fromemail>\r\n");
                    $from = fgets($connect, 1024);
                    $details .= $from."\n";

                    // Send the SCPT command with the recepient's email address
                    fputs($connect, "RCPT TO: <$toemail>\r\n");
                    $to = fgets($connect, 1024);
                    $details .= $to."\n";

                    // Close the socket connection with QUIT command to the SMTP server
                    fputs($connect, 'QUIT');
                    fclose($connect);

                    // The expected response is 250 if the email is valid
                    if (!preg_match('/^250/i', $from) || !preg_match('/^250/i', $to)) {
                        $result = 'invalid';
                    } else {
                        $result = 'valid';
                    }
                }
            } else {
                $result = 'invalid';
                $details .= 'Could not connect to server';
            }
            if ($getdetails) {
                return array($result, $details);
            } else {
                return $result;
            }
        }
    }