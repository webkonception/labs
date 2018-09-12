<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class ManufacturersEngines extends Model {

    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */
    protected $dates = ['deleted_at'];

    protected $table    = 'manufacturersengines';
    
    protected $fillable = [
        'name',
        'rewrite_url',
        'equivalent',
        'referrer',
        'description',
        'position'
    ];
    

    public static function boot()
    {
        parent::boot();
    }

}