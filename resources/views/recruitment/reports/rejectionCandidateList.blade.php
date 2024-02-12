<style>
    .modal-dialog{
        width: 1300px !important;
    }
    div.dt-buttons {
    float: right !important;
}
.datatables_header{
width: 10%;
position: absolute;
margin-left: 74%; 
}

</style>
<div class="form-inline ">
    <div class="row datatables_header">
        {{-- <div class="col-md-5 col-xs-12">
            <div class="input-group">
                <input name="search" event="enter" class="data-search form-control" id="search-input" value="{{@$search}}" kl_virtual_keyboard_secure_input="on" placeholder="Search">
                <span class="input-group-btn"><button name="search" event="click" valueFrom="#search-input" class="data-search btn btn-default" type="button">Go</button></span>
            </div>
        </div>
        <div class="col-md-7 col-xs-12">
            <button url="create-cv/{{ $project_id }}" class="add-btn getProjectId btn btn-default pull-right btn-sm" modal-size="large" projectId="{{ $project_id }}" view-type="modal" type="button" style="margin-left: 12px;"><i class="glyphicon glyphicon-plus mr5"></i>Add New</button>
        
            @include("perPageBox") --}}
        <a href="{{ url('recruitment#rejection-report') }}" class="ajax-link hand btn btn-sm btn-default pull-right mr5"><i class="fa fa-arrow-left"></i> Back</a>   
        </div>
    </div> 
    <div id=myTabContent2 class=tab-content>
        <div class="tab-pane fade active in" id="home2">
            <div class="form-inline data-table" refresh-url="listOfCV">
                <table id="rejectionCandidateList" cellspacing="0" class="responsive table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th data="1" width="1%">No.</th>
                            <th data="1" width="10%">Name</th>
                            <th data="1" width="10%">Father Name</th>
                            <th data="1" width="10%">PP.NO.</th>
                            <th data="1" width="10%">Contact NO.</th>
                            <th data="3" width="10%">Reference</th>
                            <th data="1" width="10%">Dealer</th>
                            <th data="1" width="10%">Trade</th>
                            <th data="1" width="10%">Salary</th>
                            <th width="10%">Status</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>No.</th>
                            <th>Name</th>
                            <th>Father Name</th>
                            <th>PP.NO.</th>
                            <th>Contact NO.</th>
                            <th>Reference</th>
                            <th>Dealer</th>
                            <th>Trade</th>
                            <th>Salary</th>
                            <th>Status</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php $paginate = $candidateLists; ?>
                        @if(count($candidateLists)>0)  
                        <?php $i=0;?>
                        @foreach($candidateLists as $candidateList)
                        <?php $i++;?>
                            <tr>
                                <td>{{$sn++}}</td>
                                <td>
                                    {{ $candidateList->full_name }}
                                </td>
                                <td>
                                    {{-- <button value="{{ $project->id }}" menu-active="reports" class="btn hand btn-default btn-sm preview_button"><i class="fa fa-print"></i> Print Preview</button> --}}
                                    {{ $candidateList->father_name }}
                                </td>
                                <td>
                                    {{-- <a href="reports/candidate-report/{{$project->id}}" 
                                    menu-active="reports"  class="ajax-link hand btn btn-sm btn-default ">
                                        <i class="fa fa-eye"></i> View Report
                                    </a> --}}
                                    {{  $candidateList->passport_no }} {!! @Helper::passportExpired($candidateList->id) !!}
                                </td>
                                <td>{{ $candidateList->contact_no }}</td>
                                <td>{{  @Helper::reference($candidateList->ew_project_id)->reference_name }}</td>
                                <td></td>
                                <td>{{ @Helper::singleTrade($candidateList->selected_trade)->trade_name }}</td>
                                <td>{{ @Helper::interview_candidate_info($candidateList->ew_project_id, $candidateList->id)->salary_and_others }}</td>
                                <td>
                                    <span class="{{ @Helper::interviewSelection($candidateList->id)==1?
                                        'text-success':(@Helper::interviewSelection($candidateList->id) == 2?
                                        'text-warning':(@Helper::interviewSelection($candidateList->id)==3?
                                        'text-danger':'')) }}">
                                        {!! @Helper::interviewSelection($candidateList->id) == 1?
                                        '<strong class="text-success">Accepted</strong>':(@Helper::interviewSelection($candidateList->id) == 2?
                                        '<strong class="text-warning">Waiting</strong>':(@Helper::interviewSelection($candidateList->id) == 3?
                                        '<strong class="text-danger">Decline</strong>':'<strong class="text-info">On Operation</strong>')) !!}
                                    </span>
                                </td>
                                
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
                        {{-- @include("pagination") --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('#data-list-view').html('');

    $('.getProjectId').on('click', function(){
        var project_id = $(this).attr('projectId');
       $.ajax({
            url: '{{ route('recruit.recruitment.interview.create') }}',
            type: 'get',
            data: {project_id:project_id},
            success:function(response){
                $('#ew_project_id').val(project_id);
                $('.btn-success').addClass('saveButton');
                $('.btn-success').on('click', function(){
                $('.panel-refresh').trigger('click');
               });

            }
            
        });
    });


    $('#rejectionCandidateList').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'csv', 'excel', 'pdf', 'print'
        ]
    });

        $(".select2").select2({
            placeholder: "Select"
            });
     
</script>