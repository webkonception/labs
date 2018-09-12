
@extends(config('quickadmin.route') . '.layouts.master')

@section('content')

    <p>{!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.scrappingadsdetails.create', '<i class="fa fa-plus fa-fw"></i>Add new', [], array('class' => 'btn btn-success'))) !!}</p>

@if ($scrapping_ads_details->count())
    <div class="panel panel-success">
        <div class="panel-heading">
            <h3 class="panel-title">Gateway Ads Update</h3>
        </div>
        <div class="panel-body table-responsive">
            ...
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