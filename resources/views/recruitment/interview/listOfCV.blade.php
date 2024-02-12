<style>
.modal-dialog{
    width: 95% !important;
}
/*.saveButton{
    display:none;
} */       
</style>
<div class="form-inline">
  <div class="row datatables_header">
    <div class="col-md-5 col-xs-12">
      <div class="input-group">
        <input name="search" event="enter" class="data-search form-control" id="search-input" value="{{@$search}}"
          kl_virtual_keyboard_secure_input="on" placeholder="Search">
        <span class="input-group-btn"><button name="search" event="click" valueFrom="#search-input"
            class="data-search btn btn-default" type="button">Go</button></span>
      </div>
      <button type="button" value="{{ $projectId }}" class=" btn btn-default  btn-sm cv-info-print mb10 ml10"
        style="background:#39825A;color:#fff;border:1px solid #207245;"><i class="fa fa-file-excel-o"
          aria-hidden="true"></i> CV Excel Preview</button>
    </div>
    <div class="col-md-7 col-xs-12">
      <button type="button" url="create-cv/{{ $projectId }}/{{$projectCountryId}}"
        class="add-btn getProjectId btn btn-default pull-right btn-sm" modal-size="large" projectId="{{ $projectId }}"
        view-type="modal" style="margin-left: 12px;"><i class="glyphicon glyphicon-plus mr5"></i>Add New</button>
      <a href="{{ url('recruitment#interview') }}" style="margin-left: 12px;"
        class="ajax-link btn btn-default pull-right btn-sm"><i class="fa fa-arrow-left"></i> Back</a>
      @include("perPageBox")
    </div>
  </div>
