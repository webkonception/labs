<?php
    $urlPrefix = 'for-sale';
    $urlPrefix = url(app('laravellocalization')->localizeURL($urlPrefix));
    //$subnavigation = 'manufacturers';
    $subnavigation = 'by_manufacturer';
    //$manufacturerslist = (isset($manufacturers) && count($manufacturers) > 0) ? $manufacturers : Navigation::getAdsManufacturersList(10);
    //$array = json_decode(json_encode($manufacturerslist), true);
    //$array = shuffle_with_keys($array, 0, 20, true);

    $manufacturerslist = Navigation::getAdsManufacturersList(20);

    $array = shuffle_with_keys($manufacturerslist, 0, 24, true);
    $manufacturer_url = '/' . trans('routes.' . str_replace('-', '_', $subnavigation));
?>
@if (isset($array) && count($array) > 0)
<div class="col-sm-12">
    <div class="row lgray-bg makers well">
        <div class="col-md-4 col-sm-5 text-center">
            <h3 class="title strong uppercase">{!! trans('searchbymanufacturer.title') !!}</h3>
            {{--{!! link_trans_route('for_sale', 'searchbymanufacturer.all_make_and_models', ['class'=>'btn btn-primary btn-lg']) !!}--}}
            {!! link_trans_route('for_sale', 'navigation.view_all_ads', ['class'=>'btn btn-danger btn-lg big']) !!}
            <div class="spacer-10"></div>
        </div>
        <div class="col-md-8 col-sm-7">
            <ul class="chevrons">
            @foreach ($array as $val_nav)
                <?php
                    list($rewrite_url_manufacturer, $count) = explode('#',$val_nav );

                    $url    = $urlPrefix . $manufacturer_url . '/' . str_slug($rewrite_url_manufacturer);
                    //$title  = ucfirst($rewrite_url_manufacturer). ' <span class="badge">' . $count .'</span>';
                    $title  = ucfirst($rewrite_url_manufacturer);
                ?>
                <li class=""><a href="{{ url($url) }}" title="{!! strip_tags($title) !!}">{!! title_case($title) !!}</a></li>
            @endforeach
            </ul>
        </div>
    </div>
</div>
@endif