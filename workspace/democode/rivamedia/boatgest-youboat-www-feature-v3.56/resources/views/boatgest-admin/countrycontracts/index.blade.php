@extends(config('quickadmin.route') . '.layouts.master')

@section('content')

    <p>{!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.countrycontracts.create', '<i class="fa fa-plus fa-fw"></i>Add new', [], array('class' => 'btn btn-success'))) !!}</p>
    @if ($countrycontracts->count())
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
                        <th>Status</th>
                        <th>Dealer</th>
                        <th>Commercial</th>
                        @if($isAdmin)
                        <th>Reference</th>
                        @endif
                        <th>Countries</th>
                        <th>Start date</th>
                        <th>End date</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($countrycontracts as $row)
                        <?php
                            $css_status = '';
                            $strike = '';
                            $status = $row->status;
                            switch($status) {
                                default:
                                    $css_status = '';
                                    $strike = '';
                                    break;
                                case 'closed':
                                    $css_status = 'bg bg-warning';
                                    $strike = 'style="text-decoration: line-through;"';
                                    $status = '<i title="'. $status .'" class="text-warning fa fa-2x fa-window-close"></i>';
                                    break;
                                case 'mailing':
                                    $css_status = 'bg-info bg-mailing';
                                    $status = '<i title="'. $status .'" class="text-info fa fa-2x fa-envelope"></i>';
                                    break;
                                case 'no_answer':
                                    $css_status = 'bg-warning bg-no_answer';
                                    $status = '<span title="'. $status .'" class="text-warning fa-stack fa-lg fa-2x"><i class="fa fa-phone fa-stack-1x"></i><i class="fa fa-ban fa-stack-2x text-danger"></i></span>';
                                    break;
                                case 'nok':
                                    $css_status = 'bg-danger';
                                    $strike = 'style="text-decoration: line-through;"';
                                    $status = '<i title="'. $status .'" class="text-danger fa fa-2x fa-frown-o"></i>';
                                    break;
                                case 'ok':
                                    $css_status = 'bg-success bg-ok';
                                    $status = '<i title="'. $status .'" class="text-success fa fa-2x fa-check-circle"></i>';
                                    break;
                                case 'phone_pb':
                                    $css_status = 'bg-warning bg-phone_pb';
                                    $status = '<span title="'. $status .'" class="text-warning fa-stack fa-lg fa-2x"><i class="fa fa-phone fa-stack-1x"></i><i class="fa fa-exclamation fa-stack-2x text-danger"></i></span>';
                                    break;
                                case 'recall':
                                    $css_status = 'bg-warning bg-recall';
                                    $status = '<i title="'. $status .'" class="text-warning fa fa-2x fa-phone-square"></i>';
                                    break;
                                case 'inactive':
                                    $css_status = 'inactive bg-inactive';
                                    $status = '<i title="'. $status .'" class="fa fa-2x fa-toggle-off"></i>';
                                    break;
                                case 'active' :
                                    $css_status = 'bg-success';
                                    $status = '<i title="'. $status .'" class="text-success fa fa-2x fa-check-circle"></i>';
                                    break;
                            }
                            $status .= '<br>' . $row->status;
                        ?>
                        <tr {!! $strike !!}>
                            {{--<td class="{!! $css_status !!}">{!! Form::checkbox('del-' . $row->id, 1, false, ['class'=>'single', 'data-id'=>$row->id]) !!}</td>--}}
                            <td class="{!! $css_status !!}">
                                {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.countrycontracts.edit', '<i class="fa fa-pencil fa-fw"></i>Edit', [$row->id], ['class' => 'btn btn-block btn-xs btn-primary'])) !!}
                                @if($isAdmin)
                                    <br>
                                    {!! Form::open(array('class' => '', 'method' => 'DELETE', 'onsubmit' => 'return confirm(\'Confirm deletion\');',  'route' => array(config('quickadmin.route') . '.countrycontracts.destroy', $row->id))) !!}
                                    {!! Form::button('<i class="fa fa-trash-o fa-fw"></i>Delete', array('type' => 'submit', 'class' => 'btn btn-block btn-xs btn-danger')) !!}
                                    {!! Form::close() !!}
                                @endif
                            </td>
                            <td class="{!! $css_status !!}">{!! $status !!}</td>
                            <td class="{!! $css_status !!}">@if(!empty($row->dealerscaracts_id)){!! !empty($dealerName = Search::getDealerCaractsById([$row->dealerscaracts_id])) ? $dealerName['name'] : $row->dealerscaracts_id !!}@endif</td>
                            <td class="{!! $css_status !!}">@if(!empty($row->commercialscaracts_id)){!! !empty($commercialName = Search::getCommercialCaractsById([$row->commercialscaracts_id])) ? $commercialName['name'] : $row->commercialscaracts_id !!}@endif</td>
                            @if($isAdmin)
                            <td class="{!! $css_status !!}">{!! $row->reference !!}</td>
                            @endif

                            <?php
                            $countries = '';
                            if (!empty($row->countries_ids)) {
                                //$countries_ids = unserialize($row->countries_ids);
                                $countries_ids = explode(';',$row->countries_ids);
                                foreach($countries_ids as $key => $country_id) {
                                    $getCountry = Search::getCountry($country_id);
                                    $countries .= is_array($getCountry) && array_key_exists('name', $getCountry) ? $getCountry['name'] . ', ' : '';
                                }
                                $countries = rtrim($countries, ", ");
                            }
                            ?>
                            <td class="{!! $css_status !!}">{!! $countries !!}</td>
                            <td class="{!! $css_status !!}">{!! $row->start_date !!}</td>
                            <td class="{!! $css_status !!}">{!! $row->end_date !!}</td>
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
                {!! Form::open(['route' => config('quickadmin.route') . '.countrycontracts.massDelete', 'method' => 'post', 'id' => 'massDelete']) !!}
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
