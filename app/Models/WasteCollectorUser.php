<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 06 Mar 2019 03:05:31 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class WasteCollectorUser
 * 
 * @property int $id
 * @property int $waste_collector_id
 * @property int $user_id
 * @property \Carbon\Carbon $created_at
 * @property int $created_by
 *
 * @property \App\Models\AdminUser $createdBy
 * @property \App\Models\User $user
 * @property \App\Models\WasteCollector $waste_collector
 *
 * @package App\Models
 */
class WasteCollectorUser extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'waste_collector_id' => 'int',
		'user_id' => 'int',
		'created_by' => 'int'
	];

	protected $fillable = [
		'waste_collector_id',
		'user_id',
		'created_by'
	];

    public function createdBy()
    {
        return $this->belongsTo(\App\Models\AdminUser::class, 'created_by');
    }

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class);
	}

	public function waste_collector()
	{
		return $this->belongsTo(\App\Models\WasteCollector::class);
	}
}
