<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Newsletter extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $table    = 'newsletter';

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
