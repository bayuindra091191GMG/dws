<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 01 Feb 2019 04:36:53 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Voucher
 * 
 * @property int $id
 * @property string $code
 * @property string $description
 * @property int $category_id
 * @property int $product_id
 * @property \Carbon\Carbon $start_date
 * @property \Carbon\Carbon $finish_date
 * @property int $quantity
 * @property int $status_id
 * @property int $required_poin
 * @property int $redeemable
 * @property string $img_path
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property int $created_by
 * @property int $updated_by
 * @property int $company_id
 * 
 * @property \App\Models\VoucherCategory $voucher_category
 * @property \App\Models\AdminUser $createdBy
 * @property \App\Models\AdminUser $updatedBy
 * @property \App\Models\Company $company
 * @property \App\Models\Status $status
 * @property \Illuminate\Database\Eloquent\Collection $users
 *
 * @package App\Models
 */
class Voucher extends Eloquent
{
	protected $casts = [
		'category_id' => 'int',
		'product_id' => 'int',
		'quantity' => 'int',
		'status_id' => 'int',
		'required_poin' => 'int',
		'redeemable' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $dates = [
		'start_date',
		'finish_date'
	];

	protected $fillable = [
		'code',
		'description',
		'category_id',
		'product_id',
		'start_date',
		'finish_date',
		'quantity',
		'status_id',
        'company_id',
		'required_poin',
		'redeemable',
		'img_path',
		'created_by',
		'updated_by'
	];

	public function voucher_category()
	{
		return $this->belongsTo(\App\Models\VoucherCategory::class, 'category_id');
	}

    public function company()
    {
        return $this->belongsTo(\App\Models\Company::class, 'company_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(\App\Models\AdminUser::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(\App\Models\AdminUser::class, 'updated_by');
    }

	public function status()
	{
		return $this->belongsTo(\App\Models\Status::class);
	}

	public function users()
	{
		return $this->belongsToMany(\App\Models\User::class, 'user_vouchers', 'vouchers_id')
					->withPivot('id');
	}
}
