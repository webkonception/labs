<?php
    $metas_title = '';
    $metas_description = '';

    $url_image_ext = '';
    $dealer_name = '';
    $dealer_address = '';
    $map = false;

    if(!empty($dealer_caracts)) {
        $photo = !empty($dealer_caracts['photo']) ? $dealer_caracts['photo'] : '';
        $rewrite_url = !empty($dealer_caracts['rewrite_url']) ? $dealer_caracts['rewrite_url'] : '';
        $label_txt = ucfirst(trans('validation.attributes.photo'));

        $image_name = 'logo';
        $dealer_country_id = !empty($dealer_caracts['country_id']) ? $dealer_caracts['country_id'] : '';

        $dealer_country = Search::getCountryById($dealer_country_id, false);
        $dealer_country_code = '';
        if (is_array($dealer_country) && array_key_exists('code', $dealer_country)) {
            $dealer_country_code = $dealer_country['code'];
        }
        $dealer_country = Search::getCountryById($dealer_country_id);
        $dealer_country_name = '';
        if (is_array($dealer_country) && array_key_exists('name', $dealer_country)) {
            $dealer_country_name = $dealer_country['name'];
        }

        $targetDir = 'photos/dealers/' . $dealer_country_code . '/';

        if (!empty($photo) && preg_match("/^http/", $photo)) {
            $ad_img_params = ['ad_id'=>'dealer', 'ad_title'=>$rewrite_url, 'image_name'=>$image_name];
            $url_image_ext = url_image_ext('', $photo, $targetDir, $ad_img_params);
        } else {
            $filename_dest = $_SERVER['DOCUMENT_ROOT'] . $photo;
            if (file_exists($filename_dest)) {
                $url_image_ext = $photo;
            }
        }

        $denomination = !empty($dealer_caracts['denomination']) ? $dealer_caracts['denomination'] : '';

        $firstname = !empty($dealer_caracts['firstname']) ? ucfirst(mb_strtolower($dealer_caracts['firstname'])) : '';
        $dealer_name = !empty($dealer_caracts['name']) ? (!empty($firstname) ? $firstname . ' ' . mb_strtoupper($dealer_caracts['name']) : mb_strtoupper($dealer_caracts['name'])) : '';
        if(empty($denomination)) {
            $denomination = $dealer_name;
            $dealer_name = '';
        }

        $dealer_address .= !empty($dealer_caracts['address']) ? $dealer_caracts['address'] : '';
        $semicolon = !empty($dealer_address) ? ', ' : '';

        $dealer_address .= !empty($dealer_caracts['address_more']) ? $semicolon . $dealer_caracts['address_more'] : '';
        $semicolon = !empty($dealer_address) ? ', ' : '';

        $dealer_address .= !empty($dealer_caracts['zip']) ? $semicolon . $dealer_caracts['zip'] : '';
        $semicolon = !empty($dealer_address) ? ', ' : '';

        $dealer_address .= !empty($dealer_caracts['city']) ? $semicolon . $dealer_caracts['city'] : '';
        $semicolon = !empty($dealer_address) ? ', ' : '';

        $dealer_address .= !empty($dealer_caracts['province']) ? $semicolon . $dealer_caracts['province'] : '';
        $semicolon = !empty($dealer_address) ? ', ' : '';

        $dealer_address .= !empty($dealer_caracts['region']) ? $semicolon . $dealer_caracts['region'] : '';
        $semicolon = !empty($dealer_address) ? ', ' : '';

        $dealer_address .= !empty($dealer_caracts['subregion']) ? $semicolon . $dealer_caracts['subregion'] : '';
        $semicolon = !empty($dealer_address) ? ', ' : '';

        $dealer_address .= !empty($dealer_country_name) ? $semicolon . $dealer_country_name : '';

        $phone_1 = !empty($dealer_caracts['phone_1']) ? $dealer_caracts['phone_1'] : '';
        $phone_2 = !empty($dealer_caracts['phone_2']) ? $dealer_caracts['phone_2'] : '';
        $phone_3 = !empty($dealer_caracts['phone_3']) ? $dealer_caracts['phone_3'] : '';
        $phone_mobile = !empty($dealer_caracts['phone_mobile']) ? $dealer_caracts['phone_mobile'] : '';
        $fax = !empty($dealer_caracts['fax']) ? $dealer_caracts['fax'] : '';

        $twitter = !empty($dealer_caracts['twitter']) ? $dealer_caracts['twitter'] : '';
        $facebook = !empty($dealer_caracts['facebook']) ? $dealer_caracts['facebook'] : '';

        $website_url = !empty($dealer_caracts['website_url']) ? $dealer_caracts['website_url'] : '';
        $rewrite_url = !empty($dealer_caracts['rewrite_url']) ? $dealer_caracts['rewrite_url'] : '';

        $opening_time = !empty($dealer_caracts['opening_time']) ? $dealer_caracts['opening_time'] : '';
        $legal_informations = !empty($dealer_caracts['legal_informations']) ? $dealer_caracts['legal_informations'] : '';

        $duns = !empty($dealer_caracts['duns']) ? $dealer_caracts['duns'] : '';
        $company_number = !empty($dealer_caracts['company_number']) ? $dealer_caracts['company_number'] : '';
        $siret = !empty($dealer_caracts['siret']) ? $dealer_caracts['siret'] : '';
        $ape = !empty($dealer_caracts['ape']) ? $dealer_caracts['ape'] : '';
        $vat = !empty($dealer_caracts['vat']) ? $dealer_caracts['vat'] : '';


        $metas_title = $denomination . ' - ' . trans('navigation.' . $view_name);
        $metas_description = str_limit($legal_informations, 300);

        if(!empty($dealer_address)) {
            $tab_zoom = [6,7,10,14,14];
            $map = true;
            $map_address = preg_replace('/\s+/', ' ', trim($dealer_address));
            $index = count(explode(', ', $map_address))-1;
            //var_dump(explode(', ', $map_address));
            if($index> count($tab_zoom)-1) {
                $index = count($tab_zoom)-1;
            }
            $zoom = $tab_zoom[$index];
            //var_dump($index);
            //var_dump(in_array(ucwords(mb_strtolower($map_address)), $countries->toArray()));
            if($index == 0 && !in_array(ucwords(mb_strtolower($map_address)), $countries->toArray())) {
                $zoom = end($tab_zoom);
            }
        }

        if ($ads_list) {
            $ads_list = json_decode(json_encode($ads_list), true)['ads_list']['data'];
        }
    }
    $metas = [
            'metas_title' => $metas_title . ' - ' . $website_name,
            'metas_description' => $metas_description
        //,'metas_keywords' => $metas_keywords
    ];

