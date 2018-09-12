<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class CommercialsCaracts extends Model {

    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */
    protected $dates = ['deleted_at'];

    protected $table    = 'commercialscaracts';
    
    protected $fillable = [
        'user_id',
        'firstname',
        'name',
        'address',
        'address_more',
        'zip',
        'city',
        'province',
        'region',
        'subregion',
        'country_id',
        'phone_1',
        'phone_mobile',
        'fax',
        'emails',
        'rewrite_url'
    ];
    

    public static function boot()
    {
        parent::boot();
    }
    
    
    
    
}