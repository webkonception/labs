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

    {!! Form::open(array('route' => config('quickadmin.route') . '.ads.store', 'id'=>'form-with-validation', 'class'=>'form-horizontal')) !!}

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('dealerscaracts_id', 'Dealer*', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-{{ (count($dealers) > 1 && $isAdmin) ? 6 : 9 }} col-sm-6">
                        <div class="input-group">
                            @if (count($dealers) < 2 && $isAdmin)
                            {!! htmlspecialchars_decode(link_to_route('users.create', '<i class="fa fa-plus fa-fw"></i>Add new', ['role'=>'4'], ['class'=>'blank btn btn-sm btn-block btn-success'])) !!}
                            @else
                            {{--{!! Form::select('dealerscaracts_id', $dealerscaracts, old('dealerscaracts_id'), ['placeholder'=>'Pick a choice', 'class'=>'form-control']) !!}--}}
                            {!! Form::select('dealerscaracts_id', $dealers, old('dealerscaracts_id', null), ['class'=>'form-control']) !!}
                            @endif
                            <span class="input-group-addon"><span class="fa fa-anchor"></span></span>
                        </div>
                    </div>
                    @if (count($dealers) > 1 && $isAdmin)
                    <div class="col-xs-3 col-sm-2">
{{--                        {!! htmlspecialchars_decode(link_to_route('users.create', '<i class="fa fa-plus fa-fw"></i>Add', ['role'=>'4'], ['data-target'=>'#myModal', 'data-callback'=>'function(){var update = new updateUserType();}', 'class'=>'blank btn btn-sm btn-success btn-modal'])) !!}--}}
                        {!! htmlspecialchars_decode(link_to_route('users.create', '<i class="fa fa-plus fa-fw"></i>Add', ['role'=>'4'], ['class'=>'blank btn btn-sm btn-success'])) !!}
                    </div>

                    {{--<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModal" aria-hidden="false">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel">Modal title</h4>
                                </div>
                                <div class="modal-body">
                                    ...
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>--}}
                    @endif
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('country_contracts_ids', 'Country contracts', ['class'=>'col-xs-5 col-sm-4 control-label']) !!}
                    <div class="col-xs-{{ (count($country_contracts) > 1 && $isAdmin) ? 9 : 7 }} col-sm-{{ (count($country_contracts) > 1 && $isAdmin) ? 6 : 8 }}">
                        <div class="input-group">
                            @if (count($country_contracts) < 2 && $isAdmin)
                            {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.countrycontracts.create', '<i class="fa fa-plus fa-fw"></i>Add new', [], ['id'=> 'countrycontracts_create', 'class'=>'blank btn btn-sm btn-block btn-success'])) !!}
                            @else
                            {!! Form::select('country_contracts_ids[]', $country_contracts, old('country_contracts_ids[]', null), ['multiple'=>'multiple', 'class'=>'form-control']) !!}
                            @endif
                            <span class="input-group-addon"><span class="fa fa-suitcase"></span></span>
                        </div>
                    </div>
                    @if (count($country_contracts) > 1 && $isAdmin)
                    <div class="col-xs-3 col-sm-2">
                        {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.$countrycontracts.create', '<i class="fa fa-plus fa-fw"></i>Add', [], ['id'=> 'countrycontracts_add', 'class'=>'blank btn btn-sm btn-success'])) !!}
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('adstypes_id', 'Type*', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-{{ ($isAdmin) ? 6 : 9 }} col-sm-{{ ($isAdmin) ? 6 : 8 }}">
                        <div class="input-group">
                            {!! Form::select('adstypes_id', $adstypes, old('adstypes_id', null), ['class'=>'form-control']) !!}
                            <span class="input-group-addon"><span class="fa fa-tag"></span></span>
                        </div>
                    </div>
                    @if ($isAdmin)
                    <div class="col-xs-3 col-sm-2">
                        {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.adstypes.create', '<i class="fa fa-plus fa-fw"></i>Add', [], ['class'=>'blank btn btn-sm btn-success'])) !!}
                    </div>
                    @endif
                </div>

                <div class="form-group">
                    {!! Form::label('categories_ids', 'Categories', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-{{ ($isAdmin) ? 6 : 12 }} col-sm-{{ ($isAdmin) ? 6 : 8 }}">
                        <div class="input-group">
                            {!! Form::select('categories_ids', $categories, old('categories_ids', null), ['class'=>'form-control noselect2', 'id'=>'categories_ids']) !!}
                            <span class="input-group-addon"><span class="fa fa-list-alt"></span></span>
                        </div>
                    </div>
                    @if ($isAdmin)
                    <div class="col-xs-3 col-sm-2">
                        {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.categories.create', '<i class="fa fa-plus fa-fw"></i>Add', [], ['class'=>'blank btn btn-sm btn-success'])) !!}
                    </div>
                    @endif
                </div>

                <div class="form-group">
                    {!! Form::label('subcategories_ids', 'Sub-categories', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-{{ ($isAdmin) ? 6 : 12 }} col-sm-{{ ($isAdmin) ? 6 : 8 }}">
                        <div class="input-group">
                            {!! Form::select('subcategories_ids', $subcategories, old('subcategories_ids', null), ['class'=>'form-control noselect2', 'id'=>'subcategories_ids']) !!}
                            <span class="input-group-addon"><span class="fa fa-indent"></span></span>
                        </div>
                    </div>
                    @if ($isAdmin)
                    <div class="col-xs-3 col-sm-2">
                        {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.subcategories.create', '<i class="fa fa-plus fa-fw"></i>Add', [], ['class'=>'blank btn btn-sm btn-success'])) !!}
                    </div>
                    @endif
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('start_date', 'Start date', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        <div class="input-group date">
                            {!! Form::text('start_date', old('start_date', null), ['class'=>'form-control datepicker']) !!}
                            <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('end_date', 'End date', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8">
                        <div class="input-group date">
                            {!! Form::text('end_date', old('end_date', null), ['class'=>'form-control datepicker']) !!}
                            <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('switch_status', 'Status', ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                    <div class="col-xs-9 col-sm-8 material-switch">
                        {!! Form::checkbox('switch_status', old('status', $status['active']), (old('status') == 'active') ? 'checked' : '', ['class'=>'switch', 'data-target'=>'status', 'data-default'=>'inactive']) !!}
                        <label for="switch_status" class="label-success"></label>
                        <span></span>
                        {!! Form::hidden('status', old('status', $status['inactive']), ['id'=>'status']) !!}
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="form-group">
            <div class="col-sm-12 text-center">
                {!! Form::button('<i class="fa fa-cog fa-fw"></i>' . ucfirst(trans('navigation.create')), ['type'=>'submit', 'class'=>'btn btn-lg btn-primary']) !!}
                {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.ads.index', '<i class="fa fa-mail-reply fa-fw"></i>' . ucfirst(trans('navigation.back')), [], array('class' => 'btn btn-default pull-right'))) !!}
            </div>
        </div>

    {!! Form::close() !!}

@endsection