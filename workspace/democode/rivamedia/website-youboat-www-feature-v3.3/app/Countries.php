<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Countries extends Model {

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
    }

}