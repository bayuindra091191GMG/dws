<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 23 May 2019 08:02:33 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Voucher
 * 
 * @property int $id
 * @property string $code
 * @property string $description
 * @property int $affiliate_id
 * @property \Carbon\Carbon $start_date
 * @property \Carbon\Carbon $finish_date
 * @property int $quantity
 * @property int $status_id
 * @property int $required_point
 * @property int $redeemable
 * @property string $img_path
 * @property int $category_id
 * @property int $company_id
 * @property string $redeem_code
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property int $created_by
 * @property int $updated_by
 * 
 * @property \App\Models\Affiliate $affiliate
 * @property \App\Models\VoucherCategory $voucher_category
 * @property \App\Models\Company $company
 * @property \App\Models\AdminUser $admin_user
 * @property \App\Models\Status $status
 * @property \Illuminate\Database\Eloquent\Collection $users
 *
 * @package App\Models
 */
class Voucher extends Eloquent
{
	protected $casts = [
		'affiliate_id' => 'int',
		'quantity' => 'int',
		'status_id' => 'int',
		'required_point' => 'int',
		'redeemable' => 'int',
		'category_id' => 'int',
		'company_id' => 'int',
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
		'affiliate_id',
		'start_date',
		'finish_date',
		'quantity',
		'status_id',
		'required_point',
		'redeemable',
		'img_path',
		'category_id',
		'company_id',
		'redeem_code',
		'created_by',
		'updated_by'
	];

	public function affiliate()
	{
		return $this->belongsTo(\App\Models\Affiliate::class);
	}

	public function voucher_category()
	{
		return $this->belongsTo(\App\Models\VoucherCategory::class, 'category_id');
	}

	public function company()
	{
		return $this->belongsTo(\App\Models\Company::class);
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
					->withPivot('id', 'redeem_at', 'is_used', 'used_at');
	}
}
