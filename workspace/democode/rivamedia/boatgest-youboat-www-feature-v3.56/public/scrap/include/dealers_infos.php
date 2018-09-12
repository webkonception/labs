<?php
    /*
        $url = 'https://www.rivamedia.fr/runs/XML_YB_FR.php?id=865&key=';
        //$key = 'ybfr';
        $date = date('Ymd');
        $key = sha1('ybfr'.$date);
        $url .= $key;
        echo $url;
    */

//www.seaside.fr
    $dealer_infos['www.seaside.fr']   = [
        'id_client' => 221,
        'country_contract_code' => 'uk',
        'gateaway ' => 'www.seaside.fr',
        'domain' => 'www.seaside.fr',
        'name' => 'SEASIDE',
        'address' => 'Port de plaisance',
        'zip' => '06310',
        'city' => 'BEAULIEU-sur-MER',
        'country' => 'FRANCE',
        'mooring_address' => '',
        'mooring_country' => '',
        'phone' => '+33 (0)4 93 01 14 86',
        'fax' => '+33 (0)4 93 01 36 23',
        'email' => 'mail@seaside.fr',
        'url_prefix_id' => '-a-vendre',
        'xpath' => [
            //['id'=>'ad_ref', 'xpath'=>'//annonces/annonce/id'],

            //['id'=>'ad_phone', 'value' => '+33 (0)4 93 01 14 86'],

            //['id'=>'ad_title', ''],

            ['id'=>'ad_type_cat_name', 'xpath'=>'type'],
            ['id'=>'ad_type_cat_atitle', ''],
            ['id'=>'ad_type_cat_url', 'xpath'=>'type'],

            ['id'=>'ad_subcategory_name', ''],
            ['id'=>'ad_subcategory_atitle', ''],
            ['id'=>'ad_subcategory_url', ''],

            //['id'=>'ad_location', 'xpath'=>'visible'],

            ['id'=>'ad_manufacturer_name', 'xpath'=>'marque'],
            ['id'=>'ad_manufacturer_atitle', ''],
            ['id'=>'ad_manufacturer_url', ''],

            ['id'=>'ad_model_name', 'xpath'=>'modele'],
            ['id'=>'ad_model_atitle', ''],
            ['id'=>'ad_model_url', ''],

            ['id'=>'ad_mooring_country', 'xpath'=>'visible'],

            ['id'=>'ad_width', 'xpath'=>'longueur'],
            ['id'=>'ad_length', 'xpath'=>'largeur'],

            ['id'=>'ad_price', 'xpath'=>'prix'],
            ['id'=>'ad_price_descr', ''],

            ['id'=>'ad_sale', ''],
            ['id'=>'ad_sale_type_condition', ''],
            ['id'=>'ad_sales_status', ''],

            ['id'=>'ad_dealer_name', ''],
            ['id'=>'ad_dealer_url', ''],
            ['id'=>'ad_phones', ''],

            ['id'=>'ad_photos', 'xpath'=>'photo'],
            ['id'=>'ad_photos_thumbs', ''],
            ['id'=>'ad_photo', ''],

            ['id'=>'ad_description_caracts_block', ''],
            ['id'=>'ad_description_full', 'xpath'=>'commentaires'],
            ['id'=>'ad_description_caracts_labels', ''],
            ['id'=>'ad_description_caracts_values', ''],

            ['id'=>'ad_specifications_caracts_block', ''],
            ['id'=>'ad_specifications_full', ''],
            ['id'=>'ad_specifications_caracts', ''],
            ['id'=>'ad_specifications_caracts_values', ''],

            ['id'=>'ad_features_caracts_block', ''],
            ['id'=>'ad_features_full', ''],
            ['id'=>'ad_features_caracts', ''],
            ['id'=>'ad_features_caracts_categories', ''],

            ['id'=>'ad_year_built', 'xpath'=>'annee'],
            ['id'=>'ad_propulsion', 'xpath'=>'moteur_propulsion'],
            ['id'=>'ad_nb_engines', 'xpath'=>'moteur_nb'],
        ]
    ];

