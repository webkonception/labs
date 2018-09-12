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

    {!! Form::model($boatstypes, array('class' => 'form-horizontal', 'id' => 'form-with-validation', 'role'=>'form', 'method' => 'PATCH', 'route' => array(config('quickadmin.route') . '.boatstypes.update', $boatstypes->id))) !!}

        <div class="form-group">
            {!! Form::label('name', 'Name*', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
            <div class="col-xs-9 col-sm-8">
                {!! Form::text('name', old('name', $boatstypes->name), ['class'=>'form-control']) !!}
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('description', 'Description', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
            <div class="col-xs-9 col-sm-8">
                <div class="input-group">
                    {!! Form::text('description', old('description', $boatstypes->description), ['class'=>'form-control']) !!}
                    <span class="input-group-addon"><span class="fa fa-pencil-square-o"></span></span>
                </div>
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('rewrite_url', 'Rewrite url', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
            <div class="col-xs-9 col-sm-8">
                <div class="input-group">
                    {!! Form::text('rewrite_url', old('rewrite_url', $adstypes->rewrite_url), ['class'=>'form-control']) !!}
                    <span class="input-group-addon"><span class="fa fa-link"></span></span>
                </div>
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('position', 'Position', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
            <div class="col-xs-9 col-sm-8">
                <div class="input-group">
                    {!! Form::text('position', old('position', $boatstypes->position), ['class'=>'form-control']) !!}
                    <span class="input-group-addon"><span class="fa fa-sort-numeric-asc"></span></span>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12 text-center">
              {!! Form::submit('Update', array('class' => 'btn btn-lg btn-primary')) !!}
              {!! link_to_route(config('quickadmin.route') . '.boatstypes.index', 'Cancel', $boatstypes->id, array('class' => 'btn btn-default pull-right')) !!}
            </div>
        </div>

    {!! Form::close() !!}

@endsection