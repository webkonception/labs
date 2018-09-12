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

    {!! Form::model($commercialscaracts, array('class' => 'form-horizontal', 'id' => 'form-with-validation', 'role'=>'form', 'method' => 'PATCH', 'route' => array(config('quickadmin.route') . '.commercialscaracts.update', $commercialscaracts->id))) !!}
    {!! Form::hidden('user_id',$commercialscaracts->user_id) !!}

    <section class="row well well-sm well-white">
        <div class="col-sm-6">
            <?php
            $label_txt = 'Account ' . ucfirst(trans('validation.attributes.username'));
            $attributes = [
                    'class' => 'form-control',
                    'disabled' => 'disabled',
                    'id' => 'username'
            ];
            $css_state = '';
            if (!empty($username)) {
                $css_state = 'has-success';
            }
            ?>
            <div class="form-group {!! $css_state !!}">
                {!! Form::label('username', $label_txt, ['class'=>'col-xs-3 col-sm-5 control-label']) !!}
                <div class="col-xs-9 col-sm-7">
                    <div class="input-group">
                        {!! Form::email('username', $username, $attributes) !!}
                        <span class="input-group-addon"><span class="fa fa-life-ring"></span></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <?php
            $label_txt = 'Account ' . mb_strtolower(trans('validation.attributes.email'));
            $placeholder = trans('navigation.form_enter_placeholder');
            $attributes = [
                    'class' => 'form-control',
                    'disabled' => 'disabled',
                    'id' => 'useremail'
            ];
            $css_state = '';
            if (!empty($useremail)) {
                $css_state = 'has-success';
            }
            ?>
            <div class="form-group {!! $css_state !!}">
                {!! Form::label('useremail', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                <div class="col-xs-9 col-sm-8">
                    <div class="input-group">
                        {!! Form::text('useremail', $useremail, $attributes) !!}
                        <span class="input-group-addon"><span class="fa fa-envelope-o"></span></span>
                    </div>
                    <br>
                    {!! htmlspecialchars_decode(link_to(config('quickadmin.route') . '/'. LaravelLocalization::transRoute('routes.change_password') . '/' . $user->email, '<i class="fa fa-lock fa-fw"></i>' . trans('navigation.auth.passwords.change') , ['class' => 'btn btn-danger pull-right'])) !!}
                </div>
            </div>
        </div>
    </section>

    <section class="row well well-sm well-white">
        <div class="col-sm-6">
            <?php
            $firstname = old('firstname', isset($commercialscaracts->firstname) ? $commercialscaracts->firstname : '');
            $label_txt = ucfirst(trans('validation.attributes.first_name'));
            $placeholder = trans('navigation.form_enter_placeholder');
            $attributes = [
                    'data-placeholder' => $placeholder,
                    'placeholder' => $placeholder,
                    'class' => 'form-control',
                    'id' => 'firstname'
            ];
            $css_state = '';
            if (!empty($firstname)) {
                $css_state = 'has-success';
            }
            if ($errors->has('firstname')) {
                $css_state = 'has-error';
            }
            ?>
            <div class="form-group {!! $css_state !!}">
                {!! Form::label('firstname', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                <div class="col-xs-9 col-sm-8">
                    {!! Form::text('firstname', $firstname, $attributes) !!}
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <?php
            $name = old('name', isset($commercialscaracts->name) ? $commercialscaracts->name : '');
            $label_txt = ucfirst(trans('validation.attributes.name'));
            $placeholder = trans('navigation.form_enter_placeholder');
            $attributes = [
                    'required'=>'required',
                    'data-placeholder' => $placeholder,
                    'placeholder' => $placeholder,
                    'class' => 'form-control',
                    'id' => 'name'
            ];
            $css_state = '';
            if (!empty($name)) {
                $css_state = 'has-success';
            }
            if ($errors->has('name')) {
                $css_state = 'has-error';
            }
            ?>
            <div class="form-group {!! $css_state !!}">
                {!! Form::label('name', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                <div class="col-xs-9 col-sm-8">
                    {!! Form::text('name', $name, $attributes) !!}
                </div>
            </div>
        </div>
    </section>

    <section class="row well well-sm well-white">
        <div class="col-sm-6">
            <?php
            $address = old('address', isset($commercialscaracts->address) ? $commercialscaracts->address : '');
            $label_txt = ucfirst(trans('validation.attributes.address'));
            $placeholder = trans('navigation.form_enter_placeholder');
            $attributes = [
                    'data-placeholder' => $placeholder,
                    'placeholder' => $placeholder,
                    'class' => 'form-control',
                    'id' => 'address'
            ];
            $css_state = '';
            if (!empty($address)) {
                $css_state = 'has-success';
            }
            if ($errors->has('address')) {
                $css_state = 'has-error';
            }
            ?>
            <div class="form-group {!! $css_state !!}">
                {!! Form::label('address', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                <div class="col-xs-9 col-sm-8">
                    {!! Form::textarea('address', $address, $attributes) !!}
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <?php
            $address_more = old('address_more', isset($commercialscaracts->address_more) ? $commercialscaracts->address_more : '');
            $label_txt = ucfirst(trans('validation.attributes.address_more'));
            $placeholder = trans('navigation.form_enter_placeholder');
            $attributes = [
                    'data-placeholder' => $placeholder,
                    'placeholder' => $placeholder,
                    'class' => 'form-control',
                    'id' => 'address_more'
            ];
            $css_state = '';
            if (!empty($address_more)) {
                $css_state = 'has-success';
            }
            if ($errors->has('address_more')) {
                $css_state = 'has-error';
            }
            ?>
            <div class="form-group {!! $css_state !!}">
                {!! Form::label('address_more', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                <div class="col-xs-9 col-sm-8">
                    {!! Form::textarea('address_more', $address_more, $attributes) !!}
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <?php
            $zip = old('zip', isset($commercialscaracts->zip) ? $commercialscaracts->zip : '');
            $label_txt = ucfirst(trans('validation.attributes.zip'));
            $placeholder = trans('navigation.form_enter_placeholder');
            $attributes = [
                    'data-placeholder' => $placeholder,
                    'placeholder' => $placeholder,
                    'class' => 'form-control',
                    'id' => 'zip'
            ];
            $css_state = '';
            if (!empty($zip)) {
                $css_state = 'has-success';
            }
            if ($errors->has('zip')) {
                $css_state = 'has-error';
            }
            ?>
            <div class="form-group {!! $css_state !!}">
                {!! Form::label('zip', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                <div class="col-xs-9 col-sm-8">
                    {!! Form::text('zip', $zip, $attributes) !!}
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <?php
            $city = old('city', isset($commercialscaracts->city) ? $commercialscaracts->city : '');
            $label_txt = ucfirst(trans('validation.attributes.city'));
            $placeholder = trans('navigation.form_enter_placeholder');
            $attributes = [
                    'data-placeholder' => $placeholder,
                    'placeholder' => $placeholder,
                    'class' => 'form-control',
                    'id' => 'city'
            ];
            $css_state = '';
            if (!empty($city)) {
                $css_state = 'has-success';
            }
            if ($errors->has('city')) {
                $css_state = 'has-error';
            }
            ?>
            <div class="form-group {!! $css_state !!}">
                {!! Form::label('city', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                <div class="col-xs-9 col-sm-8">
                    {!! Form::text('city', $city, $attributes) !!}
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <?php
            $province = old('province', isset($commercialscaracts->province) ? $commercialscaracts->province : '');
            $label_txt = ucfirst(trans('validation.attributes.province'));
            $placeholder = trans('navigation.form_enter_placeholder');
            $attributes = [
                    'data-placeholder' => $placeholder,
                    'placeholder' => $placeholder,
                    'class' => 'form-control',
                    'id' => 'province'
            ];
            $css_state = '';
            if (!empty($province)) {
                $css_state = 'has-success';
            }
            if ($errors->has('province')) {
                $css_state = 'has-error';
            }
            ?>
            <div class="form-group {!! $css_state !!}">
                {!! Form::label('province', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                <div class="col-xs-9 col-sm-8">
                    {!! Form::text('province', $province, $attributes) !!}
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <?php
            $region = old('region', isset($commercialscaracts->region) ? $commercialscaracts->region : '');
            $label_txt = ucfirst(trans('validation.attributes.district'));
            $placeholder = trans('navigation.form_enter_placeholder');
            $attributes = [
                    'data-placeholder' => $placeholder,
                    'placeholder' => $placeholder,
                    'class' => 'form-control',
                    'id' => 'region'
            ];
            $css_state = '';
            if (!empty($region)) {
                $css_state = 'has-success';
            }
            if ($errors->has('region')) {
                $css_state = 'has-error';
            }
            ?>
            <div class="form-group {!! $css_state !!}">
                {!! Form::label('region', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                <div class="col-xs-9 col-sm-8">
                    {!! Form::text('region', $region, $attributes) !!}
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <?php
            $subregion = old('subregion', isset($commercialscaracts->subregion) ? $commercialscaracts->subregion : '');
            $label_txt = ucfirst(trans('validation.attributes.county'));
            $placeholder = trans('navigation.form_enter_placeholder');
            $attributes = [
                    'data-placeholder' => $placeholder,
                    'placeholder' => $placeholder,
                    'class' => 'form-control',
                    'id' => 'subregion'
            ];
            $css_state = '';
            if (!empty($subregion)) {
                $css_state = 'has-success';
            }
            if ($errors->has('subregion')) {
                $css_state = 'has-error';
            }
            ?>
            <div class="form-group {!! $css_state !!}">
                {!! Form::label('subregion', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                <div class="col-xs-9 col-sm-8">
                    {!! Form::text('subregion', $subregion, $attributes) !!}
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <?php
            $country_id = old('country_id', isset($commercialscaracts->country_id) ? $commercialscaracts->country_id : '');
            $label_txt = ucfirst(trans('validation.attributes.country'));
            $placeholder = trans('navigation.form_enter_placeholder');
            $attributes = [
                    'data-placeholder' => $placeholder,
                    'placeholder' => $placeholder,
                    'class' => 'form-control',
                    'id' => 'country_id'
            ];
            $css_state = '';
            if (!empty($country_id)) {
                $css_state = 'has-success';
            }
            if ($errors->has('country_id')) {
                $css_state = 'has-error';
            }
            ?>
            <div class="form-group {!! $css_state !!}">
                {!! Form::label('country_id', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                <div class="col-xs-9 col-sm-8">
                    {!! Form::select('country_id', $countries, $country_id, $attributes) !!}
                </div>
            </div>
        </div>
    </section>

    <section class="row well well-sm well-white">
        <div class="col-sm-6">
            <?php
            $phone_1 = old('phone_1', isset($commercialscaracts->phone_1) ? $commercialscaracts->phone_1 : '');
            $label_txt = ucfirst(trans('validation.attributes.phone'));
            $placeholder = trans('navigation.form_enter_placeholder');
            $attributes = [
                    'required'=>'required',
                    'data-placeholder' => $placeholder,
                    'placeholder' => $placeholder,
                    'class' => 'form-control',
                    'id' => 'phone_1'
            ];
            $css_state = '';
            if (!empty($phone_1)) {
                $css_state = 'has-success';
            }
            if ($errors->has('phone_1')) {
                $css_state = 'has-error';
            }
            ?>
            <div class="form-group {!! $css_state !!}">
                {!! Form::label('phone_1', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                <div class="col-xs-9 col-sm-8">
                    {!! Form::tel('phone_1', $phone_1, $attributes) !!}
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <?php
            $phone_mobile = old('phone_mobile', isset($commercialscaracts->phone_mobile) ? $commercialscaracts->phone_mobile : '');
            $label_txt = ucfirst(trans('validation.attributes.mobile'));
            $placeholder = trans('navigation.form_enter_placeholder');
            $attributes = [
                    'data-placeholder' => $placeholder,
                    'placeholder' => $placeholder,
                    'class' => 'form-control',
                    'id' => 'phone_mobile'
            ];
            $css_state = '';
            if (!empty($phone_mobile)) {
                $css_state = 'has-success';
            }
            if ($errors->has('phone_mobile')) {
                $css_state = 'has-error';
            }
            ?>
            <div class="form-group {!! $css_state !!}">
                {!! Form::label('phone_mobile', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                <div class="col-xs-9 col-sm-8">
                    {!! Form::tel('phone_mobile', $phone_mobile, $attributes) !!}
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <?php
            $emails = old('emails', isset($commercialscaracts->emails) ? $commercialscaracts->emails : Auth::user()->email);
            $emails = mb_strtolower($emails);
            $label_txt = ucfirst(trans('validation.attributes.email') . 's');
            $placeholder = trans('navigation.form_enter_placeholder');
            $attributes = [
                    'required'=>'required',
                    'data-placeholder' => $placeholder,
                    'placeholder' => $placeholder,
                    'class' => 'form-control',
                    'id' => 'emails'
            ];
            $css_state = '';
            if (!empty($emails)) {
                $css_state = 'has-success';
            }
            if ($errors->has('emails')) {
                $css_state = 'has-error';
            }
            ?>
            <div class="form-group {!! $css_state !!}">
                {!! Form::label('emails', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                <div class="col-xs-9 col-sm-8">
                    {!! Form::email('emails', $emails, $attributes) !!}
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <?php
            $fax = old('fax', isset($commercialscaracts->fax) ? $commercialscaracts->fax : '');
            $label_txt = ucfirst(trans('validation.attributes.fax'));
            $placeholder = trans('navigation.form_enter_placeholder');
            $attributes = [
                    'data-placeholder' => $placeholder,
                    'placeholder' => $placeholder,
                    'class' => 'form-control',
                    'id' => 'fax'
            ];
            $css_state = '';
            if (!empty($fax)) {
                $css_state = 'has-success';
            }
            if ($errors->has('fax')) {
                $css_state = 'has-error';
            }
            ?>
            <div class="form-group {!! $css_state !!}">
                {!! Form::label('fax', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                <div class="col-xs-9 col-sm-8">
                    {!! Form::tel('fax', $fax, $attributes) !!}
                </div>
            </div>
        </div>
    </section>
    @if($isAdmin)
    <?php
        $rewrite_url = old('rewrite_url', isset($commercialscaracts->rewrite_url) ? $commercialscaracts->rewrite_url : '');
        $label_txt = 'Commercial url';
        $placeholder = trans('navigation.form_enter_placeholder');
        $attributes = [
                'data-placeholder' => $placeholder,
                'placeholder' => $placeholder,
                'disabled' => 'disabled',
                'class' => 'form-control',
                'id' => 'rewrite_url'
        ];
        $css_state = '';
        if (!empty($rewrite_url)) {
            $css_state = 'has-success';
        }
        if ($errors->has('rewrite_url')) {
            $css_state = 'has-error';
        }
    ?>
    <section class="row well well-sm well-white">
        <div class="col-sm-12">

            <div class="form-group {!! $css_state !!}">
                {!! Form::label('rewrite_url', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                <div class="col-xs-9 col-sm-8">
                    {!! Form::text('rewrite_url', 'https://commercials.youboat.com/' . $rewrite_url, $attributes) !!}
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </section>
    @endif
    <hr>
    <div class="form-group">
        <div class="col-sm-12 text-center">
            {!! Form::button('<i class="fa fa-refresh fa-fw"></i>' . ucfirst(trans('navigation.update')), ['type' => 'submit', 'class' => 'btn btn-lg btn-success btn-exception']) !!}
            {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.commercialscaracts.index', '<i class="fa fa-ban fa-fw"></i>' . ucfirst(trans('navigation.cancel')), [], ['class' => 'btn btn-default pull-right'])) !!}
        </div>
    </div>

    {!! Form::close() !!}

@endsection