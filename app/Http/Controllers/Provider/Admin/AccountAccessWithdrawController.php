<?php

namespace App\Http\Controllers\Provider\Admin;

use Illuminate\Http\Request;
use App\Model\EnCorporateAccountAccess_provider;
use App\Model\EnCorporateAccount_provider;
use App\Model\Project;
use App\Model\SoftwareMenu;
use App\Model\SoftwareInternalLink;
use App\Model\SoftwareMenuAccess_provider;
use App\Model\EnCorporateUserAccess_provider;
use App\Model\EnUsers_provider;
use App\Model\EnCorporateUserInfo_provider;
use App\Model\EnTraineeUserInfo;
use App\Model\EnCorporateCourseAgreement_provider;
use App\Model\EnCorporateUserCourseAccess_provider;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use Carbon;
use Mail;
use Helper;
use Validator;

class AccountAccessWithdrawController extends Controller
{
    //CORPORATE ACCOUNT LIST
    public function corAccountListData(Request $request)
    {
        $data['inputData'] = $request->all();

        return view('provider.admin.corporateAccountAccessWithdraw.list', $data);
    }


    //CORPORATE ACCOUNT LIST DATA FOR ACCESS WITHDRAW
    public function corAccountForWithdrawListData(Request $request) {
        $data = $request->all();
        $search = $request->search;
        $data['access'] = Helper::providerUserPageAccess($request);
        $ascDesc = Helper::ascDesc($data, ['account_name', 'email', 'email_verified', 'mobile', 'updated_at']);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;
        $project_id = Auth::provider()->get()->project_id;
        $data['corporate_account'] = $corporate_account = (!empty($request->corporate_account_id))? $request->corporate_account_id:'';
        $data['contact_person_status'] = $contact_person_status = (!empty($request->contact_person_status))? $request->contact_person_status:'';
        $data['corporateAccounts'] = EnCorporateAccount_provider::valid()->get();

        $data['enCorporateAccounts'] = EnCorporateAccount_provider::join('en_users', 'en_corporate_account.id', '=', 'en_users.corporate_id')
            ->select('en_corporate_account.*', 'en_users.id as user_id', 'en_users.email', 'en_users.email_verified', 'en_users.contact_person_status')
            ->where(function($query) use ($search)
            {
                $query->where('en_corporate_account.account_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('en_users.email', 'LIKE', '%'.$search.'%')
                    ->orWhere('en_users.email_verified', 'LIKE', '%'.$search.'%')
                    ->orWhere('en_corporate_account.mobile', 'LIKE', '%'.$search.'%')
                    ->orWhere('en_corporate_account.updated_at', 'LIKE', '%'.$search.'%');
            })
            ->where(function($query) use ($corporate_account, $contact_person_status)
            {
                if (!empty($corporate_account)) {
                    $query->where('en_corporate_account.id', $corporate_account);
                }

                if (!empty($contact_person_status)) {
                    $query->where('en_users.contact_person_status', $contact_person_status);
                }
            })
            ->where('en_users.valid', 1)
            ->where('en_corporate_account.valid', 1)
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        return view('provider.admin.corporateAccountAccessWithdraw.listData', $data);
    }


    public function accountAccessWithdraw(Request $request)
    {
        $data['user_id'] = $user_id = $request->user_id;
        $data['userInfo'] =         EnUsers_provider::where('valid', 1)->find($user_id);

        return view('provider.admin.corporateAccountAccessWithdraw.accountAccessWithdraw', $data);
    }

    public function accountAccessWithdrawAction(Request $request)
    {
        DB::beginTransaction();
        $project_id = Auth::provider()->get()->project_id;
        $provider_id = Auth::provider()->get()->id;
        $user_id = $request->user_id;
        $userinfo = EnUsers_provider::where('valid', 1)->find($user_id);
        $currentDate = date('Y-m-d H:i:s');

        if ($userinfo->contact_person_status==1) {
            $validator = Validator::make($request->all(), [
                'email'                => 'required'
            ]);

            if ($validator->passes()) {
                $emailCheck = EnUsers_provider::where('email', $request->email)->where('valid', 1)->first();
                if(empty($emailCheck)){
                    $original_string = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
                    $original_string = implode("", $original_string);
                    $verification_code = substr(str_shuffle($original_string), 0, 5).time().substr(str_shuffle($original_string), 0, 5);

                    //CREATE NEW USER
                    EnUsers_provider::create([
                        "project_id"                => $project_id,
                        "corporate_id"              => $userinfo->corporate_id,
                        "type"                      => 3,
                        "is_trainee"                => 1,
                        "is_corporate"              => 1,
                        "contact_person_status"     => 1,
                        "name"                      => $request->name,
                        "email"                     => $request->email,
                        "auth_key"                  => str_replace('.', '', uniqid('', true)),
                        "secret_key"                => md5(time()),
                        "verification_code"         => $verification_code,
                        "status"                    => "Active"
                    ]);

                    //GET NEW USER ID
                    $new_user_id = EnUsers_provider::valid()->orderBy('id', 'desc')->first()->id;

                    //CREATE TRAINEE USER INFO
                    EnTraineeUserInfo::create([
                        "corporate_id"          => $userinfo->corporate_id,
                        "user_id"               => $new_user_id,
                        "name"                  => $request->name
                    ]);

                    //UPDATE CORPORATE USER INFO
                    EnCorporateUserInfo_provider::where('user_id', $user_id)->where('corporate_id', $userinfo->corporate_id)->update([
                            'user_id'               => $new_user_id
                        ]);

                    //UPDATE CORPORATE COURSE AGREEMENT INFO
                    EnCorporateCourseAgreement_provider::where('corporate_user_id', $user_id)->where('account_id', $userinfo->corporate_id)->update([
                            'corporate_user_id'               => $new_user_id
                        ]);

                    //UPDATE CORPORATE USER COURSE ACCESS
                    EnCorporateUserCourseAccess_provider::where('user_id', $user_id)->where('account_id', $userinfo->corporate_id)->update([
                            'user_id'               => $new_user_id
                        ]);

                    //UPDATE CORPORATE USER ACCESS
                    DB::table('en_corporate_user_access')->where('user_id', $user_id)->where('account_id', $userinfo->corporate_id)->update([
                            'user_id'               => $new_user_id,
                            'updated_at'            => $currentDate,
                            'updated_by'            => $provider_id,
                            'updated_by_type'       => 2, // 2=Provider
                        ]);

                    //SEND MAIL
                    Helper::mailConfig();
                    $email_data['data'] = [
                        'name'              =>  $request->name,
                        'email'             =>  $request->email
                    ];
                    $email_data['link'] = url('corporate/corporate_email_verification?token=' .$verification_code);
                    Mail::send('emails.email_verification', $email_data, function($message) use ($request)
                    {
                        $message->subject('Verify Account');
                        $message->to($request->email, $request->name);
                    });


                    //UPDATE CORPORATE USER INFO ONLY CORPORATE INFO
                    EnUsers_provider::where('valid', 1)->find($user_id)->update([
                        "corporate_id"                  => 0,
                        "is_corporate"                  => 0,
                        "contact_person_status"         => 0,
                        "type"                          => 1,
                    ]);

                    EnTraineeUserInfo::where('valid', 1)->where('user_id', $user_id)->update([
                        "corporate_id"                  => 0
                    ]);

                    $output['messege'] = 'Corporate account has been created';
                    $output['msgType'] = 'success';
                } else {
                    $output['messege'] = 'Email already exist.';
                    $output['msgType'] = 'danger';
                }

            } else {
                $output = Helper::vError($validator);
            }
        } else {
            //UPDATE CORPORATE USER INFO ONLY CORPORATE INFO
            EnUsers_provider::where('valid', 1)->find($user_id)->update([
                "corporate_id"                  => 0,
                "is_corporate"                  => 0,
                "contact_person_status"         => 0,
            ]);

            //REMOVE CORPORATE ACCOUNT ACCESS
            DB::table('en_corporate_user_access')->where('user_id', $user_id)->where('account_id', $userinfo->corporate_id)->update([
                    'deleted_at'            => $currentDate,
                    'deleted_by'            => $provider_id,
                    'deleted_by_type'       => 2, // 2=Provider
                    'valid'                 => 0
                ]);

            $output['messege'] = 'Corporate account access has been removed';
            $output['msgType'] = 'success';
        }
        echo json_encode($output);
        DB::commit();
    }
}
