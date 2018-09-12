<?php
    //\Debugbar::disable();

    ini_set('max_execution_time', 360); // Maximum execution time of each script, in seconds (I CHANGED THIS VALUE)
    ini_set('max_input_time', 120); // Maximum amount of time each script may spend parsing request data
    //ini_set('max_input_nesting_level', 64); // Maximum input variable nesting level
    ini_set('memory_limit', '512M'); // Maximum amount of memory a script may consume (128MB by default)
    //ini_set('memory_limit', '-1');

    set_time_limit (0);

    $label_txt = ucfirst(trans('validation.attributes.photo'));
    $placeholder = trans('navigation.form_enter_placeholder');
    $css_state = '';
    if (!empty($input_value)) {
        $css_state = 'has-success';
    }
    if ($errors->has($input_name)) {
        $css_state = 'has-error';
    }

    $ad_photos = '';
    if (!empty($input_value)) {
        if(is_array($input_value)) {
            $ad_photos = $input_value;
        } else {
            $ad_photos = array_filter(explode(';', $input_value));
        }
    }
    $title = isset($ad_title) ? str_slug(mb_strtolower($ad_title), '-') : '';
    $targetDir = 'photos/' . $ad_country_code . '/' . $ad_id . '_' . $title;
    //$targetDir = 'photos/' . $ad_country_code . '/' . $ad_id;

?>
    <div class="clearfix gallery">
        <h3>Gallery</h3>

        @include('boatgest-admin.partials.upload-form', ['ad_id'=>$ad_id, 'host'=>config('youboat.' . $country_code . '.website_youboat_url'), 'root'=>config('youboat.' . $country_code . '.root'), 'custom_dir'=>'/assets/' . $targetDir . '/', 'ad_photos'=>$ad_photos, 'targetDir'=>$targetDir])

    </div>

<?php
  unset(
    $label_txt, $placeholder, $css_state,
    $input_value, $input_name,
    $ad_photos,
    $title, $ad_title, $key,
    $ad_photo, $srcUrl, $url_image, $url_image_thumb, $url_image_ext, $pathinfo, $extension,
    $targetDir, $image_name, $filename, $img_params, $referrer, $attributes, $additionalImage, $inputsAdditionalImages
  );
?>
