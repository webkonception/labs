<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;


use Illuminate\Database\Eloquent\SoftDeletes;

class Categories extends Model {

    use SoftDeletes;

    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */
    protected $dates = ['deleted_at'];

    protected $table    = 'categories';
    
    protected $fillable = [
        'adstypes_id',
        'name',
        'rewrite_url',
        'description',
        'position'
    ];
    

    public static function boot()
    {
        parent::boot();

        Categories::observe(new UserActionsObserver);
    }


    public function adstypes()
    {
        return $this->hasOne('App\AdsTypes', 'id', 'adstypes_id');
    }
    
}