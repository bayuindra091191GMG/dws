<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use App\Models\WasteCollector;
use Illuminate\Http\Request;

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
