<?php
/**
 * Created by PhpStorm.
 * User: yanse
 * Date: 14-Sep-17
 * Time: 2:38 PM
 */

namespace App\libs;

use App\Models\TransactionNumber;
use Carbon\Carbon;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;


class Utilities
{
    public static function ExceptionLog($ex){
        $logContent = ['id' => 1,
            'description' => $ex];

        $log = new Logger('exception');
        $log->pushHandler(new StreamHandler(storage_path('logs/error.log')), Logger::ALERT);
        $log->info('exception', $logContent);
    }

    public static function CreateProductSlug($string){
        try{
            $string = strtolower($string);
            $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
            $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

            return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
        }catch(\Exception $ex){
//            dd($ex);
            error_log($ex);
        }
    }

    public static function arrayIsUnique($array){
        return array_unique($array) == $array;
    }

    //  Get next incremental number of transaction number
    /**
     * @param $prepend
     * @return int
     * @throws \Exception
     */
    public static function GetNextTransactionNumber($prepend){
        try{
            $nextNo = 1;
            $orderNumber = TransactionNumber::find($prepend);
            if(empty($orderNumber)){
                TransactionNumber::create([
                    'id'        => $prepend,
                    'next_no'   => 1
                ]);
            }
            else{
                $nextNo = $orderNumber->next_no;
            }

            return $nextNo;
        }
        catch (\Exception $ex){
            throw $ex;
        }
    }

    // Update incremental number of transaction number
    /**
     * @param $prepend
     * @throws \Exception
     */
    public static function UpdateTransactionNumber($prepend){
        try{
            $orderNumber = TransactionNumber::find($prepend);
            $orderNumber->next_no++;
            $orderNumber->save();
        }
        catch (\Exception $ex){
            throw $ex;
        }
    }

    // Generate full transaction number
    /**
     * @param $prepend
     * @param $nextNumber
     * @return string
     * @throws \Exception
     */
    public static function GenerateTransactionNumber($prepend, $nextNumber){
        try{
            $modulus = "";
            $nxt = $nextNumber. '';

            switch (strlen($nxt))
            {
                case 1:
                    $modulus = "000000";
                    break;
                case 2:
                    $modulus = "00000";
                    break;
                case 3:
                    $modulus = "0000";
                    break;
                case 4:
                    $modulus = "000";
                    break;
                case 5:
                    $modulus = "00";
                    break;
                case 6:
                    $modulus = "0";
                    break;
            }

            $day = Carbon::today()->format("d");

            return $prepend. $day. "/". $modulus. $nextNumber;
        }
        catch (\Exception $ex){
            throw $ex;
        }
    }

    public static function toFloat($raw){
        $valueStr1 = str_replace('.','', $raw);
        $valueStr2 = str_replace(',', '.', $valueStr1);

        return (double) $valueStr2;
    }

    public static function removeZeroDigitsFromDecimal($number, $decimal_sep)
    {
        $explode_num = explode($decimal_sep, $number);
        if (is_array($explode_num) && isset($explode_num[count($explode_num)-1]) && intval($explode_num[count($explode_num)-1]) === 0) {
            unset($explode_num[count($explode_num)-1]);
            $number = implode($decimal_sep, $explode_num);
        }
        unset($explode_num);
        return (string) $number;
    }
}
