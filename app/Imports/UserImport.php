<?php

namespace App\Imports;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;

class UserImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        $dateTimeNow = Carbon::now('Asia/Jakarta')->toDateTimeString();

        foreach($rows as $row){
            // Create user
            $email = $row[3];
            if(!empty($email)){
                $user = User::where('email', $email)->first();
                if(empty($user) || empty($email)){
                    $newUser = User::create([
                        'first_name'        => $row[1],
                        'last_name'         => $row[2] ?? '',
                        'email'             => $email ?? '',
                        'password'          => Hash::make('dws123'),
                        'email_token'       => base64_encode($email),
                        'phone'             => $row[4] ?? '',
                        'status_id'         => 1,
                        'company_id'        => 1,
                        'routine_pickup'    => 0,
                        'created_at'        => $dateTimeNow,
                        'updated_at'        => $dateTimeNow
                    ]);
                }
            }
        }
    }
}
