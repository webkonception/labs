<?php
    Cache::flush();
?>
@extends('layouts.theme')

<?php
    if (Auth::check()) {
        $name = !empty($customerscaracts['name']) ? !empty($customerscaracts['firstname']) ? ucwords(mb_strtolower($customerscaracts['firstname'])) . ' ' . mb_strtoupper($customerscaracts['name']) : mb_strtoupper($customerscaracts['name']) : '';
    }
    $bod_reference = '';
    if ( isset($datasRequest['reference']) && !empty($datasRequest['reference'])) {
        $bod_reference =  $datasRequest['reference'];
    }
?>

<?php
    if (isset($form_action) && 'edit' === $form_action) {
        $metas_title = 'Edit your boat search for free | Update by indicating the boat search criteria, sailboat or pneumatic';
    } else {
        $metas_title = 'Submit a boat search for free | Subscribe by indicating the boat search criteria, sailboat or pneumatic';
    }
    $metas_description = 'Looking for a boat? Subscribe by indicating the boat search criteria, sailboat or pneumatic and leave contact you by our client partners who hold may be the vessel for you. boat buyer database';
    $metas = [
            'metas_title' => $metas_title . ' - ' . $website_name,
            'metas_description' => $metas_description
    ];
?>

@section('metas')
    @include('theme.partials.elements.block.metas-block', $metas)
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-9">
            <div class="icon-box ibox-outline">
                <div class="ibox-icon">
                    <i class="fa fa-search-plus"></i>
                </div>
                <h2 class="uppercase strong accent-color text-center inbox-title">{!!  trans('boat_on_demand.title') !!}</h2>
            </div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-3 text-center">
            <span class="lead accent-color-danger">{!! !empty($name) ? $name : trans('boat_on_demand.already_registered') !!}</span><br>
            <a href="{{ url(trans_route($currentLocale, 'routes.dashboard')) }}" title="{!! trans('dashboard.private_individuals_subtitle') !!}" class="btn btn-sm btn-danger"><i class="fa fa-dashboard fa-fw"></i>{!! trans('dashboard.private_individuals_subtitle') !!}</a>
        </div>
        @if (isset($form_action) && 'edit' === $form_action)
        <div class="col-xs-6 col-sm-6 col-md-3 text-right">
            <br>{!! htmlspecialchars_decode(link_to(url()->previous(), '<i class="fa fa-mail-reply fa-fw"></i>Back', ['class' => 'btn btn-sm btn-default'])) !!}
        </div>
        @endif
    </div>

    <hr>
    @if(Session::has('message'))
        @include('theme.partials.modals.msg-modal', ['form_referrer'=> (isset($form_action) && 'edit' === $form_action) ? 'form_bod_edit' : 'form_bod', 'title_modal'=>Session::get('message.title'),'message_modal'=>Session::get('message.text'), 'message_type'=>Session::get('message.type')])
    @elseif($errors->any())
        <?php
            $message_type = 'error';
            $message_modal = '<ul class="clearfix">';
            $message_modal .= implode('', $errors->all('<li>:message</li>'));
            $message_modal .= '</ul>';
            if($errors->first('email')) {
                $change_email_link = link_to('#change_email', trans('boat_on_demand.change_the_email_address'), ['class'=>"btn btn-sm btn-block btn-danger", 'data-dismiss'=>"modal", 'aria-hidden'=>"true"]);
                $message_modal .= '<p>';
                $message_modal .= $change_email_link;
                $message_modal .= '</p>';
                $modal_javascript = "<script>" . "\n";
                $modal_javascript .= "$(document).ready(function () {" . "\n";
                $modal_javascript .= "\t" . "var BODmsgModalError = $('.boat_on_demand #msgModalError');" . "\n";
                $modal_javascript .= "\t" . "$('a[href=\"#change_email\"]', BODmsgModalError).on('click', function() {" . "\n";
                $modal_javascript .= "\t\t" . "$('body,html').animate({scrollTop:$('.boat_on_demand #ci_email').offset().top}, 750, 'easeOutExpo');" . "\n";
                $modal_javascript .= "\t" . "});" . "\n";
                $modal_javascript .= "});" . "\n";
                $modal_javascript .= "</script>" . "\n";
            }
            $message_action = '';
            if($errors->first('email')) {
                $login_link = '<p>';
                $login_link .= link_trans_url(trans_route($currentLocale, 'routes.login'), 'navigation.login', [], ['class' => 'btn btn-block btn-success blank']);
                $login_link .= '</p>';

                $email = !empty($datasRequest['ci_email']) ? $datasRequest['ci_email'] : old('ci_email');
                $forgotten_password_link = '<p>';
                $forgotten_password_link = link_trans_url(trans_route($currentLocale, 'routes.password_email'), 'passwords.textlink_reset_password', ['email' => $email], ['class' => 'btn btn-sm btn-block btn-info blank']);
                $forgotten_password_link .= '</p>';

                $message_action .= '<span class="or">' . trans('navigation.or') .'</span>';

                $message_action .= '<div class="well well-white text-info clearfix">';
                $message_action .= trans('boat_on_demand.email_already_created', ['website_name'=>$website_name, 'login_link'=>$login_link]);
                $message_action .= '</div>';

                $message_action .= '<span class="or">' . trans('navigation.or') .'</span>';

                $message_action .= '<div class="well well-info text-center">';
                $message_action .= trans('boat_on_demand.email_lost_password', ['forgotten_password_link'=>$forgotten_password_link]);
                $message_action .= '</div>';
                $message_type = 'error';
            }

        ?>
        @include('theme.partials.modals.msg-modal', ['form_referrer'=> (isset($form_action) && 'edit' === $form_action) ? 'form_bod_edit' : 'form_bod', 'title_modal'=>trans('navigation.boat_on_demand'),'message_modal'=>$message_modal, 'message_action'=>$message_action, 'message_type'=>$message_type])
    @endif

    @if (isset($form_action) && 'edit' === $form_action)
    {!! Form::model($bodcaracts, array('url' => trans_route($currentLocale, 'routes.dashboard_edit_bod'), 'class' => 'form-horizontal', 'role'=>'form', 'id' => 'form_bod_edit', 'autocomplete'=>'off', 'method' => 'PATCH')) !!}
        {!! Form::hidden('id', $bodcaracts->id) !!}
    @else
    {!! Form::open(array('url'=>trans_route($currentLocale, 'routes.boat_on_demand'), 'class'=>'form-horizontal', 'role'=>'form', 'id'=>'form_bod', 'autocomplete'=>'off')) !!}
    @endif
        {!! csrf_field() !!}
        {!! Form::hidden('country_code', $country_code) !!}
        {!! Form::hidden('reference', $bod_reference) !!}
        {!! Form::hidden('currency', config('youboat.'. $country_code .'.currency')) !!}
        <div class="row">
            <div class="col-sm-12">
                <div class="step well well-sm well-white clearfix">
                    <div class="row">
                        <h3 class="accent-color-danger col-sm-6">{!! trans('boat_on_demand.title_describe') !!}</h3>
                        <p class="lead accent-color col-sm-6">
                            {!! trans('boat_on_demand.more_details_you_provide') !!}
                        </p>
                    </div>
                    @include('theme.partials.elements.bod.filters-form-bod', ['form_action'=>'boat_on_demand'])
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="step well well-sm well-white clearfix">
                    <div class="row">
                        <div class="col-xs-8">
                            <h3 class="accent-color-danger">{!! trans('boat_on_demand.trade_in_your_used_boat') !!}</h3>
                        </div>
                        <div class="col-xs-4">
                            <a class="btn btn-block btn-sm btn-danger" id="btn_recovery" type="button" data-toggle="collapse" data-target="#collapseRecovery" aria-expanded="false" aria-controls="collapseRecovery">
                                {!! trans('boat_on_demand.want_to_trade_in') !!}<span class="fa fa-2x fa-mouse-pointer fa-fw"></span>
                            </a>
                        </div>
                    </div>
                    @include('theme.partials.elements.bod.recovery-form', ['form_action'=>'boat_on_demand'])
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="step well well-sm well-white clearfix">
                    @if (Auth::guest())
                        <h3 class="accent-color-danger">{!! trans('contact_informations.title_adress') !!}</h3>
                    {{--@else
                        <h3 class="accent-color-danger">{!! trans('boat_on_demand.your_account_details') !!}</h3>--}}
                    @endif
                    @include('theme.partials.elements.bod.contact-informations-form', ['form_action'=>'boat_on_demand'])
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="form-group text-center">
                    {!! Form::button('<i class="fa fa-btn fa-2x fa-mouse-pointer fa-fw"></i>' . trans('boat_on_demand.submit_my_research'), ['type' => 'submit', 'class' => 'btn btn-lg btn-primary btn-exception']) !!}
                </div>
            </div>
        </div>
    {!! Form::close() !!}
    @if($ad_banners)
    <div class="row">
        <div class="col-sm-12 hidden-xs text-center">
            <hr>
            @include('theme.partials.elements.advertising.ad', ['ad_size'=>'728x90'])
            <hr>
        </div>
        <div class="col-sm-12 visible-xs text-center">
            <hr>
            @include('theme.partials.elements.advertising.ad', ['ad_size'=>'300x250'])
            <hr>
        </div>
    </div>
    @endif
    <div class="row">
        <div class="col-sm-12"><h3 class="uppercase strong accent-color">What is <strong class="accent-color-danger">&laquo; Boat On Demand &raquo;</strong> ?</h3></div>
        <div class="col-sm-6">

            <h4>The "Boat on demand is a "buyer's bank."</h4>
            <blockquote>
                {!! trans('boat_on_demand.looking_for') !!}
                <br><br>
                The advantage of using the <strong class="accent-color-danger">&laquo; Boat On Demand &raquo;</strong> is to let your research available and accessible by all professional sellers.
                <br>
                <br>
                So you do not have to answer all the advertisements that are of interest to you.
                Simply place your research once in the <strong class="accent-color-danger">&laquo; Boat On Demand &raquo;</strong> and wait to be contacted by telephone or email.
                <br>
                You will describe very precisely the desired boat (brand, model, category, size and especially your budget).
                <br>
                <br>
                <strong class="alert alert-success btn-block text-center uppercase">{!! trans('boat_on_demand.service_completely_free') !!}</strong>
                This positive aspect for the buyer is an additional element of his research even if the registration for the <strong class="accent-color-danger">&laquo; Boat On Demand &raquo;</strong> does not exempt to meet the published ads.
                You double your chances to find faster the perfect boat.
            </blockquote>
        </div>
        <div class="col-sm-6">
            <h4>You are a professional and you sell a boat?</h4>
            <blockquote class="text-justify">
                Consult at the section <strong class="accent-color-danger">&laquo; Boat On Demand &raquo;</strong> allows you to optimize the rotation of your stock of boats saving time.
                <br>
                Indeed, the search for a buyer might be interested in a  new or used boat requires a predominant phase that takes time.
                <br>
                <br>
                Thus, it is necessary to diffuse ads to get qualified contacts. This phase takes an incompressible time.
                <br>
                This requires taking pictures, do a full description of each boat you own, buy based on broadcast media of "packs" of ads.
                <br>
                <br>
                Finally, receive, read and manage contacts is also an activity that mobilizes a lot of time and money. Therefore, professionals have everything to gain by using the <strong class="accent-color-danger">&laquo; Boat On Demand &raquo;</strong>.
                <br>
                <br>
                To get IDs allowing you to access the basic data of qualified buyers :
                <p class="text-center">by email via the
                    <?php
                    $title  = htmlspecialchars_decode(title_case(trans('navigation.contact_form')));
                    $url    = url(trans_route($currentLocale, 'routes.contact'));
                    ?>
                    <a href="{{ $url }}" title="{!! title_case($title) !!}" class="btn btn-sm btn-primary blank">
                        {!! $title !!}
                    </a>
                </p>
                <br>
            </blockquote>
        </div>
    </div>
@endsection

@section('javascript')
    @if(isset($modal_javascript) && !empty($modal_javascript))
    {!! $modal_javascript !!}
    @endif
    {{--@if($errors->any() || Session::has('errors') || Session::has('message') || Session::has('newsletter_message') || Session::has('bod_message'))
        <script>$(document).ready(function(){$("#msgModal").modal('show');});</script>
    @endif--}}
    <script src="{!! asset(config('assets.js.youboat_filters.common.default.url')) !!}" defer></script>
    <script src="{!! asset(config('assets.js.youboat_filters_recovery.common.default.url')) !!}" defer></script>
@endsection

