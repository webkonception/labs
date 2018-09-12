<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;


use Illuminate\Database\Eloquent\SoftDeletes;

class PrivatesCaracts extends Model {

    use SoftDeletes;

    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */
    protected $dates = ['deleted_at'];

    protected $table    = 'privatescaracts';
    
    protected $fillable = [
        'user_id',
        'firstname',
        'name',
        'denomination',
        'address',
        'address_more',
        'zip',
        'city',
        'province',
        'region',
        'subregion',
        'country_id',
        'phone_1',
        'phone_2',
        'phone_3',
        'phone_mobile',
        'fax',
        'emails',
        'website_url',
        'description',
        'opening_time',
        'twitter',
        'facebook',
        'origin',
        'photo',
        'legal_informations',
        'duns',
        'company_number',
        'siret',
        'ape',
        'vat',
        'rewrite_url',
        'status'
    ];
    

    public static function boot()
    {
        parent::boot();

        DealersCaracts::observe(new UserActionsObserver);
    }

    public function ads()
    {
        return $this->belongsToMany('App\Ads', 'id', 'status');
    }
    
    
}