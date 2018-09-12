@extends(config('quickadmin.route') . '.layouts.master')

@section('content')

    @if ($errors->any())
        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-danger">
                    <ul>
                        {!! implode('', $errors->all('<li class="error">:message</li>')) !!}
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {!! Form::open(['route'=>'users.store', 'class'=>'form-horizontal', 'autocomplete'=>'off', 'id'=>'user_create']) !!}
        <div class="row">
            <div class="col-sm-6">
                <?php
                $username = old('username');
                $label_txt = ucfirst(trans('validation.attributes.username')) . ' *';
                $placeholder = trans('navigation.form_enter_placeholder');
                $attributes = [
                        'required'=>'required',
                        'data-placeholder' => $placeholder,
                        'placeholder' => $placeholder,
                        'class' => 'form-control',
                        'id' => 'username'
                ];
                $css_state = '';
                if (!empty($username)) {
                    $css_state = 'has-success';
                }
                if ($errors->has('username')) {
                    $css_state = 'has-error';
                }
                ?>
                <div class="form-group {!! $css_state !!}">
                    {!! Form::label('username', $label_txt, ['class'=>'col-sm-4 col-md-5 control-label']) !!}
                    <div class="col-sm-8 col-md-7">
                        <div class="input-group">
                            {!! Form::text('username', $username, $attributes) !!}
                            <span class="input-group-addon"><span class="fa fa-user"></span></span>
                        </div>
                    </div>
                </div>

                <?php
                $email = old('email');
                $label_txt = ucfirst(trans('validation.attributes.email')) . ' *';
                $placeholder = trans('navigation.form_enter_placeholder');
                $attributes = [
                        'required'=>'required',
                        'data-placeholder' => $placeholder,
                        'placeholder' => $placeholder,
                        'class' => 'form-control',
                        'id' => 'email'
                ];
                $css_state = '';
                if (!empty($email)) {
                    $css_state = 'has-success';
                }
                if ($errors->has('email')) {
                    $css_state = 'has-error';
                }
                ?>
                <div class="form-group {!! $css_state !!}">
                    {!! Form::label('email', $label_txt, ['class'=>'col-sm-4 col-md-5 control-label']) !!}
                    <div class="col-sm-8 col-md-7">
                        <div class="input-group">
                            {!! Form::email('email', $email, $attributes) !!}
                            <span class="input-group-addon"><span class="fa fa-envelope-o"></span></span>
                        </div>
                    </div>
                </div>

                <?php
                $label_txt = ucfirst(trans('validation.attributes.password')) . ' *';
                $placeholder = trans('navigation.form_enter_placeholder');
                $attributes = [
                        'data-placeholder' => $placeholder,
                        'required'=>'required',
                        'placeholder' => $placeholder,
                        'class' => 'form-control',
                        'id' => 'password'
                ];
                $css_state = '';
                if ($errors->has('password')) {
                    $css_state = 'has-error';
                }
                ?>
                <div class="form-group {!! $css_state !!}">
                    {!! Form::label('password', $label_txt, ['class'=>'col-sm-4 col-md-5 control-label']) !!}
                    <div class="col-sm-8 col-md-7">
                        <div class="input-group">
                            {!! Form::password('password', ['class'=>'form-control', 'placeholder'=> 'Password']) !!}
                            <span class="input-group-addon"><span class="fa fa-unlock-alt"></span></span>
                        </div>
                        @if ($errors->has('password'))
                            <span class="help-block"><strong>{{ $errors->first('password') }}</strong></span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-sm-6">

                <div class="form-group">
                    {!! Form::label('role_id', 'Role *', ['class'=>'col-sm-4 col-md-5 control-label']) !!}
                    <div class="col-sm-8 col-md-7">
                        <div class="input-group">
                            @if (isset($input['role']))
                            {!! Form::text('role_id', $roles[$input['role']], ['placeholder'=> trans('navigation.form_enter_placeholder'), 'class'=>'form-control']) !!}
                            @else
                            {!! Form::select('role_id', $roles, isset($input['role']) ? $input['role'] : null, ['placeholder'=> trans('navigation.form_enter_placeholder'), 'class'=>'form-control']) !!}
                            @endif
                            <span class="input-group-addon"><span class="fa fa-user-secret"></span></span>
                        </div>
                    </div>
                </div>

                <div class="form-group hidden">
                    {!! Form::label('type', 'Type *', ['class'=>'col-sm-4 col-md-5 control-label']) !!}
                    <div class="col-sm-8 col-md-7">
                        @if (isset($input['role']))
                        {!! Form::text('type', $roles[$input['role']], ['class'=>'form-control', 'readonly'=>'readonly']) !!}
                        @else
                        {!! Form::select('type', $types, null, ['placeholder'=> trans('navigation.form_enter_placeholder'), 'class'=>'form-control noselect2', 'readonly'=>'readonly']) !!}
                        @endif
                    </div>
                </div>

                {{--<div class="form-group">
                    {!! Form::label('status', 'Status', ['class'=>'col-sm-4 col-md-5 control-label']) !!}
                    <div class="col-sm-8 ">
                        {!! Form::select('status', $status, null, ['placeholder'=>'Pick a choice', 'class'=>'form-control']) !!}
                    </div>
                </div>--}}

                {{--<div class="form-group">
                    {!! Form::label('switch_status', 'Status', ['class'=>'col-xs-3 col-sm-4 col-md-5 control-label']) !!}
                    <div class="col-xs-9 col-sm-8 col-md-7 material-switch">
                        {!! Form::checkbox('switch_status', old('status', $status['active']), (old('status') == 'active') ? 'checked' : '', ['class'=>'switch', 'data-target'=>'status', 'data-default'=>'inactive']) !!}
                        <label for="switch_status" class="label-success"></label>
                        <span></span>
                        {!! Form::hidden('status', old('status', $status['inactive']), ['id'=>'status']) !!}
                    </div>
                </div>--}}

                <?php
                $user_status = old('status');
                $label_txt = ucfirst('status');
                $placeholder = trans('navigation.form_enter_placeholder');
                $attributes = [
                        'data-placeholder' => $placeholder,
                        'placeholder' => $placeholder,
                        'class' => 'form-control',
                        'id' => 'status'
                ];
                $css_state = '';
                if (!empty($user_status)) {
                    $css_state = 'has-success';
                }
                if ($errors->has('status')) {
                    $css_state = 'has-error';
                }
                ?>
                <div class="form-group {!! $css_state !!}">
                    {!! Form::label('status', $label_txt, ['class'=>'col-xs-3 col-sm-4 col-md-5 control-label']) !!}
                    <div class="col-xs-9 col-sm-8 col-md-7">
                        {!! Form::select('status', $status, $user_status, $attributes) !!}
                    </div>
                </div>

            </div>
        </div>
        <hr>
        <div class="form-group">
            <div class="col-sm-12 text-center">
                {!! Form::button('<i class="fa fa-cog fa-fw"></i>' . ucfirst(trans('navigation.create')), ['type'=>'submit', 'class'=>'btn btn-lg btn-primary']) !!}
                {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.users.index', '<i class="fa fa-mail-reply fa-fw"></i>' . ucfirst(trans('navigation.back')), [], array('class' => 'btn btn-default pull-right'))) !!}
            </div>
        </div>

    {!! Form::close() !!}

@endsection


