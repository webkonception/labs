<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class BodCaracts extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $table    = 'bod';

    protected $fillable = [
        'user_id',
        'prospective_customer_id',
        'reference',
        'country_code',

        'adstypes_id',
        'categories_ids',
        'subcategories_ids',
        'manufacturers_id',
        'models_id',

        'min_year_built',
        'max_year_built',
        'min_length',
        'max_length',
        'min_width',
        'max_width',
        'countries_id',
        'regions_id',
        'counties_id',
        'budget',
        'sell_type',
        'description',
        'with_marina_berth',
        'agree_similar',
        'recovery_adstypes_id',
        'recovery_categories_ids',
        'recovery_subcategories_ids',
        'recovery_manufacturers_id',
        'recovery_models_id',
        'recovery_year_built',
        'recovery_description',
        'recovery_budget',
        'ci_firstname',
        'ci_last_name',
        'ci_email',
        'ci_password',
        'ci_phone',
        'ci_zip',
        'ci_city',
        'ci_countries_id',
        'ci_regions_id',
        'ci_counties_id',
        'ci_description',
        'agree_emails',
        'agree_cgv'
    ];


    public static function boot()
    {
        parent::boot();
    }
}
