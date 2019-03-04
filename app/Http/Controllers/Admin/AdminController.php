<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use App\Models\WasteCollector;
use App\Notifications\FCMNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.dashboard');
    }

    public function showSetting()
    {
        $configuration = Configuration::find(17);
        return view('admin.setting.setting', compact('configuration'));
    }

    public function saveSetting(Request $request)
    {
        $configuration = Configuration::find(17);

        if($request->input('is_masaro')){
            $configuration->configuration_value = 1;
        }
        else{
            $configuration->configuration_value = 0;
        }

        $configuration->save();

        return redirect()->route('admin.setting');
    }

    public function showWastebankSetting()
    {
        $configuration = Configuration::find(18);
        return view('admin.setting.setting-wastebank-radius', compact('configuration'));
    }

    public function saveWastebankSetting(Request $request)
    {
        $configuration = Configuration::find(18);
        $configuration->configuration_value = $request->input('wastebank_radius');
        $configuration->save();

        return redirect()->route('admin.wastebanks-radius.setting');
    }

    public function saveUserToken(Request $request)
    {
        try{
            if (Auth::check()){
                $user = Auth::guard('admin')->user();

                //Save user deviceID
                FCMNotification::SaveToken($user->id, $request->input('token'), "browser");

                return Response::json(array('success' => 'VALID'));
            }
            return Response::json(array('errors' => 'INVALID'));
        }
        catch(\Exception $ex){
            return Response::json(array('errors' => 'INVALID'));
        }
    }

    public function test(){
//        $collector = WasteCollector::find(1);
//        dd($collector->waste_banks);

        $wasteCollectors = WasteCollector::where('status_id', 1)
            ->whereHas('waste_banks', function($query){
                $query->where('waste_bank_id', 1);
            })->get();

        dd($wasteCollectors);
    }
}
