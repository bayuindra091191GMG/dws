<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 25 Mar 2019 07:35:50 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class WasteBank
 * 
 * @property int $id
 * @property string $name
 * @property string $latitude
 * @property string $longitude
 * @property int $pic_id
 * @property string $phone
 * @property string $address
 * @property int $city_id
 * @property string $open_days
 * @property \Carbon\Carbon $open_hours
 * @property \Carbon\Carbon $closed_hours
 * @property int $waste_category_id
 * @property int $status_id
 * @property \Carbon\Carbon $created_at
 * @property int $created_by
 * @property \Carbon\Carbon $updated_at
 * @property int $updated_by
 *
 * @property \App\Models\City $city
 * @property \App\Models\Status $status
 * @property \App\Models\AdminUser $createdBy
 * @property \App\Models\AdminUser $updatedBy
 * @property \App\Models\AdminUser $pic
 * @property \App\Models\WasteCategory $waste_category
 * @property \Illuminate\Database\Eloquent\Collection $transaction_headers
 * @property \Illuminate\Database\Eloquent\Collection $users
 * @property \Illuminate\Database\Eloquent\Collection $waste_bank_schedules
 * @property \Illuminate\Database\Eloquent\Collection $waste_collectors
 *
 * @package App\Models
 */
class WasteBank extends Eloquent
{
	protected $casts = [
		'pic_id' => 'int',
		'city_id' => 'int',
		'waste_category_id' => 'int',
        'status_id' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $fillable = [
		'name',
		'latitude',
		'longitude',
		'pic_id',
		'phone',
		'address',
		'city_id',
		'open_days',
		'open_hours',
		'closed_hours',
		'waste_category_id',
        'status_id',
		'created_by',
		'updated_by'
	];

	public function city()
	{
		return $this->belongsTo(\App\Models\City::class);
	}

	public function waste_category()
	{
		return $this->belongsTo(\App\Models\WasteCategory::class);
	}

    public function pic()
    {
        return $this->belongsTo(\App\Models\AdminUser::class, 'pic_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(\App\Models\AdminUser::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(\App\Models\AdminUser::class, 'updated_by');
    }

	public function transaction_headers()
	{
		return $this->hasMany(\App\Models\TransactionHeader::class);
	}

	public function users()
	{
		return $this->belongsToMany(\App\Models\User::class, 'user_waste_banks')
					->withPivot('id');
	}

	public function waste_bank_schedules()
	{
		return $this->hasMany(\App\Models\WasteBankSchedule::class);
	}

	public function waste_collectors()
	{
		return $this->belongsToMany(\App\Models\WasteCollector::class, 'waste_collector_waste_banks')
					->withPivot('id');
	}

    public function status()
    {
        return $this->belongsTo(\App\Models\Status::class);
    }
}
