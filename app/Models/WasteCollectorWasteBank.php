<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 07 Feb 2019 07:27:45 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class WasteCollectorWasteBank
 * 
 * @property int $id
 * @property int $waste_bank_id
 * @property int $waste_collector_id
 * 
 * @property \App\Models\WasteBank $waste_bank
 * @property \App\Models\WasteCollector $waste_collector
 *
 * @package App\Models
 */
class WasteCollectorWasteBank extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'waste_bank_id' => 'int',
		'waste_collector_id' => 'int'
	];

	protected $fillable = [
		'waste_bank_id',
		'waste_collector_id'
	];

	public function waste_bank()
	{
		return $this->belongsTo(\App\Models\WasteBank::class);
	}

	public function waste_collector()
	{
		return $this->belongsTo(\App\Models\WasteCollector::class);
	}
}
