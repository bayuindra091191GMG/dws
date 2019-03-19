<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 19 Mar 2019 03:06:19 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class WasteCollectorUserStatus
 * 
 * @property int $id
 * @property int $waste_collector_user_id
 * @property \Carbon\Carbon $date
 * @property int $status_id
 * @property \Carbon\Carbon $created_at
 * @property int $created_by
 * 
 * @property \App\Models\WasteCollectorUser $waste_collector_user
 * @property \App\Models\Status $status
 *
 * @package App\Models
 */
class WasteCollectorUserStatus extends Eloquent
{
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int',
		'waste_collector_user_id' => 'int',
		'status_id' => 'int',
		'created_by' => 'int'
	];

	protected $dates = [
		'date'
	];

	protected $fillable = [
		'waste_collector_user_id',
		'date',
		'status_id',
		'created_by'
	];

	public function waste_collector_user()
	{
		return $this->belongsTo(\App\Models\WasteCollectorUser::class);
	}

	public function status()
	{
		return $this->belongsTo(\App\Models\Status::class);
	}
}
