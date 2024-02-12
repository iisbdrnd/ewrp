<?php

namespace App\Http\Controllers\SoftAdmin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB, Auth, Helper;

//Model
use App\Model\AdminMenu;
use App\Model\Icon;

class AdminMenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $data['inputData'] = $request->all();
        $data['title'] = $data['pageTitle'] = 'Menu';

        return view('softAdmin.adminMenu.index', $data);
    }

    public function adminMenuList(Request $request) {
        $data = $request->all();
        $search = $request->search;

        $ascDesc = Helper::ascDesc($data, ['menu_name', 'route', 'parent_menu_name', 'status']);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $data['adminMenu'] = AdminMenu::valid()
            ->where(function($query) use ($search)
            {
                $query->where('admin_menu.menu_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('admin_menu.route', 'LIKE', '%'.$search.'%');
            })
            ->where('admin_menu.valid', 1)
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        return view('softAdmin.adminMenu.list', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $data['icons'] = Icon::orderBy('class_name', 'asc')->get();
        

        return view('softAdmin.adminMenu.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $output = array();
        $input = $request->all();
        if(empty($input['status'])) {$input['status'] = 0;}

        if(empty($input['resource'])) {
            $input['resource'] = 0;
        } else {
            $input['route'] = $input['link_name'].'.'.$input['resource_function'];
            unset($input['link_name']);
            unset($input['resource_function']);
        }

        $input['route'] = str_replace("/", ".", $input['route']);

        if(AdminMenu::where('route', $input['route'])->where('route', '<>', '#')->first()) {
            $output['messege'] = 'Link already exists';
            $output['msgType'] = 'danger';
        } else {
            $output['messege'] = 'Menu has been created';
            $output['msgType'] = 'success';
            AdminMenu::create($input);
        }
        echo json_encode($output);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $data['icons'] = Icon::orderBy('class_name', 'asc')->get();
        $data['admin_menu'] = AdminMenu::find($id);

        return view('softAdmin.adminMenu.update', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $output = array();
        $input = $request->all();
        if(empty($input['status'])) {$input['status'] = 0;}

        if(empty($input['resource'])) {
            $input['resource'] = 0;
        } else {
            $input['route'] = $input['link_name'].'.'.$input['resource_function'];
            unset($input['link_name']);
            unset($input['resource_function']);
        }

        $input['route'] = str_replace("/", ".", $input['route']);

        if(AdminMenu::where('route', $input['route'])->where('route', '<>', '#')->where('id', '<>', $id)->first()) {
            $output['messege'] = 'Link already exists';
            $output['msgType'] = 'danger';
        } else {
            $output['messege'] = 'Menu has been updated';
            $output['msgType'] = 'success';
            AdminMenu::find($id)->update($input);
        }
        echo json_encode($output);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        AdminMenu::valid()->find($id)->delete();
    }

    public function adminMenuSorting(Request $request)
    {
        $data['inputData'] = $request->all();
        $data['title'] = $data['pageTitle'] = 'Menu Sorting';
        $data['adminMenu'] = AdminMenu::valid()->active()->orderBy('sl_no', 'asc')->get();
        
        return view('softAdmin.adminMenu.menuSorting', $data);
    }

    public function adminMenuSortingAction(Request $request)
    {
        $admin_menu1 = json_decode($request->admin_menu);

        $i1 = 1;
        foreach($admin_menu1 as $admin_menu1) {
            $menu1 = AdminMenu::active()->find($admin_menu1->id);
            if(!empty($menu1)) {
                $menu1->parent_id = 0;
                $menu1->sl_no = $i1;
                $menu1->save();
                $i1++;

                $admin_menu2 = @$admin_menu1->children;
                if(!empty($admin_menu2)) {
                    $i2 = 1;
                    foreach($admin_menu2 as $admin_menu2) {
                        $menu2 = AdminMenu::active()->find($admin_menu2->id);
                        if (!empty($menu2)) {
                            $menu2->parent_id = $admin_menu1->id;
                            $menu2->sl_no = $i2;
                            $menu2->save();
                            $i2++;

                            $admin_menu3 = @$admin_menu2->children;
                            if (!empty($admin_menu3)) {
                                $i3 = 1;
                                foreach ($admin_menu3 as $admin_menu3) {
                                    $menu3 = AdminMenu::active()->find($admin_menu3->id);
                                    if (!empty($menu3)) {
                                        $menu3->parent_id = $admin_menu2->id;
                                        $menu3->sl_no = $i3;
                                        $menu3->save();
                                        $i3++;

                                        $admin_menu4 = @$admin_menu3->children;
                                        if (!empty($admin_menu4)) {
                                            $i4 = 1;
                                            foreach ($admin_menu4 as $admin_menu4) {
                                                $menu4 = AdminMenu::active()->find($admin_menu4->id);
                                                if (!empty($menu4)) {
                                                    $menu4->parent_id = $admin_menu3->id;
                                                    $menu4->sl_no = $i4;
                                                    $menu4->save();
                                                    $i4++;

                                                    $admin_menu5 = @$admin_menu4->children;
                                                    if (!empty($admin_menu5)) {
                                                        $i5 = 1;
                                                        foreach ($admin_menu5 as $admin_menu5) {
                                                            $menu5 = AdminMenu::active()->find($admin_menu5->id);
                                                            if (!empty($menu5)) {
                                                                $menu5->parent_id = $admin_menu4->id;
                                                                $menu5->sl_no = $i5;
                                                                $menu5->save();
                                                                $i5++;
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }


}
