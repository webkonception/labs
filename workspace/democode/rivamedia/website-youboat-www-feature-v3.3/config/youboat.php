<?php
$default = [
    'locale' => 'en',
    'currency' => '£',
    'country_code' => 'GB',
    'ua' => 'UA-22390730-34',
    'robots_index' => '',
    'ad_banners' => true,
    'recaptcha' => true,
    'website_name' => 'Youboat UK',
    'website_url' => '',
    'tagline' => 'New and Used boats for sale',
    'default_metas_title' => 'Motorboats, Sailing Boats and Yachts for sale - Youboat UK',
    'default_metas_description' => 'Youboat is the largest website for power boats, sailing boats, rigid or semi-rigid hulled inflatables. All the adverts are placed in numerous categories such as passenger tour boats, fishing boats, day cruiser, open flybridge, keelboat sailing boats, dinghies.',
    'default_metas_keywords' => 'power boats, sailing boats, rigid, semi-rigid hulled inflatables, inflatables, passenger tour boats, fishing boats, day cruiser, open flybridge, keelboat sailing boats, dinghies',
    'phone' => '+44-07-5654-24955',

    'MAIL_NO_REPLY_EMAIL' => 'services@youboat.com',
    'MAIL_NO_REPLY_NAME' => 'Services',
    'MAIL_CONTACT_EMAIL' => 'contact@youboat.com',
    'MAIL_CONTACT_NAME' => 'Contact',

    'email' => 'youboat.uk@gmail.com',
    //'email' => 'youboatuk@rivamedia.fr',

    'contact_email' => 'youboat.uk@gmail.com',

    'emails_bcc' => 'contact@rivamedia.fr',
    //'emails_bcc' => 'emmanuel.deiller.rivamedia+bcc@gmail.com',
    //'country_manager_email' => 'emmanuel.deiller.rivamedia+country_manager_email@gmail.com',
    'country_manager_email' => 'youboat.uk+country_manager@gmail.com',
    'address' => '',

    'facebook' => 'https://www.facebook.com/YouboatUK',
    'facebook_widget' => false,
    'twitter' => 'https://twitter.com/youboat_com',
    'twitter_widget' => true,
    'theme' => [
        //'color'=> 1, /* 1 to 12*/
        'tpl' => [
            'header_version' => ['header-v1'],
            //'header_version' => ['header-v2'],
            //'header_version' => ['header-v3'],
            //'header_version' => ['header-v4'],
            'hero_area' => [
                'flex_slider' => true,
                'search_over_slider' => true,
                'owl_carousel' => false,
                'revolution_slider' => false,
            ],
            'cookie_legacy' => false,
            'utility_bar' => true,
            'currency_nav' => false,
            'language_nav' => true,
        ],
        'vendor' => [
            'select2' => true,
            'google_map' => true,
            'modernizr' => true,
            'flex_slider' => true,
            'owl_carousel' => true,
            'pretty_photo' => true,
            'waypoints' => false,
            'revolution_slider' => false,
            'password_checker' => true,
        ],
        'ui_plugins' => [
            'fitvids' => false,
            'superfish' => true, // set hoverintent to true if superfish
            'scroll_to' => true,
            'count_to' => false,
            'bootstrap_select' => true,
            'bootstrap_timepicker' => false,
            'bootstrap_datepicker' => false,
        ],
        'helper_plugins' => [
            'hoverintent' => true, // set hoverintent to true if superfish
            'sticky' => true,
            'imagesloaded' => false, // set imagesloaded to true if isotope
            'isotope' => false, // set imagesloaded to true if isotope
            'touch_swipe' => false,
            'throttle_debounce' => false,
            'localscroll' => false,
            'easing' => true,
            'scroll_to' => false,
            'cookie' => false,
            'swipe' => false,
            'appear' => true,
            'parallax' => true,
            'retina' => false,
            'transit' => false,
            'mousewheel' => false,
            'tweetie' => true,

        ],
    ],
    'vendor' => [
        'bootstrap_datetimepicker' => false,
        'select2' => true,
    ],
    'twitter_api' => [
        'CONSUMER_KEY' => 'JKO6zJIgNCKWUtUR8G8SL6W3x',
        'CONSUMER_SECRET' => 'wnF8juBaevW46HCoCb8O0jT6pb9LdOE4BKCkcQBFBO438iv3RR',
        'ACCESS_TOKEN' => '722416932444585984-G2bF9dTFtnkcm1eifQp70H1NLZNKLGI',
        'ACCESS_SECRET' => 'Kd0sR0HswefvdlUm6arDN8kEnc66aLH4vkbX1uZ2g8gNo',
        'owner' => 'youboat_com',
        'owner_id' => '722416932444585984'
    ],
    'locations' => [
        'regions' => [
            'south_east' => [
                'name'=> 'South East',
                'county' => [
                    'Hampshire',
                    'Surrey',
                    'Kent',
                    'West Sussex',
                    'East Sussex',
                    'Isle of Wight',
                    'Sussex',
                ]
            ],
            'south_west' => [
                'name'=> 'South West',
                'county' => [
                    'Dorset',
                    'Devon',
                    'Cornwall',
                    'Gloucestershire',
                    'Somerset',
                    'Avon',
                    'Bristol',
                    'Wiltshire',
                    'Isles of Scilly',
                ]
            ],
            'eastern' => [
                'name'=> 'Eastern',
                'county' => [
                    'Essex',
                    'Norfolk',
                    'Cambridgeshire',
                    'Suffolk',
                    'Bedfordshire',
                    'Hertfordshire',
                ]
            ],
            'north_east' => [
                'name'=> 'North East',
                'county' => [
                    'Yorkshire',
                    'North Yorkshire',
                    'South Yorkshire',
                    'Northumberland',
                    'Cleveland',
                    'Humberside',
                    'East Yorkshire',
                    'Tyne & Wear',
                    'West Yorkshire',
                    'Tyne And Wear',
                    'County Durham',
                    'Durham',
                ]
            ],
            'wales' => [
                'name'=> 'Wales',
                'county' => [
                    'Gwynedd',
                    'Pembrokeshire',
                    'West Glamorgan',
                    'Glamorgan',
                    'Conwy',
                    'South Wales',
                    'Dyfed',
                    'Clwyd',
                    'South Glamorgan',
                    'Flintshire',
                    'Monmouthshire',
                    'Carmarthenshire',
                    'Isle of Anglesey',
                    'North Wales',
                    'Gwent',
                    'Mid Glamorgan',
                    'Powys',
                    'Denbighshire',
                    'Mid Wales',
                ]
            ],
            'north_west' => [
                'name'=> 'North West',
                'county' => [
                    'Cumbria',
                    'Lancashire',
                    'Greater Manchester',
                    'Liverpool',
                    'Merseyside',
                    'Cheshire',
                ]
            ],
            'east_midlands' => [
                'name'=> 'East Midlands',
                'county' => [
                    'Leicestershire',
                    'Nottinghamshire',
                    'Lincolnshire',
                    'Derbyshire',
                    'Northamptonshire',
                    'Rutland',
                ]
            ],
            'thames_valley' => [
                'name'=> 'Thames Valley',
                'county' => [
                    'Berkshire',
                    'London',
                    'Middlesex',
                    'Buckinghamshire',
                    'Oxfordshire',
                ]
            ],
            'channel_islands' => [
                'name'=> 'Channel Islands',
                'county' => [
                    'Guernsey',
                    'Jersey',
                ]
            ],
            'scotland' => [
                'name' => 'Scotland',
                'county' => [
                    'Strathclyde',
                    'Glasgow',
                    'Edinburgh',
                    'Highland',
                    'Aberdeenshire',
                    'Argyll',
                    'Angus',
                    'Perthshire',
                    'Ayrshire',
                    'Dunbartonshire',
                    'Renfrewshire',
                    'Fife',
                    'Dumfries & Galloway',
                    'Inverness-shire',
                    'Argyllshire',
                    'East Lothian',
                    'Grampian',
                    'Central Scotland',
                    'Morayshire',
                    'West Lothian',
                    'Isle Of Bute',
                    'Isle of Skye',
                    'Ross-shire',
                    'Scottish Borders',
                    'Shetland',
                    'Stirlingshire',
                    'Dumfriesshire',
                    'Falkirk',
                    'Isle Of Lewis',
                    'Isle of Arran',
                    'Isle of Rum',
                    'Lanarkshire',
                    'Lothian',
                    'Midlothian',
                    'Orkney',
                    'Peeblesshire',
                    'Stirling',
                    'Tayside',
                ]
            ],
            'west_midlands' => [
                'name'=> 'West Midlands',
                'county' => [
                    'Herefordshire',
                    'Shropshire',
                    'Staffordshire',
                    'Warwickshire',
                    'Worcestershire',
                ]
            ],
            'northern_ireland' => [
                'name'=> 'Northern Ireland',
                'county' => [
                    'County Londonderry',
                    'County Down',
                    'Antrim',
                    'Armagh',
                    'County Antrim',
                    'Fermanagh',
                ]
            ],
            'isle_of_man' => [
                'name'=> 'Isle of Man',
                'county' => [
                    'Isle of Man',
                ]
            ],
            'southern_ireland' => [
                'name'=> 'Southern Ireland',
                'county' => [
                    'Cork',
                    'Galway',
                    'Cavan',
                ]
            ],
            'ireland' => [
                'name'=> 'Ireland',
                'county' => [
                    'Meath',
                    'Monaghan',
                    'Offaly',
                    'Waterford',
                    'Westmeath',
                    'Wexford',
                ]
            ],
        ],
        'counties' => [
            '1'=>'Aberdeenshire',
            '2'=>'Angus',
            '3'=>'Antrim',
            '4'=>'Argyll',
            '5'=>'Argyllshire',
            '6'=>'Armagh',
            '7'=>'Avon',
            '8'=>'Ayrshire',
            '9'=>'Banffshire',
            '10'=>'Bedfordshire',
            '11'=>'Berkshire',
            '12'=>'Berwickshire',
            '13'=>'Bristol',
            '14'=>'Buckinghamshire',
            '15'=>'Caithness',
            '16'=>'Cambridgeshire',
            '17'=>'Carlow',
            '18'=>'Carmarthenshire',
            '19'=>'Cavan',
            '20'=>'Central Scotland',
            '21'=>'Channel Islands',
            '22'=>'Channel Isles',
            '23'=>'Cheshire',
            '24'=>'Clackmannanshire',
            '25'=>'Clare',
            '26'=>'Cleveland',
            '27'=>'Clwyd',
            '28'=>'Co. Durham',
            '29'=>'Conwy',
            '30'=>'Cork',
            '31'=>'Cornwall',
            '32'=>'County Antrim',
            '33'=>'County Armagh',
            '34'=>'County Down',
            '35'=>'County Durham',
            '36'=>'County Fermanagh',
            '37'=>'County Londonderry',
            '38'=>'County Tyrone',
            '39'=>'Cumbria',
            '40'=>'Denbighshire',
            '41'=>'Derbyshire',
            '42'=>'Derry',
            '43'=>'Devon',
            '44'=>'Donegal',
            '45'=>'Dorset',
            '46'=>'Down',
            '47'=>'Dublin',
            '48'=>'Dumfries & Galloway',
            '49'=>'Dumfriesshire',
            '50'=>'Dunbartonshire',
            '51'=>'Dungannon',
            '52'=>'Durham',
            '53'=>'Dyfed',
            '54'=>'East Lothian',
            '55'=>'East Sussex',
            '56'=>'East Yorkshire',
            '57'=>'Edinburgh',
            '58'=>'Eilean Siar',
            '59'=>'Essex',
            '60'=>'Falkirk',
            '61'=>'Fermanagh',
            '62'=>'Fife',
            '63'=>'Flintshire',
            '64'=>'Galway',
            '65'=>'Glamorgan',
            '66'=>'Glasgow',
            '67'=>'Gloucestershire',
            '68'=>'Grampian',
            '69'=>'Greater Manchester',
            '70'=>'Guernsey',
            '71'=>'Gwent',
            '72'=>'Gwynedd',
            '73'=>'Hampshire',
            '74'=>'Herefordshire',
            '75'=>'Hertfordshire',
            '76'=>'Highland',
            '77'=>'Humberside',
            '78'=>'Huntingdonshire',
            '79'=>'Inverness-shire',
            '80'=>'Isle of Anglesey',
            '81'=>'Isle of Arran',
            '82'=>'Isle Of Barra',
            '83'=>'Isle Of Benbecula',
            '84'=>'Isle Of Bute',
            '85'=>'Isle of Canna',
            '86'=>'Isle Of Coll',
            '87'=>'Isle Of Colonsay',
            '88'=>'Isle of Cumbrae',
            '89'=>'Isle of Eigg',
            '90'=>'Isle Of Gigha',
            '91'=>'Isle Of Harris',
            '92'=>'Isle Of Iona',
            '93'=>'Isle Of Islay',
            '94'=>'Isle Of Jura',
            '95'=>'Isle Of Lewis',
            '96'=>'Isle of Man',
            '97'=>'Isle Of Mull',
            '98'=>'Isle Of North Uist',
            '99'=>'Isle of Rum',
            '100'=>'Isle Of Scalpay',
            '101'=>'Isle of Skye',
            '102'=>'Isle Of South Uist',
            '103'=>'Isle Of Tiree',
            '104'=>'Isle of Wight',
            '105'=>'Isles of Scilly',
            '106'=>'Jersey',
            '107'=>'Kent',
            '108'=>'Kerry',
            '109'=>'Kildare',
            '110'=>'Kilkenny',
            '111'=>'Kincardineshire',
            '112'=>'Kinross-Shire',
            '113'=>'Kirkcudbrightshire',
            '114'=>'Lanarkshire',
            '115'=>'Lanarkshire (Scot)',
            '116'=>'Lancashire',
            '117'=>'Laois',
            '118'=>'Leicestershire',
            '119'=>'Leitrim',
            '120'=>'Limerick',
            '121'=>'Lincolnshire',
            '122'=>'Liverpool',
            '123'=>'London',
            '124'=>'Longford',
            '125'=>'Lothian',
            '126'=>'Louth',
            '127'=>'Mayo',
            '128'=>'Meath',
            '129'=>'Merseyside',
            '130'=>'Mid Glamorgan',
            '131'=>'Mid Wales',
            '132'=>'Middlesex',
            '133'=>'Midlothian',
            '134'=>'Monaghan',
            '135'=>'Monmouthshire',
            '136'=>'Morayshire',
            '137'=>'Nairnshire',
            '138'=>'Norfolk',
            '139'=>'North Humberside',
            '140'=>'North Wales',
            '141'=>'North Yorkshire',
            '142'=>'Northamptonshire',
            '143'=>'Northern Ireland',
            '144'=>'Northumberland',
            '145'=>'Nottinghamshire',
            '146'=>'Offaly',
            '147'=>'Orkney',
            '148'=>'OverSeas',
            '149'=>'Oxfordshire',
            '150'=>'Peeblesshire',
            '151'=>'Pembrokeshire',
            '152'=>'Perthshire',
            '153'=>'Powys',
            '154'=>'Renfrewshire',
            '155'=>'Ridings',
            '156'=>'Roscommon',
            '157'=>'Ross-shire',
            '158'=>'Roxburghshire',
            '159'=>'Rutland',
            '160'=>'Scottish Borders',
            '161'=>'Selkirkshire',
            '162'=>'Shetland',
            '163'=>'Shetland Islands',
            '164'=>'Shropshire',
            '165'=>'Sligo',
            '166'=>'Somerset',
            '167'=>'South Glamorgan',
            '168'=>'South Humberside',
            '169'=>'South Wales',
            '170'=>'South Yorkshire',
            '171'=>'Southern Ireland',
            '172'=>'Staffordshire',
            '173'=>'Stirling',
            '174'=>'Stirlingshire',
            '175'=>'Strathclyde',
            '176'=>'Suffolk',
            '177'=>'Surrey',
            '178'=>'Sussex',
            '179'=>'Sutherland',
            '180'=>'Tayside',
            '181'=>'Tipperary',
            '182'=>'Tyne & Wear',
            '183'=>'Tyne And Wear',
            '184'=>'Tyrone',
            '185'=>'W Glam',
            '186'=>'Warwickshire',
            '187'=>'Waterford',
            '188'=>'West Glamorgan',
            '189'=>'West Lothian',
            '190'=>'West Midlands',
            '191'=>'West Sussex',
            '192'=>'West Yorkshire',
            '193'=>'Western Isles',
            '194'=>'Westmeath',
            '195'=>'Wexford',
            '196'=>'Wicklow',
            '197'=>'Wigtownshire',
            '198'=>'Wiltshire',
            '199'=>'Worcestershire',
            '200'=>'Yorkshire'
        ],
    ],
    'stripe' => [
        'plan_pay_url' => 'https://uk.youboat.com/pay/',
        'charge_description' => 'Unlimited UK Offer',
        'charge_amount' => 29,
        'pay_currency' => 'gbp',
        'pay_locale' => 'en',
    ]
];

