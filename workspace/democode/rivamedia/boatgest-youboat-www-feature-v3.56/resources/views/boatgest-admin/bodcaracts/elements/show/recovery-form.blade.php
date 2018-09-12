<?php
    $trade_in = false;

    $recovery_adstype                = !empty($bodcaracts->recovery_adstypes_id) ? Search::getAdsTypeById($bodcaracts->recovery_adstypes_id) : null;
    $recovery_category               = !empty($bodcaracts->recovery_categories_ids) ? Search::getCategoryById($bodcaracts->recovery_categories_ids) : null;
    $recovery_subcategory            = !empty($bodcaracts->recovery_subcategories_ids) ? Search::getSubcategoryById($bodcaracts->recovery_subcategories_ids) : null;

    $recovery_manufacturer           = !empty($bodcaracts->recovery_manufacturers_id) ? Search::getManufacturerById($bodcaracts->recovery_manufacturers_id) : null;
    $recovery_model                  = !empty($bodcaracts->recovery_models_id) ? Search::getModelById($bodcaracts->recovery_models_id) : null;

    $recovery_year_built             = !empty($bodcaracts->recovery_year_built) ? $bodcaracts->recovery_year_built : null;

    $recovery_additional_informations = false;
    $recovery_budget                 = !empty($bodcaracts->recovery_budget) ? $bodcaracts->recovery_budget : null;
    $recovery_description            = !empty($bodcaracts->recovery_description) ? $bodcaracts->recovery_description : null;

    if(!empty($recovery_budget) || !empty($recovery_description)) {
        $recovery_additional_informations = true;
    }

    if(!empty($recovery_adstype) || !empty($recovery_category) || !empty($recovery_subcategory)
        || !empty($recovery_manufacturer) || !empty($recovery_model) || !empty($recovery_year_built)
        || $recovery_additional_informations) {
        $trade_in = true;
    }

    $label_txt_recovery_manufacturers = trans('filters.manufacturers') . '/' . trans('filters.shipyards');
    /*
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
    */
    $attributes = ['class' => 'form-control', 'readonly' => 'readonly'];
?>
@if($trade_in)
<section class="well well-sm alert-warning">
    <h3 class="strong">{!! trans('boat_on_demand.trade_in') !!}</h3>
    <div class="well well-sm well-white" id="collapseRecovery">
        <h4 class="strong">{!! trans('boat_on_demand.features_title') !!}</h4>
        <section>
            <div class="row">
                @if(!empty($adstype))
                <div class="col-sm-6">
                    <?php
                    $label_txt = trans('filters.adstypes');
                    ?>
                    <div class="form-group">
                        {!! Form::label('recovery_adstype_name', $label_txt, ['class'=>'col-sm-6 col-md-4 control-label']) !!}
                        <div class="col-sm-6 col-md-8">
                            {!! Form::text('recovery_adstype_name', $recovery_adstype['name'], $attributes) !!}
                        </div>
                    </div>
                </div>
                <div class="col-sm-6"></div>
                @endif

                @if(!empty($recovery_category))
                    <div class="col-sm-6">
                        <?php
                        $label_txt = trans('filters.categories');
                        ?>
                        <div class="form-group">
                            {!! Form::label('recovery_category_name', $label_txt, ['class'=>'col-sm-6 col-md-4 control-label']) !!}
                            <div class="col-sm-6 col-md-8">
                                {!! Form::text('recovery_category_name', $recovery_category['name'], $attributes) !!}
                            </div>
                        </div>
                    </div>
                @endif

                @if(!empty($recovery_subcategory))
                    <div class="col-sm-6">
                        <?php
                        $label_txt = trans('filters.subcategories');
                        ?>
                        <div class="form-group">
                            {!! Form::label('recovery_subcategory_name', $label_txt, ['class'=>'col-sm-6 col-md-4 control-label']) !!}
                            <div class="col-sm-6 col-md-8">
                                {!! Form::text('recovery_subcategory_name', $recovery_subcategory['name'], $attributes) !!}
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="row">
                @if(!empty($recovery_manufacturer))
                <div class="col-sm-6">
                    <?php
                    $label_txt = $label_txt_manufacturers;
                    ?>
                    <div class="form-group">
                        {!! Form::label('recovery_manufacturer_name', $label_txt, ['class'=>'col-sm-6 col-md-4 control-label']) !!}
                        <div class="col-sm-6 col-md-8">
                            {!! Form::text('recovery_manufacturer_name', $recovery_manufacturer['name'], $attributes) !!}
                        </div>
                    </div>
                </div>
                @endif

                @if(!empty($recovery_model))
                <div class="col-sm-6">
                    <?php
                    $label_txt = trans('filters.models');
                    ?>
                    <div class="form-group">
                        {!! Form::label('recovery_model_name', $label_txt, ['class'=>'col-sm-6 col-md-4 control-label']) !!}
                        <div class="col-sm-6 col-md-8">
                            {!! Form::text('recovery_model_name', $recovery_model['name'], $attributes) !!}
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <div class="row">
                <div class="col-sm-6">
                    @if(!empty($recovery_year_built))
                        <?php
                        $label_txt = trans('filters.year_built');
                        ?>
                        <div class="form-group">
                            {!! Form::label('recovery_year_built', $label_txt, ['class'=>'col-xs-7 col-sm-6 col-md-4 control-label']) !!}
                            <div class="col-xs-5 col-sm-6 col-md-8">
                                {!! Form::text('recovery_year_built', $recovery_year_built, $attributes) !!}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>

        @if($recovery_additional_informations)
        <hr>

        <h4 class="strong">{!! ucfirst(trans('boat_on_demand.additional_informations')) !!}</h4>
        <section>
            <div class="row">
                @if(!empty($recovery_description))
                <div class="col-sm-6">
                    <?php
                    $label_txt = ucfirst(trans('validation.attributes.comment'));
                    ?>
                    <div class="form-group">
                        {!! Form::label('recovery_description', $label_txt, ['class'=>'col-sm-6 col-md-4 control-label']) !!}
                        <div class="col-sm-6 col-md-8">
                            {!! Form::textarea('recovery_description', $recovery_description, $attributes) !!}
                        </div>
                    </div>
                </div>
                @endif

                @if(!empty($recovery_budget))
                <div class="col-sm-6">
                    <?php
                    $label_txt = ucfirst(trans('validation.attributes.comment'));
                    ?>
                    <div class="form-group">
                        {!! Form::label('recovery_budget', $label_txt, ['class'=>'col-sm-6 col-md-4 control-label']) !!}
                        <div class="col-sm-6 col-md-8">
{{--                            {!! Form::textarea('recovery_budget', trim(preg_replace('!\s+!', ' ', money_format('%= (#10.0n', $recovery_budget))), $attributes) !!}--}}
                            {!! Form::textarea('recovery_budget', formatPrice($recovery_budget), $attributes) !!}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </section>
        @endif
    </div>
</section>
@endif