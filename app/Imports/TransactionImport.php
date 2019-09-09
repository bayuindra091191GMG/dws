<?php

namespace App\Imports;

use App\libs\Utilities;
use App\Models\Address;
use App\Models\MasaroWasteCategoryData;
use App\Models\TransactionDetail;
use App\Models\TransactionHeader;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;

class TransactionImport implements ToCollection
{

    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        $today = Carbon::today()->format("Ym");
        $dateTimeNow = Carbon::now('Asia/Jakarta')->toDateTimeString();

        $notExists = 0;
        $count = 0;

        foreach($rows as $row){

            // Create user
            $email = trim($row[2]);
            $user = User::where('email', $email)->first();
            if(empty($user)){
                $notExists++;

                $newUser = User::create([
                    'first_name'        => trim($row[1]),
                    'last_name'         => '',
                    'email'             => $email,
                    'password'          => Hash::make('dws123'),
                    'email_token'       => base64_encode($email),
                    'phone'             => '',
                    'status_id'         => 1,
                    'company_id'        => 1,
                    'routine_pickup'    => 0,
                    'created_at'        => $dateTimeNow,
                    'updated_at'        => $dateTimeNow
                ]);

                Address::create([
                    'description'       => 'Link. Serdag Baru RT 04 RW 08 Kel. Kotabumi Kec. Purwakarta Kota Cilegon Banten',
                    'user_id'           => $newUser->id,
                    'primary'           => 1,
                    'province'          => 3,
                    'city'              => 106,
                    'postal_code'       => '42434',
                    'recipient_name'    => $newUser->first_name,
                    'recipient_phone'   => '',
                    'name'              => 'LOKASI',
                    'latitude'          => '-5.987804',
                    'longitude'         => '106.042588',
                    'created_at'        => $dateTimeNow,
                    'notes'             => 'keterangan tambahan'
                ]);

                $userId = $newUser->id;
            }
            else{

                $userId = $user->id;
            }


            // Generate transaction codes
            $prepend = "TRANS/MASARO/". $today;
            $nextNo = Utilities::GetNextTransactionNumber($prepend);
            $code = Utilities::GenerateTransactionNumber($prepend, $nextNo);
            $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[9]);

            $weight = floatval($row[6]) * 1000;
            if($row[3] == '1'){
                $typeId = 2;
            }
            else{
                $typeId = 1;
            }

            $trxHeader = TransactionHeader::create([
                'transaction_no'        => $code,
                'date'                  => $date,
                'user_id'               => $userId,
                'transaction_type_id'   => $typeId,
                'total_weight'          => $weight,
                'total_price'           => 0,
                'waste_category_id'     => 2,
                'waste_bank_id'         => 1,
                'status_id'             => 13,
                'notes'                 => '',
                'created_at'            => $dateTimeNow,
                'updated_at'            => $dateTimeNow,
                'created_by_admin'      => 1,
                'updated_by_admin'      => 1,
                'point_user'            => 0
            ]);

            $wasteCode = trim($row[5]);
            error_log($count. ' '. $wasteCode);
            $masaroWaste = MasaroWasteCategoryData::where('code', $wasteCode)->first();

            TransactionDetail::create([
                'transaction_header_id'     => $trxHeader->id,
                'masaro_category_id'        => $masaroWaste->id,
                'weight'                    => $weight,
                'price'                     => 0
            ]);

            // Update transaction auto number
            Utilities::UpdateTransactionNumber($prepend);
            $count++;
        }

        error_log("NULL: ". $notExists);
    }
}
