<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class ContactForm extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $table    = 'contact';

    protected $fillable = [
        'country_code',
        'name',
        'email',
        'phone',
        'message',
        'type_request'
    ];


    public static function boot()
    {
        parent::boot();
    }
}
