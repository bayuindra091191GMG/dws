<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 26 Dec 2018 05:43:16 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class MasaroWasteCategoryData
 * 
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string $description
 * @property string $img_path
 * @property float $price
 * @property \Carbon\Carbon $created_at
 * @property int $created_by
 * @property int $updated_by
 * @property \Carbon\Carbon $updated_at
 *
 * @property \App\Models\AdminUser $createdBy
 * @property \App\Models\AdminUser $updatedBy
 *
 * @package App\Models
 */
class MasaroWasteCategoryData extends Eloquent
{
	protected $casts = [
		'price' => 'float',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $fillable = [
		'name',
        'code',
		'description',
		'img_path',
		'price',
		'created_by',
		'updated_by',
        'created_at',
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
}
