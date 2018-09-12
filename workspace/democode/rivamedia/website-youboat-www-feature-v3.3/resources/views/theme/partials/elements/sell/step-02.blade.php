<?php
    $attributes_readonly = [];
    if (Auth::check()) {
        $ci_firstname              = !empty($privatescaracts['firstname']) ? $privatescaracts['firstname'] : (!empty($datasRequest['ci_firstname']) ? $datasRequest['ci_firstname'] : null);
        $ci_firstname              = ucwords(mb_strtolower($ci_firstname));
        $ci_last_name              = !empty($privatescaracts['name']) ? $privatescaracts['name'] : (!empty($datasRequest['ci_last_name']) ? $datasRequest['ci_last_name'] : null);
        $ci_last_name              = mb_strtoupper($ci_last_name);
        $ci_email                  = !empty($privatescaracts['email']) ? $privatescaracts['email'] : (!empty($datasRequest['ci_email']) ? $datasRequest['ci_email'] : null);

        $ci_phone                  = !empty($privatescaracts['phone_1']) ? $privatescaracts['phone_1'] : (!empty($datasRequest['ci_phone']) ? $datasRequest['ci_phone'] : null);
        $ci_phone = preg_replace('/\s+/', '', $ci_phone);

        $ci_zip                    = !empty($privatescaracts['zip']) ? $privatescaracts['zip'] : (!empty($datasRequest['ci_zip']) ? $datasRequest['ci_zip'] : null);
        $ci_city                   = !empty($privatescaracts['city']) ? $privatescaracts['city'] : (!empty($datasRequest['ci_city']) ? $datasRequest['ci_city'] : null);
        $ci_city                   = mb_strtoupper($ci_city);
        $ci_country                = !empty($privatescaracts['country_id']) ? $privatescaracts['country_id'] : (!empty($datasRequest['ci_countries_id']) ? Search::getCountry ($datasRequest['ci_countries_id'], true)['id'] : Search::getCountry(config('youboat.'. $country_code .'.country_code'))['id']);

        $ci_full_address = '';
        if(!empty($ci_zip)) {
            $ci_full_address .= str_replace(['N/A'], [''], $ci_zip) . ', ';
        }
        if(!empty($ci_city)) {
            $ci_full_address .= str_replace(['N/A'], [''], $ci_city) . ', ';
        }
        if(!empty($ci_country)) {
            $getCountryById = Search::getCountry($ci_country, true);
            $ci_country_name = array_key_exists('name', $getCountryById) && !empty($getCountryById['name']) ? $getCountryById['name'] : '';
            if (!empty($ci_country_name)) {
                $ci_full_address .= $ci_country_name . ', ';
            }
        }
        $ci_full_address = str_replace([", Unknown"], [''], $ci_full_address);
        $ci_full_address = preg_replace("/, $/i", '', $ci_full_address);
        $ci_full_address = preg_replace("/,$/", '', $ci_full_address);

        $agree_emails              = !empty($privatescaracts['agree_emails']) ? $privatescaracts['agree_emails'] : (!empty($datasRequest['agree_emails']) ? $datasRequest['agree_emails'] : null);
        $agree_cgv                  = 1;
        $ci_description            = !empty($privatescaracts['description']) ? $privatescaracts['description'] : (!empty($datasRequest['ci_description']) ? $datasRequest['ci_description'] : null);
        $attributes_readonly       = ['readonly'=>'readonly'];
    } else if (!empty($datasRequest) && count($datasRequest)>0) {
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
?>
    <section class="row">
        <div class="col-sm-6">
            <?php
            $ci_firstname = !empty($ci_firstname) ? ucfirst(mb_strtolower($ci_firstname)) : old('ci_firstname');
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
                        {!! Form::text('ci_firstname', ucfirst(mb_strtolower($ci_firstname)), $attributes+$attributes_readonly) !!}
                    </div>
                </div>
            </div>
            <?php
            $ci_last_name = !empty($ci_last_name) ? mb_strtoupper($ci_last_name) : old('ci_last_name');
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
                        {!! Form::text('ci_last_name', mb_strtoupper($ci_last_name), $attributes+$attributes_readonly) !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <?php
                $ci_email = !empty($ci_email) ? $ci_email : old('ci_email');

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

                if ($errors->has('ci_email')) {
                    $css_state = 'has-error';
                }
            ?>
            <div class="form-group {!! $css_state !!}">
                {!! Form::label('ci_email', $label_txt, ['class'=>'col-xs-12 col-sm-5 control-label']) !!}
                <div class="col-xs-12 col-sm-7">
                    <div class="input-group">
                        {!! Form::email('ci_email', $ci_email, $attributes+$attributes_readonly) !!}
                    </div>
                </div>
            </div>

            <?php
            $ci_phone = !empty($ci_phone) ? $ci_phone : old('ci_phone');
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
                        {!! Form::tel('ci_phone', $ci_phone, $attributes+$attributes_readonly) !!}
                    </div>
                </div>
            </div>
        </div>
        @if (Auth::guest())
        <div class="col-sm-12">
            <hr>
            <h4>{!! trans('boat_on_demand.create_your_account_password') !!}</h4>
        </div>

        <div class="col-sm-6">
            <?php
            $ci_password = !empty($ci_password) ? $ci_password : old('ci_password');
            $label_txt = ucfirst(trans('validation.attributes.password'));
            $placeholder = trans('validation.attributes.password') . '*';
            $attributes_pwd = [
                    'class' => 'form-control'
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
                        {{--{!! Form::password('ci_password', $attributes_pwd) !!}--}}
                        {!! Form::input('password', 'ci_password', $ci_password, $attributes+$attributes_readonly) !!}
                    </div>
                </div>
            </div>
        </div>
        @endif
    </section>

    <hr>

    <section class="row">
    @if (Auth::guest())
        <div class="col-sm-6 col-md-4">
            <?php
            $ci_zip = !empty($ci_zip) ? $ci_zip : old('ci_zip');
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
                        {!! Form::text('ci_zip', $ci_zip, $attributes+$attributes_readonly) !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-4">
            <?php
            $ci_city = !empty($ci_city) ? $ci_city : old('ci_city');
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
                        {!! Form::text('ci_city', $ci_city, $attributes+$attributes_readonly) !!}
                    </div>
                </div>
            </div>
        </div>
        @if (isset($countries))
        <div class="col-sm-6 col-md-4">
        <?php
            $ci_country = !empty($ci_country) ? $ci_country : old('ci_countries_id');
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
                        {!! Form::text('ci_country_val', $countries->first(), $attributes+$attributes_readonly) !!}
                        {!! Form::hidden('ci_countries_id', $key) !!}
                    @else
                        {!! Form::select('ci_countries_id', $countries, $ci_country, $attributes+$attributes_readonly) !!}
                    @endif
                    </div>
                </div>
            </div>
        </div>
        @endif
    @elseif (Auth::check() && isset($ci_full_address))
        <div class="col-sm-12">
            <?php
            $label_txt = ucfirst(trans('validation.attributes.address'));
            $attributes = [
                    'disabled'=>'disabled',
                    'class' => 'form-control',
            ];
            $css_state = '';
            if (!empty($ci_full_address)) {
                $css_state = 'has-success';
            }
            ?>
            <div class="form-group {!! $css_state !!}">
                {!! Form::label('ci_full_address', $label_txt, ['class'=>'col-xs-12 text-success']) !!}
                <div class="col-xs-12">
                    <div class="input-group">
                        {!! Form::text('ci_full_address', $ci_full_address, $attributes) !!}
                    </div>
                </div>
            </div>
            {!! Form::hidden('ci_countries_id', $ci_country) !!}
        </div>
    @endif
    </section>

    <hr>
    <section class="row">
        @if (isset($ci_description) && !empty($ci_description))
        <?php
            $ci_description = !empty($ci_description) ? $ci_description : old('ci_description');
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
        <div class="form-group col-sm-12 {!! $css_state !!}">
            {!! Form::label('ci_description', $label_txt, ['class'=>'col-xs-12 ' . Auth::guest() ? 'col-sm-5' : 'col-sm-12' . ' control-label']) !!}
            <div class="col-xs-12 {!! Auth::guest() ? 'col-sm-7' : 'col-sm-12' !!}">
                <div class="input-group">
                    {!! Form::textarea('ci_description', $ci_description, $attributes) !!}
                </div>
            </div>
        </div>
        @endif

        @if (Auth::guest())
        <div class="col-md-6">
            <?php
            $agree_emails = !empty($agree_emails) ? $agree_emails : old('agree_emails', false);
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
                            {!! Form::checkbox('checkbox_agree_emails', !empty($agree_emails) ? true : false, $agree_emails, $checkbox_attributes) !!}
                            {!! Form::hidden('agree_emails', $agree_emails, $attributes) !!}
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
            $agree_cgv = !empty($agree_cgv) ? $agree_cgv : old('agree_cgv', false);
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
                            {!! Form::checkbox('checkbox_agree_cgv', !empty($agree_cgv) ? true : false, $agree_cgv, $checkbox_attributes) !!}
                            {!! Form::hidden('agree_cgv', $agree_cgv, $attributes) !!}
                            {!! $label_txt !!}
                        </label>
                    </div>
                    {!! $terms_link !!}
                </div>
            </div>
        </div>
        @else
            {!! Form::hidden('agree_cgv', $agree_cgv, []) !!}
        @endif
    </section>
    <div class="row step-navigation">
        <div class="col-xs-6 col-sm-4"><a class="btn-prev btn-step btn btn-primary btn-lg" data-current="#step_02" data-target="#step_01" title="{!! trans('navigation.back_to') . ' ' . trans('navigation.step') !!} 1">&laquo;<span class="hidden-xs">{!! trans('navigation.step') !!} 1</span></a></div>
    </div>
    <div class="clearfix"></div>

