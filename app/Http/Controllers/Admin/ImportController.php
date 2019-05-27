<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Imports\TransactionImport;
use App\Imports\UserImport;
use App\Models\Address;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function form()
    {
        return view('admin.import.form');
    }

    public function importExcel(Request $request){
        $excel = request()->file('excel');
        if($request->input('import') == '1'){
            Excel::import(new UserImport(), $excel);
        }
        else{
            Excel::import(new TransactionImport(), $excel);
        }

        Session::flash('success', 'Berhasil Import Data!');
        return redirect(route('admin.import.form'));
    }

    public function autoAddress(){
        $users = User::all();
        foreach ($users as $user){
            Address::create([
                'description'       => 'Link. Serdag Baru RT 04 RW 08 Kel. Kotabumi Kec. Purwakarta Kota Cilegon Banten',
                'user_id'           => $user->id,
                'primary'           => 1,
                'province'          => 3,
                'city'              => 106,
                'postal_code'       => '42434',
                'recipient_name'    => $user->first_name. ' '. $user->last_name,
                'recipient_phone'   => $user->phone ?? '',
                'name'              => 'LOKASI',
                'latitude'          => '-5.987804',
                'longitude'         => '106.042588',
                'created_at'        => Carbon::now('Asia/Jakarta')->toDateTimeString(),
                'notes'             => 'keterangan tambahan'
            ]);
        }
    }
}