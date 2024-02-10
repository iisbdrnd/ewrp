<?php

namespace App\Http\Controllers\Provider\ApprovalSystem;

use DB;
use Helper;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Model\EnCorporateAccountAccess_provider;
use App\Model\EnCorporateAccount_provider;
use App\Model\Project;
use App\Model\SoftwareModules;
use App\Model\SoftwareMenu;
use App\Model\SoftwareInternalLink;
use App\Model\SoftwareMenuAccess_provider;
use App\Model\EnCorporateUserAccess_provider;
use App\Model\EnUsers_provider;

class AccountAccessController extends Controller
{
    public function accountAccess(Request $request)
    {
        $account_id = $request->input('data');
        $data['account_info'] = EnCorporateAccount_provider::where('valid', 1)->find($account_id);
        $data['software_modules'] = SoftwareModules::active()->where('folder_id', 3)->orderBy('sl_no', 'asc')->get()->chunk(4);

        return view('provider.ApprovalSystem.corporateAccount.accountAccess', $data);
    }

    public function accountAccessMenuView(Request $request)
    {
        $module_id = $request->module_id;
        $account_id = $request->account_id;
        $data['software_module'] = SoftwareModules::active()->find($module_id);
        $checkAll = true;

        if(!empty($data['software_module'])) {
            $software_menus = SoftwareMenu::active()
                ->select('software_menu.*', 'en_corporate_account_access.id as menu_access')
                ->leftJoin('en_corporate_account_access', function($join) use ($account_id) {
                    $join->on('software_menu.id', '=', 'en_corporate_account_access.menu_id');
                    $join->on('en_corporate_account_access.account_id', '=', DB::raw($account_id));
                    $join->on('en_corporate_account_access.type', '=', DB::raw(1));
                    $join->on('en_corporate_account_access.valid', '=', DB::raw(1));
                })
                ->where('software_menu.module_id', $module_id)
                ->where('software_menu.valid', 1)
                ->orderBy('software_menu.sl_no', 'asc')
                ->get();
                

            foreach($software_menus as $software_menu) {
                $software_menu->internal_links = SoftwareInternalLink::active()
                    ->select('software_internal_link.*', 'en_corporate_account_access.id as link_access')
                    ->leftJoin('en_corporate_account_access', function($join) use ($account_id) {
                        $join->on('software_internal_link.id', '=', 'en_corporate_account_access.link_id');
                        $join->on('en_corporate_account_access.account_id', '=', DB::raw($account_id));
                        $join->on('en_corporate_account_access.type', '=', DB::raw(2));
                        $join->on('en_corporate_account_access.valid', '=', DB::raw(1));
                    })
                    ->where('software_internal_link.menu_id', $software_menu->id)
                    ->where('software_internal_link.valid', 1)
                    ->orderBy('id', 'asc')
                    ->get();

                if($checkAll) { if(empty($software_menu->menu_access)) { $checkAll = false; } }
                foreach($software_menu->internal_links as $internal_links) {
                    if($checkAll) { if(empty($internal_links->link_access)) { $checkAll = false; } }
                }
            }

            $data['software_menus'] = $software_menus;
            $data['checkAll'] = $checkAll;

            return view('provider.ApprovalSystem.corporateAccount.accountAccessMenuView', $data);
        }
    }

