<div class="site-footer-top">
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-0 ">
                @if (!preg_match('/errors/', $view_name))
                @include('theme.partials.elements.newsletter-form', ['newsletter_message', !empty($newsletter_message) ? $newsletter_message : ''])
                @endif
            </div>
            <div class="col-sm-6 col-md-2 footer_widget widget widget_custom_menu widget_links">
                <h4 class="widgettitle">{!! trans('footer.site-footer-top.services') !!}</h4>
                <ul>
                    <li>{!! link_trans_route('for_sale', 'footer.site-footer-top.buy_a_boat', []) !!}</li>
                    <li>{!! link_trans_route('sell', 'footer.site-footer-top.sell_a_boat', []) !!}</li>
                    <li>{!! link_trans_route('boat_on_demand', 'navigation.boat_on_demand', []) !!}</li>
                    @if(Auth::check())
                    <li>{!! link_trans_route('dashboard', 'navigation.customer_area', []) !!}</li>
                    @else
                    <li>{!! link_trans_route('login', 'navigation.customer_area', []) !!}</li>
                    @endif
                    <li>{!! link_trans_route('newsletter', 'navigation.newsletter', []) !!}</li>
                </ul>
            </div>
            <div class="col-sm-6 col-md-2 text-center">
                <img src="{!! asset('assets/img/ssl-certificatelogo_128.png') !!}" alt="{!! trans('footer.site-footer-top.ssl') !!}"><br>
            </div>
            {{--<div class="col-md-2 col-sm-6 footer_widget widget widget_custom_menu widget_links">
                <h4 class="widgettitle">{!! trans('footer.site-footer-top.news') !!}</h4>
                <ul>
                    <li>{!! link_trans_route('news', 'footer.site-footer-top.boats_news', []) !!}</li>
                    <li>{!! link_trans_route('reviews', 'footer.site-footer-top.boats_reviews', []) !!}</li>
                </ul>
            </div>--}}
            <div class="col-sm-12 col-md-4 footer_widget widget text_widget pull-right">
                <h4 class="widgettitle">{!! trans('navigation.about') !!} {!! $website_name !!}</h4>
                <p>{!! trans('landing.landing_text_03') !!}</p>
                {{--<p>{!! trans('landing.landing_text_04') !!}</p>--}}
                <p class="text-right">
                    {!! link_trans_route('about', 'navigation.about_us', ['class'=>'btn btn-default btn-lg', ]) !!}
                </p>
            </div>
            <div class="col-sm-12 text-center">
                <img src="{!! asset('assets/img/Powered_By_Stripe_small.png') !!}" alt="{!! trans('footer.site-footer-top.powered_by_stripe') !!}">
            </div>
        </div>
    </div>
</div>