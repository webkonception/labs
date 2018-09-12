
@extends(config('quickadmin.route') . '.layouts.master')

@section('content')

    <p>{!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.scrappingadsdetails.create', '<i class="fa fa-plus fa-fw"></i>Add new', [], array('class' => 'btn btn-success'))) !!}</p>

@if ($scrapping_ads_details->count())
    <div class="panel panel-success">
        <div class="panel-heading">
            <h3 class="panel-title">List</h3>
        </div>
        <div class="panel-body table-responsive">
            <table class="table table-striped table-hover datatable">
                <thead>
                    <tr>
                        {{--<th class="nosort">{!! Form::checkbox('delete_all', 1, false, ['class' => 'mass']) !!}</th>--}}
                        {{--<th>ad_price_descr</th>
                        <th>ad_phones</th>
                        <th>ad_ref</th>
                        <th>ad_description_full</th>
                        <th>ad_specifications_full</th>
                        <th>ad_specifications_caracts_values</th>
                        <th>ad_manufacturer_url</th>
                        <th>ad_sale_type_condition</th>
                        <th>ad_manufacturer_name</th>
                        <th>ad_title</th>
                        <th>ad_price</th>
                        <th>ad_type_cat_url</th>
                        <th>ad_sales_status</th>
                        <th>ad_location</th>
                        <th>ad_type_cat_name</th>
                        <th>ad_photos_thumbs</th>
                        <th>ad_model_name</th>
                        <th>ad_description_caracts_values</th>
                        <th>ad_mooring_country</th>
                        <th>ad_photo</th>
                        <th>ad_specifications_caracts</th>
                        <th>ad_width</th>
                        <th>ad_sale</th>
                        <th>ad_year_built</th>
                        <th>ad_dealer_name</th>
                        <th>ad_description_caracts</th>
                        <th>ad_length</th>
                        <th>ad_photos</th>
                        <th>ad_dealer_url</th>
                        <th>ad_description_caracts_labels</th>
                        <th>ad_pageUrl</th>
                        <th>ad_model_url</th>
                        <th>ad_nb_engines</th>
                        <th>ad_features_caracts</th>
                        <th>ad_features_caracts_categories</th>
                        <th>ad_features_full</th>
                        <th>ad_propulsion</th>--}}
                        <th class="nosort">Actions</th>
                        <th>Ref</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Category</th>

                        <th>Manufacturer</th>
                        {{--<th>ad_manufacturer_url</th>--}}

                        <th>Model</th>
                        {{--<th>ad_model_url</th>--}}

                        {{--<th>Price</th>--}}
                        {{--<th>ad_price_descr</th>--}}

                        <th>Location</th>
                        {{--<th>ad_mooring_country</th>--}}

                        <th>Dealer</th>
                        {{--<th>ad_dealer_url</th>--}}

                        {{--<th>Sale</th>--}}
                        <th>Sale type condition</th>
                        <th>Sales status</th>

                        {{--<th>Year built</th>--}}
                        <th>&nbsp;</th>
                    </tr>
                </thead>

                <tbody>
                @foreach ($scrapping_ads_details as $row)
                    <?php
                        $ad_referrer = $row->ad_referrer ?: '';
                        $ad_country_code = $row->ad_country_code ?: '';
                        $ad_pageUrl = $row->ad_pageUrl ?: '';

                        $ad_type = '';
                        $ad_category = '';
                        $ad_title_rewrite_url = '';
                        $ad_id = '';
                        $ad_type_cat_url = $row->ad_type_cat_url ?: '';

                        if(!empty($ad_type_cat_url)) {
                            $count = preg_match_all("/\//",$ad_type_cat_url);
                            if($count == 1) {
                                list($nothing, $ad_type) = explode("/", $ad_type_cat_url);
                            } else if ($count == 2) {
                                list($nothing, $ad_type, $ad_category) = explode("/", $ad_type_cat_url);
                            }
                        }
                    ?>
                    @if(!empty($ad_type))
                    @else
                    <?php
                        //if (preg_match("/http:/i", $ad_pageUrl)) {
                            //$ad_pageUrl = str_replace('http://'.$row->ad_referrer . '/', '', $ad_pageUrl);
                            /*
                            $pageUrl = parse_url($ad_pageUrl);
                            if($pageUrl) {
                                list($ad_type, $ad_title_rewrite_url, $ad_id) = explode("/", $pageUrl['path']);
                            }
                            */
                            $ad_pageUrl_cleaned = preg_replace("@^https?://[^/]+/@", "", $ad_pageUrl);
                            if(!empty($ad_pageUrl_cleaned)) {
                                list($ad_type, $ad_title_rewrite_url, $ad_id) = explode("/", $ad_pageUrl_cleaned);
                            }
                        //}
                    ?>
                    @endif
                    @if(!empty($ad_type))
                    <?php
                        $arrayFrom = ['Boat-Moorings','commercials','motorboats','motorboat','sailing','sailboat','Small-boats'];
                        $arrayReplace = ['pontoon-mooring','commercial-boats','power-boats','power-boats','sailing-boats','sailing-boats','ribs'];
                        $ad_type = str_replace($arrayFrom, $arrayReplace, $ad_type);
                    ?>
                    @endif
                    @if(!empty($ad_category))
                    <?php
                        //trailer
                        if (preg_match("/trailer/i", $ad_category) || preg_match("/boattrailer/i", $ad_category) || preg_match("/boat-trailer/i", $ad_category)) {
                            if('power-boats' == $ad_type || 'sailing-boats' == $ad_type ) {
                                $ad_category = $ad_type;
                                $ad_type = 'boat-trailers';
                            }
                        }
                        //jet-skis
                        if (preg_match("/jet-ski/i", $ad_category) || preg_match("/jetski/i", $ad_category)) {
                            if('power-boats' == $ad_type || 'ribs' == $ad_type) {
                                $ad_category = 'jet-skis';
                                $ad_type = 'pwc';
                            }
                        }
                        //house-boats
                        if (preg_match("/houseboat/i", $ad_category) || preg_match("/house-boat/i", $ad_category)) {
                            if('power-boats' == $ad_type || 'ribs' == $ad_type) {
                                $ad_category = '';
                                $ad_type = 'house-boats';
                            }
                        }
                        //other
                        if (preg_match("/Center-Consoles/i", $ad_category)) {
                            if('power-boats' == $ad_type || 'ribs' == $ad_type) {
                                $ad_type = 'other';
                            }
                        }
                        //-motorboats$
                        if (preg_match("/-motorboats$/i", $ad_category) || preg_match("/-motor-boats$/i", $ad_category)) {
                            if('power-boats' == $ad_type) {
                                $arrayFrom = ['-motorboats','-motor-boats'];
                                $ad_category = str_replace($arrayFrom, '', $ad_category);
                            }
                        }
                        //-sailboats$
                        if (preg_match("/-sailing-boats$/i", $ad_category) || preg_match("/-sailboats$/i", $ad_category) || preg_match("/-sailingboats$/i", $ad_category)) {
                            if('sailing-boats' == $ad_type) {
                                $arrayFrom = ['-sailing-boats','-sailboats','-sailingboats'];
                                $ad_category = str_replace($arrayFrom, '', $ad_category);
                            }
                        }
                        //dive
                        if (preg_match("/dive/i", $ad_category)) {
                            if('ribs' == $ad_type) {
                                $ad_category = 'diving-boats';
                            }
                        }
                        //^rib-
                        if (preg_match("/^rib-/i", $ad_category)) {
                            if('ribs' == $ad_type) {
                                $arrayFrom = ['rib-'];
                                $ad_category = str_replace($arrayFrom, 'ribs-', $ad_category);
                            }
                        }
                        //^taxi-
                        if (preg_match("/^taxi-/i", $ad_category)) {
                            if('commercial-boats' == $ad_type) {
                                $arrayFrom = ['taxi-'];
                                $ad_category = str_replace($arrayFrom, 'taxis-', $ad_category);
                            }
                        }
                        //-boat$
                        if (preg_match("/-boat$/i", $ad_category)) {
                            $arrayFrom = ['-boat'];
                            $ad_category = str_replace($arrayFrom, '-boats', $ad_category);
                        }
                        //-for-Sale$
                        if (preg_match("/-for-sale$/i", $ad_category)) {
                            $arrayFrom = ['-for-sale'];
                            $ad_category = str_replace($arrayFrom, '', $ad_category);
                        }

                        $ad_type = strtolower($ad_type);
                        $ad_category = strtolower($ad_category);
                    ?>
                    @endif

                    <?php
                        $ad_ref = $row->ad_ref ?: '';
                        $ad_title = $row->ad_title ?: '';
                        $ad_type_cat_name = $row->ad_type_cat_name ?: '';

                        $ad_manufacturer_name = $row->ad_manufacturer_name ?: '';
                        //$ad_manufacturer_url = $row->ad_manufacturer_url ?: '';

                        $ad_model_name = $row->ad_model_name ?: '';
                        if (!empty($ad_model_name)) {
                            //$ad_model_name = $row->ad_model_name;
                            list($ad_model_name) = explode(";", $ad_model_name);
                        }
                        //$ad_model_url = $row->ad_model_url;

                        //$ad_price = $row->ad_price;
                        //$ad_price = floatval(preg_replace('/[^\d.]/', '', $ad_price));
                        //$ad_price_descr = $row->ad_price_descr;
                    ?>

                    <?php
                        $ad_country = '';
                        $ad_region = '';
                        $ad_county = '';
                        $ad_city = '';
                        $ad_location = $row->ad_location ?: '';
                        $ad_location = str_replace(', ', ',', $ad_location);
                        if (!empty($ad_location)) {
                            //$ad_location = $row->ad_location;
                            $explode = explode(",", $ad_location);
                            if (count($explode) > 0) {
                                //list($region,$county,$country) = explode(";", $ad_location;
                                if(is_array($explode)) {
                                    $ad_country = array_pop($explode);
                                }
                                if(is_array($explode)) {
                                    $ad_county = array_pop($explode);
                                }
                                if(is_array($explode)) {
                                    $ad_region = array_pop($explode);
                                }
                            }
                        }

                        //$ad_mooring_country = $row->ad_mooring_country ?: '';
                    ?>

                    <?php
                        $ad_dealer_name = $row->ad_dealer_name ?: '';
                        //$ad_dealer_url = $row->ad_dealer_url ?: '';

                        //$ad_sale = $row->ad_sale ?: '';
                        $ad_sale_type_condition = $row->ad_sale_type_condition ?: '';
                        $ad_sale_type_condition = strtolower($ad_sale_type_condition);
                        $ad_sales_status = $row->ad_sales_status ?: '';
                        $ad_sales_status = strtolower($ad_sales_status);

                        $ad_year_built = $row->ad_year_built ?: '';
                    ?>

                    <tr>
                        {{--<td>{!! Form::checkbox('del-' . $row->id, 1, false, ['class'=>'single', 'data-id'=>$row->id]) !!}</td>--}}
                        <td>
                            {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.scrappingadsdetails.edit', '<i class="fa fa-pencil fa-fw"></i>Edit', [$row->id], ['class' => 'btn btn-block btn-xs btn-primary'])) !!}
                            @if($isAdmin)
                            {!! Form::open(array('class' => '', 'method' => 'DELETE', 'onsubmit' => 'return confirm(\'Confirm deletion\');',  'route' => array(config('quickadmin.route') . '.scrappingadsdetails.destroy', $row->id))) !!}
                            {!! Form::button('<i class="fa fa-trash-o fa-fw"></i>Delete', array('type' => 'submit', 'class' => 'btn btn-block btn-xs btn-danger btn-exception')) !!}
                            {!! Form::close() !!}
                            @endif
                        </td>

                        <td>{!! $ad_ref !!}</td>
                        <td>{!! $ad_title !!}</td>
                        <td>
                            @if(!empty($ad_type))
                            {!! $ad_type !!}
                            @endif
                        </td>
                        <td>
{{--                            {!! $ad_type_cat_name !!}--}}
                            @if(!empty($ad_category))
                                <br>
                                <br>
                                [{!! $ad_category !!}]
                            @endif
                        </td>

                        <td>{!! $ad_manufacturer_name !!}</td>
{{--                        <td>{!! $ad_manufacturer_url !!}</td>--}}

                        <td>{!! $ad_model_name !!}</td>
{{--                        <td>{!! $ad_model_url !!}</td>--}}

                        {{--<td>{!! $ad_price !!}<br>{!! $ad_price_descr !!}</td>--}}

                        <td>
                            {!! $ad_location !!}
                            <br>--
                            <br>
                            {!! $ad_country !!}
                            <br>
                            {!! $ad_region !!}
                            <br>
                            {!! $ad_county !!}
                        </td>
                        {{--<td>{!! $ad_mooring_country !!}</td>--}}

                        <td>{!! $ad_dealer_name !!}</td>
                        {{--<td>{!! $ad_dealer_url !!}</td>--}}

                        {{--<td>{!! $ad_sale !!}</td>--}}
                        <td>{!! $ad_sale_type_condition !!}</td>
                        <td>{!! $ad_sales_status !!}</td>

                        {{--<td>{!! $ad_year_built !!}</td>--}}
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{--<div class="row">
                <div class="col-xs-12">
                    <button class="btn btn-danger" id="delete">
                        <i class="fa fa-trash-o fa-fw"></i>Delete checked
                    </button>
                </div>
            </div>
            {!! Form::open(['route' => config('quickadmin.route') . '.scrappingadsdetails.massDelete', 'method' => 'post', 'id' => 'massDelete']) !!}
                <input type="hidden" id="send" name="toDelete">
            {!! Form::close() !!}--}}
        </div>
	</div>
@else
    <div class="panel panel-danger">
        <div class="panel-heading">
            <h3 class="panel-title">List</h3>
        </div>
        <div class="panel-body text-danger">
            No entries found.
        </div>
    </div>
@endif

@endsection

@section('javascript')
@stop