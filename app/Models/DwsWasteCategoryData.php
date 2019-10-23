<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 23 Oct 2019 11:36:54 +0700.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class DwsWasteCategoryData
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property float $price
 * @property string $golongan
 * @property string $img_path
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property int $created_by
 * @property int $updated_by
 * @property \Carbon\Carbon $updated_at
 *
 * @property \App\Models\AdminUser $admin_user
 * @property \Illuminate\Database\Eloquent\Collection $dws_waste_category_images
 * @property \Illuminate\Database\Eloquent\Collection $dws_wastes
 *
 * @package App\Models
 */
class DwsWasteCategoryData extends Eloquent
{
	protected $casts = [
		'price' => 'float',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $fillable = [
		'code',
		'name',
		'price',
		'golongan',
		'img_path',
		'description',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at'
	];

    public function createdBy()
    {
        return $this->belongsTo(\App\Models\AdminUser::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(\App\Models\AdminUser::class, 'updated_by');
    }

	public function dws_waste_category_images()
	{
		return $this->hasMany(\App\Models\DwsWasteCategoryImage::class, 'dws_waste_category_id');
	}

	public function dws_wastes()
	{
		return $this->hasMany(\App\Models\DwsWaste::class, 'dws_waste_category_datas_id');
	}
}
