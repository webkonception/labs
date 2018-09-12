@extends(config('quickadmin.route') . '.layouts.master')

@section('content')

    <div class="row">
        <div class="col-md-10 col-md-offset-2">
            <h1><i class="fa fa-edit fa-fw"></i>Edit user</h1>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        {!! implode('', $errors->all('<li class="error">:message</li>')) !!}
                    </ul>
                </div>
            @endif
        </div>
    </div>

    {!! Form::open(['route' => ['users.update', $user->id], 'class' => 'form-horizontal', 'method' => 'PATCH']) !!}

    <div class="form-group">
        {!! Form::label('username', 'Username', ['class'=>'col-md-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::text('username', old('username', $user->username), ['class'=>'form-control', 'placeholder'=> 'Username']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('email', 'Email', ['class'=>'col-md-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::email('email', old('email', $user->email), ['class'=>'form-control', 'placeholder'=> 'Email']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('password', 'Password', ['class'=>'col-md-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::password('password', ['class'=>'form-control', 'placeholder'=> 'Password']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('role_id', 'Role', ['class'=>'col-md-2 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::select('role_id', $roles, old('role_id', $user->role), ['class'=>'form-control']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('type', 'Type', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::select('type', $types, $user->type, ['class'=>'form-control']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('status', 'Status', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
        <div class="col-sm-10">
            {!! Form::select('status', $status, $user->status, ['class'=>'form-control']) !!}
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-12 text-center">
            {!! Form::button('<i class="fa fa-refresh fa-fw"></i>Update', ['type' => 'submit', 'class' => 'btn btn-lg btn-primary']) !!}
            {!! htmlspecialchars_decode(link_to_route('users.index', '<i class="fa fa-ban fa-fw"></i>Cancel', $user->id, ['class' => 'btn btn-default'])) !!}
        </div>
    </div>

    {!! Form::close() !!}

@endsection


