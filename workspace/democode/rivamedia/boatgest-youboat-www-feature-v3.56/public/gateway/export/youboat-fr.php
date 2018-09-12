<?php
	define('BDD_USER', 'de96033');
	define('BDD_PWD', 'Gahshe4ohTea');
	define('BDD_SERVER', 'localhost');
	define('BDD_NAME', 'youboat-www');
	
	$db = mysql_connect(BDD_SERVER, BDD_USER, BDD_PWD);
	mysql_select_db(BDD_NAME, $db);
	mysql_query("SET NAMES 'utf8'");
	
	//header("Content-type: text/xml; charset=utf-8");
	
	$xml = '';
	$xml .= '<?xml version="1.0" encoding="UTF-8"?>';
	$xml .= '<ybworld origin="YouboatWorld">';
	
	$idc = 0;
    $sql = "SELECT
				gateway_ads_details.id,
				gateway_ads_details.ad_country_code,
				gateway_ads_details.ad_referrer,
				gateway_ads_details.ad_title,
				gateway_ads_details.ad_ref,
				gateway_ads_details.ad_type_cat_url,
				gateway_ads_details.ad_type,
				gateway_ads_details.ad_type_cat_name,
				gateway_ads_details.ad_category,
				gateway_ads_details.ad_subcategory,
				gateway_ads_details.ad_manufacturer_name,
				gateway_ads_details.ad_manufacturer_url,
				gateway_ads_details.ad_model_name,
				gateway_ads_details.ad_model_url,
				gateway_ads_details.ad_price,
				gateway_ads_details.ad_price_descr,
				gateway_ads_details.ad_location,
				gateway_ads_details.ad_mooring_country,
				gateway_ads_details.with_marina_berth,
				gateway_ads_details.ad_country,
				gateway_ads_details.ad_region,
				gateway_ads_details.ad_county,
				gateway_ads_details.ad_dealer_name,
				gateway_ads_details.ad_dealer_url,
				gateway_ads_details.ad_phones,
				gateway_ads_details.ad_sale,
				gateway_ads_details.ad_sale_type_condition,
				gateway_ads_details.ad_sales_status,
				gateway_ads_details.ad_year_built,
				gateway_ads_details.ad_width,
				gateway_ads_details.ad_length,
				gateway_ads_details.ad_draft_meter,
				gateway_ads_details.ad_width_meter,
				gateway_ads_details.ad_length_meter,
				gateway_ads_details.ad_description,
				gateway_ads_details.ad_description_caracts_labels,
				gateway_ads_details.ad_description_caracts_values,
				gateway_ads_details.ad_specifications,
				gateway_ads_details.ad_specifications_caracts_labels,
				gateway_ads_details.ad_specifications_caracts_values,
				gateway_ads_details.ad_features,
				gateway_ads_details.ad_features_caracts_categories,
				gateway_ads_details.ad_features_caracts_values,
				gateway_ads_details.ad_photo,
				gateway_ads_details.ad_photos_thumbs,
				gateway_ads_details.ad_photos,
				gateway_ads_details.ad_propulsion,
				gateway_ads_details.ad_nb_engines,
				gateway_ads_details.ad_pageUrl,
				gateway_ads_details.dealerscaracts_id,
				gateway_ads_details.adstypes_id,
				gateway_ads_details.categories_ids,
				gateway_ads_details.subcategories_ids,
				gateway_ads_details.manufacturers_id,
				gateway_ads_details.models_id,
				gateway_ads_details.manufacturersengines_id,
				gateway_ads_details.modelsengines_id,
				gateway_ads_details.countries_id,
				gateway_ads_details.province,
				gateway_ads_details.region,
				gateway_ads_details.subregion,
				gateway_ads_details.city,
				gateway_ads_details.zip,
				gateway_ads_details.sell_type,
				gateway_ads_details.start_date,
				gateway_ads_details.end_date,
				gateway_ads_details.status,
				gateway_ads_details.created_at,
				gateway_ads_details.updated_at,
				gateway_ads_details.deleted_at,
				dealerscaracts.firstname,
				dealerscaracts.name,
				dealerscaracts.denomination,
				dealerscaracts.address,
				dealerscaracts.address_more,
				dealerscaracts.zip,
				dealerscaracts.city,
				dealerscaracts.province,
				dealerscaracts.region,
				dealerscaracts.subregion,
				dealerscaracts.phone_1,
				dealerscaracts.phone_mobile,
				dealerscaracts.fax,
				dealerscaracts.emails,
				dealerscaracts.website_url,
				countries.code
			FROM gateway_ads_details
			LEFT JOIN countrycontracts ON gateway_ads_details.dealerscaracts_id=countrycontracts.dealerscaracts_id
			LEFT JOIN dealerscaracts ON dealerscaracts.id=countrycontracts.dealerscaracts_id
			LEFT JOIN countries ON countries.id=dealerscaracts.country_id
			WHERE countrycontracts.status='active'
			AND gateway_ads_details.status='active'
			AND gateway_ads_details.dealerscaracts_id!=0
			AND gateway_ads_details.dealerscaracts_id!=''
			AND gateway_ads_details.adstypes_id!=0
			AND gateway_ads_details.adstypes_id!=''
			AND gateway_ads_details.manufacturers_id!=0
			AND gateway_ads_details.manufacturers_id!=''
			AND gateway_ads_details.models_id!=0
			AND gateway_ads_details.models_id!=''
			AND gateway_ads_details.ad_price>100
			AND gateway_ads_details.ad_price!=''
			AND (countrycontracts.countries_ids LIKE '75;%' OR
				 countrycontracts.countries_ids LIKE '%;75;%' OR
				 countrycontracts.countries_ids LIKE '%;75' OR
				 countrycontracts.countries_ids='75')
			ORDER BY gateway_ads_details.dealerscaracts_id ASC";
	$res = mysql_query($sql) or die ($sql." ".mysql_error());
	while ($val = mysql_fetch_array($res))
	{
		if($idc!=$val['dealerscaracts_id'])
		{
			if($idc!=0){ $xml .= '</adverts></broker>'; }
			$idc = $val['dealerscaracts_id'];
			
			$xml .= '<broker code="'.$idc.'">
						<broker_details>			
							<company_name><![CDATA['.$val['denomination'].']]></company_name>			
							<email><![CDATA['.$val['emails'].']]></email>
							<contact><![CDATA['.$val['firstname'].' '.$val['name'].']]></contact>
							<address><![CDATA['.$val['address'].']]></address>			
							<town><![CDATA['.$val['city'].']]></town>
							<county><![CDATA['.$val['region'].']]></county>
							<country><![CDATA['.$val['code'].']]></country>
							<postcode><![CDATA['.$val['zip'].']]></postcode>
							<tel1><![CDATA['.$val['phone_1'].']]></tel1>
							<tel2><![CDATA['.$val['phone_mobile'].']]></tel2>
							<fax><![CDATA['.$val['fax'].']]></fax>
							<website><![CDATA['.$val['website_url'].']]></website>
						</broker_details>
						<adverts>';
		}
			
		$xml .= '<advert ref="'.$val['id'].'">';
			$xml .= '<advert_media>';
				$photos = explode(';', $val['ad_photos']);
				foreach($photos as $photo){	$xml .= '<media>'.$photo.'</media>'; }
			$xml .= '</advert_media>';
			$xml .= '<advert_features>';
				$xml .= '<boat_type><![CDATA['.$val['ad_type'].']]></boat_type>';
				$xml .= '<boat_category><![CDATA['.$val['ad_category'].']]></boat_category>';
				$xml .= '<new_or_used><![CDATA['.$val['sell_type'].']]></new_or_used>';
				$xml .= '<vessel_lying country="'.$val['ad_country'].'"><![CDATA['.$val['ad_location'].']]></vessel_lying>';
				$xml .= '<asking_price currency="GBP" vat_included="'.$val['ad_price_descr'].'"><![CDATA['.$val['ad_price'].']]></asking_price>';
				$xml .= '<marketing_descs>';
					$xml .= '<marketing_desc language="en"><![CDATA['.$val['ad_description'].']]></marketing_desc>';
				$xml .= '</marketing_descs>';
				$xml .= '<manufacturer><![CDATA['.$val['ad_manufacturer_name'].']]></manufacturer>';
				$xml .= '<model><![CDATA['.$val['ad_model_name'].']]></model>';
				$xml .= '<fulltitle><![CDATA['.$val['ad_title'].']]></fulltitle>';
				$xml .= '<year><![CDATA['.$val['ad_year_built'].']]></year>';
			$xml .= '</advert_features>';
			$xml .= '<boat_features>';
				$xml .= '<dimensions>';
					$xml .= '<item name="beam" unit="metres"><![CDATA['.$val['ad_width_meter'].']]></item>';
					$xml .= '<item name="draft" unit="metres"><![CDATA['.$val['ad_draft_meter'].']]></item>';
					$xml .= '<item name="loa" unit="metres"><![CDATA['.$val['ad_length_meter'].']]></item>';
				$xml .= '</dimensions>';
				$xml .= '<engine>';
					$xml .= '<item name="engine_quantity"><![CDATA['.$val['ad_nb_engines'].']]></item>';
					$xml .= '<item name="propulsion"><![CDATA['.$val['ad_propulsion'].']]></item>';
				$xml .= '</engine>';
			$xml .= '</boat_features>';
		$xml .= '</advert>';
	}
	
	$xml .= '</adverts>
			</broker>';	
    $xml .= '</ybworld>';
	
	$fp = fopen("/home/de96033/htdocs/youboat-www/youboat-www_boatgest/public/gateway/export/file/youboat-fr.xml", 'w+');
	fputs($fp, $xml);
	fclose($fp);
	
	mysql_close();
?>