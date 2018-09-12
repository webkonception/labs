    @include('theme.partials.modals.msg-modal', ['form_referrer'=>'form_newsletter_footer', 'title_modal'=>trans('footer.site-footer-top.title'),'message_modal'=>'message_ajax', 'message_type'=>'ajax'])
    @include('theme.partials.modals.msg-modal', ['form_referrer'=>'form_newsletter_footer', 'title_modal'=>trans('footer.site-footer-top.title'),'message_modal'=>'message_ajax', 'message_type'=>'ajax_error'])
    <div class="footer_widget widget widget_newsletter">
        <h4 class="widgettitle">{!! trans('footer.site-footer-top.title') !!}</h4>
        {{--{!! Form::open(array('url'=>trans_route($currentLocale, 'routes.newsletter'), 'class'=>'form-horizontal', 'id'=>'form_newsletter_footer', 'role'=>'form', 'autocomplete'=>'on')) !!}--}}
        {!! Form::open(array('url'=>'/ajax-newsletter', 'class'=>'form-horizontal ajax-form', 'role'=>'form', 'id'=>'form_newsletter_footer', 'autocomplete'=>'off')) !!}
        {!! csrf_field() !!}
            {!! Form::hidden('country_code', $country_code) !!}
            {!! Form::hidden('referrer', 'form_newsletter_footer') !!}
            <div class="row">
                <div class="col-xs-10 col-xs-offset-1">
                    <div class="form-group">
                        <div class="input-group">
                            {!! Form::text('name', isset($name) ? $name : old('name'), ['class'=>'form-control', 'placeholder'=>ucfirst(trans('validation.attributes.name'))]) !!}
                            <span class="input-group-addon"><span class="fa fa-user fa-fw"></span></span>
                        </div>
                    </div>
                </div>
                <div class="col-xs-10 col-xs-offset-1">
                    <div class="form-group">
                        <div class="input-group">
                            {!! Form::email('email', isset($email) ? $email : old('email'), ['class'=>'form-control', 'placeholder'=>ucfirst(trans('validation.attributes.email')), 'required'=>'required']) !!}
                            <span class="input-group-addon"><span class="fa fa-envelope fa-fw"></span></span>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 text-center">
                    {!! Form::button(trans('navigation.sign_up_now'), ['type'=>'submit', 'class'=>'btn btn-default btn-lg btn-exception']) !!}
                </div>
            </div>
        {!! Form::close() !!}
    </div>