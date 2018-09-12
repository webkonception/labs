{!! Form::open(array('url'=>'/ajax-enquiry', 'class'=>'form-horizontal ajax-form well well-white ', 'role'=>'form', 'id'=>'form_enquiry', 'autocomplete'=>'off')) !!}
<h4 class="widgettitle title">{!! trans('navigation.contact_the_seller') !!}</h4>
<div class="vehicle-enquiry-in inputs">
    {!! csrf_field() !!}
    {!! Form::hidden('locale', !empty($currentLocale) ? $currentLocale : '') !!}
    {!! Form::hidden('currency', !empty($pricing_currency) ? $pricing_currency : '') !!}
    {!! Form::hidden('ad_id', !empty($ad_id) ? $ad_id : '') !!}
    {!! Form::hidden('ad_url', !empty($ad_url) ? $ad_url : '') !!}
    {!! Form::hidden('ad_title', !empty($ad_title_page) ? strip_tags($ad_title_page) : '') !!}
    {!! Form::hidden('ad_type', !empty($ad_type) ? $ad_type : '') !!}
    {!! Form::hidden('adstypes_id', !empty($datasRequest['adstypes_id']) ? $datasRequest['adstypes_id'] : '') !!}
    {!! Form::hidden('ad_category', !empty($ad_category) ? $ad_category : '') !!}
    {!! Form::hidden('categories_ids', !empty($datasRequest['categories_ids']) ? $datasRequest['categories_ids'] : '') !!}
    {!! Form::hidden('ad_subcategory', !empty($ad_subcategory) ? $ad_subcategory : '') !!}
    {!! Form::hidden('subcategories_ids', !empty($datasRequest['subcategories_ids']) ? $datasRequest['subcategories_ids'] : '') !!}
    {!! Form::hidden('ad_manufacturer', !empty($ad_manufacturer) ? $ad_manufacturer : '') !!}
    {!! Form::hidden('manufacturers_id', !empty($datasRequest['manufacturers_id']) ? $datasRequest['manufacturers_id'] : '') !!}
    {!! Form::hidden('ad_model', !empty($ad_model) ? $ad_model : '') !!}
    {!! Form::hidden('models_id', !empty($datasRequest['models_id']) ? $datasRequest['models_id'] : '') !!}
    {!! Form::hidden('budget', !empty($budget) ? $budget : '') !!}
    {{--{!! Form::hidden('ad_budget', !empty($datasRequest['ad_price']) ? trim(preg_replace('!\s+!', ' ', money_format('%= (#10.0n', $datasRequest['ad_price']))) : '') !!}--}}
    {{--{!! Form::hidden('ad_budget', !empty($datasRequest['ad_price']) ? formatPrice($datasRequest['ad_price']) : '') !!}--}}
    {{--{!! Form::hidden('ad_budget', !empty($datasRequest['ad_price']) ? formatPriceCurrency($ad_price, $datasRequest['countries_id']) : '') !!}--}}
    {!! Form::hidden('ad_budget', !empty($ad_budget) ? $ad_budget : '') !!}
    {!! Form::hidden('sell_type', !empty($datasRequest['sell_type']) ? $datasRequest['sell_type'] : '') !!}
    {!! Form::hidden('country_code', !empty($country_code) ? $country_code : '') !!}
    <?php
        $label_txt = ucfirst(trans('validation.attributes.first_name'));
        $placeholder = trans('navigation.form_enter_placeholder');
        $attributes = [
                'data-placeholder' => $placeholder,
                'placeholder' => $placeholder,
                'class' => 'form-control',
                'id' => 'ci_firstname'
        ];
        $css_state = '';
        if (!empty($ci_firstname)) {
            $css_state = 'has-success';
        }
        if ($errors->has('ci_firstname')) {
            $css_state = 'has-error';
        }
    ?>
    <div class="form-group {!! $css_state !!}">
        {!! Form::label('ci_firstname', $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label']) !!}
        <div class="col-xs-12 col-sm-7">
            <div class="input-group">
                {!! Form::text('ci_firstname', isset($ci_firstname) ? $ci_firstname : old('ci_firstname'), $attributes) !!}
            </div>
        </div>
    </div>
    <?php
        $label_txt = ucfirst(trans('validation.attributes.last_name'));
        $placeholder = trans('navigation.form_enter_placeholder');
        $attributes = [
                'required'=>'required',
                'data-placeholder' => $placeholder,
                'placeholder' => $placeholder,
                'class' => 'form-control', 'id' => 'ci_last_name'
        ];
        $css_state = '';
        if (!empty($ci_last_name)) {
            $css_state = 'has-success';
        }
        if ($errors->has('ci_last_name')) {
            $css_state = 'has-error';
        }
    ?>
    <div class="form-group {!! $css_state !!}">
        {!! Form::label('ci_last_name', $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label']) !!}
        <div class="col-xs-12 col-sm-7">
            <div class="input-group">
                {!! Form::text('ci_last_name', isset($ci_last_name) ? $ci_last_name : old('ci_last_name'), $attributes) !!}
            </div>
        </div>
    </div>
    <?php
        $label_txt = ucfirst(trans('validation.attributes.email'));
        $placeholder = trans('navigation.form_enter_placeholder');
        $attributes = [
                'required'=>'required',
                'data-placeholder' => $placeholder,
                'placeholder' => $placeholder,
                'class' => 'form-control', 'id' => 'ci_email'
        ];
        $css_state = '';
        if (!empty($ci_email)) {
            $css_state = 'has-success';
        }
        if ($errors->has('ci_email')) {
            $css_state = 'has-error';
        }
    ?>
    <div class="form-group {!! $css_state !!}">
        {!! Form::label('ci_email', $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label']) !!}
        <div class="col-xs-12 col-sm-7">
            <div class="input-group">
                {!! Form::email('ci_email', isset($ci_email) ? $ci_email : old('ci_email'), $attributes) !!}
            </div>
        </div>
    </div>
    <?php
        $label_txt = ucfirst(trans('validation.attributes.phone'));
        $placeholder = trans('navigation.form_enter_placeholder');
        $attributes = [
                'required'=>'required',
                'data-placeholder' => $placeholder,
                'placeholder' => $placeholder,
                'class' => 'form-control', 'id' => 'ci_phone'
        ];
        $css_state = '';
        if (!empty($ci_phone)) {
            $css_state = 'has-success';
        }
        if ($errors->has('ci_phone')) {
            $css_state = 'has-error';
        }
    ?>
    <div class="form-group {!! $css_state !!}">
        {!! Form::label('ci_phone', $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label']) !!}
        <div class="col-xs-12 col-sm-7">
            <div class="input-group">
                {!! Form::tel('ci_phone', isset($ci_phone) ? $ci_phone : old('ci_phone'), $attributes) !!}
            </div>
        </div>
    </div>
    @if (isset($countries))
    <?php
    $label_txt = ucfirst(trans('validation.attributes.country'));
    $placeholder = trans('navigation.form_select_placeholder');
    $attributes = [
            'required'=>'required',
            'data-placeholder' => $placeholder,
            'placeholder' => $placeholder,
            'class' => 'form-control', 'id' => 'ci_countries_id'
    ];
    if (!count($countries) > 0) {
        $attributes['disabled'] = 'disabled';
    }
    $css_state = '';
    if (!empty($ci_country) || count($countries) === 1) {
        $css_state = 'has-success';
    }
    if ($errors->has('ci_countries_id')) {
        $css_state = 'has-error';
    }
    ?>
    <div class="form-group {!! $css_state !!}">
        {!! Form::label('ci_countries_id', $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label']) !!}
        <div class="col-xs-12 col-sm-7">
            <div class="input-group">
                @if (count($countries) === 1)
                    <?php
                    $array = json_decode(json_encode($countries), true);
                    $key = key($array);
                    ?>
                    {!! Form::text('ci_country_val', $countries->first(), $attributes) !!}
                    {!! Form::hidden('ci_countries_id', $key) !!}
                @else
                    {!! Form::select('ci_countries_id', $countries, isset($ci_country) ? $ci_country : old('ci_countries_id'), $attributes) !!}
                @endif
            </div>
        </div>
    </div>
    @endif
    <?php
        $label_txt = ucfirst(trans('validation.attributes.comment'));
        $placeholder = trans('navigation.form_enter_placeholder');
        $attributes = [
                'rows' => 5,
                'data-placeholder' => $placeholder,
                'placeholder' => $placeholder,
                'class' => 'form-control', 'id' => 'ci_description'
        ];
        $css_state = '';
        if (!empty($ci_description)) {
            $css_state = 'has-success';
        }
        if ($errors->has('ci_description')) {
            $css_state = 'has-error';
        }
    ?>
    <div class="form-group {!! $css_state !!}">
        {!! Form::label('ci_description', $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label text-left']) !!}
        <div class="col-xs-12 col-sm-7">
            <div class="input-group">
                {!! Form::textarea('ci_description', isset($ci_description) ? $ci_description : old('ci_description'), $attributes) !!}
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="col-xs-offset-1 col-sm-offset-5 col-xs-10 col-sm-7 text-center">
            {!! Form::button(trans('navigation.send_an_email'), ['type' => 'submit', 'data-ga'=>$view_name . '~' . trans('navigation.send_enquiry') . '|' . 'Ref. ' . $ad_url, 'class' => 'GA_event btn btn-block btn-primary btn-exception']) !!}
        </div>
    </div>
</div>
{!! Form::close() !!}
