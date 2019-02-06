<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 06 Feb 2019 08:26:54 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class WasteBankSchedule
 * 
 * @property int $id
 * @property int $waste_bank_id
 * @property string $day
 * @property string $time
 * @property int $dws_waste_category_id
 * @property int $masaro_waste_category_id
 * @property \Carbon\Carbon $created_at
 * @property int $created_by
 * @property \Carbon\Carbon $updated_at
 * @property int $updated_by
 *
 * @property \App\Models\AdminUser $createdBy
 * @property \App\Models\AdminUser $updatedBy
 * @property \App\Models\DwsWasteCategoryData $dws_waste_category_data
 * @property \App\Models\MasaroWasteCategoryData $masaro_waste_category_data
 * @property \App\Models\WasteBank $waste_bank
 *
 * @package App\Models
 */
class WasteBankSchedule extends Eloquent
{
	protected $casts = [
		'waste_bank_id' => 'int',
		'dws_waste_category_id' => 'int',
		'masaro_waste_category_id' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $fillable = [
		'waste_bank_id',
		'day',
		'time',
		'dws_waste_category_id',
		'masaro_waste_category_id',
		'created_by',
		'updated_by'
	];

    public function createdBy()
    {
        return $this->belongsTo(\App\Models\AdminUser::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(\App\Models\AdminUser::class, 'updated_by');
    }

	public function dws_waste_category_data()
	{
		return $this->belongsTo(\App\Models\DwsWasteCategoryData::class, 'dws_waste_category_id');
	}

	public function masaro_waste_category_data()
	{
		return $this->belongsTo(\App\Models\MasaroWasteCategoryData::class, 'masaro_waste_category_id');
	}

	public function waste_bank()
	{
		return $this->belongsTo(\App\Models\WasteBank::class);
	}
}
