<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuSub;
use App\Transformer\MenuTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Yajra\DataTables\DataTables;

class MenuController extends Controller
{
    public function getIndex()
    {
        $menus = Menu::all();
        return DataTables::of($menus)
            ->setTransformer(new MenuTransformer)
            ->addIndexColumn()
            ->make(true);
    }

    /**
     * Function to show Menu Index Page.
    */
    public function index()
    {
        return view('admin.menu.index');
    }

    /**
     * Function to show create Menu page.
    */
    public function create()
    {
        return view('admin.menu.create');
    }

    /**
     * Function to store the new added Menu to the database.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|max:50',
            'route'    => 'required'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());

        Menu::create([
            'name'              => $request->get('name'),
            'route'             => $request->get('route')
        ]);

        Session::flash('message', 'Berhasil membuat data menu baru!');

        return redirect()->route('admin.menus');
    }

    /**
     * Function to show Edit Menu Page.
     *
     * @param Menu $menu
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Menu $menu)
    {
        return view('admin.menu.edit', compact('menu'));
    }

    /**
     * Function to save the updated Menu.
     *
     * @param Request $request
     * @param Menu $menu
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Menu $menu)
    {
        $validator = Validator::make($request->all(), [
            'name'              => 'required|max:50',
            'route'             => 'required'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());

        $menu->name = $request->get('name');
        $menu->route = $request->get('route');
        $menu->save();

        Session::flash('message', 'Berhasil mengubah data menu!');

        return redirect()->route('admin.menus.edit', ['menu' => $menu]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @internal param int $id
     */
    public function destroy(Request $request)
    {
        try{
            $menu = Menu::find($request->input('id'));

            //Deleting all the Sub Menus
            MenuSub::where('menu_id', $menu->id)->delete();
            $menu->delete();

            Session::flash('message', 'Berhasil menghapus data menu '. $menu->name);
            return Response::json(array('success' => 'VALID'));
        }
        catch(\Exception $ex){
            return Response::json(array('errors' => 'INVALID'));
        }
    }
}
