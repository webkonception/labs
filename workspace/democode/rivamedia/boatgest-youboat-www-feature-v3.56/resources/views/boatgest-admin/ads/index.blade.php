@extends(config('quickadmin.route') . '.layouts.master')

@section('content')

    <p>{!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.ads.create', '<i class="fa fa-plus fa-fw"></i>Add new', [], array('class' => 'btn btn-success'))) !!}</p>

@if ($ads->count())
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
                        <th>Dealer id</th>
                        <th>Country contracts ids</th>
                        <th>Ad's type</th>
                        <th>Categories</th>
                        <th>SubCategories</th>
                        <th>Start date</th>
                        <th>End date</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                @foreach ($ads as $row)
                    @if (isset($dealers[$row->dealerscaracts_id]))
                    <tr>
                        {{--<td>{!! Form::checkbox('del-' . $row->id, 1, false, ['class'=>'single', 'data-id'=>$row->id]) !!}</td>--}}
                        {{--<td>{{ isset($row->dealerscaracts->dealerscaracts_id) ? $dealers[$row->dealerscaracts->dealerscaracts_id] : $dealers[$row->dealerscaracts_id] }}</td>--}}
                        <td>
                            {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.ads.edit', '<i class="fa fa-pencil fa-fw"></i>Edit', [$row->id], ['class' => 'btn btn-block btn-xs btn-primary'])) !!}
                            @if($isAdmin)
                            <br>
                            {!! Form::open(array('class' => '', 'method' => 'DELETE', 'onsubmit' => 'return confirm(\'Confirm deletion\');',  'route' => array(config('quickadmin.route') . '.ads.destroy', $row->id))) !!}
                            {!! Form::button('<i class="fa fa-trash-o fa-fw"></i>Delete', array('type' => 'submit', 'class' => 'btn btn-block btn-xs btn-danger btn-exception')) !!}
                            {!! Form::close() !!}
                            @endif
                        </td>
                        <td>{{ $dealers[$row->dealerscaracts_id] }}</td>
                        <td>{{ $row->country_contracts_ids }}</td>
                        <td>{{ $row->adstypes_id }}</td>
                        <td>{{ $row->categories_ids }}</td>
                        <td>{{ $row->subcategories_ids }}</td>
                        <td>{{ $row->start_date }}</td>
                        <td>{{ $row->end_date }}</td>
                        <td>{{ $row->status }}</td>
                    </tr>
                    @endif
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
            {!! Form::open(['route' => config('quickadmin.route') . '.ads.massDelete', 'method' => 'post', 'id' => 'massDelete']) !!}
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