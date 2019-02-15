<?php

namespace App\Http\Controllers\Frontend;

use App\Mail\EmailVerification;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\FCMNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use GuzzleHttp\Client;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return redirect()->route('admin.dashboard');
        //return view('frontend.home');
    }

    public function testNotif(){
        return view('admin.test-notif');
    }
    public function testNotifSend(){
        $title = "Digital Waste Solution";
        $body = "User Confirm Transaction On Demand";
        $data = array(
            'type_id' => '1',
            'message' => $body,
        );
//        dd($data);
//        $isSuccess = FCMNotification::SendNotification(8, 'apps', $title, $body, $data);
        $isSuccess = FCMNotification::SendNotification(1, 'browser', $title, $body, $data);

//        dd($isSuccess);
        return redirect($isSuccess);
    }
    public function testEmail(){
        try{
            $exitCode = Artisan::call('cache:clear');
            $exitCode2 = Artisan::call('config:clear');

            $user = User::find('9');
            $emailVerify = new EmailVerification($user, '');
            //dd($user);
            Mail::to($user->email)->send($emailVerify);
            return true;
        }
        catch(\Exception $ex){
            return $ex;
        }
    }
    public function getLocation(){
        dd(\Request::ip());
        $asdf = geoip($ip = \Request::ip());
        dd($asdf);
    }

    public function getProvince(){
        $uri = 'https://api.rajaongkir.com/starter/province';
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', 'https://api.rajaongkir.com/starter/province',[
            'query' => ['key' => '49c2d8cab7d32fa5222c6355a07834d4']
        ]);
        $response = $response->getBody()->getContents();
        $currency = (array)json_decode($response);

        return $currency;
    }
}
