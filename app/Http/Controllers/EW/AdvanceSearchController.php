<?php

namespace App\Http\Controllers\EW;

use Illuminate\Http\Request;
use DB;
use Auth;
use Helper;

use Validator;

use App\Model\EwCandidates;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class AdvanceSearchController extends Controller
{

    public function accountSearch(Request $request) {
        $data = $request->all();
        $search = $request->search;
        $project_id = Auth::user()->get()->project_id;
        $data['accountAddAccess'] = Helper::userAccess('amount-received.create', 'ew');

        $ascDesc = Helper::ascDesc($data, ['candidate_name']);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $ascDesc = Helper::ascDesc($data, ['candidate_name', 'project_name', 'trade_name', 'reference_name']);
        $paginate = Helper::paginate($data);
        $data['sn'] = $paginate->serial;

        $data['ewCandidates'] = EwCandidates::join('ew_projects', 'ew_candidates.ew_project_id', '=', 'ew_projects.id')
            ->join('ew_trades', 'ew_candidates.trade_id', '=', 'ew_trades.id')
            ->join('ew_references', 'ew_candidates.reference_id', '=', 'ew_references.id')
            ->select('ew_candidates.*', 'ew_projects.project_name', 'ew_trades.trade_name', 'ew_references.reference_name')
            ->where(function($query) use ($search)
            {
                $query->where('ew_candidates.candidate_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('ew_projects.project_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('ew_trades.trade_name', 'LIKE', '%'.$search.'%')
                    ->orWhere('ew_references.reference_name', 'LIKE', '%'.$search.'%');
            })
            ->where('ew_candidates.valid', 1)
            ->orderBy($ascDesc[0], $ascDesc[1])
            ->paginate($paginate->perPage);

        return view('ew.advanceSearch.candidateSearch', $data);
    }
    
}

