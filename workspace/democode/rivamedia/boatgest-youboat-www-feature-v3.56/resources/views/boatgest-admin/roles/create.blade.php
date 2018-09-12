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

    {!! Form::open(array('route' => config('quickadmin.route') . '.roles.store', 'id' => 'form-with-validation', 'role'=>'form', 'class' => 'form-horizontal')) !!}
        <div class="form-group">
            {!! Form::label('id', 'Id*', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
            <div class="col-xs-9 col-sm-8">
                {!! Form::text('id', old('id'), ['class'=>'form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('title', 'Title', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
            <div class="col-xs-9 col-sm-8">
                {!! Form::text('title', old('title'), ['class'=>'form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('description', 'Description', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
            <div class="col-xs-9 col-sm-8">
                <div class="input-group">
                    {!! Form::text('description', old('description'), ['class'=>'form-control']) !!}
                    <span class="input-group-addon"><span class="fa fa-pencil-square-o"></span></span>
                </div>
            </div>
        </div>
        <hr>
        <div class="form-group">
            <div class="col-sm-12 text-center">
                {!! Form::button('<i class="fa fa-cog fa-fw"></i>' . ucfirst(trans('navigation.create')), ['type' => 'submit', 'class' => 'btn btn-lg btn-primary btn-exception']) !!}
                {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.roles.index', '<i class="fa fa-mail-reply fa-fw"></i>' . ucfirst(trans('navigation.back')), [], array('class' => 'btn btn-default pull-right'))) !!}
            </div>
        </div>

    {!! Form::close() !!}

@endsection