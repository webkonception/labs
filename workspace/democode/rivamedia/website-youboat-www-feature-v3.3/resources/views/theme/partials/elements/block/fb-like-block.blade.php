<?php
    $local_code = Search::getCountryLocaleCode(config('youboat.'. $country_code .'.country_code'))[1];
    $local_code = !empty($local_code) ? $local_code : 'en_GB';

    $fbAppId = !empty(config('youboat.'. $country_code .'.fbAppId')) ? config('youboat.'. $country_code .'.fbAppId') : '';

    $url = !empty($url) ? $url : URL::full();
    $action = !empty($action) ? $action : 'like'; // recommend
    $size = !empty($size) ? $size : 'small'; // large
    $layout = !empty($layout) ? $layout : 'standard'; //button_count
    $show_faces = !empty($show_faces) ? $show_faces : 'false';
    $share = !empty($share) ? $share : 'false';
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

<div class="fb-like"
     data-href="{!! $url !!}"
     data-size="{!! $size !!}"
     data-layout="{!! $layout !!}"
     data-action="{!! $action !!}"
     data-show-faces="{!! $show_faces !!}"
     data-share="{!! $share !!}"
>
</div>