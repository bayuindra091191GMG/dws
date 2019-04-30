<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use App\Models\DwsWasteCategoryData;
use App\Models\MasaroWasteCategoryData;
use App\Models\TransactionDetail;
use App\Models\TransactionHeader;
use App\Models\WasteCollector;
use App\Notifications\FCMNotification;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $userAdmin = Auth::guard('admin')->user();
        if($userAdmin->email == "demo@dws-solusi.net"){
            return view('home-demo');
        }

        $start = Carbon::now('Asia/Jakarta')->startOfMonth()->subMonths(2);
        $end = Carbon::now('Asia/Jakarta');

        $isSuperAdmin = $userAdmin->is_super_admin === 1 ? true : false;

        if(!$isSuperAdmin){
            $data = [
                'isSuperAdmin'                  => $isSuperAdmin
            ];

            return view('admin.dashboard')->with($data);
        }

        $transactionDatas = DB::table('transaction_headers')
            ->select(DB::raw('SUM(total_weight) as total_weight, '.
                'SUM(total_price) as total_price, '.
                'SUM(point_user) as total_distributed_point, '.
                'SUM(transaction_type_id = 1) as total_rutin, '.
                'SUM(transaction_type_id = 2) as total_antar_sendiri, '.
                'SUM(transaction_type_id = 3) as total_on_demand, '.
                'YEAR(date) as year, '.
                'MONTHNAME(date) as month'))
            ->whereBetween('date', array($start->toDateTimeString(), $end->toDateTimeString()))
            ->orderBy('date')
            ->groupBy(DB::raw('MONTHNAME(date)'))
            ->groupBy(DB::raw('YEAR(date)'))
            ->get();

        $wasteBankDatas = DB::table('waste_banks')
            ->select(DB::raw('ifnull(count(id),0) as total_count, '.
                'YEAR(created_at) as year, '.
                'MONTHNAME(created_at) as month'))
            ->whereBetween('created_at', array($start->toDateTimeString(), $end->toDateTimeString()))
            ->orderBy('created_at')
            ->groupBy(DB::raw('MONTHNAME(created_at)'))
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->get();

        $wasteCollectorDatas = DB::table('waste_collectors')
            ->select(DB::raw('ifnull(count(id),0) as total_count, '.
                'YEAR(created_at) as year, '.
                'MONTHNAME(created_at) as month'))
            ->whereBetween('created_at', array($start->toDateTimeString(), $end->toDateTimeString()))
            ->orderBy('created_at')
            ->groupBy(DB::raw('MONTHNAME(created_at)'))
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->get();

        $customerDatas = DB::table('users')
            ->select(DB::raw('ifnull(count(id),0) as total_count, '.
                'YEAR(created_at) as year, '.
                'MONTHNAME(created_at) as month'))
            ->whereBetween('created_at', array($start->toDateTimeString(), $end->toDateTimeString()))
            ->orderBy('created_at')
            ->groupBy(DB::raw('MONTHNAME(created_at)'))
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->get();

        $rutinStatusDatas = DB::table('waste_collector_user_statuses')
            ->select(DB::raw('ifnull(sum(status_id = 20),0) as total_empty_hourse, '.
                'ifnull(sum(status_id = 21),0) as total_no_waste, '.
                'YEAR(created_at) as year, '.
                'MONTHNAME(created_at) as month'))
            ->whereBetween('created_at', array($start->toDateTimeString(), $end->toDateTimeString()))
            ->orderBy('created_at')
            ->groupBy(DB::raw('MONTHNAME(created_at)'))
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->get();

        $period = CarbonPeriod::create($start, '1 month', $end);

        $dashboardDatas = collect();

        // Get waste categories
        $dwsCategories = DwsWasteCategoryData::all();
        $masaroCategories = MasaroWasteCategoryData::all();

        // Iterate each month
        foreach ($period as $dt) {
            $month = $dt->format("F");
            $monthInt = $dt->format("m");
            //dd($month);
            $year = $dt->format("Y");
            $totalPrice = 0;
            $totalWeight = 0;
            $totalDistributedPoint = 0;
            $totalTransaction = 0;
            $totalRutin = 0;
            $totalAntarSendiri = 0;
            $totalOnDemand = 0;
            $totalWasteBank = 0;
            $totalWasteCollector = 0;
            $totalCustomer = 0;
            $totalEmptyHouse = 0;
            $totalNoWaste = 0;
            $wasteCategories = collect();

            // Get monthly transaction data
            if($transactionDatas->count() > 0){
                $transactionData = $transactionDatas->where('year', $year)
                    ->where('month', $month)
                    ->first();

                if(!empty($transactionData)){
                    $totalPrice = $transactionData->total_price;
                    $totalWeight = $transactionData->total_weight;
                    $totalDistributedPoint = $transactionData->total_distributed_point;
                    $totalRutin = $transactionData->total_rutin;
                    $totalAntarSendiri = $transactionData->total_antar_sendiri;
                    $totalOnDemand = $transactionData->total_on_demand;

                    $totalTransaction = $totalRutin + $totalAntarSendiri + $totalOnDemand;

                    foreach ($dwsCategories as $dwsCategory){

                        $categoryData = DB::table('transaction_details')
                            ->join('transaction_headers', 'transaction_details.transaction_header_id', '=', 'transaction_headers.id')
                            ->select(DB::raw(
                                'SUM(transaction_details.weight) as total_category_weight, '.
                                'SUM(transaction_details.price) as total_category_price'))
                            ->where('transaction_details.dws_category_id', $dwsCategory->id)
                            ->whereMonth('transaction_headers.date', '=', $monthInt)
                            ->first();

                        $wasteCategoryItem = collect([
                            'name'      => $dwsCategory->name,
                            'weight'    => $categoryData->total_category_weight ?? 0,
                            'price'     => $categoryData->total_category_price ?? 0,
                        ]);

                        $wasteCategories->push($wasteCategoryItem);
                    }

                    foreach ($masaroCategories as $masaroCategory){

                        $categoryData = DB::table('transaction_details')
                            ->join('transaction_headers', 'transaction_details.transaction_header_id', '=', 'transaction_headers.id')
                            ->select(DB::raw(
                                'SUM(transaction_details.weight) as total_category_weight, '.
                                'SUM(transaction_details.price) as total_category_price'))
                            ->where('transaction_details.masaro_category_id', $masaroCategory->id)
                            ->whereMonth('transaction_headers.date', '=', $monthInt)
                            ->first();

                        $wasteCategoryItem = collect([
                            'name'      => $masaroCategory->name,
                            'weight'    => $categoryData->total_category_weight ?? 0,
                            'price'     => $categoryData->total_category_price ?? 0,
                        ]);

                        $wasteCategories->push($wasteCategoryItem);
                    }

                    //dd($wasteCategories);
                }
                else{
                    foreach ($dwsCategories as $dwsCategory){

                        $wasteCategoryItem = collect([
                            'name'      => $dwsCategory->name,
                            'weight'    => 0,
                            'price'     => 0
                        ]);

                        $wasteCategories->push($wasteCategoryItem);
                    }

                    foreach ($masaroCategories as $masaroCategory){

                        $wasteCategoryItem = collect([
                            'name'      => $masaroCategory->name,
                            'weight'    => 0,
                            'price'     => 0
                        ]);

                        $wasteCategories->push($wasteCategoryItem);
                    }
                }
            }

            // Get monthly waste bank data
            if($wasteBankDatas->count() > 0){
                $wasteBankData = $wasteBankDatas->where('year', $year)
                    ->where('month', $month)
                    ->first();
                if(!empty($wasteBankData)){
                    $totalWasteBank = $wasteBankData->total_count;
                }
            }

            // Get monthly waste collector data
            if($wasteCollectorDatas->count() > 0){
                $wasteCollectorData = $wasteCollectorDatas->where('year', $year)
                    ->where('month', $month)
                    ->first();
                if(!empty($wasteCollectorData)){
                    $totalWasteCollector = $wasteCollectorData->total_count;
                }
            }

            // Get monthly customer data
            if($customerDatas->count() > 0){
                $customerData = $customerDatas->where('year', $year)
                    ->where('month', $month)
                    ->first();
                if(!empty($customerData)){
                    $totalCustomer = $customerData->total_count;
                }
            }

            // Get monthly transaction routine status data
            if($rutinStatusDatas->count() > 0){
                $rutinStatusData = $rutinStatusDatas->where('year', $year)
                    ->where('month', $month)
                    ->first();
                if(!empty($rutinStatusData)){
                    $totalEmptyHouse = $rutinStatusData->total_empty_hourse;
                    $totalNoWaste = $rutinStatusData->total_no_waste;
                }
            }

            $dashboardItem = collect([
                'month'                 => $month,
                'year'                  => $year,
                'totalPrice'            => $totalPrice,
                'totalWeight'           => $totalWeight,
                'totalDistributedPoint' => $totalDistributedPoint,
                'totalTransaction'      => $totalTransaction,
                'totalRutin'            => $totalRutin,
                'totalAntarSendiri'     => $totalAntarSendiri,
                'totalOnDemand'         => $totalOnDemand,
                'totalWasteBank'        => $totalWasteBank,
                'totalWasteCollector'   => $totalWasteCollector,
                'totalCustomer'         => $totalCustomer,
                'totalEmptyHouse'       => $totalEmptyHouse,
                'totalNoWaste'          => $totalNoWaste,
                'wasteCategoryItems'    => $wasteCategories
            ]);

            $dashboardDatas->push($dashboardItem);
        }

        //dd($dashboardDatas);