$fr = [
    'locale' => 'fr',
    'currency' => '€',
    'country_code' => 'FR',
    'ua' => 'UA-22390730-34',
    'ad_banners' => true,
    'recaptcha' => true,
    'website_name' => 'Youboat FR',
    'website_url' => '',
    'tagline' => 'Bateau occasion, voilier, semi-rigide. Vente bateau et accastillage',
    'default_metas_title' => 'Bateau occasion, voilier, semi-rigide. Vente bateau et accastillage - Youboat BE',
    'default_metas_description' => "Youboat propose plus de 18.000 annonces de bateau, voilier, semi-rigides, moteurs et accessoires occasion et neuf. Achat, vente et location de vedettes habitables, open, day cruiser et même des moteurs marins, accessoires et place de port. Annonces de location de bateau. Publication d'actualités nautique pour bien acheter son bateau.",
    'default_metas_keywords' => 'Bateaux à moteur, bateaux à voile, pneumatiques rigides, semi-rigides, bateaux de tourisme, bateaux de pêche, croiseur de jour, open flybridge',
    'phone' => '+33(2)38.75.99.41',

    'MAIL_NO_REPLY_EMAIL' => 'services@youboat.com',
    'MAIL_NO_REPLY_NAME' => 'Services',
    'MAIL_CONTACT_EMAIL' => 'contact@youboat.com',
    'MAIL_CONTACT_NAME' => 'Contact',

    'email' => 'contact@youboat.com',

    'contact_email' => 'contact@youboat.com',

    'emails_bcc' => 'contact@rivamedia.fr',
    'country_manager_email' => 'contact@youboat.com',
    'address' => '',

    'facebook' => 'https://www.facebook.com/YouboatFR',
    'locations' => [
        'regions' => [
            'alsace_champagne-ardenne-lorraine' => [
                'code'=> '44',
                'name'=> 'Alsace-Champagne-Ardenne-Lorraine',
                'county' => [
                    'Ardennes',
                    'Aube',
                    'Marne',
                    'Haute-Marne',
                    'Meurthe-et-Moselle',
                    'Meuse',
                    'Moselle',
                    'Bas-Rhin',
                    'Haut-Rhin',
                    'Vosges',
                ]
            ],

            'aquitaine_limousin-poitou-charentes' => [
                'code'=> '75',
                'name'=> 'Aquitaine-Limousin-Poitou-Charentes',
                'county' => [
                    'Charente',
                    'Charente-Maritime',
                    'Corrèze',
                    'Creuse',
                    'Dordogne',
                    'Gironde',
                    'Landes',
                    'Lot-et-Garonne',
                    'Pyrénées-Atlantiques',
                    'Deux-Sèvres',
                    'Vienne',
                    'Haute-Vienne'
                ]
            ],

            'auvergne_rhone-alpes' => [
                'code'=> '84',
                'name'=> 'Auvergne-Rhône-Alpes',
                'county' => [
                    'Ain',
                    'Allier',
                    'Ardèche',
                    'Cantal',
                    'Drôme',
                    'Isère',
                    'Loire',
                    'Haute-Loire',
                    'Puy-de-Dôme',
                    'Rhône',
                    'Savoie',
                    'Haute-Savoie'
                ]
            ],

            'bourgogne_franche-comte' => [
                'code'=> '27',
                'name'=> 'Bourgogne-Franche-Comté',
                'county' => [
                    'Côte-d\'or',
                    'Doubs',
                    'Jura',
                    'Nièvre',
                    'Haute-Saône',
                    'Saône-et-Loire',
                    'Yonne',
                    'Territoire de Belfort'
                ]
            ],

            'bretagne' => [
                'code'=> '53',
                'name'=> 'Bretagne',
                'county' => [
                    'Côtes-d\'armor',
                    'Finistère',
                    'Ille-et-Vilaine',
                    'Morbihan'
                ]
            ],

            'centre_val_de_loire' => [
                'code'=> '24',
                'name'=> 'Centre-Val de Loire',
                'county' => [
                    'Cher',
                    'Eure-et-Loir',
                    'Indre',
                    'Indre-et-Loire',
                    'Loir-et-Cher',
                    'Loiret'
                ]
            ],

            'corse' => [
                'code'=> '94',
                'name'=> 'Corse',
                'county' => [
                    'Corse-du-Sud',
                    'Haute-Corse'
                ]
            ],

            'guadeloupe' => [
                'code'=> '1',
                'name'=> 'Guadeloupe',
                'county' => [
                    'Guadeloupe'
                ]
            ],

            'guyane' => [
                'code'=> '3',
                'name'=> 'Guyane',
                'county' => [
                    'Guyane'
                ]
            ],

            'ile_de-france' => [
                'code'=> '11',
                'name'=> 'Ile-de-France',
                'county' => [
                    'Paris',
                    'Seine-et-Marne',
                    'Yvelines',
                    'Essonne',
                    'Hauts-de-Seine',
                    'Seine-Saint-Denis',
                    'Val-de-Marne',
                    'Val-d\'oise'
              ]
            ],

            'la réunion,' => [
              'code'=> '4',
              'name'=> 'La Réunion',
              'county' => [
                'La Réunion'
              ]
            ],

            'languedoc_roussillon-midi-pyrénées' => [
              'code'=> '76',
              'name'=> 'Languedoc-Roussillon-Midi-Pyrénées',
              'county' => [
                'Ariège',
                'Aude',
                'Aveyron',
                'Gard',
                'Haute-Garonne',
                'Gers',
                'Hérault',
                'Lot',
                'Lozère',
                'Hautes-Pyrénées',
                'Pyrénées-Orientales',
                'Tarn',
                'Tarn-et-Garonne'
              ]
            ],

            'martinique' => [
              'code'=> '2',
              'name'=> 'Martinique',
              'county' => [
                'Martinique'
              ]
            ],

            'mayotte' => [
              'code'=> '6',
              'name'=> 'Mayotte',
              'county' => [
                'Mayotte'
              ]
            ],

            'nord_pas-de-calais-picardie' => [
              'code'=> '32',
              'name'=> 'Nord-Pas-de-Calais-Picardie',
              'county' => [
                'Aisne',
                'Nord',
                'Oise',
                'Pas-de-Calais',
                'Somme'
              ]
            ],

            'normandie' => [
              'code'=> '28',
              'name'=> 'Normandie',
              'county' => [
                'Calvados',
                'Eure',
                'Manche',
                'Orne',
                'Seine-Maritime'
              ]
            ],

            'pays_de_la_loire' => [
              'code'=> '52',
              'name'=> 'Pays de la Loire',
              'county' => [
                'Loire-Atlantique',
                'Maine-et-Loire',
                'Mayenne',
                'Sarthe',
                'Vendée'
              ]
            ],

            'provence_alpes-cote_d_azur' => [
              'code'=> '93',
              'name'=> 'Provence-Alpes-Côte d\'Azur',
              'county' => [
                    'Alpes-de-Haute-Provence',
                    'Hautes-Alpes',
                    'Alpes-Maritimes',
                    'Bouches-du-Rhône',
                    'Var',
                    'Vaucluse'
                ]
            ],
        ],
        'counties' => [
            '1' => 'Ain',
            '2' => 'Aisne',
            '3' => 'Allier',
            '4' => 'Alpes-de-Haute-Provence',
            '6' => 'Alpes-Maritimes',
            '7' => 'Ardèche',
            '8' => 'Ardennes',
            '9' => 'Ariège',
            '10' => 'Aube',
            '11' => 'Aude',
            '12' => 'Aveyron',
            '67' => 'Bas-Rhin',
            '13' => 'Bouches-du-Rhône',
            '14' => 'Calvados',
            '15' => 'Cantal',
            '16' => 'Charente',
            '17' => 'Charente-Maritime',
            '18' => 'Cher',
            '19' => 'Corrèze',
            '2a' => 'Corse-du-sud',
            '21' => 'Côte-d\'or',
            '22' => 'Côtes-d\'armor',
            '23' => 'Creuse',
            '79' => 'Deux-Sèvres',
            '24' => 'Dordogne',
            '25' => 'Doubs',
            '26' => 'Drôme',
            '91' => 'Essonne',
            '27' => 'Eure',
            '28' => 'Eure-et-Loir',
            '29' => 'Finistère',
            '30' => 'Gard',
            '32' => 'Gers',
            '33' => 'Gironde',
            '971' => 'Guadeloupe',
            '973' => 'Guyane',
            '68' => 'Haut-Rhin',
            '2b' => 'Haute-corse',
            '31' => 'Haute-Garonne',
            '43' => 'Haute-Loire',
            '52' => 'Haute-Marne',
            '70' => 'Haute-Saône',
            '74' => 'Haute-Savoie',
            '87' => 'Haute-Vienne',
            '5' => 'Hautes-Alpes',
            '65' => 'Hautes-Pyrénées',
            '92' => 'Hauts-de-Seine',
            '34' => 'Hérault',
            '35' => 'Ile-et-Vilaine',
            '36' => 'Indre',
            '37' => 'Indre-et-Loire',
            '38' => 'Isère',
            '39' => 'Jura',
            '40' => 'Landes',
            '41' => 'Loir-et-Cher',
            '42' => 'Loire',
            '44' => 'Loire-Atlantique',
            '45' => 'Loiret',
            '46' => 'Lot',
            '47' => 'Lot-et-Garonne',
            '48' => 'Lozère',
            '49' => 'Maine-et-Loire',
            '50' => 'Manche',
            '51' => 'Marne',
            '972' => 'Martinique',
            '53' => 'Mayenne',
            '976' => 'Mayotte',
            '54' => 'Meurthe-et-Moselle',
            '55' => 'Meuse',
            '56' => 'Morbihan',
            '57' => 'Moselle',
            '58' => 'Nièvre',
            '59' => 'Nord',
            '60' => 'Oise',
            '61' => 'Orne',
            '75' => 'Paris',
            '62' => 'Pas-de-Calais',
            '63' => 'Puy-de-Dôme',
            '64' => 'Pyrénées-Atlantiques',
            '66' => 'Pyrénées-Orientales',
            '974' => 'Réunion',
            '69' => 'Rhône',
            '71' => 'Saône-et-Loire',
            '72' => 'Sarthe',
            '73' => 'Savoie',
            '77' => 'Seine-et-Marne',
            '76' => 'Seine-Maritime',
            '93' => 'Seine-Saint-Denis',
            '80' => 'Somme',
            '81' => 'Tarn',
            '82' => 'Tarn-et-Garonne',
            '90' => 'Territoire de Belfort',
            '94' => 'Val-de-Marne',
            '95' => 'Val-d\'oise',
            '83' => 'Var',
            '84' => 'Vaucluse',
            '85' => 'Vendée',
            '86' => 'Vienne',
            '88' => 'Vosges',
            '89' => 'Yonne',
            '78' => 'Yvelines',
        ],
    ],
    'stripe' => [
        'plan_pay_url' => 'https://fr.youboat.com/pay/',
        'charge_description' => 'Offre illimitée FR',
        'charge_amount' => 29,
        'pay_currency' => 'eur',
        'pay_locale' => 'fr',
    ]
];

