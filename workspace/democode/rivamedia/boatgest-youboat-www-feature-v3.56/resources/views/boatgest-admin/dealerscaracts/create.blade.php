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

    {!! Form::open(array('route' => config('quickadmin.route') . '.dealerscaracts.store', 'id' => 'form-with-validation-create', 'role'=>'form', 'files'=>true, 'class' => 'form-horizontal')) !!}
    <section class="row well well-sm well-white">
        <div class="col-sm-6">
            <?php
            $user_id = !empty($user_id) ? $user_id : old('user_id', null);
            $label_txt = 'Account ' . ucfirst(trans('validation.attributes.username'));
            $attributes = [
                    'class' => 'form-control',
                    'required' => 'required',
                    'id' => 'user_id'
            ];
            $css_state = '';
            if (!empty($user_id) || !empty($username)) {
                $css_state = 'has-success';
                $attributes['disabled'] = 'disabled';
            }
            ?>
            <div class="form-group {!! $css_state !!}">
                {!! Form::label('user_id', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                <div class="col-xs-{{ (count($dealersusernames) > 1 && $isAdmin) ? 6 : 9 }} col-sm-{{ (count($dealersusernames) > 1 && $isAdmin) ? 6 : 8 }}">
                    <div class="input-group">
                        @if (!empty($username))
                            {!! Form::text('username', $username, $attributes) !!}
                            {!! Form::hidden('user_id', $user_id) !!}
                        @elseif (count($dealersusernames) < 1 && $isAdmin)
                            {!! htmlspecialchars_decode(link_to_route('users.create', '<i class="fa fa-plus fa-fw"></i>Add new', ['role'=>'4'], ['class'=>'blank btn btn-sm btn-block btn-success'])) !!}
                        @else
                            {!! Form::select('user_id', $dealersusernames, $user_id, $attributes) !!}
                        @endif
                        <span class="input-group-addon"><span class="fa fa-anchor"></span></span>
                    </div>
                </div>
                @if (count($dealersusernames) > 1 && $isAdmin)
                    <div class="col-xs-3 col-sm-2">
                        {!! htmlspecialchars_decode(link_to_route('users.create', '<i class="fa fa-plus fa-fw"></i>Add', ['role'=>'4'], ['class'=>'blank btn btn-sm btn-success'])) !!}
                    </div>
                @endif
            </div>
        </div>
        @if (!empty($useremail))
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
                        {!! Form::email('useremail', $useremail, $attributes) !!}
                        <span class="input-group-addon"><span class="fa fa-envelope-o"></span></span>
                    </div>
                    <br>
                    {!! htmlspecialchars_decode(link_to(config('quickadmin.route') . '/'. LaravelLocalization::transRoute('routes.change_password') . '/' . $useremail, '<i class="fa fa-lock fa-fw"></i>' . trans('navigation.auth.passwords.change') , ['class' => 'btn btn-danger pull-right'])) !!}
                </div>
            </div>
        </div>
        @endif

        <div class="col-sm-6">
            <?php
            /*$user_status = !empty($user_status) ? $user_status : old('user_status', null);
            $label_txt = ucfirst('status');
            $placeholder = trans('navigation.form_enter_placeholder');
            $attributes = [
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
            }*/
            ?>
            {{--<div class="form-group {!! $css_state !!}">
                {!! Form::label('status', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                <div class="col-xs-9 col-sm-8">
                    {!! Form::select('status', $status, $user_status, $attributes) !!}
                </div>
            </div>--}}
        </div>
    </section>

    <section class="row well well-sm well-white">
        <div class="col-sm-6">
            <?php
            $denomination = old('denomination');
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
            <?php
            $name = old('name');
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
            $photo = old('photo');
            $denomination = old('denomination');

            $title = !empty($denomination) ? $denomination : 'dealer';
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
                    if(preg_match("/^(http|https):\/\//i", $photo)) {
                        $targetDir = 'photos/dealers/' . $country_code . '/' . $title;
                        $url_image_ext = url_image_ext($referrer, $photo, $targetDir, $img_params);
                    }
                    if(empty($url_image_ext) && file_exists(public_path() . asset('/assets/photos/dealers/' . $country_code . '/' . $title . '/' . $basename))) {
                        $url_image_ext = asset('/assets/' . 'photos/dealers/' . $country_code . '/' . $title . '/' . $basename);
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
            $phone_1 = old('phone_1');
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
            $phone_2 = old('phone_2');
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
            $phone_3 = old('phone_3');
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
            $emails = old('emails', !empty($emails) ? $emails : null);
            $emails = empty($emails) & !empty($useremail) ? $useremail .';' : null;
            $label_txt = ucfirst(trans('validation.attributes.email')) . 's';
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
    </section>

    <section class="row well well-sm well-white">
        <div class="col-sm-6">
            <?php
            $website_url = old('website_url');
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
            /*$rewrite_url = old('rewrite_url', !empty($username) ? str_slug($username) : '');
            $label_txt = 'Dealer url';
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
            }*/
            ?>
            {{--<div class="form-group {!! $css_state !!}">
                {!! Form::label('rewrite_url', $label_txt, ['class'=>'col-xs-3 col-sm-4 control-label']) !!}
                <div class="col-xs-9 col-sm-8">
                    {!! Form::text('rewrite_url', 'https://dealers.youboat.com/' . $rewrite_url, $attributes) !!}
                </div>
            </div>--}}
        </div>
    </section>

    <section class="row well well-sm well-white">
        <div class="col-sm-6">
            <?php
            $opening_time = old('opening_time');
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
            $legal_informations = old('legal_informations');
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
            $duns = old('duns');
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
            $company_number = old('company_number');
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
            $siret = old('siret');
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
            $ape = old('ape');
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
            $vat = old('vat');
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
                    $origin = old('origin');
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
            {!! Form::button('<i class="fa fa-cog fa-fw"></i>' . ucfirst(trans('navigation.create')), ['type' => 'submit', 'class' => 'btn btn-lg btn-primary btn-exception']) !!}
            {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.dealerscaracts.index', '<i class="fa fa-mail-reply fa-fw"></i>' . ucfirst(trans('navigation.back')), [], array('class' => 'btn btn-default pull-right'))) !!}
        </div>
    </div>

    {!! Form::close() !!}

@endsection