?>
@extends('layouts.theme')

@section('metas')
    @include('theme.partials.elements.block.metas-block', $metas)
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                @if(!empty($dealer_caracts))
                <div class="well">

                    <div class="row">

                        @if(!empty($denomination))
                            <div class="col-sm-6">
                                <h2 class="uppercase strong accent-color">{!! $denomination !!}</h2>
                            </div>
                        @endif

                        @if (!empty($url_image_ext))
                            <div class="col-sm-6 text-center">
                                {!! image(preg_replace('/^\/\//', '/', $url_image_ext), $denomination, ['class'=>'img-responsive img-thumbnail'])!!}
                            </div>
                        @endif

                    </div>

                    @if(!empty($dealer_name))
                        <div class="row">
                            <div class="col-sm-12">
                                {!! $dealer_name !!}
                            </div>
                        </div>
                    @endif

                    <section class="well well-white">

                        @if(!empty($dealer_address))
                            <div class="row">

                                <div class="col-sm-6">
                                    <strong class="text-primary">{!! ucfirst(trans('validation.attributes.address')) !!}:</strong>
                                    &nbsp;
                                    {!! $dealer_address !!}
                                </div>

                            </div>
                            <hr>
                        @endif

                        @if(!empty($website_url))
                            <div class="row">

                                @if(!empty($website_url))
                                    <div class="col-sm-12">
                                        <strong class="text-primary">Website url:</strong>
                                        &nbsp;
                                        {!! $website_url !!}
                                    </div>
                                @endif

                            </div>
                            <hr>
                        @endif

                        <div class="row">

                            @if(!empty($phone_1))
                                <div class="col-sm-6">
                                    <strong class="text-primary">{!! ucfirst(trans('validation.attributes.phone')) !!}:</strong>
                                    &nbsp;
                                    {!! $phone_1 !!}
                                </div>
                            @endif

                            @if(!empty($phone_2))
                                <div class="col-sm-6">
                                    <strong class="text-primary">{!! ucfirst(trans('validation.attributes.phone')) !!}:</strong>
                                    &nbsp;
                                    {!! $phone_2 !!}
                                </div>
                            @endif

                            @if(!empty($phone_3))
                                <div class="col-sm-6">
                                    <strong class="text-primary">{!! ucfirst(trans('validation.attributes.phone')) !!}:</strong>
                                    &nbsp;
                                    {!! $phone_3 !!}
                                </div>
                            @endif

                            @if(!empty($phone_mobile))
                                <div class="col-sm-6">
                                    <strong class="text-primary">{!! ucfirst(trans('validation.attributes.mobile')) !!}:</strong>
                                    &nbsp;
                                    {!! $phone_mobile !!}
                                </div>
                            @endif

                            @if(!empty($fax))
                                <div class="col-sm-6">
                                    <strong class="text-primary">{!! ucfirst(trans('validation.attributes.fax')) !!}:</strong>
                                    &nbsp;
                                    {!! $fax !!}
                                </div>
                            @endif

                        </div>

                        @if(!empty($twitter) || !empty($facebook))
                            <hr>
                            <div class="row">

                                @if(!empty($twitter))
                                    <div class="col-sm-6">
                                        <strong class="text-primary">Twitter:</strong>
                                        &nbsp;
                                        {!! $twitter !!}
                                    </div>
                                @endif

                                @if(!empty($facebook))
                                    <div class="col-sm-6">
                                        <strong class="text-primary">Facebook:</strong>
                                        &nbsp;
                                        {!! $facebook !!}
                                    </div>
                                @endif

                            </div>
                        @endif

                        @if(!empty($opening_time) || !empty($legal_informations))
                            <hr>
                            <div class="row">

                                @if(!empty($opening_time))
                                    <div class="col-sm-6">
                                        <strong class="text-primary">Opening time:</strong>
                                        <br>
                                        <blockquote>{!! nl2br($opening_time, false) !!}</blockquote>
                                    </div>
                                @endif

                                @if(!empty($opening_time))
                                    <div class="col-sm-6">
                                        <strong class="text-primary">Legal informations:</strong>
                                        <br>
                                        <blockquote>{!! $legal_informations !!}</blockquote>
                                    </div>
                                @endif

                            </div>
                        @endif

                        @if(!empty($duns) || !empty($company_number) || !empty($siret) || !empty($ape) || !empty($vat))
                            <div class="row">

                                @if(!empty($duns))
                                    <div class="col-sm-6">
                                        <strong class="text-primary">Duns:</strong>
                                        &nbsp;
                                        {!! $duns !!}
                                    </div>
                                @endif

                                @if(!empty($company_number))
                                    <div class="col-sm-6">
                                        <strong class="text-primary">Company number:</strong>
                                        &nbsp;
                                        {!! $company_number !!}
                                    </div>
                                @endif

                                @if(!empty($siret))
                                    <div class="col-sm-6">
                                        <strong class="text-primary">Siret:</strong>
                                        &nbsp;
                                        {!! $siret !!}
                                    </div>
                                @endif

                                @if(!empty($ape))
                                    <div class="col-sm-6">
                                        <strong class="text-primary">Ape:</strong>
                                        &nbsp;
                                        {!! $ape !!}
                                    </div>
                                @endif

                                @if(!empty($vat))
                                    <div class="col-sm-6">
                                        <strong class="text-primary">Vat:</strong>
                                        &nbsp;
                                        {!! $vat !!}
                                    </div>
                                @endif

                            </div>
                        @endif

                        @if($map)
                            <div class="clearfix">
                                <div id="ad-location">
                                    <div id="map" class="text-center">{!! trans('show_ad_detail.loading_map_text') !!}</div>
                                </div>
                            </div>
                        @endif
                    </section>
                </div>
                @if (isset($ads_list) && is_array($ads_list) && count($ads_list) > 0)
                <div class="row">
                    <div class="col-md-12">
                        @include('theme.partials.elements.recent-ads', ['data_columns'=>3, 'data_items_desktop'=>3, 'data_items_desktop_small'=>2,
                        'ads_list'=>$ads_list, 'ads_title_block' => trans('navigation.dealer_ads')])
                    </div>
                </div>
                @endif
                @else
                <div class="alert alert-danger">
                    {!! trans('errors/404.error_detail') !!}
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    @if ($map)
        <script defer>
            $(document).ready(function () {

                if ('undefined' != typeof $('#map')) {

                    var w = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;

                    var myMarker = null;

                    // start coordinates
                    var myLatlng = new google.maps.LatLng(-34.397, 150.644);

                    var styles = [
                        {
                            featureType: "all",
                            stylers: [
                                { saturation: -80 }
                            ]
                        },{
                            featureType: "road.arterial",
                            elementType: "geometry",
                            stylers: [
                                { hue: "#0A4B73" },
                                { saturation: 50 }
                            ]
                        },{
                            featureType: "poi.business",
                            elementType: "labels",
                            stylers: [
                                { visibility: "off" }
                            ]
                        }
                    ];

                    // map options
                    var myOptions = {
                        zoom: {!! $zoom !!},
                        center: myLatlng,
                        mapTypeId: google.maps.MapTypeId.ROADMAP,
                        disableDefaultUI: false,
                        draggable: true,
                        zoomControl: true,
                        mapTypeControl: true,
                        scaleControl: true,
                        streetViewControl: true,
                        rotateControl: true,
                        types: ['establishment'],
                        styles : styles
                    };

                    // If this is in responsive mobile, disable zoom, scroll, drag and double click zoom
                    // -> This effectively makes the map static on mobile
                    if (w <= 768)
                    {
                        // allow zoom control
                        myOptions.zoomControl = true;

                        // change zoom control's position
                        myOptions.zoomControlOptions = {
                            position: google.maps.ControlPosition.TOP_RIGHT
                        };

                        // get rid of everything else
                        myOptions.scrollWheel = false;
                        myOptions.scaleControl = false;
                        myOptions.streetViewControl = false;
                        myOptions.rotateControl = false;
                        myOptions.draggable = false;
                        myOptions.disableDoubleClickZoom = true;
                    } else {
                        // change zoom control's position
                        myOptions.zoomControlOptions = {
                            position: google.maps.ControlPosition.RIGHT_BOTTOM
                        };
                    }

                    var myMap = new google.maps.Map(
                            document.getElementById('map'),
                            myOptions
                    );

                    var myPanel    = document.getElementById('panel');

                    // Creating Pin Icon
                    var myImage = '/assets/img/marker_port_blue.png';
                    var myMarkerImage = new google.maps.MarkerImage(myImage, null, null, new google.maps.Point(0, 64), new google.maps.Size(64, 64));

                    // geocoding adress
                    var GeocoderOptions = {
                        'address' : '{!! addslashes(trim($map_address)) !!}',
                        'region' : '{!! strtoupper($dealer_country_code) !!}'
                    };

                    function GeocodingResult( results , status ) {
                        // result ok
                        if( status == google.maps.GeocoderStatus.OK ) {

                            // remove existing marker
                            if(myMarker != null) {
                                myMarker.setMap(null);
                            }

                            // create new marker for the address
                            myMarker = new google.maps.Marker({
                                position: results[0].geometry.location,
                                map: myMap,
                                icon: myMarkerImage,
                                title: "{!! addslashes($denomination) !!}"
                            });

                            // center view on this marker
                            myMap.setCenter(results[0].geometry.location);
                        }

                    }

                    var myGeocoder = new google.maps.Geocoder();
                    myGeocoder.geocode( GeocoderOptions, GeocodingResult );


                    var direction = new google.maps.DirectionsRenderer({
                        map   : myMap,
                        panel : myPanel
                    });

                    var calculate = function(direction){
                        origin      = document.getElementById('origin').value;
                        destination = document.getElementById('destination').value;
                        if(origin && destination){
                            var request = {
                                origin      : origin,
                                destination : destination,
                                travelMode  : google.maps.DirectionsTravelMode.DRIVING // Driving Mode
                            }
                            var directionsService = new google.maps.DirectionsService();
                            directionsService.route(request, function(response, status){
                                //console.log(response);
                                if(status == google.maps.DirectionsStatus.OK){
                                    direction.setDirections(response);
                                }
                            });
                        }
                    };

                    $('#calculate_route').on('click', function(event) {
                        event.preventDefault();
                        calculate(direction);
                    });
                }
            });
        </script>
    @endif
@endsection
