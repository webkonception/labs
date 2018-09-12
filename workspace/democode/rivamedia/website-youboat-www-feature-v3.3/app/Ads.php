<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class Ads extends Model {

    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */
    protected $dates = ['deleted_at'];

    protected $table    = 'ads';

    protected $fillable = [
        'dealerscaracts_id',
        'country_contracts_ids',
        'adstype_id',
        'categories_ids',
        'subcategories_ids',
        'start_date',
        'end_date',
        'status'
    ];

    public static $status = ['inactive' => 'inactive', 'active' => 'active'];
    

    public static function boot()
    {
        parent::boot();
    }

    /**
     * Set attribute to datetime format
     * @param $input
     */
    public function setStartDateAttribute($input)
    {
        debug($input);
        if (strlen($input) < 11) {
            $input .= ' 00:00:00';
        }
        debug($input);
        $this->attributes['start_date'] = Carbon::createFromFormat(config('quickadmin.date_format') . ' ' . config('quickadmin.time_format'), $input)->format('Y-m-d H:i:s');
    }

    /**
     * Get attribute from datetime format
     * @param $input
     *
     * @return string
     */
    public function getStartDateAttribute($input)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $input)->format(config('quickadmin.date_format') . ' ' . config('quickadmin.time_format'));
    }

    /**
     * Set attribute to datetime format
     * @param $input
     */
    public function setEndDateAttribute($input)
    {
        debug($input);
        if (strlen($input) < 11) {
            $input .= ' 00:00:00';
        }
        debug($input);
        $this->attributes['end_date'] = Carbon::createFromFormat(config('quickadmin.date_format') . ' ' . config('quickadmin.time_format'), $input)->format('Y-m-d H:i:s');
    }

    /**
     * Get attribute from datetime format
     * @param $input
     *
     * @return string
     */
    public function getEndDateAttribute($input)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $input)->format(config('quickadmin.date_format') . ' ' . config('quickadmin.time_format'));
    }


    public function dealerscaracts()
    {
        return $this->hasOne('App\DealersCaracts', 'id', 'dealerscaracts_id');
    }

}