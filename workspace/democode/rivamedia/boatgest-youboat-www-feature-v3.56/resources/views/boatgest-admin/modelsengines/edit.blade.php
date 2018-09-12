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

    {!! Form::model($modelsengines, array('class' => 'form-horizontal', 'id' => 'form-with-validation', 'role'=>'form', 'method' => 'PATCH', 'route' => array(config('quickadmin.route') . '.modelsengines.update', $modelsengines->id))) !!}
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('manufacturers_id ', 'Manufacturer*', ['class'=>'col-sm-4 control-label']) !!}
                    <div class="col-sm-8">
                        {!! Form::text('manufacturers_id', old('manufacturers_id', $modelsengines->manufacturers_id), ['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('name', 'Name*', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        <div class="input-group">
                            {!! Form::text('name', old('name', $modelsengines->name), ['class'=>'form-control']) !!}
                            <span class="input-group-addon"><span class="fa fa-industry"></span></span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('rewrite_url', 'Rewrite url*', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        <div class="input-group">
                            {!! Form::text('rewrite_url', old('rewrite_url', $modelsengines->rewrite_url), ['class'=>'form-control']) !!}
                            <span class="input-group-addon"><span class="fa fa-link"></span></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('equivalent', 'Equivalent', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        <div class="input-group">
                            {!! Form::text('equivalent', old('equivalent', $modelsengines->equivalent), ['class'=>'form-control']) !!}
                            <span class="input-group-addon"><span class="fa fa-arrows-h"></span></span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('referrer', 'Referrer', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        <div class="input-group">
                            {!! Form::text('referrer', old('referrer', $modelsengines->referrer), ['class'=>'form-control']) !!}
                            <span class="input-group-addon"><span class="fa fa-external-link"></span></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        {!! Form::label('description', 'Description', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                        <div class="col-xs-9 col-sm-8">
                            <div class="input-group">
                                {!! Form::text('description', old('description', $modelsengines->description), ['class'=>'form-control']) !!}
                                <span class="input-group-addon"><span class="fa fa-pencil-square-o"></span></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                    {!! Form::label('position', 'Position', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        <div class="input-group">
                            {!! Form::text('position', old('position', $modelsengines->position), ['class'=>'form-control']) !!}
                            <span class="input-group-addon"><span class="fa fa-sort-numeric-asc"></span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="form-group">
            <div class="col-sm-12 text-center">
                {!! Form::button('<i class="fa fa-refresh fa-fw"></i>' . ucfirst(trans('navigation.update')), ['type' => 'submit', 'class' => 'btn btn-lg btn-success btn-exception']) !!}
                {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.modelsengines.index', '<i class="fa fa-ban fa-fw"></i>' . ucfirst(trans('navigation.cancel')), [], ['class' => 'btn btn-default pull-right'])) !!}
            </div>
        </div>

    {!! Form::close() !!}

@endsection