$be = [
    'locale' => 'nl',
    'currency' => '€',
    'country_code' => 'BE',
    'ua' => 'UA-22390730-34',
    'robots_index' => 'noindex, nofollow',
    'ad_banners' => true,
    'recaptcha' => true,
    'website_name' => 'Youboat BE',
    'website_url' => '',
    'tagline' => 'Bateau occasion, voilier, semi-rigide. Vente bateau et accastillage',
    'default_metas_title' => 'Bateau occasion, voilier, semi-rigide. Vente bateau et accastillage - Youboat BE',
    'default_metas_description' => "Youboat propose plus de 18.000 annonces de bateau, voilier, semi-rigides, moteurs et accessoires occasion et neuf. Achat, vente et location de vedettes habitables, open, day cruiser et même des moteurs marins, accessoires et place de port. Annonces de location de bateau. Publication d'actualités nautique pour bien acheter son bateau.",
    'default_metas_keywords' => 'Bateaux à moteur, bateaux à voile, pneumatiques rigides, semi-rigides, bateaux de tourisme, bateaux de pêche, croiseur de jour, open flybridge',
    'phone' => '+33(2)38.75.99.41',

    'MAIL_NO_REPLY_EMAIL' => 'services@youboat.com',
    'MAIL_NO_REPLY_NAME' => 'Services',
    'MAIL_CONTACT_EMAIL' => 'contact@youboat.com',
    'MAIL_CONTACT_NAME' => 'Contact',

    'email' => 'contact@youboat.com',

    'contact_email' => 'contact@youboat.com',

    'emails_bcc' => 'contact@rivamedia.fr',
    'country_manager_email' => 'contact@youboat.com',
    'address' => '',

    'facebook' => 'https://www.facebook.com/YouboatBE',
    'locations' => [
        'regions' => [
            'bruxelles' => [
                'name'=> 'Bruxelles',
                'county' => [
                    'Bruxelles',
                ]
            ],
            'flandres' => [
                'name'=> 'Flandres',
                'county' => [
                    'Flandre-Occidentale',
                    'Flandre-Orientale',
                    'Anvers',
                    'Limbourg',
                    'Brabant Flamand',
                ]
            ],
            'wallonie' => [
                'name'=> 'Wallonie',
                'county' => [
                    'Brabant Wallon',
                    'Hainaut',
                    'Liège',
                    'Namur',
                    'Luxembourg',
                ]
            ],
        ],
        /* code INS */
        'counties' => [
            '4000' => 'Bruxelles',
            '30000' => 'Flandre-Occidentale',
            '40000' => 'Flandre-Orientale',
            '10000' => 'Anvers',
            '70000' => 'Limbourg',
            '20001' => 'Brabant Flamand',
            '20002' => 'Brabant Wallon',
            '50000' => 'Hainaut',
            '60000' => 'Liège',
            '90000' => 'Namur',
            '80000' => 'Luxembourg',
        ],
    ],
    'stripe' => [
        'plan_pay_url' => 'https://www.youboat.be/pay/',
        'charge_description' => 'Offre illimitée BE',
        'charge_amount' => 29,
        'pay_currency' => 'eur',
        'pay_locale' => 'be',
    ]
];

