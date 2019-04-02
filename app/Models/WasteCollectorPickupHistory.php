<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 02 Apr 2019 04:27:04 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class WasteCollectorPickupHistory
 * 
 * @property int $id
 * @property int $waste_collector_user_id
 * @property int $customer_user_id
 * @property int $transaction_header_id
 * @property string $notes
 * @property int $status_id
 * @property \Carbon\Carbon $created_at
 * 
 * @property \App\Models\User $user
 * @property \App\Models\Status $status
 * @property \App\Models\TransactionHeader $transaction_header
 * @property \App\Models\WasteCollector $waste_collector
 *
 * @package App\Models
 */
class WasteCollectorPickupHistory extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'waste_collector_user_id' => 'int',
		'customer_user_id' => 'int',
		'transaction_header_id' => 'int',
		'status_id' => 'int'
	];

	protected $fillable = [
		'waste_collector_user_id',
		'customer_user_id',
		'transaction_header_id',
		'notes',
		'status_id'
	];

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class, 'customer_user_id');
	}

	public function status()
	{
		return $this->belongsTo(\App\Models\Status::class);
	}

	public function transaction_header()
	{
		return $this->belongsTo(\App\Models\TransactionHeader::class);
	}

	public function waste_collector()
	{
		return $this->belongsTo(\App\Models\WasteCollector::class, 'waste_collector_user_id');
	}
}
