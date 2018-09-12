<?php
    if (!empty($datasRequest) && count($datasRequest)>0) {
        $sell_type              = isset($sell_type) ? $sell_type : (!empty($datasRequest['sell_type']) ? $datasRequest['sell_type'] : null);
        $adstype                = isset($adstype) ? $adstype : (!empty($datasRequest['adstypes_id']) ? Search::getAdsTypeById ($datasRequest['adstypes_id']) : null);
        $category               = isset($category) ? $category : (!empty($datasRequest['categories_ids']) ? Search::getCategoryById ($datasRequest['categories_ids']) : null);
        $subcategory            = isset($subcategory) ? $subcategory : (!empty($datasRequest['subcategories_ids']) ? Search::getSubcategoryById ($datasRequest['subcategories_ids']) : null);

        $manufacturer           = isset($manufacturer) ? $manufacturer : (!empty($datasRequest['manufacturers_id']) ? Search::getManufacturerById ($datasRequest['manufacturers_id']) : null);

        $model                  = isset($model) ? $model : (!empty($datasRequest['models_id']) ? Search::getModelById ($datasRequest['models_id']) : null);

        $boat_min_length        = isset($boat_min_length) ? $boat_min_length : (!empty($datasRequest['min_length']) ? $datasRequest['min_length'] : null);
        $boat_max_length        = isset($boat_max_length) ? $boat_max_length : (!empty($datasRequest['max_length']) ? $datasRequest['max_length'] : null);

        $boat_min_width         = isset($boat_min_width) ? $boat_min_width : (!empty($datasRequest['min_width']) ? $datasRequest['min_width'] : null);
        $boat_max_width         = isset($boat_max_width) ? $boat_max_width : (!empty($datasRequest['max_width']) ? $datasRequest['max_width'] : null);

        $min_year_built         = isset($min_year_built) ? $min_year_built : (!empty($datasRequest['min_year_built']) ? $datasRequest['min_year_built'] : null);
        $max_year_built         = isset($max_year_built) ? $max_year_built : (!empty($datasRequest['max_year_built']) ? $datasRequest['max_year_built'] : null);

        $min_ad_price           = isset($min_ad_price) ? $min_ad_price : (!empty($datasRequest['min_ad_price']) ? $datasRequest['min_ad_price'] : null);
        $max_ad_price           = isset($max_ad_price) ? $max_ad_price : (!empty($datasRequest['max_ad_price']) ? $datasRequest['max_ad_price'] : null);

        $budget                 = isset($budget) ? $budget : (!empty($datasRequest['budget']) ? $datasRequest['budget'] : null);
        $country                = isset($country) ? $country : (!empty($datasRequest['countries_id']) ? Search::getCountry ($datasRequest['countries_id']) : Search::getCountry(config('youboat.'. $country_code .'.country_code'))['id']);
        $region                 = isset($region) ? $region : (!empty($datasRequest['regions_id']) ? $datasRequest['regions_id'] : null);
        $county                 = isset($county) ? $county : (!empty($datasRequest['counties_id']) ? $datasRequest['counties_id'] : null);

        $description            = isset($description) ? $description : (!empty($datasRequest['description']) ? $datasRequest['description'] : null);
        $with_marina_berth      = isset($with_marina_berth) ? $with_marina_berth : (!empty($datasRequest['with_marina_berth']) ? $datasRequest['with_marina_berth'] : null);
        $agree_similar          = isset($agree_similar) ? $agree_similar : (!empty($datasRequest['agree_similar']) ? $datasRequest['agree_similar'] : null);
    } else {
        $country = Search::getCountry(config('youboat.'. $country_code .'.country_code'));
    }

    //@TODO : mutualize code $boat_locations for $regions & $counties
    $boat_locations = config('youboat.'. $country_code .'.locations');
    $boat_locations_regions = $boat_locations['regions'];
    $boat_locations_counties = $boat_locations['counties'];

    $regions = [];
    foreach($boat_locations_regions as $key => $val_region) {
        $regions[$key] = $val_region['name'];
    }

    $counties = [];
    foreach($boat_locations_counties as $key => $val_county) {
        $counties[$key] = $val_county;
    }

    $label_txt_manufacturers = trans('filters.manufacturers') . '/' . trans('filters.shipyards');
    if (isset($adstype['rewrite_url'])) {
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

    <h4 class="accent-color">{!! trans('boat_on_demand.features_title') !!}</h4>

    <section class="row">
        @if (isset($adstypes))
        <?php
            $label_txt = trans('filters.adstypes');
            $attributes = [
                'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                'data-placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                'class' => 'form-control nosort',
                'id' => 'adstypes_id'
            ];
            $attributes['required'] = 'required';
            $css_state = '';
            if (!count($adstypes) > 0) {
                $attributes['disabled'] = 'disabled';
                $css_state .= 'collapse ';
            }

            if (!empty($adstype['id'])) {
                $css_state = 'has-success';
            }
            if ($errors->has('adstypes_id')) {
                $css_state = 'has-error';
            }
        ?>
        <div class="adstypes form-group col-sm-6 {!! $css_state !!}">
            {!! Form::label('adstypes_id', $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label']) !!}
            <div class="col-xs-12 col-sm-7">
                <div class="input-group">
                    {!! Form::select('adstypes_id', $adstypes, !empty($adstype['id']) ? $adstype['id'] : old('adstypes_id'), $attributes) !!}
                </div>
            </div>
        </div>
        @endif
    </section>

    <section class="row">
        @if (isset($categories))
        <?php
            $label_txt = trans('filters.categories');
            $attributes = [
                'data-ajax--url'=>"categories",
                'data-parent' => !empty($adstype['id']) ? $adstype['id'] : '',
                'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                'data-placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                'class' => 'form-control',
                'id' => 'categories_ids'
            ];
            $css_state = '';
            if (!count($categories) > 0) {
                $attributes['disabled'] = 'disabled';
            }

            if (!empty($category['id'])) {
                $css_state = 'has-success';
            }
            if ($errors->has('categories_ids')) {
                $css_state = 'has-error';
            }
        ?>
        <div class="categories form-group col-sm-6 {!! $css_state !!}">
            {!! Form::label('categories_ids', $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label']) !!}
            <div class="col-xs-12 col-sm-7">
                <div class="input-group">
                    {!! Form::select('categories_ids', $categories, !empty($category['id']) ? $category['id'] : old('categories_ids'), $attributes) !!}
                </div>
            </div>
        </div>
        @endif

        @if (isset($subcategories))
        <?php
            $label_txt = trans('filters.subcategories');
            $attributes = [
                    'data-ajax--url'=>"subcategories",
                    'data-parent' => !empty($category['id']) ? $category['id'] : '',
                    'data-header' => '-- ' . trans('navigation.form_any') . ' --',
                    'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                    'class' => 'form-control',
                    'id' => 'subcategories_ids'
            ];
            $css_state = '';
            if (!count($subcategories) > 0) {
                $attributes['disabled'] = 'disabled';
                $css_state .= 'collapse ';
            }

            if (!empty($subcategory['id'])) {
                $css_state = 'has-success';
            }
            if ($errors->has('subcategories_ids')) {
                $css_state = 'has-error';
            }
        ?>
        <div class="subcategories form-group col-sm-6 {!! $css_state !!}">
            {!! Form::label('subcategories_ids', $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label']) !!}
            <div class="col-xs-12 col-sm-7">
                <div class="input-group">
                    {!! Form::select('subcategories_ids', $subcategories, !empty($subcategory['id']) ? $subcategory['id'] : old('subcategories_ids'), $attributes) !!}
                </div>
            </div>
        </div>
        @endif
    </section>

    <section class="row">
        {{--@if (isset($manufacturers))--}}
        <?php
            if(!empty($manufacturers)) {
                $array = json_decode(json_encode($manufacturers), true);
                asort($array);
                $manufacturers = $array;
            }
            $label_txt = $label_txt_manufacturers;
            $attributes = [
                //'data-ajax--url'=>"/ajax-manufacturer",
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
            if (!empty($manufacturer['id'])) {
                $css_state = 'has-success';
            }
            if ($errors->has('manufacturers_id')) {
                $css_state = 'has-error';
            }
        ?>

            <div class="manufacturers form-group col-sm-6 {!! $css_state !!}">
            {!! Form::label('manufacturers_id', $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label']) !!}
            <div class="col-xs-12 col-sm-7">
                <div class="input-group">
                    {{--{!! Form::select('manufacturers_id', !empty($manufacturer['id']) ? [$manufacturer['id']=>$manufacturer['name']] : $manufacturers, !empty($manufacturer['id']) ? $manufacturer['id'] : old('manufacturers_id'), $attributes) !!}--}}
                    {!! Form::select('manufacturers_id', !empty($manufacturer['id']) ? [$manufacturer['id']=>$manufacturer['name']] : [], !empty($manufacturer['id']) ? $manufacturer['id'] : old('manufacturers_id'), $attributes) !!}
                </div>
            </div>
        </div>
        {{--@endif--}}
        @if (isset($models))
        <?php
            if(!empty($models)) {
                $array = json_decode(json_encode($models), true);
                asort($array);
                $models = $array;
            }

            $models_id = old('models_id', isset($model['id']) ? $model['id'] : '');
            //$models_id = !empty($model['id']) ? $model['id'] : old('models_id');
            $label_txt = trans('filters.models');
            $attributes = [
                'ajax-url'=>"/ajax-models",
                'data-parent' => !empty($manufacturer['id']) ? $manufacturer['id'] : '',
                'data-placeholder' => $label_txt,
                'placeholder' => $label_txt,
                'class' => 'form-control',
                'id' => 'models_id'
            ];

            $css_state = '';
            if (!count($models) > 0 && !isset($models_id)) {
                $attributes['disabled'] = 'disabled';
                $css_state .= 'collapse ';
            }
            if (!empty($models_id)) {
                $css_state = 'has-success';
            }
            if ($errors->has('models_id')) {
                $css_state = 'has-error';
            }
        ?>
        <div class="models form-group col-sm-6 {!! $css_state !!}">
            {!! Form::label('models_id', $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label']) !!}
            <div class="col-xs-12 col-sm-7">
                <div class="input-group">
                    {!! Form::select('models_id', $models, $models_id, $attributes) !!}
                    {{--{!! Form::select('models_id', !empty($models_id) ? [$models_id=>$model['name']] : [], $models_id, $attributes) !!}--}}
                </div>
            </div>
        </div>
        @endif
    </section>

    <section class="row">
        <?php
            $years = [];
            $label_txt = 'Min. ' . trans('filters.year_built');
            $attributes = [
                    'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                    'data-placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                    'class' => 'form-control ',
                    'id' => 'min_year_built'
            ];
            $css_state = '';
            if (!empty($min_year_built)) {
                $css_state = 'has-success';
            }
            if ($errors->has('min_year_built')) {
                $css_state = 'has-error';
            }
            $curYear = date("Y");
        ?>
        <div class="form-group col-sm-6 {!! $css_state !!}">
            {!! Form::label('min_year_built', $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label']) !!}
            <div class="col-xs-12 col-sm-7">
                <div class="input-group">
                    {{--{!! Form::select('min_year_built', $years, !empty($min_year_built) ? $min_year_built : old('min_year_built'), $attributes) !!}--}}
                    {!! Form::selectRange('min_year_built', 1970, $curYear, !empty($min_year_built) ? $min_year_built : old('min_year_built'), $attributes) !!}
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
            if (!empty($max_year_built)) {
                $css_state = 'has-success';
            }
            if ($errors->has('max_year_built')) {
                $css_state = 'has-error';
            }
        ?>
        <div class="form-group col-sm-6 {!! $css_state !!}">
            {!! Form::label('max_year_built', $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label']) !!}
            <div class="col-xs-12 col-sm-7">
                <div class="input-group">
                    {{--{!! Form::select('max_year_built', array_reverse($years,true), !empty($max_year_built) ? $max_year_built : old('max_year_built'), $attributes) !!}--}}
                    {!! Form::selectRange('max_year_built', $curYear, 1970, !empty($max_year_built) ? $max_year_built : old('max_year_built'), $attributes) !!}
                </div>
            </div>
        </div>
    </section>

    <section class="row">
        <?php
            $label_txt = trans('filters.min_length') . ' (m)';
            $attributes = [
                'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                'data-placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                'class' => 'form-control nosort',
                'id' => 'min_length'
            ];
            $css_state = '';
            if (!empty($boat_min_length)) {
                $css_state = 'has-success';
            }
            if ($errors->has('min_length')) {
                $css_state = 'has-error';
            }
        ?>
        <div class="form-group col-sm-6 {!! $css_state !!}">
            {!! Form::label('min_length', $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label']) !!}
            <div class="col-xs-12 col-sm-7">
                <div class="input-group">
                    {!! Form::selectRange('min_length', 3, 16, !empty($boat_min_length) ? $boat_min_length : old('min_length'), $attributes) !!}
                </div>
                {{--<input type="range" name="min_length_range" id="min_length_range" min="3" max="16" step="1" value="{!! !empty($boat_min_length) ? $boat_min_length : old('min_length') !!}">--}}
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
            if (!empty($boat_max_length)) {
                $css_state = 'has-success';
            }
            if ($errors->has('max_length')) {
                $css_state = 'has-error';
            }
        ?>
        <div class="form-group col-sm-6 {!! $css_state !!}">
            {!! Form::label('max_length', $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label']) !!}
            <div class="col-xs-12 col-sm-7">
                <div class="input-group">
                    {!! Form::selectRange('max_length', 16, 3,  !empty($boat_max_length) ? $boat_max_length : old('max_length'), $attributes) !!}
                </div>
                {{--<input type="range" name="max_length_range" id="max_length_range" min="16" max="3"  step="1" value="{!! !empty($boat_max_length) ? $boat_max_length : old('max_length') !!}">--}}
            </div>
        </div>
    </section>

    <section class="row">
        <?php
            $label_txt = trans('filters.min_width') . ' (m)';
            $attributes = [
                    'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                    'data-placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                    'class' => 'form-control nosort',
                    'id' => 'min_width'
            ];
            $css_state = '';
            if (!empty($boat_min_width)) {
                $css_state = 'has-success';
            }
            if ($errors->has('min_width')) {
                $css_state = 'has-error';
            }
        ?>
        <div class="form-group col-sm-6 {!! $css_state !!}">
            {!! Form::label('min_width', $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label']) !!}
            <div class="col-xs-12 col-sm-7">
                <div class="input-group">
                    {!! Form::selectRange('min_width', 3, 6, !empty($boat_min_width) ? $boat_min_width : old('min_width'), $attributes) !!}
                </div>
                {{--<input type="range" name="min_width_range" id="min_width_range" min="3" max="6" step="1" value="{!! !empty($boat_min_width) ? $boat_min_width : old('min_width') !!}">--}}
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
            if (!empty($boat_max_width)) {
                $css_state = 'has-success';
            }
            if ($errors->has('max_width')) {
                $css_state = 'has-error';
            }
        ?>
        <div class="form-group col-sm-6 {!! $css_state !!}">
            {!! Form::label('max_width', $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label']) !!}
            <div class="col-xs-12 col-sm-7">
                <div class="input-group">
                    {!! Form::selectRange('max_width', 6, 3, !empty($boat_max_width) ? $boat_max_width : old('max_width'), $attributes) !!}
                </div>
                {{--<input type="range" name="max_width_range" id="max_width_range" min="6" max="3"  step="1" value="{!! !empty($boat_max_width) ? $boat_max_width : old('max_width') !!}">--}}
            </div>
        </div>
    </section>

    <hr>

    <h4 class="accent-color">{!! trans('boat_on_demand.place_of_navigation') !!}</h4>

    <section class="row">
        @if (isset($countries))
        <?php
            $label_txt = ucfirst(trans('validation.attributes.country'));
            $attributes = [
                'data-header' => '-- ' . trans('navigation.form_any') . ' --',
                'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                'class' => 'form-control',
                'id' => 'countries_id'
            ];
            $css_state = '';
            if (!count($countries) > 0) {
                $attributes['disabled'] = 'disabled';
                $css_state .= 'collapse ';
            }
            if (!empty($country['id']) || count($countries) === 1) {
                $css_state = 'has-success';
            }
            if ($errors->has('countries_id')) {
                $css_state = 'has-error';
            }
        ?>
        <div class="form-group col-sm-6 col-md-4 {!! $css_state !!}">
            {!! Form::label('countries_id', $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label']) !!}
            <div class="col-xs-12 col-sm-7">
                <div class="input-group">
                    @if (count($countries) === 1)
                    <?php
                        $array = json_decode(json_encode($countries), true);
                        $key = key($array);
                    ?>
                    {!! Form::text('country_val', $countries->first(), $attributes) !!}
                    {!! Form::hidden('countries_id', $key) !!}
                    @else
                    {!! Form::select('countries_id', $countries, !empty($country['id']) ? $country['id'] : old('countries_id'), $attributes) !!}
                    @endif
                </div>
            </div>
        </div>
        @endif

        @if (isset($regions))
        <?php
            $label_txt = ucfirst(trans('validation.attributes.district'));
            $attributes = [
                'data-header' => '-- ' . trans('navigation.form_any') . ' --',
                'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                'class' => 'form-control',
                'id' => 'regions_id'
            ];
            $css_state = '';
            if (!count($regions) > 0) {
                $attributes['disabled'] = 'disabled';
                $css_state .= 'collapse ';
            }
            if (!empty($region) || count($regions) === 1) {
                $css_state = 'has-success';
            }
            if ($errors->has('regions_id')) {
                $css_state = 'has-error';
            }
        ?>
        <div class="form-group col-sm-6 col-md-4 {!! $css_state !!}">
            {!! Form::label('regions_id', $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label']) !!}
            <div class="col-xs-12 col-sm-7">
                <div class="input-group">
                    @if (count($regions) === 1)
                    <?php
                        $array = json_decode(json_encode($regions), true);
                        $key = key($array);
                    ?>
                        {!! Form::text('region_val', $regions->first(), $attributes) !!}
                        {!! Form::hidden('regions_id', $key.'') !!}
                    @else
                        {!! Form::select('regions_id', $regions, !empty($region) ? $region : old('regions_id'), $attributes) !!}
                    @endif
                </div>
            </div>
        </div>
        @endif

        @if (isset($counties))
        <?php
            $label_txt = ucfirst(trans('validation.attributes.county'));
            $attributes = [
                    'data-header' => '-- ' . trans('navigation.form_any') . ' --',
                    'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                    'class' => 'form-control',
                    'id' => 'counties_id'
            ];
            $css_state = '';
            if (!count($counties) > 0) {
                $attributes['disabled'] = 'disabled';
                $css_state .= 'collapse ';
            }
            if (!empty($county) || count($counties) === 1) {
                $css_state = 'has-success';
            }
            if ($errors->has('counties_id')) {
                $css_state = 'has-error';
            }
        ?>
        <div class="form-group col-sm-6 col-md-4 {!! $css_state !!}">
            {!! Form::label('counties_id', $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label']) !!}
            <div class="col-xs-12 col-sm-7">
                <div class="input-group">
                    @if (count($counties) === 1)
                    <?php
                        $array = json_decode(json_encode($counties), true);
                        $key = key($array);
                    ?>
                        {!! Form::text('county_val', $counties->first(), $attributes) !!}
                        {!! Form::hidden('counties_id', $key.'') !!}
                    @else
                        {!! Form::select('counties_id', $counties, !empty($county) ? $county : old('counties_id'), $attributes) !!}
                    @endif
                </div>
            </div>
        </div>
        @endif
    </section>

    <hr>

    <h4 class="accent-color">{!! ucfirst(trans('boat_on_demand.additional_informations')) !!}</h4>

    <section class="row">
        <?php
            $label_txt = ucfirst(trans('boat_on_demand.budget'));
            $attributes = [
                    'data-header' => trans('navigation.form_enter_placeholder'),
                    'placeholder' => trans('navigation.form_enter_placeholder'),
                    'class' => 'form-control',
                    'id' => 'budget'
            ];
            $css_state = '';
            if (!empty($budget)) {
                $css_state = 'has-success';
            }
            if ($errors->has('budget')) {
                $css_state = 'has-error';
            }
            $attributes['required'] = 'required';
        ?>
        <div class="form-group col-sm-6 {!! $css_state !!}">
            {!! Form::label('budget', $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label']) !!}
            <div class="col-xs-12 col-sm-7">
                <div class="input-group">
                    {!! Form::text('budget', !empty($budget) ? $budget : old('budget'), $attributes) !!}
                    <span class="input-group-addon">{!! config('youboat.'. $country_code .'.currency') !!}</span>
                </div>
            </div>
        </div>

        @if (isset($selltypes))
        <?php
            $label_txt = trans('selltype.sell_type');
            $attributes = [
                    'data-header' => '-- ' . trans('navigation.form_any') . ' --',
                    'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                    'class' => 'form-control',
                    'id' => 'sell_type'
            ];
            $css_state = '';
            if (!count($selltypes) > 0) {
                $attributes['disabled'] = 'disabled';
                $css_state .= 'collapse ';
            }
            if (!empty($sell_type)) {
                $css_state = 'has-success';
            }
            if ($errors->has('sell_type')) {
                $css_state = 'has-error';
            }
        ?>
        <div class="form-group col-sm-6 {!! $css_state !!}">
            {!! Form::label('sell_type', $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label']) !!}
            <div class="col-xs-12 col-sm-7">
                <div class="input-group">
                    {!! Form::select('sell_type', $selltypes, !empty($sell_type) ? $sell_type : old('sell_type'), $attributes) !!}
                </div>
            </div>
        </div>
        @endif
    </section>

    <section class="row">
        <?php
            $label_txt = ucfirst(trans('validation.attributes.comment'));
            $attributes = [
                    'data-header' => trans('navigation.form_enter_placeholder'),
                    'placeholder' => trans('navigation.form_enter_placeholder'),
                    'class' => 'form-control',
                    'id' => 'description'
            ];
            $css_state = '';
            if (!empty($description)) {
                $css_state = 'has-success';
            }
            if ($errors->has('description')) {
                $css_state = 'has-error';
            }
        ?>
        <div class="form-group col-sm-6 {!! $css_state !!}">
            {!! Form::label('description', $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label']) !!}
            <div class="col-xs-12 col-sm-7">
                <div class="input-group">
                    {!! Form::textarea('description', !empty($description) ? $description : old('description'), $attributes) !!}
                </div>
            </div>
        </div>
        <?php
            $label_txt = ucfirst(trans('boat_on_demand.with_marina_berth'));
            $css_state = '';
            if (!empty($with_marina_berth) && '1' == $with_marina_berth) {
                $css_state = 'has-success';
                $attributes = [
                    'id'=>'with_marina_berth',
                    //'checked'=>'checked'
                ];
                $checkbox_attributes = [
                    'id'=>'checkbox_with_marina_berth',
                    'checked'=>'checked'
                ];
            } else {
                $attributes = [
                    'id'=>'with_marina_berth'
                ];
                $checkbox_attributes = [
                        'id'=>'checkbox_with_marina_berth'
                ];
            }
            if ($errors->has('with_marina_berth')) {
                $css_state = 'has-error';
            }
        ?>
        <div class="form-group col-sm-6 {!! $css_state !!}">
            <div class="col-xs-12 col-sm-7 col-sm-offset-5">
                <div class="checkbox {!! $css_state !!}">
                    <label for="checkbox_with_marina_berth">
                        {!! Form::checkbox('checkbox_with_marina_berth', !empty($with_marina_berth) ? true : false, !empty($with_marina_berth) ? $with_marina_berth : old('with_marina_berth', 0), $checkbox_attributes) !!}
                        {!! Form::hidden('with_marina_berth', !empty($with_marina_berth) ? $with_marina_berth : old('with_marina_berth', 0), $attributes) !!}
                        {!! $label_txt !!}
                    </label>
                </div>
            </div>
        </div>
        <?php
            $label_txt = ucfirst(trans('boat_on_demand.agree_similar'));
            $css_state = '';
            if (!empty($agree_similar) && '1' == $agree_similar) {
                $css_state = 'has-success';
                $attributes = [
                    'id'=>'agree_similar',
                    //'checked'=>'checked'
                ];
                $checkbox_attributes = [
                    'id'=>'checkbox_agree_similar',
                    'checked'=>'checked'
                ];
            } else {
                $attributes = [
                    'id'=>'agree_similar'
                ];
                $checkbox_attributes = [
                    'id'=>'checkbox_agree_similar'
                ];
            }
            if ($errors->has('agree_similar')) {
                $css_state = 'has-error';
            }
        ?>
        <div class="form-group col-sm-6 {!! $css_state !!}">
            <div class="col-xs-12 col-sm-7 col-sm-offset-5">
                <div class="checkbox {!! $css_state !!}">
                    <label for="checkbox_agree_similar">
                        {!! Form::checkbox('checkbox_agree_similar', !empty($agree_similar) ? true : false, !empty($agree_similar) ? $agree_similar : old('agree_similar', false), $checkbox_attributes) !!}
                        {!! Form::hidden('agree_similar', !empty($agree_similar) ? $agree_similar : old('agree_similar', false), $attributes) !!}
                        {!! $label_txt !!}
                    </label>
                </div>
            </div>
        </div>
    </section>
