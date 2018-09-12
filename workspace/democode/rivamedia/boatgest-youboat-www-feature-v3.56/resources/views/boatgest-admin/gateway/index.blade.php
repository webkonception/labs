
@extends(config('quickadmin.route') . '.layouts.master')

@section('content')

    <p>{!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.scrappingadsdetails.create', '<i class="fa fa-plus fa-fw"></i>Add new', [], array('class' => 'btn btn-success'))) !!}</p>

@if (isset($scrapping_ads_details) && $scrapping_ads_details->count())
    <div class="panel panel-success">
        <div class="panel-heading">
            <h3 class="panel-title">Gateway Ads List</h3>
        </div>
        <div class="panel-body table-responsive">

        </div>
	</div>
@else
    <div class="panel panel-danger">
        <div class="panel-heading">
            <h3 class="panel-title">List</h3>
        </div>
        <div class="panel-body text-danger">
            {!! Form::open(array('route' => config('quickadmin.route') . '.gateway.index', 'id' => 'form-gateway', 'role'=>'form', 'role'=>'form', 'class' => 'form-horizontal')) !!}
            <div class="well ">
                <div class="row">
                    <div class="col-sm-6">
                        @if (isset($dealers_referrer))
                            <?php
                            $referrer = old('referrer');
                            $label_txt = 'Dealer referrer';
                            $attributes = [
                                    'required'=>'required',
                                    'data-header' => '-- ' . trans('navigation.form_any') . ' --',
                                    'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                                    'class' => 'form-control',
                                    'id' => 'referrer'
                            ];
                            $css_state = '';
                            if (!count($dealers_referrer) > 0) {
                                $attributes['disabled'] = 'disabled';
                                $css_state .= 'collapse ';
                            }
                            if (!empty($referrer) || count($dealers_referrer) === 1) {
                                $css_state = 'has-success';
                            }
                            if ($errors->has('countries_id')) {
                                $css_state = 'has-error';
                            }
                            ?>
                            <div class="form-group {!! $css_state !!}">
                                {!! Form::label('countries_id', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                                <div class="col-xs-9 col-sm-8">
                                    {!! Form::select('referrer', $dealers_referrer, $referrer, $attributes) !!}
                                </div>
                            </div>
                        @endif
                    </div>

                    @if (isset($websites))
                        <div class="col-sm-6">
                            <?php
                            $country_code = old('country_code');
                            $label_txt = 'Website ' . ucfirst(trans('validation.attributes.country'));
                            $attributes = [
                                    'required'=>'required',
                                    'data-header' => '-- ' . trans('navigation.form_any') . ' --',
                                    'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                                    'class' => 'form-control countries select2',
                                    'id' => 'country_code'
                            ];
                            $css_state = '';
                            if (!count($websites) > 0) {
                                $attributes['disabled'] = 'disabled';
                                $css_state .= 'collapse ';
                            }
                            if (!empty($country_code) || count($websites) === 1) {
                                $css_state = 'has-success';
                            }
                            if ($errors->has('countries_id')) {
                                $css_state = 'has-error';
                            }
                            ?>
                            <div class="form-group {!! $css_state !!}">
                                {!! Form::label('countries_id', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                                <div class="col-xs-9 col-sm-8">
                                    {!! Form::select('country_code', $websites, $country_code, $attributes) !!}
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (isset($countries))
                    <div class="col-sm-6">
                        <?php
                        $country_code = old('country_code');
                        $label_txt = 'Website ' . ucfirst(trans('validation.attributes.country'));
                        $attributes = [
                                'required'=>'required',
                                'data-header' => '-- ' . trans('navigation.form_any') . ' --',
                                'placeholder' => '-- ' . trans('navigation.form_any') . ' --',
                                'class' => 'form-control countries select2',
                                'id' => 'country_code'
                        ];
                        $css_state = '';
                        if (!count($countries) > 0) {
                            $attributes['disabled'] = 'disabled';
                            $css_state .= 'collapse ';
                        }
                        if (!empty($country_code) || count($countries) === 1) {
                            $css_state = 'has-success';
                        }
                        if ($errors->has('countries_id')) {
                            $css_state = 'has-error';
                        }
                        ?>
                        <div class="form-group {!! $css_state !!}">
                            {!! Form::label('countries_id', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                            <div class="col-xs-9 col-sm-8">
                                {!! Form::select('country_code', $countries, $country_code, $attributes) !!}
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="col-sm-6 col-sm-offset-6 text-right">
                        {!! Form::button('<i class="fa fa-refresh fa-fw"></i>' . ucfirst(trans('navigation.submit')), ['type' => 'submit', 'class' => 'btn btn-lg btn-success btn-exception']) !!}
                        {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.scrappingadsdetails.index', '<i class="fa fa-ban fa-fw"></i>' . ucfirst(trans('navigation.cancel')), [], ['class' => 'btn btn-default pull-right'])) !!}
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
            <div class="row well well-white ">
                <div class="col-sm-12">
                    {!! $return !!}
                    <br><br>
                </div>
            </div>
        </div>
    </div>
@endif

@endsection

@section('javascript')
@stop