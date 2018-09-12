<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model {

    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */
    protected $dates = ['deleted_at'];

    protected $table    = 'categories';
    
    protected $fillable = [
        'adstypes_id',
        'name',
        'rewrite_url',
        'description',
        'position'
    ];
    

    public static function boot()
    {
        parent::boot();

    }


    public function adstypes()
    {
        return $this->hasOne('App\AdsTypes', 'id', 'adstypes_id');
    }
    
}