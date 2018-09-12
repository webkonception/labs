<div class="modal fade" id="loginModal" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4>{!! trans('login_modal.login_to_your_account') !!}</h4>
            </div>
            <div class="modal-body">
                {!! Form::open(array('url'=>'login', 'class'=>'form-horizontal', 'autocomplete'=>'off')) !!}
                {!! csrf_field() !!}
                    <div class="input-group{{ isset($errors) && $errors->has('username') ? ' has-error' : '' }}">
                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        <input type="text" class="form-control" placeholder="{!! trans('validation.attributes.username') !!}">
                    </div>
                    <div class="input-group{{ isset($errors) && $errors->has('password') ? ' has-error' : '' }}">
                        <span class="input-group-addon"><i class="fa fa-key"></i></span>
                        <input type="password" class="form-control" placeholder="{!! trans('validation.attributes.password') !!}">
                    </div>
                    <div class="input-group">
                        <div class="checkbox">
                            <label for="remember">
                                {!! Form::checkbox('remember', null) !!} {!! trans('validation.attributes.remember_me') !!}
                            </label>
                        </div>
                    </div>
                    {!! Form::button('<i class="fa fa-btn fa-sign-in fa-fw"></i>' . trans('navigation.auth.login'), ['type' => 'submit', 'class' => 'btn btn-block btn-primary']) !!}
                    {!! link_trans_route('password_email', 'navigation.auth.passwords.forgot', ['class' => 'btn btn-link']) !!}
                {!! Form::close() !!}
            </div>
            {{--<div class="modal-footer">
                <button type="button" class="btn btn-block btn-facebook btn-social"><i class="fa fa-facebook fa-fw"></i>{!! trans('navigation.login_with_facebook') !!}</button>
                <button type="button" class="btn btn-block btn-twitter btn-social"><i class="fa fa-twitter fa-fw"></i>{!! trans('navigation.login_with_twitter') !!}</button>
            </div>--}}
        </div>
    </div>
</div>