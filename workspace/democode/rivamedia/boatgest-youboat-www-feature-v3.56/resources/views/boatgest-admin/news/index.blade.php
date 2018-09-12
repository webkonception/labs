@extends(config('quickadmin.route') . '.layouts.master')

@section('content')

    @if($isAdmin || 'commercial' == Auth::user()->type)
    <p>{!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.news.create', '<i class="fa fa-plus fa-fw"></i>Add new', [], array('class' => 'btn btn-success'))) !!}</p>
    @else
    <p class="clearfix">&nbsp;</p>
    @endif

    @if ($news->count())
        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title">List</h3>
            </div>
            <div class="panel-body table-responsive">
                <table class="table table-striped table-hover datatable">
                    <thead>
                    <tr>
                        {{--<th class="nosort">{!! Form::checkbox('delete_all', 1, false, ['class' => 'mass']) !!}</th>--}}
                        {{--<th>Prospective customer id</th>--}}
                        <th class="nosort">Actions</th>
                        {{--<th>{!! trans('boat_on_demand.deposit_date') !!}</th>--}}
                        <th>Date</th>
                        <th>Author</th>
                        <th>Country</th>
                        <th>Title</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($news as $row)
                        <?php
                            $name = !empty($row->author_name) ? mb_strtoupper($row->author_name) : '';
                        ?>
                        <tr>
                            {{--<td>{!! $row->prospective_customer_id !!}</td>--}}
                            <td>
                                {!! htmlspecialchars_decode(link_to_route('NewsDetail', '<i class="fa fa-eye fa-fw"></i>Detail', [$row->id], ['class' => 'btn btn-block btn-sm btn-default'])) !!}
                            </td>
                            <td>{!! $row->updated_at->format('Y-m-d') !!}</td>
                            <td>
                                {!! $name . ' ' . trans('boat_on_demand.is_looking_for') !!}
                                {!! !empty($row->ci_email) ? '<br>' . $row->ci_email : '' !!}
                                {!! !empty($row->ci_phone) ? '<br>' . $row->ci_phone : '' !!}
                            </td>
                            <td>{!! $ad !!}</td>
                            <?php
                                $country = '';
                                if (!empty($row->ci_countries_id)) {
                                    $country = Search::getCountry($row->ci_countries_id)['name'];
                                }
                            ?>
                            <td>{!! $country !!}</td>
                            @if($isAdmin)<td>{!! $row->reference !!}</td>@endif
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
                {!! Form::open(['route' => config('quickadmin.route') . '.news.massDelete', 'method' => 'post', 'id' => 'massDelete']) !!}
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