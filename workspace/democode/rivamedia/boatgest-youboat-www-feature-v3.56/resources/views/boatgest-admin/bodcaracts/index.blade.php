@extends(config('quickadmin.route') . '.layouts.master')

@section('content')

    @if($isAdmin || 'commercial' == Auth::user()->type)
    <p>{!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.bodcaracts.create', '<i class="fa fa-plus fa-fw"></i>Add new', [], array('class' => 'btn btn-success'))) !!}</p>
    @else
    <p class="clearfix">&nbsp;</p>
    @endif

    @if ($bodcaracts->count())
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
                        <th>Name</th>
                        <th>Manufactuer / Model</th>
                        <th>Type / Category</th>
                        <th>Budget</th>
                        {{--<th>SubCategory</th>--}}
                        {{--<th>Name</th>--}}
                        {{--<th>Email</th>--}}
                        {{--<th>Phone</th>--}}
                        <th>Country</th>
                        @if($isAdmin)<th>Status</th>@endif
                        @if($isAdmin)<th>Reference</th>@endif
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($bodcaracts as $row)
                        <?php
                            $status = '';
                            $strike = '';
                            //'not_valid','valid','in_moderation','unpublished'
                            switch($row->status) {
                                default:
                                    $status = '';
                                    $strike = '';
                                    break;
                                case 'not_valid':
                                    $status = ' bg-danger text-danger';
                                    $strike = 'style="text-decoration: line-through;"';
                                    break;
                                case 'valid':
                                    $status = ' bg-success strong';
                                    break;
                                case 'in_moderation':
                                    $status = ' bg-info';
                                    break;
                                case 'unpublished':
                                    $status = ' bg-warning';
                                    break;
                            }

                            $name = !empty($row->ci_firstname) && !empty($row->ci_last_name) ? ucfirst(mb_strtolower($row->ci_firstname)) . ' ' : '';
                            $name .= !empty($row->ci_last_name) ? mb_strtoupper($row->ci_last_name) : '';

                            $adstype_name = '';
                            if (isset($row->adstypes_id)) {
                                $adstype = Search::getAdsTypeById($row->adstypes_id);
                                $adstype_name = !empty($adstype['name']) ? $adstype['name'] : '';
                            }

                            $category_name = '';
                            if (!empty($row->categories_ids)) {
                                $category = Search::getCategoryById($row->categories_ids);
                                $category_name = !empty($category['name']) ? $category['name'] : '';
                            }

                            $subcategory_name = '';
                            if (!empty($row->subcategories_ids)) {
                                $subcategory = Search::getSubCategoryById($row->subcategories_ids);
                                $subcategory_name = !empty($subcategory['name']) ? $subcategory['name'] : '';
                            }

                            $manufacturer_name = '';
                            if (!empty($row->manufacturers_id)) {
                                $manufacturer = Search::getManufacturerById($row->manufacturers_id);
                                $manufacturer_name = !empty($manufacturer['name']) ? $manufacturer['name'] : '';
                            }

                            $model_name = '';
                            if (!empty($row->models_id)) {
                                $model = Search::getModelById($row->models_id);
                                $model_name = !empty($model['name']) ? $model['name'] : '';
                            }
                            //$budget = !empty($row->budget) ? trim(preg_replace('!\s+!', ' ', money_format('%= (#10.0n', $row->budget))) : '';
                            $budget = !empty($row->budget) ? formatPrice($row->budget) : '';
                        ?>
                        <tr>
                            {{--<td class="{!! $status !!}">{!! Form::checkbox('del-' . $row->id, 1, false, ['class'=>'single', 'data-id'=>$row->id]) !!}</td>--}}
                            <td class="{!! $status !!}">
                                @if($isAdmin || 'commercial' == Auth::user()->type)
                                    {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.bodcaracts.edit', '<i class="fa fa-pencil fa-fw"></i>Edit', [$row->id], ['class' => 'btn btn-block btn-xs btn-primary'])) !!}
                                    @if($isAdmin)
                                    <br>
                                    {!! Form::open(array('class' => '', 'method' => 'DELETE', 'onsubmit' => 'return confirm(\'Confirm deletion\');',  'route' => array(config('quickadmin.route') . '.bodcaracts.destroy', $row->id))) !!}
                                    {!! Form::button('<i class="fa fa-trash-o fa-fw"></i>' . trans('navigation.delete'), array('type' => 'submit', 'class' => 'btn btn-block btn-xs btn-danger btn-exception')) !!}
                                    {!! Form::close() !!}
                                    @endif
                                    <br>
                                    {!! htmlspecialchars_decode(link_to_route('BodCaractsDetail', '<i class="fa fa-eye fa-fw"></i>Detail', [$row->id], ['class' => 'btn btn-block btn-sm btn-default'])) !!}
                                @else
                                    {!! htmlspecialchars_decode(link_to_route('BodCaractsDetail', '<i class="fa fa-eye fa-fw"></i>Detail', [$row->id], ['class' => 'btn btn-block btn-sm btn-default'])) !!}
                                @endif
                            </td>
                            {{--<td class="{!! $status !!}" {!! $strike !!}>{{ $row->updated_at->format('Y-m-d') }}</td>--}}
                            <td class="{!! $status !!}" {!! $strike !!}>{{ $name . ' ' . trans('boat_on_demand.is_looking_for') }}</td>
                            <td class="{!! $status !!}" {!! $strike !!}>{{ $manufacturer_name }}{!!  !empty($model_name) ? ' / ' : '' !!}{{ $model_name }}</td>
                            <td class="{!! $status !!}" {!! $strike !!}>{{ $adstype_name }}{!!  !empty($category_name) ? ' / ' : '' !!}{{ $category_name }}</td>
                            {{--<td class="{!! $status !!}" {!! $strike !!}>{{ $subcategory_name }}</td>--}}
                            <td class="{!! $status !!}" {!! $strike !!}>{{ $budget }}</td>
                            {{--<td class="{!! $status !!}" {!! $strike !!}>{{ $row->ci_email }}</td>--}}
                            {{--<td class="{!! $status !!}" {!! $strike !!}>{{ $row->ci_phone }}</td>--}}
                            <?php
                                $country = '';
                                if (!empty($row->ci_countries_id)) {
                                    $country = Search::getCountry($row->ci_countries_id)['name'];
                                }
                            ?>
                            <td class="{!! $status !!}" {!! $strike !!}>{!! $country !!}</td>
                            @if($isAdmin)<td class="{!! $status !!}" {!! $strike !!}>{{ $row->status }}</td>@endif
                            @if($isAdmin)<td class="{!! $status !!}" {!! $strike !!}>{{ $row->reference }}</td>@endif
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
                {!! Form::open(['route' => config('quickadmin.route') . '.bodcaracts.massDelete', 'method' => 'post', 'id' => 'massDelete']) !!}
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