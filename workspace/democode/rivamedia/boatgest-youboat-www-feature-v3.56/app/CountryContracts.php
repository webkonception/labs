<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Laraveldaily\Quickadmin\Observers\UserActionsObserver;

use Carbon\Carbon; 

use Illuminate\Database\Eloquent\SoftDeletes;

class CountryContracts extends Model {

    use SoftDeletes;

    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */
    protected $dates = ['deleted_at'];

    protected $table    = 'countrycontracts';
    
    protected $fillable = [
        'user_id',
        'dealerscaracts_id',
        'commercialscaracts_id',
        'reference',
        'description',
        'countries_ids',
        'amount',
        'start_date',
        'end_date',
        'status'
    ];
    
    //public static $type = ["bronze" => "bronze", "silver" => "silver", "gold" => "gold", "platinium" => "platinium", "custom" => "custom"];
    public static $status = ["inactive" => "inactive", "active" => "active"];

    public static function boot()
    {
        parent::boot();

        CountryContracts::observe(new UserActionsObserver);
    }
    
    public function dealerscaracts()
    {
        return $this->hasOne('App\DealersCaracts', 'id', 'dealerscaracts_id');
    }


    
    /**
     * Set attribute to date format
     * @param $input
     */
    public function setStartDateAttribute($input)
    {
        $this->attributes['start_date'] = Carbon::createFromFormat(config('quickadmin.date_format'), $input)->format('Y-m-d');
    }

    /**
     * Get attribute from date format
     * @param $input
     *
     * @return string
     */
    public function getStartDateAttribute($input)
    {
        return Carbon::createFromFormat('Y-m-d', $input)->format(config('quickadmin.date_format'));
    }

    /**
     * Set attribute to date format
     * @param $input
     */
    public function setEndDateAttribute($input)
    {
        $this->attributes['end_date'] = Carbon::createFromFormat(config('quickadmin.date_format'), $input)->format('Y-m-d');
    }

    /**
     * Get attribute from date format
     * @param $input
     *
     * @return string
     */
    public function getEndDateAttribute($input)
    {
        return Carbon::createFromFormat('Y-m-d', $input)->format(config('quickadmin.date_format'));
    }
}