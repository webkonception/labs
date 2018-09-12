<?php
    if (isset($form_action) && 'boat_on_demand' === $form_action) {
        $bod = true;
    }

    /*if(isset($_SERVER['argv'])) {
        debug('$_SERVER[\'argv\']');
        debug($_SERVER['argv']);
    }*/
    $showSearchNotificationForm = false;
    if (count($datasRequest)>0) {
        /*if (!empty($search_query) && empty($manufacturer['id'])) {
            $search = explode(' ', $search_query);
            if (is_array($search)) {
                $query = $search[0];
                //foreach ($search as $query) {
//                    $array = json_decode(json_encode($selltypes), true);
//                    if (search_array($query, $array)) {
//                        $sell_type = strtolower($query);
//                    }
//                    $array = json_decode(json_encode($adstypes), true);
//                    if (search_array($query, $array)) {
//                        $adstype = Search::getAdsType($query, true);
//                    }
//                    $array = json_decode(json_encode($categories), true);
//                    if (search_array($query, $array)) {
//                        $category = Search::getCategory($query, true);
//                    }
                    $array = json_decode(json_encode($manufacturers), true);

                    //if (search_array($query, $array)) {
                    if (preg_match_array($query, $array)) {
                        $manufacturer = Search::getManufacturer($query, true);
                        $manufacturer = Search::getGateWayManufacturerByName($query, true)[0];
                    } else {
                        $array = json_decode(json_encode($models), true);
                        if (preg_match_array($query, $array)) {
                            //$model = Search::getModel($query, true);
                            $model = Search::getGateWayModelByName($query, true)[0];
                        }
                    }
                //}
            }
        }*/

        $sell_type              = !empty($sell_type) ? $sell_type : (!empty($datasRequest['sell_type']) ? $datasRequest['sell_type'] : null);
        $adstype                = !empty($adstype) ? $adstype : (!empty($datasRequest['adstypes_id']) ? Search::getAdsTypeById($datasRequest['adstypes_id']) : null);
        $category               = !empty($category) ? $category : (!empty($datasRequest['categories_ids']) ? Search::getCategoryById($datasRequest['categories_ids']) : null);
        $subcategory            = !empty($subcategory) ? $subcategory : (!empty($datasRequest['subcategories_ids']) ? Search::getSubcategoryById($datasRequest['subcategories_ids']) : null);

        //$manufacturer           = !empty($manufacturer) ? $manufacturer : (!empty($datasRequest['manufacturers_id']) ? Search::getManufacturerById($datasRequest['manufacturers_id']) : null);
        $manufacturer           = !empty($manufacturer) ? $manufacturer : (!empty($datasRequest['manufacturers_id']) ? Search::getGateWayManufacturerByName($datasRequest['manufacturers_id'], true) : null);

        //@$manufacturerengine     = !empty($manufacturerengine) ? $manufacturerengine : (!empty($datasRequest['manufacturersengines_id']) ? Search::getManufacturerEngineById($datasRequest['manufacturersengines_id']) : null);

        $model                  = !empty($model) ? $model : (!empty($datasRequest['models_id']) ? Search::getModelById($datasRequest['models_id']) : null);
        //@$modelengine            = !empty($modelengine) ? $modelengine : (!empty($datasRequest['modelsengines_id']) ? Search::getModelEngineById($datasRequest['modelsengines_id']) : null);

        $boat_min_length        = !empty($boat_min_length) ? $boat_min_length : (!empty($datasRequest['min_length']) ? $datasRequest['min_length'] : null);
        $boat_max_length        = !empty($boat_max_length) ? $boat_max_length : (!empty($datasRequest['max_length']) ? $datasRequest['max_length'] : null);

        $boat_min_width         = !empty($boat_min_width) ? $boat_min_width : (!empty($datasRequest['min_width']) ? $datasRequest['min_width'] : null);
        $boat_max_width         = !empty($boat_max_width) ? $boat_max_width : (!empty($datasRequest['max_width']) ? $datasRequest['max_width'] : null);

        $min_year_built         = !empty($min_year_built) ? $min_year_built : (!empty($datasRequest['min_year_built']) ? $datasRequest['min_year_built'] : null);
        $max_year_built         = !empty($max_year_built) ? $max_year_built : (!empty($datasRequest['max_year_built']) ? $datasRequest['max_year_built'] : null);

        $min_ad_price           = !empty($min_ad_price) ? $min_ad_price : (!empty($datasRequest['min_ad_price']) ? $datasRequest['min_ad_price'] : null);
        $max_ad_price           = !empty($max_ad_price) ? $max_ad_price : (!empty($datasRequest['max_ad_price']) ? $datasRequest['max_ad_price'] : null);

        $country                = !empty($country) ? $country : (!empty($datasRequest['countries_id']) ? Search::getCountry($datasRequest['countries_id']) : Search::getCountry(config('youboat.'. $country_code .'.country_code'))['id']);

        $county_id              = !empty($county_id) ? $county_id : (!empty($datasRequest['county_id']) ? $datasRequest['county_id'] : null);

        $description            = !empty($description) ? $description : (!empty($datasRequest['description']) ? $datasRequest['description'] : null);
        $with_marina_berth      = !empty($with_marina_berth) ? $with_marina_berth : (!empty($datasRequest['with_marina_berth']) ? $datasRequest['with_marina_berth'] : '');
        $agree_similar          = !empty($agree_similar) ? $agree_similar : (!empty($datasRequest['agree_similar']) ? $datasRequest['agree_similar'] : '');

        $max                    = !empty($max) ? $max : (!empty($datasRequest['max']) ? $datasRequest['max'] : '20');
        $page                   = !empty($page) ? $page : (!empty($datasRequest['page']) ? $datasRequest['page'] : '1');
        $sort_by                = !empty($sort_by) ? $sort_by : (!empty($datasRequest['sort_by']) ? $datasRequest['sort_by'] : 'updated_at-desc');
        $results_view           = !empty($results_view) ? $results_view : (!empty($datasRequest['results_view']) ? $datasRequest['results_view'] : 'grid');

        $cookieName = 'search_notification';
        $cookieSearchNotificationValue = Cookie::get($cookieName);
        if(!empty($cookieSearchNotificationValue) && is_array($cookieSearchNotificationValue)) {
            foreach($cookieSearchNotificationValue as $k =>$v) {
                $cookie_manufacturer_name = $v['manufacturer_name'];
                $cookie_model_name = $v['model_name'];
                $manufacturer_name = !empty($manufacturer['name']) ? $manufacturer['name'] : '';
                $model_name = !empty($model['name']) ? $model['name'] : '';

                if ($cookie_manufacturer_name == $manufacturer_name && $cookie_model_name == $model_name) {
                    $showSearchNotificationForm = false;
                }
            }
        }
    } else {
        $country = Search::getCountry(config('youboat.'. $country_code .'.country_code'));
    }

    //@TODO : mutualize code $boat_locations for $districts & $counties
    $boat_locations = config('youboat.'. $country_code .'.locations');
    $boat_locations_counties = $boat_locations['counties'];

    $counties = [];
    foreach($boat_locations_counties as $key => $county) {
        $counties[$key] = $county;
    }

    //@if (isset($adstype['rewrite_url']) || isset($manufacturerengine['rewrite_url'])) {
    $label_txt_manufacturers = trans('filters.manufacturers') . '/' . trans('filters.shipyards');
    if (isset($adstype['rewrite_url'])) {
        //@if (preg_match('/engine/', $adstype['rewrite_url']) || isset($manufacturerengine['rewrite_url'])) {
        if (preg_match('/engine/', $adstype['rewrite_url'])) {
            $engines_inputs_disabled        = true;
            $not_engines_inputs_disabled    = false;
            $label_txt_manufacturers = trans('filters.manufacturers_engines');
        } else {
            $engines_inputs_disabled        = false;
            $not_engines_inputs_disabled    = true;
        }
    } else {
        $engines_inputs_disabled        = false;
        $not_engines_inputs_disabled    = false;
    }

