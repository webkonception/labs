<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;


use Illuminate\Database\Eloquent\SoftDeletes;

class ScrappingAdsDetails extends Model {

    use SoftDeletes;

    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */
    protected $dates = ['deleted_at'];

    protected $table    = 'scrapping_ads_details';
    
    protected $fillable = [
        'ad_price_descr',
        'ad_phones',
        'ad_ref',
        'ad_description_full',
        'ad_specifications_full',
        'ad_specifications_caracts_values',
        'ad_manufacturer_url',
        'ad_sale_type_condition',
        'ad_manufacturer_name',
        'ad_title',
        'ad_price',
        'ad_type_cat_url',
        'ad_subcategory_url',
        'ad_sales_status',
        'ad_location',
        'ad_type_cat_name',
        'ad_subcategory_name',
        'ad_photos_thumbs',
        'ad_model_name',
        'ad_description_caracts_values',
        'ad_mooring_country',
        'ad_photo',
        'ad_specifications_caracts',
        'ad_width',
        'ad_sale',
        'ad_year_built',
        'ad_dealer_name',
        'ad_description_caracts',
        'ad_length',
        'ad_photos',
        'ad_dealer_url',
        'ad_description_caracts_labels',
        'ad_pageUrl',
        'ad_model_url',
        'ad_nb_engines',
        'ad_features_caracts',
        'ad_features_caracts_categories',
        'ad_features_full',
        'ad_propulsion'
    ];
    

    public static function boot()
    {
        parent::boot();

        Countries::observe(new UserActionsObserver);
    }

}