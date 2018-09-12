<?php
    $local_code = Search::getCountryLocaleCode(config('youboat.'. $country_code .'.country_code'))[1];
    $local_code = !empty($local_code) ? $local_code : 'en_GB';

    $fbAppId = !empty(config('youboat.'. $country_code .'.fbAppId')) ? config('youboat.'. $country_code .'.fbAppId') : '';

    $url = !empty($url) ? $url : config('youboat.'. $country_code .'.facebook');
?>
<h4><i class="fa fa-facebook"></i> {!! trans('elements.connect-with-us.facebook_feed') !!}</h4>
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
<div class="fb-page"
     data-href="{!! $url !!}"
     data-tabs="timeline"
     {{--data-width="480"--}}
     data-small-header="true"
     data-adapt-container-width="true"
     data-hide-cover="true"
     data-show-facepile="false">
        <blockquote cite="{!! $url !!}" class="fb-xfbml-parse-ignore">
                <a href="{!! $url !!}">{!! $website_name !!}</a>
        </blockquote>
</div>