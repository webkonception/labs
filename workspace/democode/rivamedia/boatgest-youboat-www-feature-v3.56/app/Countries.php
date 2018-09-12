<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;


use Illuminate\Database\Eloquent\SoftDeletes;

class Countries extends Model {

    use SoftDeletes;

    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */
    protected $dates = ['deleted_at'];

    protected $table    = 'countries';
    
    protected $fillable = [
        'code',
        'name',
        'currency',
        'currency_name',
        'vat',
        'locales'
    ];
    

    public static function boot()
    {
        parent::boot();

        Countries::observe(new UserActionsObserver);
    }

}