    public function accountAccessAction(Request $request)
    {
        DB::beginTransaction();
        $account_id = $request->input('account');

        $module_id = $request->input('module');
        $menu = $request->input('menu');
        $link = $request->input('internal_link');

        $menu_db = collect(SoftwareMenu::corporateAccountAccessMenus($account_id, $module_id)->pluck('id'));
        $link_db = collect(SoftwareInternalLink::corporateAccountAccessLinks($account_id, $module_id)->pluck('link_id'));

        //Menu Remove
        $menu_diff = $menu_db->diff($menu);
        if(!empty($menu_diff)) {
            //Remove Project Access
            $cur_menu_ac = EnCorporateAccountAccess_provider::where('valid', 1)->whereIn('menu_id', $menu_diff)->where('type', 1)->where('account_id', $account_id)->get();
            foreach($cur_menu_ac as $cur_menu) {
                EnCorporateAccountAccess_provider::where('valid', 1)->find($cur_menu->id)->delete();
            }
            //Remove User Access
            $cur_menu_ac = EnCorporateUserAccess_provider::where('valid', 1)->whereIn('menu_id', $menu_diff)->where('type', 1)->where('account_id', $account_id)->get();
            foreach($cur_menu_ac as $cur_menu) {
                EnCorporateUserAccess_provider::where('valid', 1)->find($cur_menu->id)->delete();
            }
        }
     
        //Link Remove
        $link_diff = $link_db->diff($link);
        if(!empty($link_diff)) {
            //Remove Project Access
            $cur_link_ac = EnCorporateAccountAccess_provider::where('valid', 1)->whereIn('link_id', $link_diff)->where('type', 2)->where('account_id', $account_id)->get();
            foreach($cur_link_ac as $cur_link) {
                EnCorporateAccountAccess_provider::where('valid', 1)->find($cur_link->id)->delete();
            }
            //Remove User Access
            $cur_link_ac = EnCorporateUserAccess_provider::where('valid', 1)->whereIn('link_id', $link_diff)->where('type', 2)->where('account_id', $account_id)->get();
            foreach($cur_link_ac as $cur_link) {
                EnCorporateUserAccess_provider::where('valid', 1)->find($cur_link->id)->delete();
            }
        }

        //Access User
        $user_id = @EnUsers_provider::where('valid', 1)
            ->where('corporate_id', $account_id)
            ->where('contact_person_status', 1)
            ->first()->id;

        //Menu Add
        if(!empty($menu)) {
            foreach($menu as $menu_id) {
                if(!$menu_db->contains($menu_id)) {
                    //Corporate
                    EnCorporateAccountAccess_provider::create(array(
                        'menu_id' => $menu_id,
                        'type' => 1,
                        'account_id' => $account_id
                    ));
                    //User
                    EnCorporateUserAccess_provider::create(array(
                        'menu_id'       => $menu_id,
                        'type'          => 1,
                        'account_id'    => $account_id,
                        'user_id'       => $user_id
                    ));
                }
            }
        }

        //Link Add
        if(!empty($link)) {
            foreach($link as $link_id) {
                if(!$link_db->contains($link_id)) {
                    $menu_id = SoftwareInternalLink::find($link_id)->menu_id;
                    //Corporate
                    EnCorporateAccountAccess_provider::create(array(
                        'link_id' => $link_id,
                        'menu_id' => $menu_id,
                        'type' => 2,
                        'account_id' => $account_id
                    ));
                    //User
                    EnCorporateUserAccess_provider::create(array(
                        'link_id'       => $link_id,
                        'menu_id'       => $menu_id,
                        'type'          => 2,
                        'account_id'    => $account_id,
                        'user_id'       => $user_id
                    ));
                }
            }
        }
        DB::commit();
    }
    
    public function accountAccessView(Request $request)
    {
        $account_id = $request->account_id;
        $module_id = $request->module_id;
        $data = $request->all();
        $search = $request->search;

        $ascDesc = Helper::ascDesc($data, ['menu_name', 'link_name', 'module_name', 'updated_at']);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $corporateAccess = EnCorporateAccountAccess_provider::leftJoin('software_menu as a', 'en_corporate_account_access.menu_id', '=', 'a.id')
            ->leftJoin('software_internal_link', 'en_corporate_account_access.link_id', '=', 'software_internal_link.id')
            ->leftJoin('software_menu as b', 'software_internal_link.menu_id', '=', 'b.id')
            ->leftJoin('software_modules as c', 'a.module_id', '=', 'c.id')
            ->leftJoin('software_modules as d', 'b.module_id', '=', 'd.id')
            ->select('en_corporate_account_access.*', DB::raw("if(en_corporate_account_access.link_id=0, a.menu_name, b.menu_name) as menu_name"), 'software_internal_link.link_name', DB::raw("if(en_corporate_account_access.link_id=0, c.module_name, d.module_name) as module_name"))
            ->where('en_corporate_account_access.account_id', $account_id)
            ->where(function($query) use ($search)
            {
                $query->where('a.menu_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('b.menu_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('software_internal_link.link_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('c.module_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('d.module_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('en_corporate_account_access.updated_at', 'LIKE', '%'.$search.'%');
            });
        if(!empty($module_id)) {
            $corporateAccess = $corporateAccess->where(function($query) use ($module_id)
            {
                $query->where('a.module_id', $module_id)
                    ->orWhere('b.module_id', $module_id);
            });
        }
        $data['corporateAccess'] = $corporateAccess->orderBy($ascDesc[0], $ascDesc[1])->paginate($paginate->perPage);

        return view('provider.ApprovalSystem.corporateAccount.accountAccessView', $data);
    }


}
