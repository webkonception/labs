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

    {!! Form::model($dealerscaracts, array('class' => 'form-horizontal', 'id' => 'form-with-validation', 'role'=>'form', 'files'=>true, 'method' => 'PATCH', 'route' => array(config('quickadmin.route') . '.dealerscaracts.update', $dealerscaracts->id))) !!}
    {!! Form::hidden('user_id',$dealerscaracts->user_id) !!}
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
                {!! Form::label('username', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                <div class="col-xs-9 col-sm-8">
                    <div class="input-group">
                        {!! Form::text('username', $username, $attributes) !!}
                        <span class="input-group-addon"><span class="fa fa-anchor"></span></span>
                    </div>
                </div>
            </div>
            <?php
                $user_status = !empty($dealerscaracts->status) ? $dealerscaracts->status : old('status', $dealerscaracts->status);
                $label_txt = ucfirst('status');
                $placeholder = trans('navigation.form_enter_placeholder');
                $attributes = [
                        'disabled'=>'disabled',
                        'data-placeholder' => $placeholder,
                        'placeholder' => $placeholder,
                        'class' => 'form-control',
                        'id' => 'status'
                ];
                $css_state = '';
                if (!empty($user_status)) {
                    $css_state = 'has-success';
                }
                if ($errors->has('status')) {
                    $css_state = 'has-error';
                }
            ?>
            <div class="form-group {!! $css_state !!}">
                {!! Form::label('status', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                <div class="col-xs-9 col-sm-8">
                    {!! Form::text('status', $user_status, $attributes) !!}
                    {{--                    {!! Form::select('status', $status, $user_status, $attributes) !!}--}}
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
            $denomination = old('denomination', isset($dealerscaracts->denomination) ? $dealerscaracts->denomination : '');
            $label_txt = ucfirst(trans('validation.attributes.denomination'));
            $placeholder = trans('navigation.form_enter_placeholder');
            $attributes = [
                    'required'=>'required',
                    'data-placeholder' => $placeholder,
                    'placeholder' => $placeholder,
                    'class' => 'form-control',
                    'id' => 'denomination'
            ];
            $css_state = '';
            if (!empty($denomination)) {
                $css_state = 'has-success';
            }
            if ($errors->has('denomination')) {
                $css_state = 'has-error';
            }
            ?>
            <div class="form-group {!! $css_state !!}">
                {!! Form::label('denomination', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                <div class="col-xs-9 col-sm-8">
                    {!! Form::text('denomination', $denomination, $attributes) !!}
                </div>
            </div>
            <?php
            $firstname = old('firstname', isset($dealerscaracts->firstname) ? $dealerscaracts->firstname : '');
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
            <?php
            $name = old('name', isset($dealerscaracts->name) ? $dealerscaracts->name : '');
            $label_txt = ucfirst(trans('validation.attributes.name'));
            $placeholder = trans('navigation.form_enter_placeholder');
            $attributes = [
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
        <div class="col-sm-6">
            <?php
            $photo = old('photo', isset($dealerscaracts->photo) ? $dealerscaracts->photo : '');
            $denomination = old('denomination', isset($dealerscaracts->denomination) ? $dealerscaracts->denomination : '');

            $title = !empty($dealerscaracts->denomination) ? $dealerscaracts->denomination : 'dealer';
            $title = str_slug(mb_strtolower($title), '-');

            $label_txt = ucfirst(trans('validation.attributes.photo'));
            $placeholder = trans('navigation.form_enter_placeholder');
            $attributes = [
                    'data-placeholder' => $placeholder,
                    'placeholder' => $placeholder,
                    'class' => 'form-control',
                    'id' => 'photo'
            ];

            $CountryLocaleFull = Search::getCountryLocaleFull(mb_strtoupper($country_code));
            $locale = 'en-GB';
            if(!empty($CountryLocaleFull['locales'])) {
                list($locale) = explode(',', $CountryLocaleFull['locales']);
            }

            $css_state = '';
            $url_image_ext = '';
            $url_image = '';
            $url_image_thumb = '';
            if (!empty($photo)) {
                $css_state = 'has-success';
                $filename = basename($photo);

                $srcUrl = preg_replace("@^(https|http)?://[^/]+/@", "", $photo);
                $srcUrl = preg_replace("/^\//i", "", $srcUrl);

                $url_image = '';
                if(file_exists(public_path() . '/' . $srcUrl)) {
                    $url_image_ext = asset('/' . $srcUrl);
                } else {
                    $pathinfo = pathinfo($srcUrl);
                    $basename = $pathinfo['basename'];
                    //$filename = $pathinfo['filename'];
                    $filename = $title;
                    $extension = $pathinfo['extension'];

                    $image_name = isset($filename) ? $filename : 'logo';
                    $img_params = ['ad_id'=>'dealer', 'ad_title'=>$denomination, 'image_name'=> $image_name, 'sub_dir' => ''/*, 'force'=>true*/];

                    if(!empty($locale) && !empty($currentLocale)) {
                        $img_params['header_language'] = 'Accept-Language: ' . $locale .',' . $currentLocale . ';q=0.8';
                    }

                    $referrer = '';
                    $url_image_ext = '';
                    $targetDir = 'photos/dealers/' . $country_code . '/' . $title;
                    //$filename = '/assets/' . $targetDir . '/' . $basename;
                    $filename = '/assets/' . $targetDir . '/' . $filename . '.' . $extension;

                    if(preg_match("/^(http|https):\/\//i", $photo)) {
                        $url_image_ext = url_image_ext($referrer, $photo, $targetDir, $img_params);
                    }
                    if(empty($url_image_ext) && file_exists(public_path() . asset($filename))) {
                        $url_image_ext = asset($filename);
                    }
                }
                if(!empty($url_image_ext)) {
                    $url_image = thumbnail($url_image_ext, "100%", "100%", false, false);
                    $url_image_thumb = thumbnail($url_image_ext, 170, 114, false, false);
                }
            }
            if ($errors->has('name')) {
                $css_state = 'has-error';
            }
            ?>
            <div class="form-group">
                @if (!empty($url_image_thumb))
                    <div class="col-xs-3 col-sm-4">
                        {!! image($url_image_thumb, $denomination, ['class'=>'img-responsive'])!!}
                    </div>
                @endif
                <div class="col-xs-9 col-sm-8">
                    {!! Form::hidden('url_image_ext', '/' . preg_replace("@^(https|http)?://[^/]+/@", "", $url_image_ext)) !!}
                    {!! Form::file('photo') !!}
                    {!! Form::hidden('photo_w', 1024) !!}
                    {!! Form::hidden('photo_h', 768) !!}
                </div>
            </div>
            {{--<div class="form-group {!! $css_state !!}">
                {!! Form::label('photo_saved', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                <div class="col-xs-9 col-sm-8">
                    {!! Form::text('photo_saved',$photo, $attributes) !!}
                </div>
            </div>--}}
        </div>
    </section>

    <section class="row well well-sm well-white">
        <div class="col-sm-6">
            <?php
            $address = old('address', isset($dealerscaracts->address) ? $dealerscaracts->address : '');
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
            $address_more = old('address_more', isset($dealerscaracts->address_more) ? $dealerscaracts->address_more : '');
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
            $zip = old('zip', isset($dealerscaracts->zip)  ? $dealerscaracts->zip : '');
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
            $city = old('city', isset($dealerscaracts->city) ? $dealerscaracts->city : '');
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
            $province = old('province', isset($dealerscaracts->province) ? $dealerscaracts->province : '');
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
            $region = old('region', isset($dealerscaracts->region) ? $dealerscaracts->region : '');
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
            $subregion = old('subregion', isset($dealerscaracts->subregion) ? $dealerscaracts->subregion : '');
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
            $country_id = old('country_id', isset($dealerscaracts->country_id) ? $dealerscaracts->country_id : '');
            $label_txt = ucfirst(trans('validation.attributes.country'));
            $placeholder = trans('navigation.form_enter_placeholder');
            $attributes = [
                    'required'=>'required',
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
            $phone_1 = old('phone_1', isset($dealerscaracts->phone_1) ? $dealerscaracts->phone_1 : '');
            $label_txt = ucfirst(trans('validation.attributes.phone')) . ' n°1';
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
            $phone_2 = old('phone_2', isset($dealerscaracts->phone_2) ? $dealerscaracts->phone_2 : '');
            $label_txt = ucfirst(trans('validation.attributes.phone')) . ' n°2';
            $placeholder = trans('navigation.form_enter_placeholder');
            $attributes = [
                    'data-placeholder' => $placeholder,
                    'placeholder' => $placeholder,
                    'class' => 'form-control',
                    'id' => 'phone_2'
            ];
            $css_state = '';
            if (!empty($phone_1)) {
                $css_state = 'has-success';
            }
            if ($errors->has('phone_2')) {
                $css_state = 'has-error';
            }
            ?>
            <div class="form-group {!! $css_state !!}">
                {!! Form::label('phone_2', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                <div class="col-xs-9 col-sm-8">
                    {!! Form::tel('phone_2', $phone_2, $attributes) !!}
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <?php
            $phone_3 = old('phone_3', isset($dealerscaracts->phone_3) ? $dealerscaracts->phone_3 : '');
            $label_txt = ucfirst(trans('validation.attributes.phone')) . ' n°3';
            $placeholder = trans('navigation.form_enter_placeholder');
            $attributes = [
                    'data-placeholder' => $placeholder,
                    'placeholder' => $placeholder,
                    'class' => 'form-control',
                    'id' => 'phone_3'
            ];
            $css_state = '';
            if (!empty($phone_1)) {
                $css_state = 'has-success';
            }
            if ($errors->has('phone_3')) {
                $css_state = 'has-error';
            }
            ?>
            <div class="form-group {!! $css_state !!}">
                {!! Form::label('phone_3', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                <div class="col-xs-9 col-sm-8">
                    {!! Form::tel('phone_3', $phone_3, $attributes) !!}
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <?php
            $phone_mobile = old('phone_mobile', isset($dealerscaracts->phone_mobile) ? $dealerscaracts->phone_mobile : '');
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
            $emails = old('emails', isset($dealerscaracts->emails) ? $dealerscaracts->emails : Auth::user()->email);
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
                    {!! Form::text('emails', $emails, $attributes) !!}
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <?php
            $fax = old('fax', isset($dealerscaracts->fax) ? $dealerscaracts->fax : '');
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
            $twitter = old('twitter', isset($dealerscaracts->twitter) ? $dealerscaracts->twitter : '');
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
            $facebook = old('facebook', isset($dealerscaracts->facebook) ? $dealerscaracts->facebook : '');
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
    </section>

    <section class="row well well-sm well-white">
        <div class="col-sm-6">
            <?php
            $website_url = old('website_url', isset($dealerscaracts->website_url) ? $dealerscaracts->website_url : '');
            $label_txt = 'Website url';
            $placeholder = trans('navigation.form_enter_placeholder');
            $attributes = [
                    'data-placeholder' => $placeholder,
                    'placeholder' => $placeholder,
                    'class' => 'form-control',
                    'id' => 'website_url'
            ];
            $css_state = '';
            if (!empty($website_url)) {
                $css_state = 'has-success';
            }
            if ($errors->has('website_url')) {
                $css_state = 'has-error';
            }
            ?>
            <div class="form-group {!! $css_state !!}">
                {!! Form::label('website_url', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                <div class="col-xs-9 col-sm-8">
                    {!! Form::text('website_url', $website_url, $attributes) !!}
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <?php
            $rewrite_url = old('rewrite_url', isset($dealerscaracts->rewrite_url) ? $dealerscaracts->rewrite_url : $title);
            $country_code_from = ['GB'];
            $country_code_to = ['uk'];
            $dealer_url = 'https://dealers.youboat.com/' . str_replace($country_code_from, $country_code_to, $country_id) . '/' . $rewrite_url;
            $label_txt = 'Dealer url';
            $placeholder = trans('navigation.form_enter_placeholder');
            $attributes = [
                    'data-placeholder' => $placeholder,
                    'placeholder' => $placeholder,
                    'readonly' => 'readonly',
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
            <div class="form-group {!! $css_state !!}">
                {!! Form::label('rewrite_url', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                <div class="col-xs-9 col-sm-8">
                    <legend>
                        <a href="{!! $dealer_url !!}" class="blank" title="{!! $dealer_url !!}">{!! $dealer_url !!}<i class="fa fa-external-link-square fa-fw"></i></a>
                    </legend>
                    {!! Form::hidden('rewrite_url', $rewrite_url, $attributes) !!}
                </div>
            </div>
        </div>
    </section>

    <section class="row well well-sm well-white">
        <div class="col-sm-6">
            <?php
            $opening_time = old('opening_time', isset($dealerscaracts->opening_time)  ? $dealerscaracts->opening_time : '');
            $label_txt = 'Opening time';
            $placeholder = trans('navigation.form_enter_placeholder');
            $attributes = [
                    'data-placeholder' => $placeholder,
                    'placeholder' => $placeholder,
                    'class' => 'form-control',
                    'id' => 'opening_time'
            ];
            $css_state = '';
            if (!empty($opening_time)) {
                $css_state = 'has-success';
            }
            if ($errors->has('opening_time')) {
                $css_state = 'has-error';
            }
            ?>
            <div class="form-group {!! $css_state !!}">
                {!! Form::label('opening_time', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                <div class="col-xs-9 col-sm-8">
                    {!! Form::textarea('opening_time', $opening_time, $attributes) !!}
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <?php
            $legal_informations = old('legal_informations', isset($dealerscaracts->legal_informations) ? $dealerscaracts->legal_informations : '');
            $label_txt = 'Legal informations';
            $placeholder = trans('navigation.form_enter_placeholder');
            $attributes = [
                    'data-placeholder' => $placeholder,
                    'placeholder' => $placeholder,
                    'class' => 'form-control',
                    'id' => 'legal_informations'
            ];
            $css_state = '';
            if (!empty($legal_informations)) {
                $css_state = 'has-success';
            }
            if ($errors->has('legal_informations')) {
                $css_state = 'has-error';
            }
            ?>
            <div class="form-group {!! $css_state !!}">
                {!! Form::label('legal_informations', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                <div class="col-xs-9 col-sm-8">
                    {!! Form::textarea('legal_informations', $legal_informations, $attributes) !!}
                </div>
            </div>
        </div>
    </section>

    <section class="row well well-sm well-white">
        <div class="col-sm-6">
            <?php
            $duns = old('duns', isset($dealerscaracts->duns) ? $dealerscaracts->duns : '');
            $label_txt = 'Duns';
            ?>
            <div class="form-group">
                {!! Form::label('duns', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                <div class="col-xs-9 col-sm-8">
                    {!! Form::text('duns', $duns, ['class'=>'form-control']) !!}
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <?php
            $company_number = old('company_number', isset($dealerscaracts->company_number) ? $dealerscaracts->company_number : '');
            $label_txt = 'Company number';
            ?>
            <div class="form-group">
                {!! Form::label('company_number', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                <div class="col-xs-9 col-sm-8">
                    {!! Form::text('company_number', $company_number, ['class'=>'form-control']) !!}
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <?php
            $siret = old('siret', isset($dealerscaracts->siret) ? $dealerscaracts->siret : '');
            $label_txt = 'Siret';
            ?>
            <div class="form-group">
                {!! Form::label('siret', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                <div class="col-xs-9 col-sm-8">
                    {!! Form::text('siret', $siret, ['class'=>'form-control']) !!}
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <?php
            $ape = old('ape', isset($dealerscaracts->ape) ? $dealerscaracts->ape : '');
            $label_txt = 'Ape';
            ?>
            <div class="form-group">
                {!! Form::label('ape', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                <div class="col-xs-9 col-sm-8">
                    {!! Form::text('ape', $ape, ['class'=>'form-control']) !!}
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <?php
            $vat = old('vat', isset($dealerscaracts->vat) ? $dealerscaracts->vat : '');
            $label_txt = 'Vat';
            ?>
            <div class="form-group">
                {!! Form::label('vat', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                <div class="col-xs-9 col-sm-8">
                    {!! Form::text('vat', $vat, ['class'=>'form-control']) !!}
                </div>
            </div>
        </div>
    </section>

    @if($isAdmin || 'commercial' == Auth::user()->type)
        <section class="row well well-sm well-white">
            @if($isAdmin)
                <div class="col-sm-6">
                    <?php
                    $origin = old('origin', isset($dealerscaracts->origin) ? $dealerscaracts->origin : '');
                    $label_txt = 'Origin';
                    ?>
                    <div class="form-group">
                        {!! Form::label('origin', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                        <div class="col-xs-9 col-sm-8">
                            {!! Form::text('origin', $origin, ['class'=>'form-control']) !!}
                        </div>
                    </div>
                </div>
            @endif
        </section>
    @endif
    <hr>
    <div class="form-group">
        <div class="col-sm-12 text-center">
            {!! Form::button('<i class="fa fa-refresh fa-fw"></i>' . ucfirst(trans('navigation.update')), ['type' => 'submit', 'class' => 'btn btn-lg btn-success btn-exception']) !!}
            {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.dealerscaracts.index', '<i class="fa fa-ban fa-fw"></i>' . ucfirst(trans('navigation.cancel')), [], ['class' => 'btn btn-default pull-right'])) !!}
        </div>
    </div>

    {!! Form::close() !!}

@endsection