<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelsEngines extends Model {

    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */
    protected $dates = ['deleted_at'];

    protected $table    = 'modelsengines';
    
    protected $fillable = [
        'manufacturersengines_id',
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