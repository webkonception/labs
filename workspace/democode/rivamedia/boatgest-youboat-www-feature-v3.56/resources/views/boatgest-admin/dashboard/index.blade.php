@extends(config('quickadmin.route') . '.layouts.master')

@section('content')

    @if ($isAdmin)
        @include(config('quickadmin.route') . '.partials.inbox')
    @endif
    <hr>
    <section class="row well clearfix">
        <div class="col-sm-8 well well-white clearfix">
            <div class="col-xs-12">
                <div class="row lead strong">
                    <?php
                    $username = Auth::user()->username;
                    $label_txt = ucfirst(trans('validation.attributes.username'));
                    ?>
                    <div class="text-primary col-lg-3 col-md-4 col-sm-4 col-xs-12">{!! $label_txt !!}&nbsp;:&nbsp;</div>
                    <div class="col-lg-9 col-md-8 col-sm-8 col-xs-12">{!! $username !!}</div>
                </div>
            </div>

            <div class="col-xs-12">
                <div class="row">
                    <?php
                    $emails = !empty($user_caracts['emails']) ? $user_caracts['emails'] : (!empty($user->email) ? $user->email : '');
                    $label_txt = ucfirst(trans('validation.attributes.email')) . 's';
                    ?>
                    <div class="text-primary lead strong col-lg-3 col-md-4 col-sm-4 col-xs-12">{!! $label_txt !!}&nbsp;:&nbsp;</div>
                    <div class="strong col-lg-9 col-md-8 col-sm-8 col-xs-12">{!! $emails !!}</div>
                </div>
            </div>
        </div>
        @if(!empty($user_caracts))
        <div class="col-sm-4 lead">
            {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.' . $user->type . 'scaracts.edit', '<i class="fa fa-pencil fa-fw"></i>Edit my account', [$user_caracts['id']], ['class' => 'btn btn-block btn-lg btn-primary'])) !!}
        </div>
        <br>
        @elseif('admin' != $user->type)
        <div class="col-sm-4 lead">
            {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.' . $user->type . 'scaracts.create', '<i class="fa fa-plus-circle fa-fw"></i>Complete my account', ['user_id'=>Auth::user()->id], ['class' => 'btn btn-block btn-lg btn-success'])) !!}
        </div>
        <br>
        @endif
    </section>
    @if(!empty($user_caracts))
    <div class="well">
        <div class="row">
            <div class="col-sm-6 col-sm-offset-6">
            </div>
        </div>
        <section class="well well-white"><div class="row">
            <div class="col-sm-6">
                <?php
                $denomination = !empty($user_caracts['denomination']) ? $user_caracts['denomination'] : '';
                $label_txt = ucfirst(trans('validation.attributes.denomination'));
                ?>
                @if(!empty($denomination))
                <div class="row">
                    <strong class="text-primary col-xs-3 col-sm-4">{!! $label_txt !!}</strong>
                    <div class="col-xs-9 col-sm-8">&nbsp;:&nbsp;
                        {!! $denomination !!}
                    </div>
                </div>
                @endif
                <?php
                $firstname = !empty($user_caracts['firstname']) ? ucfirst(mb_strtolower($user_caracts['firstname'])) : '';
                $name = !empty($user_caracts['name']) ? (!empty($firstname) ? $firstname . ' ' . mb_strtoupper($user_caracts['name']) : mb_strtoupper($user_caracts['name'])) : '';
                $label_txt = ucfirst(trans('validation.attributes.name'));
                ?>
                @if(!empty($name))
                <div class="row">
                    <strong class="text-primary col-xs-3 col-sm-4">{!! $label_txt !!}</strong>
                    <div class="col-xs-9 col-sm-8">&nbsp;:&nbsp;
                        {!! $name !!}
                    </div>
                </div>
                @endif
            </div>
            <div class="col-sm-6">
                <?php
                $url_image_ext = '';

                $photo = !empty($user_caracts['photo']) ? $user_caracts['photo'] : '';
                $rewrite_url = !empty($user_caracts['rewrite_url']) ? $user_caracts['rewrite_url'] : '';
                $label_txt = ucfirst(trans('validation.attributes.photo'));

                ///
                $image_name = 'logo';
                    /*

                $filename = $params['image_name'] . '.' . $extension;
                $params['filename'] = $filename;
                $subDir = $params['ad_id'] . '_' . str_slug($params['ad_title']);
*/
                $targetDir = 'photos/dealers/' . $country_code . '/';
  /*              $assetsPath = '/assets/' . $targetDir . $subDir;
                $pathname = $_SERVER['DOCUMENT_ROOT'] . $assetsPath ;
                $filename_dest = $pathname . '/' . $filename;
                if (file_exists($filename_dest)) {
                    $url_image_ext = $filename_dest;
                }*/
                ///

                if (!empty($photo) && preg_match("/^http/", $photo)) {
                    $css_state = 'has-success';
                    $ad_img_params = ['ad_id'=>'dealer', 'ad_title'=>$rewrite_url, 'image_name'=>$image_name];
                    $url_image_ext = url_image_ext('', $photo, $targetDir, $ad_img_params);
                } else {
                    $url_image_ext = $photo;
                }
                ?>
                @if (!empty($url_image_ext))
                <div class="row">
                    <div class="col-xs-12">
                        {!! image(thumbnail($url_image_ext, 120, null, false, false), $denomination, ['class'=>'img-responsive'])!!}
                    </div>
                </div>
                @endif
            </div>
        </div></section>

        <section class="well well-white"><div class="row">
            <div class="col-sm-6">
                <?php
                $address = !empty($user_caracts['address']) ? $user_caracts['address'] : '';
                $label_txt = ucfirst(trans('validation.attributes.address'));
                ?>
                @if(!empty($address))
                <div class="row">
                    <strong class="text-primary col-xs-3 col-sm-4">{!! $label_txt !!}</strong>
                    <div class="col-xs-9 col-sm-8">&nbsp;:&nbsp;
                        {!! $address !!}
                    </div>
                </div>
                @endif
            </div>
            <div class="col-sm-6">
                <?php
                $address_more = !empty($user_caracts['address_more']) ? $user_caracts['address_more'] : '';
                $label_txt = ucfirst(trans('validation.attributes.address_more'));
                ?>
                @if(!empty($address_more))
                <div class="row">
                    <strong class="text-primary col-xs-3 col-sm-4">{!! $label_txt !!}</strong>
                    <div class="col-xs-9 col-sm-8">&nbsp;:&nbsp;
                        {!! $address_more !!}
                    </div>
                </div>
                @endif
            </div>
            <div class="col-sm-6">
                <?php
                $zip = !empty($user_caracts['zip']) ? $user_caracts['zip'] : '';
                $label_txt = ucfirst(trans('validation.attributes.zip'));
                ?>
                @if(!empty($zip))
                <div class="row">
                    <strong class="text-primary col-xs-3 col-sm-4">{!! $label_txt !!}</strong>
                    <div class="col-xs-9 col-sm-8">&nbsp;:&nbsp;
                        {!! $zip !!}
                    </div>
                </div>
                @endif
            </div>
            <div class="col-sm-6">
                <?php
                $city = !empty($user_caracts['city']) ? $user_caracts['city'] : '';
                $label_txt = ucfirst(trans('validation.attributes.city'));
                ?>
                @if(!empty($city))
                <div class="row">
                    <strong class="text-primary col-xs-3 col-sm-4">{!! $label_txt !!}</strong>
                    <div class="col-xs-9 col-sm-8">&nbsp;:&nbsp;
                        {!! $city !!}
                    </div>
                </div>
                @endif
            </div>
            <div class="col-sm-6">
                <?php
                $province = !empty($user_caracts['province']) ? $user_caracts['province'] : '';
                $label_txt = ucfirst(trans('validation.attributes.province'));
                ?>
                @if(!empty($province))
                <div class="row">
                    <strong class="text-primary col-xs-3 col-sm-4">{!! $label_txt !!}</strong>
                    <div class="col-xs-9 col-sm-8">&nbsp;:&nbsp;
                        {!! $province !!}
                    </div>
                </div>
                @endif
            </div>
            <div class="col-sm-6">
                <?php
                $region = !empty($user_caracts['region']) ? $user_caracts['region'] : '';
                $label_txt = ucfirst(trans('validation.attributes.district'));
                ?>
                @if(!empty($region))
                <div class="row">
                    <strong class="text-primary col-xs-3 col-sm-4">{!! $label_txt !!}</strong>
                    <div class="col-xs-9 col-sm-8">&nbsp;:&nbsp;
                        {!! $region !!}
                    </div>
                </div>
                @endif
            </div>
            <div class="col-sm-6">
                <?php
                $subregion = !empty($user_caracts['subregion']) ? $user_caracts['subregion'] : '';
                $label_txt = ucfirst(trans('validation.attributes.county'));
                ?>
                @if(!empty($subregion))
                <div class="row">
                    <strong class="text-primary col-xs-3 col-sm-4">{!! $label_txt !!}</strong>
                    <div class="col-xs-9 col-sm-8">&nbsp;:&nbsp;
                        {!! $subregion !!}
                    </div>
                </div>
                @endif
            </div>
            <div class="col-sm-6">
                <?php
                $country_name = !empty($user_caracts['country_id']) ? $countries[$user_caracts['country_id']] : '';
                $label_txt = ucfirst(trans('validation.attributes.country'));
                ?>
                @if(!empty($country_name))
                <div class="row">
                    <strong class="text-primary col-xs-3 col-sm-4">{!! $label_txt !!}</strong>
                    <div class="col-xs-9 col-sm-8">&nbsp;:&nbsp;
                        {!! $country_name !!}
                    </div>
                </div>
                @endif
            </div>
        </div></section>

        <section class="well well-white"><div class="row">
            <div class="col-sm-6">
                <?php
                $phone_1 = !empty($user_caracts['phone_1']) ? $user_caracts['phone_1'] : '';
                $label_txt = ucfirst(trans('validation.attributes.phone'));
                ?>
                @if(!empty($phone_1))
                <div class="row">
                    <strong class="text-primary col-xs-3 col-sm-4">{!! $label_txt !!}</strong>
                    <div class="col-xs-9 col-sm-8">&nbsp;:&nbsp;
                        {!! $phone_1 !!}
                    </div>
                </div>
                @endif
            </div>
            <div class="col-sm-6">
                <?php
                $phone_2 = !empty($user_caracts['phone_2']) ? $user_caracts['phone_2'] : '';
                $label_txt = ucfirst(trans('validation.attributes.phone'));
                ?>
                @if(!empty($phone_2))
                <div class="row">
                    <strong class="text-primary col-xs-3 col-sm-4">{!! $label_txt !!}</strong>
                    <div class="col-xs-9 col-sm-8">&nbsp;:&nbsp;
                        {!! $phone_2 !!}
                    </div>
                </div>
                @endif
            </div>
            <div class="col-sm-6">
                <?php
                $phone_3 = !empty($user_caracts['phone_3']) ? $user_caracts['phone_3'] : '';
                $label_txt = ucfirst(trans('validation.attributes.phone'));
                ?>
                @if(!empty($phone_3))
                <div class="row">
                    <strong class="text-primary col-xs-3 col-sm-4">{!! $label_txt !!}</strong>
                    <div class="col-xs-9 col-sm-8">&nbsp;:&nbsp;
                        {!! $phone_3 !!}
                    </div>
                </div>
                @endif
            </div>

            <div class="col-sm-6">
                <?php
                $phone_mobile = !empty($user_caracts['phone_mobile']) ? $user_caracts['phone_mobile'] : '';
                $label_txt = ucfirst(trans('validation.attributes.mobile'));
                ?>
                @if(!empty($phone_mobile))
                <div class="row">
                    <strong class="text-primary col-xs-3 col-sm-4">{!! $label_txt !!}</strong>
                    <div class="col-xs-9 col-sm-8">&nbsp;:&nbsp;
                        {!! $phone_mobile !!}
                    </div>
                </div>
                @endif
            </div>
            <div class="col-sm-6">
                <?php
                $fax = !empty($user_caracts['fax']) ? $user_caracts['fax'] : '';
                $label_txt = ucfirst(trans('validation.attributes.fax'));
                ?>
                @if(!empty($fax))
                <div class="row">
                    <strong class="text-primary col-xs-3 col-sm-4">{!! $label_txt !!}</strong>
                    <div class="col-xs-9 col-sm-8">&nbsp;:&nbsp;
                        {!! $fax !!}
                    </div>
                </div>
                @endif
            </div>
        </div></section>

        <?php
        $twitter = !empty($user_caracts['twitter']) ? $user_caracts['twitter'] : '';
        $facebook = !empty($user_caracts['facebook']) ? $user_caracts['facebook'] : '';
        ?>
        @if(!empty($twitter) || !empty($facebook))
        <section class="well well-white"><div class="row">
            <div class="col-sm-6">
                <?php
                $label_txt = 'Twitter';
                ?>
                @if(!empty($twitter))
                <div class="row">
                    <strong class="text-primary col-xs-3 col-sm-4">{!! $label_txt !!}</strong>
                    <div class="col-xs-9 col-sm-8">&nbsp;:&nbsp;
                        {!! $twitter !!}
                    </div>
                </div>
                @endif
            </div>
            <div class="col-sm-6">
                <?php
                $label_txt = 'Facebook';
                ?>
                @if(!empty($facebook))
                <div class="row">
                    <strong class="text-primary col-xs-3 col-sm-4">{!! $label_txt !!}</strong>
                    <div class="col-xs-9 col-sm-8">&nbsp;:&nbsp;
                        {!! $facebook !!}
                    </div>
                </div>
                @endif
            </div>
        </div></section>
        @endif

        <?php
        $website_url = !empty($user_caracts['website_url']) ? $user_caracts['website_url'] : '';
        $rewrite_url = !empty($user_caracts['rewrite_url']) ? $user_caracts['rewrite_url'] : '';
        ?>
        @if(!empty($website_url) || !empty($rewrite_url))
        <section class="well well-white"><div class="row">
            <div class="col-sm-12">
                <?php
                $label_txt = 'Website url';
                ?>
                @if(!empty($website_url))
                <div class="row">
                    <strong class="text-primary col-xs-3 col-sm-4">{!! $label_txt !!}</strong>
                    <div class="col-xs-9 col-sm-8">&nbsp;:&nbsp;
                        <a href="{!! $website_url !!}" class="blank">{!! $website_url !!}<i class="fa fa-fw"></i></a>
                    </div>
                </div>
                @endif
            </div>

            <div class="col-sm-12">
                <?php
                $label_txt = 'Dealer url';
                ?>
                @if(!empty($rewrite_url))
                <div class="row">
                    <strong class="text-primary col-xs-3 col-sm-4">{!! $label_txt !!}</strong>
                    <div class="col-xs-9 col-sm-8">&nbsp;:&nbsp;
                        <a href="{!! $rewrite_url !!}" class="blank">{!! $rewrite_url !!}</a>
                    </div>
                </div>
                @endif
            </div>
        </div></section>
        @endif

        <?php
        $opening_time = !empty($user_caracts['opening_time']) ? $user_caracts['opening_time'] : '';
        $legal_informations = !empty($user_caracts['legal_informations']) ? $user_caracts['legal_informations'] : '';
        ?>
        @if(!empty($opening_time) || !empty($legal_informations))
        <section class="well well-white"><div class="row">
            <div class="col-sm-6">
                <?php
                $label_txt = 'Opening time';
                ?>
                @if(!empty($opening_time))
                <div class="row">
                    <strong class="text-primary col-xs-3 col-sm-4">{!! $label_txt !!}</strong>
                    <div class="col-xs-9 col-sm-8">&nbsp;:&nbsp;
                        <blockquote>{!! nl2br($opening_time, false) !!}</blockquote>
                    </div>
                </div>
                @endif
            </div>

            <div class="col-sm-6">
                <?php
                $label_txt = 'Legal informations';
                ?>
                @if(!empty($opening_time))
                <div class="row">
                    <strong class="text-primary col-xs-3 col-sm-4">{!! $label_txt !!}</strong>
                    <div class="col-xs-9 col-sm-8">&nbsp;:&nbsp;
                        <blockquote>{!! $legal_informations !!}</blockquote>
                    </div>
                </div>
                @endif
            </div>
        </div></section>
        @endif

        <?php
        $duns = !empty($user_caracts['duns']) ? $user_caracts['duns'] : '';
        $company_number = !empty($user_caracts['company_number']) ? $user_caracts['company_number'] : '';
        $siret = !empty($user_caracts['siret']) ? $user_caracts['siret'] : '';
        $ape = !empty($user_caracts['ape']) ? $user_caracts['ape'] : '';
        $vat = !empty($user_caracts['vat']) ? $user_caracts['vat'] : '';
        ?>
        @if(!empty($duns) || !empty($company_number) || !empty($siret) || !empty($ape) || !empty($vat))
        <section class="well well-white"><div class="row">
            <div class="col-sm-6">
                <?php
                $duns = !empty($user_caracts['duns']) ? $user_caracts['duns'] : '';
                $label_txt = 'Duns';
                ?>
                @if(!empty($duns))
                <div class="row">
                    <strong class="text-primary col-xs-3 col-sm-4">{!! $label_txt !!}</strong>
                    <div class="col-xs-9 col-sm-8">&nbsp;:&nbsp;
                        {!! $duns !!}
                    </div>
                </div>
                @endif
            </div>

            <div class="col-sm-6">
                <?php
                $label_txt = 'Company number';
                ?>
                @if(!empty($company_number))
                <div class="row">
                    <strong class="text-primary col-xs-3 col-sm-4">{!! $label_txt !!}</strong>
                    <div class="col-xs-9 col-sm-8">&nbsp;:&nbsp;
                        {!! $company_number !!}
                    </div>
                </div>
                @endif
            </div>

            <div class="col-sm-6">
                <?php
                $label_txt = 'Siret';
                ?>
                @if(!empty($siret))
                <div class="row">
                    <strong class="text-primary col-xs-3 col-sm-4">{!! $label_txt !!}</strong>
                    <div class="col-xs-9 col-sm-8">&nbsp;:&nbsp;
                        {!! $siret !!}
                    </div>
                </div>
                @endif
            </div>

            <div class="col-sm-6">
                <?php
                $label_txt = 'Ape';
                ?>
                @if(!empty($ape))
                <div class="row">
                    <strong class="text-primary col-xs-3 col-sm-4">{!! $label_txt !!}</strong>
                    <div class="col-xs-9 col-sm-8">&nbsp;:&nbsp;
                        {!! $ape !!}
                    </div>
                </div>
                @endif
            </div>

            <div class="col-sm-6">
                <?php
                $label_txt = 'Vat';
                ?>
                @if(!empty($vat))
                <div class="row">
                    <strong class="text-primary col-xs-3 col-sm-4">{!! $label_txt !!}</strong>
                    <div class="col-xs-9 col-sm-8">&nbsp;:&nbsp;
                        {!! $vat !!}
                    </div>
                </div>
                @endif
            </div>
        </div></section>
        @endif

        <?php
        $origin = !empty($user_caracts['origin']) ? $user_caracts['origin'] : '';
        $user_status = !empty($user_caracts['status']) ? $user_caracts['status'] : '';
        ?>
        @if((!empty($origin) || !empty($user_status)) && $isAdmin)
        <section class="well well-white"><div class="row">
            <div class="col-sm-6">
                <?php
                $label_txt = 'Origin';
                ?>
                @if(!empty($origin))
                <div class="row">
                    <strong class="text-primary col-xs-3 col-sm-4">{!! $label_txt !!}</strong>
                    <div class="col-xs-9 col-sm-8">&nbsp;:&nbsp;
                        {!! $origin !!}
                    </div>
                </div>
                @endif
            </div>
            <div class="col-sm-6">
                <?php
                $label_txt = ucfirst('status');
                ?>
                @if(!empty($user_status))
                <div class="row">
                    <strong class="text-primary col-xs-3 col-sm-4">{!! $label_txt !!}</strong>
                    <div class="col-xs-9 col-sm-8">&nbsp;:&nbsp;
                        {!! $user_status !!}
                    </div>
                </div>
                @endif
            </div>
        </div></section>
        @endif
        <section class="well well-white"><div class="row">
            <div class="col-sm-4 lead">
                {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.adscaracts.index', '<i class="fa fa-newspaper-o fa-fw"></i>' . trans('dashboard.manage_your_ads'), [], ['class' => 'btn btn-block btn-lg btn-success'])) !!}
            </div>
            <div class="col-sm-4 lead">
                {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.bodcaracts.index', '<i class="fa fa-search-plus fa-fw"></i>' . trans('navigation.boat_on_demand'), [], ['class' => 'btn btn-block btn-lg btn-danger'])) !!}
            </div>
            <div class="col-sm-4 lead">
                <a href="{!! config('youboat.'. $country_code .'.stripe.plan_pay_url') !!}" class="blank btn btn-block btn-lg btn-primary"><i class="fa fa-tags fa-fw"></i>{!! trans('dashboard.subscribe_to_our_offer') !!}</a>
            </div>
        </div></section>
    </div>
    @elseif('admin' == $user->type || 'commercial' == $user->type )
    <section class="well well-white">
        <div class="row">
            <div class="col-sm-4 lead">
                {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.adscaracts.index', '<i class="fa fa-newspaper-o fa-fw"></i>' . trans('dashboard.manage_your_ads'), [], ['class' => 'btn btn-block btn-lg btn-success'])) !!}
            </div>
            <div class="col-sm-4 lead">
                {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.bodcaracts.index', '<i class="fa fa-search-plus fa-fw"></i>' . trans('navigation.boat_on_demand'), [], ['class' => 'btn btn-block btn-lg btn-danger'])) !!}
            </div>
            <div class="col-sm-4 lead">
                {!! htmlspecialchars_decode(link_to_route(config('quickadmin.route') . '.statistics.index', '<i class="fa fa-line-chart fa-fw"></i>' . trans('navigation.statistics'), [], ['class' => 'btn btn-block btn-lg btn-primary'])) !!}
            </div>
        </div>
    </section>
    @endif
@endsection
@section('javascript')
    <script>
        var canvasMinHeight = '400px';
        $(function() {
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                $($(e.target).attr('href')).find('canvas').height(canvasMinHeight);
            });
            $NavTabs = $('.nav-tabs');
            $NavTabs.each(function() {
                var $This = $(this);
                $('li:eq(1) a', $This).tab('show');
            });
        });
    </script>
@endsection