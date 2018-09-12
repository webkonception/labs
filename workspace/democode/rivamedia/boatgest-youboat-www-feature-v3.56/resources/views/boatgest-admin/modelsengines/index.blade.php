@extends(config('quickadmin.route') . '.layouts.master')

@section('content')

    <p>{!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.modelsengines.create', '<i class="fa fa-plus fa-fw"></i>' . trans('navigation.add') . ' new ' . trans('modelsengines.model_engine'), [], array('class' => 'btn btn-success'))) !!}</p>

    {!! Form::open(array('route' => config('quickadmin.route') . '.modelsengines.index', 'method' => 'GET', 'id' => 'form-with-validation', 'class' => 'form-horizontal')) !!}
    <div class="row">
        <div class="col-sm-10">
            <div class="form-group">
                {!! Form::label('manufacturersengines_id', trans('manufacturersengines.manufacturers_engines') . '*', ['class'=>'col-xs-12 col-sm-4 control-label']) !!}
                <div class="{{ (count($manufacturersengines) > 1 && $isAdmin) ? 'col-xs-8' : '' }} col-sm-{{ (count($manufacturersengines) > 1 && $isAdmin) ? 6 : 8 }}">
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
                    <div class="col-xs-4 col-sm-4">
                        {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.manufacturersengines.create', '<i class="fa fa-plus fa-fw"></i>' . trans('navigation.add') . ' ' . trans('manufacturersengines.manufacturer_engine'), [], ['class'=>'blank btn btn-sm btn-success'])) !!}
                    </div>
                @endif
            </div>
        </div>
        <div class="col-sm-2">
            {!! Form::button('<i class="fa fa-cog fa-fw"></i>Submit', ['type'=>'submit', 'class'=>'btn btn-sm btn-primary']) !!}
        </div>
    </div>
    {!! Form::close() !!}

@if (isset($modelsengines) && $modelsengines->count())
{{--@if (sizeof($models) > 0)--}}
    <div class="panel panel-success">
        <div class="panel-heading">
            <h3 class="panel-title">List</h3>
        </div>
        <div class="panel-body table-responsive">
            <table class="table table-striped table-hover datatable">
                <thead>
                    <tr>
                        {{--<th class="nosort">{!! Form::checkbox('delete_all', 1, false, ['class' => 'mass']) !!}</th>--}}
                        <th class="nosort">Actions</th>
                        <th>Manufacturer</th>
                        <th>Name</th>
                        <th>Rewrite Url</th>
                        <th>Equivalent</th>
                        <th>Referrer</th>
                        <th>position</th>
                    </tr>
                </thead>

                <tbody>
                @foreach ($modelsengines as $row)
                    <tr>
                        {{--<td>{!! Form::checkbox('del-' . $row->id, 1, false, ['class'=>'single', 'data-id'=>$row->id]) !!}</td>--}}
                        <td>
                            {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.modelsengines.edit', '<i class="fa fa-pencil fa-fw"></i>Edit', [$row->id], ['class' => 'btn btn-block btn-xs btn-primary'])) !!}
                            @if($isAdmin)
                            <br>
                            {!! Form::open(array('class' => '', 'method' => 'DELETE', 'onsubmit' => 'return confirm(\'Confirm deletion\');',  'route' => array(config('quickadmin.route') . '.modelsengines.destroy', $row->id))) !!}
                            {!! Form::button('<i class="fa fa-trash-o fa-fw"></i>Delete', array('type' => 'submit', 'class' => 'btn btn-block btn-xs btn-danger btn-exception')) !!}
                            {!! Form::close() !!}
                            @endif
                        </td>
                        <td>{{ $manufacturersengines[$row->manufacturersengines_id] }}</td>
                        <td>{{ $row->name }}</td>
                        <td>{{ $row->rewrite_url }}</td>
                        <td>{{ $row->equivalent }}</td>
                        <td>{{ $row->referrer }}</td>
                        <td>{{ $row->position }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{--<div class="row">
                <div class="col-xs-12">
                    <button class="btn btn-danger" id="delete">
                        <i class="fa fa-trash-o fa-fw"></i>Delete checked
                    </button>
                </div>
            </div>
            {!! Form::open(['route' => config('quickadmin.route') . '.modelsengines.massDelete', 'method' => 'post', 'id' => 'massDelete']) !!}
                <input type="hidden" id="send" name="toDelete">
            {!! Form::close() !!}--}}
        </div>
	</div>
@else
    <div class="panel panel-danger">
        <div class="panel-heading">
            <h3 class="panel-title">List</h3>
        </div>
        <div class="panel-body text-danger">
            No entries found.
        </div>
    </div>
@endif

@endsection