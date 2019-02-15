<?php
/**
 * Created by PhpStorm.
 * User: YANSEN
 * Date: 2/13/2019
 * Time: 21:26
 */

namespace App\Notifications;


use App\Models\FcmTokenApp;
use App\Models\FcmTokenBrowser;
use GuzzleHttp\Client;


class FCMNotification
{
    public static function SaveToken($userId, $token, $type){
        if($type == 'apps'){
            $fcmToken = FcmTokenApp::create([
                'user_id' => $userId,
                'token' => $token
            ]);
        }
        else{
            $fcmToken = FcmTokenBrowser::create([
                'user_admin_id' => $userId,
                'token' => $token
            ]);
        }
    }

    public static function SendNotification($userId, $type, $title, $body){
        try{
            if($type == 'apps'){
                $user  = FcmTokenApp::where('user_id', $userId)->first();
            }
            else{
                $user  = FcmTokenBrowser::where('user_admin_id', $userId)->first();
            }
//            $token = $user->token;
            $token = "e8dPZUFC6D4:APA91bE5LdSuuQssuT4IhumpZKGBw9QVNI4qXBqZsCULIlU5TOnkI8wCvv-WwTmkB8Qn4pIgf_EXY-177u58pk1s_fG5On2CZ8ZRoPiBE_vrxVQrj4kRYvLyEzzT4wKegEwa2l1ObziA";
            $data = array(
                "to" => $token,
                "notification" => [
                    "title"=> $title,
                    "body"=> $body,
                ]
            );
            $data_string = json_encode($data);
            $client = new Client([
                'base_uri' => "https://fcm.googleapis.com/fcm/send",
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'key=' .env('FCM_SERVER_KEY'),
                ],
            ]);
//            dd($data_string);
            $response = $client->request('POST', 'https://fcm.googleapis.com/fcm/send', [
                'body' => $data_string
            ]);
            $responseJSON = json_decode($response->getBody());
//            dd($responseJSON->results[0]->message_id);
            return $responseJSON->results[0]->message_id;
        }
        catch (\Exception $exception){
//            dd($exception);
//            error_log($exception);
            return "";
        }
    }
}