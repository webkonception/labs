<!-- The file upload form used as target for the file upload widget -->
<?php
if(isset($_SESSION['current_ad_id'])) {
    unset($_SESSION['current_ad_id']);
    session_destroy();
} else {
    session_name("fileupload");
    session_start();
    $_SESSION['current_ad_id'] = $ad_id;
}
$host = '';
$custom_dir = !empty($custom_dir) ? $custom_dir : '/uploads/' . (!empty($ad_id) ? $ad_id . '/' : '');
$website_youboat_url = !empty($website_name) ? $website_name : config('youboat.' . $country_code . '.website_youboat_url');
?>
<div id="fileupload" data-url="/server/php/" data-custom_dir="{!! $custom_dir !!}"  data-host="{!! $host !!}">
    <div class="row fileupload-buttonbar">
        <div class="col-sm-7">
            <div class="col-sm-12">
                <!-- The fileinput-button span is used to style the file input field as button -->
                  <span class="btn btn-success fileinput-button">
                      <i class="glyphicon glyphicon-plus"></i>
                      <span>Add files...</span>
                      <input type="file" name="files[]" multiple>
                  </span>
                <button type="button" id="btn_upload" class="btn btn-primary start btn-exception">
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>Start upload</span>
                </button>
                <button type="reset" id="btn_reset" class="btn btn-warning cancel btn-exception">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel upload</span>
                </button>
                <button type="button" id="btn_delete" class="btn btn-danger delete btn-exception">
                    <i class="glyphicon glyphicon-trash"></i>
                    <span>Delete</span>
                </button>
                <input type="checkbox" class="toggle">
                <!-- The global file processing state -->
                <span class="fileupload-process"></span>
            </div>
            <!-- The global progress state -->
            <div class="col-sm-12 fileupload-progress fade">
                <!-- The global progress bar -->
                <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                </div>
                <!-- The extended global progress state -->
                <div class="progress-extended">&nbsp;</div>
            </div>
        </div>
        <div class="col-sm-5">
            <div id="dropzone" class="fade well">
                <i class="fa fa-files-o fa-3x" aria-hidden="true"></i>
                Drop files here
                <i class="fa fa-download fa-3x" aria-hidden="true"></i>
            </div>
        </div>
    </div>
    <div id="upload_container" class="row">
        <!-- The container for the uploaded files -->
        <div class="col-xs-12">
            {{--<table role="presentation" class="table table-striped files"><tbody></tbody></table>--}}
            <div role="presentation" id="photos" class="ui-sortable"></div>
        </div>
    </div>
    <!-- The blueimp Gallery widget -->
    <div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls" data-filter=":even">
        <div class="slides"></div>
        <h3 class="title"></h3>
        <a class="prev">‹</a>
        <a class="next">›</a>
        <a class="close">×</a>
        <a class="play-pause"></a>
        <ol class="indicator"></ol>
    </div>
</div>
<?php
if(is_array($ad_photos)) {
    $existing_files = [];
    $root = '';
    $host = '';
    $public_path = !empty($root) ? str_replace(['youboat-www_boatgest', 'boatgest-youboat'] , [$root, $root], public_path()) : public_path();

    foreach(array_unique($ad_photos) as $key => $url) {

        $url_image = '';
        $url_image_thumb = '';
        $url_image_ext = '';
        $referrer = '';

        if(!preg_match("/^(http|https):\/\//i", $url)) {
            $url = $website_youboat_url . $url;
        }

        if(!empty($url)) {
            $srcUrl = preg_replace("@^(https|http)?://[^/]+/@", "", $url);
            $srcUrl = preg_replace("/^\//i", "", $srcUrl);

            $referrer = !empty($host) ? $host : 'http://' . $ad_referrer;
            if(file_exists($public_path . '/' . $srcUrl)) {
                if(!empty($host)) {
                    $url_image_ext = $referrer . '/' . $srcUrl;
                } else {
                    $url_image_ext = asset('/' . $srcUrl);
                }
                //
               //var_dump($url_image_ext);
            } else {
                $pathinfo = pathinfo($srcUrl);
                //$basename = $pathinfo['basename'];
                //$filename = $pathinfo['filename'];
                $extension = $pathinfo['extension'];

                $image_name = 'photo-' . $key;
                $filename = '/assets/' . $targetDir . '/' . $image_name . '.' . $extension;

                $img_params = ['ad_id'=>$ad_id, 'ad_title'=>$ad_title, 'image_name'=>$image_name, 'sub_dir'=>'', 'force'=>false];
                //$img_params = ['ad_id'=>$ad_id, 'ad_title'=>$ad_title, 'image_name'=>$image_name, 'sub_dir'=>'', 'force'=>false, 'public_path'=>$public_path];

                if(!empty($locale) && !empty($currentLocale)) {
                    $img_params['header_language'] = 'Accept-Language: ' . $locale .',' . $currentLocale . ';q=0.8';
                }

                //$referrer = preg_match("/^(http|https):\/\//i", $url) ? '' : 'http://' . $ad_referrer;
               if(preg_match("/^(http|https):\/\//i", $url)) {
                    $url_image_ext = url_image_ext('', $url, $targetDir, $img_params);
                }
               //var_dump($url_image_ext);
               //var_dump('$public_path . $filename');
               //var_dump($public_path . $filename);
                //if(empty($url_image_ext) && file_exists($public_path . asset($filename))) {
                if(empty($url_image_ext) && file_exists($public_path . $filename)) {
                    //$url_image_ext = asset($filename);
                    $url_image_ext = $referrer . '/' . $filename;
                }
               //var_dump($url_image_ext);
            }
        }
        if(!empty($url_image_ext)) {
            // generate local files
            $url_image = thumbnail($url_image_ext, "100%", "100%", false, false, false);
            //$url_image = thumbnail($url_image_ext, "100%", "100%", false, false, false, ['public_path'=>$public_path]);

            $url_image_thumb = thumbnail($url_image_ext, 170, 114, false, false, false);
            //$url_image_thumb = thumbnail($url_image_ext, 170, 114, false, false, false, ['public_path'=>$public_path]);

            $url = $url_image_ext;
            $parse_url = parse_url($url);
            $path = $parse_url['path'];
            $filename = $_SERVER['DOCUMENT_ROOT'] . $path;

            $pathinfo = pathinfo($path);

            $name = $pathinfo['basename'];
            $path = $pathinfo['dirname'];
            //$deleteUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/server/php/?file=' . $name;
            $deleteUrl = '/server/php/?file=' . $name;
            $thumbnailUrl = preg_replace('/' . $name . '/', '', $url) . 'thumbnail/' . $name;

            $size = filesize($filename);
            //$size = '';

            $existing_files[$key]= [
                    'deleteType' => "DELETE",
                    'deleteUrl' => $deleteUrl,
                    'name' => $name,
                    'path' => $path,
                    'size' => $size,
                    'thumbnailUrl' => $thumbnailUrl,
                    'url' => $url,
                    'error' => null
            ];
        }
    }
    if(count($existing_files)>0) {
        $script = '<script>';
        $script .= 'var existing_files = ' . json_encode(['files' => $existing_files]) .';';
        $script .= '</script>';
        echo $script;
    }
} else {
    $script = '<script>';
    $script .= 'var existing_files = "";';
    $script .= '</script>';
    echo $script;
}
?>

<div class="modal fade" id="modal-progress" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Uploading...</h4>
            </div>
            <div class="modal-body">
                <div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: 0%;">0%</div>
                </div>
            </div>
        </div>
    </div>
</div>
