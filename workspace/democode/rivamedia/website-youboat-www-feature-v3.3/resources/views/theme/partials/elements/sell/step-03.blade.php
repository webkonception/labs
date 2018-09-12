<?php
    //$ready_to_pay = old('ready_to_pay', !empty($ready_to_pay));
    //$ready_to_pay = old('ready_to_pay', !empty($ready_to_pay) ? $ready_to_pay : false);
?>
<section class="row">
    <?php
        /*$input_name = 'ready_to_pay';
        $input_value = !empty($ready_to_pay) ? $ready_to_pay : old($input_name);
        $attributes = [
            'required' => 'required',
            'class' => 'form-control required',
            'id' => $input_name
        ];
        $css_state = '';
        if (!empty($input_value)) {
            $css_state = 'has-success';
        }
        if ($errors->has($input_name) || empty($input_value)) {
            $css_state = 'has-error';
        }*/
    ?>
    {{--<div class="form-group {!! $css_state !!}">
        <div class="input-group">
            {!! Form::hidden('ready_to_pay', $input_value, $attributes) !!}
        </div>
    </div>--}}
    <div class="col-sm-12 preview-and-payment_errors {!! isset($formPosted)  ? 'hidden' : '' !!}">
        <p class="alert alert-danger lead big">
            {!! trans('filters.please') !!}&nbsp;{!! trans('sell.fill_mandatory_fields') !!}
        </p>
    </div>

    <div class="col-sm-12 preview-and-payment">

        {{--<ul class="nav nav-tabs nav-justified" role="tablist">
            <li role="presentation" class="{!! isset($formPosted) && ('ready_to_pay' === $formPosted || 'success' === $formPosted) ? 'active' : '' !!}"><a class="bg-success uppercase" href="#summary" aria-controls="summary" role="tab" data-toggle="tab">{!! ucfirst(trans('sell.summary')) !!}<i class="fa fa-2x fa-fw fa-newspaper-o pull-right" aria-hidden="true"></i></a></li>
            <li role="presentation" class="text-right">{!! Form::button(ucfirst(trans('navigation.edit')) . '<i class="fa fa-inverse fa-pencil fa-fw" aria-hidden="true"></i>', ['type' => 'button', 'id' => 'btn_modify', 'class' => 'btn btn-md btn-info btn-exception']) !!}</li>
        </ul>--}}

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane {!! isset($formPosted) && ('ready_to_pay' === $formPosted || 'success' === $formPosted) ? 'active' : '' !!}" id="summary">
                {{--<div class="row well well-white">
                    <div class="col-sm-12 preview">
                        @include('theme.partials.elements.sell.sell-preview')
                    </div>
                    @if('success' === $formPosted && !Session::get('country_contracts.id'))
                    <div class="col-sm-12 pay-form">
                        @include('theme.partials.elements.sell.payment')
                    </div>
                    @endif
                </div>--}}
                <div class="preview">
                    @include('theme.partials.elements.sell.sell-preview')
                </div>
                @if('success' === $formPosted && !Session::get('country_contracts.id'))
                <hr>
                <div class="pay-form">
                    @include('theme.partials.elements.sell.payment')
                </div>
                @endif
                <div class="row step-navigation">
                    <div class="col-xs-6 col-sm-4">
                        <span class="btn-prev">
                            <a class="btn btn-info btn-lg" href="#ad_summary" aria-controls="ad_summary" role="tab" data-toggle="tab" title="{!! trans('sell.ad_preview') !!}"><i class="fa fa-eye fa-fw"></i><span class="hidden-xs">{!! trans('sell.ad_preview') !!}</span></a>
                        </span>
                    </div>
                    <div class="col-xs-6 col-sm-4 col-sm-offset-4 text-right">
                        <span class="btn-next">
                            <a class=" btn btn-info btn-lg" href="#account_detail" aria-controls="account_detail" role="tab" data-toggle="tab" title="{!! trans('dashboard.your_account_details') !!}"><span class="hidden-xs">{!! trans('dashboard.your_account_details') !!}</span><i class="fa fa-user fa-fw"></i></a>
                        </span>
                    </div>
                </div>
            </div>
            {{--<div role="tabpanel" class="tab-pane {!! isset($formPosted) && 'ready_to_pay' === $formPosted ? 'active' : '' !!}" id="payment">
                <div class="row well well-white">
                    <div class="col-sm-12">
                        @include('theme.partials.elements.sell.payment')
                    </div>
                </div>
            </div>--}}
        </div>
    </div>
</section>
