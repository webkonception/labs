<?php
    $javascript="";
    if (!empty($datasRequest) && count($datasRequest)>0) {
        //echo'<pre>';
        //var_dump($datasRequest);
        $recovery_adstype                = isset($recovery_adstype) ? $recovery_adstype : (!empty($datasRequest['recovery_adstypes_id']) ? Search::getAdsTypeById ($datasRequest['recovery_adstypes_id']) : null);
        $recovery_category               = isset($recovery_category) ? $recovery_category : (!empty($datasRequest['recovery_categories_ids']) ? Search::getCategoryById ($datasRequest['recovery_categories_ids']) : null);
        $recovery_subcategory            = isset($recovery_subcategory) ? $recovery_subcategory : (!empty($datasRequest['recovery_subcategories_ids']) ? Search::getSubcategoryById ($datasRequest['recovery_subcategories_ids']) : null);

        $recovery_manufacturer           = isset($recovery_manufacturer) ? $recovery_manufacturer : (!empty($datasRequest['recovery_manufacturers_id']) ? Search::getManufacturerById ($datasRequest['recovery_manufacturers_id']) : null);

        $recovery_model                  = isset($recovery_model) ? $recovery_model : (!empty($datasRequest['recovery_models_id']) ? Search::getModelById ($datasRequest['recovery_models_id']) : null);

        $recovery_year_built             = isset($recovery_year_built) ? $recovery_year_built : (!empty($datasRequest['recovery_year_built']) ? $datasRequest['recovery_year_built'] : null);

        $recovery_budget                 = isset($recovery_budget) ? $recovery_budget : (!empty($datasRequest['recovery_budget']) ? $datasRequest['recovery_budget'] : null);

        $recovery_description            = isset($recovery_description) ? $recovery_description : (!empty($datasRequest['recovery_description']) ? $datasRequest['recovery_description'] : null);
    }

    $label_txt_recovery_manufacturers = trans('filters.manufacturers') . '/' . trans('filters.shipyards');
    if (!empty($recovery_adstype['rewrite_url'])) {
        if (preg_match('/engine/', $recovery_adstype['rewrite_url'])) {
            $recovery_engines_inputs_disabled        = true;
            $recovery_not_engines_inputs_disabled    = false;
            $label_txt_recovery_manufacturers = trans('filters.manufacturers_engines');
        } else {
            $recovery_engines_inputs_disabled        = false;
            $recovery_not_engines_inputs_disabled    = true;
        }
    } else {
        $recovery_engines_inputs_disabled        = false;
        $recovery_not_engines_inputs_disabled    = false;
    }
