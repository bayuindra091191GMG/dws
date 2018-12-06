<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 06 Dec 2018 06:52:28 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class RelatedProduct
 * 
 * @property int $id
 * @property int $product_id
 * @property int $related_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\Product $product
 *
 * @package App\Models
 */
class RelatedProduct extends Eloquent
{
	protected $casts = [
		'product_id' => 'int',
		'related_id' => 'int'
	];

	protected $fillable = [
		'product_id',
		'related_id'
	];

	public function product()
	{
		return $this->belongsTo(\App\Models\Product::class, 'related_id');
	}
}
