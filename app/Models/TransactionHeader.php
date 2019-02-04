<?php

/**
 * Created by Reliese Model.
 * Date: Sat, 02 Feb 2019 03:36:37 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class TransactionHeader
 * 
 * @property int $id
 * @property string $transaction_no
 * @property \Carbon\Carbon $date
 * @property int $user_id
 * @property int $transaction_type_id
 * @property float $total_weight
 * @property float $total_price
 * @property int $waste_category_id
 * @property int $status_id
 * @property string $notes
 * @property \Carbon\Carbon $created_at
 * @property int $created_by_admin
 * @property int $created_by_user
 * @property \Carbon\Carbon $updated_at
 * @property int $updated_by_admin
 * @property int $updated_by_user
 * 
 * @property \App\Models\AdminUser $admin_user
 * @property \App\Models\User $user
 * @property \App\Models\Status $status
 * @property \App\Models\TransactionType $transaction_type
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
		'status_id' => 'int',
		'created_by_admin' => 'int',
		'created_by_user' => 'int',
		'updated_by_admin' => 'int',
		'updated_by_user' => 'int'
	];

	protected $dates = [
		'date'
	];

	protected $fillable = [
		'transaction_no',
		'date',
		'user_id',
		'transaction_type_id',
		'total_weight',
		'total_price',
		'waste_category_id',
		'status_id',
		'notes',
		'created_by_admin',
		'created_by_user',
		'updated_by_admin',
		'updated_by_user'
	];

	protected $appends = [
	    'total_weight_string',
        'total_price_string'
    ];

    public function getTotalWeightStringAttribute(){
        return number_format($this->attributes['total_weight'], 0, ",", ".");
    }

    public function getTotalPriceStringAttribute(){
        return number_format($this->attributes['total_price'], 0, ",", ".");
    }


    public function admin_user()
	{
		return $this->belongsTo(\App\Models\AdminUser::class, 'updated_by_admin');
	}

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class);
	}

	public function status()
	{
		return $this->belongsTo(\App\Models\Status::class);
	}

	public function transaction_type()
	{
		return $this->belongsTo(\App\Models\TransactionType::class);
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
