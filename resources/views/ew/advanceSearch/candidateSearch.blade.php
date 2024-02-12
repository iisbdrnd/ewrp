<div class="form-inline">
    <div class="row datatables_header">
        <div class="col-md-5 col-xs-12">
            <div class="input-group">
                <input name="search" event="enter" class="data-search form-control" id="search-input" value="{{@$search}}" kl_virtual_keyboard_secure_input="on" placeholder="Search">
                <span class="input-group-btn"><button name="search" event="click" valueFrom="#search-input" class="data-search btn btn-default" type="button">Go</button></span>
            </div>
        </div>
        <!-- <div class="col-md-7 col-xs-12">
            <button class="add-btn btn btn-default pull-right btn-sm" type="button" style="margin-left: 12px;"><i class="glyphicon glyphicon-plus mr5"></i>Add New</button>
        </div> -->
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
                        <th width="20%" data="1">Project Phone</th>
                        <th width="20%" data="1">Job Email</th>
                        <th width="20%" data="1">Reference Name</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>No.</th>
                        <th>Candidate Name</th>
                        <th>Project Phone</th>
                        <th>Job Email</th>
                        <th>Reference Name</th>
                    </tr>
                </tfoot>
                <tbody>
                <?php $paginate = $ewCandidates; ?>
                @if(count($ewCandidates)>0)
                @foreach($ewCandidates as $ewCandidates)
                    <tr>
                        <td>{{$sn++}}</td>
                        <td><a class="hand set-search" value="{{$ewCandidates->id}}" text="{{$ewCandidates->candidate_name}}">{{$ewCandidates->candidate_name}}</a></td>
                        <td>{{$ewCandidates->project_name}}</td>
                        <td>{{$ewCandidates->job_name}}</td>
                        <td>{{$ewCandidates->reference_name}}</td>
                    </tr>
                @endforeach
                @else
                    <tr>
                        <td colspan="5" class="emptyMessage">Empty</td>
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