</div>
<div id=myTabContent2 class=tab-content>
  <div class="tab-pane fade active in" id=home2>
    <div class="form-inline data-table" refresh-url="listOfCV?projectId={{ $project_id }}">
      <table cellspacing="0" class="responsive table table-striped table-bordered">
        <thead>
          <tr>
            <th data="1" width="1%">No.</th>
            <th data="1" width="10%">CV No.</th>
            <th data="1" width="10%">Name</th>
            <th data="1" width="10%">Father Name</th>
            <th data="1" width="10%">PP.NO.</th>
            <th data="1" width="10%">Contact NO.</th>
            <th data="3" width="10%">Reference</th>
            <th data="1" width="10%">Dealer</th>
            <th data="1" width="10%">S.Trade</th>
            <th data="1" width="10%">Salary</th>
            <th width="10%">Status</th>
            @if($access->edit || $access->destroy)
            <th width="9%">Action</th>
            @endif
          </tr>
        </thead>
        <tfoot>
          <tr>
            <th>No.</th>
            <th>CV No.</th>
            <th>Name</th>
            <th>Father Name</th>
            <th>PP.NO.</th>
            <th>Contact NO.</th>
            <th>Reference</th>
            <th>Dealer</th>
            <th>S.Trade</th>
            <th>Salary</th>
            <th>Status</th>
            @if($access->edit || $access->destroy)
            <th>Action</th>
            @endif
          </tr>
        </tfoot>
        <tbody class="text-center">
          <?php $paginate = $CVLists; ?>
          @if(count($CVLists)>0)
          @foreach($CVLists as $CVList)
          <tr>
            <td>{{$sn++}}</td>
            <td>{{ $CVList->cv_number }}</td>
            <td>{{ $CVList->full_name }}</td>
            <td>{{ $CVList->father_name }}</td>
            <td>{{ $CVList->passport_no }} {!! Helper::passportExpired($CVList->id) !!}</td>
            <td>{{ $CVList->contact_no }}</td>
            <td>{{ @Helper::reference($CVList->reference_id)->reference_name }}</td>
            <td>
                <?php ///$rf = json_decode(Helper::reference($CVList->reference_id)->dealer); ?>
                {{-- @if(!empty($rf) && $rf != NULL)
                  @foreach(json_decode(Helper::reference($CVList->reference_id)->dealer, true) as $dealerId)
                  {{ Helper::dealer($CVList->dealer)->name }}
                  @endforeach
                @endif  --}}

                 {{ Helper::dealer($CVList->dealer)->name }}
            </td>
            <td>{{ @Helper::singleTrade($CVList->selected_trade)->trade_name }}</td>
            <td>{{ @Helper::interviewTable($CVList->ew_project_id, $CVList->id)->salary }}</td>
            <td>
              <span>
                {!! $CVList->result == 1 
                ? '<strong class="text-success">Pass</strong>'
                : ($CVList->result == 2 
                ? '<strong class="text-warning">Fail</strong>' 
                : ($CVList->result == 3 
                ? '<strong class="text-danger">Waiting</strong>' 
                : ($CVList->result == 4 
                ? '<strong class="text-info">Hold</strong>'
                : ($CVList->result == 5 
                ? '<strong class="text-danger">Decline</strong>'
                : '')))) !!} 
              </span>
            </td>
            @if($access->edit || $access->destroy)
            <td class="text-center">
              @if($access->edit)
              <i class="fa fa-edit" view-type="modal" modal-size="large" title="Update" id="edit"
                data="{{$CVList->id}}"></i>
              @endif
              @if($access->destroy)
              @if (@Helper::flightCompleted($CVList->ew_project_id,$CVList->id) != 1 &&
              $CVList->approved_status != 1)
              <i class="fa fa-trash-o" id="delete" data="{{$CVList->id}}"></i>
              @endif
              @endif
              @if(strstr($interviewCallProjectName, 'L&T'))
                <button value="{{ $CVList->id }}" class=" btn btn-default btn-xs preview_button_lt mt5"><i
                    class="fa fa-print"></i> Print Preview</button>
              @else 
                <button value="{{ $CVList->id }}" class=" btn btn-default btn-xs preview_button_els mt5"><i
                class="fa fa-print"></i> Print Preview</button>
              @endif
            </td>
            @endif
          </tr>
          @endforeach
          @else
          <tr>
            <td colspan="13" class="emptyMessage">Empty</td>
          </tr>
          @endif
        </tbody>
      </table>
      <div class="row">
        <div class="col-md-12 col-xs-12">
          @include("pagination")
          {{-- {!! $CVLists->render() !!} --}}
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
    // $('#data-list-view').html('');
    // $('#perPage').find('option:eq(10)').prop('selected', true);
    // $('#perPage').find('option:eq(50)').prop('selected', false);
    // $('.getProjectId').on('click', function(){
    //     var project_id = $(this).attr('projectId');
    //    $.ajax({
    //         url: '',
    //         type: 'get',
    //         data: {project_id:project_id},
    //         success:function(response){
    //             $('#ew_project_id').val(project_id);
    //             $('.btn-success').addClass('saveButton');
    //             $('.btn-success').on('click', function(){
    //             $('.panel-refresh').trigger('click');
    //            });

    //         }
            
    //     });
    // });
    $(document).ready(function(){
    $(".preview_button_lt").on('click', function(e) {
          e.preventDefault();
          var candidate_id = $(this).val();
          var width        = $(document).width();
          var height       = $(document).height();
          var previewType  = 1;
          var url          = "{{ url('recruitment/interview-evaluation-form') }}?candidate_id="+candidate_id;
          var myWindow     = window.open(url, "", "width="+width+",height="+height);
          $('.preview_button_lt').removeAttr('disabled').removeClass('disabled');
      });  

    $(".preview_button_els").on('click', function(e) {
          e.preventDefault();
          var candidate_id = $(this).val();
          // alert(project);
          var width        = $(document).width();
          var height       = $(document).height();
          var previewType  = 1;
          var url          = "{{ url('recruitment/worker-evaluation-form') }}?candidate_id="+candidate_id;
          var myWindow     = window.open(url, "", "width = "+width+",height = "+height);
          $('.preview_button_els').removeAttr('disabled').removeClass('disabled');
      });  
      

    $(".cv-info-print").on('click', function(e) {
            e.preventDefault();
            var projectId   = $(this).val();
            // alert(project);
            var width       = $(document).width();
            var height      = $(document).height();
            var previewType = 1;
            var url         = "{{ url('recruitment/cv-info-print') }}?projectId="+projectId;
            var myWindow    = window.open(url, "", "width="+width+",height="+height);
            $('.cv-info-print').removeAttr('disabled').removeClass('disabled');
        });   
    });
 
</script>
