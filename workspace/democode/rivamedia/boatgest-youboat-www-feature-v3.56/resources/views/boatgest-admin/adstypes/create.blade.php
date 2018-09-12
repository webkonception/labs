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

    {!! Form::open(array('route' => config('quickadmin.route') . '.adstypes.store', 'id' => 'form-with-validation', 'role'=>'form', 'class' => 'form-horizontal')) !!}
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('name', 'Name*', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        <div class="input-group">
                            {!! Form::text('name', old('name'), ['class'=>'form-control']) !!}
                            <span class="input-group-addon"><span class="fa fa-tags"></span></span>
                        </div>
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
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('rewrite_url', 'Rewrite url', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        <div class="input-group">
                            {!! Form::text('rewrite_url', old('rewrite_url'), ['class'=>'form-control']) !!}
                            <span class="input-group-addon"><span class="fa fa-link"></span></span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('position', 'Position', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        <div class="input-group">
                            {!! Form::text('position', old('position'), ['class'=>'form-control']) !!}
                            <span class="input-group-addon"><span class="fa fa-sort-numeric-asc"></span></span>
                        </div>
                    </div>
                </div>

                {{--<div class="form-group">
                    {!! Form::label('switch_status', 'Status', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8 material-switch">
                        {!! Form::checkbox('switch_status', old('status', $status['active']), (old('status') == 'active') ? 'checked' : '', ['class'=>'switch', 'data-target'=>'status', 'data-default'=>'inactive']) !!}
                        <label for="switch_status" class="label-success"></label>
                        <span></span>
                        {!! Form::hidden('status', old('status', $status['inactive']), ['id'=>'status']) !!}
                    </div>
                </div>--}}
                <div class="form-group">
                    <?php
                        $status = old('status', $status['active']);
                        $default = ($status == 'active') ? 'inactive' : 'active';
                        $checked = ($status == 'active') ? 'checked' : '';
                    ?>
                    {!! Form::label('switch_status', 'Status', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8 material-switch">
                        {!! Form::checkbox('switch_status', $status, $checked, ['class'=>'switch', 'data-target'=>'status', 'data-default'=>$default]) !!}
                        <label for="switch_status" class="label-success"></label>
                        <span></span>
                        {!! Form::hidden('status', $status, ['class'=>'form-control', 'id'=>'status']) !!}
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="form-group">
            <div class="col-sm-12 text-center">
                {!! Form::button('<i class="fa fa-cog fa-fw"></i>' . ucfirst(trans('navigation.create')), ['type' => 'submit', 'class' => 'btn btn-lg btn-primary btn-exception']) !!}
                {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.adstypes.index', '<i class="fa fa-mail-reply fa-fw"></i>' . ucfirst(trans('navigation.back')), [], array('class' => 'btn btn-default pull-right'))) !!}
            </div>
        </div>

    {!! Form::close() !!}

@endsection