<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuSub;
use App\Transformer\MenuSubTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Yajra\DataTables\DataTables;

class SubMenuController extends Controller
{
    public function getIndex()
    {
        $menus = MenuSub::all();
        return DataTables::of($menus)
            ->setTransformer(new MenuSubTransformer)
            ->addIndexColumn()
            ->make(true);
    }

    /**
     * Function to show Sub Menu Index Page.
     */
    public function index()
    {
        return view('admin.submenu.index');
    }

    /**
     * Function to show create Sub Menu page.
     */
    public function create()
    {
        $menus = Menu::all();
        return view('admin.submenu.create', compact('menus'));
    }

    /**
     * Function to store the new added Sub Menu to the database.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|max:50',
            'route'     => 'required',
            'menu_id'   => 'required'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors())->withInput($request->all());

        MenuSub::create([
            'name'              => $request->get('name'),
            'route'             => $request->get('route'),
            'menu_id'           => $request->get('menu_id')
        ]);

        Session::flash('message', 'Berhasil membuat data menu sub baru!');

        return redirect()->route('admin.menusubs');
    }

    /**
     * Function to show Edit Menu Page.
     *
     * @param MenuSub $subMenu
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(MenuSub $subMenu)
    {
        $menus = Menu::all();
        return view('admin.menu-subs.edit', compact('menus', 'subMenu'));
    }

    /**
     * Function to save the updated Menu.
     *
     * @param Request $request
     * @param MenuSub $subMenu
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, MenuSub $subMenu)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|max:50',
            'route'     => 'required',
            'menu_id'   => 'required'
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator->errors());

        $subMenu->name = $request->get('name');
        $subMenu->route = $request->get('route');
        $subMenu->menu_id = $request->get('menu_id');
        $subMenu->save();

        Session::flash('message', 'Berhasil mengubah data menu Sub!');

        return redirect()->route('admin.menu-subs.edit', ['menuSub' => $subMenu]);
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
            $menuSub = MenuSub::find($request->input('id'));
            $menuSub->delete();

            Session::flash('message', 'Berhasil menghapus data Menu Sub '. $menuSub->name);
            return Response::json(array('success' => 'VALID'));
        }
        catch(\Exception $ex){
            return Response::json(array('errors' => 'INVALID'));
        }
    }
}
