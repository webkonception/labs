<?php
    if (!empty($datasRequest) && count($datasRequest)>0) {
        $ci_firstname              = isset($ci_firstname) ? $ci_firstname : (!empty($datasRequest['ci_firstname']) ? $datasRequest['ci_firstname'] : null);
        $ci_firstname              = ucwords(mb_strtolower($ci_firstname));
        $ci_last_name              = isset($ci_last_name) ? $ci_last_name : (!empty($datasRequest['ci_last_name']) ? $datasRequest['ci_last_name'] : null);
        $ci_last_name              = mb_strtoupper($ci_last_name);
        $ci_email                  = isset($ci_email) ? $ci_email : (!empty($datasRequest['ci_email']) ? $datasRequest['ci_email'] : null);
        $ci_email                  = mb_strtolower($ci_email);
        $ci_phone                  = isset($ci_phone) ? $ci_phone : (!empty($datasRequest['ci_phone']) ? $datasRequest['ci_phone'] : null);
        $ci_password               = isset($ci_password) ? $ci_password : (!empty($datasRequest['ci_password']) ? $datasRequest['ci_password'] : null);

        $ci_country                = isset($ci_country) ? $ci_country : (!empty($datasRequest['ci_countries_id']) ? Search::getCountry ($datasRequest['ci_countries_id']) : Search::getCountry(config('youboat.'. $country_code .'.country_code'))['id']);

        $ci_city                   = isset($ci_city) ? $ci_city : (!empty($datasRequest['ci_city']) ? $datasRequest['ci_city'] : null);
        $ci_city                   = mb_strtoupper($ci_city);
        $ci_zip                    = isset($ci_zip) ? $ci_zip : (!empty($datasRequest['ci_zip']) ? $datasRequest['ci_zip'] : null);
        $ci_description            = isset($ci_description) ? $ci_description : (!empty($datasRequest['ci_description']) ? $datasRequest['ci_description'] : null);
        $agree_emails              = isset($agree_emails) ? $agree_emails : (!empty($datasRequest['agree_emails']) ? $datasRequest['agree_emails'] : '');
        $agree_cgv                 = isset($agree_cgv) ? $agree_cgv : (!empty($datasRequest['agree_cgv']) ? $datasRequest['agree_cgv'] : '');
    } else {
        $ci_country = Search::getCountry(config('youboat.'. $country_code .'.country_code'));
    }
