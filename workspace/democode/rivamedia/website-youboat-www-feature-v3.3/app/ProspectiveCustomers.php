<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class ProspectiveCustomers extends Model {

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $table    = 'prospective_customers';

    protected $fillable = [
        'ci_firstname',
        'ci_last_name',
        'ci_email',
        'ci_phone',
        'country_code',
        'referrer',
        'ci_description',
        'reference'
    ];


    public static function boot()
    {
        parent::boot();
    }

}