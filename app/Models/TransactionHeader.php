<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 08 Jan 2019 07:42:15 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class TransactionHeader
 * 
 * @property int $id
 * @property string $transaction_no
 * @property int $user_id
 * @property int $transaction_type_id
 * @property float $total_weight
 * @property float $total_price
 * @property int $waste_category_id
 * @property int $status_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\Status $status
 * @property \App\Models\TransactionType $transaction_type
 * @property \App\Models\User $user
 * @property \App\Models\WasteCategory $waste_category
 * @property \Illuminate\Database\Eloquent\Collection $transaction_details
 *
 * @package App\Models
 */
class TransactionHeader extends Eloquent
{
	protected $casts = [
		'user_id' => 'int',
		'transaction_type_id' => 'int',
		'total_weight' => 'float',
		'total_price' => 'float',
		'waste_category_id' => 'int',
		'status_id' => 'int'
	];

	protected $fillable = [
		'transaction_no',
		'user_id',
		'transaction_type_id',
		'total_weight',
		'total_price',
		'waste_category_id',
		'status_id'
	];

	public function status()
	{
		return $this->belongsTo(\App\Models\Status::class);
	}

	public function transaction_type()
	{
		return $this->belongsTo(\App\Models\TransactionType::class);
	}

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class);
	}

	public function waste_category()
	{
		return $this->belongsTo(\App\Models\WasteCategory::class);
	}

	public function transaction_details()
	{
		return $this->hasMany(\App\Models\TransactionDetail::class);
	}
}
