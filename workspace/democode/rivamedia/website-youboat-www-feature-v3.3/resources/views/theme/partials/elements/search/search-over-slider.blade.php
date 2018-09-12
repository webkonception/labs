<!-- Search Form -->
<div class="floated">
    <div class="search-form">
        <div class="alert alert-info fade clearfix">
            <h2>{!! trans('search.search_form_title') !!}</h2>
            <p class="col-sm-8 lead">{!! trans('search.search_form_intro') !!}</p>
            <p class="col-sm-4">{!! link_trans_route('for_sale', 'navigation.view_all_ads', ['class'=>'btn btn-danger btn-lg big']) !!}</p>
        </div>
        <div class="search-form-inner">
            {!! Form::open(array('url'=>trans_route($currentLocale, 'routes.for_sale'), 'role'=> '', 'id'=>'form_filters', 'autocomplete'=>'off', 'method'=>'GET')) !!}
                <div class="input-group input-group-lg hidden-xs">
                    {!! Form::text('query', old('query'), ['class'=>'form-control', 'placeholder'=>trans('navigation.form_search_placeholder')]) !!}
                    <span class="input-group-btn">
                        {!! Form::button(trans('navigation.search'), ['type' => 'submit', 'data-ga'=>$view_name . '~' . trans('navigation.basic_search'), 'class' => 'btn btn-primary', 'id' => 'btn_basic_search']) !!}
                    </span>
                </div>
                <div class="clearfix">
                    <a href="{!! url(trans_route($currentLocale, 'routes.for_sale')) !!}" data-basic_search="{!! trans('navigation.basic_search') !!}" data-advanced_search="{!! trans('navigation.advanced_search') !!}" class="pull-right btn btn-sm col-xs-12 col-sm-4 btn-default search-advanced-trigger basic">{!! trans('filters.advanced_search_filters') !!}<span class="fa fa-arrow-down fa-fw"></span></a>
                    <span class="label label-warning">{!! isset($total_used_boats) ? $total_used_boats : 1900 !!} {!! trans('navigation.used_boats') !!}</span>
                    <span class="label label-success">{!! isset($total_new_boats) ? $total_new_boats : 230 !!} {!! trans('navigation.new_boats') !!}</span>
                </div>
                <div class="advanced-search-row">
                    <div class="row">
                        @if (isset($adstypes))
                        <?php
                            $placeholder = trans('filters.adstypes');
                            $attributes = [
                                //'data-ajax--url'=>"/ajax-gateway_adstype",
                                //'data-ajax--url' => LaravelLocalization::localizeURL('/ajax-gateway_adstype')
                                'data-ajax--url' => LaravelLocalization::localizeURL('/ajax-adstypes'),
                                'data-placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                                'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                                'class' => 'form-control nosort ',
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
                        <div class="col-xs-12 col-sm-6 form-group {!! $css_state !!}">
                            {!! Form::label('adstypes_id', $placeholder, ['class'=>'control-label']) !!}
                            <div class="input-group">
                                {!! Form::select('adstypes_id', $adstypes, !empty($adstype['id']) ? $adstype['id'] : old('adstypes_id'), $attributes) !!}
                                {!! $addon !!}
                            </div>
                        </div>
                        @endif

                        @if (isset($categories))
                        <?php
                            $placeholder = trans('filters.categories');
                            $attributes = [
                                //'data-ajax--url'=>"/ajax-gateway_category",
                                //'data-ajax--url' => LaravelLocalization::localizeURL('/ajax-gateway_category'),
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
                                $css_state = 'has-success';
                            }
                            $addon = '<span class="input-group-addon"><i class="fa fa-check"></i></span>';
                        ?>
                        <div class="col-xs-12 col-sm-6 form-group {!! $css_state !!}">
                            {!! Form::label('categories_ids', $placeholder, ['class'=>'control-label']) !!}
                            <div class="input-group">
                                {!! Form::select('categories_ids', $categories, !empty($category['id']) ? $category['id'] : old('categories_ids'), $attributes) !!}
                                {!! $addon !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @endif
                        @if (isset($manufacturers))
                        <?php
                            /*if(!empty($manufacturers)) {
                                $array = json_decode(json_encode($manufacturers), true);
                                asort($array);
                                $manufacturers = $array;
                            }*/
                            $placeholder = trans('filters.manufacturers') . '/' . trans('filters.shipyards');
                            $attributes = [
                                    //'data-ajax--url' => '/ajax-gateway_manufacturer',
                                    //'data-ajax--url' => '/ajax-manufacturer',
                                    'data-ajax--url' => LaravelLocalization::localizeURL('/ajax-manufacturer'),
                                    'data-placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                                    'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
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
                        <div class="col-xs-12 col-sm-6 form-group {!! $css_state !!}">
                            {!! Form::label('manufacturers_id', $placeholder, ['class'=>'control-label']) !!}
                            <div class="input-group <?php echo isset($manufacturer['id']) ? 'has-success' : ''; ?>">
                                {!! Form::select('manufacturers_id', !empty($manufacturer['id']) ? [$manufacturer['id']=>$manufacturer['name']] : [], isset($manufacturer['id']) ? $manufacturer['id'] : old('manufacturers_id'), $attributes) !!}
                                {!! $addon !!}
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
                            $placeholder = trans('filters.models');
                            $attributes = [
                                    'data-placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                                    'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                                    'class' => 'form-control',
                                    'id' => 'models_id'
                            ];

                            $css_state = '';
                            //if (!count($models) > 0) {
                            if (!count($models) > 0 && !isset($models_id)) {
                                $attributes['disabled'] = 'disabled';
                                $css_state .= 'collapse ';
                            }

                            $addon = '';
                            if (!empty($models_id)) {
                                $css_state = 'has-success';
                            }
                            $addon = '<span class="input-group-addon"><i class="fa fa-check"></i></span>';
                        ?>
                        <div class="col-xs-12 col-sm-6 form-group {!! $css_state !!}">
                            {!! Form::label('models_id', $placeholder, ['class'=>'control-label']) !!}
                            <div class="input-group <?php echo isset($model['id']) ? 'has-success' : ''; ?>">
                                {{--{!! Form::select('models_id', !empty($model['id']) ? [$model['id']=>$model['name']] : [], isset($model['id']) ? $model['id'] : old('models_id'), $attributes) !!}--}}
                                {!! Form::select('models_id', $models, $models_id, $attributes) !!}
                                {!! $addon !!}
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="row">
                    @if (isset($years_built))
                        <?php
                            $years = [];
                            foreach ($years_built as $key => $value) {
                                //$years[$key] = $key . ' (' . $value['count'] . ')';
                                $years[$key] = $key;
                            }

                            $label_txt = 'Min. ' . trans('filters.year_built');
                            $attributes = [
                                'data-header' => trans('navigation.form_select_placeholder') . ' ' . $placeholder,
                                'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
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
                        <div class="col-xs-6 col-sm-3 form-group {!! $css_state !!}">
                            {!! Form::label('min_year_built', $label_txt, ['class'=>'control-label']) !!}
                            <div class="input-group">
                                {!! Form::select('min_year_built', $years, !empty($min_year_built) ? $min_year_built : old('min_year_built'), $attributes) !!}
                                {!! $addon !!}
                            </div>
                        </div>
                        <?php
                            $label_txt = 'Max. ' . trans('filters.year_built');
                            $attributes = [
                                'data-header' => trans('navigation.form_select_placeholder') . ' ' . $placeholder,
                                'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
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
                        <div class="col-xs-6 col-sm-3 form-group {!! $css_state !!}">
                            {!! Form::label('max_year_built', $label_txt, ['class'=>'control-label']) !!}
                            <div class="input-group">
                                {!! Form::select('max_year_built', array_reverse($years,true), !empty($max_year_built) ? $max_year_built : old('max_year_built'), $attributes) !!}
                                {!! $addon !!}
                            </div>
                        </div>
                    @endif

                        <?php
                            $label_txt = trans('filters.min_length') . ' (m)';
                            $attributes = [
                                'data-header' => trans('navigation.form_select_placeholder') . ' ' . $placeholder,
                                'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                                'class' => 'form-control nosort ',
                                'data-size' => '5',
                                'data-live-search' => 'true',
                                'id' => 'min_length'
                            ];

                            $css_state = '';
                            $addon = '';
                            if (!empty($boat_min_length)) {
                                $css_state = 'has-success';
                            }
                            $addon = '<span class="input-group-addon"><i class="fa fa-check"></i></span>';
                        ?>
                        <div class="col-xs-6 col-sm-3 form-group {!! $css_state !!}">
                            {!! Form::label('min_length', $label_txt, ['class'=>'control-label']) !!}
                            <div class="input-group">
                                {!! Form::selectRange('min_length', 1, 16, isset($boat_min_length) ? $boat_min_length : old('min_length'), $attributes) !!}
                                {!! $addon !!}
                            </div>
                            {{--<input type="range" name="min_length_range" id="min_length_range" min="0" max="14" step="1" value="0">--}}
                        </div>
                        <?php
                            $label_txt = trans('filters.max_length') . ' (m)';
                            $attributes = [
                                'data-header' => trans('navigation.form_select_placeholder') . ' ' . $placeholder,
                                'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                                'class' => 'form-control nosort ',
                                'data-size' => '5',
                                'data-live-search' => 'true',
                                'id' => 'max_length'
                            ];

                            $css_state = '';
                            $addon = '';
                            if (!empty($boat_max_length)) {
                                $css_state = 'has-success';
                            }
                            $addon = '<span class="input-group-addon"><i class="fa fa-check"></i></span>';
                        ?>
                        <div class="col-xs-6 col-sm-3 form-group {!! $css_state !!}">
                            {!! Form::label('max_length', $label_txt, ['class'=>'control-label']) !!}
                            <div class="input-group">
                                {!! Form::selectRange('max_length', 16, 1,  isset($boat_max_length) ? $boat_max_length : old('max_length'), $attributes) !!}
                                {!! $addon !!}
                            </div>
                            {{--<input type="range" name="max_length_range" id="max_length_range" min="0" max="14" step="1" value="0">--}}
                        </div>

                        <?php
                            $label_txt = trans('filters.min_width') . ' (m)';
                            $attributes = [
                                    'data-header' => trans('navigation.form_select_placeholder') . ' ' . $placeholder,
                                    'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                                    'class' => 'form-control nosort ',
                                    'data-size' => '5',
                                    'data-live-search' => 'true',
                                    'id' => 'min_width'
                            ];

                            $css_state = '';
                            $addon = '';
                            if (!empty($boat_min_width)) {
                                $css_state = 'has-success';
                            }
                            $addon = '<span class="input-group-addon"><i class="fa fa-check"></i></span>';
                        ?>
                        <div class="col-xs-6 col-sm-3 form-group {!! $css_state !!}">
                            {!! Form::label('min_width', $label_txt, ['class'=>'control-label']) !!}
                            <div class="input-group">
                                {!! Form::selectRange('min_width', 1, 6, isset($boat_min_width) ? $boat_min_width : old('min_width'), $attributes) !!}
                                {!! $addon !!}
                            </div>
                            {{--<input type="range" name="min_width_range" id="min_width_range" min="0" max="14" step="1" value="0">--}}
                        </div>
                        <?php
                            $label_txt = trans('filters.max_width') . ' (m)';
                            $attributes = [
                                    'data-header' => trans('navigation.form_select_placeholder') . ' ' . $placeholder,
                                    'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                                    'class' => 'form-control nosort ',
                                    'data-size' => '5',
                                    'data-live-search' => 'true',
                                    'id' => 'max_width'
                            ];

                            $css_state = '';
                            $addon = '';
                            if (!empty($boat_max_width)) {
                                $css_state = 'has-success';
                            }
                            $addon = '<span class="input-group-addon"><i class="fa fa-check"></i></span>';
                        ?>
                        <div class="col-xs-6 col-sm-3 form-group {!! $css_state !!}">
                            {!! Form::label('max_width', $label_txt, ['class'=>'control-label']) !!}
                            <div class="input-group">
                                {!! Form::selectRange('max_width', 6, 1,  isset($boat_max_width) ? $boat_max_width : old('max_width'), $attributes) !!}
                                {!! $addon !!}
                            </div>
                            {{--<input type="range" name="max_width_range" id="max_width_range" min="0" max="14" step="1" value="0">--}}
                        </div>
                    </div>
                    <div class="row">
                        @if (isset($selltypes))
                        <?php
                            $label_txt = trans('filters.sell_type');
                            $attributes = [
                                'data-header' => trans('navigation.form_select_placeholder') . ' ' . $placeholder,
                                'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
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
                            <div class="col-xs-6 col-sm-3 form-group  {!! $css_state !!}">
                            {!! Form::label('sell_type', $label_txt, ['class'=>'control-label']) !!}
                            <div class="input-group">
                                {!! Form::select('sell_type', $selltypes, isset($sell_type) ? $sell_type : old('sell_type'), $attributes) !!}
                                {!! $addon !!}
                            </div>
                        </div>
                        @endif

                        <div class="col-xs-6 col-sm-offset-3 col-sm-6 form-group">
                            <br>
                            <span class="input-group">
                                {!! Form::button(trans('navigation.search'), ['type' => 'submit', 'data-ga'=>$view_name . '~' . trans('navigation.advanced_search'), 'class' => 'btn btn-block btn-primary']) !!}
                            </span>
                        </div>
                    </div>
                </div>
                {!! Form::hidden('country_code', $country_code) !!}
                {!! Form::hidden('currency', config('youboat.'. $country_code .'.currency')) !!}
            {!! Form::close() !!}
        </div>
    </div>
</div>