?>
<section class="well well-sm well-info alert-info">
    <h3 class="strong">{!! trans('boat_on_demand.customer_details') !!}</h3>
    <div class="well well-sm well-white">
        <h4 class="strong">{!! trans('boat_on_demand.contact_information') !!}</h4>
        @if (!isset($form_action))
        {!! Form::open(array('url'=>trans_route($currentLocale, 'routes.contact-informations'), 'class'=>'form-horizontal', 'id'=>'form_contact_informations', 'autocomplete'=>'off')) !!}
            {!! csrf_field() !!}
            {!! Form::hidden('country_code', $country_code) !!}
        @endif
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
                    {!! Form::label('ci_firstname', $label_txt, ['class'=>'col-sm-4 control-label']) !!}
                    <div class="col-sm-8">
                        {!! Form::text('ci_firstname', !empty($ci_firstname) ? $ci_firstname : old('ci_firstname'), $attributes) !!}
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
                    {!! Form::label('ci_last_name', $label_txt, ['class'=>'col-sm-4 control-label']) !!}
                    <div class="col-sm-8">
                        {!! Form::text('ci_last_name', !empty($ci_last_name) ? $ci_last_name : old('ci_last_name'), $attributes) !!}
                    </div>
                </div>
            </div>

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
                    {!! Form::label('ci_phone', $label_txt, ['class'=>'col-sm-4 control-label']) !!}
                    <div class="col-sm-8">
                        {!! Form::tel('ci_phone', !empty($ci_phone) ? $ci_phone : old('ci_phone'), $attributes) !!}
                    </div>
                </div>
            </div>
        </section><hr>

        <section class="row">
            <div class="col-sm-6">
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
                    {!! Form::label('ci_zip', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        {!! Form::text('ci_zip', !empty($ci_zip) ? $ci_zip : old('ci_zip'), $attributes) !!}
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
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
                    {!! Form::label('ci_city', $label_txt, ['class'=>'col-sm-4 control-label']) !!}
                    <div class="col-sm-8">
                        {!! Form::text('ci_city', !empty($ci_city) ? $ci_city : old('ci_city'), $attributes) !!}
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                @if (isset($countries))
                    <?php
                    $ci_countries_id = old('ci_countries_id', !empty($bodcaracts->ci_countries_id)?$bodcaracts->ci_countries_id:'');
                    //$ci_countries_code = Search::getCountryById($ci_countries_id,false)['code'];
                    $label_txt = ucfirst(trans('validation.attributes.country'));
                    $attributes = [
                            'data-header' => '-- ' . trans('navigation.form_any') . ' --',
                            'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                            'class' => 'form-control countries',
                            'id' => 'ci_countries_id'
                    ];
                    $css_state = '';
                    if (!count($countries) > 0) {
                        $attributes['disabled'] = 'disabled';
                        $css_state .= 'collapse ';
                    }
                    if (!empty($ci_countries_id) || count($countries) === 1) {
                        $css_state = 'has-success';
                    }
                    if ($errors->has('ci_countries_id')) {
                        $css_state = 'has-error';
                    }
                    ?>
                    <div class="form-group {!! $css_state !!}">
                        {!! Form::label('ci_countries_id', $label_txt, ['class'=>'col-sm-4 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::select('ci_countries_id', $countries, $ci_countries_id, $attributes) !!}
                        </div>
                    </div>
                @endif
            </div>
        </section>

        <hr>

        <section class="row">
            <div class="col-sm-6">
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
                <div class="form-group {!! $css_state !!}">
                    {!! Form::label('ci_description', $label_txt, ['class'=>'col-sm-4 control-label']) !!}
                    <div class="col-sm-8">
                        {!! Form::textarea('ci_description', !empty($ci_description) ? $ci_description : old('ci_description'), $attributes) !!}
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <?php
                $agree_emails = old('agree_emails', !empty($bodcaracts->agree_emails)?:0);
                $label_txt = ucfirst(trans('contact_informations.label_optin_agree_emails'));
                $css_state = '';
                if (!empty($agree_emails) && 1 === $agree_emails) {
                    $css_state = 'has-success';
                    $attributes = [
                            'id'=>'agree_emails',
                            'checked'=>'checked'
                    ];
                } else {
                    $attributes = [
                            'id'=>'agree_emails'
                    ];
                }
                if ($errors->has('agree_emails')) {
                    $css_state = 'has-error';
                }
                ?>
                <div class="form-group {!! $css_state !!}">
                    {!! Form::label('switch_agree_emails', $label_txt, ['class'=>'col-xs-9 col-sm-10 control-label']) !!}
                    <div class="col-xs-3 col-sm-2 material-switch">
                        {!! Form::checkbox('switch_agree_emails', 'active', ($agree_emails == 1) ? 'checked' : '', ['class'=>'switch', 'data-target'=>'agree_emails', 'data-default'=>0]) !!}
                        <label for="switch_agree_emails" class="label-success"></label>
                        <span></span>
                        {!! Form::hidden('agree_emails', $agree_emails, ['class'=>'form-control', 'id'=>'agree_emails']) !!}
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <?php
                $agree_cgv = old('agree_cgv', !empty($bodcaracts->agree_cgv)?:0);
                $label_txt = ucfirst(trans('contact_informations.label_optin_agree_cgv', ['terms'=>htmlspecialchars_decode(title_case(trans('navigation.cgv'))),'website_name'=>$website_name])) . '*';

                $url    = url(trans_route($currentLocale, 'routes.cgv'));
                $title = trans('navigation.read_the_terms');
                $terms_link = '(<a href="' . $url . '" title="' . title_case($title) . '" class="accent-color-danger strong blank">' . $title .'</a>)';

                $css_state = '';
                if (!empty($agree_cgv) && 1 === $agree_cgv) {
                    $css_state = 'has-success';
                    $attributes = [
                            'id'=>'agree_cgv',
                            'checked'=>'checked'
                    ];
                } else {
                    $attributes = [
                            'id'=>'agree_cgv'
                    ];
                }
                if ($errors->has('agree_cgv')) {
                    $css_state = 'has-error';
                }
                ?>
                <div class="form-group {!! $css_state !!}">
                    {!! Form::label('switch_agree_cgv', $label_txt, ['class'=>'col-xs-9 col-sm-10 control-label']) !!}
                    <div class="col-xs-3 col-sm-2 material-switch">
                        {!! Form::checkbox('switch_agree_cgv', 'active', ($agree_cgv == 1) ? 'checked' : '', ['class'=>'switch', 'data-target'=>'agree_cgv', 'data-default'=>0]) !!}
                        <label for="switch_agree_cgv" class="label-success"></label>
                        <span></span>
                        {!! Form::hidden('agree_cgv', $agree_cgv, ['class'=>'form-control', 'id'=>'agree_cgv']) !!}
                    </div>
                    <div class="col-xs-12 text-center">
                        {!! $terms_link !!}
                    </div>
                </div>
            </div>
        </section>
        <section class="row">
            <div class="col-sm-12">
                <hr>
                <h4 class="strong"">{!! trans('boat_on_demand.your_account_details') !!}</h4>
            </div>

            @if($isAdmin || 'commercial' == Auth::user()->type)
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
                if (!isset($form_action) && preg_match('/edit/i', $form_action)) {
                    $attributes['readonly'] = 'readonly';
                }
                $css_state = '';
                if (!empty($ci_email)) {
                    $css_state = 'has-success';
                }
                if ($errors->has('ci_email') || $errors->has('email')) {
                    $css_state = 'has-error';
                }

                $infoEmail = '';
                if (!empty($ci_email)) {
                    $fromemail                  = config('youboat.' . $country_code . '.MAIL_NO_REPLY_EMAIL');
                    $verifyEmail                = verifyEmail($ci_email, $fromemail, true);

                    if(is_array($verifyEmail) && !empty($verifyEmail[0])) {
                        $infoEmail = '<br>';
                        $infoEmail .= '<span class="label '. str_replace(['invalid','valid'], ['label-warning','label-success'], $verifyEmail[0]) . '">';
                        $infoEmail .= ($verifyEmail[0] == 'valid') ? $verifyEmail[0] : 'Need verification !';
                        $infoEmail .= '</span>';
                    }
                    if($css_state != 'has-error' && preg_match('/invalid/i', $verifyEmail[0])) {
                        $css_state = 'has-warning';
                    }
                }
                ?>
                <div class="form-group {!! $css_state !!}">
                    {!! Form::label('ci_email', $label_txt, ['class'=>'col-sm-4 control-label']) !!}
                    <div class="col-sm-8">
                        {!! Form::email('ci_email', !empty($ci_email) ? $ci_email : old('ci_email'), $attributes) !!}
                        {!! $infoEmail !!}
                    </div>
                </div>
            </div>
            @endif

            @if(!empty($ci_password) && 'already_created' != $ci_password || (isset($form_action) && preg_match('/create/i', $form_action)))
            <div class="col-sm-6">
                <?php
                    $label_txt = ucfirst(trans('validation.attributes.password'));
                    $placeholder = trans('navigation.form_enter_placeholder');
                    $attributes = [
                        'required'=>'required',
                        'data-placeholder' => $placeholder,
                        'placeholder' => $placeholder,
                        'class' => 'form-control',
                        'id' => 'ci_password'
                    ];
                    if (!isset($form_action) && preg_match('/edit/i', $form_action)) {
                        $attributes['readonly'] = 'readonly';
                    }
                    $css_state = '';
                    if (!empty($ci_password)) {
                        $css_state = 'has-success';
                    }
                    if ($errors->has('ci_password')) {
                        $css_state = 'has-error';
                    }
                ?>
                <div class="form-group {!! $css_state !!}">
                    {!! Form::label('ci_password', $label_txt, ['class'=>'col-sm-4 control-label']) !!}
                    <div class="col-sm-8">
                        {!! Form::text('ci_password', !empty($ci_password) ? $ci_password : old('ci_password'), $attributes) !!}
                    </div>
                </div>
            </div>
            @endif
            {{--<div class="col-sm-12 text-center">
                {!! link_to_action('Auth\PasswordController@getEmail', 'Change Your Password?', [], ['class'=>'btn btn-danger']) !!}
            </div>--}}
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
