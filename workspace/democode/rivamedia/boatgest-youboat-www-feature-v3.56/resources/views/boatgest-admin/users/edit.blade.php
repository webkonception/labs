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

    {!! Form::open(['route' => ['users.update', $user->id], 'class' => 'form-horizontal', 'method' => 'PATCH']) !!}

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('username', 'Username*', ['class'=>'col-sm-4 control-label']) !!}
                    <div class="col-sm-8">
                        <div class="input-group">
                            {!! Form::text('username', old('username', $user->username), ['class'=>'form-control', 'placeholder'=> 'Username']) !!}
                            <span class="input-group-addon"><span class="fa fa-user"></span></span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('email', 'Email', ['class'=>'col-sm-4 control-label']) !!}
                    <div class="col-sm-8">
                        <div class="input-group">
                            {!! Form::email('email', old('email', $user->email), ['class'=>'form-control', 'placeholder'=> 'Email']) !!}
                            <span class="input-group-addon"><span class="fa fa-envelope-o"></span></span>
                        </div>
                    </div>
                </div>
                {!! Form::hidden('password', old('password', $user->password), ['class'=>'form-control', 'placeholder'=> 'password']) !!}
                {!! htmlspecialchars_decode(link_to(config('quickadmin.route') . '/'. LaravelLocalization::transRoute('routes.change_password') . '/' . $user->email, '<i class="fa fa-lock fa-fw"></i>' . trans('navigation.auth.passwords.change') , ['class' => 'btn btn-danger pull-right'])) !!}
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('role_id', 'Role', ['class'=>'col-sm-4 control-label']) !!}
                    <div class="col-sm-8">
                        <div class="input-group">
                            {!! Form::select('role_id', $roles, old('role_id', $user->role_id), ['class'=>'form-control', 'placeholder'=>'Pick a choice']) !!}
                            <span class="input-group-addon"><span class="fa fa-user-secret"></span></span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('type', 'Type', ['class'=>'col-sm-4 control-label']) !!}
                    <div class="col-sm-8">
                        {!! Form::select('type', $types, old('type', $user->type), ['class'=>'form-control', 'placeholder'=>'Pick a choice', 'readonly'=>'readonly']) !!}
                    </div>
                </div>

                <div class="form-group">
                    <?php
                    $status = !empty($user->status) ? $user->status : old('status', $user->status);
                    $default = ($status == 'active') ? 'inactive' : 'active';
                    ?>
                    {!! Form::label('switch_status', 'Status', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8 material-switch">
                        {!! Form::checkbox('switch_status', $status, ($status == 'active') ? 'checked' : '', ['class'=>'switch', 'data-target'=>'status', 'data-default'=>$default]) !!}
                        <label for="switch_status" class="label-success"></label>
                        <span></span>
                        {!! Form::hidden('status', $status, ['class'=>'form-control', 'id'=>'status']) !!}
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="form-group">
            @if($isAdmin)
            @if($caracts_need_to_create && 'admin' != $user_type)
            <div class="col-sm-4 text-left bloc_edit_btns">
                {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.' . $user_type . 'scaracts.create', '&nbsp;' . ucfirst($user_type) .'\'s Caracts create<i class="fa fa fa-plus fa-fw"></i>', ['user_id' => $user->id], ['class' => $user_type . 'scaracts_edit btn btn-md btn-block btn-warning'])) !!}
            </div>
            @elseif('admin' != $user_type)
            <div class="col-sm-4 text-left bloc_edit_btns">
                {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.' . $user_type . 'scaracts.edit', '&nbsp;' . ucfirst($user_type) .'\'s Caracts Edit<i class="fa fa-pencil fa-fw"></i>', [$caracts_id], ['class' => $user_type . 'scaracts_edit btn btn-md btn-block btn-primary'])) !!}
            </div>
            @endif
            @endif
            <div class="col-sm-4 text-center">
                {!! Form::button('<i class="fa fa-refresh fa-fw"></i>' . ucfirst(trans('navigation.update')), ['type' => 'submit', 'class' => 'btn btn-lg btn-success btn-exception']) !!}
            </div>
            <div class="col-sm-4 text-right">
                {!! htmlspecialchars_decode(link_to_route('users.index', '<i class="fa fa-ban fa-fw"></i>' . ucfirst(trans('navigation.cancel')), [], ['class' => 'btn btn-default pull-right'])) !!}
            </div>
        </div>

    {!! Form::close() !!}

@endsection


