<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 07 Feb 2019 07:27:19 +0000.
 */

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use SMartins\PassportMultiauth\HasMultiAuthApiTokens;

/**
 * Class WasteCollector
 * 
 * @property int $id
 * @property string $email
 * @property string $password
 * @property string $first_name
 * @property string $last_name
 * @property string $identity_number
 * @property string $phone
 * @property string $address
 * @property float $point
 * @property string $img_path
 * @property int $status_id
 * @property int $created_by
 * @property int $company_id
 * @property int $on_demand_status
 * @property \Carbon\Carbon $created_at
 * @property int $updated_by
 * @property \Carbon\Carbon $updated_at
 *
 * @property \App\Models\Status $status
 * @property \App\Models\Company $company
 * @property \App\Models\AdminUser $createdBy
 * @property \App\Models\AdminUser $updatedBy
 * @property \Illuminate\Database\Eloquent\Collection $transaction_headers
 * @property \Illuminate\Database\Eloquent\Collection $waste_banks
 *
 * @package App\Models
 */
class WasteCollector extends Authenticatable
{
	use Notifiable, HasMultiAuthApiTokens;

    public function findForPassport($username)
    {
        return $this->where('phone', $username)->first();
    }

	protected $casts = [
		'point' => 'float',
		'status_id' => 'int',
		'on_demand_status' => 'int',
		'created_by' => 'int',
		'updated_by' => 'int'
	];

	protected $hidden = [
		'password'
	];

	protected $fillable = [
		'email',
		'password',
		'first_name',
		'last_name',
		'identity_number',
		'phone',
		'address',
		'point',
		'img_path',
		'status_id',
		'on_demand_status',
		'created_by',
		'updated_by'
	];

    public function status()
    {
        return $this->belongsTo(\App\Models\Status::class);
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

	public function transaction_headers()
	{
		return $this->hasMany(\App\Models\TransactionHeader::class);
	}

	public function waste_banks()
	{
		return $this->belongsToMany(\App\Models\WasteBank::class, 'waste_collector_waste_banks')
					->withPivot('id');
	}
}
