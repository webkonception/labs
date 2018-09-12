<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class EnquiryForm extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $table    = 'enquiry';

    protected $fillable = [
        'prospective_customer_id',
        'reference',
        'country_code',

        'ad_id',
        'ad_url',
        'ad_title',

        'ci_firstname',
        'ci_last_name',
        'ci_email',
        'ci_phone',
        'ci_countries_id',
        'ci_description'
    ];


    public static function boot()
    {
        parent::boot();
    }
}