?>
<section class="well well-sm well-warning alert-warning">
    <h3 class="strong">{!! trans('boat_on_demand.trade_in') !!}</h3>
    <div class="well well-sm well-white" id="collapseRecovery">
        <h4 class="strong">{!! trans('boat_on_demand.features_title') !!}</h4>
    @if (!isset($form_action))
    {!! Form::open(array('url'=>trans_route($currentLocale, 'routes.for_sale'), 'class'=>'form-horizontal', 'id'=>'form_filters', 'autocomplete'=>'off')) !!}
        {!! csrf_field() !!}
        {!! Form::hidden('country_code', $country_code) !!}
    @endif
        <section class="row">
            @if (isset($adstypes))
            <?php
                //$recovery_adstypes_id = !empty($recovery_adstype['id']) ? $recovery_adstype['id'] : old('recovery_adstypes_id');
                $recovery_adstypes_id = old('recovery_adstypes_id', !empty($bodcaracts->recovery_adstypes_id)?$bodcaracts->recovery_adstypes_id:'');
                $placeholder = trans('navigation.form_select_placeholder');
                $label_txt = trans('filters.adstypes');
                $attributes = [
                        'data-header' => '-- ' . trans('navigation.form_any') . ' --',
                        'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                        'class' => 'form-control nosort',
                        'id' => 'recovery_adstypes_id'
                ];
                $attributes['data-required'] = 'required';
                $css_state = '';
                if (!count($adstypes) > 0) {
                    $attributes['disabled'] = 'disabled';
                    $css_state .= 'collapse ';
                }

                if (!empty($recovery_adstype['id'])) {
                    $css_state = 'has-success';
                }
                if ($errors->has('recovery_adstypes_id')) {
                    $css_state = 'has-error';
                }
                if (!count($adstypes) > 0) {
                    $attributes['disabled'] = 'disabled';
                }
            ?>
                <div class="form-group form-group col-sm-6 {!! $css_state !!}">
                    {!! Form::label('recovery_adstypes_id', $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label']) !!}
                    <div class="col-xs-{{ ($isAdmin) ? 8 : 12 }} col-sm-{{ ($isAdmin) ? 4 : 7 }}">
                        {!! Form::select('recovery_adstypes_id', $adstypes, $recovery_adstypes_id,  $attributes) !!}
                    </div>
                    @if ($isAdmin)
                        <div class="col-xs-4 col-sm-3">
                            {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.adstypes.create', '<i class="fa fa-plus fa-fw"></i>Add', [], ['class'=>'blank btn btn-sm btn-success btn-block'])) !!}
                        </div>
                    @endif
                </div>
            @endif
        </section>

        <section class="row">
            @if (isset($recovery_categories))
            <?php
                //$recovery_categories_ids = !empty($recovery_category['id']) ? $recovery_category['id'] : old('recovery_categories_ids');
                $recovery_categories_ids = old('recovery_categories_ids', !empty($bodcaracts->recovery_categories_ids)?$bodcaracts->recovery_categories_ids:'');
                $label_txt = trans('filters.categories');
                $attributes = [
                        'data-ajax--url'=>"categories",
                        //'data-parent' => !empty($recovery_adstype['id']) ? $recovery_adstype['id'] : '',
                        'data-parent' => !empty($adstypes_id) ? $adstypes_id : '',
                        'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                        'data-placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                        'class' => 'form-control',
                        'id' => 'recovery_categories_ids'
                ];
                $css_state = '';
                if (!count($recovery_categories) > 0) {
                    $attributes['disabled'] = 'disabled';
                }

                if (!empty($recovery_categories_ids)) {
                    $css_state = 'has-success';
                 }
                if ($errors->has('recovery_categories_ids')) {
                    $css_state = 'has-error';
                }
            ?>
                <div class="categories form-group col-sm-6 {!! $css_state !!}">
                    {!! Form::label('recovery_categories_ids', $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label']) !!}
                    <div class="col-xs-{{ ($isAdmin) ? 8 : 12 }} col-sm-{{ ($isAdmin) ? 4 : 7 }}">
                        {!! Form::select('recovery_categories_ids', $recovery_categories, $recovery_categories_ids, $attributes) !!}
                    </div>
                    @if ($isAdmin)
                        <div class="col-xs-4 col-sm-3">
                            {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.categories.create', '<i class="fa fa-plus fa-fw"></i>Add', [], ['class'=>'blank btn btn-sm btn-success btn-block'])) !!}
                        </div>
                    @endif
                </div>
            @endif

            @if (isset($recovery_subcategories))
            <?php
                $recovery_subcategories_ids = old('recovery_subcategories_ids', !empty($bodcaracts->recovery_subcategories_ids)?$bodcaracts->recovery_subcategories_ids:'');
                $label_txt = trans('filters.subcategories');
                $attributes = [
                        'data-ajax--url'=>"subcategories",
                        //'data-parent' => !empty($recovery_category['id']) ? $recovery_category['id'] : '',
                        'data-parent' => !empty($recovery_subcategories_ids) ? $recovery_subcategories_ids : '',
                        'data-header' => '-- ' . trans('navigation.form_any') . ' --',
                        'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                        'class' => 'form-control',
                        'id' => 'recovery_subcategories_ids'
                ];
                $css_state = '';
                if (!count($recovery_subcategories) > 0) {
                    $attributes['disabled'] = 'disabled';
                    $css_state .= 'collapse ';
                }

                //if (!empty($recovery_subcategory['id'])) {
                if (!empty($recovery_subcategories_ids)) {
                    $css_state = 'has-success';
                }
                if ($errors->has('recovery_subcategories_ids')) {
                    $css_state = 'has-error';
                }
            ?>
                <div class="subcategories form-group col-sm-6 {!! $css_state !!}">
                    {!! Form::label('recovery_subcategories_ids', $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label']) !!}
                    <div class="col-xs-{{ ($isAdmin) ? 8 : 12 }} col-sm-{{ ($isAdmin) ? 4 : 7 }}">
                        {!! Form::select('recovery_subcategories_ids', $recovery_subcategories, $recovery_subcategories_ids, $attributes) !!}
                    </div>
                    @if ($isAdmin)
                        <div class="col-xs-4 col-sm-3">
                            {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.subcategories.create', '<i class="fa fa-plus fa-fw"></i>Add', [], ['class'=>'blank btn btn-sm btn-success btn-block'])) !!}
                        </div>
                    @endif
                </div>
            @endif
        </section>

        <section class="row">
            @if (isset($recovery_manufacturers))
            <?php
                if(!empty($recovery_manufacturers)) {
                    $array = json_decode(json_encode($recovery_manufacturers), true);
                    asort($array);
                    $recovery_manufacturers = $array;
                }
                $recovery_manufacturers_id = old('recovery_manufacturers_id', !empty($bodcaracts->recovery_manufacturers_id)?$bodcaracts->recovery_manufacturers_id:'');
                $label_txt = $label_txt_recovery_manufacturers;
                $attributes = [
                    'data-ajax--url'=>"/ajax-manufacturer",
                    'data-placeholder' => $label_txt,
                    'placeholder' => $label_txt,
                    'class' => 'form-control select2',
                    'id' => 'recovery_manufacturers_id'
                ];
                $css_state = '';
                if (!count($recovery_manufacturers) > 0) {
                    $attributes['disabled'] = 'disabled';
                    $css_state .= 'collapse ';
                }
                //if (!empty($recovery_manufacturer['id'])) {
                if (!empty($recovery_manufacturers_id)) {
                    $css_state = 'has-success';
                }
                if ($errors->has('recovery_manufacturers_id')) {
                    $css_state = 'has-error';
                }
            ?>
            <div class="manufacturers form-group col-sm-6 {!! $css_state !!}">
                {!! Form::label('recovery_manufacturers_id', $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label']) !!}
                <div class="col-xs-{{ ($isAdmin) ? 8 : 12 }} col-sm-{{ ($isAdmin) ? 4 : 7 }}">
                    {!! Form::select('recovery_manufacturers_id',!empty($recovery_manufacturer['id']) ? [$recovery_manufacturer['id']=>$recovery_manufacturer['name']] : [], !empty($recovery_manufacturer['id']) ? $recovery_manufacturer['id'] : $recovery_manufacturers_id,  $attributes) !!}
                </div>
                @if ($isAdmin)
                    <div class="col-xs-4 col-sm-3">
                        {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.manufacturers.create', '<i class="fa fa-plus fa-fw"></i>Add', [], ['class'=>'blank btn btn-sm btn-success btn-block'])) !!}
                    </div>
                @endif
            </div>
            @endif
            @if (isset($recovery_models))
            <?php
                if(!empty($recovery_models)) {
                    $array = json_decode(json_encode($recovery_models), true);
                    asort($array);
                    $recovery_models = $array;
                }
                $recovery_models_id = old('recovery_models_id', !empty($bodcaracts->recovery_models_id)?$bodcaracts->recovery_models_id:'');
                $label_txt = trans('filters.models');
                $attributes = [
                    'ajax-url'=>"/ajax-models",
                    'data-parent' => !empty($recovery_manufacturers_id) ? $recovery_manufacturers_id : '',
                    'data-placeholder' => $label_txt,
                    'placeholder' => $label_txt,
                    'class' => 'form-control',
                    'id' => 'recovery_models_id'
                ];
                $css_state = '';
                if (!count($recovery_models) > 0 && !isset($recovery_models_id)) {
                    $attributes['disabled'] = 'disabled';
                    $css_state .= 'collapse ';
                }
                //if (!empty($recovery_model['id'])) {
                if (!empty($recovery_models_id)) {
                    $css_state = 'has-success';
                }
                if ($errors->has('recovery_models_id')) {
                    $css_state = 'has-error';
                }
            ?>
            <div class="models form-group col-sm-6 {!! $css_state !!}">
                {!! Form::label('recovery_models_id', $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label']) !!}
                <div class="col-xs-{{ ($isAdmin) ? 8 : 12 }} col-sm-{{ ($isAdmin) ? 4 : 7 }}">
                    {!! Form::select('recovery_models_id', $recovery_models, $recovery_models_id, $attributes) !!}
                </div>
                @if ($isAdmin)
                    <div class="col-xs-4 col-sm-3">
                        {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.models.create', '<i class="fa fa-plus fa-fw"></i>Add', [], ['class'=>'blank btn btn-sm btn-success btn-block'])) !!}
                    </div>
                @endif
            </div>
            @endif
        </section>

        <section class="row">
            <?php
                $years = [];
                $label_txt = trans('filters.year_built');
                $attributes = [
                    'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                    'data-placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                    'class' => 'form-control ',
                    'id' => 'recovery_year_built'
                ];
                $css_state = '';
                if (!empty($recovery_year_built)) {
                    $css_state = 'has-success';
                }
                if ($errors->has('recovery_year_built')) {
                    $css_state = 'has-error';
                }
                $curYear = date("Y");
            ?>
            <div class="form-group col-sm-6 {!! $css_state !!}">
                {!! Form::label('recovery_year_built', $label_txt, ['class'=>'col-xs-8 col-sm-5 control-label']) !!}
                <div class="col-xs-4 col-sm-7">
                    {{--{!! Form::select('recovery_year_built', array_reverse($years,true), !empty($recovery_year_built) ? $recovery_year_built : old('recovery_year_built'), $attributes) !!}--}}
                    {!! Form::selectRange('recovery_year_built', $curYear, 1860, !empty($recovery_year_built) ? $recovery_year_built : old('recovery_ear_built'), $attributes) !!}
                </div>
            </div>
        </section>

        <hr>

        <h4 class="strong">{!! ucfirst(trans('boat_on_demand.additional_informations')) !!}</h4>

        <section class="row">
            <div class="form-group col-sm-6 {{ !empty($recovery_description) ? 'has-success' : '' }}">
                {!! Form::label('recovery_description', ucfirst(trans('validation.attributes.comment')), ['class'=>'col-xs-12 col-sm-5 control-label']) !!}
                <div class="col-xs-12 col-sm-7">
                    <?php
                    $placeholder = trans('navigation.form_enter_placeholder');
                    $attributes = [
                        'placeholder' => $placeholder,
                        'class' => 'form-control',
                        'id' => 'recovery_description'
                    ];
                    ?>
                    {!! Form::textarea('recovery_description', !empty($recovery_description) ? $recovery_description : old('recovery_description'), $attributes) !!}
                </div>
            </div>
            <div class="form-group col-sm-6 {{ !empty($recovery_budget) ? 'has-success' : '' }}">
                {!! Form::label('recovery_budget', ucfirst(trans('boat_on_demand.wish_price')), ['class'=>'col-sm-5 control-label']) !!}
                <div class="col-sm-7">
                    <?php
                    $placeholder = trans('navigation.form_enter_placeholder');
                    $attributes = [
                        'placeholder' => $placeholder,
                        'class' => 'form-control',
                        'id' => 'recovery_budget'
                    ];
                    ?>
                    <div class="input-group">
                        {!! Form::text('recovery_budget', !empty($recovery_budget) ? $recovery_budget : old('recovery_budget'), $attributes) !!}
                        <span class="input-group-addon">{!! config('youboat.'. $country_code .'.currency') !!}</span>
                    </div>
                </div>
            </div>
        </section>

        <div class="clearfix"></div>

    @if (!isset($form_action))
        <div class="form-group">
            <div class="col-xs-offset-3 col-sm-offset-4 col-xs-12 col-sm-7 text-center">
                {!! Form::button('<i class="fa fa-btn fa-search fa-fw"></i>' . trans('navigation.submit'), ['type' => 'submit', 'class' => 'btn btn-lg btn-block btn-primary']) !!}
            </div>
        </div>
        {!! Form::close() !!}
    @endif
    </div>
</section>