$nl = [
    'locale' => 'nl',
    'currency' => '€',
    'country_code' => 'NL',
    'ua' => 'UA-22390730-34',
    'robots_index' => 'noindex, nofollow',
    'ad_banners' => true,
    'recaptcha' => true,
    'website_name' => 'Youboat NL',
    'website_url' => '',
    'tagline' => 'Gebruikte boot, zeilboot, semi-rigide. boot verkoop en hulpstukken',
    'default_metas_title' => 'Gebruikte boot, zeilboot, semi-rigide. boot te koop en hulpstukken - Youboat NL',
    'default_metas_description' => "Youboat biedt meer dan 18.000 boot advertenties, zeilboot, semi-rigide, motoren en accessoires en nieuwe kans. Aankoop, verkoop en verhuur van sterren bewoonbaar, open, dag cruiser en zelfs scheepsmotoren, accessoires en ligplaats. huurboot advertenties. Publicatie van nieuws voor waterput kopen zijn boot.",
    'default_metas_keywords' => 'Motorboten, zeilboten, rigid inflatable, semi-rigide, toeristische boten, vissersboten, dagcruiser open flybridge',
    'phone' => '+33(2)38.75.99.41',

    'MAIL_NO_REPLY_EMAIL' => 'services@youboat.com',
    'MAIL_NO_REPLY_NAME' => 'Services',
    'MAIL_CONTACT_EMAIL' => 'contact@youboat.com',
    'MAIL_CONTACT_NAME' => 'Contact',

    'email' => 'contact@youboat.com',

    'contact_email' => 'contact@youboat.com',

    'emails_bcc' => 'contact@rivamedia.fr',
    'country_manager_email' => 'contact@youboat.com',
    'address' => '',

    'facebook' => 'https://www.facebook.com/YouboatNL',
    'locations' => [
        'regions' => [
        ],
        /* code INS */
        'counties' => [
        ],
    ],
    'stripe' => [
        'plan_pay_url' => 'https://www.youboat.nl/pay/',
        'charge_description' => 'Offre illimitée NL',
        'charge_amount' => 29,
        'pay_currency' => 'eur',
        'pay_locale' => 'nl',
    ]
];

