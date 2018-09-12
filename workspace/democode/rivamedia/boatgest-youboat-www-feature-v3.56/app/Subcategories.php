<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;


use Illuminate\Database\Eloquent\SoftDeletes;

class Subcategories extends Model {

    use SoftDeletes;

    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */
    protected $dates = ['deleted_at'];

    protected $table    = 'subcategories';
    
    protected $fillable = [
        'categories_id',
        'name',
        'rewrite_url',
        'description',
        'position'
    ];
    

    public static function boot()
    {
        parent::boot();

        Subcategories::observe(new UserActionsObserver);
    }
    
    public function categories()
    {
        return $this->hasOne('App\Categories', 'id', 'categories_id');
    }

}