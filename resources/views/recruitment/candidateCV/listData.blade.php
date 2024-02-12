<div class="form-inline">
  <div class="row datatables_header">
    <div class="col-md-2 col-xs-12" style="width:14% !important;">
      <div class="input-group">
        <input name="search" event="enter" class="data-search form-control" id="search-input" value="{{@$search}}"
          kl_virtual_keyboard_secure_input="on" placeholder="Search">
        <span class="input-group-btn"><button name="search" event="click" valueFrom="#search-input"
            class="data-search btn btn-default" type="button">Go</button></span>
      </div>
    </div>
    <div class="col-md-2 col-xs-12" style="width:14% !important;">
        <select id="search_trade" name="search_trade" event="change" class="select2 form-control ml0 data-search">
            <option value="0">All Trade</option>
            @foreach($trades as $trade)
              <option value="{{ $trade->id }}" @if($selectedTradeId==$trade->id){{'selected'}}@endif>{{ $trade->trade_name }}</option>
            @endforeach
        </select>
    </div>
    {{-- <div class="col-md-2 col-xs-12 pull-left" style="width:15% !important;">
        <select id="search_reference" name="search_reference" event="change" class="select2 form-control ml0 data-search">
            <option value="0">All Reference</option>
            @foreach($references as $reference)
              <option value="{{ $reference->id }}" @if($selectedRefId==$reference->id){{'selected'}}@endif>{{ $reference->reference_name }}</option>
            @endforeach
        </select>
    </div> --}}
    <div class="col-md-2 col-xs-12 pull-left" style="width:14% !important;">
        <select id="search_dealer" name="search_dealer" event="change" class="select2 form-control ml0 data-search">
            <option value="0">All Dealer {{@$selectedDealerId}}</option>
            @foreach ($dealers as $dealer)
                <option value="{{ $dealer->id }}" @if($selectedDealerId == $dealer->id){{'selected'}}@endif>
                  {{ $dealer->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2 col-xs-12 pull-left" style="width:14% !important;">
        <select id="search_project" name="search_project" event="change" class="select2 form-control ml0 data-search">
            <option value="0">All Project</option>
            {{-- <option value="a" @if($selecteProjectId=="a"){{'selected'}}@endif>Valid Project</option> --}}
            <option value="b" @if($selecteProjectId=="b"){{'selected'}}@endif>CV Bank</option>
            <option value="c" @if($selecteProjectId=="c"){{'selected'}}@endif>Deployed</option>
            @foreach ($projects as $project)
                <option value={{$project->id}} @if($selecteProjectId== $project->id){{'selected'}}@endif>{{$project->project_name}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2 col-xs-12 pull-left" style="width:24% !important;">
      <button class="btn btn-default btn-sm">Total CV <strong class="text-primary">({{ $totalCV }})</strong>
      </button>
      <a href="{{$pdf_url}}" class="btn btn-default" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> PDF</a>
      <a href="{{$excel_url}}" class="btn btn-default" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Excel</a>
    </div>
    <div class="col-md-2 col-xs-12 pull-right" style="width:16% !important;">
      @if ($access->create)
      <button class="add-btn btn btn-default pull-right btn-sm" type="button" style="margin-left: 12px;"><i
          class="glyphicon glyphicon-plus mr5"></i>Add New</button>
      @endif
      
      @include("perPageBox")
    </div>
  </div>
</div>
<div id=myTabContent2 class=tab-content>
  <div class="tab-pane fade active in" id=home2>
    <div class="form-inline">
      <table cellspacing="0" class="responsive table table-striped table-bordered" id="candidateCvData">
        <thead>
          <tr>
            <th width="4%">SL.No.</th>
            <th data="2" width="10%">CV No.</th>
            <th data="3" width="15%">Name</th>
            <th data="4" width="10%">PP.No.</th>
            <th data="5" width="10%">Project</th>
            <th width="8%">Trade</th>
            <th width="8%">Reference</th>
            <th width="8%">Dealer</th>
            <th width="8%">Contact No</th>
            <th width="3%">Home Exp</th>
            <th width="3%">Ovr Exp</th>
            <th width="8%">CV Status</th>
            @if($access->edit || $access->destroy)
            <th width="8%">Action</th>
            @endif
          </tr>
        </thead>
        <tfoot>
          <tr>
            <th>SL.No.</th>
            <th>CV No.</th>
            <th>Name</th>
            <th>PP. No.</th>
            <th>Project</th>
            <th>Trade</th>
            <th>Reference</th>
            <th>Dealer</th>
            <th>Contact No.</th>
            <th>Home Exp</th>
            <th>Ovr Exp</th>
            <th>CV Status</th>

            @if ($access->edit || $access->destroy)

            <th>Action</th>

            @endif

          </tr>
        </tfoot>
        <tbody class="text-center">
          <?php $paginate = $candidateCVs; ?>

          @if (count($candidateCVs)>0)

          @foreach ($candidateCVs as $candidateCV)

          <tr>
            <td>{{$sn++}}</td>
            <?php $trades = 1; ?>
            <td>{{ $candidateCV->cv_number }}</td>
            <td>{{ $candidateCV->full_name }}</td>
            <td>
              {{ $candidateCV->passport_no }} {!! @Helper::passportExpired($candidateCV->id) !!}
              <p style="margin-bottom: 0;">NID:</p> 
              @if(!empty($candidateCV->national_id)) {{$candidateCV->national_id}} @else N/A @endif
            </td>
            <td>@if(!empty($candidateCV->ew_project_id)) {{ @Helper::projects($candidateCV->ew_project_id)->project_name }} @else CV Bank @endif</td>
            <td>
              <?php 
                $trades = (object)json_decode($candidateCV->trade, true);
                $totalTrades = count((array)$trades);
                $i=1;
              ?>
              @if (!empty($candidateCV->selected_trade))
                {{Helper::singleTrade($candidateCV->selected_trade)->trade_name}}
              @elseif($totalTrades > 0)
                @foreach ($trades as $trade)
                  {{Helper::singleTrade($trade)->trade_name}}
                  <?php if ($i<$totalTrades) { echo ","; } $i++;?>
                @endforeach
              @else 
                  No Trade
              @endif

            </td>
            <td>{{ @Helper::reference($candidateCV->reference_id)->reference_name }}</td>
            
            <td>
              <?php 
                 // $dealers = (object)json_decode($candidateCV->dealer, true);
                  //$i=1;
                  //$totalDealers = count((array)$dealers);
              ?>

              {{ @Helper::dealer($candidateCV->dealer_id)->name }}
              

            </td>
            <td>{{ $candidateCV->contact_no }}</td>
            <td>
              <?php 
                $homeExp = (object)json_decode($candidateCV->total_home_exp, true);
                $totalHomeExp = 0;
                foreach($homeExp as $hExp){
                  $totalHomeExp+= $hExp;
                }
                echo $totalHomeExp;
              ?>
            </td>
            <td>
              <?php 
                $homeExp = (object)json_decode($candidateCV->total_overs_exp, true);
                $totalOvrExp = 0;
                foreach($totalOvrExp as $OExp){
                  $totalOvrExp+= $OExp;
                }
                echo $totalOvrExp;
              ?>
            </td>
            <td>
              <?php 
                $deployDate = @Helper::deployDate($candidateCV->ew_project_id,$candidateCV->id);
              ?>

              {!! @Helper::flightCompleted($candidateCV->ew_project_id,$candidateCV->id) == 1 &&
              $candidateCV->approved_status == 1
              ? '<span class="text-danger"><b style="font-size:15px">Deployed</b></span></br><span style="color:green"><b>'.$deployDate.'</b></span>'
              : ($candidateCV->approved_status == 1
              ? '<span class="text-primary"><strong>Work In Progress</strong></span>'
              : '<span class="text-primary"><strong>Mobilize is Not Started!</strong></span>') !!}
              <hr>
              <span>
                {!! $candidateCV->result == 1
                ? '<strong class="text-success"><b>Pass</b></strong>'
                : ($candidateCV->result == 2
                ? '<strong class="text-warning">Fail</strong>'
                : ($candidateCV->result == 3
                ? '<strong class="text-danger">Waiting</strong>'
                : ($candidateCV->result == 4
                ? '<strong class="text-info">Hold</strong>'
                : ($candidateCV->result == 5
                ? '<strong class="text-danger">Decline</strong>'
                : '')))) !!}
              </span>
            </td>
            <td class="text-center">

              @if ($access->edit || $access->destroy)

              @if ($access->edit)<i class="fa fa-edit" title="Update" id="edit" data="{{$candidateCV->id}}"></i>

              @endif

              @if ($access->destroy)
                @if (@Helper::flightCompleted($candidateCV->ew_project_id,$candidateCV->id) != 1 &&
                $candidateCV->approved_status != 1)
                  <i class="fa fa-trash-o" id="delete" data="{{$candidateCV->id}}"></i>
                @endif
              @endif

              <br>
              <button value="{{ $candidateCV->id }}" {{ $candidateCV->cv_transferred_status != 1?'disabled':'' }}
                class=" btn btn-default btn-xs preview_button"><i class="fa fa-print"></i> Print
                Preview</button>

              <button url="cv-moved-form/{{ $candidateCV->id }}" class="add-btn btn {{ @Helper::mobilizeFinalCompletedStatus($candidateCV->id) == 0 && $candidateCV->cv_transferred_status == 1  ? 
                    'btn-default' 
                    :'btn-success' }}  btn-xs mt5" view-type="modal" type="button" {{ @Helper::mobilizeFinalCompletedStatus($candidateCV->id) == 0 && $candidateCV->cv_transferred_status == 1 
                    ? 'disabled' 
                    : '' }}>
                            
                @if (@Helper::mobilizeFinalCompletedStatus($candidateCV->id) == 0 &&
                $candidateCV->cv_transferred_status == 1)

                <span><strong>CV Transfered</strong></span>

                @else

                <i class="glyphicon glyphicon-plus mr5"></i>
                Transfer to Inteview

                @endif

              </button>
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
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  $(".select2").select2({
      placeholder: "Select"
  });

  $(document).ready(function () {
    $(".preview_button").on('click', function (e) {
      e.preventDefault();
      var candidate_id = $(this).val();
      // alert(project);
      var width = $(document).width();
      var height = $(document).height();
      var previewType = 1;
      var url = "{{ url('recruitment/cv-print-preview') }}?candidate_id=" + candidate_id;
      var myWindow = window.open(url, "", "width=" + width + ",height=" + height);
      $('.preview_button').removeAttr('disabled').removeClass('disabled');
    });
  });
</script>