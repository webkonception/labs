<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;


use Illuminate\Database\Eloquent\SoftDeletes;

class Roles extends Model {

    use SoftDeletes;

    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */
    protected $dates = ['deleted_at'];

    protected $table    = 'roles';
    
    protected $fillable = [
          'id',
          'title',
          'description'
    ];
    

    public static function boot()
    {
        parent::boot();

        Roles::observe(new UserActionsObserver);
    }
    
    
    
    
}