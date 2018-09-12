<?php
    $ci_firstname              = !empty($enquiries->ci_firstname) ? $enquiries->ci_firstname : null;
    $ci_firstname              = ucwords(mb_strtolower($ci_firstname));
    $ci_last_name              = !empty($enquiries->ci_last_name) ? $enquiries->ci_last_name : null;
    $ci_last_name              = mb_strtoupper($ci_last_name);
    $ci_email                  = !empty($enquiries->ci_email) ? $enquiries->ci_email : null;
    $ci_email                  = mb_strtolower($ci_email);

    $ci_phone                  = !empty($enquiries->ci_phone) ? $enquiries->ci_phone : null;

    $ci_city                   = !empty($enquiries->ci_city) ? $enquiries->ci_city : null;
    $ci_city                   = mb_strtoupper($ci_city);
    $ci_zip                    = !empty($enquiries->ci_zip) ? $enquiries->ci_zip : null;
    $ci_country                = !empty($enquiries->ci_countries_id) ? Search::getCountry($enquiries->ci_countries_id) : null;
    $ci_description            = !empty($enquiries->ci_description) ? $enquiries->ci_description : null;

    $attributes = ['class' => 'form-control', 'readonly' => 'readonly'];

?>
<section class="well well-sm alert-info">
    <h3 class="strong">{!! trans('boat_on_demand.customer_details') !!}</h3>
    <div class="well well-sm well-white">
        <h4 class="strong">{!! trans('boat_on_demand.contact_information') !!}</h4>
        <section class="row">
            <div class="col-sm-6">
                <?php
                    $label_txt = ucfirst(trans('validation.attributes.first_name'));
                ?>
                <div class="form-group">
                    {!! Form::label('ci_firstname', $label_txt, ['class' => 'col-sm-4 control-label']) !!}
                    <div class="col-sm-8">
                        {!! Form::text('ci_firstname', !empty($ci_firstname) ? $ci_firstname : old('ci_firstname'), $attributes) !!}
                    </div>
                </div>
                <?php
                    $label_txt = ucfirst(trans('validation.attributes.last_name'));
                ?>
                <div class="form-group">
                    {!! Form::label('ci_last_name', $label_txt, ['class' => 'col-sm-4 control-label']) !!}
                    <div class="col-sm-8">
                        {!! Form::text('ci_last_name', !empty($ci_last_name) ? $ci_last_name : old('ci_last_name'), $attributes) !!}
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                @if($isAdmin || 'commercial' == Auth::user()->type)
                <?php
                    $label_txt = ucfirst(trans('validation.attributes.email'));

                    $css_state = '';
                    if (!empty($ci_email)) {
                        $css_state = 'has-success';
                    }
                    if ($errors->has('ci_email') || $errors->has('email')) {
                        $css_state = 'has-error';
                    }

                    $infoEmail = '';
                    if (!empty($ci_email)) {
                        $fromemail                  = config('youboat.' . $country_code . '.MAIL_NO_REPLY_EMAIL');
                        $verifyEmail                = verifyEmail($ci_email, $fromemail, true);

                        if(is_array($verifyEmail) && !empty($verifyEmail[0])) {
                            $infoEmail = '<span class="col-sm-1">';
                            $infoEmail .= '<i class="col-sm-1 fa fa-';
                            $infoEmail .= str_replace(['invalid','valid'], ['exclamation','check'], $verifyEmail[0]);
                            $infoEmail .= '">';
                            //$infoEmail .= ' title="' . ($verifyEmail[0] == 'valid') ? $verifyEmail[0] : 'Need verification !' .'">';
                            $infoEmail .= '</i>';
                            $infoEmail .= '</span>';
                        }
                        if($css_state != 'has-error' && preg_match('/invalid/i', $verifyEmail[0])) {
                            $css_state = 'has-warning';
                        }
                    }
                ?>
                <div class="form-group {!! $css_state !!}">
                    {!! Form::label('ci_email', $label_txt, ['class'=>'col-sm-3 control-label']) !!}
                    {!! $infoEmail !!}
                    <div class="col-sm-8">
                        {!! Form::email('ci_email', !empty($ci_email) ? $ci_email : old('ci_email'), $attributes) !!}
                    </div>
                </div>
                @endif
                <?php
                    $label_txt = ucfirst(trans('validation.attributes.phone'));
                ?>
                <div class="form-group">
                    {!! Form::label('ci_phone', $label_txt, ['class' => 'col-sm-4 control-label']) !!}
                    <div class="col-sm-8">
                        {!! Form::tel('ci_phone', !empty($ci_phone) ? $ci_phone : old('ci_phone'), $attributes) !!}
                    </div>
                </div>
            </div>
        </section>

        <hr>

        <section class="row">
            @if(!empty($ci_zip))
            <div class="col-sm-6">
                <?php
                    $label_txt = ucfirst(trans('validation.attributes.zip'));
                ?>
                <div class="form-group">
                    {!! Form::label('ci_zip', $label_txt, ['class' => 'col-xs-7 col-sm-4 control-label']) !!}
                    <div class="col-xs-5 col-sm-8">
                        {!! Form::text('ci_zip', !empty($ci_zip) ? $ci_zip : old('ci_zip'), $attributes) !!}
                    </div>
                </div>
            </div>
            @endif
            @if(!empty($ci_city))
                <div class="col-sm-6">
                <?php
                    $label_txt = ucfirst(trans('validation.attributes.city'));
                ?>
                <div class="form-group">
                    {!! Form::label('ci_city', $label_txt, ['class' => 'col-sm-4 control-label']) !!}
                    <div class="col-sm-8">
                        {!! Form::text('ci_city', !empty($ci_city) ? $ci_city : old('ci_city'), $attributes) !!}
                    </div>
                </div>
            </div>
            @endif
            @if (isset($ci_country))
            <div class="col-sm-6">
                <?php
                    $label_txt = ucfirst(trans('validation.attributes.country'));
                ?>
                <div class="form-group">
                    {!! Form::label('ci_country_name', $label_txt, ['class'=>'col-sm-4 control-label']) !!}
                    <div class="col-sm-8">
                        {!! Form::text('ci_country_name', $ci_country['name'], $attributes) !!}
                    </div>
                </div>
            </div>
            @endif
        </section>

        @if($isAdmin || 'commercial' == Auth::user()->type)
        <hr>

        <section class="row">
            <?php
                $cols = '';
                if (!$isAdmin || 'commercial' != Auth::user()->type) {
                   $cols = 'col-sm-12';
                }
            ?>
            <div class="{!! !empty($cols) ? $cols : 'col-sm-6' !!}">
                <?php
                    $label_txt = ucfirst(trans('validation.attributes.comment'));
                ?>
                <div class="form-group">
                    {!! Form::label('ci_description', $label_txt, ['class' => (!empty($cols) ? $cols : 'col-sm-4') . ' control-label']) !!}
                    <div class="{!! !empty($cols) ? $cols : 'col-sm-8' !!}">
                        {!! Form::textarea('ci_description', !empty($ci_description) ? $ci_description : old('ci_description'), $attributes) !!}
                    </div>
                </div>
            </div>
        </section>
        @endif
    </div>
</section>
