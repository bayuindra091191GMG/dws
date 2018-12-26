<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Transformer\UserTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class UserController extends Controller
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

    public function getIndex(Request $request){
        $users = User::query();
        return DataTables::of($users)
            ->setTransformer(new UserTransformer)
            ->addIndexColumn()
            ->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('admin.user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $user = User::find($id);
        return view('admin.user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'first_name'        => 'required|max:100',
            'last_name'         => 'required|max:100',
            'email'             => 'required|regex:/^\S*$/u|unique:users|max:50',
            'phone'             => 'required'
        ],[
            'email.unique'      => 'ID Login Akses telah terdaftar!',
            'email.regex'       => 'ID Login Akses harus tanpa spasi!'
        ]);

        if(!ctype_space($request->input('password'))){
            $validator->sometimes('password', 'min:6|confirmed', function ($input) {
                return $input->password;
            });
        }

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());

        $user = User::find($request->input('id'));
        $user->email = $request->input('email');
        $user->name = $request->input('name');
        $user->phone = $request->input('phone');
        $user->save();

        Session::flash('success', 'Success Updating User!');
        return redirect()->route('admin.users');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        //
        try {
            //Belum melakukan pengecekan hubungan antar Table
            $userId = $request->input('id');
            $user = User::find($userId);
            $user->delete();

            Session::flash('success', 'Success Deleting User ' . $user->email . ' - ' . $user->name);
            return Response::json(array('success' => 'VALID'));
        }
        catch(\Exception $ex){
            return Response::json(array('errors' => 'INVALID'));
        }
    }
}
