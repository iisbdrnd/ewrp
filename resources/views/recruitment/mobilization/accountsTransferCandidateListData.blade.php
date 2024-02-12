<div class="form-inline">
    <div class="row datatables_header">
        <div class="col-md-3 col-xs-12">
            <div class="input-group">
                <input name="search" event="enter" class="data-search form-control" id="search-input" value="{{@$search}}" kl_virtual_keyboard_secure_input="on" placeholder="Search">
                <span class="input-group-btn"><button name="search" event="click" valueFrom="#search-input" class="data-search btn btn-default" type="button">Go</button></span>
            </div>
        </div>
        <div class="col-md-2 col-xs-12 pl0">
            <select name="cv_transfer_status" event="change" class="data-search form-control select2" >
                <option {{ $cv_transfer_status == 0?"selected":"" }} value="0">All </option>
                <option {{ $cv_transfer_status == 1?"selected":"" }} value="1">Collectable Selection</option>
                <option {{ $cv_transfer_status == 2?"selected":"" }} value="2">Not Collectable Selection</option>
            </select>
        </div>
        <div class="col-md-7 col-xs-12">
           {{--  @if($access->create)
                <button class="add-btn btn btn-default pull-right btn-sm" type="button" style="margin-left: 12px;"><i class="glyphicon glyphicon-plus mr5"></i>Add New</button>
            @endif --}}
            @include("perPageBox")
            <a href="{{ url('recruitment#mobilization') }}" style="margin-right: 12px;" class="ajax-link btn btn-default pull-right btn-sm"><i
              class="fa fa-arrow-left"></i> Back</a>

            <a href="{{ url('eastWest#candidate-info-list/'.$projectId) }}" class="btn btn-default pull-right btn-sm" type="a" style="margin-right: 12px;"><i class="fa fa-arrow-left"></i> Accounts Transferred 
            ({{ @Helper::accountTransferred(@Helper::projects($projectId)->id, 1) }})
            </a>
            
        </div>
    </div>
        <table id="myTable" cellspacing="0" class="responsive table table-striped table-bordered">
          <thead>
            <tr>
              <th width="1%"  data="1">No.</th>
              <th width="4%"  data="1">CV Number</th>
              <th width="5%"  data="1">Candidate Name</th>
              <th width="5%"  data="1">Father's Name</th>
              <th width="10%" data="1">Passport Number</th>
              <th width="10%" data="1">Selected Trade</th>
              <th width="10%" data="1">Salary</th>
              <th width="10%" data="1">Reference</th>
              <th width="20%" data="1">Dealer</th>
              <th width="10%" data="1">Candidate Contact No.</th>
              <th width="10%" data="1">Passport Status</th>
              <th width="5%"  data="1">Action</th>
              {{-- <th width="9%" width="" data="1">Mobilize Activity</th> --}}
              {{-- <th width="9%" width="" data="1">Pipeline</th> --}}
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th>No.</th>
              <th>CV Number</th>
              <th>Candidate Name</th>
              <th>Father's Name</th>
              <th>Passport Number</th>
              <th>Selected Trade</th>
              <th>Salary</th>
              <th>Reference</th>
              <th>Dealer</th>
              <th>Candidate Contact No.</th>
              <th>Passport Status</th>
              <th>Action</th>
              {{-- <th>Mobilize Activity</th> --}}
              {{-- <th>Pipeline</th> --}}
            </tr>
          </tfoot>
          <tbody>
            <?php $paginate = $candidateDetails; $i=0; ?>
            @forelse($candidateDetails as $candidateDetail)
            <?php $i++;?>
            <tr>
              <input type="hidden" value="{{ @Helper::mcStatus($projectId,$candidateDetail->id, $mobilizeId)['data'] != null?@Helper::mcStatus($projectId,$candidateDetail->id, $mobilizeId)["data"]:0  }}" id="mobilize_data">
              <td>{{ $i }}</td>
              <td>{{ $candidateDetail->cv_number }}</td>
              <td>
                <a href=""
                  menu-active="mobilization" class="ajax-link">{{ $candidateDetail->full_name }}
                </a>
                {{-- mobilization/single-candidate/{{$candidateDetail->ew_project_id}}/{{ $candidateDetail->id }} --}}
              </td>
              <td>{{ $candidateDetail->father_name }}</td>
              <td>{{ $candidateDetail->passport_no }}</td>
              <td>
                @if($candidateDetail->selected_trade != 0)
                  {{ @Helper::singleTrade($candidateDetail->selected_trade)->trade_name }}
                @else 
                <span class="text-danger">Update selected trade</span>
                @endif
                </td>
              <td>{{ @Helper::interviewTable($candidateDetail->ew_project_id, $candidateDetail->id)->salary }}</td>
              <td>{{ @Helper::reference($candidateDetail->reference_id)->reference_name }}</td>
              <td>
               @if (!empty(json_decode(@Helper::reference($candidateDetail->reference_id)->dealer, true)) && json_decode(@Helper::reference($candidateDetail->reference_id)->dealer, true) != null)

                    @foreach(json_decode(@Helper::reference($candidateDetail->reference_id)->dealer, true) as $dealerId)
                    {{ @Helper::dealer($dealerId)->name }}
                    @endforeach

                    @endif
              </td>
              <td>{{ $candidateDetail->contact_no }}</td>
              <td>
                {{ $candidateDetail->passport_status == 1?'In Office':($candidateDetail->passport_status == 2?'Yes':($candidateDetail->passport_status == 3?'No':'')) }}
              </td>
              <!-- mobilizationModalView() function define below -->
              <td>
                @if($candidateDetail->candidate_status == 0)
                @if($candidateDetail->selected_trade != 0)
               <button type="button" onclick="transferToAccounts({{ $projectId }},{{ $candidateDetail->id }})" class="btn btn-md btn-success">Transfer To Accounts</button>
               @else 
               <button  url="updateSelectedTradeForm?projectId={{ $projectId }}&candidateId={{ $candidateDetail->id }}" view-type="modal" modal-size="medium" class="add-btn btn btn-md btn-danger">Update Selected Trade</button>
               @endif
               @else 
               <button type="button" class="btn btn-md btn-default">Candidate Transferred</button>
               @endif
              </td>
            </tr>
            @empty
            <tr>
              <td style="border:0px;"></td>
              <td style="border:0px;"></td>
              <td style="border:0px;"></td>
              <td style="border:0px;"></td>
              <td style="border:0px;"></td>
              <td style="border:0px;"></td>
              <td style="border:0px;"></td>
              <td style="border:0px;"></td>
              <td style="border:0px;"></td>
              <td style="border:0px;"></td>
              <td style="border:0px;"></td>
              <td style="border:0px;"></td>
            </tr>
            @endforelse
          </tbody>
        </table>
        <div class="row">
          <div class="col-md-12 col-xs-12">
            @include("pagination")
          </div>
        </div>
</div>
{{ csrf_field() }}
<script>

function transferToAccounts(projectId, candidateId){
    console.log(candidateId+" "+projectId);
    $.confirm({
        title: 'Confirm!',
        content: 'Are you sure want to transfer this candidate to accounts?Yes/No',
        buttons: {
            confirm: function () {
              $.alert({
                    title: 'Congratulation!',
                    content: 'Your candidate is transferred!',
                });
                $.post('{{ route('recruit.create-candidate') }}',{"projectId":projectId, "candidateId":candidateId, "_token":$("input[name=_token]").val()}, function(data){
                $.gritter.add({
                    title: "Done !!!",
                    text: data.messege,
                    time: "",
                    close_icon: "entypo-icon-cancel s12",
                    icon: "icomoon-icon-checkmark-3",
                    class_name: "success-notice"
                });
                $('.panel-refresh').trigger('click');
            });  
            },
            // cancel: function () {
            //     $.alert('Canceled!');
            // },
            somethingElse: {
                text: 'Cancel',
                btnClass: 'btn-red',
                keys: ['enter', 'shift'],
                cancel: function(){
                  
                }
            }
        }
    });
}
</script>