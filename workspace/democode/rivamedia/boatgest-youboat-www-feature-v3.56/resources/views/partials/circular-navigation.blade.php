<?php
    $circularLinks = [];
    $circularLinksAction = [];

    $circularLinksAction[] = ['action' => 'HomeController@index', 'fa-icon' => 'fa-home', 'title' => 'Home', 'param' => [], 'options' => []];
    if (Auth::guest()) {
        $circularLinksAction[] = ['action' => 'Auth\AuthController@getLogin', 'fa-icon' => 'fa-sign-in', 'title' => 'Login', 'param' => [], 'options' => []];
        //$circularLinks[] = ['url' => 'login', 'fa-icon' => 'fa-sign-in', 'title' => 'Login', 'param' => [], 'options' => []];
    } else {
        $circularLinks[] = ['url' => config('quickadmin.homeRoute'), 'fa-icon' => 'fa-dashboard', 'title' => 'Board', 'param' => [], 'options' => []];
    }
    //$circularLinksAction[] = ['action' => 'PagesController@contact', 'fa-icon' => 'fa-envelope-o', 'title' => 'Contact', 'param' => [], 'options' => []];
    $circularLinksAction[] = ['action' => 'ContactController@create', 'fa-icon' => 'fa-envelope-o', 'title' => 'Contact', 'param' => [], 'options' => []];
    $circularLinksAction[] = ['action' => 'PagesController@about', 'fa-icon' => 'fa-info', 'title' => 'About', 'param' => [], 'options' => []];
?>
<div class="component">
    <!-- Start Nav Structure -->
    <button class="cn-button" id="cn-button" data-default="+" data-active="-">+</button>
    <div class="cn-wrapper" id="cn-wrapper">
        <ul>
        @foreach ($circularLinks as $link)
            <li class="{{ strtolower($link['title']) }}"><a href="{{ url($link['url']) }}">{!! '<strong class="hidden-xs">' . $link['title'] . '</strong>' . '<br><span class="fa ' . $link['fa-icon'] .'"></span>' !!}</a></li>
            @endforeach
        @foreach ($circularLinksAction as $link)
            <li class="{{ strtolower($link['title']) }}">{!! htmlspecialchars_decode( link_to_action(
                $link['action'],
                '<strong class="hidden-xs">' . $link['title'] . '</strong>' . '<br><span class="fa ' . $link['fa-icon'] .'"></span>',
                $link['param'],
                $link['options']
            ))!!}</li>
        @endforeach
        </ul>
    </div>
    <div id="cn-overlay" class="cn-overlay"></div>
    <!-- End Nav Structure -->
</div>
@section('javascript')
<script src="{{  url('assets/vendor') }}/circular-navigation/circular-navigation.js"></script>
@endsection