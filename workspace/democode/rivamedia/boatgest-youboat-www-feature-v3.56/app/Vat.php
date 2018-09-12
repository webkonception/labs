<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;


use Illuminate\Database\Eloquent\SoftDeletes;

class Vat extends Model {

    use SoftDeletes;

    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */
    protected $dates = ['deleted_at'];

    protected $table    = 'vat';
    
    protected $fillable = [
        'country_code',
        'country_name',
        'standard_rate',
        'abbreviation'
    ];
    

    public static function boot()
    {
        parent::boot();

        Vat::observe(new UserActionsObserver);
    }

}