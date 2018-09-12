<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;

use Illuminate\Database\Eloquent\SoftDeletes;

class AdsCaracts extends Model {

    use SoftDeletes;

    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */
    protected $dates = ['deleted_at'];

    protected $table    = 'gateway_ads_details';

    protected $fillable = [
        'id',
        'ad_referrer',
        'ad_country_code',

        'ad_title',
        'ad_ref',

        'ad_type_cat_url',
        'ad_type',

        'ad_type_cat_name',
        'ad_category',

        'ad_manufacturer_name',
        'ad_manufacturer_url',

        'ad_model_name',
        'ad_model_url',

        'ad_price',
        'ad_price_descr',

        'ad_location',
        'ad_mooring_country',
        'ad_country',
        'ad_region',
        'ad_county',

        'ad_dealer_name',
        'ad_dealer_url',

        'ad_phones',

        'ad_sale',
        'ad_sale_type_condition',
        'ad_sales_status',

        'ad_year_built',

        'ad_width',
        'ad_length',

        'ad_width_meter',
        'ad_length_meter',
        'ad_draft_meter',

        'ad_description',
        'ad_description_caracts_labels',
        'ad_description_caracts_values',

        'ad_specifications',
        'ad_specifications_caracts_labels',
        'ad_specifications_caracts_values',

        'ad_features',
        'ad_features_caracts_categories',
        'ad_features_caracts_values',

        'ad_photo',
        'ad_photos_thumbs',
        'ad_photos',

        'ad_propulsion',
        'ad_nb_engines',

        'ad_pageUrl',

        // @TODO

        'dealerscaracts_id',
        'adstypes_id',
        'categories_ids',
        'subcategories_ids',

        'manufacturers_id',
        'models_id',
        'manufacturersengines_id',
        'modelsengines_id',

        'countries_id',
        'province',
        'region',
        'subregion',
        'city',
        'zip',

        'sell_type',

        'start_date',
        'end_date',
        'status'
    ];

    public static function boot()
    {
        parent::boot();

        AdsCaracts::observe(new UserActionsObserver);
    }

    /*
    public function ads()
    {
        return $this->hasOne('App\AdsCaracts', 'id', 'status');
    }
    */
    
}