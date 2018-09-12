@if (isset($block_format) && 'format-standard' === $block_format)
<?php
    $label = 'label-default';
    $btn_submit = 'btn-primary';
    if (!empty($ad_sell_type)) {
        if (preg_match("/new/i", $ad_sell_type)) {
            $label = 'label-success';
            $btn_submit = 'btn-danger';
        } else if (preg_match("/used/i", $ad_sell_type)) {
            $label = 'label-warning';
        } else if (preg_match("/damaged/i", $ad_sell_type)) {
            $label = 'label-danger';
        }
    }

    if(preg_match("/^(http|https):\/\//i", $ad_img_src)) {
        $ad_img_params = ['ad_id'=>$ad_id, 'ad_title'=>$ad_title, 'image_name'=>'photo-0'];
        $referrer = preg_match("/^(http|https):\/\//i", $ad_img_src) ? '' : 'http://' . $ad_referrer;
        $url_image_ext = url_image_ext($referrer, $ad_img_src, 'photos/' . $country_code . '/', $ad_img_params);
    } else {
        $url_image_ext = $ad_img_src;
    }
?>
    <div class="vehicle-block format-standard flag {!! $ad_country_code !!}">
        <a href="{!! url($ad_url) !!}" class="media-box">
            @if ($url_image_ext)
                {!! image(preg_replace("/^\/\//", '/', thumbnail($url_image_ext, 277, null, true, false)), $ad_title) !!}
            @else
                {{--<span class="default-img">
                    {!! image('assets/img/picture-broken-link.png', $ad_title, ['class'=>'block-center', 'width'=>'277']) !!}
                </span>--}}
                <div class="default-img text-center">
                    <br>
                    <span class="fa-stack fa-lg fa-4x">
                        <i class="fa fa-camera fa-stack-1x"></i>
                        <i class="fa fa-ban fa-stack-2x"></i>
                    </span>
                </div>
            @endif
        </a>
        @if (!empty($ad_sell_type))
        <span class="label {{ $label }} vehicle-age">{!! $ad_sell_type !!}</span>
        @endif
        @if ($ad_premium_listing)
        <span class="label label-success premium-listing">{!! trans('navigation.premium_listing') !!}</span>
        @endif
        <h5 class="vehicle-title text-center"><a href="{!! url($ad_url) !!}" title="{!! $ad_title !!}">{!! str_limit($ad_title, 64) !!}</a></h5>
        <p class="vehicle-meta text-center">
            {!! $ad_meta !!}
            @if (!empty($ad_dealer))
            <br>
            {!! trans('navigation.listed_by') !!} <span class="user-type">{!! $ad_dealer !!}</span>
            @endif
        </p>

        @if (!empty($ad_cost))
        <p class="lead strong accent-color-danger vehicle-price text-center">{!! $ad_cost !!}</p>
        @endif

        <p class="text-center">
            @if (!empty($ad_results_type_url))
            <a href="{!! url($ad_results_type_url) !!}" title="{!! trans('navigation.view_all_ads') !!} {!! $ad_type !!}" class="vehicle-body-type">
                <span class="label label-primary">{!! $ad_type !!}</span>
            </a>&nbsp;
            @endif
            @if (!empty($ad_results_category_url))
            <a href="{!! url($ad_results_category_url) !!}" title="{!! trans('navigation.view_all_ads') !!} {!! $ad_category !!}" class="vehicle-body-type">
                <span class="label label-primary">{!! $ad_category !!}</span>
            </a>&nbsp;
            @endif
            @if (!empty($ad_results_subcategory_url))
            <a href="{!! url($ad_results_subcategory_url) !!}" title="{!! trans('navigation.view_all_ads') !!} {!! $ad_subcategory !!}" class="vehicle-body-type">
                <span class="label label-primary">{!! $ad_subcategory !!}</span>
            </a>
            @endif
        </p>
    </div>
@endif