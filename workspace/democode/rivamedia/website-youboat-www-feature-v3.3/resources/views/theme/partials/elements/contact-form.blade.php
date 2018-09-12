<div class="well row">

    <div class="col-sm-12 well well-white">
        <h3>{!! trans('contact.title', ['website_name' => $website_name]) !!}</h3>
        <p>{!! trans('contact.desc', ['website_name' => $website_name]) !!}</p>
    </div>

    <div class="spacer-20"></div>

    {!! Form::open(array('url'=>trans_route($currentLocale, (preg_match('/landing/', $currentRoute) ? 'routes.landingcontact' : 'routes.contact')), 'class'=>'form', 'id'=>'form_contact', 'role'=>'form')) !!}
    {!! csrf_field() !!}
    {!! Form::hidden('country_code', $country_code) !!}

    <div class="form-group col-sm-12 {{ $errors->has('name') ? 'has-error' : '' }}">
        {!! Form::label('name', ucfirst(trans('validation.attributes.name')), ['class'=>'control-label']) !!}
        {!! Form::text('name', old('name'), ['class'=>'form-control', 'placeholder'=>trans('validation.attributes.name'), 'required'=>'required']) !!}
        @if ($errors->has('name'))
            <span class="help-block"><strong>{{ $errors->first('name') }}</strong></span>
        @endif
    </div>

    <div class="form-group col-sm-12 col-md-6 {{ $errors->has('email') ? 'has-error' : '' }}">
        {!! Form::label('email', ucfirst(trans('validation.attributes.email')), ['class'=>'control-label']) !!}
        {!! Form::email('email', old('email'), ['class'=>'form-control', 'placeholder'=>trans('validation.attributes.email'), 'required'=>'required']) !!}
        @if ($errors->has('email'))
            <span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
        @endif
    </div>

    <div class="form-group col-sm-12 col-md-6 {{ $errors->has('phone') ? 'has-error' : '' }}">
        {!! Form::label('phone', ucfirst(trans('validation.attributes.phone')), ['class'=>'control-label']) !!}
        {!! Form::tel('phone', old('phone'), ['class'=>'form-control', 'placeholder'=>trans('validation.attributes.phone')]) !!}
        @if ($errors->has('phone'))
            <span class="help-block"><strong>{{ $errors->first('phone') }}</strong></span>
        @endif
    </div>

    <div class="form-group col-sm-12 {{ $errors->has('message') ? 'has-error' : '' }}">
        {!! Form::label('message', ucfirst(trans('validation.attributes.message')), ['class'=>'control-label']) !!}
        {!! Form::textarea('message', old('message'), ['class'=>'form-control', 'placeholder'=>trans('validation.attributes.message'), 'required'=>'required']) !!}
        @if ($errors->has('message'))
            <span class="help-block"><strong>{{ $errors->first('message') }}</strong></span>
        @endif
    </div>
    @if (!app()->isLocal() && config('youboat.' . $country_code . '.recaptcha'))
        <div class="form-group col-sm-8 {{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }} controls">
            <div class="row">
                <div class="col-xs-12">
                    {!! Recaptcha::render(['lang'=> config('app.locale'), 'theme'=>'light', 'callback'=>'recaptchaCallback']) !!}
                </div>
                @if ($errors->has('g-recaptcha-response'))
                    <div class="col-xs-12">
                        <div class="help-block">
                            <ul class="error-list">
                                @foreach ($errors->get('g-recaptcha-response') as $message)
                                    <li><strong>{!! $message !!}</strong></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <div class="form-group text-center {!! (!app()->isLocal() && config('youboat.' . $country_code . '.recaptcha')) ? 'col-xs-12 col-sm-12 col-md-12 col-lg-4' : 'col-xs-12 col-sm-12 col-md-6' !!} pull-right">
        <br>
        {!! Form::button(trans('navigation.contact_us'), ['type'=>'submit', 'class'=>'btn btn-lg btn-primary btn-block btn-exception']) !!}
    </div>
    {!! Form::close() !!}

</div>