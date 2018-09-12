<?php
    if (!empty($datasRequest) && count($datasRequest)>0) {
        $ci_firstname              = isset($ci_firstname) ? $ci_firstname : (!empty($datasRequest['ci_firstname']) ? $datasRequest['ci_firstname'] : null);
        $ci_last_name              = isset($ci_last_name) ? $ci_last_name : (!empty($datasRequest['ci_last_name']) ? $datasRequest['ci_last_name'] : null);
        $ci_email                  = isset($ci_email) ? $ci_email : (!empty($datasRequest['ci_email']) ? $datasRequest['ci_email'] : null);
        $ci_phone                  = isset($ci_phone) ? $ci_phone : (!empty($datasRequest['ci_phone']) ? $datasRequest['ci_phone'] : null);
        $ci_phone = preg_replace('/\s+/', '', $ci_phone);

        $ci_password               = isset($ci_password) ? $ci_password : (!empty($datasRequest['ci_password']) ? $datasRequest['ci_password'] : null);

        $ci_zip                    = isset($ci_zip) ? $ci_zip : (!empty($datasRequest['ci_zip']) ? $datasRequest['ci_zip'] : null);
        $ci_city                   = isset($ci_city) ? $ci_city : (!empty($datasRequest['ci_city']) ? $datasRequest['ci_city'] : null);
        $ci_country                = isset($ci_country) ? $ci_country : (!empty($datasRequest['ci_countries_id']) ? Search::getCountry ($datasRequest['ci_countries_id'])['id'] : Search::getCountry(config('youboat.'. $country_code .'.country_code'))['id']);
        $agree_emails              = isset($agree_emails) ? $agree_emails : (!empty($datasRequest['agree_emails']) ? $datasRequest['agree_emails'] : null);
        $agree_cgv                 = isset($agree_cgv) ? $agree_cgv : (!empty($datasRequest['agree_cgv']) ? $datasRequest['agree_cgv'] : null);
        $ci_description            = isset($ci_description) ? $ci_description : (!empty($datasRequest['ci_description']) ? $datasRequest['ci_description'] : null);
    } else {
        $ci_country = Search::getCountry(config('youboat.'. $country_code .'.country_code'));
    }

    if (Auth::check()) {
        $ci_firstname              = !empty($customerscaracts['firstname']) ? $customerscaracts['firstname'] : (!empty($datasRequest['ci_firstname']) ? $datasRequest['ci_firstname'] : null);
        $ci_firstname              = ucwords(mb_strtolower($ci_firstname));
        $ci_last_name              = !empty($customerscaracts['name']) ? $customerscaracts['name'] : (!empty($datasRequest['ci_last_name']) ? $datasRequest['ci_last_name'] : null);
        $ci_last_name              = mb_strtoupper($ci_last_name);
        $ci_email                  = !empty($customerscaracts['email']) ? $customerscaracts['email'] : (!empty($datasRequest['ci_email']) ? $datasRequest['ci_email'] : null);
        $ci_phone                  = !empty($customerscaracts['phone_1']) ? $customerscaracts['phone_1'] : (!empty($datasRequest['ci_phone']) ? $datasRequest['ci_phone'] : null);
        $ci_phone = preg_replace('/\s+/', '', $ci_phone);
        $ci_zip                    = !empty($customerscaracts['zip']) ? $customerscaracts['zip'] : (!empty($datasRequest['ci_zip']) ? $datasRequest['ci_zip'] : null);
        $ci_city                   = !empty($customerscaracts['city']) ? $customerscaracts['city'] : (!empty($datasRequest['ci_city']) ? $datasRequest['ci_city'] : null);
        $ci_city                   = mb_strtoupper($ci_city);
        $ci_country                = !empty($customerscaracts['country_id']) ? $customerscaracts['country_id'] : (!empty($datasRequest['ci_countries_id']) ? Search::getCountry ($datasRequest['ci_countries_id'])['id'] : Search::getCountry(config('youboat.'. $country_code .'.country_code'))['id']);
        $agree_emails              = !empty($customerscaracts['agree_emails']) ? $customerscaracts['agree_emails'] : (!empty($datasRequest['agree_emails']) ? $datasRequest['agree_emails'] : null);
        $agree_cgv                  = 1;
    }
?>
@if (!isset($form_action))
    {!! Form::open(array('url'=>trans_route($currentLocale, 'routes.contact-informations'), 'class'=>'form-horizontal', 'id'=>'form_contact_informations', 'autocomplete'=>'off')) !!}
    {!! csrf_field() !!}
    {!! Form::hidden('country_code', $country_code) !!}
