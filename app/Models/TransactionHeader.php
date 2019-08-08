<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 07 Feb 2019 07:30:24 +0000.
 */

namespace App\Models;

use Carbon\Carbon;
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
 * @property int $waste_bank_id
 * @property int $waste_collector_id
 * @property string $notes
 * @property int $status_id
 * @property string $latitude
 * @property string $longitude
 * @property \Carbon\Carbon $created_at
 * @property int $created_by_admin
 * @property int $created_by_user
 * @property \Carbon\Carbon $updated_at
 * @property int $updated_by_admin
 * @property int $updated_by_user
 * @property int $point_user
 * @property int $point_waste_collector
 * @property string $image_path
 *
 * @property \App\Models\AdminUser $admin_user
 * @property \App\Models\User $user
 * @property \App\Models\Status $status
 * @property \App\Models\TransactionType $transaction_type
 * @property \App\Models\WasteBank $waste_bank
 * @property \App\Models\WasteCategory $waste_category
 * @property \App\Models\WasteCollector $waste_collector
 * @property \Illuminate\Database\Eloquent\Collection $point_histories
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
		'waste_bank_id' => 'int',
		'waste_collector_id' => 'int',
		'status_id' => 'int',
		'created_by_admin' => 'int',
		'created_by_user' => 'int',
		'updated_by_admin' => 'int',
		'updated_by_user' => 'int',
        'point_user' => 'int',
        'point_waste_collector' => 'int'
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
		'waste_bank_id',
		'waste_collector_id',
		'notes',
		'status_id',
        'latitude',
        'longitude',
		'created_by_admin',
		'created_by_user',
		'updated_by_admin',
		'updated_by_user',
        'point_user',
        'point_waste_collector',
        'image_path'
	];

    protected $appends = [
        'date_string',
        'total_weight_string',
        'total_weight_kg',
        'total_weight_kg_string',
        'total_price_string'
    ];

    public function getDateStringAttribute(){
        return Carbon::parse($this->attributes['date'])->format('d M Y');
    }

    public function getImagePathAttribute($value){
        if(!empty($value)){
            if($this->attributes['transaction_type_id'] === 1){
                return "https://dws-solusi.net/public/storage/transactions/routine/". $value;
            }
            else{
                return "https://dws-solusi.net/public/storage/transactions/ondemand/". $value;
            }
        }
        else{
            return "";
        }
    }

    public function getTotalWeightStringAttribute(){
        return number_format($this->attributes['total_weight'], 0, ",", ".");
    }

    public function getTotalWeightKgAttribute(){
        return $this->attributes['total_weight'] / 1000;
    }

    public function getTotalWeightKgStringAttribute(){
        return number_format($this->attributes['total_weight'] / 1000, 4, ",", ".");
    }

    public function getTotalPriceStringAttribute(){
        return number_format($this->attributes['total_price'], 4, ",", ".");
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

	public function waste_bank()
	{
		return $this->belongsTo(\App\Models\WasteBank::class);
	}

	public function waste_category()
	{
		return $this->belongsTo(\App\Models\WasteCategory::class);
	}

	public function waste_collector()
	{
		return $this->belongsTo(\App\Models\WasteCollector::class);
	}

	public function point_histories()
	{
		return $this->hasMany(\App\Models\PointHistory::class, 'transaction_id');
	}

	public function transaction_details()
	{
		return $this->hasMany(\App\Models\TransactionDetail::class);
	}
}