$es = [
    'locale' => 'es',
    'currency' => '€',
    'country_code' => 'ES',
    'ua' => 'UA-22390730-34',
    'robots_index' => 'noindex, nofollow',
    'ad_banners' => true,
    'recaptcha' => true,
    'website_name' => 'Youboat ES',
    'website_url' => '',
    'tagline' => 'Bateau occasion, voilier, semi-rigide. Vente bateau et accastillage',
    'default_metas_title' => 'Bateau occasion, voilier, semi-rigide. Vente bateau et accastillage - Youboat ES',
    'default_metas_description' => "Youboat propose plus de 18.000 annonces de bateau, voilier, semi-rigides, moteurs et accessoires occasion et neuf. Achat, vente et location de vedettes habitables, open, day cruiser et même des moteurs marins, accessoires et place de port. Annonces de location de bateau. Publication d'actualités nautique pour bien acheter son bateau.",
    'default_metas_keywords' => 'Bateaux à moteur, bateaux à voile, pneumatiques rigides, semi-rigides, bateaux de tourisme, bateaux de pêche, croiseur de jour, open flybridge',
    'phone' => '+33(2)38.75.99.41',

    'MAIL_NO_REPLY_EMAIL' => 'services@youboat.com',
    'MAIL_NO_REPLY_NAME' => 'Services',
    'MAIL_CONTACT_EMAIL' => 'contact@youboat.com',
    'MAIL_CONTACT_NAME' => 'Contact',

    'email' => 'contact@youboat.com',

    'contact_email' => 'contact@youboat.com',

    'emails_bcc' => 'contact@rivamedia.fr',
    'country_manager_email' => 'contact@youboat.com',
    'address' => '',

    'facebook' => 'https://www.facebook.com/YouboatES',
    'locations' => [
        'regions' => [

        ],
        /* code INS */
        'counties' => [

        ],
    ],
    'stripe' => [
        'plan_pay_url' => 'https://www.youboat.es/pay/',
        'charge_description' => 'Offre illimitée ES',
        'charge_amount' => 29,
        'pay_currency' => 'eur',
        'pay_locale' => 'es',
    ]
];

