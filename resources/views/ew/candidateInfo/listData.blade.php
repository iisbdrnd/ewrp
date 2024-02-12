<div class="form-inline">
    <div class="row datatables_header">
        <div class="col-md-3 col-xs-12">
            <div class="input-group">
                <input name="search" event="enter" class="data-search form-control" id="search-input" value="{{@$search}}" kl_virtual_keyboard_secure_input="on" placeholder="Search">
                <span class="input-group-btn"><button name="search" event="click" valueFrom="#search-input" class="data-search btn btn-default" type="button">Go</button></span>
            </div>
        </div>
        <div class="col-md-2 col-xs-12 pl0">
            <select name="collectable_selection_status" event="change" class="data-search form-control select2" >
                <option {{ @$collectable_selection_status == 2?"selected":"" }} value="2"> Collectable Selection No</option>
                <option {{ @$collectable_selection_status == 1?"selected":"" }} value="1">Collectable Selection Yes</option>
            </select>
        </div>
        <div class="col-md-7 col-xs-12">
            {{-- @if($access->create)
            <button url="candidate-info/create" class="add-btn btn btn-default pull-right btn-sm" type="button" style="margin-left: 12px;"><i class="glyphicon glyphicon-plus mr5"></i>Add New</button> 
            @endif  --}}
            @include("perPageBox")
            <a href="{{ url('eastWest#candidate-info') }}" menu-active="mobilization" class="ajax-link btn btn-default pull-right btn-sm" type="a" style="margin-right: 12px;"><i class="fa fa-arrow-left"></i> Back</a>
            
            <a href="{{ url('recruitment#mobilization/accounts-transfer-candidate/'.$projectId) }}" menu-active="mobilization" class="btn btn-default pull-right btn-sm" type="a" style="margin-right: 12px;"><i class="fa fa-arrow-left"></i> Accounts Room  
                ({{ @Helper::accountTransferred(@Helper::projects($projectId)->id, 0) }})
                </a>
        </div>
    </div>
</div>
<div id=myTabContent2 class=tab-content>
    <div class="tab-pane fade active in" id=home2>
        <div class="form-inline">
            <table cellspacing="0" class="responsive table table-striped table-bordered">
                <thead>
                    <tr>
                        <th width="5%">No.</th>
                        <th width="20%" data="1">Candidate Name</th>
                        <th width="20%" data="1">Project Name</th>
                        <th width="20%" data="1">Trade Name</th>
                        <th width="20%" data="1">Reference Name</th>
                        <th width="10%" data="1">Collectable Selection</th>
                        @if($access->edit)
                        <th width="5%">Action</th>
                        @endif
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>No.</th>
                        <th>Candidate Name</th>
                        <th>Project Name</th>
                        <th>Trade Name</th>
                        <th>Reference Name</th>
                        <th>Collectable Selection</th>
                        @if($access->edit)
                        <th>Action</th>
                        @endif
                    </tr>
                </tfoot>
                <tbody>
                <?php $paginate = $candidates; ?>
                @if(count($candidates)>0)
                @foreach($candidates as $candidate)
                    <tr>
                        <td>{{$sn++}}</td>
                        <td>{{$candidate->candidate_id.' - '.$candidate->candidate_name}}</td>
                        <td>{{$candidate->project_name}}</td>
                        <td>{{$candidate->trade_name}}</td>
                        <td>{{$candidate->reference_name}}</td>
                        <td>{{ $candidate->collectable_status == 1?'Yes':($candidate->collectable_status == 0?'No':'')}}</td>
                        @if($access->edit)
                        <td class="tac">
                            <a  url="candidate-info/{{$candidate->id}}/edit" view-type="modal" modal-size="large" class="add-btn btn {{ $candidate->collectable_status == 0?'btn-success':'btn-default' }}">Update Collectable Amount</a>

                        </td>
                        @endif
                    </tr>
                @endforeach
                @else
                    <tr>
                        <td colspan="7" class="emptyMessage">Empty</td>
                    </tr>
                @endif
                </tbody>
            </table>
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    @include("pagination")
                </div>
            </div>
        </div>
    </div>
</div>

