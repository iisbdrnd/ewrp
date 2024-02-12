<style>
.notifications {
    padding: 0 7px;
    color: #fff;
    background: #ed7a53;
    border-radius: 2px;
    -webkit-border-radius: 2px;
    -moz-border-radius: 2px;
    font-weight: 700;
    font-size: 12px;
    font-family: Tahoma;
    position: absolute;
    box-shadow: 0 1px 0 0 rgba(0,0,0,.2);
    text-shadow: none;
    margin-left: 5px;
}
.notifications.green {
background: #9fc569;
}
</style>
<div class="form-inline">
    <div class="row datatables_header">
        <div class="col-md-3 col-xs-6">
            <div class="input-group">
                <input name="search" event="enter" class="data-search form-control" id="search-input" value="{{@$search}}" kl_virtual_keyboard_secure_input="on" placeholder="Search">
                <span class="input-group-btn"><button name="search" event="click" valueFrom="#search-input" class="data-search btn btn-default" type="button">Go</button></span>
            </div>
        </div>
        <div class="col-md-9 col-xs-12">
            @include("perPageBox")
        </div>
    </div>
</div>
<div id="myTabContent2" class="tab-content">
    <div class="tab-pane fade active in" id=home2>
        <div class="form-inline">
            <table cellspacing="0" class="responsive table table-striped table-bordered">
                <thead>
                    <tr>
                        <th width="5%" data="1">No.</th>
                        <th  data="65%" data="2">Project Name</th>
                        <th width="15%" width="" data="3">Intreview Status</th>
                        <th width="15%" data="4">Go to interview Room</th>
                </thead>
                <tfoot>
                    <tr>
                        <th>No.</th>
                        <th>Project Name</th>
                        <th>Intreview Status</th>
                        <th>Go to interview Room</th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php $paginate = $interviewCallProjects; ?>
                    @if(count($interviewCallProjects)>0)  
                    @foreach($interviewCallProjects as $interviewCallProject)
                        <tr>
                            <td>{{$sn++}}</td>
                            <td>
                                <a url="listOfCV?projectId={{ $interviewCallProject->id }}&projectCountryId={{$interviewCallProject->project_country_id}}" main-selector="mainDiv" panel-title="Project Name :  {{ $interviewCallProject->project_name }}" clone-selector="cloneDiv" data-prefix="cl" class="seq-forward"><span class="txt">
                                {{ $interviewCallProject->project_name }}
                                </span><span class="notifications green">{{ @Helper::totalInterviewFace($interviewCallProject->id) !=0?@Helper::totalInterviewFace($interviewCallProject->id):'' }}</span>
                                </a>
                            </td>
                            <td><strong class="{{ $interviewCallProject->status==1?'text-success': ( $interviewCallProject->status==2?'text-danger':'') }}">{{ $interviewCallProject->status==1?'Running':'Close' }}</strong> </td>
                            <td class="tac">
                                @if(@Helper::checkAssignuser($interviewCallProject->id) == "notAllowed")
                                <button class="btn btn-default btn-sm" disabled>You are Not Allowed</button>  
                                @else
                            <button url="listOfCV?projectId={{ $interviewCallProject->id }}&projectCountryId={{$interviewCallProject->project_country_id}}"  main-selector="mainDiv" clone-selector="cloneDiv" data-prefix="cl" panel-title="Project Name :  {{ $interviewCallProject->project_name }}" class="seq-forward hand btn btn-default btn-sm" type="button"><i class=" fa fa-long-arrow-right"></i> Interview Room</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    @else    
                        <tr>
                            <td colspan="8" class="emptyMessage">Empty</td>
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