@endif
@if (Auth::guest())
<section class="row">
    <div class="col-sm-6">
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
                    {!! Form::text('ci_firstname', !empty($ci_firstname) ? $ci_firstname : old('ci_firstname'), $attributes) !!}
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
                    'class' => 'form-control',
                    'id' => 'ci_last_name'
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
                    {!! Form::text('ci_last_name', !empty($ci_last_name) ? $ci_last_name : old('ci_last_name'), $attributes) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6">
        <?php
            $label_txt = ucfirst(trans('validation.attributes.email'));
            $placeholder = trans('navigation.form_enter_placeholder');
            $attributes = [
                    'required'=>'required',
                    'data-placeholder' => $placeholder,
                    'placeholder' => $placeholder,
                    'class' => 'form-control',
                    'id' => 'ci_email'
            ];
            $css_state = '';
            if (!empty($ci_email)) {
                $css_state = 'has-success';
            }
            if ($errors->has('ci_email') || $errors->has('email')) {
                $css_state = 'has-error';
            }
        ?>
        <div class="form-group {!! $css_state !!}">
            {!! Form::label('ci_email', $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label']) !!}
            <div class="col-xs-12 col-sm-7">
                <div class="input-group">
                    {!! Form::email('ci_email', !empty($ci_email) ? $ci_email : old('ci_email'), $attributes) !!}
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
                    'class' => 'form-control',
                    'id' => 'ci_phone'
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
                    {!! Form::tel('ci_phone', !empty($ci_phone) ? $ci_phone : old('ci_phone'), $attributes) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <hr>
        <h4>{!! trans('boat_on_demand.create_your_account_password') !!}</h4>
    </div>

    <div class="col-sm-6">
        <?php
            $label_txt = ucfirst(trans('validation.attributes.password'));
            $placeholder = trans('validation.attributes.password') . '*';
            $attributes_pwd = [
                    'class' => 'form-control hidden'
            ];
            $attributes = [
                    'required'=>'required',
                    'data-placeholder' => $placeholder,
                    'placeholder' => $placeholder,
                    'class' => 'form-control password-input',
                //'class' => 'form-control',
                    'id' => 'ci_password'
            ];

            $css_state = '';
            if (!empty($ci_password)) {
                $css_state = 'has-success';
            }
            if ($errors->has('ci_password')) {
                $css_state = 'has-error';
            }
        ?>
            <div class="form-group {!! $css_state !!}">
                {!! Form::label('ci_password', $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label']) !!}
                <div class="col-xs-12 col-sm-7">
                    <div class="input-group">
                        {!! Form::password('ci_password', $attributes_pwd) !!}
                        {!! Form::input('password', 'ci_password', !empty($ci_password) ? $ci_password : null, $attributes) !!}
                        {{--<a href="javascript:void(0);" class="btn btn-xs btn-info password-generate pull-right" title="{!! trans('passwords.generate') !!} {!! trans('validation.attributes.password') !!}">{!! trans('passwords.generate') !!} {!! trans('validation.attributes.password') !!}</a>--}}
                    </div>
                </div>
            </div>
        </div>
        {{--<div class="col-sm-6">
            <div class="form-group">
                {!! Form::label('password_strenght', ucfirst(trans('passwords.password_strenght')), ['class'=>'col-xs-12 col-sm-5 control-label']) !!}
                <div class="col-xs-12 col-sm-7">
                    <div class="progress"><div class="progress-bar password-output" style="width: 0%"></div></div>
                </div>
            </div>
        </div>--}}

</section>

<hr>

<section class="row">
    <div class="col-sm-6 col-md-4">
        <?php
            $label_txt = ucfirst(trans('validation.attributes.zip'));
            $placeholder = trans('navigation.form_enter_placeholder');
            $attributes = [
                'data-placeholder' => $placeholder,
                'placeholder' => $placeholder,
                'class' => 'form-control',
                'id' => 'ci_zip'
            ];

            $css_state = '';
            if (!empty($ci_zip)) {
                $css_state = 'has-success';
            }
            if ($errors->has('ci_zip')) {
                $css_state = 'has-error';
            }
        ?>
        <div class="form-group {!! $css_state !!}">
            {!! Form::label('ci_zip', $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label']) !!}
            <div class="col-xs-12 col-sm-7">
                <div class="input-group">
                    {!! Form::text('ci_zip', !empty($ci_zip) ? $ci_zip : old('ci_zip'), $attributes) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        <?php
            $label_txt = ucfirst(trans('validation.attributes.city'));
            $placeholder = trans('navigation.form_enter_placeholder');
            $attributes = [
                    'data-placeholder' => $placeholder,
                    'placeholder' => $placeholder,
                    'class' => 'form-control',
                    'id' => 'ci_city'
            ];

            $css_state = '';
            if (!empty($ci_city)) {
                $css_state = 'has-success';
            }
            if ($errors->has('ci_city')) {
                $css_state = 'has-error';
            }
        ?>
        <div class="form-group {!! $css_state !!}">
            {!! Form::label('ci_city', $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label']) !!}
            <div class="col-xs-12 col-sm-7">
                <div class="input-group">
                    {!! Form::text('ci_city', !empty($ci_city) ? $ci_city : old('ci_city'), $attributes) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-4">
        @if (isset($countries))
            <?php
                $label_txt = ucfirst(trans('validation.attributes.country'));
                $placeholder = trans('navigation.form_select_placeholder');
                $attributes = [
                        'data-placeholder' => $placeholder,
                        'placeholder' => $placeholder,
                        'class' => 'form-control',
                        'id' => 'ci_countries_id'
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
                            {!! Form::select('ci_countries_id', $countries, !empty($ci_country) ? $ci_country : old('ci_countries_id'), $attributes) !!}
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>

<hr>
@elseif (
    Auth::check() && (
        empty($ci_last_name) ||
        empty($ci_phone) ||
        (!empty($ci_last_name) && $errors->has('ci_last_name')) ||
        (!empty($ci_phone) && $errors->has('ci_phone'))
    )
)

    {!! Form::hidden('ci_firstname', $ci_firstname) !!}
    @if(empty($ci_last_name) || (!empty($ci_last_name) && $errors->has('ci_last_name')))
    <div class="col-sm-6">
        <?php
            $label_txt = ucfirst(trans('validation.attributes.last_name'));
            $placeholder = trans('navigation.form_enter_placeholder');
            $attributes = [
                    'required'=>'required',
                    'data-placeholder' => $placeholder,
                    'placeholder' => $placeholder,
                    'class' => 'form-control',
                    'id' => 'ci_last_name'
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
                    {!! Form::text('ci_last_name', !empty($ci_last_name) ? $ci_last_name : old('ci_last_name'), $attributes) !!}
                </div>
            </div>
        </div>
    </div>
    @else
        {!! Form::hidden('ci_last_name', $ci_last_name) !!}
    @endif
        {!! Form::hidden('ci_email', $ci_email) !!}
    @if(empty($ci_phone) || (!empty($ci_phone) && $errors->has('ci_phone')))
    <div class="col-sm-6">
        <?php
            $label_txt = ucfirst(trans('validation.attributes.phone'));
            $placeholder = trans('navigation.form_enter_placeholder');
            $attributes = [
                    'required'=>'required',
                    'data-placeholder' => $placeholder,
                    'placeholder' => $placeholder,
                    'class' => 'form-control',
                    'id' => 'ci_phone'
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
                    {!! Form::tel('ci_phone', !empty($ci_phone) ? $ci_phone : old('ci_phone'), $attributes) !!}
                </div>
            </div>
        </div>
    </div>
    @else
        {!! Form::hidden('ci_phone', $ci_phone) !!}
    @endif
    {!! Form::hidden('ci_zip', $ci_zip) !!}
    {!! Form::hidden('ci_city', $ci_city) !!}
    {!! Form::hidden('ci_countries_id', $ci_country) !!}
    {{--{!! Form::hidden('ci_regions_id', $customerscaracts['region_id']) !!}
    {!! Form::hidden('ci_counties_id', $customerscaracts['county_id']) !!}--}}
    {!! Form::hidden('agree_emails', $agree_emails) !!}
    {!! Form::hidden('agree_cgv', $agree_cgv) !!}
@else
        {!! Form::hidden('ci_firstname', $ci_firstname) !!}
        {!! Form::hidden('ci_last_name', $ci_last_name) !!}
        {!! Form::hidden('ci_email', $ci_email) !!}
        {!! Form::hidden('ci_phone', $ci_phone) !!}
        {!! Form::hidden('ci_zip', $ci_zip) !!}
        {!! Form::hidden('ci_city', $ci_city) !!}
        {!! Form::hidden('ci_countries_id', $ci_country) !!}
        {{--{!! Form::hidden('ci_regions_id', $customerscaracts['region_id']) !!}
        {!! Form::hidden('ci_counties_id', $customerscaracts['county_id']) !!}--}}
        {!! Form::hidden('agree_emails', $agree_emails) !!}
        {!! Form::hidden('agree_cgv', $agree_cgv) !!}
@endif
<section class="row">
    <?php
        $label_txt = ucfirst(trans('validation.attributes.comment'));
        $placeholder = trans('navigation.form_enter_placeholder');
        $attributes = [
                'rows' => 5,
                'data-placeholder' => $placeholder,
                'placeholder' => $placeholder,
                'class' => 'form-control',
                'id' => 'ci_description'
        ];
        $css_state = '';
        if (!empty($ci_description)) {
            $css_state = 'has-success';
        }
        if ($errors->has('ci_description')) {
            $css_state = 'has-error';
        }
    ?>
    <div class="form-group {!! Auth::guest() ? 'col-sm-6' : 'col-sm-12' !!} {!! $css_state !!}">
        {!! Form::label('ci_description', $label_txt, ['class'=>'col-xs-12 ' . Auth::guest() ? 'col-sm-5' : 'col-sm-12' . ' control-label']) !!}
        <div class="col-xs-12 {!! Auth::guest() ? 'col-sm-7' : 'col-sm-12' !!}">
            <div class="input-group">
                {!! Form::textarea('ci_description', !empty($ci_description) ? $ci_description : old('$ci_description'), $attributes) !!}
            </div>
        </div>
    </div>

    @if (Auth::guest())
    <div class="col-md-6">
        <?php
            $label_txt = ucfirst(trans('contact_informations.label_optin_agree_emails'));
            $css_state = '';
            if (!empty($agree_emails) && '1' == $agree_emails) {
                $css_state = 'has-success';
                $attributes = [
                    'id'=>'agree_emails',
                    //'checked'=>'checked'
                ];
                $checkbox_attributes = [
                    'id'=>'checkbox_agree_emails',
                    'checked'=>'checked'
                ];
            } else {
                $attributes = [
                    'id'=>'agree_emails'
                ];
                $checkbox_attributes = [
                    'id'=>'checkbox_agree_emails'
                ];
            }
            if ($errors->has('agree_emails')) {
                $css_state = 'has-error';
            }
        ?>
        <div class="form-group {!! $css_state !!}">
            <div class="col-xs-12">
                <div class="checkbox {!! $css_state !!}">
                    <label for="checkbox_agree_emails">
                        {!! Form::checkbox('checkbox_agree_emails', !empty($agree_emails) ? true : false, !empty($agree_emails) ? $agree_emails : old('agree_emails', false), $checkbox_attributes) !!}
                        {!! Form::hidden('agree_emails', !empty($agree_emails) ? $agree_emails : old('agree_emails', false), $attributes) !!}
                        {!! $label_txt !!}
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <?php
            $url    = url(trans_route($currentLocale, 'routes.cgv'));
            $title = trans('navigation.read_the_terms');
            $terms_link = '(<a href="' . $url . '" title="' . title_case($title) . '" class="accent-color-danger strong blank">' . $title .'</a>)';
            $label_txt = ucfirst(trans('contact_informations.label_optin_agree_cgv', ['terms'=>htmlspecialchars_decode(title_case(trans('navigation.cgv'))),'website_name'=>$website_name])) ;
            $css_state = '';
            if (!empty($agree_cgv) && '1' == $agree_cgv) {
                $css_state = 'has-success';
                $attributes = [
                    'id'=>'agree_cgv',
                    'required'=>'required'
                ];
                $checkbox_attributes = [
                    'id'=>'checkbox_agree_cgv',
                    'required'=>'required',
                    'checked'=>'checked'
                ];
            } else {
                $attributes = [
                    'id'=>'agree_cgv',
                    'required'=>'required'
                ];
                $checkbox_attributes = [
                    'id'=>'checkbox_agree_cgv',
                    'required'=>'required'
                ];
            }
            if ($errors->has('agree_cgv')) {
                $css_state = 'has-error';
            }
        ?>
        <div class="form-group">
            <div class="col-xs-12">
                <div class="checkbox {!! $css_state !!}">
                    <label class="checkbox" for="checkbox_agree_cgv">
                        {!! Form::checkbox('checkbox_agree_cgv', !empty($agree_cgv) ? true : false, !empty($agree_cgv) ? $agree_cgv : old('agree_cgv', false), $checkbox_attributes) !!}
                        {!! Form::hidden('agree_cgv', !empty($agree_cgv) ? $agree_cgv : old('agree_cgv', false), $attributes) !!}
                        {!! $label_txt !!}
                    </label>
                </div>
                {!! $terms_link !!}
            </div>
        </div>
    </div>
    @endif
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