//        foreach($dashboardDatas as $dashboardData){
//            dd($dashboardData->get('totalPrice'));
//        }

        // Check admin type
//        if($userAdmin->is_super_admin === 0 && !empty($userAdmin->waste_bank_id)){
//            $adminWasteBankId = $userAdmin->waste_bank_id;
//            $adminBankCatId = $userAdmin->waste_bank->waste_category->id;
//            $trxDetailRutin = TransactionDetail::with(['dws_waste_category_data', 'masaro_waste_category_data'])
//                ->whereHas('transaction_header', function($query) use($adminWasteBankId){
//                    $query->where('transaction_type_id', 1)
//                        ->where('waste_bank_id', $adminWasteBankId)
//                        ->orderByDesc('date');
//            });
//
//
//            $trxDetailAntarSendiri = TransactionDetail::with(['dws_waste_category_data', 'masaro_waste_category_data'])
//                ->whereHas('transaction_header', function($query) use($adminWasteBankId){
//                    $query->where('transaction_type_id', 2)
//                        ->where('waste_bank_id', $adminWasteBankId)
//                        ->orderByDesc('date');
//            });
//
//            $trxDetailInstant = TransactionDetail::with(['dws_waste_category_data', 'masaro_waste_category_data'])
//                ->whereHas('transaction_header', function($query) use($adminWasteBankId){
//                    $query->where('transaction_type_id', 3)
//                        ->where('waste_bank_id', $adminWasteBankId)
//                        ->orderByDesc('date');
//            });
//
//            if($adminBankCatId === 1){
//                $trxDetailRutin = $trxDetailRutin->whereNotNull('dws_category_id');
//
//                $trxDetailAntarSendiri = $trxDetailAntarSendiri->whereNotNull('dws_category_id');
//
//                $trxDetailInstant = $trxDetailInstant->whereNotNull('dws_category_id');
//            }
//            else{
//                $trxDetailRutin = $trxDetailRutin->whereNotNull('masaro_category_id');
//
//                $trxDetailAntarSendiri = $trxDetailAntarSendiri->whereNotNull('masaro_category_id');
//
//                $trxDetailInstant = $trxDetailInstant->whereNotNull('masaro_category_id');
//            }
//        }
//        else{
//            $trxDetailRutin = TransactionDetail::with(['dws_waste_category_data', 'masaro_waste_category_data'])
//                ->whereHas('transaction_header', function($query){
//                    $query->where('transaction_type_id', 1)
//                        ->orderByDesc('date');
//            });
//            $trxDetailAntarSendiri = TransactionDetail::with(['dws_waste_category_data', 'masaro_waste_category_data'])
//                ->whereHas('transaction_header', function($query){
//                    $query->where('transaction_type_id', 2)
//                        ->orderByDesc('date');
//            });
//            $trxDetailInstant = TransactionDetail::with(['dws_waste_category_data', 'masaro_waste_category_data'])
//                ->whereHas('transaction_header', function($query){
//                    $query->where('transaction_type_id', 3)
//                        ->orderByDesc('date');
//            });
//        }
//
//        $trxDetailRutin = $trxDetailRutin->take(10)->get();
//        $trxDetailAntarSendiri = $trxDetailAntarSendiri->take(10)->get();
//        $trxDetailInstant = $trxDetailInstant->take(10)->get();
//
//        // Get total waste value
//        $totalRutinWasteWeight = 0;
//        $totalRutinWastePrice = 0;
//        if($trxDetailRutin->count() > 0){
//            foreach ($trxDetailRutin as $trxDetail){
//                $totalRutinWasteWeight += $trxDetail->weight;
//                $totalRutinWastePrice += $trxDetail->price;
//            }
//        }
//
//        $totalAntarSendiriWasteWeight = 0;
//        $totalAntarSendiriWastePrice = 0;
//        if($trxDetailAntarSendiri->count() > 0){
//            foreach ($trxDetailAntarSendiri as $trxDetail){
//                $totalAntarSendiriWasteWeight += $trxDetail->weight;
//                $totalAntarSendiriWastePrice += $trxDetail->price;
//            }
//        }
//
//        $totalInstantWasteWeight = 0;
//        $totalInstantWastePrice = 0;
//        if($trxDetailInstant->count() > 0){
//            foreach ($trxDetailInstant as $trxDetail){
//                $totalInstantWasteWeight += $trxDetail->weight;
//                $totalInstantWastePrice += $trxDetail->price;
//            }
//        }

        $data = [
            'isSuperAdmin'                  => $isSuperAdmin,
            'dashboardDatas'                => $dashboardDatas

//            'trxDetailAntarSendiri'         => $trxDetailAntarSendiri,
//            'trxDetailInstant'              => $trxDetailInstant,
//            'trxDetailRutin'                => $trxDetailRutin,
//            'totalRutinWasteWeight'         => number_format($totalRutinWasteWeight, 0, ",", "."),
//            'totalRutinWastePrice'          => number_format($totalRutinWastePrice, 0, ",", "."),
//            'totalAntarSendiriWasteWeight'  => number_format($totalAntarSendiriWasteWeight, 0, ",", "."),
//            'totalAntarSendiriWastePrice'   => number_format($totalAntarSendiriWastePrice, 0, ",", "."),
//            'totalInstantWasteWeight'       => number_format($totalInstantWasteWeight, 0, ",", "."),
//            'totalInstantWastePrice'        => number_format($totalInstantWastePrice, 0, ",", "."),
        ];

        return view('admin.dashboard')->with($data);
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
    public function logout(Request $request){
        Auth::guard('admin')->logout();
        $request->session()->flush();
        $request->session()->regenerate();

        return redirect()->route('admin.login');
    }
}
