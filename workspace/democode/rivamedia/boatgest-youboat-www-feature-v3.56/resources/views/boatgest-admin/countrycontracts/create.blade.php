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

    {!! Form::open(array('route' => config('quickadmin.route') . '.countrycontracts.store', 'id' => 'form-with-validation', 'role'=>'form', 'class' => 'form-horizontal')) !!}
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group {!! (count($dealersusernames) == 1) ? 'has-success' : '' !!}">
                    {!! Form::label('dealerscaracts_id', 'Dealer', ['class'=>'col-sm-4 control-label']) !!}
                    <div class="col-sm-{{ (count($dealersusernames) > 1 && $isAdmin) ? 6 : 8 }}">
                        <div class="input-group">
                            @if (count($dealersusernames) < 2 && $isAdmin)
                            {!! htmlspecialchars_decode(link_to_route('users.create', '<i class="fa fa-plus fa-fw"></i>Add new', ['role'=>'4'], ['class'=>'blank btn btn-sm btn-block btn-success'])) !!}
                            @else
                            {!! Form::select('dealerscaracts_id', $dealersusernames, old('dealerscaracts_id', null), ['class'=>'form-control', 'required'=>'required']) !!}
                            @endif
                            <span class="input-group-addon"><span class="fa fa-anchor"></span></span>
                        </div>
                    </div>
                    @if (count($dealersusernames) > 1 && $isAdmin)
                    <div class="col-sm-2">
                        {!! htmlspecialchars_decode(link_to_route('users.create', '<i class="fa fa-plus fa-fw"></i>Add', ['role'=>'4'], ['class'=>'blank btn btn-sm btn-success'])) !!}
                    </div>
                    @endif
                </div>

                <div class="form-group {!! (count($commercialsusernames) == 1) ? 'has-success' : '' !!}">
                    {!! Form::label('commercialscaracts_id', 'Commercial', ['class'=>'col-sm-4 control-label']) !!}
                    <div class="col-sm-{{ (count($commercialsusernames) > 2 && $isAdmin) ? 6 : 8 }}">
                        <div class="input-group">
                            @if (count($commercialsusernames) < 2 && $isAdmin)
                                {!! htmlspecialchars_decode(link_to_route('users.create', '<i class="fa fa-plus fa-fw"></i>Add new', ['role'=>'5'], ['class'=>'blank btn btn-sm btn-block btn-success'])) !!}
                            @else
                            {!! Form::select('commercialscaracts_id', $commercialsusernames, old('commercialscaracts_id', null), ['class'=>'form-control']) !!}
                            @endif
                            <span class="input-group-addon"><span class="fa fa-dollar"></span></span>
                        </div>
                    </div>
                    @if (count($commercialsusernames) > 2 && $isAdmin)
                    <div class="col-sm-2">
                        {!! htmlspecialchars_decode(link_to_route('users.create', '<i class="fa fa-plus fa-fw"></i>Add', ['role'=>'5'], ['class'=>'blank btn btn-sm btn-success'])) !!}
                    </div>
                    @endif
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('countries_ids', 'Countries associated *', ['class'=>'col-sm-4 control-label']) !!}
                    <div class="col-sm-8">
                        <div class="input-group">
                            {!! Form::select('countries_ids[]', $countries, old('countries_ids', null), ['multiple'=>'multiple', 'class'=>'form-control', 'required'=>'required']) !!}
                            <span class="input-group-addon"><span class="fa fa-map-o"></span></span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('amount', 'Amount', ['class'=>'col-sm-4 control-label']) !!}
                    <div class="col-sm-8">
                        <div class="input-group">
                            {!! Form::text('amount', old('amount', null), ['class'=>'form-control', 'required'=>'required']) !!}
                            <span class="input-group-addon"><span class="fa fa-money"></span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('description', 'Description', ['class'=>'col-sm-4 control-label']) !!}
                    <div class="col-sm-8">
                        <div class="input-group">
                            {!! Form::textarea('description', old('description'), ['class'=>'form-control']) !!}
                            <span class="input-group-addon"><span class="fa fa-pencil-square-o"></span></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    {!! Form::label('start_date', 'Start date', ['class'=>'col-sm-4 control-label']) !!}
                    <div class="col-sm-8">
                        <div class="input-group date">
                            {!! Form::text('start_date', old('start_date', null), ['class'=>'form-control datepicker', 'required'=>'required']) !!}
                            <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('end_date', 'End date', ['class'=>'col-sm-4 control-label']) !!}
                    <div class="col-sm-8">
                        <div class="input-group date">
                            {!! Form::text('end_date', old('end_date', null), ['class'=>'form-control datepicker', 'required'=>'required']) !!}
                            <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('reference', 'Reference', ['class'=>'col-sm-4 control-label']) !!}
                    <div class="col-sm-8">
                        <div class="input-group">
{{--                            {!! Form::text('reference', old('reference'), ['readonly'=>'readonly', 'class'=>'form-control']) !!}--}}
                            {!! Form::text('reference', old('reference'), ['class'=>'form-control']) !!}
                            <span class="input-group-addon"><span class="fa fa-barcode"></span></span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <?php
                        $status = old('status', $status['active']);
                        $default = ($status == 'active') ? 'inactive' : 'active';
                        $checked = ($status == 'active') ? 'checked' : '';
                    ?>
                    {!! Form::label('switch_status', 'Status *', ['class'=>'col-xs-9 col-sm-4 control-label']) !!}
                    <div class="col-xs-3 col-sm-8 material-switch">
                        {!! Form::checkbox('switch_status', $status, $checked, ['class'=>'switch', 'data-target'=>'status', 'data-default'=>$default]) !!}
                        <label for="switch_status" class="label-success"></label>
                        <span></span>
                        {!! Form::hidden('status', $status, ['class'=>'form-control', 'id'=>'status', 'required'=>'required']) !!}
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="form-group">
            <div class="col-sm-12 text-center">
                {!! Form::button('<i class="fa fa-cog fa-fw"></i>' . ucfirst(trans('navigation.create')), ['type'=>'submit', 'class'=>'btn btn-lg btn-primary']) !!}
                {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.countrycontracts.index', '<i class="fa fa-mail-reply fa-fw"></i>' . ucfirst(trans('navigation.back')), [], array('class' => 'btn btn-default pull-right'))) !!}
            </div>
        </div>

    {!! Form::close() !!}

@endsection

@section('javascript')
    <script>
    $(document).ready(function() {
    @if (count($dealersusernames) > 5)
        $('#dealerscaracts_id').select2();
    @endif
    @if (count($commercialsusernames) > 5)
        $('#commercialscaracts_id').select2();
    @endif
    });
    </script>
@endsection