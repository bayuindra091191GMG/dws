<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 07 Feb 2019 07:28:43 +0000.
 */

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class AdminUser
 * 
 * @property int $id
 * @property int $is_super_admin
 * @property int $role_id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $password
 * @property string $language
 * @property int $waste_bank_id
 * @property int $status_id
 * @property string $image_path
 * @property string $remember_token
 * @property \Carbon\Carbon $email_verified_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\Status $status
 * @property \App\Models\WasteBank $waste_bank
 * @property \App\Models\Role $role
 * @property \Illuminate\Database\Eloquent\Collection $companies
 * @property \Illuminate\Database\Eloquent\Collection $dws_waste_category_datas
 * @property \Illuminate\Database\Eloquent\Collection $dws_wastes
 * @property \Illuminate\Database\Eloquent\Collection $faqs
 * @property \Illuminate\Database\Eloquent\Collection $masaro_waste_category_datas
 * @property \Illuminate\Database\Eloquent\Collection $masaro_wastes
 * @property \Illuminate\Database\Eloquent\Collection $store_addresses
 * @property \Illuminate\Database\Eloquent\Collection $transaction_headers
 * @property \Illuminate\Database\Eloquent\Collection $voucher_categories
 * @property \Illuminate\Database\Eloquent\Collection $vouchers
 * @property \Illuminate\Database\Eloquent\Collection $waste_bank_schedules
 * @property \Illuminate\Database\Eloquent\Collection $waste_banks
 * @property \Illuminate\Database\Eloquent\Collection $waste_collectors
 *
 * @package App\Models
 */
class AdminUser extends Authenticatable
{
    protected $guard = 'admin';

	protected $casts = [
		'is_super_admin' => 'int',
		'role_id' => 'int',
		'waste_bank_id' => 'int',
		'status_id' => 'int'
	];

	protected $dates = [
		'email_verified_at'
	];

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
		'is_super_admin',
		'role_id',
		'first_name',
		'last_name',
		'email',
		'password',
		'language',
		'waste_bank_id',
		'status_id',
		'image_path',
		'remember_token',
		'email_verified_at'
	];

	public function status()
	{
		return $this->belongsTo(\App\Models\Status::class);
	}

	public function waste_bank()
	{
		return $this->belongsTo(\App\Models\WasteBank::class);
	}

	public function role()
	{
		return $this->belongsTo(\App\Models\Role::class);
	}

	public function companies()
	{
		return $this->hasMany(\App\Models\Company::class, 'updated_by');
	}

	public function dws_waste_category_datas()
	{
		return $this->hasMany(\App\Models\DwsWasteCategoryData::class, 'created_by');
	}

	public function dws_wastes()
	{
		return $this->hasMany(\App\Models\DwsWaste::class, 'updated_by');
	}

	public function faqs()
	{
		return $this->hasMany(\App\Models\Faq::class, 'updated_by');
	}

	public function masaro_waste_category_datas()
	{
		return $this->hasMany(\App\Models\MasaroWasteCategoryData::class, 'created_by');
	}

	public function masaro_wastes()
	{
		return $this->hasMany(\App\Models\MasaroWaste::class, 'updated_by');
	}

	public function store_addresses()
	{
		return $this->hasMany(\App\Models\StoreAddress::class, 'updated_by');
	}

	public function transaction_headers()
	{
		return $this->hasMany(\App\Models\TransactionHeader::class, 'updated_by_admin');
	}

	public function voucher_categories()
	{
		return $this->hasMany(\App\Models\VoucherCategory::class, 'updated_by');
	}

	public function vouchers()
	{
		return $this->hasMany(\App\Models\Voucher::class, 'updated_by');
	}

	public function waste_bank_schedules()
	{
		return $this->hasMany(\App\Models\WasteBankSchedule::class, 'updated_by');
	}

	public function waste_banks()
	{
		return $this->hasMany(\App\Models\WasteBank::class, 'updated_by');
	}

	public function waste_collectors()
	{
		return $this->hasMany(\App\Models\WasteCollector::class, 'updated_by');
	}
}
