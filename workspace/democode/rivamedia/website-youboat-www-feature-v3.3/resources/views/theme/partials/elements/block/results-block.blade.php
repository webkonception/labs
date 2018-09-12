@if (isset($block_format) && 'format-standard' === $block_format)
<?php
    $label = 'label-default';
    $btn_submit = 'btn-primary';
    if (!empty($ad_sell_type)) {
        if (preg_match("/new/i", $ad_sell_type)) {
            $label = 'label-success';
            $btn_submit = 'btn-success';
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
    <div class="result-item format-standard">
        <div class="result-item-image">
            <a href="{!! url($ad_url) !!}" class="media-box">
                @if ($url_image_ext && !preg_match("/broken/i", $url_image_ext))
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

            <div class="labels">
                @if (!empty($ad_year_built))
                <span class="label label-danger">{!! $ad_year_built !!}</span>
                @endif
                @if (!empty($ad_results_type_url))
                <a href="{!! url($ad_results_type_url) !!}" title="{!! trans('navigation.view_all_ads') !!} {!! $ad_type !!}" class="vehicle-body-type">
                    <span class="label label-primary">{!! $ad_type !!}</span>
                </a>
                @endif
                @if (!empty($ad_results_category_url))
                <a href="{!! url($ad_results_category_url) !!}" title="{!! trans('navigation.view_all_ads') !!} {!! $ad_category !!}">
                    <span class="label label-primary">{!! $ad_category !!}</span>
                </a>
                @endif
                @if (!empty($ad_results_subcategory_url))
                <a href="{!! url($ad_results_subcategory_url) !!}" title="{!! trans('navigation.view_all_ads') !!} {!! $ad_subcategory !!}">
                    <span class="label label-primary">{!! $ad_subcategory !!}</span>
                </a>
                @endif
            </div>
        </div>
        <div class="result-item-in flag {!! $ad_country_code !!}">
            <h4 class="result-item-title"><a href="{!! url($ad_url) !!}" title="{!! $ad_title !!}">{!! $ad_title !!}</a></h4>
            <div class="result-item-cont">
                @if (!empty($ad_description))
                <div class="result-item-block col1">
                    <p>{!! $ad_description !!}</p>
                </div>
                @endif
                <div class="result-item-block col2">
                    @if (!empty($ad_cost))
                    <div class="result-item-pricing">
                        <div class="price">{!! $ad_cost !!}</div>
                    </div>
                    @endif
                    {{--<div class="result-item-action-buttons">
                        --}}{{--<a href="#" class="btn btn-default btn-sm"><i class="fa fa-star-o"></i> Save</a>--}}{{--
                        <a href="{!! url($ad_url) !!}" title="{!! trans('navigation.enquire') !!} {!! $ad_title !!}" class="btn btn-lg {{$btn_submit}}">{!! trans('navigation.enquire') !!}<i class="fa fa-mouse-pointer"></i></a><br>
                        --}}{{--<a href="#" class="distance-calc"><i class="fa fa-map-marker"></i> Distance from me?</a>--}}{{--
                    </div>--}}
                </div>
            </div>
            <div class="result-item-features">
                <a href="{!! url($ad_url) !!}" title="{!! trans('navigation.view_details') !!} {!! trans('navigation.for') !!} {!! $ad_title !!}" class="view_details pull-right"><i class="fa fa-plus"></i> {!! trans('navigation.view_details') !!}</a>

                <ul class="inline">
                    @if (!empty($ad_location))
                        <li>{!! $ad_location !!}</li>
                    @endif
                    @if (!empty($ad_dealer))
                        <li>{!! trans('navigation.listed_by') !!} {!! $ad_dealer !!}</li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
@endif