$lu = [
    'locale' => 'lu',
    'currency' => '€',
    'country_code' => 'LU',
    'ua' => 'UA-22390730-34',
    'robots_index' => 'noindex, nofollow',
    'ad_banners' => true,
    'recaptcha' => true,
    'website_name' => 'Youboat LU',
    'website_url' => '',
    'tagline' => 'Bateau occasion, voilier, semi-rigide. Vente bateau et accastillage',
    'default_metas_title' => 'Bateau occasion, voilier, semi-rigide. Vente bateau et accastillage - Youboat LU',
    'default_metas_description' => "Youboat propose plus de 18.000 annonces de bateau, voilier, semi-rigides, moteurs et accessoires occasion et neuf. Achat, vente et location de vedettes habitables, open, day cruiser et même des moteurs marins, accessoires et place de port. Annonces de location de bateau. Publication d'actualités nautique pour bien acheter son bateau.",
    'default_metas_keywords' => 'Bateaux à moteur, bateaux à voile, pneumatiques rigides, semi-rigides, bateaux de tourisme, bateaux de pêche, croiseur de jour, open flybridge',
    'phone' => '+33(2)38.75.99.41',

    'MAIL_NO_REPLY_EMAIL' => 'services@youboat.com',
    'MAIL_NO_REPLY_NAME' => 'Services',
    'MAIL_CONTACT_EMAIL' => 'contact@youboat.com',
    'MAIL_CONTACT_NAME' => 'Contact',

    'email' => 'contact@youboat.com',

    'contact_email' => 'contact@youboat.com',

    'emails_bcc' => 'contact@rivamedia.fr',
    'country_manager_email' => 'contact@youboat.com',
    'address' => '',

    'facebook' => 'https://www.facebook.com/YouboatLU',
    'locations' => [
        'regions' => [

        ],
        /* code INS */
        'counties' => [

        ],
    ],
    'stripe' => [
        'plan_pay_url' => 'https://www.youboat.lu/pay/',
        'charge_description' => 'Offre illimitée LU',
        'charge_amount' => 29,
        'pay_currency' => 'eur',
        'pay_locale' => 'lu',
    ]
];

return [
    'uk'  => $default,
    'en'  => $default,
    'es'  => array_merge($default, $es),
    'fr'  => array_merge($default, $fr),
	'lu'  => array_merge($default, $lu),
    'be'  => array_merge($default, $be),
    'nl'  => array_merge($default, $nl),
    'gb'  => $default,
    'dev'  => $default
];
