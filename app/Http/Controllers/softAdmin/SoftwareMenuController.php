<?php

namespace App\Http\Controllers\SoftAdmin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB, Auth, Helper;

//Model
use App\Model\SoftwareModules;
use App\Model\SoftwareMenu;
use App\Model\Icon;

class SoftwareMenuController extends Controller
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

        return view('softAdmin.softwareMenu.index', $data);
    }

    public function softwareMenuList(Request $request) {
        $data = $request->all();
        $search = $request->search;

        $ascDesc = Helper::ascDesc($data, ['menu_name', 'route', 'parent_menu_name', 'module_name', 'status']);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $data['softwareMenus'] = SoftwareMenu::leftJoin('software_modules', 'software_menu.module_id', '=', 'software_modules.id')
            ->leftJoin('software_menu as parent_menu', 'software_menu.parent_id', '=', 'parent_menu.id')
            ->join('software_folder', 'software_modules.folder_id', '=', 'software_folder.id')
            ->select('software_menu.*', 'software_folder.folder_name', DB::raw("if(software_menu.resource=1, concat(software_modules.route_prefix, REPLACE(software_modules.url_prefix,'/','.'), '.', software_menu.route), concat(software_modules.route_prefix, software_menu.route)) as route"), 'software_modules.module_name', 'parent_menu.menu_name as parent_menu_name')
            ->where(function($query) use ($search)
            {
                $query->where('software_menu.menu_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('software_menu.route', 'LIKE', '%'.$search.'%')
                    ->orWhere('software_modules.module_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('parent_menu.menu_name', 'LIKE', '%'.$search.'%');
            })
            ->where('software_menu.valid', 1)
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        return view('softAdmin.softwareMenu.list', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $data['icons'] = Icon::orderBy('class_name', 'asc')->get();
        $data['software_modules']  = SoftwareModules::orderBy('module_name', 'asc')->get();
        $data['folder'] = DB::table('software_folder')->orderBy('id', 'asc')->get();

        return view('softAdmin.softwareMenu.create', $data);
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

        if(SoftwareMenu::where('route', $input['route'])->where('module_id', $input['module_id'])->where('route', '<>', '#')->first()) {
            $output['messege'] = 'Link already exists';
            $output['msgType'] = 'danger';
        } else {
            $output['messege'] = 'Menu has been created';
            $output['msgType'] = 'success';
            SoftwareMenu::create($input);
        }
        echo json_encode($output);
    }


    public function softwareLinkModule(Request $request)
    {
        $folder_id=$request->folder_id;
        
        $data['softwareLinkModules'] =SoftwareModules::where('folder_id', $folder_id)->active()->get();

        return view('softAdmin.softwareMenu.softwareLinkModule', $data);
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
        $data['software_modules']  = SoftwareModules::orderBy('module_name', 'asc')->get();
        $data['software_menu'] = $software_menu = SoftwareMenu::find($id);
        $data['software_module']  = SoftwareModules::find($software_menu->module_id);
        $data['folder'] = DB::table('software_folder')->orderBy('id', 'asc')->get();

        return view('softAdmin.softwareMenu.update', $data);
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

        if(SoftwareMenu::where('route', $input['route'])->where('module_id', $input['module_id'])->where('route', '<>', '#')->where('id', '<>', $id)->first()) {
            $output['messege'] = 'Link already exists';
            $output['msgType'] = 'danger';
        } else {
            $output['messege'] = 'Menu has been updated';
            $output['msgType'] = 'success';
            SoftwareMenu::find($id)->update($input);
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
        SoftwareMenu::find($id)->delete();
    }

    public function softwareMenuSorting(Request $request)
    {
        $data['inputData'] = $request->all();
        $data['title'] = $data['pageTitle'] = 'Menu Sorting';
        $data['software_modules'] = SoftwareModules::active()->orderBy('module_name', 'asc')->get()->chunk(4);
        $data['folder'] = DB::table('software_folder')->orderBy('id', 'asc')->get();
        return view('softAdmin.softwareMenu.menuSorting', $data);
    }

    public function menuSortingModuleView(Request $request)
    {
        $folder_id = $request->folder_id;

        $data['software_modules'] = SoftwareModules::active()->where('folder_id', $folder_id)->orderBy('module_name', 'asc')->get()->chunk(4);
        
        return view('softAdmin.softwareMenu.menuSortingModuleView', $data);
    }

    public function softwareMenuSortingMenuList(Request $request)
    {
        $module_id = $request->module_id;
        $data['software_module'] = SoftwareModules::active()->find($module_id);

        if(!empty($data['software_module'])) {
            $data['software_menus'] = SoftwareMenu::active()->where('module_id', $module_id)->orderBy('sl_no', 'asc')->get();
            return view('softAdmin.softwareMenu.menuSortingMenuList', $data);
        }
    }

    public function softwareMenuSortingAction(Request $request)
    {
        $soft_menus1 = json_decode($request->soft_menus);

        $i1 = 1;
        foreach($soft_menus1 as $soft_menu1) {
            $menu1 = SoftwareMenu::active()->find($soft_menu1->id);
            if(!empty($menu1)) {
                $menu1->parent_id = 0;
                $menu1->sl_no = $i1;
                $menu1->save();
                $i1++;

                $soft_menus2 = @$soft_menu1->children;
                if(!empty($soft_menus2)) {
                    $i2 = 1;
                    foreach($soft_menus2 as $soft_menu2) {
                        $menu2 = SoftwareMenu::active()->find($soft_menu2->id);
                        if (!empty($menu2)) {
                            $menu2->parent_id = $soft_menu1->id;
                            $menu2->sl_no = $i2;
                            $menu2->save();
                            $i2++;

                            $soft_menus3 = @$soft_menu2->children;
                            if (!empty($soft_menus3)) {
                                $i3 = 1;
                                foreach ($soft_menus3 as $soft_menu3) {
                                    $menu3 = SoftwareMenu::active()->find($soft_menu3->id);
                                    if (!empty($menu3)) {
                                        $menu3->parent_id = $soft_menu2->id;
                                        $menu3->sl_no = $i3;
                                        $menu3->save();
                                        $i3++;

                                        $soft_menus4 = @$soft_menu3->children;
                                        if (!empty($soft_menus4)) {
                                            $i4 = 1;
                                            foreach ($soft_menus4 as $soft_menu4) {
                                                $menu4 = SoftwareMenu::active()->find($soft_menu4->id);
                                                if (!empty($menu4)) {
                                                    $menu4->parent_id = $soft_menu3->id;
                                                    $menu4->sl_no = $i4;
                                                    $menu4->save();
                                                    $i4++;

                                                    $soft_menus5 = @$soft_menu4->children;
                                                    if (!empty($soft_menus5)) {
                                                        $i5 = 1;
                                                        foreach ($soft_menus5 as $soft_menu5) {
                                                            $menu5 = SoftwareMenu::active()->find($soft_menu5->id);
                                                            if (!empty($menu5)) {
                                                                $menu5->parent_id = $soft_menu4->id;
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
