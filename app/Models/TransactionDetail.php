<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 08 Jan 2019 07:19:11 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class TransactionDetail
 * 
 * @property int $id
 * @property int $transaction_header_id
 * @property int $dws_category_id
 * @property int $masaro_category_id
 * @property float $weight
 * @property float $price
 * @property string $image_path
 * @property string $note
 * 
 * @property \App\Models\DwsWasteCategoryData $dws_waste_category_data
 * @property \App\Models\MasaroWasteCategoryData $masaro_waste_category_data
 * @property \App\Models\TransactionHeader $transaction_header
 *
 * @package App\Models
 */
class TransactionDetail extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'transaction_header_id' => 'int',
		'dws_category_id' => 'int',
		'masaro_category_id' => 'int',
		'weight' => 'float',
		'price' => 'float'
	];

	protected $fillable = [
		'transaction_header_id',
		'dws_category_id',
		'masaro_category_id',
		'weight',
		'price',
        'image_path',
        'note'
	];

	protected $appends = [
	    'weight_string',
        'weight_kg',
        'weight_kg_string',
        'price_string'
    ];

    public function getWeightStringAttribute(){
        return number_format($this->attributes['weight'], 0, ",", ".");
    }

    public function getWeightKgAttribute(){
        $kg = $this->attributes['weight'] / 1000;
        return $kg;
    }

    public function getWeightKgStringAttribute(){
        $kg = $this->attributes['weight'] / 1000;
        return number_format($kg, 2, ",", ".");
    }

    public function getPriceStringAttribute(){
        return number_format($this->attributes['price'], 0, ",", ".");
    }

    public function getImagePathAttribute($value){
        if(!empty($value)){
            return "https://dws-solusi.net/public/storage/transactions/". $value;
        }
        else{
            return "";
        }
    }

	public function dws_waste_category_data()
	{
		return $this->belongsTo(\App\Models\DwsWasteCategoryData::class, 'dws_category_id');
	}

	public function masaro_waste_category_data()
	{
		return $this->belongsTo(\App\Models\MasaroWasteCategoryData::class, 'masaro_category_id');
	}

	public function transaction_header()
	{
		return $this->belongsTo(\App\Models\TransactionHeader::class);
	}
}