?>
@if (isset($block_format) && 'format-standard' === $block_format)

@elseif (isset($block_format) && 'filters-sidebar' === $block_format)
    {!! Form::open(array('url'=>trans_route($currentLocale, 'routes.for_sale'), 'class'=>'form-horizontal', 'id'=>'form_filters', 'autocomplete'=>'off', 'method'=>'GET')) !!}
    @if (isset($adstypes))
    <?php
        $label_txt = trans('filters.adstypes');
        $attributes = [
            //'data-ajax--url'=>"/ajax-gateway_adstype",
            //'data-ajax--url' => LaravelLocalization::localizeURL('/ajax-gateway_adstype'),
            'data-ajax--url' => LaravelLocalization::localizeURL('/ajax-adstypes'),
            'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
            'data-placeholder' => '-- ' . trans('navigation.form_any') . ' --',
            'class' => 'form-control nosort',
            'id' => 'adstypes_id'
        ];

        $css_state = '';
        if (!count($adstypes) > 0) {
            $attributes['disabled'] = 'disabled';
            $css_state .= 'collapse ';
        }

        $addon = '';
        if (!empty($adstype['id'])) {
            $css_state = 'has-success';
        }
        $addon = '<span class="input-group-addon"><i class="fa fa-check"></i></span>';
    ?>
    <div class="form-group {!! $css_state !!}">
        <div class="col-sm-12">
            {!! Form::label('adstypes_id', $label_txt, ['class'=>'control-label']) !!}
            <div class="input-group">
                {!! Form::select('adstypes_id', $adstypes, !empty($adstype['id']) ? $adstype['id'] : old('adstypes_id'), $attributes) !!}
                {!! $addon !!}
            </div>
        </div>
    </div>
    @endif

    @if (isset($categories))
    <?php
        $label_txt = trans('filters.categories');
        $attributes = [
            //'data-ajax--url'=>"/ajax-gateway_category",
            //'data-ajax--url' => LaravelLocalization::localizeURL('/ajax-gateway_category'),
            'data-parent' => !empty($adstype['id']) ? $adstype['id'] : '',
            'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
            'data-placeholder' => '-- ' . trans('navigation.form_any') . ' --',
            'class' => 'form-control ',
            'id' => 'categories_ids'
        ];

        $css_state = '';
        if (!count($categories) > 0) {
            $attributes['disabled'] = 'disabled';
            $css_state .= 'collapse ';
        }

        $addon = '';
        if (!empty($category['id'])) {
            $css_state .= 'has-success';
        }
        $addon = '<span class="input-group-addon"><i class="fa fa-check"></i></span>';
    ?>
    <div class="form-group {!! $css_state !!}">
        <div class="col-sm-12">
            {!! Form::label('categories_ids', $label_txt, ['class'=>'control-label']) !!}
            <div class="input-group">
                {!! Form::select('categories_ids', $categories, !empty($category['id']) ? $category['id'] : old('categories_ids'), $attributes) !!}
                {!! $addon !!}
            </div>
        </div>
    </div>
    @endif

    @if (isset($subcategories))
    <?php
        $label_txt = trans('filters.subcategories');
        $attributes = [
            //'data-ajax--url'=>"/ajax-gateway_subcategory",
            //'data-ajax--url' => LaravelLocalization::localizeURL('/ajax-gateway_subcategory'),
            'data-parent' => !empty($category['id']) ? $category['id'] : '',
            'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
            'data-placeholder' => '-- ' . trans('navigation.form_any') . ' --',
            'class' => 'form-control ',
            'id' => 'subcategories_ids'
        ];

        $css_state = '';
        if (!count($subcategories) > 0) {
            $attributes['disabled'] = 'disabled';
            $css_state .= 'collapse ';
        }

        $addon = '';
        if (!empty($subcategory['id'])) {
            $css_state = 'has-success';
        }
        $addon = '<span class="input-group-addon"><i class="fa fa-check"></i></span>';
    ?>
    <div class="form-group subcategory {!! $css_state !!}">
        <div class="col-sm-12">
            {!! Form::label('subcategories_ids', $label_txt, ['class'=>'control-label']) !!}
            <div class="input-group">
                {!! Form::select('subcategories_ids', $subcategories, !empty($subcategory['id']) ? $subcategory['id'] : old('subcategories_ids'), $attributes) !!}
                {!! $addon !!}
            </div>
        </div>
    </div>
    @endif

    @if (isset($manufacturers))
    <?php
        /*if(!empty($manufacturers)) {
            $array = json_decode(json_encode($manufacturers), true);
            asort($array);
            $manufacturers = $array;
        }*/
        $label_txt = $label_txt_manufacturers;
        $attributes = [
            //'data-ajax--url' => '/ajax-gateway_manufacturer',
            //'data-ajax--url' => '/ajax-manufacturer',
            'data-ajax--url' => LaravelLocalization::localizeURL('/ajax-manufacturer'),
            'data-placeholder' => $label_txt,
            'placeholder' => $label_txt,
            'class' => 'form-control select2',
            'id' => 'manufacturers_id'
        ];

        $css_state = '';
        if (!count($manufacturers) > 0) {
            $attributes['disabled'] = 'disabled';
            $css_state .= 'collapse ';
        }
        $addon = '';
        if (!empty($manufacturer['id'])) {
            $css_state = 'has-success';
        }
        $addon = '<span class="input-group-addon"><i class="fa fa-check"></i></span>';
    ?>
    <div class="form-group manufacturer {!! $css_state !!}">
        <div class="col-sm-12">
            {!! Form::label('manufacturers_id', $label_txt, ['class'=>'control-label']) !!}
            <div class="input-group">
                {!! Form::select('manufacturers_id', !empty($manufacturer['id']) ? [$manufacturer['id']=>$manufacturer['name']] : [], !empty($manufacturer['id']) ? $manufacturer['id'] : old('manufacturers_id'), $attributes) !!}
                {!! $addon !!}
            </div>
        </div>
    </div>
    @endif

    @if (isset($models))
    <?php
        if(!empty($models)) {
            $array = json_decode(json_encode($models), true);
            asort($array);
            $models = $array;
        }
        $models_id = old('models_id', isset($model['id']) ? $model['id'] : '');
        $label_txt = trans('filters.models');
        $attributes = [
            'data-parent' => !empty($manufacturer['id']) ? $manufacturer['id'] : '',
            'data-placeholder' => $label_txt,
            'placeholder' => $label_txt,
            'class' => 'form-control',
            'id' => 'models_id'
        ];

        $css_state = '';
        //if (!count($models) > 0 && !isset($model['id'])) {
        //if (!count($models) > 0) {
        if (!count($models) > 0 && !isset($models_id)) {
            $attributes['disabled'] = 'disabled';
            $css_state .= 'collapse ';
        }

        $addon = '';
        if (!empty($model['id'])) {
            $css_state = 'has-success';
            //$attributes['data-selected'] = $model['id'];
        }
        $addon = '<span class="input-group-addon"><i class="fa fa-check"></i></span>';
    ?>
    <div class="form-group model {!! $css_state !!}">
        <div class="col-sm-12">
            {!! Form::label('models_id', $label_txt, ['class'=>'control-label']) !!}
            <div class="input-group">
                {{--{!! Form::select('models_id', !empty($model['id']) ? [$model['id']=>$model['name']] : [], !empty($model['id']) ? $model['id'] : old('models_id'), $attributes) !!}--}}
                {!! Form::select('models_id', $models, $models_id, $attributes) !!}
                {!! $addon !!}
            </div>
        </div>
    </div>
    @endif
    @if (isset($ad_prices))
        <?php
        $prices = [];
        foreach ($ad_prices as $key => $value) {
            //$prices[$key] = $key . ' (' . $value['count'] . ')';
            //$prices[$key] = $key;
            $prices[$key] = $value['price'];
            $prices_formatted[$key] = $value['price_formatted'];
        }
        $label_txt = 'Min. ' . trans('filters.price');
        $attributes = [
                'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                'data-placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                'class' => 'form-control ',
                'id' => 'min_ad_price'
        ];

        $css_state = '';
        if (!count($ad_prices) > 0) {
            $attributes['disabled'] = 'disabled';
            $css_state .= 'collapse ';
        }

        $addon = '';
        if (!empty($min_ad_price)) {
            $css_state = 'has-success';
        }
        $addon = '<span class="input-group-addon"><i class="fa fa-check"></i></span>';
        ?>
        <div class="col-xs-6">
            <div class="form-group {!! $css_state !!}">
                {!! Form::label('min_ad_price', $label_txt, ['class'=>'control-label']) !!}
                <div class="input-group">
                    {!! Form::select('min_ad_price', $prices_formatted, !empty($min_ad_price) ? $min_ad_price : old('min_ad_price'), $attributes) !!}
                    {!! $addon !!}
                </div>
                {{--<input type="range" name="min_ad_price" id="min_ad_price" min="{!! array_values($prices)[0] !!}" max="{!! end($prices) !!}" step="1" value="{!! !empty($min_ad_price) ? $min_ad_price : old('min_ad_price') !!}">--}}
            </div>
        </div>
        <?php
        $label_txt = 'Max. ' . trans('filters.price');
        $attributes = [
                'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                'data-placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                'class' => 'form-control ',
                'id' => 'max_ad_price'
        ];

        $css_state = '';
        if (!count($ad_prices) > 0) {
            $attributes['disabled'] = 'disabled';
            $css_state .= 'collapse ';
        }

        $addon = '';
        if (!empty($max_ad_price)) {
            $css_state = 'has-success';
        }
        $addon = '<span class="input-group-addon"><i class="fa fa-check"></i></span>';
        ?>
        <div class="col-xs-6">
            <div class="form-group {!! $css_state !!}">
                {!! Form::label('max_ad_price', $label_txt, ['class'=>'control-label']) !!}
                <div class="input-group">
                    {!! Form::select('max_ad_price', array_reverse($prices_formatted,true), !empty($max_ad_price) ? $max_ad_price : old('max_ad_price'), $attributes) !!}
                    {!! $addon !!}
                </div>
                {{--<input type="range" name="max_ad_price" id="max_ad_price" min="{!! end(array_reverse($prices,true)) !!}" max="{!! array_values(array_reverse($prices,true))[0] !!}" step="1" value="{!! !empty($max_ad_price) ? $max_ad_price : old('max_ad_price') !!}">--}}
            </div>
        </div>
    @endif

    @if (isset($years_built))
    <?php
        $years = [];
        foreach ($years_built as $key => $value) {
            //$years[$key] = $key . ' (' . $value['count'] . ')';
            $years[$key] = $key;
        }

        $label_txt = 'Min. ' . trans('filters.year_built');
        $attributes = [
                'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                'data-placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                'class' => 'form-control ',
                'id' => 'min_year_built'
        ];

        $css_state = '';
        if (!count($years_built) > 0) {
            $attributes['disabled'] = 'disabled';
            $css_state .= 'collapse ';
        }

        $addon = '';
        if (!empty($min_year_built)) {
            $css_state = 'has-success';
        }
        $addon = '<span class="input-group-addon"><i class="fa fa-check"></i></span>';
    ?>
    <div class="col-xs-6">
        <div class="form-group {!! $css_state !!}">
            {!! Form::label('min_year_built', $label_txt, ['class'=>'control-label']) !!}
            <div class="input-group">
                {!! Form::select('min_year_built', $years, !empty($min_year_built) ? $min_year_built : old('min_year_built'), $attributes) !!}
                {!! $addon !!}
            </div>
        </div>
    </div>
    <?php
        $label_txt = 'Max. ' . trans('filters.year_built');
        $attributes = [
            'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
            'data-placeholder' => '-- ' . trans('navigation.form_any') . ' --',
            'class' => 'form-control ',
            'id' => 'max_year_built'
        ];

        $css_state = '';
        if (!count($years_built) > 0) {
            $attributes['disabled'] = 'disabled';
            $css_state .= 'collapse ';
        }

        $addon = '';
        if (!empty($max_year_built)) {
            $css_state = 'has-success';
        }
        $addon = '<span class="input-group-addon"><i class="fa fa-check"></i></span>';
    ?>
    <div class="col-xs-6">
        <div class="form-group {!! $css_state !!}">
            {!! Form::label('max_year_built', $label_txt, ['class'=>'control-label']) !!}
            <div class="input-group">
                {!! Form::select('max_year_built', array_reverse($years,true), !empty($max_year_built) ? $max_year_built : old('max_year_built'), $attributes) !!}
                {!! $addon !!}
            </div>
        </div>
    </div>
    @endif

    <?php
    $label_txt = trans('filters.min_length') . ' (m)';
    $attributes = [
        'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
        'data-placeholder' => '-- ' . trans('navigation.form_any') . ' --',
        'class' => 'form-control nosort ',
        'id' => 'min_length'
    ];

    $css_state = '';
    $addon = '';
    if (!empty($boat_min_length)) {
        $css_state = 'has-success';
    }
    $addon = '<span class="input-group-addon"><i class="fa fa-check"></i></span>';
    ?>
    <div class="col-xs-6">
        <div class="form-group {!! $css_state !!}">
            {!! Form::label('min_length', $label_txt, ['class'=>'control-label']) !!}
            <div class="input-group">
                {!! Form::selectRange('min_length', 1, 16, !empty($boat_min_length) ? $boat_min_length : old('min_length'), $attributes) !!}
                {!! $addon !!}
            </div>
            {{--<input type="range" name="min_length_range" id="min_length_range" min="0" max="14" step="1" value="0">--}}
        </div>
    </div>
    <?php
        $label_txt = trans('filters.max_length') . ' (m)';
        $attributes = [
            'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
            'data-placeholder' => '-- ' . trans('navigation.form_any') . ' --',
            'class' => 'form-control nosort ',
            'id' => 'max_length'
        ];

        $css_state = '';
        $addon = '';
        if (!empty($boat_max_length)) {
            $css_state = 'has-success';
        }
        $addon = '<span class="input-group-addon"><i class="fa fa-check"></i></span>';
    ?>
    <div class="col-xs-6">
        <div class="form-group {!! $css_state !!}">
            {!! Form::label('max_length', $label_txt, ['class'=>'control-label']) !!}
            <div class="input-group">
                {!! Form::selectRange('max_length', 16, 1,  !empty($boat_max_length) ? $boat_max_length : old('max_length'), $attributes) !!}
                {!! $addon !!}
            </div>
            {{--<input type="range" name="max_length_range" id="max_length_range" min="0" max="14" step="1" value="0">--}}
        </div>
    </div>

    <?php
        $label_txt = trans('filters.min_width') . ' (m)';
        $attributes = [
                'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                'data-placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                'class' => 'form-control nosort ',
                'id' => 'min_width'
        ];

        $css_state = '';
        $addon = '';
        if (!empty($boat_min_width)) {
            $css_state = 'has-success';
        }
        $addon = '<span class="input-group-addon"><i class="fa fa-check"></i></span>';
    ?>
    <div class="col-xs-6">
        <div class="form-group {!! $css_state !!}">
            {!! Form::label('min_width', $label_txt, ['class'=>'control-label']) !!}
            <div class="input-group">
                {!! Form::selectRange('min_width', 1, 6, !empty($boat_min_width) ? $boat_min_width : old('min_width'), $attributes) !!}
                {!! $addon !!}
            </div>
            {{--<input type="range" name="min_width_range" id="min_width_range" min="0" max="14" step="1" value="0">--}}
        </div>
    </div>
    <?php
        $label_txt = trans('filters.max_width') . ' (m)';
        $attributes = [
                'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                'data-placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                'class' => 'form-control nosort ',
                'id' => 'max_width'
        ];

        $css_state = '';
        $addon = '';
        if (!empty($boat_max_width)) {
            $css_state = 'has-success';
        }
        $addon = '<span class="input-group-addon"><i class="fa fa-check"></i></span>';
    ?>
    <div class="col-xs-6">
        <div class="form-group {!! $css_state !!}">
            {!! Form::label('max_width', $label_txt, ['class'=>'control-label']) !!}
            <div class="input-group">
                {!! Form::selectRange('max_width', 6, 1,  !empty($boat_max_width) ? $boat_max_width : old('max_width'), $attributes) !!}
                {!! $addon !!}
            </div>
            {{--<input type="range" name="max_width_range" id="max_width_range" min="0" max="14" step="1" value="0">--}}
        </div>
    </div>

    @if (isset($countries))
    <?php
        $label_txt = ucfirst(trans('validation.attributes.country'));
        $attributes = [
            //'data-ajax--url'=>"/ajax-country",
            'data-ajax--url' => LaravelLocalization::localizeURL('/ajax-country'),
            'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
            'data-placeholder' => '-- ' . trans('navigation.form_any') . ' --',
            'class' => 'form-control ',
            'id' => 'countries_id'
        ];
        if (!count($adstypes) > 0) {
            $attributes['disabled'] = 'disabled';
        }

        $css_state = '';
        $addon = '';
        if (!empty($country['id'])) {
            $css_state = 'has-success';
        }
        $addon = '<span class="input-group-addon"><i class="fa fa-check"></i></span>';
    ?>
    <div class="form-group {!! $css_state !!}">
        <div class="col-sm-12">
            {!! Form::label('countries_id', $label_txt, ['class'=>'control-label']) !!}
            <div class="input-group">
                {!! Form::select('countries_id', $countries, !empty($country['id']) ? $country['id'] : old('countries_id'), $attributes) !!}
                {!! $addon !!}
            </div>
        </div>
    </div>
    @endif

    @if (isset($selltypes))
    <?php
        $label_txt = trans('filters.sell_type');
        $attributes = [
            'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
            'data-placeholder' => '-- ' . trans('navigation.form_any') . ' --',
            'class' => 'form-control ',
            'id' => 'sell_type'
        ];

        $css_state = '';
        if (!count($selltypes) > 0) {
            $attributes['disabled'] = 'disabled';
            $css_state .= 'collapse ';
        }

        $addon = '';
        if (!empty($sell_type)) {
            $css_state = 'has-success';
        }
        $addon = '<span class="input-group-addon"><i class="fa fa-check"></i></span>';
    ?>
    <div class="col-xs-6">
        <div class="form-group {!! $css_state !!}">
            {!! Form::label('sell_type', $label_txt, ['class'=>'control-label']) !!}
            <div class="input-group">
                {!! Form::select('sell_type', $selltypes, !empty($sell_type) ? $sell_type : old('sell_type'), $attributes) !!}
                {!! $addon !!}
            </div>
        </div>
    </div>
    @endif


    {!! Form::hidden('currency', config('youboat.'. $country_code .'.currency')) !!}
    @if (!empty($search_query)){!! Form::hidden('query', $search_query, ['id'=>'query']) !!}@endif
    @if (!empty($max)){!! Form::hidden('max', $max, ['id'=>'max']) !!}@endif
    @if (!empty($page)){!! Form::hidden('page', $page, ['id'=>'page']) !!}@endif
    @if (!empty($sort_by)){!! Form::hidden('sort_by', $sort_by, ['id'=>'sort_by']) !!}@endif
    @if (!empty($results_view)){!! Form::hidden('results_view', $results_view, ['id'=>'results_view']) !!}@endif

    <div class="clearfix"></div>

    <div class="form-group">
        <div class="col-xs-6 text-center">
            {{--{!! Form::button('<i class="fa fa-btn fa-refresh fa-fw"></i>' . trans('navigation.reset'), ['type' => 'reset', 'class' => 'btn btn-sm btn-default']) !!}--}}
        </div>
        <div class="col-xs-12 col-sm-6 col-sm-offset-6 col-md-12 col-md-offset-0 text-center">
            {!! Form::button('<i class="fa fa-btn fa-search fa-fw"></i>' . trans('navigation.search'), ['type' => 'submit', 'class' => 'btn btn-block btn-primary']) !!}
        </div>
    </div>

    {!! Form::close() !!}

    <hr>
    @if ($showSearchNotificationForm)
    @if (!empty($datasRequest['manufacturers_id']) || !empty($datasRequest['models_id']) && $showSearchNotificationForm)
    <div class="row">
        <div class="col-sm-12">
            @include('theme.partials.elements.search.search-notification')
        </div>
    </div>
    <hr>
    @endif
    @endif
@endif