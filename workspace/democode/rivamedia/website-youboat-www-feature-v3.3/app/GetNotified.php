<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class GetNotified extends Model {

    use SoftDeletes;

    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */
    protected $dates = ['deleted_at'];

    protected $table    = 'getnotified';
    
    protected $fillable = [
        'country_code',
        'name',
        'email'
    ];
    

    public static function boot()
    {
        parent::boot();
    }
    
}