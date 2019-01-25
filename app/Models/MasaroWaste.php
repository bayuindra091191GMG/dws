<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 22 Jan 2019 04:23:09 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class MasaroWaste
 * 
 * @property int $id
 * @property int $masaro_waste_category_datas_id
 * @property string $name
 * @property string $description
 * @property string $other_description
 * @property string $img_path
 * @property \Carbon\Carbon $created_at
 * @property int $created_by
 * @property \Carbon\Carbon $updated_at
 * @property int $updated_by
 *
 * @property \App\Models\AdminUser $createdBy
 * @property \App\Models\AdminUser $updatedBy
 * @property \App\Models\MasaroWasteCategoryData $masaro_waste_category_data
 *
 * @package App\Models
 */
class MasaroWaste extends Eloquent
{
    protected $appends = [
        'img_full_path'
    ];

	protected $casts = [
		'masaro_waste_category_datas_id' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $fillable = [
		'masaro_waste_category_datas_id',
		'name',
		'description',
		'other_description',
		'img_path',
		'created_by',
		'updated_by'
	];

    public function getImgFullPathAttribute(){
        return url('') . '/storage/admin/masaroitem/' . $this->attributes['img_path'];
    }

    public function createdBy()
    {
        return $this->belongsTo(\App\Models\AdminUser::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(\App\Models\AdminUser::class, 'updated_by');
    }

	public function masaro_waste_category_data()
	{
		return $this->belongsTo(\App\Models\MasaroWasteCategoryData::class, 'masaro_waste_category_datas_id');
	}
}
