@extends('layouts.' . (preg_match('/landing/', $currentRoute) ? 'landing' : 'theme'))
<?php
    $width = 337;
    $height = 228;

    $metas_title = $title;
    $metas_description = $intro;
    $metas_og = [
        'og_url' => [
                'property' => 'og:url',
                'content' => URL::full()
        ],
        'og_type' => [
                'property' => 'og:type',
                'content' => 'website'
        ],
        'og_title' => [
            'property' => 'og:title',
            'content' => $metas_title
        ],
        'og_description' => [
                'property' => 'og:description',
                'content' => $metas_description
        ],
        'og_image' => [
                'property' => 'og:image',
                'content' => url(asset($photo))
        ],
        'og_height' => [
                'property' => 'og:image:height',
                'content' => $height
        ],
        'og_width' => [
                'property' => 'og:image:width',
                'content' => $width
        ]
    ];
    $metas = [
        'metas_title' => $metas_title . ' - ' . $website_name,
        'metas_description' => $metas_description,
        'metas_og' => $metas_og,
        //,'metas_keywords' => $metas_keywords
    ];
    $photo = thumbnail(asset($photo), $width, $height, false, false, true, 100);
?>

@section('metas')
    @include('theme.partials.elements.block.metas-block', $metas)
@endsection

@section('content')
    <div class="row">

        <div class="col-sm-12">
            <div class="well row">
                <div class="well well-white clearfix">
                    <div class="col-xs-12 col-sm-7">
                        <h2 class="title accent-color">{!! $title !!}</h2>
                        <p class="lead">{!! nl2br($intro) !!}</p>
                    </div>
                    <div class="col-xs-12 col-sm-5 text-center">
                        @if(!empty($url))
                            <a href="{!! $url !!}" title="{!! $title !!}" data-ga="{!! $view_name . '~' . 'photo' . '|' . 'Ref. ' . $rewrite_url !!}" class="GA_event blank">
                                {!! image($photo, $title, ['class'=>'img-thumbnail img-illus pull-right']) !!}
                            </a>
                        @else
                            {!! image($photo, $title, ['class'=>'img-thumbnail img-illus']) !!}
                        @endif
                    </div>
                </div>
                <div class="well well-white clearfix">
                    <div class="col-xs-12">
                        <div class="accent-color-danger lead big">{!! $date !!}</div>
                        <blockquote>
                            {!! nl2br($description) !!}
                        </blockquote>
                    </div>
                    <div class="col-sm-4 text-center">
                        @include('theme.partials.elements.block.fb-like-block', [
                            'url'=>URL::full(),
                            'action' => 'recommend',
                            'size' => 'large',
                            'layout' => 'button_count',
                            'show_faces' => 'true',
                            'share' => 'true'
                        ])
                    </div>
                    <div class="col-sm-4 text-center">
                        {{--@include('theme.partials.elements.block.fb-save-block', ['url'=>URL::full()])--}}
                    </div>
                    <div class="col-sm-4 pull-right text-right">
                        @if(!empty($url))
                        <a href="{!! $url !!}" title="{!! trans('navigation.read_more') !!}" data-ga="{!! $view_name . '~' . trans('navigation.read_more') . '|' . 'Ref. ' . $rewrite_url !!}" class="GA_event btn btn-success btn-md pull-right blank">
                            {!! trans('navigation.read_more') !!}
                            <i class="fa fa-info-circle fa-fw"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="col-sm-8">
            @include('theme.partials.elements.latest-news', ['data_columns'=>2, 'items'=> !empty($latest_news) ? $latest_news : []])
        </div>
        <div class="col-sm-4">
            <div class="col-sm-12 well well-white">
                <h3>{!! trans('contact.title_facebook') !!}</h3>
                <a href="{!! config('youboat.' . $country_code . '.facebook') !!}" data-ga="{!! $view_name . '~' . 'facebook' . '|' . 'Ref. ' . URL::full() !!}" class="GA_event blank">
                    <span class="fa-stack fa-lg">
                      <i class="fa fa-square-o fa-stack-2x"></i>
                      <i class="fa fa-facebook fa-stack-1x"></i>
                    </span>
                    {!! rtrim(preg_replace('/(https|http):\/\//', '', config('youboat.' . $country_code . '.facebook')), '\/') !!}
                </a>
                @include('theme.partials.elements.block.fb-page-block', ['url'=>config('youboat.'. $country_code .'.facebook')])
            </div>
            <div class="col-sm-12 well well-white">
                <h3>{!! trans('contact.title_tweet') !!}</h3>
                <a href="{!! config('youboat.' . $country_code . '.twitter') !!}" data-ga="{!! $view_name . '~' . 'twitter' . '|' . 'Ref. ' . URL::full() !!}" class="GA_event blank">
                    <span class="fa-stack fa-lg">
                      <i class="fa fa-square-o fa-stack-2x"></i>
                      <i class="fa fa-twitter fa-stack-1x"></i>
                    </span>
                    {!! rtrim(preg_replace('/(https|http):\/\//', '', config('youboat.' . $country_code . '.twitter')), '\/') !!}
                </a>
            </div>
        </div>
        <div class="col-sm-12">
            <a href="{{ url(trans_route($currentLocale, '/')) }}" title="{!! trans('navigation.back_to_home') !!}" class="btn btn-default pull-right">
                <i class="fa fa-mail-reply fa-fw"></i>
                {!! trans('navigation.back') !!}
            </a>
        </div>
    </div>
@endsection
