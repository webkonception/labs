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

    {!! Form::model($categories, array('class' => 'form-horizontal', 'id' => 'form-with-validation', 'role'=>'form', 'method' => 'PATCH', 'route' => array(config('quickadmin.route') . '.categories.update', $categories->id))) !!}
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('adstypes_id', 'Type parent*', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        <div class="input-group">
                            {!! Form::select('adstypes_id', $adstypes, old('categories_id', $categories->adstypes_id), ['class'=>'form-control']) !!}
                            <span class="input-group-addon"><span class="fa fa-tag"></span></span>
                        </div>
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
                            {!! Form::text('name', old('name', $categories->name), ['class'=>'form-control']) !!}
                            <span class="input-group-addon"><span class="fa fa-list-ol"></span></span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('description', 'Description', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        <div class="input-group">
                            {!! Form::textarea('description', old('description', $categories->description), ['class'=>'form-control']) !!}
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
                            {!! Form::text('rewrite_url', old('rewrite_url', $categories->rewrite_url), ['class'=>'form-control']) !!}
                            <span class="input-group-addon"><span class="fa fa-link"></span></span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('position', 'Position', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        <div class="input-group">
                            {!! Form::text('position', old('position', $categories->position), ['class'=>'form-control']) !!}
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
                {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.categories.index', '<i class="fa fa-ban fa-fw"></i>' . ucfirst(trans('navigation.cancel')), [], ['class' => 'btn btn-default pull-right'])) !!}
            </div>
        </div>

    {!! Form::close() !!}

@endsection