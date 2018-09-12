<?php
    /*if (!empty($datasRequest) && count($datasRequest)>0) {
        $email                  = isset($email) ? $email : (!empty($datasRequest['email' . '_' . $target]) ? $datasRequest['email' . '_' . $target] : null);
        $password               = isset($password) ? $password : (!empty($datasRequest['password' . '_' . $target]) ? $datasRequest['password' . '_' . $target] : null);
    }*/
    $email                  = !empty($email) ? $email : old('email');

    $target = !empty($target) ? $target : 'bod';
    $text_title = !empty($text_title) ? $text_title : 'text_title';
    $text_intro = !empty($text_intro) ? $text_intro : 'text_intro';
    $btn_link_text = !empty($btn_link_text) ? $btn_link_text : 'btn_link_text';
?>
<h3 class="text_title lead {!! $target == 'bod' ? 'accent-color-danger' : 'text-info' !!}">{!! $text_title !!}</h3>

<div class="well {!! $target == 'bod' ? 'well-white' : '' !!} clearfix">
    {!! Form::open(array('url'=>trans_route($currentLocale, 'routes.login'), 'class'=>'form', 'id'=>'form_dashboard' . '_' . $target, 'autocomplete'=>'off', 'role'=>'form')) !!}
    <p class="text_intro {!! $target == 'bod' ? '' : 'accent-color' !!}">{!! $text_intro !!}</p>
        {!! csrf_field() !!}
        {!! Form::hidden('country_code', $country_code) !!}
        {!! Form::hidden('target', $target) !!}
        <?php
            $label_txt = ucfirst(trans('validation.attributes.email'));
            $placeholder = trans('navigation.form_enter_placeholder');
            $attributes = [
                    'required'=>'required',
                    'data-placeholder' => $placeholder,
                    'placeholder' => $placeholder,
                    'class' => 'form-control',
                    'id' => 'email' . '_' . $target
            ];
            $css_state = '';
            if (!empty($email)) {
                $css_state = 'has-success';
            }
            $helpBlock = '';
            if ($errors->has('email')) {
                $css_state = 'has-error';
                $helpBlock = '<span class="help-block"><strong>' . $errors->first('email') . '</strong></span>';
            }
        ?>
        <div class="form-group {!! $css_state !!}">
            {!! Form::label('email' . '_' . $target, $label_txt, ['class' => 'control-label']) !!}
            <div class="">
                {!! Form::email('email', $email, $attributes) !!}
                {!! $helpBlock !!}
            </div>
        </div>

        <?php
            $label_txt = ucfirst(trans('validation.attributes.password'));
            $placeholder = trans('navigation.form_enter_placeholder');
            $attributes = [
                    'required'=>'required',
                    'data-placeholder' => $placeholder,
                    'placeholder' => $placeholder,
                    'class' => 'form-control password-input',
                    'id' => 'password' . '_' . $target
            ];
            $css_state = '';
            if (!empty($password)) {
                $css_state = 'has-success';
            }
            $helpBlock = '';
            if ($errors->has('password')) {
                $css_state = 'has-error';
                $helpBlock = '<span class="help-block"><strong>' . $errors->first('password') . '</strong></span>';
            }
        ?>
        <div class="form-group {!! $css_state !!}">
            {!! Form::label('password' . '_' . $target, $label_txt, ['class' => 'control-label']) !!}
            <div class="">
                {!! Form::password('password', $attributes) !!}
                {!! $helpBlock !!}
            </div>
        </div>

        <div class="form-group">
            <div class="checkbox">
                <label>
                    {!! Form::checkbox('remember', null) !!} {!! ucfirst(trans('validation.attributes.remember_me')) !!}
                </label>
            </div>
        </div>

        <div class="form-group text-center">
            {!! Form::button($btn_link_text, ['type' => 'submit', 'class' => 'btn btn-lg btn-block ' . ($target == 'bod' ? 'btn-danger' : 'btn-primary') . ' btn-exception']) !!}
        </div>

        <div class="form-group text-right">
            {!! link_trans_url(trans_route($currentLocale, 'routes.password_email'), 'navigation.auth.passwords.forgot', ['email' => $email], ['class' => 'btn btn-sm btn-default']) !!}
        </div>
    {!! Form::close() !!}
</div>