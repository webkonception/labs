<?php
    $country_code = isset($bodcaracts->country_code) ? $bodcaracts->country_code : '';
    $website_name = !empty($website_name) ? $website_name : config('youboat.' . $country_code . '.website_name');
?>
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
    <hr>
    @endif


    @if($isAdmin)
    {!! Form::open(array('class' => 'clearfix', 'method' => 'DELETE', 'onsubmit' => 'return confirm(\'Confirm deletion\');',  'route' => array(config('quickadmin.route') . '.bodcaracts.destroy', $bodcaracts->id))) !!}
    {!! Form::button('<i class="fa fa-trash-o fa-fw"></i>Delete', array('type' => 'submit', 'class' => 'pull-right btn btn-danger btn-exception')) !!}
    {!! Form::close() !!}
    @endif

    {!! Form::model($bodcaracts, array('files' => true, 'class' => 'form-horizontal', 'id' => 'form-with-validation', 'role'=>'form', 'method' => 'PATCH', 'route' => array(config('quickadmin.route') . '.bodcaracts.update', $bodcaracts->id))) !!}
        {!! Form::hidden('country_code', $country_code) !!}
        {!! Form::hidden('currency', config('youboat.'. $country_code .'.currency')) !!}

        <div class="show">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs nav-justified" role="tablist">
                <li role="presentation" class="active"><a href="#contact_informations" aria-controls="contact_informations" role="tab" data-toggle="tab">{!! trans('boat_on_demand.customer_details') !!}</a></li>
                <li role="presentation"><a href="#ad_description" aria-controls="ad_description" role="tab" data-toggle="tab">{!! trans('boat_on_demand.title_whished_boat_description') !!}</a></li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="contact_informations">
                    @include('boatgest-admin.bodcaracts.elements.contact-informations-form', ['form_action'=>'boat_on_demand_edit', 'countries'=>$countries, 'website_name'=>$website_name])
                </div>
                <div role="tabpanel" class="tab-pane" id="ad_description">
                    @include('boatgest-admin.bodcaracts.elements.filters-form-bod', [])

                    @include('boatgest-admin.bodcaracts.elements.recovery-form', ['form_action'=>'boat_on_demand_edit'])
                </div>
            </div>
        </div>

        <hr>

        @if($isAdmin || 'commercial' == Auth::user()->type)
        <section class="well well-white">
            <div class="row">

                @if($isAdmin)
                    <div class="col-xs-12">
                        <div class="row lead strong">
                            <?php
                            $reference = isset($bodcaracts->reference) ? $bodcaracts->reference : '';
                            $label_txt = 'Ref.';
                            ?>
                            <div class="text-primary col-xs-12 col-sm-4 col-md-5 col-lg-4">{!! $label_txt !!}:</div>
                            <div class="col-xs-12 col-sm-8 col-md-7 col-lg-8">{!! $reference !!}</div>
                            {!! Form::hidden('reference', $reference) !!}
                        </div>
                    </div>
                @endif

                <div class="col-xs-12">
                    <div class="row lead strong">
                        <?php
                        $deposit_date = isset($bodcaracts->updated_at) ? $bodcaracts->updated_at : '';
                        $label_txt = ucfirst(trans('boat_on_demand.deposit_date'));
                        ?>
                        <div class="text-primary col-xs-12 col-sm-4 col-md-5 col-lg-4">{!! $label_txt !!}:</div>
                        <div class="col-xs-12 col-sm-8 col-md-7 col-lg-8">{!! $deposit_date !!}</div>
                    </div>
                </div>

                <div class="col-xs-12">
                    <?php
                    $bod_status = isset($bodcaracts->status) ? $bodcaracts->status : '';
                    $label_txt = 'Status:';
                    $attributes = [
                            'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                            'data-placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                            'class' => 'form-control',
                            'id' => 'status'
                    ];

                    $css_state = '';
                    if (!empty($bod_status)) {
                        $css_state = 'has-success';
                    }
                    ?>
                    <div class="form-group {!! $css_state !!}">
                        {!! Form::label('status', $label_txt, ['class'=>'text-primary col-xs-12 col-sm-4 lead strong']) !!}
                        <div class="col-xs-6 col-sm-4 col-md-8 text-center">
                            {!! Form::select('status', $status, $bod_status,  $attributes) !!}
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <hr>
        @endif

        <div class="form-group">
            <div class="col-sm-12 text-center">
                {!! Form::button('<i class="fa fa-refresh fa-fw"></i>' . ucfirst(trans('navigation.update')), ['type' => 'submit', 'class' => 'btn btn-lg btn-success btn-exception']) !!}
                {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.bodcaracts.index', '<i class="fa fa-ban fa-fw"></i>' . ucfirst(trans('navigation.cancel')), [], ['class' => 'btn btn-default pull-right'])) !!}
            </div>
        </div>

    {!! Form::close() !!}

@endsection

@section('javascript')
    <script src="{{ asset('assets/vendor/youboat/js/filters_search.js') }}"></script>
    <script src="{{ asset('assets/vendor/youboat/js/filters_recovery.js') }}"></script>
@endsection
