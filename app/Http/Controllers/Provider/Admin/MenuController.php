<?php

namespace App\Http\Controllers\Provider\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB, Auth;

//Model
use App\Model\SoftwareModules_provider;
use App\Model\SoftwareMenu_provider;
use App\Model\SoftwareMenuSorting;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    
    public function menuSorting(Request $request)
    {
        $project_id = Auth::guard('provider')->user()->project_id;
        $data['inputData'] = $request->all();
        $data['title'] = $data['pageTitle'] = 'Menu Sorting';
        $data['software_modules'] = SoftwareModules_provider::projectAccessModules($project_id)->chunk(4);

        return view('provider.admin.menu.menuSorting', $data);
    }

    public function menuSortingMenuList(Request $request)
    {
        $project_id = Auth::guard('provider')->user()->project_id;
        $module_id = $request->module_id;
        $data['software_module'] = SoftwareModules_provider::active()->find($module_id);

        if(!empty($data['software_module'])) {
            $data['software_menus'] = SoftwareMenu_provider::projectAccessMenus($project_id, $module_id);
            return view('provider.admin.menu.menuSortingMenuList', $data);
        }
    }
    public function menuSortingAction(Request $request)
    {
        $soft_menus1 = json_decode($request->soft_menus);

        $i1 = 1;
        foreach($soft_menus1 as $soft_menu1) {
            $menu1 = SoftwareMenuSorting::valid()->where('menu_id', $soft_menu1->id)->first();
            if(!empty($menu1)) {
                $menu1->parent_id = 0;
                $menu1->sl_no = $i1;
                $menu1->save();
            } else {
                SoftwareMenuSorting::create([
                    "menu_id" => $soft_menu1->id,
                    "parent_id" => 0,
                    "sl_no" => $i1
                ]);
            }
            $i1++;

            $soft_menus2 = @$soft_menu1->children;
            if(!empty($soft_menus2)) {
                $i2 = 1;
                foreach($soft_menus2 as $soft_menu2) {
                    $menu2 = SoftwareMenuSorting::valid()->where('menu_id', $soft_menu2->id)->first();
                    if (!empty($menu2)) {
                        $menu2->parent_id = $soft_menu1->id;
                        $menu2->sl_no = $i2;
                        $menu2->save();
                    } else {
                        SoftwareMenuSorting::create([
                            "menu_id" => $soft_menu2->id,
                            "parent_id" => $soft_menu1->id,
                            "sl_no" => $i2
                        ]);
                    }
                    $i2++;

                    $soft_menus3 = @$soft_menu2->children;
                    if (!empty($soft_menus3)) {
                        $i3 = 1;
                        foreach ($soft_menus3 as $soft_menu3) {
                            $menu3 = SoftwareMenuSorting::valid()->where('menu_id', $soft_menu3->id)->first();
                            if (!empty($menu3)) {
                                $menu3->parent_id = $soft_menu2->id;
                                $menu3->sl_no = $i3;
                                $menu3->save();
                            } else {
                                SoftwareMenuSorting::create([
                                    "menu_id" => $soft_menu3->id,
                                    "parent_id" => $soft_menu2->id,
                                    "sl_no" => $i3
                                ]);
                            }
                            $i3++;

                            $soft_menus4 = @$soft_menu3->children;
                            if (!empty($soft_menus4)) {
                                $i4 = 1;
                                foreach ($soft_menus4 as $soft_menu4) {
                                    $menu4 = SoftwareMenuSorting::valid()->where('menu_id', $soft_menu4->id)->first();
                                    if (!empty($menu4)) {
                                        $menu4->parent_id = $soft_menu3->id;
                                        $menu4->sl_no = $i4;
                                        $menu4->save();
                                    } else {
                                        SoftwareMenuSorting::create([
                                            "menu_id" => $soft_menu4->id,
                                            "parent_id" => $soft_menu3->id,
                                            "sl_no" => $i4
                                        ]);
                                    }
                                    $i4++;

                                    $soft_menus5 = @$soft_menu4->children;
                                    if (!empty($soft_menus5)) {
                                        $i5 = 1;
                                        foreach ($soft_menus5 as $soft_menu5) {
                                            $menu5 = SoftwareMenuSorting::valid()->where('menu_id', $soft_menu5->id)->first();
                                            if (!empty($menu5)) {
                                                $menu5->parent_id = $soft_menu4->id;
                                                $menu5->sl_no = $i5;
                                                $menu5->save();
                                            } else {
                                                SoftwareMenuSorting::create([
                                                    "menu_id" => $soft_menu5->id,
                                                    "parent_id" => $soft_menu4->id,
                                                    "sl_no" => $i5
                                                ]);
                                            }
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
