<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $table    = 'news';

    protected $fillable = [
        'news_country_code',
        'author_name',
        'author_name',
        'author_email',
        'author_phone',
        'author_url',
        'url',
        'title',
        'intro',
        'description',
        'photo',
        'date',
        'category',
        'rewrite_url',
        'start_date',
        'end_date',
        'status',
    ];


    public static function boot()
    {
        parent::boot();
    }
}

