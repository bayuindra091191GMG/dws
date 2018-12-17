<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 17 Dec 2018 09:18:51 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class WasteBank
 * 
 * @property int $id
 * @property string $name
 * @property string $latittude
 * @property string $longitude
 * @property int $pic_id
 * @property string $phone
 * @property string $address
 * @property int $city_id
 * @property \Carbon\Carbon $created_at
 * @property int $created_by
 * @property \Carbon\Carbon $updated_at
 * @property int $updated_by
 * 
 * @property \App\Models\City $city
 * @property \App\Models\AdminUser $createdBy
 * @property \App\Models\AdminUser $updatedBy
 *
 * @package App\Models
 */
class WasteBank extends Eloquent
{
	protected $casts = [
		'pic_id' => 'int',
		'city_id' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $fillable = [
		'name',
		'latittude',
		'longitude',
		'pic_id',
		'phone',
		'address',
		'city_id',
		'created_by',
		'updated_by'
	];

	public function city()
	{
		return $this->belongsTo(\App\Models\City::class);
	}

    public function createdBy()
    {
        return $this->belongsTo(\App\Models\AdminUser::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(\App\Models\AdminUser::class, 'updated_by');
    }
}
