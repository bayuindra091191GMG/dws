<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 11 Mar 2019 08:44:36 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class FcmTokenCollector
 * 
 * @property int $id
 * @property int $collector_id
 * @property string $token
 * 
 * @property \App\Models\WasteCollector $waste_collector
 *
 * @package App\Models
 */
class FcmTokenCollector extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'collector_id' => 'int'
	];

	protected $hidden = [
		'token'
	];

	protected $fillable = [
		'collector_id',
		'token'
	];

	public function waste_collector()
	{
		return $this->belongsTo(\App\Models\WasteCollector::class, 'collector_id');
	}
}
