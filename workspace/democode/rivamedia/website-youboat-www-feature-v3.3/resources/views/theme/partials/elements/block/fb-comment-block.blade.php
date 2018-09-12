<?php
    $local_code = Search::getCountryLocaleCode(config('youboat.'. $country_code .'.country_code'))[1];
    $local_code = !empty($local_code) ? $local_code : 'en_GB';

    $fbAppId = !empty(config('youboat.'. $country_code .'.fbAppId')) ? config('youboat.'. $country_code .'.fbAppId') : '';

    $url = !empty($url) ? $url : URL::full();
?>
<div id="fb-root"></div>
<script>
    (function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/{!! $local_code !!}/sdk.js#xfbml=1&version=v2.8&appId={!! $fbAppId !!}";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>
<div class="fb-comments"
     data-href="{!! $url !!}"
     data-numposts="{!! !empty($numposts) ? $numposts : '5' !!}"
     data-colorscheme="{!! !empty($colorscheme) ? $colorscheme : 'light' !!}"
     data-width="{!! !empty($width) ? $width : '100%' !!}"
     data-order-by="{!! !empty($order_by) ? $order_by : 'social' !!}"
     {{--« social » (social), « reverse_time » (antichronologique) ou « time » (chronologique)--}}
></div>