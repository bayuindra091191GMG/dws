<?php

namespace App\Imports;

use App\libs\Utilities;
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

        foreach($rows as $row){

            // Create user
            $email = $row[2];
            $user = User::where('email', $email)->first();
            if(empty($user)){
                $notExists++;
                continue;
            }

            $userId = $user->id;

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
            error_log($wasteCode);
            $masaroWaste = MasaroWasteCategoryData::where('code', $wasteCode)->first();

            $trxDetail = TransactionDetail::create([
                'transaction_header_id'     => $trxHeader->id,
                'masaro_category_id'        => $masaroWaste->id,
                'weight'                    => $weight,
                'price'                     => 0
            ]);

            // Update transaction auto number
            Utilities::UpdateTransactionNumber($prepend);
        }

        error_log("NULL: ". $notExists);
    }
}
