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

    {!! Form::open(array('route' => config('quickadmin.route') . '.modelsengines.store', 'id' => 'form-with-validation', 'role'=>'form', 'class' => 'form-horizontal')) !!}
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('manufacturersengines_id', trans('manufacturersengines.manufacturers_engines') . '*', ['class'=>'col-xs-12 col-sm-4 control-label']) !!}
                    <div class="{{ (count($manufacturersengines) > 1 && $isAdmin) ? 'col-xs-9' : '' }} col-sm-{{ (count($manufacturersengines) > 1 && $isAdmin) ? 6 : 8 }}">
                        <div class="input-group">
                            @if (count($manufacturersengines) < 2 && $isAdmin)
                                {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.manufacturersengines.create', '<i class="fa fa-plus fa-fw"></i>' . trans('navigation.add') . ' new ' . trans('manufacturersengines.manufacturer_engine'), [], ['class'=>'blank btn btn-sm btn-block btn-success'])) !!}
                            @else
                                {!! Form::select('manufacturersengines_id', $manufacturersengines, !empty($manufacturerengineId) ? $manufacturerengineId : old('manufacturersengines_id', null), ['class'=>'form-control']) !!}
                            @endif
                            <span class="input-group-addon"><span class="fa fa-industry"></span></span>
                        </div>
                    </div>
                    @if (count($manufacturersengines) > 1 && $isAdmin)
                        <div class="col-xs-3 col-sm-2">
                            {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.manufacturersengines.create', '<i class="fa fa-plus fa-fw"></i>' . trans('navigation.add') . ' ' . trans('manufacturersengines.manufacturer_engine'), [], ['class'=>'blank btn btn-sm btn-success'])) !!}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('name', trans('modelsengines.model') . ' Name*', ['class'=>'col-xs-4 col-sm-4 control-label']) !!}
                    <div class="col-xs-8 col-sm-8">
                        {!! Form::text('name', old('name', null), ['class'=>'form-control']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('rewrite_url', 'Rewrite url*', ['class'=>'col-xs-4 col-sm-4 control-label']) !!}
                    <div class="col-xs-8 col-sm-8">
                        {!! Form::text('rewrite_url', old('rewrite_url', null), ['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('equivalent', 'Equivalent', ['class'=>'col-xs-4 col-sm-4 control-label']) !!}
                    <div class="col-xs-8 col-sm-8">
                        {!! Form::text('equivalent', old('equivalent', null), ['class'=>'form-control']) !!}
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('referrer', 'Referrer*', ['class'=>'col-xs-4 col-sm-4 control-label']) !!}
                    <div class="col-xs-8 col-sm-8">
                        {!! Form::text('referrer', old('referrer', null), ['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('description', 'Description', ['class'=>'col-xs-4 col-sm-4 control-label']) !!}
                    <div class="col-xs-8 col-sm-8">
                        {!! Form::text('description', old('description', null), ['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('position', 'position', ['class'=>'col-xs-4 col-sm-4 control-label']) !!}
                    <div class="col-xs-8 col-sm-8">
                        {!! Form::text('position', old('position', null), ['class'=>'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="form-group">
            <div class="col-sm-12 text-center">
                {!! Form::button('<i class="fa fa-cog fa-fw"></i>' . ucfirst(trans('navigation.create')), ['type'=>'submit', 'class'=>'btn btn-lg btn-primary']) !!}
                {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.modelsengines.index', '<i class="fa fa-mail-reply fa-fw"></i>' . ucfirst(trans('navigation.back')), [], array('class' => 'btn btn-default pull-right'))) !!}
            </div>
        </div>

    {!! Form::close() !!}

@endsection