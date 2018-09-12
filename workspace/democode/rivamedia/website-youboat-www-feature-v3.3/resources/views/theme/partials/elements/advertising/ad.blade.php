<?php
    $advertising = '';

    $ad_advertizer = 'autopromo'; // force $ad_advertizer

    $advertising_ads = [];

    $sell_offer_prices = [
       'uk' => config('youboat.'. $country_code .'.currency') . '29',
       'fr' => config('youboat.'. $country_code .'.currency') . '29',
       'be' => config('youboat.'. $country_code .'.currency') . '29',
       'nl' => config('youboat.'. $country_code .'.currency') . '29',
       'lu' => config('youboat.'. $country_code .'.currency') . '29',
    ];

    $lang = '';
    if(count(config('app.languages')) > 1) {
        $lang = '/' . config('app.locale');
    }

    $autopromo_banners = [
        'AUTOPROMO_SELL_2017' => [
                '300x250' => [
                        'number_rotation'       => 3,
                        'banner_title'          => trans('sell.sell_offer_text1', ['price' => $sell_offer_prices[$country_code]]),
                        'ad_img_size'           => 'width="300" height="250"',
                        'banner_url'            => 'assets/a_d_v_e_r_t_i_s_i_n_g/' . $country_code . $lang . '/autopromo/' . 'banner-sell_300x250_%d.jpg',
                        'ad_url_tracking'       => url(trans_route($currentLocale, 'routes.sell')),
                        'ad_img_src_tracking'   => '',
                        'data_ga'               => 'data-ga="AUTOPROMO~SELL|Banner 300x250 %d"',
                ],
                '728x90' => [
                        'number_rotation'       => 3,
                        'banner_title'          => trans('sell.sell_offer_text1', ['price' => $sell_offer_prices[$country_code]]),
                        'ad_img_size'           => 'width="728" height="90"',
                        'banner_url'            => 'assets/a_d_v_e_r_t_i_s_i_n_g/' . $country_code . $lang . '/autopromo/' . 'banner-sell_728x90_%d.jpg',
                        'ad_url_tracking'       => url(trans_route($currentLocale, 'routes.sell')),
                        'ad_img_src_tracking'   => '',
                        'data_ga'               => 'data-ga="AUTOPROMO~SELL|Banner 728x90 %d"',
                ]
        ]
    ];

    $advertising_ads['autopromo'] = [
        'uk' => $autopromo_banners,
        'fr' => $autopromo_banners,
        'be' => $autopromo_banners,
        'nl' => $autopromo_banners
    ];

    $advertising_ads['yb'] = [
        'uk' => [
            'LBS_2017' => [
                '300x250' => [
                    'banner_title'          => 'London Boat Show 2017',
                    'ad_img_size'           => 'width="300" height="250"',
                    'banner_url'            => 'assets/a_d_v_e_r_t_i_s_i_n_g/' . $country_code . '/yb/' . 'banner-300x250-LBS_2017.png',
                    'ad_url_tracking'       => 'http://londonboatshow.com/',
                    'ad_img_src_tracking'   => '',
                ],
                '728x90' => [
                    'banner_title'          => 'London Boat Show 2017',
                    'ad_img_size'           => 'width="728" height="90"',
                    'banner_url'            => 'assets/a_d_v_e_r_t_i_s_i_n_g/' . $country_code . '/yb/' . 'banner-728x90-LBS_2017.gif',
                    'ad_url_tracking'       => 'http://londonboatshow.com/',
                    'ad_img_src_tracking'   => '',
                ]
            ]
        ]
    ];
    $advertising_ads['xiti'] = [
        'uk' => [
            'Nautic_Echange-Youboat' => [
                '300x250' => [
                    'banner_title'          => '',
                    'ad_img_size'           => 'width="300" height="250"',
                    'banner_url'            => 'assets/a_d_v_e_r_t_i_s_i_n_g/' . $country_code . '/xiti/' . 'banner-300x250-nautic-voile-order-UK.gif',
                    'ad_url_tracking'       => 'http://logi11.xiti.com/go.url?xts=263340&xtor=AD-6831542-[Nautic_Echange-Youboat-300x250]-[GB]-[rect300]-[http://www.youboat.fr/]-[HP]&url=http://www.salonnautiqueparis.com/GB/Ticketing-2015.htm?FromBO=Y',
                    'ad_img_src_tracking'   => 'http://logi11.xiti.com/get.url?xts=263340&xtor=AD-6831542-[Nautic_Echange-Youboat-300x250]-[GB]-[rect300]-[http://www.youboat.fr/]-[HP]&type=ad&url=',
                ],
                '728x90' => [
                    'banner_title'          => '',
                    'ad_img_size'           => 'width="728" height="90"',
                    'banner_url'            => 'assets/a_d_v_e_r_t_i_s_i_n_g/' . $country_code . '/xiti/' . 'banner-728x90-nautic-voile-moteur-CTA-UK.gif',
                    'ad_url_tracking'       => 'http://logi11.xiti.com/go.url?xts=263340&xtor=AD-6831542-[Nautic_Echange-Youboat-728x90]-[GB]-[wide]-[http://www.youboat.fr/]-[HP]&url=http://www.salonnautiqueparis.com/GB/Ticketing-2015.htm?FromBO=Y',
                    'ad_img_src_tracking'   => 'http://logi11.xiti.com/get.url?xts=263340&xtor=AD-6831542-[Nautic_Echange-Youboat-728x90]-[GB]-[wide]-[http://www.youboat.fr/]-[HP]&type=ad&url=',
                ]
            ]
        ]
    ];

    if(!isset($ad_advertizer)) {
        $ad_advertizer = key($advertising_ads);
    }
    if(!isset($ad_campaign) && array_key_exists($country_code, $advertising_ads[$ad_advertizer])) {
        $ad_campaign = key($advertising_ads[$ad_advertizer][$country_code]);
    }

    if (isset($ad_advertizer) && isset($ad_campaign) && isset($ad_size)) {
        $ad_img_size            = isset($advertising_ads[$ad_advertizer][$country_code][$ad_campaign][$ad_size]['ad_img_size']) ? $advertising_ads[$ad_advertizer][$country_code][$ad_campaign][$ad_size]['ad_img_size'] : '';

        $data_ga                = isset($advertising_ads[$ad_advertizer][$country_code][$ad_campaign][$ad_size]['data_ga']) ? $advertising_ads[$ad_advertizer][$country_code][$ad_campaign][$ad_size]['data_ga'] : '';
        $GA_event               = !empty($data_ga) ? 'GA_event' : 'blank';
        $banner_url             = isset($advertising_ads[$ad_advertizer][$country_code][$ad_campaign][$ad_size]['banner_url']) ? asset($advertising_ads[$ad_advertizer][$country_code][$ad_campaign][$ad_size]['banner_url']) : '';
        $number_rotation        = isset($advertising_ads[$ad_advertizer][$country_code][$ad_campaign][$ad_size]['number_rotation']) ? $advertising_ads[$ad_advertizer][$country_code][$ad_campaign][$ad_size]['number_rotation'] : '';

        if(!empty($number_rotation) && !empty($banner_url)) {
            $i = mt_rand(1,$number_rotation);
            $banner_url             = sprintf($banner_url, $i);
            if(!empty($data_ga)) {
                $data_ga = sprintf($data_ga, $i);
            }
        }

        $banner_title           = isset($advertising_ads[$ad_advertizer][$country_code][$ad_campaign][$ad_size]['banner_title']) ? $advertising_ads[$ad_advertizer][$country_code][$ad_campaign][$ad_size]['banner_title'] : '';

        if(empty($ad_img_size)) {
            list($w,$h) = explode('x', $ad_size);
            $ad_img_size            = 'width="' . $w .'" height="' . $h .'"';
        }
        if(!empty($banner_url)) {
            $ad_url_tracking        = isset($advertising_ads[$ad_advertizer][$country_code][$ad_campaign][$ad_size]['ad_url_tracking']) ? $advertising_ads[$ad_advertizer][$country_code][$ad_campaign][$ad_size]['ad_url_tracking'] : '';
            $ad_img_src_tracking    = isset($advertising_ads[$ad_advertizer][$country_code][$ad_campaign][$ad_size]['ad_img_src_tracking']) ? $advertising_ads[$ad_advertizer][$country_code][$ad_campaign][$ad_size]['ad_img_src_tracking'] . $banner_url : $banner_url;
            $advertising = '<a href="' . $ad_url_tracking . '" ' . $data_ga . 'class="' . $GA_event . ' advertising advertising_'. $ad_size .'"><img src="' . $ad_img_src_tracking . '"' . $ad_img_size . ' alt="' . $banner_title . '" class="img-responsive"></a>';
        }
    }

    if(!empty($advertising)) {
        echo $advertising;
    } else if (isset($topnav) && !empty($topnav)) {
        echo $topnav;
    }
?>
