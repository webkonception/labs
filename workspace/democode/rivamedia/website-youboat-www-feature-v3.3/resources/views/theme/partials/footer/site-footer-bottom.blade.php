<div class="site-footer-bottom">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-sm-6 copyrights-left">
                <p>{!! trans('footer.copyright', ['website_name' => $website_name]) !!}</p>
            </div>
            <div class="col-md-8 col-sm-6 copyrights-right">
                <ul class="social-icons social-icons-colored pull-right">
                    <li class="facebook"><a href="{!! config('youboat.' . $country_code . '.facebook') !!}" class="blank"><i class="fa fa-facebook"></i></a></li>
                    <li class="twitter"><a href="{!! config('youboat.' . $country_code . '.twitter') !!}" class="blank"><i class="fa fa-twitter"></i></a></li>
                    {{--<li class="linkedin"><a href="#"><i class="fa fa-linkedin"></i></a></li>
                    <li class="youtube"><a href="#"><i class="fa fa-youtube"></i></a></li>
                    <li class="flickr"><a href="#"><i class="fa fa-flickr"></i></a></li>
                    <li class="vimeo"><a href="#"><i class="fa fa-vimeo-square"></i></a></li>
                    <li class="digg"><a href="#"><i class="fa fa-digg"></i></a></li>--}}
                </ul>
                <div class="footer_widget widget widget_custom_menu widget_links">
                    <ul>
                        <li class="pull-left">{!! link_trans_route('cgv', 'navigation.cgv', []) !!}&nbsp;&nbsp;</li>
                        {{--<li class="pull-left">{!! link_trans_route('legal', 'navigation.legal', []) !!}&nbsp;&nbsp;</li>--}}
                        {{--<li class="pull-left">{!! link_trans_route('privacy', 'navigation.privacy', []) !!}&nbsp;&nbsp;</li>--}}
                        <li class="pull-left">{!! link_trans_route('about', 'navigation.about_us', []) !!}&nbsp;&nbsp;</li>
                        <li class="pull-left">{!! link_trans_route('contact', 'navigation.contact_us', []) !!}&nbsp;&nbsp;</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!--Start of Tawk.to Script-->
    <script type="text/javascript">
        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
        (function(){
            var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
            s1.async=true;
            s1.src='https://embed.tawk.to/588b8288c9a1bb25a1fff0df/default';
            s1.charset='UTF-8';
            s1.setAttribute('crossorigin','*');
            s0.parentNode.insertBefore(s1,s0);
        })();
    </script>
    <!--End of Tawk.to Script-->
</div>