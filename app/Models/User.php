<?php

/**
 * Created by Reliese Model.
 * Date: Tue, 08 Jan 2019 07:19:32 +0000.
 */

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

/**
 * Class User
 * 
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $password
 * @property string $image_path
 * @property string $company_name
 * @property string $email_token
 * @property string $phone
 * @property int $status_id
 * @property string $tax_no
 * @property int $company_id
 * @property \Carbon\Carbon $email_verified_at
 * @property string $remember_token
 * @property float $wallet
 * @property float $point
 * @property int $routine_pickup
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\Status $status
 * @property \App\Models\Company $company
 * @property \Illuminate\Database\Eloquent\Collection $addresses
 * @property \Illuminate\Database\Eloquent\Collection $avoredaddresses
 * @property \Illuminate\Database\Eloquent\Collection $orders
 * @property \Illuminate\Database\Eloquent\Collection $point_histories
 * @property \Illuminate\Database\Eloquent\Collection $product_reviews
 * @property \Illuminate\Database\Eloquent\Collection $transaction_headers
 * @property \Illuminate\Database\Eloquent\Collection $user_user_groups
 * @property \Illuminate\Database\Eloquent\Collection $wallet_histories
 * @property \Illuminate\Database\Eloquent\Collection $wishlists
 *
 * @package App\Models
 */
class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

	protected $casts = [
		'status_id' => 'int',
		'waste_category_id' => 'int',
		'wallet' => 'float',
		'point' => 'float',
		'routine_pickup' => 'int'
	];

	protected $dates = [
		'email_verified_at'
	];

	protected $hidden = [
		'password',
		'email_token',
		'remember_token'
	];

	protected $fillable = [
		'first_name',
		'last_name',
		'email',
		'password',
		'image_path',
		'company_name',
		'email_token',
		'phone',
		'status_id',
		'tax_no',
		'company_id',
		'email_verified_at',
		'remember_token',
		'wallet',
		'point',
		'routine_pickup'
	];

	public function status()
	{
		return $this->belongsTo(\App\Models\Status::class);
	}

	public function company()
	{
		return $this->belongsTo(\App\Models\Company::class);
	}

	public function addresses()
	{
		return $this->hasMany(\App\Models\Address::class);
	}

	public function orders()
	{
		return $this->hasMany(\App\Models\Order::class);
	}

	public function point_histories()
	{
		return $this->hasMany(\App\Models\PointHistory::class);
	}

	public function product_reviews()
	{
		return $this->hasMany(\App\Models\ProductReview::class);
	}

	public function transaction_headers()
	{
		return $this->hasMany(\App\Models\TransactionHeader::class);
	}

	public function user_user_groups()
	{
		return $this->hasMany(\App\Models\UserUserGroup::class);
	}

	public function wallet_histories()
	{
		return $this->hasMany(\App\Models\WalletHistory::class);
	}

	public function wishlists()
	{
		return $this->hasMany(\App\Models\Wishlist::class);
	}
}
