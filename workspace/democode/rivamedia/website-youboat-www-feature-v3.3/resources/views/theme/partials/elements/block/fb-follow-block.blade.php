<?php
    $local_code = Search::getCountryLocaleCode(config('youboat.'. $country_code .'.country_code'))[1];
    $local_code = !empty($local_code) ? $local_code : 'en_GB';

    $fbAppId = !empty(config('youboat.'. $country_code .'.fbAppId')) ? config('youboat.'. $country_code .'.fbAppId') : '';

    $url = !empty($url) ? $url : config('youboat.'. $country_code .'.facebook');
    $size = !empty($size) ? $size : 'small'; // large
    $layout = !empty($layout) ? $layout : 'standard'; //button_count
    $show_faces = !empty($show_faces) ? $show_faces : 'false';
?>
<div id="fb-root"></div>
<script>
    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/{!! $local_code !!}/sdk.js#xfbml=1&version=v2.8&appId={!! $fbAppId !!}";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>
<div class="fb-follow"
     data-href="{!! $url !!}"
     data-layout="{!! $layout !!}"
     data-size="{!! $size !!}"
     data-show-faces="{!! $show_faces !!}"
></div>