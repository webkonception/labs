@extends(config('quickadmin.route') . '.layouts.master')

@section('content')

    <p>{!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.privatescaracts.create', '<i class="fa fa-plus fa-fw"></i>Add new', [], array('class' => 'btn btn-success'))) !!}</p>

    @if ($privatescaracts->count())
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
                        <th>Private</th>
                        {{--<th>Zip</th>--}}
                        <th>Country</th>
                        {{--<th>City</th>--}}
                        <th>Emails</th>
                        <th>Phones</th>
                        <th>Mobile</th>
                        <th>Fax</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($privatescaracts as $row)
                        <?php
                            $user  = App\User::findOrFail($row->user_id);
                            $row->status = $user ->status;
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

                            $name = !empty($row->denomination) ? '<strong class="text-primary">' . mb_strtoupper($row->denomination) . '</strong>' : '';
                            $name .= !empty($row->name) ? (empty($name) ? '' : '<br><b>') . mb_strtoupper($row->name) . '</b>' : '';
                            $name .= !empty($row->firstname) && !empty($row->name) ? ' <b>' . ucfirst(mb_strtolower($row->firstname)) . '</b>' : '';
                            $name .= !empty($row->user_id) && !empty($privatesusernames[$row->user_id]) ? '<br><em>[' . $privatesusernames[$row->user_id] . ']</em>' : '';

                            $country = '';
                            if (!empty($row->country_id)) {
                                $country = Search::getCountry($row->country_id)['name'];
                            }
                        ?>
                        <tr {!! $strike !!}>
                            {{--<td class="{!! $css_status !!}">{!! Form::checkbox('del-' . $row->id, 1, false, ['class'=>'single', 'data-id'=>$row->id]) !!}</td>--}}
                            <td class="{!! $css_status !!}">
                                {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.privatescaracts.edit', '<i class="fa fa-pencil fa-fw"></i>Edit', [$row->id], ['class' => 'btn btn-block btn-xs btn-primary'])) !!}
                                @if($isAdmin)
                                <br>
                                {!! Form::open(array('class' => '', 'method' => 'DELETE', 'onsubmit' => 'return confirm(\'Confirm deletion\');',  'route' => array(config('quickadmin.route') . '.privatescaracts.destroy', $row->id))) !!}
                                {!!  Form::button('<i class="fa fa-trash-o fa-fw"></i>Delete', array('type' => 'submit', 'class' => 'btn btn-block btn-xs btn-danger btn-exception')) !!}
                                {!! Form::close() !!}
                                @endif
                            </td>
                            <td class="{!! $css_status !!}">{!! $status !!}</td>
                            <td class="{!! $css_status !!}">{!! $name !!}</td>
                            {{--<td class="{!! $css_status !!}">{!! $row->zip !!}</td>--}}
                            <td class="{!! $css_status !!}">{!! $country !!}</td>
                            {{--<td class="{!! $css_status !!}">{!! $row->city !!}</td>--}}
                            <td class="{!! $css_status !!}">{!! str_replace(';','<br>',$row->emails) !!}</td>
                            <td class="{!! $css_status !!}">{!! $row->phone_1 !!}{!! !empty($row->phone_2) ? '<br>' . $row->phone_2 : '' !!}{!! !empty($row->phone_3) ? '<br>' . $row->phone_3 : '' !!}</td>
                            <td class="{!! $css_status !!}">{!! $row->phone_mobile !!}</td>
                            <td class="{!! $css_status !!}">{!! $row->fax !!}</td>
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
                {!! Form::open(['route' => config('quickadmin.route') . '.privatescaracts.massDelete', 'method' => 'post', 'id' => 'massDelete']) !!}
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