@extends(config('quickadmin.route') . '.layouts.master')

@section('content')

    <div class="row">
        <div class="col-md-10 col-md-offset-2">
            <h1>Create user</h1>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        {!! implode('', $errors->all('<li class="error">:message</li>')) !!}
                    </ul>
                </div>
            @endif
        </div>
    </div>

    {!! Form::open(['route' => 'users.store', 'class' => 'form-horizontal']) !!}

    <div class="form-group">
        {!! Form::label('username', 'Username', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
        <div class="col-xs-9 col-sm-8">
            {!! Form::text('username', old('username'), ['class'=>'form-control', 'placeholder'=> 'Username']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('email', 'Email', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
        <div class="col-xs-9 col-sm-8">
            {!! Form::email('email', old('email'), ['class'=>'form-control', 'placeholder'=> 'Email']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('password', 'Password', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
        <div class="col-xs-9 col-sm-8">
            {!! Form::password('password', ['class'=>'form-control', 'placeholder'=> 'Password']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('role_id', 'Role', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
        <div class="col-xs-9 col-sm-8">
            {!! Form::select('role_id', $roles, old('role_id'), ['placeholder' => 'Pick a choice', 'class'=>'form-control']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('type', 'Type', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
        <div class="col-xs-9 col-sm-8">
            {!! Form::select('type', $types, old('type'), ['placeholder' => 'Pick a choice', 'class'=>'form-control']) !!}
        </div>
    </div>

    <div class="form-group">
        {!! Form::label('status', 'Status', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
        <div class="col-xs-9 col-sm-8">
            {!! Form::select('status', $status, old('status'), ['placeholder' => 'Pick a choice', 'class'=>'form-control']) !!}
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-12 text-center">
            {!! Form::button('<i class="fa fa-cog fa-fw"></i>Create', ['type'=>'submit', 'class'=>'btn btn-lg btn-primary']) !!}
            {!! htmlspecialchars_decode(link_to(url()->previous(), '<i class="fa fa-mail-reply fa-fw"></i>Back', ['class' => 'btn btn-default pull-right'])) !!}
        </div>
    </div>

    {!! Form::close() !!}

@endsection


