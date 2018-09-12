@extends(config('quickadmin.route') . '.layouts.master')

@section('content')

    <p>{!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.countries.create', '<i class="fa fa-plus fa-fw"></i>Add new', [], array('class' => 'btn btn-success'))) !!}</p>

@if ($countries->count())
    <div class="panel panel-success">
        <div class="panel-heading">
            <h3 class="panel-title">List</h3>
        </div>
        <div class="panel-body table-responsive">
            <table class="table table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th class="nosort">{!! Form::checkbox('delete_all', 1, false, ['class' => 'mass']) !!}</th>
                        <th class="nosort">Actions</th>
                        <th>Flag</th>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Currency</th>
                        <th>Currency name</th>
                        <th>Vat</th>
                        <th>Languages</th>
                    </tr>
                </thead>

                <tbody>
                @foreach ($countries as $row)
                    <tr>
                        <td>
                            {!! Form::checkbox('del-' . $row->id, 1, false, ['class'=>'single', 'data-id'=>$row->id]) !!}
                        </td>
                        <td>
                            {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.countries.edit', '<i class="fa fa-pencil fa-fw"></i>Edit', [$row->id], ['class' => 'btn btn-block btn-xs btn-primary'])) !!}
                            @if($isAdmin)
                            <br>
                            {!! Form::open(array('class' => '', 'method' => 'DELETE', 'onsubmit' => 'return confirm(\'Confirm deletion\');',  'route' => array(config('quickadmin.route') . '.countries.destroy', $row->id))) !!}
                            {!!  Form::button('<i class="fa fa-trash-o fa-fw"></i>Delete', array('type' => 'submit', 'class' => 'btn btn-block btn-xs btn-danger btn-exception')) !!}
                            {!! Form::close() !!}
                            @endif
                        </td>
                        <td><img class="img-flag" src="{{ asset('assets/img/flags/' . $row->code . '.png') }}" alt="{{ $row->code }}"></td>
                        <td>{{ $row->code }}</td>
                        <td>{{ $row->name }}</td>
                        <td>{{ $row->currency }}</td>
                        <td>{{ $row->currency_name }}</td>
                        <td>{{ $row->vat }}</td>
                        <td>{{ $row->locales }}</td>
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
            {!! Form::open(['route' => config('quickadmin.route') . '.countries.massDelete', 'method' => 'post', 'id' => 'massDelete']) !!}
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

@section('javascript')
@stop