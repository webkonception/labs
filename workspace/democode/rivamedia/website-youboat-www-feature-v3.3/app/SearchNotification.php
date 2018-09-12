<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class SearchNotification extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $table    = 'search_notification';

    protected $fillable = [
        'ci_email',
        'country_code',
        'manufacturers_id',
        'models_id'
    ];


    public static function boot()
    {
        parent::boot();
    }
}
