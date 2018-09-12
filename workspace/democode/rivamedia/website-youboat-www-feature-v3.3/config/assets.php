<?php
$protocole = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) ||  (isset($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] == 'https')) ? 'https' : 'https';
//$protocole = 'https';

return [

    //CSS
    'css' => [

        'styles' => [
            'local' => [
                'default' => ['url' => 'assets/css/styles.css'],
            ],
        ],

        'fontawesome' => [
            'local' => [
                '4_6_3' => ['url' => 'assets/vendor/font-awesome/4.6.3/css/font-awesome.min.css'],
            ],
            'external' => [
                '4_6_3' => ['url' => $protocole . ':' . '//maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css', 'integrity' => 'sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+', 'crossorigin' => 'anonymous'],
            ],

            /*'external' => [
                '4_6_3' => ['url' => $protocole . ':' . '//use.fontawesome.com/1a8d2c66a6.css'],
            ],*/
        ],

        'bootstrap' => [
            'local' => [
                '3_3_1' => ['url' => 'assets/vendor/bootstrap/3.3.1/css/bootstrap.min.css'],
                '3_3_6' => ['url' => 'assets/vendor/bootstrap/3.3.6/css/bootstrap.min.css'],
            ],
            'external' => [
                '3_3_1' => ['url' => $protocole . ':' . '//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css', 'integrity' => 'sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7', 'crossorigin' => 'anonymous'],
                '3_3_6' => ['url' => $protocole . ':' . '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css', 'integrity' => 'sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7', 'crossorigin' => 'anonymous'],
            ],
        ],

        'bootstrap_theme' => [
            'local' => [
                'default' => ['url' => 'assets/theme/css/bootstrap-theme.css'],
                '3_2_0' => ['url' => 'assets/vendor/bootstrap/3.2.0/css/bootstrap-theme.min.css'],
                '3_3_6' => ['url' => 'assets/vendor/bootstrap/3.3.6/css/bootstrap-theme.min.css'],
            ],
            'external' => [
                'default' => ['url' => 'assets/theme/css/bootstrap-theme.css'],
                '3_2_0' => ['url' => $protocole . ':' . '//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css', 'integrity' => 'sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7', 'crossorigin' => 'anonymous'],
                '3_3_6' => ['url' => $protocole . ':' . '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css', 'integrity' => 'sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7', 'crossorigin' => 'anonymous'],
            ],
        ],

        'select2' => [
            'local' =>  [
                '4_0_2' => ['url' => 'assets/vendor/select2/4.0.2/css/select2.min.css'],
            ],
            'external' => [
                '4_0_2' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/css/select2.min.css'],
            ],
        ],

        'bootstrap_select' => [
            'local' =>  [
                '1_6_0' => ['url' => 'assets/theme/ui-plugins/bootstrap-select/1.6.0/css/bootstrap-select.min.css'],
                '1_10_0' => ['url' => 'assets/theme/ui-plugins/bootstrap-select/1.10.0/css/bootstrap-select.min.css'],
            ],
            'external' => [
                '1_6_0' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.0/css/bootstrap-select.min.css'],
                '1_10_0' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css'],
            ],
        ],

        'pretty_photo' => [
            'local' => [
                '3_1_6' => ['url' => 'assets/theme/vendor/prettyphoto/3.1.6/css/prettyPhoto.min.css'],
            ],
            'external' => [
                '3_1_6' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/prettyPhoto/3.1.6/css/prettyPhoto.min.css'],
            ],
        ],

        'owl_carousel' => [
            'local' => [
                '1_24' => ['url' => 'assets/theme/vendor/owl-carousel/1.24/css/owl.carousel.css'],
                '1_3_3' => ['url' => 'assets/theme/vendor/owl-carousel/1.3.3/css/owl.carousel.css'],
            ],
            'external' => [
                '1_24' => ['url' => 'assets/theme/vendor/owl-carousel/1.24/css/owl.carousel.css'],
                '1_3_3' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.css'],
            ],
        ],

        'owl_carousel_theme' => [
            'local' => [
                '1_24' => ['url' => 'assets/theme/vendor/owl-carousel/1.24/css/owl.theme.css'],
                '1_3_3' => ['url' => 'assets/theme/vendor/owl-carousel/1.3.3/css/owl.theme.css'],
            ],
            'external' => [
                '1_24' => ['url' => 'assets/theme/vendor/owl-carousel/1.24/css/owl.theme.css'],
                '1_3_3' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.theme.min.css'],
            ],
        ],

        'owl_carousel_transitions' => [
            'local' => [
                '1_3_3' => ['url' => 'assets/theme/vendor/owl-carousel/1.3.3/css/owl.transitions.css'],
            ],
            'external' => [
                '1_3_3' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.transitions.min.css'],
            ],
        ],

        'vegas' => [
            'local' => [
                '2_2_0' => ['url' => 'assets/vendor/vegas/2.2.0/vegas.min.css'],
            ],
            'external' => [
                '2_2_0' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/vegas/2.2.0/vegas.min.css'],
            ],
        ],

        'animate_css' => [
            'local' => [
                'default' => ['url' => 'assets/vendor/animate.css/animations.css'],
                '3_5_1' => ['url' => 'assets/vendor/animate.css/3.5.1/css/animate.min.css'],
            ],
            'external' => [
                'default' => ['url' => 'assets/vendor/animate.css/animations.css'],
                '3_5_1' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.1/animate.min.css'],
            ],
        ],

        // common
        'youboat' => [
            'common' => [
                'default' => ['url' => 'assets/vendor/youboat/css/youboat.css'],
            ],
        ],
        'material_switch' => [
            'common' => [
                'default' => ['url' => 'assets/vendor/material-switch/css/material-switch.css'],
            ]
        ],
        'cookie_legacy' => [
            'common' => [
                'default' => ['url' => 'assets/vendor/cookie-legacy/css/cookie-legacy.min.css'],
            ]
        ],
        'outlined_iconset' => [
            'common' => [
                'default' => ['url' => 'assets/theme/vendor/outlined-iconset/css/outlined-iconset.css'],
            ]
        ],
        'theme' => [
            'common' => [
                'theme' => ['url' => 'assets/theme/css/theme.css'],
                'styles' => ['url' => 'assets/theme/css/styles.css'],
                'override' => ['url' => 'assets/theme/%country_code%/css/override.css'],
                'ie' => ['url' => 'assets/vendor/youboat/css/ie.css'],
            ],
        ],
        'theme_color' => [
            'common' => [
                'default' => ['url' => 'assets/theme/css/colors/color%theme_color%.css'],
            ],
        ],

        'revolution_slider_extralayers' => [
            'common' => [
                'default' => ['url' => 'assets/theme/css/extras/extralayers.css'],
            ],
        ],
        'revolution_slider_settings' => [
            'common' => [
                'default' => ['url' => 'assets/theme/vendor/revslider/4.6.4/css/settings.css'],
            ],
        ],
        'revolution_slider_fonts' => [
            'common' => [
                'default' => ['url' => $protocole . ':' . '//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Raleway:100,200,300,700,800,900'],
            ],
        ],

        'landing_css' => [
            'local' => [
                'default' => ['url' => 'assets/vendor/youboat/landing/css/%country_code%/landing.min.css'],
            ],
        ],

        'blueimp_gallery' => [
            'common' => [
                'default' => ['url' => 'assets/vendor/jquery-fileupload/css/blueimp-gallery.min.css'],
            ],
        ],

    ],

    //JS

    // vendor
    'js' => [

        'jquery' => [
            'local' => [
                '1_12_1' => ['url' => 'assets/vendor/jquery/jquery-1.12.1.min.js'],
                '2_0_0' => ['url' => 'assets/vendor/jquery/jquery-2.0.0.min.js'],
                '2_2_1' => ['url' => 'assets/vendor/jquery/jquery-2.2.1.min.js'],
            ],
            'external' => [
                '1_12_1' => ['url' => $protocole . ':' . '//code.jquery.com/jquery-1.12.1.min.js'],
                '2_0_0' => ['url' => $protocole . ':' . '//code.jquery.com/jquery-2.0.0.min.js'],
                '2_2_1' => ['url' => $protocole . ':' . '//code.jquery.com/jquery-2.2.1.min.js'],
            ],
        ],

        'fontawesome' => [
            'local' => [
                '4_6_3' => ['url' => $protocole . ':' . '//use.fontawesome.com/5f04df30a1.js'],
            ],
            'external' => [
                '4_6_3' => ['url' => $protocole . ':' . '//use.fontawesome.com/5f04df30a1.js'],
            ],
        ],
        'bootstrap' => [
            'local' => [
                '3_3_1' => ['url' => 'assets/vendor/bootstrap/3.3.1/js/bootstrap.min.js'],
                '3_3_6' => ['url' => 'assets/vendor/bootstrap/3.3.6/js/bootstrap.min.js'],
            ],
            'external' => [
                '3_3_1' => ['url' => $protocole . ':' . '//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js'],
                '3_3_6' => ['url' => $protocole . ':' . '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js', 'integrity' => 'sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS', 'crossorigin' => 'anonymous'],
            ],
        ],

        'moment_with_locales' => [
            'local' =>  [
                '2_11_2' => ['url' => 'assets/vendor/moment/2.11.2/min/moment-with-locales.min.js'],
                '2_13_0' => ['url' => 'assets/vendor/moment/2.13.0/min/moment-with-locales.min.js'],
            ],
            'external' => [
                '2_11_2' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment-with-locales.min.js'],
                '2_13_0' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment-with-locales.min.js'],
            ],
        ],

        'bootstrap_datetimepicker' => [
            'local' =>  [
                '4_17_37' => ['url' => 'assets/vendor/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js'],
            ],
            'external' => [
                '4_17_37' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js'],
            ],
        ],

        'select2' => [
            'local' =>  [
                '4_0_2' => ['url' => 'assets/vendor/select2/4.0.2/js/select2.min.js'],
            ],
            'external' => [
                '4_0_2' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.min.js'],
            ],
        ],

        // Webfont
        'webfont' => [
            'common' => [
                'default' => ['url' => 'assets/js/webfonts.js'],
            ]
        ],

        // Webfont theme
        'webfont_theme' => [
            'common' => [
                'default' => ['url' => 'assets/theme/js/webfonts.js'],
            ]
        ],

        // Theme vendor
        'modernizr' => [
            'local' => [
                '2_6_2' => ['url' => 'assets/theme/vendor/modernizr/2.6.2/js/modernizr.js'],
                '2_8_3' => ['url' => 'assets/theme/vendor/modernizr/2.8.3/js/modernizr.min.js'],
            ],
            'external' => [
                '2_6_2' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/modernizr/2.6.2/modernizr.min.js'],
                '2_8_3' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js'],
            ],
        ],

        'flex_slider' => [
            'local' =>  [
                '2_1' => ['url' => 'assets/theme/vendor/flexslider/2.1/js/jquery.flexslider-min.js'],
                '2_6_1' => ['url' => 'assets/theme/vendor/flexslider/2.6.1/js/jquery.flexslider.min.js'],
            ],
            'external' => [
                '2_1' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/flexslider/2.1/jquery.flexslider-min.js'],
                '2_6_1' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/flexslider/2.6.1/jquery.flexslider.min.js'],
            ],
        ],

        'owl_carousel' => [
            'local' =>  [
                '1_31' => ['url' => 'assets/theme/vendor/owl-carousel/1.31/js/owl.carousel.min.js'],
                '1_3_3' => ['url' => 'assets/theme/vendor/owl-carousel/1.3.3/js/owl.carousel.min.js'],
            ],
            'external' => [
                '1_3_1' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.1/owl.carousel.min.js'],
                '1_3_3' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.js'],
            ],
        ],

        'pretty_photo' => [
            'local' => [
                '3_1_6' => ['url' => 'assets/theme/vendor/prettyphoto/3.1.6/js/prettyphoto.min.js'],
            ],
            'external' => [
                '3_1_6' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/prettyPhoto/3.1.6/js/jquery.prettyPhoto.min.js'],
            ],
        ],

        'waypoints' => [
            'local' =>  [
                '2_0_2' => ['url' => 'assets/theme/vendor/jquery.waypoints/2.0.2/js/waypoints.min.js'],
                '2_0_5' => ['url' => 'assets/theme/vendor/jquery.waypoints/2.0.5/js/waypoints.min.js'],
                '4_0' => ['url' => 'assets/theme/vendor/jquery.waypoints/4.0/js/jquery.waypoints.min.js'],
            ],
            'external' => [
                '2_0_2' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/waypoints/2.0.2/waypoints.min.js'],
                '2_0_5' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/waypoints/2.0.5/waypoints.min.js'],
                '4_0_0' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.0/jquery.waypoints.min.js'],
            ],
        ],

        // no external cdn
        'revolution_slider_tools' => [
            'common' =>  [
                '4_6_4' => ['url' => 'assets/theme/vendor/revslider/4.6.4/js/jquery.themepunch.tools.min.js'],
            ]
        ],

        'revolution_slider' => [
            'common' =>  [
                '4_6_4' => ['url' => 'assets/theme/vendor/revslider/4.6.4/js/jquery.themepunch.revolution.min.js'],
            ]
        ],

        'password_checker' => [
            'common' => [
                'default' => ['url' => 'assets/theme/vendor/password-checker/js/password-checker.js'],
            ]
        ],

        // no local 
        'google_map' => [
            'common' => [
                //'default' => ['url' => $protocole . ':' . '//maps.googleapis.com/maps/api/js?sensor=false'],
                'default' => ['url' => $protocole . ':' . '//maps.googleapis.com/maps/api/js?key=' . env('GOOGLEMAP_API_KEY') . '&callback=initMap'],
            ]
        ],

        // theme ui-plugins
        'fitvids' => [
            'local' =>  [
                '1_0_0' => ['url' => 'assets/theme/ui-plugins/jquery.fitvids/1.0/js/jquery.fitvids.js'],
                '1_1_0' => ['url' => 'assets/theme/ui-plugins/jquery.fitvids/1.1.0/js/jquery.fitvids.js'],
            ],
            'external' => [
                '1_0_0' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/fitvids/1.0.0/jquery.fitvids.min.js'],
                '1_1_0' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/fitvids/1.1.0/jquery.fitvids.min.js'],
            ],
        ],

        'superfish' => [
            'local' =>  [
                '1_7_4' => ['url' => 'assets/theme/ui-plugins/superfish/1.7.4/js/superfish.min.js'],
                '1_7_9' => ['url' => 'assets/theme/ui-plugins/superfish/1.7.9/js/superfish.min.js'],
            ],
            'external' => [
                '1_7_4' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/superfish/1.7.4/superfish.min.js'],
                '1_7_9' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/superfish/1.7.9/js/superfish.min.js'],
            ],
        ],

        'scroll_to' => [
            'local' =>  [
                '1_4_3' => ['url' => 'assets/theme/ui-plugins/jquery.scrollto/1.4.3/js/jquery.scrollTo.min.js'],
                '2_1_2' => ['url' => 'assets/theme/ui-plugins/jquery.scrollto/2.1.2/js/jquery.scrollTo.min.js'],
            ],
            'external' => [
                '1_4_3' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/jquery-scrollTo/1.4.3/jquery.scrollTo.min.js'],
                '2_1_2' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/jquery-scrollTo/2.1.2/jquery.scrollTo.min.js'],
            ],
        ],

        'bootstrap_select' => [
            'local' =>  [
                '1_6_0' => ['url' => 'assets/theme/ui-plugins/bootstrap-select/1.6.0/js/bootstrap-select.min.js'],
                '1_10_0' => ['url' => 'assets/theme/ui-plugins/bootstrap-select/1.10.0/js/bootstrap-select.min.js'],
            ],
            'external' => [
                '1_6_0' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.0/js/bootstrap-select.min.js'],
                '1_10_0' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js'],
            ],
        ],

        'bootstrap_timepicker' => [
            'local' =>  [
                '0_2_6' => ['url' => 'assets/theme/ui-plugins/bootstrap-timepicker/0.2.6/js/bootstrap-timepicker.min.js'],
                '0_5_2' => ['url' => 'assets/theme/ui-plugins/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js'],
            ],
            'external' => [
                '0_2_6' => ['url' => $protocole . ':' . '//cdn.jsdelivr.net/bootstrap.timepicker/0.2.6/js/bootstrap-timepicker.min.js'],
                '0_5_2' => ['url' => $protocole . ':' . '//cdn.jsdelivr.net/bootstrap.timepicker/0.5.2/js/bootstrap-timepicker.min.js'],
            ],
        ],

        'bootstrap_datepicker' => [
            'local' =>  [
                'jdewit' => ['url' => 'assets/theme/ui-plugins/bootstrap-datepicker_jdewit/js/bootstrap-datepickermin.js'],
                '1_6_1' => ['url' => 'assets/theme/ui-plugins/bootstrap-datepicker/1.6.1/js/bootstrap-datepicker.min.js'],
            ],
            'external' => [
                '1_6_1' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.1/js/bootstrap-datepicker.min.js'],
            ],
        ],

        // no cdn
        'count_to' => [
            'common' =>  [
                '1_0_0' => ['url' => 'assets/theme/ui-plugins/jquery.countTo/1.0.0/js/jquery.countTo.js'],
                '1_2_0' => ['url' => 'assets/theme/ui-plugins/jquery.countTo/1.2.0/js/jquery.countTo.js'],
            ]
        ],


        // theme helper-plugins
        'hoverintent' => [
            'local' =>  [
                '2013_03_11' => ['url' => 'assets/theme/helper-plugins/jquery.hoverintent/2013.03.11/js/hoverintent.min.js'],
                '1_8_1' => ['url' => 'assets/theme/helper-plugins/jquery.hoverintent/1.8.1/js/jquery.hoverIntent.min.js'],
            ],
            'external' => [
                '2013_03_11' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/jquery.hoverintent/2013.03.11/hoverintent.min.js'],
                '1_8_1' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/jquery.hoverintent/1.8.1/jquery.hoverIntent.min.js'],
            ],
        ],

        'sticky' => [
            'local' =>  [
                '1_0_0' => ['url' => 'assets/theme/helper-plugins/sticky/1.0.0/js/jquery.sticky.js'],
                '1_0_3' => ['url' => 'assets/theme/helper-plugins/sticky/1.0.3/js/jquery.sticky.js'],
            ],
            'external' => [
                '1_0_0' => ['url' => $protocole . ':' . '//cdn.jsdelivr.net/jquery.sticky/1.0.0/jquery.sticky.min.js'],
                '1_0_3' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/jquery.sticky/1.0.3/jquery.sticky.min.js'],
            ],
        ],

        'isotope' => [
            'local' =>  [
                '1_5_25' => ['url' => 'assets/theme/helper-plugins/jquery.isotope/1.5.25/js/jquery.isotope.min.js'],
            ],
            'external' => [
                '1_5_25' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/jquery.isotope/1.5.25/jquery.isotope.min.js'],
            ],
        ],

        'touch_swipe' => [
            'local' =>  [
                '1_3_3' => ['url' => 'assets/theme/helper-plugins/jquery.touchSwipe/1.3.3/js/jquery.touchSwipe.min.js'],
                '1_6_16' => ['url' => 'assets/theme/helper-plugins/jquery.touchSwipe/1.6.16/js/jquery.touchSwipe.min.js'],
            ],
            'external' => [
                '1_3_3' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/jquery.touchswipe/1.3.3/jquery.touchSwipe.min.js'],
                '1_6_16' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/jquery.touchswipe/1.6.16/jquery.touchSwipe.min.js'],
            ],
        ],

        'throttle_debounce' => [
            'local' =>  [
                '1_1' => ['url' => 'assets/theme/helper-plugins/jquery.ba-throttle-debounce/1.1/js/jquery.ba-throttle-debounce.min.js'],
            ],
            'external' => [
                '1_1' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/jquery-throttle-debounce/1.1/jquery.ba-throttle-debounce.min.js'],
            ],
        ],

        'localscroll' => [
            'local' =>  [
                '1_2_7' => ['url' => 'assets/theme/helper-plugins/jquery.localscroll/1.2.7/js/jquery.localscroll.min.js'],
                '1_4_0' => ['url' => 'assets/theme/helper-plugins/jquery.localscroll/1.4.0/js/jquery.localscroll.min.js'],
            ],
            'external' => [
                '1_2_8' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/jquery-localScroll/1.2.8/jquery.localScroll.min.js'],
                '1_4_0' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/jquery-localScroll/1.4.0/jquery.localScroll.min.js'],
            ],
        ],

        'imagesloaded' => [
            'local' =>  [
                '2_1_1' => ['url' => 'assets/theme/helper-plugins/jquery.imagesloaded/2.1.1/js/jquery.imagesloaded.min.js'],
                '4_1_0' => ['url' => 'assets/theme/helper-plugins/jquery.imagesloaded/4.1.0/js/imagesloaded.min.js'],
                '4_1_0_pkgd' => ['url' => 'assets/theme/helper-plugins/jquery.imagesloaded/4.1.0/js/imagesloaded.pkgd.min.js'],
            ],
            'external' => [
                '2_1_1' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/2.1.0/jquery.imagesloaded.min.js'],
                '4_1_0' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/4.1.0/imagesloaded.min.js'],
                '4_1_0_pkgd' => ['url' => $protocole . ':' . '//npmcdn.com/imagesloaded@4.1/imagesloaded.pkgd.min.js'],
            ],
        ],

        'easing' => [
            'local' =>  [
                '1_3' => ['url' => 'assets/theme/helper-plugins/jquery-easing/1.3/js/jquery.easing.js'],
            ],
            'external' => [
                '1_3' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js'],
            ],
        ],

        /*'scroll_to' => [
            'local' =>  [
                '1_4_3' => ['url' => 'assets/theme/ui-plugins/jquery.scrollto/1.4.3/js/jquery.scrollTo.min.js'],
                '2_1_2' => ['url' => 'assets/theme/ui-plugins/jquery.scrollto/2.1.2/js/jquery.scrollTo.min.js'],
            ],
            'external' => [
                '1_4_3' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/jquery-scrollTo/1.4.3/jquery.scrollTo.min.js'],
                '2_1_2' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/jquery-scrollTo/2.1.2/jquery.scrollTo.min.js'],
            ],
        ],*/

        'cookie' => [
            'local' =>  [
                '2_1_1' => ['url' => 'assets/theme/helper-plugins/js-cookie/2.1.1/js/js.cookie-2.1.1.min.js'],
            ],
            'external' => [
                '2_1_1' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/js-cookie/2.1.1/js.cookie.min.js'],
            ],
        ],

        'swipe' => [
            'local' =>  [
                '2_0' => ['url' => 'assets/theme/helper-plugins/swipe/2.0/js/swipe.min.js'],
            ],
            'external' => [
                '2_0_0' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/swipe/2.0.0/swipe.min.js'],
            ],
        ],

        'appear' => [
            'local' =>  [
                'default' => ['url' => 'assets/theme/helper-plugins/jquery.appear/js/jquery.appear.js'],
                '0_3_3' => ['url' => 'assets/theme/helper-plugins/jquery.appear/0.3.3/js/jquery.appear.min.js'],
            ],
            'external' => [
                'default' => ['url' => $protocole . ':' . '//raw.githubusercontent.com/bas2k/jquery.appear/master/jquery.appear.js'],
                '0_3_3' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/jquery.appear/0.3.3/jquery.appear.min.js'],
            ],
        ],

        'parallax' => [
            'local' =>  [
                '1_1_3' => ['url' => 'assets/theme/helper-plugins/jquery-parallax/1.1.3/js/jquery-parallax-min.js'],
            ],
            'external' => [
                '1_1_3' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/jquery-parallax/1.1.3/jquery-parallax-min.js'],
            ],
        ],

        'retina' => [
            'local' =>  [
                '1_1_0' => ['url' => 'assets/theme/helper-plugins/retina/1.1.0/js/retina.min.js'],
                '1_3_0' => ['url' => 'assets/theme/helper-plugins/retina/1.3.0/js/retina.min.js'],
            ],
            'external' => [
                '1_1_0' => ['url' => $protocole . ':' . '//cdn.jsdelivr.net/retinajs/1.1.0/retina.min.js'],
                '1_3_0' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/retina.js/1.3.0/retina.min.js'],
            ],
        ],

        'transit' => [
            'local' =>  [
                '0_9_12' => ['url' => 'assets/theme/helper-plugins/jquery.transit/0.9.12/js/jquery.transit.min.js'],
            ],
            'external' => [
                '0_9_12' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/jquery.transit/0.9.12/jquery.transit.min.js'],
            ],
        ],

        'mousewheel' => [
            'local' =>  [
                '3_0_6' => ['url' => 'assets/theme/helper-plugins/jquery.mousewheel/3.0.6/js/jquery.mousewheel.min.js'],
                '3_1_13' => ['url' => 'assets/theme/helper-plugins/jquery.mousewheel/3.1.13/js/jquery.mousewheel.min.js'],
            ],
            'external' => [
                '3_0_6' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/jquery-mousewheel/3.0.6/jquery.mousewheel.min.js'],
                '3_1_13' => ['url' => $protocole . ':' . '//cdnjs.cloudflare.com/ajax/libs/jquery-mousewheel/3.1.13/jquery.mousewheel.min.js'],
            ],
        ],

        // no cdn
        'tweetie' => [
            'common' => [
                'default' => ['url' => 'assets/theme/helper-plugins/tweetie/js/tweetie.min.js'],
            ]
        ],

        // common
        'ui_plugins' => [
            'common' => [
                'default' => ['url' => 'assets/theme/js/base/ui-plugins.js'],
            ]
        ],
        'helper_plugins' => [
            'common' => [
                'default' => ['url' => 'assets/theme/js/base/helper-plugins.js'],
            ]
        ],
        'init_theme' => [
            'common' => [
                'default' => ['url' => 'assets/theme/js/init.js'],
            ]
        ],

        'youboat' => [
            'common' => [
                'default' => ['url' => 'assets/vendor/youboat/js/youboat.js'],
            ]
        ],

        'youboat_filters' => [
            'common' => [
                //'default' => ['url' => 'assets/vendor/youboat/js/youboat_filters.js'],
                //'default' => ['url' => 'assets/vendor/youboat/js/filters.js'],
                'default' => ['url' => 'assets/vendor/youboat/js/filters_search.js'],
            ]
        ],

        'youboat_filters_bod' => [
            'common' => [
                'default' => ['url' => 'assets/vendor/youboat/js/youboat_filters_bod.js'],
            ]
        ],

        'youboat_filters_recovery' => [
            'common' => [
                'default' => ['url' => 'assets/vendor/youboat/js/filters_recovery.js'],
            ]
        ],

        'cookie_legacy' => [
            'common' => [
                'default' => ['url' => 'assets/vendor/cookie-legacy/js/cookie-legacy.min.js'],
            ]
        ],

        'blueimp_gallery' => [
            'common' => [
                'default' => ['url' => 'assets/vendor/jquery-fileupload/js/vendor/jquery.blueimp-gallery.min.js'],
            ],
        ],

    ],
];