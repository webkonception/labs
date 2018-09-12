<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;


use Illuminate\Database\Eloquent\SoftDeletes;

class AdsTypes extends Model {

    use SoftDeletes;

    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */
    protected $dates = ['deleted_at'];

    protected $table    = 'adstypes';
    
    protected $fillable = [
        'name',
        'rewrite_url',
        'description',
        'position',
        'status'
    ];
    

    public static function boot()
    {
        parent::boot();

        AdsTypes::observe(new UserActionsObserver);
    }
    
    
    
    
}