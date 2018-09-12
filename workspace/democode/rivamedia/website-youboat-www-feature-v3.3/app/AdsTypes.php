<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class AdsTypes extends Model {

    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */
    protected $dates = ['deleted_at'];

    protected $table    = 'adstypes';
    
    protected $fillable = [
        'name',
        'description',
        'position',
        'status'
    ];
    

    public static function boot()
    {
        parent::boot();

    }

}