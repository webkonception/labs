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

    {!! Form::open(array('route' => config('quickadmin.route') . '.customerscaracts.store', 'id' => 'form-with-validation', 'role'=>'form', 'role'=>'form', 'class' => 'form-horizontal')) !!}

    <section class="row well well-sm well-white">
        <div class="col-sm-6">
            <?php
            $user_id = !empty($user_id) ? $user_id : old('user_id', null);
            $label_txt = 'Customer ' . ucfirst(trans('validation.attributes.username'));
            $attributes = [
                    'class' => 'form-control',
                    'required' => 'required',
                    'id' => 'user_id'
            ];
            if (count($customersusernames) == 1) {
                $attributes['readonly'] = 'readonly';
            }
            $css_state = '';
            if (!empty($user_id) || !empty($username)) {
                $css_state = 'has-success';
            }
            ?>
            <div class="form-group {!! $css_state !!}">
                {!! Form::label('user_id', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                <div class="col-xs-{{ (count($customersusernames) > 1 && $isAdmin) ? 6 : 9 }} col-sm-{{ (count($customersusernames) > 1 && $isAdmin) ? 6 : 8 }}">
                    <div class="input-group">
                        @if (!empty($username))
                            {!! Form::text('username', $username, $attributes) !!}
                            {!! Form::hidden('user_id', $user_id) !!}
                        @elseif (count($customersusernames) < 1 && $isAdmin)
                            {!! htmlspecialchars_decode(link_to_route('users.create', '<i class="fa fa-plus fa-fw"></i>Add new', ['role'=>'4'], ['class'=>'blank btn btn-sm btn-block btn-success'])) !!}
                        @else
                            {!! Form::select('user_id', $customersusernames, $user_id, $attributes) !!}
                        @endif
                        <span class="input-group-addon"><span class="fa fa-life-ring"></span></span>
                    </div>
                </div>
                @if (count($customersusernames) > 1 && $isAdmin)
                    <div class="col-xs-3 col-sm-2">
                        {!! htmlspecialchars_decode(link_to_route('users.create', '<i class="fa fa-plus fa-fw"></i>Add', ['role'=>'4'], ['class'=>'blank btn btn-sm btn-success'])) !!}
                    </div>
                @endif
            </div>
        </div>

        <div class="col-sm-6">
            <?php
            $emails = old('emails', !empty($useremail) ? $useremail : null);
            $label_txt = ucfirst(trans('validation.attributes.email'));
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
    </section>

    <section class="row well well-sm well-white">
        <div class="col-sm-6">
            <?php
            $firstname = old('firstname');
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
            $name = old('name');
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
            if (!empty($firstname)) {
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
            $address = old('address');
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
            $address_more = old('address_more');
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
            $zip = old('zip');
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
            $city = old('city');
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
            $province = old('province');
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
            $region = old('region');
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
            $subregion = old('subregion');
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
            $country_id = old('country_id');
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
            $phone_1 = old('phone_1');
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
            $phone_mobile = old('phone_mobile');
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
            $fax = old('fax');
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

    <section class="row well well-sm well-white">
        <div class="col-sm-6">
            <?php
            $twitter = old('twitter');
            $label_txt = 'Twitter';
            $placeholder = trans('navigation.form_enter_placeholder');
            $attributes = [
                    'data-placeholder' => $placeholder,
                    'placeholder' => $placeholder,
                    'class' => 'form-control',
                    'id' => 'twitter'
            ];
            $css_state = '';
            if (!empty($twitter)) {
                $css_state = 'has-success';
            }
            if ($errors->has('twitter')) {
                $css_state = 'has-error';
            }
            ?>
            <div class="form-group {!! $css_state !!}">
                {!! Form::label('twitter', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                <div class="col-xs-9 col-sm-8">
                    {!! Form::text('twitter', $twitter, $attributes) !!}
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <?php
            $facebook = old('facebook');
            $label_txt = 'Facebook';
            $placeholder = trans('navigation.form_enter_placeholder');
            $attributes = [
                    'data-placeholder' => $placeholder,
                    'placeholder' => $placeholder,
                    'class' => 'form-control',
                    'id' => 'facebook'
            ];
            $css_state = '';
            if (!empty($facebook)) {
                $css_state = 'has-success';
            }
            if ($errors->has('facebook')) {
                $css_state = 'has-error';
            }
            ?>
            <div class="form-group {!! $css_state !!}">
                {!! Form::label('facebook', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                <div class="col-xs-9 col-sm-8">
                    {!! Form::text('facebook', $facebook, $attributes) !!}
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <?php
            $label_txt = ucfirst(trans('contact_informations.label_optin_agree_emails'));
            $agree_emails = old('agree_emails');
            $default = ($agree_emails == '1') ? '0' : '1';
            ?>
            <div class="form-group">
                {!! Form::label('switch_agree_emails', $label_txt, ['class'=>'col-xs-10 control-label']) !!}
                <div class="col-xs-2 material-switch">
                    {!! Form::checkbox('switch_agree_emails', $agree_emails, ($agree_emails == '1') ? 'checked' : '', ['class'=>'switch', 'data-target'=>'agree_emails', 'data-default'=>$default]) !!}
                    <label for="switch_agree_emails" class="label-success"></label>
                    <span></span>
                    {!! Form::hidden('agree_emails', $agree_emails, ['class'=>'form-control', 'id'=>'agree_emails']) !!}
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </section>
    <hr>
    <div class="form-group">
        <div class="col-sm-12 text-center">
            {!! Form::button('<i class="fa fa-cog fa-fw"></i>' . ucfirst(trans('navigation.create')), ['type' => 'submit', 'class' => 'btn btn-lg btn-primary btn-exception']) !!}
            {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.customerscaracts.index', '<i class="fa fa-mail-reply fa-fw"></i>' . ucfirst(trans('navigation.back')), [], array('class' => 'btn btn-default pull-right'))) !!}
        </div>
    </div>

    {!! Form::close() !!}

@endsection