//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_ref', 'xpath'=>'//annonces/annonce/id'];
//
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_type_cat_name', 'xpath'=>'//annonces/annonce/type'];
//
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_ref', 'xpath'=>'//*[@class="ad-detail-wrapper"]/div[@class="ad_header_block"]/div[@class="top"]//span[@class="ref"]'];
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_title', 'xpath'=>'//*[@class="ad-detail-wrapper"]/div[@class="ad_header_block"]/div[@class="top"]/h1[@class="title"]'];
//
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_type_cat_name', 'xpath'=>'//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td[contains(.,"Type:")]/following::td[1]|//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td[contains(.,"Category:")]/following::td[1]'];
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_type_cat_atitle', 'xpath'=>'//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td[contains(.,"Type:")]/following::td[1]/a|//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td[contains(.,"Category:")]/following::td[1]/a']; ///@title
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_type_cat_url', 'xpath'=>'//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td[contains(.,"Type:")]/following::td[1]/a/@href|//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td[contains(.,"Category:")]/following::td[1]/a/@href'];
//
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_subcategory_name', 'xpath'=>'//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td[contains(.,"Subcategory:")]/following::td[1]'];
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_subcategory_atitle', 'xpath'=>'//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td[contains(.,"Subcategory:")]/following::td[1]/a']; ///@title
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_subcategory_url',   'xpath'=>'//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td[contains(.,"Subcategory:")]/following::td[1]/a/@href'];
//
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_location', 'xpath'=>'//*[@class="ad-detail-wrapper"]/div[@class="ad_header_block"]/div[@class="top"]//a[@class="location"]'];
//
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_manufacturer_name', 'xpath'=>'//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td[contains(.,"Manufacturer:")]/following::td[@class="label_value"][1]'];
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_manufacturer_atitle', 'xpath'=>'//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td[contains(.,"Manufacturer:")]/following::td[1]/a']; ///@title
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_manufacturer_url', 'xpath'=>'//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td[contains(.,"Manufacturer:")]/following::td[1]/a/@href'];
//
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_model_name',    'xpath'=>'//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td[not(@style="display: none;")]/span[contains(.,"Model:")][1]/following::span[@class="label_value"][1]'];
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_model_atitle',   'xpath'=>'//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td/span[contains(.,"Model:")]/following::span[1]/a']; ///@title
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_model_url', 'xpath'=>'/*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td/span[contains(.,"Model:")]/following::span[1]/a/@href'];
//
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_mooring_country', 'xpath'=>'//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td[contains(.,"Mooring Country:")]/following::td[1]'];
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_width', 'xpath'=>'//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td[contains(.,"Width:")]/following::td[1]'];
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_length', 'xpath'=>'//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td[contains(.,"Length:")]/following::td[1]'];
//
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_price', 'xpath'=>'//*[@class="ad-detail-wrapper"]/div[@class="ad_header_block"]/div[@class="top"]/div[@class="price"]/text()'];
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_price_descr', 'xpath'=>'//*[@class="ad-detail-wrapper"]/div[@class="ad_header_block"]/div[@class="top"]/div[@class="price"]/span[@class="price_descr"]'];
//
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_sale', 'xpath'=>'//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td[not(@style="display: none;")]/span[contains(.,"Sale:")]/following::span[@class="label_value"][1]'];
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_sale_type_condition', 'xpath'=>'//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td[contains(.,"Sale Type:")]/following::td[1]|//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td[contains(.,"Condition:")]/following::td[1]'];
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_sales_status', 'xpath'=>'//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td[contains(.,"Sales status:")]/following::td[1]'];
//
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_dealer_name', 'xpath'=>'//*[@class="ad_info"]//div[@class="item_contact_descr ad_url_min"]//a/@title'];
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_dealer_url', 'xpath'=>'//*[@class="ad_info"]//div[@class="item_contact_descr ad_url_min"]//a/@href'];
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_phones', 'xpath'=>'//*[@class="ad-buttons-block"]//span[@class="phone-number"]'];
//
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_photo', 'xpath'=>'//*[@id="big_photo_link"]/img/@src'];
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_photos', 'xpath'=>'//*[@class="ad_images_block"]/ul/li//a/@data-img'];
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_photos_thumbs', 'xpath'=>'//*[@class="ad_images_block"]//ul[@class="jcarousel-skin-vivapets"]//img/@src'];
//
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_description_caracts_block', 'xpath'=>'//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]'];
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_description_full', 'xpath'=>'//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]//div[contains(@class,"ad_descr")]'];
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_description_caracts_labels', 'xpath'=>'//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td[@class="label"]|//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td/span[@class="label"]'];
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_description_caracts_values', 'xpath'=>'//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td[@class="label_value"]|//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td/span[@class="label_value"]'];
//
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_specifications_caracts_block', 'xpath'=>'//*[@class="ad-detail-wrapper"]/div[@class="ad_info"]/div[@id="ad-block-specs"]//table[@class="other_details"]'];
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_specifications_full', 'xpath'=>'//*[@class="ad-detail-wrapper"]/div[@class="ad_info"]/div[@id="ad-block-specs"]'];
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_specifications_caracts', 'xpath'=>'//*[@class="ad-detail-wrapper"]/div[@class="ad_info"]/div[@id="ad-block-specs"]//table[@class="other_details"]//td[@class="label"]|//*[@class="ad-detail-wrapper"]/div[@class="ad_info"]/div[@id="ad-block-specs"]//table[@class="other_details"]//td/span[@class="label"]'];
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_specifications_caracts_values', 'xpath'=>'//*[@class="ad-detail-wrapper"]/div[@class="ad_info"]/div[@id="ad-block-specs"]//table[@class="other_details"]//td[@class="label_value"]|//*[@class="ad-detail-wrapper"]/div[@class="ad_info"]/div[@id="ad-block-specs"]//table[@class="other_details"]//td/span[@class="label_value"]'];
//
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_features_caracts_block', 'xpath'=>'//*[@class="ad-detail-wrapper"]/div[@class="ad_info"]/div[@id="ad-block-features"]//div[@class="ad-block-content"]'];
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_features_full', 'xpath'=>'//*[@class="ad-detail-wrapper"]/div[@class="ad_info"]/div[@id="ad-block-features"]'];
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_features_caracts', 'xpath'=>'//*[@class="ad-detail-wrapper"]/div[@class="ad_info"]/div[@id="ad-block-features"]//div[@class="ad-block-content"]/ul[@class="multi"]/li'];
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_features_caracts_categories', 'xpath'=>'//*[@class="ad-detail-wrapper"]/div[@class="ad_info"]/div[@id="ad-block-features"]//div[@class="ad-block-content"]/ul[@class="multi"]/li[@class="category"]'];
//
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_year_built', 'xpath'=>'//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td[contains(.,"Year built:")]/following::td[1]'];
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_propulsion', 'xpath'=>'//*[@class="ad-detail-wrapper"]//div[@class="ad_info"]/div[not(@id="ad-block-specs")]//table[@class="other_details"]//td[contains(.,"Propulsion:")]/following::td[1]'];
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_nb_engines', '//annonces/annonce/moteur_nb'];
//
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_ref', 'xpath'=>'//annonces/annonce/id'];
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_title', ''];
//
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_type_cat_name', 'xpath'=>'//annonces/annonce/type'];
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_type_cat_atitle', ''];
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_type_cat_url', ''];
//
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_subcategory_name', ''];
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_subcategory_atitle', ''];
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_subcategory_url', ''];
//
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_location', 'xpath'=>'//annonces/annonce/visible'];
//
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_manufacturer_name', 'xpath'=>'//annonces/annonce/marque'];
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_manufacturer_atitle', ''];
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_manufacturer_url', ''];
//
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_model_name', 'xpath'=>'//annonces/annonce/modele'];
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_model_atitle', ''];
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_model_url', ''];
//
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_mooring_country', ''];
//
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_width', 'xpath'=>'//annonces/annonce/longueur'];
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_length', 'xpath'=>'//annonces/annonce/largeur'];
//
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_price', 'xpath'=>'//annonces/annonce/prix'];
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_price_descr', ''];
//
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_sale', ''];
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_sale_type_condition', ''];
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_sales_status', ''];
//
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_dealer_name', ''];
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_dealer_url', ''];
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_phones', ''];
//
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_photo', 'xpath'=>'//annonces/annonce/photo[0]'];
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_photos', 'xpath'=>'//annonces/annonce/photo'];
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_photos_thumbs', ''];
//
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_description_caracts_block', ''];
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_description_full', '//annonces/annonce/commentaires'];
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_description_caracts_labels', ''];
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_description_caracts_values', ''];
//
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_specifications_caracts_block', ''];
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_specifications_full', ''];
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_specifications_caracts', ''];
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_specifications_caracts_values', ''];
//
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_features_caracts_block', ''];
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_features_full', ''];
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_features_caracts', ''];
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_features_caracts_categories', ''];
//
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_year_built', '//annonces/annonce/annee'];
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_propulsion', '//annonces/annonce/moteur_propulsion'];
//    $processingXpath['www.seaside.fr'][] = ['id'=>'ad_nb_engines', '//annonces/annonce/moteur_nb'];
