<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 17 Dec 2018 09:00:01 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Company
 * 
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $address
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property int $created_by
 * @property \Carbon\Carbon $updated_at
 * @property int $updated_by
 * 
 * @property \App\Models\AdminUser $createdBy
 * @property \App\Models\AdminUser $updatedBy
 *
 * @package App\Models
 */
class Company extends Eloquent
{
	protected $casts = [
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $fillable = [
		'code',
		'name',
		'address',
		'description',
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
}
